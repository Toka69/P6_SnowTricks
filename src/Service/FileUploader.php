<?php


namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;


class FileUploader
    {
    private string $targetDirectory;
    private SluggerInterface $slugger;

    public function __construct($targetDirectory, SluggerInterface $slugger){
        $this->targetDirectory = $targetDirectory;
        $this->slugger = $slugger;
    }

    public function upload(UploadedFile $file){
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            $file->move($this->targetDirectory, $newFilename);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $newFilename;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}