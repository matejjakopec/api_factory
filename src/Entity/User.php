<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity()]
class User implements PasswordAuthenticatedUserInterface, UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Assert\NotBlank]
    private $username;

    #[ORM\Column(type: 'string', length: 255)]
    private $password;

    #[ORM\Column(type: 'datetime')]
    private $contractStartDate;

    #[ORM\Column(type: 'datetime')]
    private $contractEndDate;

    #[ORM\Column(type: 'string', length: 255)]
    private $type;

    #[ORM\Column(type: 'boolean')]
    private $verified;

    #[ORM\Column(type: 'datetime')]
    private $tokenValidAfter;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getContractStartDate()
    {
        return $this->contractStartDate;
    }


    public function setContractStartDate($contractStartDate): self
    {
        $this->contractStartDate = $contractStartDate;
        return $this;
    }


    public function getContractEndDate()
    {
        return $this->contractEndDate;
    }


    public function setContractEndDate($contractEndDate): self
    {
        $this->contractEndDate = $contractEndDate;
        return $this;
    }


    public function getType()
    {
        return $this->type;
    }


    public function setType($type): self
    {
        $this->type = $type;
        return $this;
    }


    public function getVerified()
    {
        return $this->verified;
    }


    public function setVerified($verified): self
    {
        $this->verified = $verified;
        return $this;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials()
    {

    }

    public function getUserIdentifier(): string
    {
        return $this->id;
    }

    public function getTokenValidAfter()
    {
        return $this->tokenValidAfter;
    }

    public function setTokenValidAfter($tokenValidAfter): self
    {
        $this->tokenValidAfter = $tokenValidAfter;
        return $this;
    }
}