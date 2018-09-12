<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 08/06/17
 * Time: 13:51
 */

namespace AppBundle\Services;


use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDir;

    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }
    public function upload(UploadedFile $file)
    {
        //$fileName =  md5(uniqid()) . '.' . $file->guessExtension();
        //$fileName =  md5(uniqid()) . '-' . $file->getClientOriginalName();
        $fileName =  $file->getClientOriginalName();
        $file->move($this->targetDir, $fileName);
        return $fileName;
    }
    public function getTargetDir()
    {
        return $this->targetDir;
    }

    /**
     * Set targetDir
     *
     * @param string $targetDir
     *
     * @return Cloud
     */
    public function setTargetDir($targetDir)
    {
        $this->targetDir = $targetDir;
        return $this;
    }
}