<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PeopleRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: PeopleRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class People
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Assert\Length(min: 2)]
    #[ORM\Column(type: 'string', length: 255)]
    private $firstname;

    #[Assert\Length(min: 2, max: 255)]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $lastname;

    #[ORM\Column(type: 'date')]
    private $dateofbirth;

    #[Assert\Choice([true, false])]
    #[ORM\Column(type: 'boolean')]
    private $sex;

    #[Assert\Email()]
    #[ORM\Column(type: 'string', length: 255)]
    private $email;

    #[Assert\NotBlank()]
    #[Assert\NotNull()]
    #[ORM\Column(type: 'string', length: 255)]
    private $post;

    // #[Assert\Length(minMessage: "This value cannot have at least 5 characters")]
    #[ORM\Column(type: 'text', nullable: true)]
    private $topic;

    #[ORM\Column(type: 'date')]
    private $startdate;

    #[ORM\Column(type: 'date')]
    private $enddate;

    #[Assert\Length(min: 150, max: 255)]
    #[ORM\Column(type: 'text', nullable: true)]
    private $result;

    #[Assert\NotNull()]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 255)]
    #[ORM\Column(type: 'string', length: 150)]
    private $school;

    #[Assert\Choice([0, 1, 2, 3, 4, 5])]
    #[ORM\Column(type: 'integer')]
    private $level;

    #[Assert\NotNull()]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 255)]
    #[ORM\Column(type: 'string', length: 100)]
    private $domain;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'people')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\OneToMany(mappedBy: 'people', targetEntity: Certificate::class, orphanRemoval: true)]
    private $certificates;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $internshipletter;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $report;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $otherfile;

    public function __construct()
    {
        $this->certificates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname($lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getDateofbirth(): ?\DateTimeInterface
    {
        return $this->dateofbirth;
    }

    public function setDateofbirth(\DateTimeInterface $dateofbirth): self
    {
        $this->dateofbirth = $dateofbirth;

        return $this;
    }

    public function getSex(): ?bool
    {
        return $this->sex;
    }

    public function setSex(bool $sex): self
    {
        $this->sex = $sex;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPost(): ?string
    {
        return $this->post;
    }

    public function setPost($post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getTopic(): ?string
    {
        return $this->topic;
    }

    public function setTopic(?string $topic): self
    {
        $this->topic = $topic;

        return $this;
    }

    public function getStartdate(): ?\DateTimeInterface
    {
        return $this->startdate;
    }

    public function setStartdate(\DateTimeInterface $startdate): self
    {
        $this->startdate = $startdate;

        return $this;
    }

    public function getEnddate(): ?\DateTimeInterface
    {
        return $this->enddate;
    }

    public function setEnddate(\DateTimeInterface $enddate): self
    {
        $this->enddate = $enddate;

        return $this;
    }

    public function getResult(): ?string
    {
        return $this->result;
    }

    public function setResult(?string $result): self
    {
        $this->result = $result;

        return $this;
    }

    public function getSchool(): ?string
    {
        return $this->school;
    }

    public function setSchool(string $school): self
    {
        $this->school = $school;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getDomain(): ?string
    {
        return $this->domain;
    }

    public function setDomain(string $domain): self
    {
        $this->domain = $domain;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Files>
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    /**
     * @return Collection<int, Certificate>
     */
    public function getCertificates(): Collection
    {
        return $this->certificates;
    }

    public function addCertificate(Certificate $certificate): self
    {
        if (!$this->certificates->contains($certificate)) {
            $this->certificates[] = $certificate;
            $certificate->setPeople($this);
        }

        return $this;
    }

    public function removeCertificate(Certificate $certificate): self
    {
        if ($this->certificates->removeElement($certificate)) {
            // set the owning side to null (unless already changed)
            if ($certificate->getPeople() === $this) {
                $certificate->setPeople(null);
            }
        }

        return $this;
    }

    public function getSlug(): string
    {
        $slug = new Slugify();
        return $slug->slugify($this->getFirstname());
    }

    public function getInternshipletter(): ?string
    {
        return $this->internshipletter;
    }

    public function setInternshipletter(?string $internshipletter): self
    {
        $this->internshipletter = $internshipletter;

        return $this;
    }

    public function getReport(): ?string
    {
        return $this->report;
    }

    public function setReport(?string $report): self
    {
        $this->report = $report;

        return $this;
    }

    public function getOtherfile(): ?string
    {
        return $this->otherfile;
    }

    public function setOtherfile(?string $otherfile): self
    {
        $this->otherfile = $otherfile;

        return $this;
    }

    public function duration() : string
    {
        $start =$this->getStartdate();
        $end = $this->getEnddate(); 
        $interval = $end->diff($start);
        $month = (int) $interval->format('%m');
        $days = (int) $interval->format('%d');
        return $month + " month " + $days + " days";
    }
}
