<?php


namespace App\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class PhotoFormatExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('photoFormat', [$this, 'photoFormat'])
        ];
    }

    public function photoFormat($value): string
    {
        if (str_contains($value, "https://")){
            return $value;
        }

        return "uploads/".$value;
    }
}