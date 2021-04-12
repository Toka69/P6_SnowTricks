<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Loader\TrickLoader;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     * @param TrickRepository $trickRepository
     * @param Session $session
     * @return Response
     */
    public function index(TrickRepository $trickRepository, Session $session): Response
    {
        $tricks = $trickRepository->findBy([],[], 8, 0);
        //init the session variable needed to the ajax trickLoader
        $session->remove('currentTrick');

        return $this->render('home/index.html.twig', [
            'tricks' => $tricks
        ]);
    }

    /**
     * @Route("/more/", name="home_loadMore")
     * @param TrickLoader $trickLoader
     * @return JsonResponse
     */
    public function load(TrickLoader $trickLoader): Response
    {
        return $this->json($trickLoader->arrayByOffset());
    }
}
