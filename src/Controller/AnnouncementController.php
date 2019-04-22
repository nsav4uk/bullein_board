<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\{Announcement, User};
use App\Form\AnnouncementType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AnnouncementController
 * @package App\Controller
 * @IsGranted("ROLE_USER")
 */
class AnnouncementController extends AbstractController
{
    /** @var EntityManagerInterface */
    private $em;

    /**
     * AnnouncementController constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/new", methods={"GET", "POST"}, name="add-announcement")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $form = $this->createForm(AnnouncementType::class);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $announcement = $form->getData();
                $announcement->setUser($this->getUser());
                $this->em->persist($announcement);
                $this->em->flush();

                return $this->redirectToRoute('index');
            }
        }

        return $this->render('announcement/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/view/{id}", name="view-announcement")
     * @param Announcement $announcement
     * @return Response
     */
    public function view(Announcement $announcement): Response
    {
        return $this->render('announcement/view.html.twig', [
            'announcement' => $announcement
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit-announcement")
     * @param Request $request
     * @param Announcement $announcement
     * @return Response
     */
    public function edit(Request $request, Announcement $announcement): Response
    {
        $form = $this->createForm(AnnouncementType::class, $announcement);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->flush();

                return $this->redirectToRoute('index');
            }
        }

        return $this->render('announcement/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete-announcement")
     * @param Announcement $announcement
     * @return Response
     */
    public function delete(Announcement $announcement): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $user->removeAnnouncement($announcement);
        $this->em->flush();

        return $this->redirectToRoute('get-by-category', [
            'category' => $announcement->getCategory()->getId()
        ]);
    }
}
