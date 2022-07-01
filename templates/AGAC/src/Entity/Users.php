<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use App\Entity\OtherInformations;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UsersRepository::class)
 */
class Users
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $last_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $first_name;

    /**
     * @ORM\Column(type="date")
     */
    private $date_of_birth;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $login;

    /**
     * @ORM\Column(type="text")
     */
    private $password;

    /**
     * @ORM\ManyToOne(targetEntity=OtherInformations::class, inversedBy="users")
     */
    private $other_informations;

    /**
     * @ORM\OneToMany(targetEntity=Templates::class, mappedBy="author")
     */
    private $templates;

    /**
     * @ORM\OneToMany(targetEntity=UsersHasCertificates::class, mappedBy="users")
     */
    private $certificates;

    public function __construct()
    {
        $this->certificates = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(?string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getDateOfBirth(): ?String
    {
        return $this->date_of_birth->format("Y-m-d");
    }

    public function setDateOfBirth(\DateTimeInterface $date_of_birth): self
    {
        $this->date_of_birth = $date_of_birth;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

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

    public function getOtherInformations(): ?OtherInformations
    {
        return $this->other_informations;
    }

    public function setOtherInformations(?OtherInformations $other_informations): self
    {
        $this->other_informations = $other_informations;

        return $this;
    }

    /**
     * @return Collection<int, Templates>
     */
    public function getTemplates(): Collection
    {
        return $this->templates;
    }

    public function addTemplate(Templates $template): self
    {
        if (!$this->templates->contains($template)) {
            $this->templates[] = $template;
            $template->setAuthor($this);
        }

        return $this;
    }

    public function removeTemplate(Templates $template): self
    {
        if ($this->templates->removeElement($template)) {
            // set the owning side to null (unless already changed)
            if ($template->getAuthor() === $this) {
                $template->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UsersHasCertificates>
     */
    public function getCertificates(): Collection
    {
        return $this->certificates;
    }

    public function addCertificate(UsersHasCertificates $certificate): self
    {
        if (!$this->certificates->contains($certificate)) {
            $this->certificates[] = $certificate;
            $certificate->setUsers($this);
        }

        return $this;
    }

    public function removeCertificate(UsersHasCertificates $certificate): self
    {
        if ($this->certificates->removeElement($certificate)) {
            // set the owning side to null (unless already changed)
            if ($certificate->getUsers() === $this) {
                $certificate->setUsers(null);
            }
        }

        return $this;
    }
    
    /**
     * @method getSlug
     *
     * @return string
     */
    public function getSlug() : string
    {
        $slug = new Slugify();
        return $slug->slugify($this->last_name);
    }
    
    /**
     * @method getYear
     *
     * @return string
     */
    public function getYear() : int
    {
        return (date("Y") - $this->date_of_birth->format("Y"));
    }
    
    /**
     * getStart
     *
     * @return string
     */
    public function getStart() : string
    {
        return $this->getOtherInformations()->getStartDate()->format("Y-m-d");
    }
    
    /**
     * getEnd
     *
     * @return string
     */
    public function getEnd() : string
    {
        return $this->getOtherInformations()->getEndDate()->format("Y-m-d");
    }
}
