<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Kyle
 * Date: 11/18/2018
 * Time: 9:06 PM
 */

namespace CryptoSim\Portfolio\Application;


class GroupInvite
{
    private $id;
    private $toUserId;
    private $fromUserId;
    private $groupId;
    private $fromUsername;

    public function __construct(
        string $id,
        string $toUserId,
        string $fromUserId,
        string $groupId,
        string $fromUsername
    ) {
        $this->id = $id;
        $this->toUserId = $toUserId;
        $this->fromUserId = $fromUserId;
        $this->groupId = $groupId;
        $this->fromUsername = $fromUsername;
    }

    /**
     * @return string
     */
    public function getGroupId(): string
    {
        return $this->groupId;
    }

    /**
     * @return string
     */
    public function getToUserId(): string
    {
        return $this->toUserId;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFromUserId(): string
    {
        return $this->fromUserId;
    }

    /**
     * @return string
     */
    public function getFromUsername(): string
    {
        return $this->fromUsername;
    }
}