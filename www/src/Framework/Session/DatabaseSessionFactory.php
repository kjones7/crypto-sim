<?php declare(strict_types=1);

namespace CryptoSim\Framework\Session;


use CryptoSim\Framework\Dbal\DatabaseUrl;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\PdoSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;

final class DatabaseSessionFactory
{
    private $databaseUrl;

    public function __construct(DatabaseUrl $databaseUrl)
    {
        $this->databaseUrl = $databaseUrl;
    }

    public function create(): SessionInterface
    {
        $pdoSessionHandler = new PdoSessionHandler($this->databaseUrl->toString());
        $sessionStorage = new NativeSessionStorage([], $pdoSessionHandler);
        return new Session($sessionStorage);
    }
}