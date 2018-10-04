<?php declare(strict_types=1);

namespace CryptoSim\Simulation\Presentation;

use CryptoSim\Simulation\Application\SaveTransaction;
use CryptoSim\Simulation\Application\SaveTransactionHandler;
use CryptoSim\Simulation\Domain\GetCryptocurrenciesQuery;
use CryptoSim\Simulation\Domain\PortfolioRepository;
use CryptoSim\Simulation\Domain\Transaction;
use CryptoSim\Simulation\Domain\TransactionRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use CryptoSim\Framework\Rendering\TemplateRenderer;

final class SimulationController
{
    private $templateRenderer;
    private $portfolioRepository;
    private $getCryptocurrenciesQuery;
    private $saveTransactionHandler;

    public function __construct(
        TemplateRenderer $templateRenderer,
        PortfolioRepository $portfolioRepository,
        GetCryptocurrenciesQuery $getCryptocurrenciesQuery,
        SaveTransactionHandler $saveTransactionHandler
    ) {
        $this->templateRenderer = $templateRenderer;
        $this->portfolioRepository = $portfolioRepository;
        $this->getCryptocurrenciesQuery = $getCryptocurrenciesQuery;
        $this->saveTransactionHandler = $saveTransactionHandler;
    }

    public function show(Request $request, array $vars) {
        $portfolioId = $vars['portfolioId'];
        $template = 'Simulation.html.twig';

        //TODO - Validate the $portfolioId once you get the portfolio data from database
        $portfolio = $this->portfolioRepository->getPortfolioFromId($portfolioId);
        $cryptocurrencies = $this->getCryptocurrenciesQuery->execute();

        if(!$portfolio) {
            $template = 'PageNotFound.html.twig';
        }

        $content = $this->templateRenderer->render($template, [
            'portfolioId' => $portfolioId,
            'portfolio' => $portfolio,
            'cryptocurrencies' => $cryptocurrencies
        ]);
        return new Response($content);
    }

    // TODO - Use a more secure way of getting the portfolio ID
    public function saveTransaction(Request $request, array $vars)
    {
        $portfolioId = $vars['portfolioId'];
//        $response = new RedirectResponse("/play/{$portfolioId}");

        $transactionAmount = ((string)$request->get('type') == "buy") ? (string)(-1 * (string)$request->get('transaction-amount')) : (string)$request->get('transaction-amount');
        $saveTransaction = new SaveTransaction(
            $portfolioId,
            (int)$request->get('cryptocurrency-id'),
            $transactionAmount,
            (string)$request->get('type')
        );
        $this->saveTransactionHandler->handle($saveTransaction);

        return $this->getUpdatedPortfolioResponse($portfolioId);
    }

    public function getUpdatedPortfolio(Request $request, array $vars)
    {
        $portfolioId = $vars['portfolioId'];

        return $this->getUpdatedPortfolioResponse($portfolioId);
    }

    // API
    public function getBuyCryptoData() {
        $cryptocurrencies = $this->getCryptocurrenciesQuery->apiExecute();

        if(!$cryptocurrencies) {
            // TODO - Handle error
        }

        $response = new Response();
        $response->setContent(
            json_encode($cryptocurrencies)
        );
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    private function getUpdatedPortfolioResponse($portfolioId)
    {
        $updatedPortfolio = $this->portfolioRepository->getPortfolioFromId($portfolioId);
        $cryptocurrencies = $this->getCryptocurrenciesQuery->execute();

        $template = 'responses/PortfolioCrypto.html.twig';
        if(!$updatedPortfolio) {
            $template = 'PageNotFound.html.twig';
        }

        $content = $this->templateRenderer->render($template, [
            'portfolioId' => $portfolioId,
            'portfolio' => $updatedPortfolio,
            'cryptocurrencies' => $cryptocurrencies
        ]);
        // TODO - Add error handling before returning response
        $response = new Response();
        $response->setContent(json_encode(array(
            'content' => $content,
            'updatedPortfolio' => $updatedPortfolio->jsonify(),
            'cryptocurrencies' => $cryptocurrencies
        )));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}