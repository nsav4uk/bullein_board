<?php declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class CategoryFixtures
 * @package App\DataFixtures
 */
class CategoryFixtures extends Fixture
{
    /** @var array */
    private const CATEGORIES = ['Purchase', 'Selling', 'Rent'];

    public const REFERENCES = [
        'purchase-category',
        'selling-category',
        'rent-category'
    ];

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        foreach (self::CATEGORIES as $name) {
            $category = new Category();
            $category->setName($name);
            $manager->persist($category);

            $this->addReference(strtolower($name). '-category', $category);
        }

        $manager->flush();
    }
}
