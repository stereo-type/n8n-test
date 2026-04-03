<?php

declare(strict_types=1);

namespace App\Features\Registration\Application\Service;

use App\Features\Registration\Application\DTO\RegistrationDTO;
use App\Features\Registration\Application\Exception\EmailAlreadyExistsException;
use App\Features\Registration\Domain\Entity\User;
use App\Features\Registration\Infrastructure\Repository\UserRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function register(RegistrationDTO $dto): User
    {
        if ($this->userRepository->emailExists($dto->getEmail())) {
            throw new EmailAlreadyExistsException($dto->getEmail());
        }

        $user = new User();
        $user->setEmail($dto->getEmail());
        $user->setFirstName($dto->getFirstName());
        $user->setLastName($dto->getLastName());
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, $dto->getPlainPassword())
        );

        $this->userRepository->save($user);

        $this->logger->info('User registered successfully', [
            'email' => $user->getEmail(),
        ]);

        return $user;
    }
}
