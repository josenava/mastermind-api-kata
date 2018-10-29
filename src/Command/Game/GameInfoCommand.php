<?php
declare(strict_types=1);

namespace App\Command\Game;


use Ramsey\Uuid\UuidInterface;

final class GameInfoCommand
{
    /**
     * @var UuidInterface
     */
    private $uuid;

    /**
     * @param UuidInterface $uuid
     */
    public function __construct(UuidInterface $uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * @return UuidInterface
     */
    public function uuid(): UuidInterface
    {
        return $this->uuid;
    }
}
