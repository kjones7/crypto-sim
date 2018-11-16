<?php declare(strict_types=1);

namespace CryptoSim\Portfolio\Presentation;

use CryptoSim\Framework\Csrf\StoredTokenValidator;
use Symfony\Component\HttpFoundation\Request;

final class CreatePortfolioFormFactory
{
    private $storedTokenValidator;

    public function __construct(StoredTokenValidator $storedTokenValidator)
    {
        $this->storedTokenValidator = $storedTokenValidator;
    }

    public function createFromRequest(Request $request): CreatePortfolioForm
    {
        $groupInviteUserIds = null;
        $portfolioType = $request->get('type');

        // Need to get user id's of invites if portfolio is 'group' type
        if($portfolioType == 'group') {
            // Get invites
            $numInvites = $request->get('num-invites');
            if(is_null($numInvites)) {
                throw new \Exception("Invalid number of invites");
            }

            $groupInviteUserIds = [];
            for($i = 1; $i <= $numInvites; $i++) {
                $key = "invite" . $i;
                $groupInviteUserIds[] = $request->get($key);
            }
        }

        return new CreatePortfolioForm(
            $this->storedTokenValidator,
            (string)$request->get('token'),
            (string)$request->get('title'),
            (string)$portfolioType,
            (string)$request->get('visibility'),
            $groupInviteUserIds
        );
    }
}