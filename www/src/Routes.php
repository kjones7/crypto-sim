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
];