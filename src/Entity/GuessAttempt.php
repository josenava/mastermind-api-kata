<?php
declare(strict_types=1);

namespace App\Entity;


use App\Command\GuessAttempt\GuessCommand;
use App\ValueObject\ColorCombination;
use App\ValueObject\Feedback;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class GuessAttempt implements \JsonSerializable
{
    /**
     * @var int|null
     */
    private $id;
    /**
     * @var int
     */
    private $gameId;
    /**
     * @var UuidInterface
     */
    private $uuid;
    /**
     * @var ColorCombination
     */
    private $playerAttempt;
    /**
     * @var \DateTimeImmutable|null
     */
    private $createdAt;
    /**
     * @var Feedback|null
     */
    private $feedback;

    private function __construct(
        ?int $id,
        ?int $gameId,
        UuidInterface $uuid,
        ColorCombination $playerAttempt,
        ?Feedback $feedback = null,
        ?\DateTimeImmutable $createdAt = null
    )
    {
        $this->id = $id;
        $this->gameId = $gameId;
        $this->uuid = $uuid;
        $this->playerAttempt = $playerAttempt;
        $this->createdAt = $createdAt;
        $this->feedback = $feedback;
    }

    public static function fromArray(array $guessAttemptData): self
    {
        return new self(
            (int) $guessAttemptData['id'],
            (int) $guessAttemptData['game_id'],
            Uuid::fromString($guessAttemptData['uuid']),
            ColorCombination::fromCombination($guessAttemptData['player_guess']),
            Feedback::fromString($guessAttemptData['feedback'])
        );
    }

    public static function fromCommand(GuessCommand $command): self
    {
        return new self(null, null, $command->gameUuid(), $command->colorCombination());
    }

    /**
     * @return int|null
     */
    public function id(): ?int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function gameId(): int
    {
        return $this->gameId;
    }

    /**
     * @return UuidInterface
     */
    public function uuid(): UuidInterface
    {
        return $this->uuid;
    }

    /**
     * @return ColorCombination
     */
    public function playerAttempt(): ColorCombination
    {
        return $this->playerAttempt;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function createdAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return Feedback|null
     */
    public function feedback(): ?Feedback
    {
        return $this->feedback;
    }

    public function jsonSerialize(): array
    {
        return [
            'uuid' => $this->uuid()->toString(),
            'player_attempt' => $this->playerAttempt->toString(),
            'feedback' => $this->feedback->toString(),
        ];
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setFeedback(Feedback $feedback): void
    {
        $this->feedback = $feedback;
    }

    /**
     * @param int $gameId
     */
    public function setGameId(int $gameId): void
    {
        $this->gameId = $gameId;
    }

    /**
     * @return bool
     */
    public function isWinner(): bool
    {
        return $this->feedback()->isWinner();
    }
}
