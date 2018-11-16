<?php declare(strict_types=1);
// command
namespace CryptoSim\Portfolio\Application;

use Ramsey\Uuid\UuidInterface;

final class CreatePortfolio
{
    private $userId;
    private $title;
    private $type;
    private $visibility;
    private $groupInviteUserIds;

    public function __construct(
        UuidInterface $userId,
        string $title,
        string $type,
        string $visibility,
        ?array $groupInviteUserIds
    ){
        $this->userId = $userId;
        $this->title = $title;
        $this->type = $type;
        $this->visibility = $visibility;
        $this->groupInviteUserIds = $groupInviteUserIds;
    }

    /**
     * @return UuidInterface
     */
    public function getUserId(): UuidInterface
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getVisibility(): string
    {
        return $this->visibility;
    }

    /**
     * @return array|null
     */
    public function getGroupInviteUserIds(): ?array
    {
        return $this->groupInviteUserIds;
    }
}