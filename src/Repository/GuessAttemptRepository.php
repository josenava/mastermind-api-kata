<?php
declare(strict_types=1);

namespace App\Repository;


use App\Entity\GuessAttempt;

interface GuessAttemptRepository
{
    /**
     * @param GuessAttempt $guessAttempt
     * @return GuessAttempt
     */
    public function save(GuessAttempt $guessAttempt): GuessAttempt;
}