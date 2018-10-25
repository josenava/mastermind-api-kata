<?php
declare(strict_types=1);

namespace App\Controller;


use App\Command\Game\CreateCommand;
use App\UseCase\Game\CreateUseCase;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GameController extends Controller
{
    /**
     * @var CreateUseCase
     */
    private $createUseCase;

    public function __construct(CreateUseCase $createGameUseCase)
    {
        $this->createUseCase = $createGameUseCase;
    }

    public function createAction(Request $request): JsonResponse
    {
        try {
            $gameName = $request->request->get('name');
            $maxAttempts = $request->request->getInt('max_attempts');
            $combination = $request->request->get('combination');

            $command = new CreateCommand(Uuid::uuid4(), $gameName, $maxAttempts, $combination);

            return $this->json($this->createUseCase->execute($command));
        } catch (\Exception $exception) {
            return $this->json($exception->getMessage());
        }
    }
}
