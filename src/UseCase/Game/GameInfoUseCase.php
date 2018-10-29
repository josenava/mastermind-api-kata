<?php
declare(strict_types=1);

namespace App\UseCase\Game;


use App\Command\Game\GameInfoCommand;
use App\Entity\Game;
use App\Repository\GameRepository;

class GameInfoUseCase
{
    /**
     * @var GameRepository
     */
    private $gameRepository;

    /**
     * @param GameRepository $gameRepository
     */
    public function __construct(GameRepository $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    /**
     * @param GameInfoCommand $gameInfo
     * @return Game|null
     */
    public function execute(GameInfoCommand $gameInfo): ?Game
    {
        return $this->gameRepository->findByUuid($gameInfo->uuid());
    }
}
