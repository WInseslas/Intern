<?php

    namespace App\Entity;

    use Cocur\Slugify\Slugify;
    use Doctrine\ORM\Mapping as ORM;
    use App\Entity\Trait\Createdable;
    use App\Repository\CertificateRepository;
    use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

    

    #[UniqueEntity('coded')]
    #[ORM\Entity(repositoryClass: CertificateRepository::class)]
    class Certificate
    {
        use Createdable;

        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column(type: 'integer')]
        private $id;

        #[ORM\Column(type: 'string', length: 255, unique: true)]
        private $coded;

        #[ORM\ManyToOne(targetEntity: Template::class, inversedBy: 'certificates')]
        #[ORM\JoinColumn(nullable: false)]
        private $template;
      
        #[ORM\ManyToOne(targetEntity: People::class, inversedBy: 'certificates')]
        #[ORM\JoinColumn(nullable: false)]
        private $people;

        #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'certificates')]
        #[ORM\JoinColumn(nullable: false)]
        private $user;

        #[ORM\Column(type: 'boolean', nullable: true)]
        private $isverified;

        // #[ORM\OneToMany(mappedBy: 'certificate', targetEntity: Userhasverified::class, orphanRemoval: true)]
        // private $userhasverifieds;

        public function __construct()
        {
            $this->created_at = new \DateTimeImmutable();
        }

        public function getId(): ?int
        {
            return $this->id;
        }

        public function getCoded(): ?string
        {
            return $this->coded;
        }

        public function setCoded(string $coded): self
        {
            $this->coded = $coded;

            return $this;
        }

        public function getTemplate(): ?Template
        {
            return $this->template;
        }

        public function setTemplate(?Template $template): self
        {
            $this->template = $template;

            return $this;
        }

        public function getPeople(): ?People
        {
            return $this->people;
        }

        public function setPeople(?People $people): self
        {
            $this->people = $people;

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

        public function getSlug() : string
        {
            $slug = new Slugify();
            return $slug->slugify($this->getCoded());
        }

        public function isIsverified(): ?bool
        {
            return $this->isverified;
        }

        public function setIsverified(?bool $isverified): self
        {
            $this->isverified = $isverified;

            return $this;
        }
    }
