<?php
declare(strict_types=1);

namespace App\Repository;


use App\Entity\Game;
use App\Entity\GuessAttempt;
use Doctrine\DBAL\Connection;
use Ramsey\Uuid\UuidInterface;

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

    public function findByUuid(UuidInterface $uuid): Game
    {
        $selectQuery = <<<SQL
SELECT id, uuid, name, combination, max_guess_attempts, created_at FROM game
WHERE uuid = ?
SQL;

        $gameResult = $this->connection->fetchAssoc($selectQuery, [$uuid->toString()]);
        $game = Game::fromArray($gameResult);

        $selectGameGuessAttemptsQuery = <<<SQL
SELECT id, uuid, game_id, player_guess, feedback, created_at FROM guess_attempt
WHERE game_id = ?
SQL;

        $guessAttemptsData = $this->connection->fetchAll($selectGameGuessAttemptsQuery, [
            $game->id()
        ]);

        $guessAttempts = array_map(function(array $guessAttempt) {
            return GuessAttempt::fromArray($guessAttempt);
        }, $guessAttemptsData);


        $game->setGuessAttempts($guessAttempts);

        return $game;
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
            $game->combination()->toString()
        ]);

        $gameId = (int) $this->connection->lastInsertId();
        $game->setId($gameId);

        return true;
    }
}
