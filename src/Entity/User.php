<?php

    namespace App\Entity;

    use Cocur\Slugify\Slugify;
    use Doctrine\ORM\Mapping as ORM;
    use App\Entity\Trait\Createdable;
    use App\Entity\Trait\Updatedable;
    use App\Repository\UserRepository;
    use Doctrine\Common\Collections\Collection;
    use Symfony\Component\HttpFoundation\File\File;
    use Doctrine\Common\Collections\ArrayCollection;
    use Vich\UploaderBundle\Mapping\Annotation as Vich;
    use Symfony\Component\Validator\Constraints as Assert;
    use Symfony\Component\HttpFoundation\File\UploadedFile;
    use Symfony\Component\Security\Core\User\UserInterface;
    use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
    use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

    #[Vich\Uploadable] 
    #[ORM\Entity(repositoryClass: UserRepository::class)]
    #[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
    class User implements UserInterface, PasswordAuthenticatedUserInterface
    {
        use Createdable, Updatedable;
        
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column(type: 'integer')]
        private $id;

        #[Assert\Email()]
        #[Assert\NotNull()]
        #[Assert\NotBlank()]
        #[ORM\Column(type: 'string', length: 180, unique: true)]
        private $email;

        #[ORM\Column(type: 'json')]
        private $roles = [];

        #[Assert\NotNull()]
        #[Assert\NotBlank()]
        #[Assert\Length(min: 6)]
        #[ORM\Column(type: 'string')]
        private $password = "password"; 

        #[Assert\NotNull()]
        #[Assert\NotBlank()]
        #[Assert\Length(min: 6, max: 255)]
        #[ORM\Column(type: 'string', length: 255)]
        private $fullname;

        #[ORM\OneToMany(mappedBy: 'user', targetEntity: People::class, orphanRemoval: true)]
        private $people;

        #[ORM\OneToMany(mappedBy: 'author', targetEntity: Template::class, orphanRemoval: true)]
        private $templates;

        #[ORM\OneToMany(mappedBy: 'user', targetEntity: Certificate::class, orphanRemoval: true)]
        private $certificates;

        #[ORM\Column(type: 'string', length: 255, nullable: true)]
        private $reset_token;

        #[Vich\UploadableField(mapping: 'profile', fileNameProperty: 'avatar')]
        private ?File $imageFile = null;
       
        #[ORM\Column(type: 'string', length: 255, nullable: true)]
        private $avatar;

        public function __construct()
        {
            $this->people = new ArrayCollection();
            $this->templates = new ArrayCollection();
            $this->certificates = new ArrayCollection();
            $this->created_at = new \DateTimeImmutable();
            $this->updated_at = new \DateTimeImmutable();
        }

        public function getId(): ?int
        {
            return $this->id;
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

        /**
         * A visual identifier that represents this user.
         *
         * @see UserInterface
         */
        public function getUserIdentifier(): string
        {
            return (string) $this->email;
        }

        /**
         * @see UserInterface
         */
        public function getRoles(): array
        {
            $roles = $this->roles;
            // guarantee every user at least has ROLE_USER
            $roles[] = 'ROLE_USER';
            return array_unique($roles);
        }

        public function setRoles(array $roles): self
        {
            $this->roles = $roles;

            return $this;
        }

        /**
         * @see PasswordAuthenticatedUserInterface
         */
        public function getPassword(): string
        {
            return $this->password;
        }

        public function setPassword(string $password): self
        {
            $this->password = $password;

            return $this;
        }

        /**
         * @see UserInterface
         */
        public function eraseCredentials()
        {
            // If you store any temporary, sensitive data on the user, clear it here
            // $this->plainPassword = null;
        }

        public function getFullname(): ?string
        {
            return $this->fullname;
        }

        public function setFullname(string $fullname): self
        {
            $this->fullname = $fullname;

            return $this;
        }

        /**
         * @return Collection<int, People>
         */
        public function getPeople(): Collection
        {
            return $this->people;
        }

        public function addPerson(People $person): self
        {
            if (!$this->people->contains($person)) {
                $this->people[] = $person;
                $person->setUser($this);
            }

            return $this;
        }

        public function removePerson(People $person): self
        {
            if ($this->people->removeElement($person)) {
                // set the owning side to null (unless already changed)
                if ($person->getUser() === $this) {
                    $person->setUser(null);
                }
            }
            return $this;
        }

        /**
         * @return Collection<int, Template>
         */
        public function getTemplates(): Collection
        {
            return $this->templates;
        }

        public function addTemplate(Template $template): self
        {
            if (!$this->templates->contains($template)) {
                $this->templates[] = $template;
                $template->setAuthor($this);
            }

            return $this;
        }

        public function removeTemplate(Template $template): self
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
                $certificate->setUser($this);
            }

            return $this;
        }

        public function removeCertificate(Certificate $certificate): self
        {
            if ($this->certificates->removeElement($certificate)) {
                // set the owning side to null (unless already changed)
                if ($certificate->getUser() === $this) {
                    $certificate->setUser(null);
                }
            }

            return $this;
        }

        public function getResetToken(): ?string
        {
            return $this->reset_token;
        }

        public function setResetToken(string $reset_token): self
        {
            $this->reset_token = $reset_token;

            return $this;
        }

        /**
         * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
         * of 'UploadedFile' is injected into this setter to trigger the update. If this
         * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
         * must be able to accept an instance of 'File' as the bundle will inject one here
         * during Doctrine hydration.
         *
         * @param File|UploadedFile|null $imageFile
         * @return self
         */
        public function setImageFile(?File $imageFile = null): self
        {
            $this->imageFile = $imageFile;
            if (null !== $imageFile) {
                // It is required that at least one field changes if you are using doctrine
                // otherwise the event listeners won't be called and the file is lost
                $this->updated_at = new \DateTimeImmutable();
            }
            return $this;
        }

        public function getImageFile(): ?File
        {
            return $this->imageFile;
        }

        public function setAvatar(?string $avatar): self
        {
            $this->avatar = $avatar;
            return $this;
        }
    
        public function getAvatar(): ?string
        {
            return $this->avatar;
        }

        public function getSlug() : string
        {
            $slug = new Slugify();
            return $slug->slugify($this->getFullname());
        }
    }
