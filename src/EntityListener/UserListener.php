<?php


namespace App\EntityListener;

use App\Entity\User;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UserListener
{
    private FileUploader $fileUploader;

    public function __construct(FileUploader $fileUploader){
        $this->fileUploader = $fileUploader;
    }

    public function prePersist(User $user){
        $this->upload($user);
    }

    public function preFlush(User $user){
        if ($user->getFile() !== null) {
            $this->upload($user);
        }
    }

    public function upload(User $user){
        if($user->getFile() instanceof UploadedFile){
            $photoFilename = $this->fileUploader->upload($user->getFile());
            $user->setPhoto($photoFilename);
        }
    }
}
