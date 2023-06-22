<?php

namespace App\EntityListener;

use App\Entity\Picture;
use Doctrine\ORM\Events;
use App\Service\FileUploader;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;

#[AsEntityListener(event: Events::postRemove, entity: Picture::class)]
class PictureEntityListener
{
    
    public function __construct(
        private FileUploader $fileUploader
    ){
    }

    public function postRemove(Picture $picture, LifecycleEventArgs $event)
    {
        $this->fileUploader->remove($picture->getFile(), 'locations');
    }
}