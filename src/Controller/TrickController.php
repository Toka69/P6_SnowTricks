<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Trick;
use App\Form\TrickType;
use App\Repository\CommentRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class TrickController extends AbstractController
{
    /**
     * @Route("/trick/add", name="trick_add")
     * @param Request $request
     * @param SluggerInterface $slugger
     * @param EntityManagerInterface $em
     * @param UserInterface $user
     * @return Response
     */
    public function add(Request $request, SluggerInterface $slugger, EntityManagerInterface $em, UserInterface $user){
        $trick = new Trick;

        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if($form->isSubmitted()){
            $trick->setUser($user);
            $trick->setSlug(strtolower($slugger->slug($trick->getName())));
            $trick->setCreatedDate(new DateTimeImmutable());

            $em->persist($trick);
            $em->flush();

            return $this->redirectToRoute('trick_show', [
                'category_slug' => $trick->getCategory()->getSlug(),
                'slug' => $trick->getSlug()
            ]);
        }

        $formView = $form->createView();

        return $this->render('trick/add.html.twig', [
            'formView' => $formView
        ]);
    }

    /**
     * @Route("/{id}/edit", name="trick_edit")
     * @param Trick $trick
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function edit(Trick $trick, Request $request, EntityManagerInterface $em){

        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if($form->isSubmitted()){
            $em->flush();

            return $this->redirectToRoute('trick_show', [
                'category_slug' => $trick->getCategory()->getSlug(),
                'slug' => $trick->getSlug()
            ]);
        }

        $formView = $form->createView();

        return $this->render('trick/edit.html.twig', [
                'trick' => $trick,
                'formView' => $formView
            ]
        );
    }

    /**
     * @Route("/{slug}", name="trick_category")
     * @param $slug
     * @param Category $category
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
}
