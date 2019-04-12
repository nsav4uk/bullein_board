<?php

namespace App\DataFixtures;

use App\Entity\Announcement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class AnnouncementFixtures
 * @package App\DataFixtures
 */
class AnnouncementFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $announcement = new Announcement();

        $announcement
            ->setTitle('Test Buying')
            ->setBody('Test Body')
            ->setCategory($this->getReference(CategoryFixtures::PURCHASE_REFERENCE));

        $manager->persist($announcement);

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return array(
            CategoryFixtures::class,
        );
    }
}
