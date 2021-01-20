<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{
    /**
     * @Route("/{slug}", name="trick_category")
     * @param $slug
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    public function category($slug, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->findOneBy([
            'slug' => $slug
        ]);

        if(!$category){
            throw $this->createNotFoundException("Category unavailable");
        }

        return $this->render('trick/category.html.twig', [
            'slug' => $slug,
            'category' => $category
        ]);
    }

    /**
     * @Route("/{category_slug}/{slug}",name="trick_show")
     * @param $slug
     * @param TrickRepository $trickRepository
     * @return Response
     */
    public function show($slug, TrickRepository $trickRepository): Response
    {
        $trick = $trickRepository->findOneBy([
            'slug' => $slug
        ]);

        if(!$trick){
            throw $this->createNotFoundException("Trick unavailable");
        }

        return $this->render('trick/show.html.twig', [
            'trick' => $trick
        ]);
    }
}
