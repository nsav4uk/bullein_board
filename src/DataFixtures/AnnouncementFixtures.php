<?php declare(strict_types=1);

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
        foreach (CategoryFixtures::REFERENCES as $reference) {
            for ($i = 0; $i < 5; $i++) {
                $announcement = new Announcement();

                $announcement
                    ->setTitle('Test' . $i)
                    ->setBody('Test Body' . $i)
                    ->setCategory($this->getReference($reference))
                    ->setUser($this->getReference('user'))
                ;

                $manager->persist($announcement);
            }
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return array(
            CategoryFixtures::class,
            UserFixtures::class
        );
    }
}
