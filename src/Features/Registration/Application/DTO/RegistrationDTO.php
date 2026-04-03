<?php

declare(strict_types=1);

namespace App\Features\Registration\Application\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class RegistrationDTO
{
    #[Assert\NotBlank(message: 'registration.email.not_blank')]
    #[Assert\Email(message: 'registration.email.invalid')]
    #[Assert\Length(max: 180, maxMessage: 'registration.email.too_long')]
    private string $email = '';

    #[Assert\NotBlank(message: 'registration.first_name.not_blank')]
    #[Assert\Length(min: 2, max: 100, minMessage: 'registration.first_name.too_short', maxMessage: 'registration.first_name.too_long')]
    private string $firstName = '';

    #[Assert\NotBlank(message: 'registration.last_name.not_blank')]
    #[Assert\Length(min: 2, max: 100, minMessage: 'registration.last_name.too_short', maxMessage: 'registration.last_name.too_long')]
    private string $lastName = '';

    #[Assert\NotBlank(message: 'registration.password.not_blank')]
    #[Assert\Length(min: 8, max: 255, minMessage: 'registration.password.too_short', maxMessage: 'registration.password.too_long')]
    #[Assert\Regex(
        pattern: '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
        message: 'registration.password.weak'
    )]
    private string $plainPassword = '';

    #[Assert\NotBlank(message: 'registration.confirm_password.not_blank')]
    #[Assert\EqualTo(propertyPath: 'plainPassword', message: 'registration.confirm_password.mismatch')]
    private string $confirmPassword = '';

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getConfirmPassword(): string
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword(string $confirmPassword): self
    {
        $this->confirmPassword = $confirmPassword;

        return $this;
    }
}
