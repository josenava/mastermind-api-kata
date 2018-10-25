<?php
declare(strict_types=1);

namespace App\Entity;


use App\Command\Game\CreateCommand;
use Ramsey\Uuid\Uuid;

class Game implements \JsonSerializable
{
    private const DEFAULT_MAX_ATTEMPTS = 10;

    /**
     * @var string
     */
    private $name;
    /**
     * @var int|null
     */
    private $maxAttempts;
    /**
     * @var string
     */
    private $combination;
    /**
     * @var \DateTimeImmutable|null
     */
    private $createdAt;
    /**
     * @var array
     */
    private $guessAttempts;
    /**
     * @var int|null
     */
    private $id;
    /**
     * @var Uuid
     */
    private $uuid;

    private function __construct(
        ?int $id,
        Uuid $uuid,
        string $name,
        ?int $maxAttempts,
        string $combination,
        ?\DateTimeImmutable $createdAt = null
    )
    {
        $this->id = $id;
        $this->uuid = $uuid;
        $this->name = $name;
        $this->maxAttempts = $maxAttempts ?? self::DEFAULT_MAX_ATTEMPTS;
        $this->combination = $combination;
        $this->createdAt = $createdAt;
        $this->guessAttempts = [];
    }

    public static function fromArray(array $gameData): self
    {
        return new self(
            $gameData['id'],
            $gameData['uuid'],
            $gameData['name'],
            $gameData['max_attempts'],
            $gameData['combination'],
            $gameData['created_at']
        );
    }

    public static function fromCommand(CreateCommand $command): self
    {
        return new self(null, $command->uuid(), $command->name(), $command->maxAttempts(), $command->combination());
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return int|null
     */
    public function maxAttempts(): ?int
    {
        return $this->maxAttempts;
    }

    /**
     * @return string
     */
    public function combination(): string
    {
        return $this->combination;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function createdAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'max_attempts' => $this->maxAttempts,
            'created_at' => $this->createdAt->format('YmdHis')
        ];
    }

    public function equalsTo(Game $game): bool
    {
        return $this->uuid()->equals($game->uuid());
    }

    /**
     * @return array
     */
    public function guessAttempts(): array
    {
        return $this->guessAttempts;
    }

    /**
     * @return int
     */
    public function id(): int
    {
        return $this->id;
    }

    /**
     * @return Uuid
     */
    public function uuid(): Uuid
    {
        return $this->uuid;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
