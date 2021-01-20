<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Repository\CategoryRepository;
use App\Repository\PhotoRepository;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
     * @param PhotoRepository $photoRepository
     * @return Response
     */
    public function show($slug, TrickRepository $trickRepository, PhotoRepository $photoRepository): Response
    {
        $trick = $trickRepository->findOneBy([
            'slug' => $slug
        ]);

        if(!$trick){
            throw $this->createNotFoundException("Trick unavailable");
        }

        $cover = $photoRepository->findBy([
            'trick' => $trick->getId(),
            'cover' => true
        ]);
        if(!$cover){
            $cover = $photoRepository->findOneBy([
                'trick' => $trick->getId()
            ]);
        }
        if(!$cover){
            $cover = new Photo;
            $cover->setlocation("../img/cover.jpg");
        }

        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
            'cover' => $cover
        ]);
    }
}
