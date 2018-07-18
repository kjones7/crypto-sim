<?php declare(strict_types=1);

use Auryn\Injector;
use CryptoSim\Framework\Rendering\TemplateRenderer;
use CryptoSim\Framework\Rendering\TwigTemplateRendererFactory;
use CryptoSim\Framework\Rendering\TemplateDirectory;
use Doctrine\DBAL\Connection;
use CryptoSim\Framework\Dbal\ConnectionFactory;
use CryptoSim\Framework\Dbal\DatabaseUrl;
use CryptoSim\Framework\Csrf\TokenStorage;
use CryptoSim\Framework\Csrf\SymfonySessionTokenStorage;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use CryptoSim\User\Domain\UserRepository;
use CryptoSim\User\Infrastructure\DbalUserRepository;
use CryptoSim\User\Application\DoesNicknameExistQuery;
use CryptoSim\User\Infrastructure\DbalDoesNicknameExistQuery;
use CryptoSim\Framework\Rbac\User;
use CryptoSim\Framework\Rbac\SymfonySessionCurrentUserFactory;
use CryptoSim\User\Application\FriendRequestsQuery;
use CryptoSim\User\Infrastructure\DbalFriendRequestsQuery;
use CryptoSim\User\Domain\FriendRequestsRepository;
use CryptoSim\User\Infrastructure\DbalFriendRequestsRepository;
use CryptoSim\User\Application\FriendsListQuery;
use CryptoSim\User\Infrastructure\DbalFriendsListQuery;
use CryptoSim\User\Application\GetPublicUserFromNicknameQuery;
use CryptoSim\User\Infrastructure\DbalGetPublicUserFromNicknameQuery;
use CryptoSim\User\Domain\PublicUserRepository;
use CryptoSim\User\Infrastructure\DbalPublicUserRepository;

$injector = new Injector();

$injector->delegate(
    TemplateRenderer::class,
    function () use ($injector): TemplateRenderer {
        $factory = $injector->make(TwigTemplateRendererFactory::class);
        return $factory->create();
    }
);

$injector->define(
    DatabaseUrl::class,
    [':url' => 'mysql://' . $_ENV['DB_USER'] . ':' . $_ENV['DB_PASS'] . '@' . $_ENV['DB_HOST'] . '/' . $_ENV['DB_NAME']]
);

$injector->delegate(Connection::class, function () use ($injector): Connection {
    $factory = $injector->make(ConnectionFactory::class);
    return $factory->create();
});

$injector->define(TemplateDirectory::class, [':rootDirectory' => ROOT_DIR]);

// Database connection will be reused instead of being created every time a connection is needed
$injector->share(Connection::class);

$injector->alias(TokenStorage::class, SymfonySessionTokenStorage::class);
$injector->alias(SessionInterface::class, Session::class);

$injector->alias(UserRepository::class, DbalUserRepository::class);
$injector->alias(FriendRequestsRepository::class, DbalFriendRequestsRepository::class);
$injector->alias(PublicUserRepository::class, DbalPublicUserRepository::class);

$injector->alias(DoesNicknameExistQuery::class, DbalDoesNicknameExistQuery::class);
$injector->alias(FriendRequestsQuery::class, DbalFriendRequestsQuery::class);
$injector->alias(FriendsListQuery::class, DbalFriendsListQuery::class);
$injector->alias(GetPublicUserFromNicknameQuery::class, DbalGetPublicUserFromNicknameQuery::class);

// authentication (permissions and roles)
$injector->delegate(User::class, function () use ($injector): User {
    $factory = $injector->make(SymfonySessionCurrentUserFactory::class);
    return $factory->create();
});

return $injector;