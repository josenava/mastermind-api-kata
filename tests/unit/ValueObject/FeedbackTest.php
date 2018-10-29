<?php
declare(strict_types=1);

namespace App\Tests\unit\ValueObject;

use App\Exception\InvalidFeedback;
use App\ValueObject\ColorCombination;
use App\ValueObject\Feedback;
use PHPUnit\Framework\TestCase;

final class FeedbackTest extends TestCase
{
    public function testFeedback(): void
    {
        $winnerCombination = ColorCombination::fromCombination('RED,BLUE,GREEN,BLUE');
        $guessCombination = ColorCombination::fromCombination('BLACK,BLUE,GREEN,RED');

        $feedback = Feedback::create($winnerCombination, $guessCombination);

        $this->assertEquals($feedback->toString(), ',RED,RED,WHITE');

        $winnerCombination = ColorCombination::fromCombination('ORANGE,BLUE,GREEN,BLUE');
        $guessCombination = ColorCombination::fromCombination('BLUE,BLUE,BLUE,RED');

        $feedback = Feedback::create($winnerCombination, $guessCombination);

        $this->assertEquals($feedback->toString(), ',RED,WHITE,');
    }

    public function testInvalidFeedbackException(): void
    {
        $this->expectException(InvalidFeedback::class);
        Feedback::fromString('blablabla');
    }
}
