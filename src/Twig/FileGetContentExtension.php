<?php


namespace App\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class FileGetContentExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('fileGetContent', [$this, 'fileGetContent'])
        ];
    }

    public function fileGetContent ($value){

        if($value) {
            $mimeType = $value->getClientMimeType();
            $test = realpath($value);
            $imageData = base64_encode(file_get_contents($test));

            $src = 'data: ' . $mimeType . ';base64,' . $imageData;

            return $src;
        }

//        dd($image = $value->getClientOriginalName());
//        $fileGetContent = file_get_contents($value);
//
//        $fileGetContent = 'data: {".jpg"};base64,'.$fileGetContent;
//
//        return $fileGetContent;
    }
}