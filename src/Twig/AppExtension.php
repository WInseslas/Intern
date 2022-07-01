<?php
    // src/Twig/AppExtension.php
    namespace App\Twig;

    use Twig\Extension\AbstractExtension;
    use Twig\TwigFunction;

    class AppExtension extends AbstractExtension
    {
        public function getFunctions()
        {
            return [
                new TwigFunction('area', [$this, 'calculateArea']),
                new TwigFunction('profile', [$this, 'defaultImage']),
                new TwigFunction('CustomYear', [$this, 'CustomYears']),
            ];
        }

        public function defaultImage(string $filename): string
        {
           if (strlen(trim($filename)) == 0) {
               return 'prof';
           }

           if (!file_exists($filename)) {
               return 'prof';
           }

            return $filename;
        }

        public function CustomYears(int $year): string
        {
            if ($year == 1) {
               return $year . "An";
            } else {
               return $year . "Ans";
            }
        }

        public function calculateArea(int $width, int $length = 1): int
        {
            return $width * $length;
        }
    }