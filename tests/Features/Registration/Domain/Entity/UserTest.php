<?php

declare(strict_types=1);

namespace App\Tests\Features\Registration\Domain\Entity;

use App\Features\Registration\Domain\Entity\User;
use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase
{
    public function testUserHasRoleUserByDefault(): void
    {
        $user = new User();

        self::assertContains('ROLE_USER', $user->getRoles());
    }

    public function testUserIdentifierIsEmail(): void
    {
        $user = new User();
        $user->setEmail('test@example.com');

        self::assertSame('test@example.com', $user->getUserIdentifier());
    }

    public function testSetIdConvertsToInt(): void
    {
        $user = new User();
        $user->setId('42');

        self::assertSame(42, $user->getId());
    }

    public function testSetIdWithNullDoesNotChange(): void
    {
        $user = new User();
        $user->setId(null);

        self::assertNull($user->getId());
    }

    public function testToStringReturnsEmail(): void
    {
        $user = new User();
        $user->setEmail('test@example.com');

        self::assertSame('test@example.com', (string) $user);
    }

    public function testCreatedAtIsSetOnConstruct(): void
    {
        $before = new \DateTimeImmutable();
        $user = new User();
        $after = new \DateTimeImmutable();

        self::assertGreaterThanOrEqual($before, $user->getCreatedAt());
        self::assertLessThanOrEqual($after, $user->getCreatedAt());
    }

    public function testIsNotActiveByDefault(): void
    {
        $user = new User();

        self::assertFalse($user->isActive());
    }

    public function testSetPasswordAcceptsHashedPassword(): void
    {
        $user = new User();
        // bcrypt / argon2 hashes always start with '$'
        $hash = '$2y$13$abcdefghijklmnopqrstuuVGZzH3P2nFtsCu3bfcNPTwIH8DWdK5S';
        $user->setPassword($hash);

        self::assertSame($hash, $user->getPassword());
    }

    public function testSetPasswordRejectsPlainText(): void
    {
        $user = new User();

        $this->expectException(\InvalidArgumentException::class);

        $user->setPassword('short');
    }

    public function testSetIdAcceptsIntegerString(): void
    {
        $user = new User();
        $user->setId('99');

        self::assertSame(99, $user->getId());
    }

    public function testSetIdAcceptsInt(): void
    {
        $user = new User();
        $user->setId(7);

        self::assertSame(7, $user->getId());
    }
}
