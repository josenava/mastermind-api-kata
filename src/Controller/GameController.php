<?php
declare(strict_types=1);

namespace App\Controller;


use App\Command\Game\CreateCommand;
use App\Command\Game\GameInfoCommand;
use App\Command\GuessAttempt\GuessCommand;
use App\Exception\GameNotFound;
use App\UseCase\Game\CreateUseCase;
use App\UseCase\Game\GameInfoUseCase;
use App\UseCase\GuessAttempt\GuessUseCase;
use App\ValueObject\ColorCombination;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends Controller
{
    /**
     * @var CreateUseCase
     */
    private $createUseCase;
    /**
     * @var GuessUseCase
     */
    private $guessUseCase;
    /**
     * @var GameInfoUseCase
     */
    private $gameInfoUseCase;

    public function __construct(
        CreateUseCase $createGameUseCase,
        GuessUseCase $guessUseCase,
        GameInfoUseCase $gameInfoUseCase
    )
    {
        $this->createUseCase = $createGameUseCase;
        $this->guessUseCase = $guessUseCase;
        $this->gameInfoUseCase = $gameInfoUseCase;
    }

    /**
     * @Route("/api/game", methods={"POST"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createAction(Request $request): JsonResponse
    {
        try {
            $gameName = $request->request->get('name');
            $maxAttempts = $request->request->getInt('max_attempts');
            $combination = ColorCombination::fromCombination($request->request->get('combination'));

            $command = new CreateCommand(Uuid::uuid4(), $gameName, $maxAttempts, $combination);

            return $this->json($this->createUseCase->execute($command));
        } catch (\Exception $exception) {
            return $this->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Route("/api/game/{uuid}", methods={"GET"})
     *
     * Game contains all the guess_attempts + the winner combination
     *
     * @param string $uuid
     * @return JsonResponse
     */
    public function gameHistoricAction(string $uuid): JsonResponse
    {
        try {
            $gameInfoCommand = new GameInfoCommand(Uuid::fromString($uuid));
            $game = $this->gameInfoUseCase->execute($gameInfoCommand);

            return $this->json($game);
        } catch (GameNotFound $gameNotFound) {
            return $this->json($gameNotFound->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @Route("/api/game/{uuid}/guess_attempt")
     *
     * @param string $uuid
     * @param Request $request
     * @return JsonResponse
     */
    public function guessAction(string $uuid, Request $request): JsonResponse
    {
        try {
            $guessAttempt = $request->request->get('guess_attempt');
            $guessCommand = new GuessCommand(Uuid::fromString($uuid), ColorCombination::fromCombination($guessAttempt));

            return $this->json($this->guessUseCase->execute($guessCommand));
        } catch (\Exception $exception) {
            return $this->json($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
