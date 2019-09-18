<?php declare(strict_types=1);

namespace CryptoSim\User\Presentation;

use CryptoSim\User\Application\SendFriendRequest;
use CryptoSim\User\Application\SendFriendRequestHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class SendFriendRequestController
{
    private $sendFriendRequestHandler;
    private $session;

    public function __construct(
        SendFriendRequestHandler $sendFriendRequestHandler,
        SessionInterface $session
    ) {
        $this->sendFriendRequestHandler = $sendFriendRequestHandler;
        $this->session = $session;
    }

    public function send(Request $request) {
        $nickname = $request->get('send-friend-request');
        $userId = $request->get('userId');

        try {
            $this->sendFriendRequestHandler->handle(new SendFriendRequest(
                $nickname,
                $userId
            ));
        } catch (\Exception $e) {
            $this->session->getFlashBag()->add(
                'errors',
                'An error occurred while sending the friend request. Please try again.'
            );
        }



        return new RedirectResponse("/user/{$nickname}");
    }
}