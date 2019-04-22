<?php declare(strict_types=1);

namespace App\Controller;

use App\Repository\AnnouncementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class IndexController
 * @package App\Controller
 */
class IndexController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="index")
     * @param AnnouncementRepository $announcementRepository
     * @return Response
     */
    public function index(AnnouncementRepository $announcementRepository): Response
    {
        return $this->render('index/index.html.twig', [
            'announcements' => $announcementRepository->findBy([], ['updatedAt' => 'DESC'])
        ]);
    }
}
