<?php declare(strict_types=1);

namespace CryptoSim\User\Presentation;

use CryptoSim\User\Application\DeclineFriendRequest;
use CryptoSim\User\Application\DeclineFriendRequestHandler;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class DeclineFriendRequestController
{
    private $friendRequestHandler;

    public function __construct(DeclineFriendRequestHandler $friendRequestHandler)
    {
        $this->friendRequestHandler = $friendRequestHandler;
    }

    public function decline(Request $request): Response
    {
        $this->friendRequestHandler->handle(new DeclineFriendRequest(
                $request->request->get('from-user-id')
            )
        );

        return new RedirectResponse('/dashboard');
    }
}