<?php declare(strict_types=1);

namespace CryptoSim\User\Presentation;

use CryptoSim\User\Application\SendFriendRequest;
use CryptoSim\User\Application\SendFriendRequestHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

final class SendFriendRequestController
{
    private $sendFriendRequestHandler;

    public function __construct(SendFriendRequestHandler $sendFriendRequestHandler)
    {
        $this->sendFriendRequestHandler = $sendFriendRequestHandler;
    }

    public function send(Request $request) {
        $nickname = $request->get('send-friend-request');

        $this->sendFriendRequestHandler->handle(new SendFriendRequest(
            $nickname,
            $request->get('userId')
        ));

        return new RedirectResponse("/user/{$nickname}");
    }
}