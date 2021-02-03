<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use ArrayObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @param TrickRepository $trickRepository
     * @return Response
     */
    public function index(TrickRepository $trickRepository): Response
    {
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
