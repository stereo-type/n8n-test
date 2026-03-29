<?php

declare(strict_types=1);

namespace App\Tests\Features\Registration\Application\Service;

use App\Features\Registration\Application\DTO\RegistrationDTO;
use App\Features\Registration\Application\Exception\EmailAlreadyExistsException;
use App\Features\Registration\Application\Service\RegistrationService;
use App\Features\Registration\Domain\Entity\User;
use App\Features\Registration\Infrastructure\Repository\UserRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class RegistrationServiceTest extends TestCase
{
    private UserRepository & MockObject $userRepository;
    private UserPasswordHasherInterface & MockObject $passwordHasher;
    private LoggerInterface & MockObject $logger;
    private RegistrationService $registrationService;

    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->registrationService = new RegistrationService(
            $this->userRepository,
            $this->passwordHasher,
            $this->logger,
        );
    }

    public function testRegisterCreatesUserSuccessfully(): void
    {
        $dto = $this->buildDTO('user@example.com', 'Ivan', 'Ivanov', 'Password1');

        $this->userRepository
            ->expects(self::once())
            ->method('emailExists')
            ->with('user@example.com')
            ->willReturn(false);

        $this->passwordHasher
            ->expects(self::once())
            ->method('hashPassword')
            ->with(self::isInstanceOf(User::class), 'Password1')
            ->willReturn('$2y$13$abcdefghijklmnopqrstuuVGZzH3P2nFtsCu3bfcNPTwIH8DWdK5S');

        $this->userRepository
            ->expects(self::once())
            ->method('save')
            ->with(self::isInstanceOf(User::class));

        $this->logger
            ->expects(self::once())
            ->method('info')
            ->with('User registered successfully', ['email' => 'user@example.com']);

        $user = $this->registrationService->register($dto);

        self::assertSame('user@example.com', $user->getEmail());
        self::assertSame('Ivan', $user->getFirstName());
        self::assertSame('Ivanov', $user->getLastName());
        self::assertSame('$2y$13$abcdefghijklmnopqrstuuVGZzH3P2nFtsCu3bfcNPTwIH8DWdK5S', $user->getPassword());
    }

    public function testRegisterThrowsWhenEmailAlreadyExists(): void
    {
        $dto = $this->buildDTO('exists@example.com', 'Ivan', 'Ivanov', 'Password1');

        $this->userRepository
            ->expects(self::once())
            ->method('emailExists')
            ->with('exists@example.com')
            ->willReturn(true);

        $this->userRepository
            ->expects(self::never())
            ->method('save');

        $this->expectException(EmailAlreadyExistsException::class);

        $this->registrationService->register($dto);
    }

    public function testRegisteredUserHasDefaultRoleUser(): void
    {
        $dto = $this->buildDTO('user@example.com', 'Ivan', 'Ivanov', 'Password1');

        $this->userRepository->method('emailExists')->willReturn(false);
        $this->passwordHasher->method('hashPassword')->willReturn('$2y$13$abcdefghijklmnopqrstuuVGZzH3P2nFtsCu3bfcNPTwIH8DWdK5S');
        $this->userRepository->method('save');
        $this->logger->method('info');

        $user = $this->registrationService->register($dto);

        self::assertContains('ROLE_USER', $user->getRoles());
    }

    private function buildDTO(
        string $email,
        string $firstName,
        string $lastName,
        string $password,
    ): RegistrationDTO {
        $dto = new RegistrationDTO();
        $dto->setEmail($email);
        $dto->setFirstName($firstName);
        $dto->setLastName($lastName);
        $dto->setPlainPassword($password);
        $dto->setConfirmPassword($password);

        return $dto;
    }
}
