<?php
declare(strict_types=1);

namespace App\ValueObject;


use App\Exception\InvalidFeedback;

final class Feedback
{
    private const RIGHT_POSITION_COLOR = 'RED';
    private const RIGHT_COLOR_COLOR = 'WHITE';
    private const FEEDBACK_ELEMENTS = 4;

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
        $guessAttemptCombination = $guessedCombination->combination();
        $winnerColorCombination = $winnerCombination->combination();
        $winnerCountValues = array_count_values($winnerColorCombination);
        $guessCountValues = array_count_values($guessAttemptCombination);

        $feedback = [];

        foreach ($guessAttemptCombination as $index => $guessColor) {
            if (!isset($winnerCountValues[$guessColor])) {
                $feedback[] = '';
                continue;
            }

            if ($winnerColorCombination[$index] !== $guessColor) {
                if ($guessCountValues[$guessColor] > $winnerCountValues[$guessColor]) {
                    $guessCountValues[$guessColor]--;
                    $feedback[] = '';
                } else {
                    $feedback[] = self::RIGHT_COLOR_COLOR;
                }
                continue;
            }

            $feedback[] = self::RIGHT_POSITION_COLOR;
        }

        return new self($feedback);
    }

    public static function fromString(string $feedback): self
    {
        self::validate($feedback);
        return new self(explode(',', $feedback));
    }

    public function feedback(): array
    {
        return $this->feedback;
    }

    /**
     * When all feedback elements are RED
     * @return bool
     */
    public function isWinner(): bool
    {
        $rightPositionElements = 0;
        foreach ($this->feedback as $feedbackElement) {
            if ($feedbackElement === self::RIGHT_POSITION_COLOR) {
                $rightPositionElements++;
            }
        }

        return $rightPositionElements === self::FEEDBACK_ELEMENTS;
    }

    public function toString(): string
    {
        return implode(',', $this->feedback);
    }

    private static function validate(string $feedback): void
    {
        if (false === strpos($feedback, ',')) {
            throw new InvalidFeedback('Please check the feedback, it seems invalid.');
        }
    }
}
