<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Trick;
use App\Repository\CategoryRepository;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class TrickController extends AbstractController
{
    /**
     * @Route("/{slug}", name="trick_category")
     * @param $slug
     * @param Category $category
     * @paramConverter("category")
     * @return Response
     */
    public function category($slug, Category $category): Response
    {
        return $this->render('trick/category.html.twig', [
            'slug' => $slug,
            'category' => $category
        ]);
    }

    /**
     * @Route("/{category_slug}/{slug}",name="trick_show")
     * @param $slug
     * @param Trick $trick
     * @paramConverter("trick")
     * @return Response
     */
    public function show(Trick $trick): Response
    {
        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
            'cover' => $trick->getCover()
        ]);
    }
}
