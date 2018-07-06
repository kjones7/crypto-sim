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
    ]
];