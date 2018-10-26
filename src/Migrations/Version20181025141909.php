<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


final class Version20181025141909 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $createGameSql = <<<SQL
CREATE TABLE game
(
  id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  uuid CHAR(36) NOT NULL,
  name VARCHAR(40),
  max_guess_attempts TINYINT UNSIGNED NOT NULL,
  combination VARCHAR(50) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4
SQL;

        $this->addSql($createGameSql);

        $createGuessAttemptSql = <<<SQL
CREATE TABLE guess_attempt
(
  id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  game_id MEDIUMINT UNSIGNED NOT NULL,
  uuid CHAR(36) NOT NULL,
  player_guess VARCHAR(50) COMMENT 'Player combination attempt',
  feedback VARCHAR(50),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY guess_attempt_game_fk (game_id) REFERENCES game(id)
)
SQL;

        $this->addSql($createGuessAttemptSql);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE guess_attempt');
        $this->addSql('DROP TABLE game');
    }
}
