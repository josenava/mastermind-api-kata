<?php
declare(strict_types=1);

namespace App\Command\Game;


use Ramsey\Uuid\Uuid;

final class CreateCommand
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var int|null
     */
    private $maxAttempts;
    /**
     * @var string
     */
    private $combination;
    /**
     * @var Uuid
     */
    private $uuid;

    /**
     * @param Uuid $uuid
     * @param string $name
     * @param int|null $maxAttempts
     * @param string $combination
     */
    public function __construct(Uuid $uuid, string $name, ?int $maxAttempts, string $combination)
    {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->maxAttempts = $maxAttempts;
        $this->combination = $combination;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return int|null
     */
    public function maxAttempts(): ?int
    {
        return $this->maxAttempts;
    }

    /**
     * @return string
     */
    public function combination(): string
    {
        return $this->combination;
    }

    /**
     * @return Uuid
     */
    public function uuid(): Uuid
    {
        return $this->uuid;
    }
}
