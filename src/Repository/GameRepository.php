<?php
declare(strict_types=1);

namespace App\Repository;


use App\Entity\Game;

interface GameRepository
{
    public function findById(int $id): Game;
    public function save(Game $game): Game;
}