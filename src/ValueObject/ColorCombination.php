<?php
declare(strict_types=1);

namespace App\ValueObject;


use App\Exception\EmptyColorCombination;
use App\Exception\InvalidColorCombination;
use App\Exception\InvalidLengthColorCombination;

final class ColorCombination
{
    public const VALID_COLORS = [
        'RED',
        'YELLOW',
        'BLUE',
        'BLACK',
        'ORANGE',
        'GREEN'
    ];

    public const MAX_ELEMENTS = 4;

    /**
     * @var array
     */
    private $combination;

    private function __construct(array $combination)
    {
        $this->combination = $combination;
    }

    public static function fromCombination(?string $combination): self
    {
        self::validateCombination($combination);

        return new self(explode(',', $combination));
    }

    private static function validateCombination(?string $combination): bool
    {
        if (null === $combination) {
            throw new InvalidLengthColorCombination(sprintf('Please enter a string combination of %d colors', self::MAX_ELEMENTS));
        }

        $colorCombination = explode(',', $combination);
        if (count($colorCombination) !== self::MAX_ELEMENTS) {
            throw new InvalidLengthColorCombination(sprintf('Please enter a combination of %d colors', self::MAX_ELEMENTS));
        }

        if (count(array_intersect($colorCombination, self::VALID_COLORS)) !== count($colorCombination)) {
            throw new InvalidColorCombination(self::VALID_COLORS);
        }

        return true;
    }

    /**
     * @return array
     */
    public function combination(): array
    {
        return $this->combination;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return implode(',', $this->combination);
    }
}
