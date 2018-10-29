<?php
declare(strict_types=1);

namespace App\Command\GuessAttempt;


use App\ValueObject\ColorCombination;
use Ramsey\Uuid\UuidInterface;

final class GuessCommand
{
    /**
     * @var UuidInterface
     */
    private $gameUuid;
    /**
     * @var ColorCombination
     */
    private $colorCombination;

    public function __construct(UuidInterface $gameUuid, ColorCombination $colorCombination)
    {
        $this->gameUuid = $gameUuid;
        $this->colorCombination = $colorCombination;
    }

    /**
     * @return UuidInterface
     */
    public function gameUuid(): UuidInterface
    {
        return $this->gameUuid;
    }

    /**
     * @return ColorCombination
     */
    public function colorCombination(): ColorCombination
    {
        return $this->colorCombination;
    }
}
