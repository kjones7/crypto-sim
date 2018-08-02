<?php declare(strict_types=1);

namespace CryptoSim\Portfolio\Presentation;

use CryptoSim\Framework\Csrf\StoredTokenValidator;
use Symfony\Component\HttpFoundation\Request;

final class CreatePortfolioFormFactory
{
    private $storedTokenValidator;

    public function __construct(StoredTokenValidator $storedTokenValidator)
    {
        $this->storedTokenValidator = $storedTokenValidator;
    }

    public function createFromRequest(Request $request): CreatePortfolioForm
    {
        return new CreatePortfolioForm(
            $this->storedTokenValidator,
            (string)$request->get('token'),
            (string)$request->get('title'),
            (string)$request->get('type'),
            (string)$request->get('visibility')
        );
    }
}