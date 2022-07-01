<?php

    namespace App\Entity\Trait;
    
    use Doctrine\ORM\Mapping as ORM;

    /**
     * This trait
     */
    trait Updatedable
    {
        #[ORM\Column(type: 'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP'])]
        private $updated_at;
        
        public function getupdatedAt(): ?\DateTimeImmutable
        {
            return $this->updated_at;
        }

        public function setupdatedAt(\DateTimeImmutable $updated_at): self
        {
            $this->updated_at = $updated_at;

            return $this;
        }
    }
    