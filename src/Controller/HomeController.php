<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Loader\TrickLoader;

class HomeController extends AbstractController
{
    protected $trickLoader;

    public function __construct(TrickLoader $trickLoader)
    {
        $this->trickLoader = $trickLoader;
    }

    /**
     * @Route("/", name="homepage")
     * @param TrickRepository $trickRepository
     * @return Response
     */
    public function index(TrickRepository $trickRepository): Response
    {
        $tricks = $trickRepository->findBy([],[], 8, 0);

        return $this->render('home/index.html.twig', [
            'tricks' => $tricks
        ]);
    }

    /**
     * @Route("/more/", name="home_loadMore")
     * @return JsonResponse
     */
    public function load(): Response
    {
        return $this->json($this->trickLoader->arrayJson());
    }
}

