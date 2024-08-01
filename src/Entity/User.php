<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
#[ORM\UniqueConstraint(name: "UNIQ_IDENTIFIER_LOGIN_PASS", fields: ["login", "pass"])]
#[UniqueEntity(fields: ["login", "pass"], message: "A user with this login and password already exists, and you now know their credentials")]
class User implements UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 8)]
    #[Assert\NotBlank(message: "Login cannot be blank")]
    #[Assert\Length(max: 8, maxMessage: "Login cannot be longer than 8 characters")]
    private ?string $login = null;

    #[ORM\Column(type: Types::STRING, length: 8)]
    #[Assert\NotBlank(message: "Phone cannot be blank")]
    #[Assert\Length(max: 8, maxMessage: "Phone cannot be longer than 8 characters")]
    private ?string $phone = null;

    #[ORM\Column(type: Types::STRING, length: 8)]
    #[Assert\NotBlank(message: "Password cannot be blank")]
    #[Assert\Length(max: 8, maxMessage: "Password cannot be longer than 8 characters")]
    private ?string $pass = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): static
    {
        $this->login = $login;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getPass(): ?string
    {
        return $this->pass;
    }

    public function setPass(string $pass): static
    {
        $this->pass = $pass;

        return $this;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->login;
    }
}
