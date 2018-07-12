<?php declare(strict_types=1);

namespace CryptoSim\User\Application;

use CryptoSim\User\Domain\FriendRequestsRepository;

final class AcceptFriendRequestHandler
{
    private $friendRequestRepository;

    public function __construct(FriendRequestsRepository $friendRequestRepository)
    {
        $this->friendRequestRepository = $friendRequestRepository;
    }

    public function handle(AcceptFriendRequest $command): void
    {
        $this->friendRequestRepository->accept($command->getFromNickname());
    }
}