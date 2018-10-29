<?php
declare(strict_types=1);

namespace App\Repository;


use App\Entity\Game;
use Ramsey\Uuid\UuidInterface;

interface GameRepository
{
    /**
     * @param UuidInterface $uuid
     * @return Game|null
     */
    public function findByUuid(UuidInterface $uuid): Game;

    /**
     * @param Game $game
     * @return bool
     */
    public function save(Game $game): bool;

    public function update(Game $game): bool;
}