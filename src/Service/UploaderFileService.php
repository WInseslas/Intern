<?php
    namespace App\Service;

    use Symfony\Component\HttpFoundation\File\Exception\FileException;
    use Symfony\Component\HttpFoundation\File\UploadedFile;
    use Symfony\Component\String\Slugger\SluggerInterface;

    class UploaderFileService
    {
        private $targetDirectory;
        private $peopleDirectory;
        private $slugger;

        public function __construct(String $targetDirectory, String $peopleDirectory, SluggerInterface $slugger)
        {
            $this->targetDirectory = $targetDirectory;
            $this->peopleDirectory = $peopleDirectory;
            $this->slugger = $slugger;
        }

        public function upload(UploadedFile $file, string $target = null)
        {
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $this->slugger->slug($originalFilename);
            $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

            try {
                if (is_null($target)) {
                    $file->move($this->getPeopleDirectory(), $fileName);
                } else {
                    $file->move($this->getTargetDirectory(), $fileName);
                }
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
                die("An error occurred while downloading the file ". $e->getMessage());
            }
            
            return $fileName;
        }

        public function getTargetDirectory() : String
        {
            return $this->targetDirectory;
        }

        public function getPeopleDirectory() : String
        {
            return $this->peopleDirectory;
        }
    }