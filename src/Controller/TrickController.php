<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Photo;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Form\TrickType;
use App\Repository\CommentRepository;
use App\Repository\TrickRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use function Symfony\Component\String\u;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class TrickController extends AbstractController
{
    /**
     * @Route("/trick/add", name="trick_add")
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @param SluggerInterface $slugger
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function add(Request $request, SluggerInterface $slugger, EntityManagerInterface $em): Response
    {
        $trick = new Trick;

        $form = $this->createForm(TrickType::class, $trick, [
            "validation_groups" => ["Default", "addTrick"]
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $trick->setSlug($slugger->slug($trick->getName())->lower());
            $trick->setUser($this->getUser());
            $trick->setCreatedDate(new DateTimeImmutable());

            $em->persist($trick);
            $em->flush();

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
     * @param TrickRepository $trickRepository
     * @param Trick $trick
     * @param Request $request
     * @param SluggerInterface $slugger
     * @param EntityManagerInterface $em
     * @param SessionInterface $session
     * @return Response
     */
    public function edit(TrickRepository $trickRepository, Trick $trick, Request $request, SluggerInterface $slugger,
                         EntityManagerInterface $em, SessionInterface $session): Response
    {
        $form = $this->createForm(TrickType::class, $trick, [
            "validation_groups" => ["Default", "editTrick"]
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            //When adding one or more new photos
            foreach ($form['photos']->getData() as $photo){
                if($photo->getFile() && is_null($photo->getId())) {$em->persist($photo);}
            }

            //When adding a cover
            if ($form->getData()->getFileCover()){
                $em->persist((new Photo)
                ->setTrick($trick)
                ->setCover(true)
                ->setFile($form->getData()->getFileCover()));
            }

            $trick->removeEmptyPhotoField($trick->getPhotos());
            $trick->setSlug(u($slugger->slug($trick->getName()))->lower());
            $trick->setModifiedDate(new DateTimeImmutable());
            $em->flush();

            $session->remove('slugTrickNameBeforeChanged');

            $this->addFlash('success', 'Your trick has been changed');

            return $this->redirectToRoute('trick_show', [
                'category_slug' => $trick->getCategory()->getSlug(),
                'slug' => $trick->getSlug()
            ]);
        }

        $trickUnchanged = $trickRepository->findOneBy(['id' => $trick->getId()]);
        $session->set('slugTrickNameBeforeChanged', $trickUnchanged->getSlug());

        return $this->render('trick/edit.html.twig', [
                'trick' => $trick,
                'formView' => $form->createView()
            ]
        );
    }

    /**
     * @Route("{id}/delete", name="trick_delete")
     * @param Trick $trick
     * @param EntityManagerInterface $em
     * @param SessionInterface $session
     * @return RedirectResponse
     */
    public function delete(Trick $trick, EntityManagerInterface $em, SessionInterface $session): RedirectResponse
    {
        $this->denyAccessUnlessGranted('DELETE', $trick, "You are not the owner of this trick and you are not authorized to delete it.");

        $em->remove($trick);
        $em->flush();

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
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function show(Trick $trick, Request $request, EntityManagerInterface $em, CommentRepository $commentRepository): Response
    {
        $comment = New Comment;

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $comment->setCreatedDate(new DateTimeImmutable());
            $comment->setUser($this->getUser());
            $comment->setTrick($trick);
            $em->persist($comment);
            $em->flush();

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
        $comments = $commentRepository->getCommentsByTrickId($trick, $numberComments, $currentComment, "DESC");
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
