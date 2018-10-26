<?php
declare(strict_types=1);

namespace App\Repository;


use App\Entity\Game;
use Ramsey\Uuid\UuidInterface;

interface GameRepository
{
    public function findByUuid(UuidInterface $uuid): Game;
    public function save(Game $game): bool;
}