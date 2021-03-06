<?php declare(strict_types=1);

namespace CryptoSim\Portfolio\Domain;

use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Uuid;

final class Portfolio
{
    private $id;
    private $userId;
    private $title;
    private $type;
    private $dateCreated;
    private $visibility;
    private $groupInviteUserIds;

    private function __construct(
        UuidInterface $id,
        UserId $userId,
        string $title,
        string $type,
        DateTimeImmutable $dateCreated,
        string $visibility,
        ?array $groupInviteUserIds
    ){
        $this->id = $id;
        $this->userId = $userId;
        $this->title = $title;
        $this->type = $type;
        $this->dateCreated = $dateCreated;
        $this->visibility = $visibility;
        $this->groupInviteUserIds = $groupInviteUserIds;
    }

    public static function create(
        UuidInterface $userId,
        string $title,
        string $type,
        string $visibility,
        ?array $groupInviteUserIds
    ): Portfolio
    {
        return new Portfolio(
            Uuid::uuid4(),
            UserId::fromUuid($userId),
            $title,
            $type,
            new DateTimeImmutable(),
            $visibility,
            $groupInviteUserIds
        );
    }

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @return UserId
     */
    public function getUserId(): UserId
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
     * @return DateTimeImmutable
     */
    public function getDateCreated(): DateTimeImmutable
    {
        return $this->dateCreated;
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
