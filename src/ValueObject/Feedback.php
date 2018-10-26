<?php
declare(strict_types=1);

namespace App\ValueObject;


final class Feedback
{
    private const RIGHT_POSITION_COLOR = 'RED';
    private const RIGHT_COLOR_COLOR = 'WHITE';

    /**
     * @var array
     */
    private $feedback;

    private function __construct(array $feedback)
    {
        $this->feedback = $feedback;
    }

    public static function create(ColorCombination $winnerCombination, ColorCombination $guessedCombination): self
    {
        $feedback = [];
        $guessAttemptCombination = $guessedCombination->combination();
        $winnerColorCombination = $winnerCombination->combination();
        foreach ($guessAttemptCombination as $index => $color) {
            if (!in_array($color, $winnerColorCombination)) {
                $feedback[] = '';
                continue;
            }

            if ($winnerColorCombination[$index] === $color) {
                $feedback[] = self::RIGHT_POSITION_COLOR;
            } else {
                $feedback[] = self::RIGHT_COLOR_COLOR;
            }
        }

        return new self($feedback);
    }

    public static function fromString(string $feedback): self
    {
//        self::validate();
        return new self(explode(',', $feedback));
    }

    public function feedback(): array
    {
        return $this->feedback;
    }

    public function toString(): string
    {
        return implode(',', $this->feedback);
    }
}
