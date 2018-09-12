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
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use UserBundle\Entity\User;

class UserUploadListener
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
            if (!$entity instanceof User) {
                return;
            }

            if ($fileName = $entity->getAvatar()) {
                $entity->setAvatar(new File($this->uploader->getTargetDir() . '/' . $fileName));
            }
        }
    }
    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof User) {
            return;
        }
        if(is_file($entity->getAvatar())) {
            unlink($entity->getAvatar());
        }
    }
    private function uploadFile($entity)
    {
        if (!$entity instanceof User) {
            return;
        }
        $file = $entity->getAvatar();
        if (!$file instanceof UploadedFile) {
            return;
        }
        $fileName = $this->uploader->upload($file);
        $entity->setAvatar($fileName);
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