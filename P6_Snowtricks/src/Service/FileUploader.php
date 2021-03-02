<?php

namespace App\Service;


use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;



/**
 * Supports downloaded files
 *
 * @author Edwige Genty
 */
class FileUploader
{
    /**
     * @var $targetDirectory provides path for uploading downloaded files
     */
    private $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    /**
     * Rename file uploaded and move them in the good picture directory
     *
     * @param UploadedFile $file
     *
     * @return string
     */
    public function upload(UploadedFile $file)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    /**
     * Provides path to store uploaded files. Configured in config/service.yaml
     *
     * @return provides
     */
    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}