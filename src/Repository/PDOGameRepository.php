<?php
declare(strict_types=1);

namespace App\Repository;


use App\Entity\Game;

class PDOGameRepository implements GameRepository
{

    public function findById(int $id): Game
    {
        // TODO: Implement findById() method.
    }

    public function save(Game $game): bool
    {
        // TODO: Implement save() method.
    }
}
