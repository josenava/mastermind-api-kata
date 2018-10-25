<?php
declare(strict_types=1);

namespace App\Repository;


use App\Entity\Game;
use Doctrine\DBAL\Connection;

class PDOGameRepository implements GameRepository
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function findById(int $id): Game
    {
        // TODO: Implement findById() method.
    }

    public function save(Game $game): bool
    {
        $insertQuery = <<<SQL
INSERT INTO game (uuid, name, max_guess_attempts, combination)
VALUES (?, ?, ?, ?)
SQL;

        $this->connection->executeQuery($insertQuery, [
            $game->uuid()->toString(),
            $game->name(),
            $game->maxAttempts(),
            $game->combination()
        ]);

        $gameId = $this->connection->lastInsertId();
        $game->setId($gameId);

        return true;
    }
}
