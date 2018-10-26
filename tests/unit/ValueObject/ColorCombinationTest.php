<?php
declare(strict_types=1);

namespace App\Tests\unit\ValueObject;

use App\Exception\InvalidColorCombination;
use App\Exception\InvalidLengthColorCombination;
use App\ValueObject\ColorCombination;
use PHPUnit\Framework\TestCase;

final class ColorCombinationTest extends TestCase
{
    public function testEmptyCombinationThrowsError(): void
    {
        $this->expectException(InvalidLengthColorCombination::class);
        ColorCombination::fromCombination('');
    }

    public function testUnexpectedColorThrowsError(): void
    {
        $this->expectException(InvalidColorCombination::class);
        ColorCombination::fromCombination('RED,BLUE,BLUE,PURPLE');
    }

    public function testNullColorThrowsError(): void
    {
        $this->expectException(InvalidLengthColorCombination::class);
        ColorCombination::fromCombination(null);
    }
}
