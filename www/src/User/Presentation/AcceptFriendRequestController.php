<?php declare(strict_types=1);

namespace CryptoSim\User\Presentation;

use CryptoSim\User\Application\AcceptFriendRequest;
use CryptoSim\User\Application\AcceptFriendRequestHandler;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class AcceptFriendRequestController
{
    //TODO - Change this to acceptFriendRequestHandler
    private $friendRequestHandler;

    public function __construct(AcceptFriendRequestHandler $friendRequestHandler)
    {
        $this->friendRequestHandler = $friendRequestHandler;
    }

    public function accept(Request $request): Response
    {
        $this->friendRequestHandler->handle(new AcceptFriendRequest(
            $request->request->get('from-user-id')
            )
        );

        return new RedirectResponse('/dashboard');
    }
}