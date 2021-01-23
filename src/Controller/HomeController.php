<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Repository\TrickRepository;
use ArrayObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     * @param TrickRepository $trickRepository
     * @return Response
     */
    public function index(TrickRepository $trickRepository): Response
    {
        $tricks = $trickRepository->findAll();
        $arrayobj = new ArrayObject;

        foreach ($tricks as $trick){
            $cover = $trick->getCover();
            $arrayobj->append($cover);
        }


        return $this->render('home/index.html.twig', [
            'tricksPhotos' => $arrayobj
        ]);
    }
}
