<?php


namespace App\Controller;


use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @param EntityManager $em
     * @param Request $request
     * @param User $user
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route("/profile/{id}", name="profile", priority=10)
     */
    public function profile(EntityManagerInterface $em, Request $request, User $user){
        $form=$this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->flush();

            $this->addFlash('success', 'Your profile has been changed');
        }

        return $this->render("/profile/edit.html.twig", [
            'formView' => $form->createView()
        ]);
    }
}
