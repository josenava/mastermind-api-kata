<?php
declare(strict_types=1);

namespace App\Tests\unit\UseCase\Game;

use App\Command\Game\CreateCommand;
use App\Entity\Game;
use App\Repository\GameRepository;
use App\UseCase\Game\CreateUseCase;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class CreateUseCaseTest extends TestCase
{
    public function testCreateUseCase()
    {
        $command = new CreateCommand(Uuid::uuid4(), 'Hey', 20, 'RED,YELLOW,BLUE,BLUE');
        $game = Game::fromCommand($command);

        $gameRepositoryProphecy = $this->prophesize(GameRepository::class);
        $gameRepositoryProphecy->save($game)->willReturn($game);
        $gameRepository = $gameRepositoryProphecy->reveal();

        $useCase = new CreateUseCase($gameRepository);

        $gameCreated = $useCase->execute($command);
        $this->assertTrue($game->equalsTo($gameCreated));
    }
}
