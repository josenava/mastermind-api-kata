<?php
declare(strict_types=1);

namespace App\Exception;


final class InvalidColorCombination extends \InvalidArgumentException
{
    public function __construct(array $combination)
    {
        $message = sprintf(
            'The provided combination is invalid, please use the following colors: %s',
            implode(',', $combination)
        );
        parent::__construct($message);
    }
}
