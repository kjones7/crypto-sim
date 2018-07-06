<?php declare(strict_types=1);
// command handler
namespace CryptoSim\User\Application;
use CryptoSim\User\Domain\UserRepository;

final class LogInHandler
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(LogIn $command): void
    {
    $user = $this->userRepository->findByNickname($command->getNickname());

    if ($user === null) {
        return;
    }

    $user->logIn($command->getPassword());
    
    $this->userRepository->save($user);
    }
}