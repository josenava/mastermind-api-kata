<?php
declare(strict_types=1);

namespace App\Tests\unit\UseCase\GuessAttempt;

use App\Command\GuessAttempt\GuessCommand;
use App\Entity\Game;
use App\Entity\GuessAttempt;
use App\Exception\MaxAttemptsReached;
use App\Repository\GameRepository;
use App\Repository\GuessAttemptRepository;
use App\UseCase\GuessAttempt\GuessUseCase;
use App\ValueObject\ColorCombination;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Ramsey\Uuid\Uuid;

final class GuessUseCaseTest extends TestCase
{
    public function testMaxAttemptsThrown(): void
    {
        $this->expectException(MaxAttemptsReached::class);
        $guessRepositoryProphecy = $this->prophesize(GuessAttemptRepository::class);

        $combination = ColorCombination::fromCombination('RED,YELLOW,BLUE,BLUE');
        $gameUuid = Uuid::uuid4();
        $guessAttempt = GuessAttempt::fromCommand(
            new GuessCommand($gameUuid, ColorCombination::fromCombination('RED,RED,BLUE,BLUE'))
        );

        $game = Game::fromArray([
            'id' => '12',
            'uuid' => Uuid::fromString($gameUuid->toString()),
            'name' => 'TestGame',
            'max_guess_attempts' => 1,
            'combination' => $combination->toString(),
            'created_at' => null,
            'updated_at' => null,
        ]);
        $game->giveFeedback($guessAttempt);
        $game->addGuessAttempt($guessAttempt);

        $gameRepositoryProphecy = $this->prophesize(GameRepository::class);
        $gameRepositoryProphecy->findByUuid($gameUuid)->willReturn($game);

        $guessUseCase = new GuessUseCase($gameRepositoryProphecy->reveal(), $guessRepositoryProphecy->reveal());
        $guessUseCase->execute(new GuessCommand($gameUuid, ColorCombination::fromCombination('RED,GREEN,BLUE,BLUE')));
    }

    public function testIsNotWinner(): void
    {
        $guessRepositoryProphecy = $this->prophesize(GuessAttemptRepository::class);

        $combination = ColorCombination::fromCombination('RED,YELLOW,BLUE,BLUE');
        $gameUuid = Uuid::uuid4();
        $guessAttempt = GuessAttempt::fromCommand(
            new GuessCommand($gameUuid, ColorCombination::fromCombination('RED,RED,BLUE,BLUE'))
        );

        $game = Game::fromArray([
            'id' => '12',
            'uuid' => Uuid::fromString($gameUuid->toString()),
            'name' => 'TestGame',
            'max_guess_attempts' => 4,
            'combination' => $combination->toString(),
            'created_at' => null,
            'updated_at' => null,
        ]);
        $game->giveFeedback($guessAttempt);
        $game->addGuessAttempt($guessAttempt);

        $gameRepositoryProphecy = $this->prophesize(GameRepository::class);
        $gameRepositoryProphecy->findByUuid($gameUuid)->willReturn($game);

        // actual test
        $gameRepositoryProphecy->update($game)->shouldBeCalled();
        $guessRepositoryProphecy->save(Argument::any())->shouldBeCalled();


        $guessUseCase = new GuessUseCase($gameRepositoryProphecy->reveal(), $guessRepositoryProphecy->reveal());
        $guessUseCase->execute(new GuessCommand($gameUuid, ColorCombination::fromCombination('RED,GREEN,BLUE,BLUE')));
    }
}
