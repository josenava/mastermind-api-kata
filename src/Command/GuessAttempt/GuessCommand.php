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
    private $uuid;
    /**
     * @var ColorCombination
     */
    private $colorCombination;

    public function __construct(UuidInterface $uuid, ColorCombination $colorCombination)
    {
        $this->uuid = $uuid;
        $this->colorCombination = $colorCombination;
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
    public function colorCombination(): ColorCombination
    {
        return $this->colorCombination;
    }

}
