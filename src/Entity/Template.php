<?php

    namespace App\Entity;

    use Cocur\Slugify\Slugify;
    use Doctrine\ORM\Mapping as ORM;
    use App\Entity\Trait\Createdable;
    use App\Repository\TemplateRepository;
    use Doctrine\Common\Collections\Collection;
    use Doctrine\Common\Collections\ArrayCollection;
    use Symfony\Component\Validator\Constraints as Assert;


    #[ORM\Entity(repositoryClass: TemplateRepository::class)]
    class Template
    {
        use Createdable;
        
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column(type: 'integer')]
        private $id;

        #[Assert\NotNull()]
        #[Assert\NotBlank()]
        #[Assert\Length(min: 5)]
        #[ORM\Column(type: 'string', length: 100)]
        private $wording;

        #[ORM\Column(type: 'string', length: 255)]
        private $file;

        #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'templates')]
        #[ORM\JoinColumn(nullable: false)]
        private $author;

        #[ORM\OneToMany(mappedBy: 'template', targetEntity: Certificate::class, orphanRemoval: true)]
        private $certificates;

        #[ORM\Column(type: 'string', length: 255)]
        private $coordinates;

        public function __construct()
        {
            $this->certificates = new ArrayCollection();
            $this->created_at = new \DateTimeImmutable();

        }
        
        public function getId(): ?int
        {
            return $this->id;
        }

        public function getWording(): ?string
        {
            return $this->wording;
        }

        public function setWording(string $wording): self
        {
            $this->wording = $wording;

            return $this;
        }

        public function getFile(): ?string
        {
            // $dir = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . "public" . DIRECTORY_SEPARATOR . "assets" . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . "templates" . DIRECTORY_SEPARATOR;
            return $this->file;
        }

        public function setFile(string $file): self
        {
            $this->file = $file;

            return $this;
        }

        public function getAuthor(): ?User
        {
            return $this->author;
        }

        public function setAuthor(?User $author): self
        {
            $this->author = $author;

            return $this;
        }

        public function getSlug() : string
        {
            $slug = new Slugify();
            return $slug->slugify($this->getFile());
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
                $certificate->setTemplate($this);
            }

            return $this;
        }

        public function removeCertificate(Certificate $certificate): self
        {
            if ($this->certificates->removeElement($certificate)) {
                // set the owning side to null (unless already changed)
                if ($certificate->getTemplate() === $this) {
                    $certificate->setTemplate(null);
                }
            }

            return $this;
        }

        public function getCoordinates(): ?string
        {
            return $this->coordinates;
        }

        public function setCoordinates(string $coordinates): self
        {
            $this->coordinates = $coordinates;

            return $this;
        }
    }
