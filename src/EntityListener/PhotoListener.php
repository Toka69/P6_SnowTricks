<?php


namespace App\EntityListener;

use App\Entity\Photo;
use App\Service\FileUploader;

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

    /**
     * @param Photo $photo
     */
    public function preFlush(Photo $photo){
        if($photo->getFile()) {
            $photoFilename = $this->fileUploader->upload($photo->getFile());
            $photo->setLocation($photoFilename);
      }
    }
}