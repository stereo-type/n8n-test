<?php

declare(strict_types=1);

namespace App\Features\Registration\Application\Exception;

final class EmailAlreadyExistsException extends \DomainException
{
    public function __construct(string $email)
    {
        parent::__construct(\sprintf('User with email "%s" already exists.', $email));
    }
}
