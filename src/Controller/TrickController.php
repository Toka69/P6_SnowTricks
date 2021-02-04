<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Trick;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
     * @param Trick $trick
     * @return Response
     * @paramConverter("trick")
     */
    public function show(Trick $trick): Response
    {
        return $this->render('trick/show.html.twig', [
            'trick' => $trick
        ]);
    }

    /**
     * @Route("/{category_slug}/{slug}/more/{id}", name="trick_loadMore")
     * @param Request $request
     * @param CommentRepository $commentRepository
     * @param Trick $trick
     * @return JsonResponse
     */
    public function load(Request $request, CommentRepository $commentRepository, Trick $trick): Response
    {
        $arrayJson = [];
        $currentComment = $request->getSession()->get('currentComment', 0);
        $numberComments = 10;
        $currentComment = $currentComment + $numberComments;
        $comments = $commentRepository->getCommentsByTrickId($trick, $numberComments, $currentComment);
        foreach ($comments as $comment){
            $arrayComment = [
                "content" => $comment->getContent(),
                "photo" => $comment->getUser()->getPhoto(),
                "firstName" => $comment->getUser()->getFirstName(),
                "lastName" => $comment->getUser()->getLastName(),
                "createdDate" => $comment->getCreatedDate(),
                "end" => 0
            ];
            array_push($arrayJson, $arrayComment);
        }

        $request->getSession()->set('currentComment', $currentComment);

        if ($currentComment + $numberComments >= count($commentRepository->getCommentsByTrickId($trick))){
            array_push($arrayJson, ['end' => 1]);
            $request->getSession()->remove('currentComment');
        }

        return $this->json($arrayJson);
    }

    /**
     * @Route("/{category_slug}/{slug}/edit", name="trick_edit")
     * @param Trick $trick
     * @return Response
     */
    public function edit(Trick $trick){

        return $this->render('trick/edit.html.twig', [
            'trick' => $trick
            ]
        );
    }
}
