<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Form\TrickType;
use App\Repository\CommentRepository;
use App\Service\Loader\CommentLoader;
use App\Service\Manager\TrickService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class TrickController extends AbstractController
{
    private TrickService $trickService;

    public function __construct(TrickService $trickService){
        $this->trickService = $trickService;
    }

    /**
     * @Route("/trick/add", name="trick_add")
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @param SessionInterface $session
     * @return Response
     */
    public function add(Request $request, SessionInterface $session): Response
    {
        $trick = new Trick;

        $this->trickService->trickNameBeforeChanged($trick, $session);

        $form = $this->createForm(TrickType::class, $trick, [
            "validation_groups" => ["Default", "addTrick"]
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->trickService->persistAddNewTrick($trick);

            $this->addFlash('success', 'Your trick has been created !');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('trick/add.html.twig', [
            'formView' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="trick_edit")
     * @IsGranted("ROLE_USER", message="You must been logged!")
     * @param Trick $trick
     * @param Request $request
     * @param SessionInterface $session
     * @return Response
     */
    public function edit(Trick $trick, Request $request, SessionInterface $session): Response
    {
        $form = $this->createForm(TrickType::class, $trick, [
            "validation_groups" => ["Default", "editTrick"]
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->trickService->addNewPhotos($form);
            $this->trickService->addCover($form, $trick);
            $this->trickService->updateTrick($trick);

            $session->remove('slugTrickNameBeforeChanged');

            $this->addFlash('success', 'Your trick has been changed');

            return $this->redirectToRoute('trick_show', [
                'category_slug' => $trick->getCategory()->getSlug(),
                'slug' => $trick->getSlug()
            ]);
        }

        $this->trickService->trickNameBeforeChanged($trick, $session);

        return $this->render('trick/edit.html.twig', [
                'trick' => $trick,
                'formView' => $form->createView()
            ]
        );
    }

    /**
     * @Route("{id}/delete", name="trick_delete")
     * @param Trick $trick
     * @return RedirectResponse
     */
    public function delete(Trick $trick): RedirectResponse
    {
        $this->denyAccessUnlessGranted('DELETE', $trick, "You are not the owner of this trick and you are not authorized to delete it.");

        $this->trickService->removeTrick($trick);

        $this->addFlash('success', 'The trick has been deleted');

        return $this->redirectToRoute('homepage');
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
     * @param Request $request
     * @param CommentRepository $commentRepository
     * @return Response
     */
    public function show(Trick $trick, Request $request, CommentRepository $commentRepository): Response
    {
        $comment = New Comment;

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->trickService->addNewComment($comment, $trick);

            return $this->redirectToRoute('trick_show', [
                'category_slug' => $trick->getCategory()->getSlug(),
                'slug' => $trick->getSlug()
            ]);
        }

        return $this->render('trick/show.html.twig', [
            'commentsDescOrder' => $commentRepository->findBy(['trick' => $trick->getId()], ['createdDate' => 'DESC']),
            'trick' => $trick,
            'formView' => $form->createView()
        ]);
    }

    /**
     * @Route("/{category_slug}/{slug}/more/{id}", name="comment_loadMore")
     * @param Trick $trick
     * @param CommentLoader $commentLoader
     * @return JsonResponse
     */
    public function load(Trick $trick, CommentLoader $commentLoader): Response
    {
        return $this->json($commentLoader->arrayByOffset(["trick" => $trick]));
    }
}
