<?php
declare(strict_types=1);

namespace App\Entity;


use App\Command\Game\CreateCommand;
use App\ValueObject\ColorCombination;
use App\ValueObject\Feedback;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

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
     * @var ColorCombination
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
     * @var UuidInterface
     */
    private $uuid;
    /**
     * @var bool
     */
    private $finished;
    /**
     * @var \DateTimeImmutable|null
     */
    private $updatedAt;


    /**
     * @param int|null $id
     * @param UuidInterface $uuid
     * @param string $name
     * @param int|null $maxAttempts
     * @param ColorCombination $combination
     * @param \DateTimeImmutable|null $createdAt
     * @param \DateTimeImmutable|null $updatedAt
     */
    private function __construct(
        ?int $id,
        UuidInterface $uuid,
        string $name,
        ?int $maxAttempts,
        ColorCombination $combination,
        ?\DateTimeImmutable $createdAt = null,
        ?\DateTimeImmutable $updatedAt = null
    )
    {
        $this->id = $id;
        $this->uuid = $uuid;
        $this->name = $name;
        $this->maxAttempts = $maxAttempts ?? self::DEFAULT_MAX_ATTEMPTS;
        $this->combination = $combination;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->guessAttempts = [];
        $this->finished = false;
    }

    public static function fromArray(array $gameData): self
    {
        return new self(
            (int) $gameData['id'],
            Uuid::fromString($gameData['uuid']),
            $gameData['name'],
            (int) $gameData['max_guess_attempts'],
            ColorCombination::fromCombination($gameData['combination']),
            $gameData['created_at'] ? new \DateTimeImmutable($gameData['created_at']) : null,
            $gameData['updated_at'] ? new \DateTimeImmutable($gameData['updated_at']) : null
        );
    }

    public static function fromCommand(CreateCommand $command): self
    {
        return new self(null, $command->uuid(), $command->name(), $command->maxAttempts(), $command->combination());
    }

    public function giveFeedback(GuessAttempt $guessAttempt): GuessAttempt
    {
        $feedback = Feedback::create($this->combination, $guessAttempt->playerAttempt());
        $guessAttempt->setFeedback($feedback);

        return $guessAttempt;
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
     * @return ColorCombination
     */
    public function combination(): ColorCombination
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
            'uuid' => $this->uuid()->toString(),
            'name' => $this->name,
            'max_attempts' => $this->maxAttempts,
            'guess_attempts' => $this->guessAttempts,
            'finished' => $this->finished
        ];
    }

    public function allowAttempt(): bool
    {
        return count($this->guessAttempts) < $this->maxAttempts;
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
     * @return UuidInterface
     */
    public function uuid(): UuidInterface
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

    /**
     * @param array $guessAttempts
     */
    public function setGuessAttempts(array $guessAttempts): void
    {
        $this->guessAttempts = $guessAttempts;
    }

    public function addGuessAttempt(GuessAttempt $guessAttempt): void
    {
        $this->guessAttempts[] = $guessAttempt;
        if ($guessAttempt->isWinner() || count($this->guessAttempts) === $this->maxAttempts) {
            $this->finished = true;
        }
    }

    /**
     * @return bool
     */
    public function finished(): bool
    {
        return $this->finished;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function updatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
