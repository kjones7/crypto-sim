<?php declare(strict_types=1);

namespace CryptoSim\User\Application;

use CryptoSim\User\Domain\UserRepository;
use CryptoSim\User\Domain\User;

final class RegisterUserHandler
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(RegisterUser $command): void
    {
        $user = User::register(
            $command->getNickname(),
            $command->getPassword()
        );
        $this->userRepository->add($user);
    }
}