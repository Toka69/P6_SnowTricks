<?php


namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Class FileUploader
 * @package App\Service
 */
class FileUploader
{
    private string $targetDirectory;

    private SluggerInterface $slugger;

    public function __construct($targetDirectory, SluggerInterface $slugger)
    {
        $this->targetDirectory = $targetDirectory;
        $this->slugger = $slugger;
    }

    public function upload(UploadedFile $file)
    {
        $originalFilename = $file->getPathInfo();
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
        $file->move($this->targetDirectory, $newFilename);

        return $newFilename;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}
