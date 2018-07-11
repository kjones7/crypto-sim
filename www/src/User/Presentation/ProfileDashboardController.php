<?php declare(strict_types=1);

namespace CryptoSim\User\Presentation;

use CryptoSim\Framework\Rendering\TemplateRenderer;
use CryptoSim\User\Application\FriendRequestsQuery;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

final class ProfileDashboardController
{
    private $templateRenderer;
    private $session;
    private $friendRequestsQuery;

    public function __construct(
        TemplateRenderer $templateRenderer,
        Session $session,
        FriendRequestsQuery $friendRequestsQuery
    ) {
        $this->templateRenderer = $templateRenderer;
        $this->session = $session;
        $this->friendRequestsQuery = $friendRequestsQuery;
    }

    public function show() : Response
    {
        $template = 'ProfileDashboard.html.twig';

        if(!$this->session->get('userId')) {
            $template = 'PageNotFound.html.twig';
        }
//        $friendRequests = [
//            [
//                'nickname' => 'kayla'
//            ],
//            [
//                'nickname' => 'joe'
//            ],
//            [
//                'nickname' => 'bob'
//            ]
//        ];
        $content = $this->templateRenderer->render(
            $template,
            [
                'nickname' => $this->session->get('nickname'),
                'friendRequests' => $this->friendRequestsQuery->execute()
            ]
        );

        return new Response($content);
    }
}