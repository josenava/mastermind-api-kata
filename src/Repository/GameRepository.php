<?php
declare(strict_types=1);

namespace App\Repository;


interface GameRepository
{
    public function findById(int $id): Game;
    public function save(Game $game): bool;
}