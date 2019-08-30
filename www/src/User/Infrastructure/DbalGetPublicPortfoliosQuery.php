<?php declare(strict_types=1);

namespace CryptoSim\User\Infrastructure;

use CryptoSim\Framework\DbMapping\Portfolios\PortfoliosMapper;
use CryptoSim\User\Application\Portfolio;
use CryptoSim\User\Application\GetPublicPortfoliosQuery;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class DbalGetPublicPortfoliosQuery implements GetPublicPortfoliosQuery
{
    private $connection;
    private $session;
    private $portfoliosMapper;

    public function __construct(
        Connection $connection,
        SessionInterface $session,
        PortfoliosMapper $portfoliosMapper
    ) {
        $this->connection = $connection;
        $this->session = $session;
        $this->portfoliosMapper = $portfoliosMapper;
    }

    public function execute(string $userId): array
    {
        $qb = $this->connection->createQueryBuilder();
        $stmt = $qb
            ->addSelect('id')
            ->addSelect('type')
            ->addSelect('title')
            ->from('portfolios')
            ->where("user_id = {$qb->createNamedParameter($userId)}")
            ->andWhere("visibility = 'public'")
            ->andWhere("status = 'open'")
            ->execute()
        ;

        $rows = $stmt->fetchAll();

        $portfolios = [];
        foreach ($rows as $row){
            try {
                $portfolios[] = new Portfolio(
                    $row['id'],
                    $row['type'],
                    $row['title'],
                    $this->portfoliosMapper->getPortfolioWorth($row['id'])
                );
            } catch (\Exception $e) {
                // TODO - Handle exception
                throw $e;
            }
        }

        return $portfolios;
    }
}