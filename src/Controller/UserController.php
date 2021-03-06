<?php


namespace App\Controller;

use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return Response
     * @Route("/profile", name="profile", priority=10)
     */
    public function profile(EntityManagerInterface $em, Request $request): Response
    {

        $form=$this->createForm(UserType::class, $this->getUser(), [
            "validation_groups" => ["profile"]
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em->flush();
            $this->addFlash('success', 'Your profile has been changed');
        }

        return $this->render("/profile/edit.html.twig", [
            'formView' => $form->createView()
        ]);
    }
}
