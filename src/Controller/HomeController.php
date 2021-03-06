<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @param Security $security
     * @param TrickRepository $trickRepository
     * @return Response
     */
    public function index(Request $request, Security $security, TrickRepository $trickRepository): Response
    {
        if(str_contains($request->cookies->get('sf_redirect'), 'login_check')){
          $user = $security->getUser();
          $user->setIsVerified(true);
          $this->addFlash('success', 'Your email has been verified and you are logged in');
        }
        $tricks = $trickRepository->findBy([],[], 8, 0);

        return $this->render('home/index.html.twig', [
            'tricks' => $tricks
        ]);
    }

    /**
     * @Route("/more/", name="home_loadMore")
     * @param Request $request
     * @param TrickRepository $trickRepository
     * @return JsonResponse
     */
    public function load(Request $request, TrickRepository $trickRepository): Response
    {
        $arrayJson = [];
        $currentTrick = $request->getSession()->get('currentTrick', 0 );
        $numberTricks = 8;
        $currentTrick = $currentTrick + $numberTricks;
        $tricks = $trickRepository->findBy([],[], $numberTricks, $currentTrick);

        foreach ($tricks as $trick){
            $arrayTrick = [
                'id' => $trick->getId(),
                'name' => $trick->getName(),
                'cover' => $trick->getCover()->getLocation(),
                'slug' => $trick->getSlug(),
                'categorySlug' => $trick->getCategory()->getSlug(),
                'end' => 0
            ];
            array_push($arrayJson, $arrayTrick);
        }

        $request->getSession()->set('currentTrick', $currentTrick);

        if ($currentTrick + $numberTricks >= $trickRepository->count([])){
            array_push($arrayJson, ['end' => 1]);
            $request->getSession()->remove('currentTrick');
        }

        return $this->json($arrayJson);
    }
}

