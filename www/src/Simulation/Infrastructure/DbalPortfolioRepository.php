<?php declare(strict_types=1);

namespace CryptoSim\Simulation\Infrastructure;

use CryptoSim\Simulation\Application\Portfolio;
use CryptoSim\Simulation\Domain\PortfolioRepository;
use Doctrine\DBAL\Connection;

final class DbalPortfolioRepository implements PortfolioRepository
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getPortfolioFromId(string $portfolioId): Portfolio
    {
        $qb = $this->connection->createQueryBuilder();
        $stmt = $qb
            ->addSelect('id')
            ->addSelect('title')
            ->from('portfolios')
            ->where("id = {$qb->createNamedParameter($portfolioId)}")
            ->execute()
        ;

        $row = $stmt->fetch();

        if(!$row) {
            return null;
        }

        $portfolioUSDAmount = $this->getPortfolioUSDAmountFromId($portfolioId);
        $portfolioCryptoWorthInUSD = $this->getPortfolioCryptoWorthInUSDFromId($portfolioId);
        $portfolioWorth = (string)($portfolioUSDAmount + $portfolioCryptoWorthInUSD);

        return new Portfolio(
            $row['id'],
            $row['title'],
            $portfolioUSDAmount,
            $portfolioCryptoWorthInUSD,
            $portfolioWorth
        );
    }

    private function getPortfolioCryptoWorthInUSDFromId(string $portfolioId): ?string
    {
//        $qb = $this->connection->createQueryBuilder();
        $stmt = $this->connection->prepare(
            "SELECT (
                        (SELECT SUM(t.usd_amount) FROM transactions t WHERE type = 'buy' AND t.portfolio_id = :portfolioId1 AND status='active')
                         - (SELECT SUM(t.usd_amount) FROM transactions t WHERE type = 'sell' AND t.portfolio_id = :portfolioId2 AND status='active')) 
                         AS crypto_amount
         ");
        $stmt->bindParam(':portfolioId1', $portfolioId);
        $stmt->bindParam(':portfolioId2', $portfolioId);
        $stmt->execute();

        $row =$stmt->fetch();

        if(!$row) {
            return null; // TODO - Add error catching for when this returns NULL
        }

        return $row['crypto_amount'];
    }


    private function getPortfolioUSDAmountFromId(string $portfolioId): ?string
    {
        // TODO - Make these queries readable
        $stmt = $this->connection->prepare(
            "SELECT (
                        portfolios.start_amount -
                        (
                          (SELECT SUM(t.usd_amount) FROM transactions t WHERE type = 'buy' AND t.portfolio_id = :portfolioId1 AND status='active')
                        - (SELECT SUM(t.usd_amount) FROM transactions t WHERE type = 'sell' AND t.portfolio_id = :portfolioId2 AND status='active')
                        )
                     ) AS usd_amount
                     FROM portfolios"
        );
        $stmt->bindParam(':portfolioId1', $portfolioId);
        $stmt->bindParam(':portfolioId2', $portfolioId);
        $stmt->execute();

        $row =$stmt->fetch();

        if(!$row) {
            return null; // TODO - Add error catching for when this returns NULL
        }

        return $row['usd_amount'];
    }

//    private function getPortfolioWorth(string $portfolioId): string
//    {
//        $USDAmount = $this->getPortfolioUSDAmountFromId($portfolioId);
//        $cryptoAmountInUSD = $this->getPortfolioCryptoAmountInUSDFromId($portfolioId);
//
//        return $USDAmount + $cryptoAmountInUSD;
//    }
}