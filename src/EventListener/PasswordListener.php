<?php declare(strict_types=1);

namespace App\EventListener;

use App\Entity\User;
use Doctrine\ORM\Event\{
    LifecycleEventArgs, PreUpdateEventArgs
};
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

/**
 * Class PasswordListener
 * @package App\EventListener
 */
class PasswordListener
{
    /** @var UserPasswordEncoder */
    private $passwordEncoder;

    /**
     * UserListener constructor.
     * @param UserPasswordEncoder $passwordEncoder
     */
    public function __construct(UserPasswordEncoder $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getEntity();

        if ($entity instanceof User && $args->hasChangedField('password')) {
            $entity->setPassword($this->passwordEncoder->encodePassword($entity, $entity->getPassword()));
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if ($entity instanceof User && $entity->getPassword() !== null) {
            $entity->setPassword($this->passwordEncoder->encodePassword($entity, $entity->getPassword()));
        }
    }
}
