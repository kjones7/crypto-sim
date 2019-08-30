<?php declare(strict_types=1);

use Auryn\Injector;
use CryptoSim\Framework\Rendering\TemplateRenderer;
use CryptoSim\Framework\Rendering\TwigTemplateRendererFactory;
use CryptoSim\Framework\Rendering\TemplateDirectory;
use CryptoSim\Framework\Session\DatabaseSessionFactory;
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
use CryptoSim\User\Domain\FriendsListRepository;
use CryptoSim\User\Infrastructure\DbalFriendsListRepository;
use CryptoSim\User\Application\GetPublicUserFromNicknameQuery;
use CryptoSim\User\Infrastructure\DbalGetPublicUserFromNicknameQuery;
use CryptoSim\User\Domain\PublicUserRepository;
use CryptoSim\User\Infrastructure\DbalPublicUserRepository;
use CryptoSim\Portfolio\Domain\PortfolioRepository;
use CryptoSim\Portfolio\Infrastructure\DbalPortfolioRepository;
use CryptoSim\Simulation\Domain\GetCryptocurrenciesQuery;
use CryptoSim\Simulation\Infrastructure\DbalGetCryptocurrenciesQuery;

$injector = new Injector();

$injector->delegate(
    TemplateRenderer::class,
    function () use ($injector): TemplateRenderer {
        $factory = $injector->make(TwigTemplateRendererFactory::class);
        return $factory->create();
    }
);

// Select database to use based off of current envionment
$databaseName = ($_ENV['APP_ENV'] === 'test') ? $_ENV['DB_TEST_NAME'] : $_ENV['DB_NAME'];
$injector->define(
    DatabaseUrl::class,
    [':url' => 'mysql://' . $_ENV['DB_USER'] . ':' . $_ENV['DB_PASS'] . '@' . $_ENV['DB_HOST'] . '/' . $databaseName]
);

$injector->delegate(Connection::class, function () use ($injector): Connection {
    $factory = $injector->make(ConnectionFactory::class);
    return $factory->create();
});

$injector->define(TemplateDirectory::class, [':rootDirectory' => ROOT_DIR]);

// Database connection will be reused instead of being created every time a connection is needed
$injector->share(Connection::class);

$injector->alias(TokenStorage::class, SymfonySessionTokenStorage::class);
$injector->delegate(SessionInterface::class, function() use ($injector): SessionInterface {
    $factory = $injector->make(DatabaseSessionFactory::class);
    return $factory->create();
});
$injector->alias(UserRepository::class, DbalUserRepository::class);
$injector->alias(FriendRequestsRepository::class, DbalFriendRequestsRepository::class);
$injector->alias(PublicUserRepository::class, DbalPublicUserRepository::class);

$injector->alias(DoesNicknameExistQuery::class, DbalDoesNicknameExistQuery::class);
$injector->alias(FriendRequestsQuery::class, DbalFriendRequestsQuery::class);
$injector->alias(FriendsListRepository::class, DbalFriendsListRepository::class);
$injector->alias(GetPublicUserFromNicknameQuery::class, DbalGetPublicUserFromNicknameQuery::class);

$injector->alias(PortfolioRepository::class, DbalPortfolioRepository::class);
$injector->alias(\CryptoSim\Simulation\Domain\PortfolioRepository::class, \CryptoSim\Simulation\Infrastructure\DbalPortfolioRepository::class);

$injector->alias(GetCryptocurrenciesQuery::class, DbalGetCryptocurrenciesQuery::class);

$injector->alias(\CryptoSim\Simulation\Domain\TransactionRepository::class, \CryptoSim\Simulation\Infrastructure\DbalTransactionRepository::class);

$injector->alias(\CryptoSim\Simulation\Domain\GetLeaderboardQuery::class, \CryptoSim\Simulation\Infrastructure\DbalGetLeaderboardQuery::class);
$injector->alias(\CryptoSim\Simulation\Domain\GetGroupLeaderboardQuery::class, \CryptoSim\Simulation\Infrastructure\DbalGetGroupLeaderboardQuery::class);

// authentication (permissions and roles)
$injector->delegate(User::class, function () use ($injector): User {
    $factory = $injector->make(SymfonySessionCurrentUserFactory::class);
    return $factory->create();
});

$injector->alias(\CryptoSim\User\Application\GetFriendsListQuery::class, \CryptoSim\User\Infrastructure\DbalGetFriendsListQuery::class);
$injector->alias(\CryptoSim\Portfolio\Domain\GroupRepository::class, \CryptoSim\Portfolio\Infrastructure\DbalGroupRepository::class);

$injector->alias(\CryptoSim\Simulation\Application\GroupHasNotReceivedAllResponsesQuery::class, \CryptoSim\Simulation\Infrastructure\DbalGroupHasNotReceivedAllResponsesQuery::class);

$injector->alias(\CryptoSim\Portfolio\Application\PortfoliosQuery::class, \CryptoSim\Portfolio\Infrastructure\DbalPortfoliosQuery::class);
$injector->alias(\CryptoSim\User\Application\GetPublicPortfoliosQuery::class, \CryptoSim\User\Infrastructure\DbalGetPublicPortfoliosQuery::class);

return $injector;