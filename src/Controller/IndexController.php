<?php declare(strict_types=1);

namespace App\Controller;

use App\Form\AnnouncementType;
use App\Repository\{
    AnnouncementRepository, CategoryRepository
};
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{
    Request, Response
};
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class IndexController
 * @package App\Controller
 */
class IndexController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="index")
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    public function index(CategoryRepository $categoryRepository, AnnouncementRepository $announcementRepository): Response
    {
        return $this->render('index/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'announcements' => $announcementRepository->findBy([], ['updatedAt' => 'DESC'])
        ]);
    }

    /**
     * @Route("/new", methods={"GET", "POST"}, name="add-announcement")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AnnouncementType::class);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $announcement = $form->getData();
                $announcement->setUser($this->getUser());
                $em->persist($announcement);
                $em->flush();

                return $this->redirectToRoute('index');
            }
        }

        return $this->render('index/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
