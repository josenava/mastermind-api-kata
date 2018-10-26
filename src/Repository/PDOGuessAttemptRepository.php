<?php
declare(strict_types=1);

namespace App\Repository;


use App\Entity\GuessAttempt;
use Doctrine\DBAL\Connection;

class PDOGuessAttemptRepository implements GuessAttemptRepository
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

    public function save(GuessAttempt $guessAttempt): GuessAttempt
    {
        $insertQuery = <<<SQL
INSERT INTO guess_attempt (game_id, uuid, player_guess, feedback)
VALUES (?, ?, ?, ?)
SQL;

        $this->connection->executeQuery($insertQuery, [
            $guessAttempt->gameId(),
            $guessAttempt->uuid()->toString(),
            $guessAttempt->playerAttempt()->toString(),
            $guessAttempt->feedback()->toString()
        ]);

        return $guessAttempt;
    }
}
