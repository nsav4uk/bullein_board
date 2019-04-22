<?php declare(strict_types=1);

namespace App\EventListener;

use App\Entity\{Announcement, Image};
use App\Service\FileUploader;
use Doctrine\ORM\Event\{LifecycleEventArgs, PreUpdateEventArgs};

/**
 * Class ImageUploadListener
 * @package App\EventListener
 */
class ImageUploadListener
{
    /** @var FileUploader */
    private $uploader;

    /**
     * ImageUploadListener constructor.
     * @param FileUploader $uploader
     */
    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    /**
     * @param $entity
     * @throws \Exception
     */
    private function uploadFile($entity): void
    {
        if (!$entity instanceof Announcement) {
            return;
        }

        $files = $entity->getFiles();

        foreach ($files as $file) {
            $fileName = $this->uploader->upload($file);
            $image = new Image();
            $entity->addImage($image->setName($fileName));
        }
    }
}
