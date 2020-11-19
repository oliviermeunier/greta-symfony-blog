<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private $postImageDirectory;

    public function __construct(string $postImageDirectory)
    {
        $this->postImageDirectory = $postImageDirectory;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('asset_post_image', [$this, 'assetPostImage']),
        ];
    }

    public function assetPostImage($image)
    {
        // Si $image contient une URL
        if (filter_var($image, FILTER_VALIDATE_URL)) {

            // On retourne directement $image sans faire de modifications
            return $image;
        }

        // Sinon on concatène le chemin vers le dossier des images
        return $this->postImageDirectory . '/' . $image;
    }
}
