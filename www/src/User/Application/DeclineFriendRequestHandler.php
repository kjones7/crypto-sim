<?php declare(strict_types=1);

namespace CryptoSim\User\Application;

use CryptoSim\User\Domain\FriendRequestsRepository;

final class DeclineFriendRequestHandler
{
    private $friendRequestRepository;

    public function __construct(FriendRequestsRepository $friendRequestRepository)
    {
        $this->friendRequestRepository = $friendRequestRepository;
    }

    public function handle(DeclineFriendRequest $command): void
    {
        $this->friendRequestRepository->decline($command->getFromUserId());
    }
}