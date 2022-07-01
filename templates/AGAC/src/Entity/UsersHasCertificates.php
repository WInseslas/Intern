<?php

namespace App\Entity;

use App\Repository\UsersHasCertificatesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UsersHasCertificatesRepository::class)
 */
class UsersHasCertificates
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="certificates")
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity=Certificates::class)
     */
    private $certificates;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsers(): ?Users
    {
        return $this->users;
    }

    public function setUsers(?Users $users): self
    {
        $this->users = $users;

        return $this;
    }

    public function getCertificates(): ?Certificates
    {
        return $this->certificates;
    }

    public function setCertificates(?Certificates $certificates): self
    {
        $this->certificates = $certificates;

        return $this;
    }
}
