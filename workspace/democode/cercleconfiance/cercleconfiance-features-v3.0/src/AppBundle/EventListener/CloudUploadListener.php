<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 08/06/17
 * Time: 18:30
 */

namespace AppBundle\EventListener;


use AppBundle\Services\FileUploader;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Cloud;
class CloudUploadListener
{
    private $uploader;
    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;
    }
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $this->uploadFile($entity);
    }
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();
        $this->uploadFile($entity);
    }
    public function postLoad(LifecycleEventArgs $args)
    {
        if(isset ($_SERVER['argv']) && !$_SERVER['argv'][1] === 'doctrine:fixtures:load') {

            $entity = $args->getEntity();
            if (!$entity instanceof Cloud) {
                return;
            }
            if ($fileName = $entity->getFileName()) {
                $entity->setFileName(new File($this->uploader->getTargetDir() . '/' . $fileName));
            }
        }
    }
    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof Cloud) {
            return;
        }
        if(is_file($entity->getFileName())) {
            unlink($entity->getFileName());
        }
    }
    private function uploadFile($entity)
    {
        if (!$entity instanceof Cloud) {
            return;
        }
        $file = $entity->getFileName();
        if (!$file instanceof UploadedFile) {
            return;
        }
        $targetDir = $entity->getTargetDir();
        $this->uploader->setTargetDir($this->uploader->getTargetDir(). '/' . $targetDir);
        $fileName = $this->uploader->upload($file);
        $entity->setFileName($targetDir . '/' . $fileName);
    }

    /**
     * @return FileUploader
     */
    public function getUploader()
    {
        return $this->uploader;
    }

    /**
     * @param FileUploader $uploader
     * @return UserUploadListener
     */
    public function setUploader($uploader)
    {
        $this->uploader = $uploader;
        return $this;
    }

}