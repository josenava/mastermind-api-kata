<?php
declare(strict_types=1);

namespace App\Repository;


use App\Entity\Game;
use App\Entity\GuessAttempt;
use App\Exception\GameNotFound;
use Doctrine\DBAL\Connection;
use Ramsey\Uuid\UuidInterface;

class PDOGameRepository implements GameRepository
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param UuidInterface $uuid
     * @return Game
     * @throws GameNotFound
     * @throws \Doctrine\DBAL\DBALException
     */
    public function findByUuid(UuidInterface $uuid): Game
    {
        $selectQuery = <<<SQL
SELECT id, uuid, name, combination, max_guess_attempts, finished, created_at, updated_at FROM game
WHERE uuid = ?
SQL;

        $gameResult = $this->connection->fetchAssoc($selectQuery, [$uuid->toString()]);
        if (false === $gameResult) {
            throw new GameNotFound(sprintf('Game uuid: %s not found.', $uuid->toString()));
        }

        $game = Game::fromArray($gameResult);

        $selectGameGuessAttemptsQuery = <<<SQL
SELECT id, uuid, game_id, player_guess, feedback, created_at FROM guess_attempt
WHERE game_id = ? ORDER BY id DESC
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

    /**
     * @param Game $game
     * @return bool
     * @throws \Doctrine\DBAL\DBALException
     */
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

    /**
     * @param Game $game
     * @return bool
     * @throws \Doctrine\DBAL\DBALException
     */
    public function update(Game $game): bool
    {
        $updateQuery = <<<SQL
UPDATE game SET updated_at = NOW(), finished = ?
WHERE uuid = ?
SQL;

        $this->connection->executeQuery($updateQuery, [
            (int)$game->finished(),
            $game->uuid()->toString()
        ]);

        return true;
    }
}
