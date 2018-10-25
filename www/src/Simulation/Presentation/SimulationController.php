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
use Symfony\Component\HttpFoundation\Session\Session;

final class SimulationController
{
    private $templateRenderer;
    private $portfolioRepository;
    private $getCryptocurrenciesQuery;
    private $saveTransactionHandler;
    private $session;

    public function __construct(
        TemplateRenderer $templateRenderer,
        PortfolioRepository $portfolioRepository,
        GetCryptocurrenciesQuery $getCryptocurrenciesQuery,
        SaveTransactionHandler $saveTransactionHandler,
        Session $session
    ) {
        $this->templateRenderer = $templateRenderer;
        $this->portfolioRepository = $portfolioRepository;
        $this->getCryptocurrenciesQuery = $getCryptocurrenciesQuery;
        $this->saveTransactionHandler = $saveTransactionHandler;
        $this->session = $session;
    }

    public function show(Request $request, array $vars) {
        $portfolioId = $vars['portfolioId'];
        $userId = $this->session->get('userId');
        $template = 'Simulation.html.twig';

        //TODO - Validate the $portfolioId once you get the portfolio data from database
        $portfolio = $this->portfolioRepository->getPortfolioFromId($portfolioId, $userId); // Commented out since I changed the parameters of getPortfolioFromId
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
        $userId = $this->session->get('userId');
//        $response = new RedirectResponse("/play/{$portfolioId}");

        $transactionAmount = ((string)$request->get('type') == "buy") ? (string)(-1 * (string)$request->get('transaction-amount')) : (string)$request->get('transaction-amount');
        $saveTransaction = new SaveTransaction(
            $portfolioId,
            (int)$request->get('cryptocurrency-id'),
            $transactionAmount,
            (string)$request->get('type')
        );
        $this->saveTransactionHandler->handle($saveTransaction);

        return $this->getUpdatedPortfolioAPI($portfolioId, $userId);
    }

    public function getUpdatedPortfolio(Request $request, array $vars)
    {
        $portfolioId = $vars['portfolioId'];
        $userId = $this->session->get('userId');

        return $this->getUpdatedPortfolioResponse($portfolioId, $userId);
    }

    // API
    public function saveTransactionAPI(Request $request, array $vars) {
//        $portfolioId = $vars['portfolioId'];
        $portfolioId = $request->get('portfolio-id');
        $userId = $this->session->get('userId');
//        $response = new RedirectResponse("/play/{$portfolioId}");

        $transactionAmount = ((string)$request->get('type') == "buy") ? (string)(-1 * (string)$request->get('transaction-amount')) : (string)$request->get('transaction-amount');
        $saveTransaction = new SaveTransaction(
            $portfolioId,
            (int)$request->get('cryptocurrency-id'),
            $transactionAmount,
            (string)$request->get('type')
        );
        $this->saveTransactionHandler->handle($saveTransaction);

        return $this->getUpdatedPortfolioResponse($portfolioId, $userId);
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

    // API
    public function getPortfolio(Request $request)
    {
        $portfolioId = $request->get('portfolio-id');
        $userId = $this->session->get('userId');

        $portfolio = $this->portfolioRepository->getPortfolioFromId($portfolioId, $userId);

        $response = new Response(json_encode($portfolio->jsonify()));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    //API
    public function getUpdatedPortfolioAPI(Request $request, array $vars)
    {
        $portfolioId = $vars['portfolioId'];
        $userId = $this->session->get('userId');

        // Copied from getUpdatedPortfolioResponse(), but instead uses jsonify_repopulate
        $updatedPortfolio = $this->portfolioRepository->getPortfolioFromId($portfolioId, $userId);
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
            'updatedPortfolio' => $updatedPortfolio->jsonify_repopulate(),
            'cryptocurrencies' => $cryptocurrencies
        )));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    private function getUpdatedPortfolioResponse($portfolioId, $userId)
    {
        $updatedPortfolio = $this->portfolioRepository->getPortfolioFromId($portfolioId, $userId);
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