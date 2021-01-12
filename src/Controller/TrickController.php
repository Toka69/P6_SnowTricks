<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{
    /**
     * @Route("/group/trick/{slug}",name="trick_show")
     * @param $slug
     * @return Response
     */
    public function show($slug): Response
    {
        return $this->render('trick/show.html.twig', [
            'slug' => $slug
        ]);
    }
}
