<?php

namespace App\Entity;

use App\Repository\InternetUsersVerifieCertificatesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InternetUsersVerifieCertificatesRepository::class)
 */
class InternetUsersVerifieCertificates
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=InternetUsers::class)
     */
    private $internet_users;

    /**
     * @ORM\ManyToOne(targetEntity=Certificates::class)
     */
    private $certificates;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInternetUsers(): ?InternetUsers
    {
        return $this->internet_users;
    }

    public function setInternetUsers(?InternetUsers $internet_users): self
    {
        $this->internet_users = $internet_users;

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
