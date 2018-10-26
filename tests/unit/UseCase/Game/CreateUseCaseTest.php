<?php
declare(strict_types=1);

namespace App\Tests\unit\UseCase\Game;

use App\Command\Game\CreateCommand;
use App\Entity\Game;
use App\Repository\GameRepository;
use App\UseCase\Game\CreateUseCase;
use App\ValueObject\ColorCombination;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class CreateUseCaseTest extends TestCase
{
    public function testCreateUseCase(): void
    {
        $combination = ColorCombination::fromCombination('RED,YELLOW,BLUE,BLUE');
        $command = new CreateCommand(Uuid::uuid4(), 'Hey', 20, $combination);
        $game = Game::fromCommand($command);

        $gameRepositoryProphecy = $this->prophesize(GameRepository::class);
        $gameRepositoryProphecy->save($game)->willReturn(true);
        $gameRepository = $gameRepositoryProphecy->reveal();

        $useCase = new CreateUseCase($gameRepository);

        $gameCreated = $useCase->execute($command);
        $this->assertTrue($game->equalsTo($gameCreated));
    }
}
