<?php declare(strict_types=1);
return [
    [
        'GET',
        '/',
        'CryptoSim\FrontPage\Presentation\FrontPageController#show'
    ],
    [
        'GET',
        '/register',
        'CryptoSim\User\Presentation\RegistrationController#show'
    ],
    [
        'POST',
        '/register',
        'CryptoSim\User\Presentation\RegistrationController#register'
    ],
    [
        'GET',
        '/login',
        'CryptoSim\User\Presentation\LoginController#show'
    ],
    [
        'POST',
        '/login',
        'CryptoSim\User\Presentation\LoginController#logIn'
    ],
    [
        'GET',
        '/user/{nickname}',
        'CryptoSim\User\Presentation\ProfileController#show'
    ],
    [
        'GET',
        '/dashboard',
        'CryptoSim\User\Presentation\ProfileDashboardController#show'
    ],
    [
        'POST',
        '/dashboard/friendrequest/accept',
        'CryptoSim\User\Presentation\AcceptFriendRequestController#accept'
    ],
    [
        'POST',
        '/dashboard/friendrequest/decline',
        'CryptoSim\User\Presentation\DeclineFriendRequestController#decline'
    ],
    [
        'POST',
        '/user/{nickname}/sendfriendrequest',
        'CryptoSim\User\Presentation\SendFriendRequestController#send'
    ],[
        'GET',
        '/play/{portfolioId}',
        'CryptoSim\Simulation\Presentation\SimulationController#show'
    ],[
        'GET',
        '/portfolios/create',
        'CryptoSim\Portfolio\Presentation\PortfolioController#show'
    ],[
        'POST',
        '/portfolios/create',
        'CryptoSim\Portfolio\Presentation\PortfolioController#create'
    ],[
        'POST',
        '/play/{portfolioId}',
        'CryptoSim\Simulation\Presentation\SimulationController#saveTransaction'
    ],[
        'POST',
        '/api/v1/play/{portfolioId}',
        'CryptoSim\Simulation\Presentation\SimulationController#getUpdatedPortfolio'
    ],[
        'POST',
        '/api/v1/simulation/getBuyCryptoData',
        'CryptoSim\Simulation\Presentation\SimulationController#getBuyCryptoData'
    ],
];