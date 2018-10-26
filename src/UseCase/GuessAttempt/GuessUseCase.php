<?php
declare(strict_types=1);

namespace App\UseCase\GuessAttempt;


use App\Command\GuessAttempt\GuessCommand;
use App\Entity\Game;
use App\Entity\GuessAttempt;
use App\Exception\MaxAttemptsReached;
use App\Repository\GameRepository;
use App\Repository\GuessAttemptRepository;

class GuessUseCase
{
    /**
     * @var GuessAttemptRepository
     */
    private $guessAttemptRepository;
    /**
     * @var GameRepository
     */
    private $gameRepository;

    public function __construct(GameRepository $gameRepository, GuessAttemptRepository $guessAttemptRepository)
    {
        $this->guessAttemptRepository = $guessAttemptRepository;
        $this->gameRepository = $gameRepository;
    }

    public function execute(GuessCommand $command): Game
    {
        $game = $this->gameRepository->findByUuid($command->uuid());

        if (!$game->allowAttempt()) {
            throw new MaxAttemptsReached(sprintf('Max number of attempts %d reached.', $game->maxAttempts()));
        }

        $guessAttempt = GuessAttempt::fromCommand($command);
        $guessAttempt->setGameId($game->id());
        $game->giveFeedback($guessAttempt);

        $this->guessAttemptRepository->save($guessAttempt);
        $game->addGuessAttempt($guessAttempt);

        return $game;
    }
}
