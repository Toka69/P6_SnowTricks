<?php


namespace App\EntityListener;

use App\Entity\Photo;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class PhotoListener
 * @package App\EntityListener
 */
class PhotoListener
{
    /**
     * @var FileUploader
     */
    private FileUploader $fileUploader;

    public function __construct(FileUploader $fileUploader){
        $this->fileUploader = $fileUploader;
    }
    //When add a new photo
    public function prePersist(Photo $photo){
        $this->upload($photo);
    }

    //when update photo collection
    public function preFlush(Photo $photo){
        if (!is_null($photo->getId())) {
            $this->upload($photo);
        }
    }

    /**
     * @param Photo $photo
     */
    public function upload(Photo $photo){
        if($photo->getFile() instanceof UploadedFile){
            $photoFilename = $this->fileUploader->upload($photo->getFile());
            $photo->setLocation($photoFilename);
        }
    }
}
