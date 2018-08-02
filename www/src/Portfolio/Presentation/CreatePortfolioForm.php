<?php declare(strict_types=1);

namespace CryptoSim\Portfolio\Presentation;

use CryptoSim\Framework\Csrf\StoredTokenValidator;
use CryptoSim\Framework\Csrf\Token;
use CryptoSim\Framework\Rbac\AuthenticatedUser;
use CryptoSim\Portfolio\Application\CreatePortfolio;

final class CreatePortfolioForm
{
    private $storedTokenValidator;
    private $token;
    private $title;
    private $type;
    private $visibility;

    public function __construct(
        StoredTokenValidator $storedTokenValidator,
        string $token,
        string $title,
        string $type,
        string $visibility
    ){
        $this->storedTokenValidator = $storedTokenValidator;
        $this->token = $token;
        $this->title = $title;
        $this->type = $type;
        $this->visibility = $visibility;
    }

    public function getValidationErrors(): array
    {
        $errors = [];
        $possibleTypes = ['freeplay'];
        $possibleVisibilities = ['private', 'public'];

        if(!$this->storedTokenValidator->validate(
            'create_portfolio',
            new Token($this->token)
        )){
            $errors[] = 'Invalid token';
        }
        if(strlen($this->title) > 60) {
            $errors[] = 'Title must be under 60 characters';
        }
        if(!in_array($this->type, $possibleTypes)) {
            $errors[] = 'Invalid portfolio type';
        }
        if(!in_array($this->visibility, $possibleVisibilities)) {
            $errors[] = 'Invalid portfolio visiblity';
        }

        return $errors;
    }

    public function hasValidationErrors(): bool
    {
        return (count($this->getValidationErrors()) > 0);
    }

    public function toCommand(AuthenticatedUser $portfolioCreator): CreatePortfolio
    {
        return new CreatePortfolio(
            $portfolioCreator->getId(),
            $this->title,
            $this->type,
            $this->visibility
        );
    }
}