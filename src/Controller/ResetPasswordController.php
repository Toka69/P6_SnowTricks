<?php


namespace App\Controller;


use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ResetPasswordController extends AbstractController
{
    /**
     * @Route("/reset", name="reset_password")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function resetPassword(Request $request){

        if ($request->isMethod('POST'))
        {
            echo 'toto';
        }

        return $this->render('security/resetPassword.html.twig');
    }
}
