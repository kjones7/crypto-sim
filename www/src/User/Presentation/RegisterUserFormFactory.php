<?php declare(strict_types=1);

namespace CryptoSim\User\Presentation;

use CryptoSim\Framework\Csrf\StoredTokenValidator;
use Symfony\Component\HttpFoundation\Request;
use CryptoSim\User\Application\DoesNicknameExistQuery;

final class RegisterUserFormFactory
{
    private $storedTokenValidator;
    private $nicknameTakenQuery;

    public function __construct(
        StoredTokenValidator $storedTokenValidator,
        DoesNicknameExistQuery $nicknameTakenQuery
    ) {
        $this->storedTokenValidator = $storedTokenValidator;
        $this->nicknameTakenQuery = $nicknameTakenQuery;
    }

    public function createFromRequest(Request $request): RegisterUserForm
    {
        return new RegisterUserForm(
            $this->storedTokenValidator,
            $this->nicknameTakenQuery,
            (string)$request->get('token'),
            (string)$request->get('nickname'),
            (string)$request->get('country'),
            (string)$request->get('password')
        );
    }
}