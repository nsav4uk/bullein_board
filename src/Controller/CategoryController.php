<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Category;
use App\Repository\{
    AnnouncementRepository, CategoryRepository
};
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CategoryController
 * @package App\Controller
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/category/{category}", methods={"GET"}, name="get-by-category")
     * @param AnnouncementRepository $announcementRepository
     * @param Category $category
     * @return Response
     */
    public function index(AnnouncementRepository $announcementRepository, Category $category): Response
    {
        return $this->render('index/index.html.twig', [
            'announcements' => $announcementRepository->findBy(['category' => $category], ['updatedAt' => 'DESC'])
        ]);
    }

    /**
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    public function categories(CategoryRepository $categoryRepository): Response
    {
        return $this->render('index/sidebar.html.twig', [
            'categories' => $categoryRepository->getCategoryNames()
        ]);
    }
}
