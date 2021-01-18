<?php

namespace App\Controller;

use App\Repository\PhotoRepository;
use App\Repository\TrickRepository;
use App\Repository\VideoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @param TrickRepository $trickRepository
     * @param PhotoRepository $photoRepository
     * @param VideoRepository $videoRepository
     * @return Response
     * @Route("/", name="homepage")
     */
    public function index(TrickRepository $trickRepository, PhotoRepository $photoRepository, VideoRepository $videoRepository): Response
    {
        $tricks = $trickRepository->findAll();

        return $this->render('home/index.html.twig', [
            'tricks' => $tricks
        ]);
    }
}
