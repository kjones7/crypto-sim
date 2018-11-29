<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Kyle
 * Date: 11/28/2018
 * Time: 6:09 PM
 */

namespace CryptoSim\User\Application;
use Ramsey\Uuid\UuidInterface;

class CreatePortfolioFromGroupInvite
{
    private $groupId;
    private $userId;

    public function __construct(
        string $groupId,
        UuidInterface $userId
    ){
        $this->groupId = $groupId;
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function getGroupId(): string
    {
        return $this->groupId;
    }

    /**
     * @return UuidInterface
     */
    public function getUserId(): UuidInterface
    {
        return $this->userId;
    }
}