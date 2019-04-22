<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Announcement;
use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class AnnouncementRepository
 * @package App\Repository
 */
class AnnouncementRepository extends ServiceEntityRepository
{
    /**
     * AnnouncementRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Announcement::class);
    }

    /**
     * @param Category|null $category
     * @return Query
     */
    public function getAnnouncements(?Category $category): Query
    {
        $qb = $this->createQueryBuilder('c')
            ->orderBy('c.updatedAt', 'DESC');

        if ($category) {
            $qb->where('c.category = :category')
                ->setParameter('category', $category);
        }

        return $qb->getQuery();
    }
}
