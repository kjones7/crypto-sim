<?php declare(strict_types=1);

namespace CryptoSim\Portfolio\Domain;

use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Uuid;

final class PortfolioCreatedFromGroupInvite
{
    private $id;
    private $userId;
    private $dateCreated;
    private $groupId;

    private function __construct(
        UuidInterface $id,
        UserId $userId,
        DateTimeImmutable $dateCreated,
        string $groupId
    ){
        $this->id = $id;
        $this->userId = $userId;
        $this->dateCreated = $dateCreated;
        $this->groupId = $groupId;
    }

    public static function create(
        UuidInterface $userId,
        string $groupId
    ): PortfolioCreatedFromGroupInvite
    {
        return new PortfolioCreatedFromGroupInvite(
            Uuid::uuid4(),
            UserId::fromUuid($userId),
            new DateTimeImmutable(),
            $groupId
        );
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
    public function getGroupId(): string
    {
        return $this->groupId;
    }

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getDateCreated(): DateTimeImmutable
    {
        return $this->dateCreated;
    }
}
