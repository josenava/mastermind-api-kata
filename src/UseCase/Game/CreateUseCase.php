<?php
declare(strict_types=1);

namespace App\UseCase\Game;


use App\Command\Game\CreateCommand;
use App\Entity\Game;
use App\Repository\GameRepository;

class CreateUseCase
{
    /**
     * @var GameRepository
     */
    private $gameRepository;

    public function __construct(GameRepository $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    public function execute(CreateCommand $command): Game
    {
        $game = Game::fromCommand($command);

        $this->gameRepository->save($game);

        return $game;
    }
}
