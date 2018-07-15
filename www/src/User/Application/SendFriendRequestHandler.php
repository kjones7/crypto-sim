<?php declare(strict_types=1);

namespace CryptoSim\User\Application;

use CryptoSim\User\Domain\FriendRequestsRepository;

final class SendFriendRequestHandler
{
    private $friendRequestsRepository;

    public function __construct(FriendRequestsRepository $friendRequestsRepository)
    {
        $this->friendRequestsRepository = $friendRequestsRepository;
    }

    public function handle(SendFriendRequest $command)
    {
        $this->friendRequestsRepository->send($command->getToUserId());
    }
}