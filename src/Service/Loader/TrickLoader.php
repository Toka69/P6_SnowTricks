<?php


namespace App\Service\Loader;


use App\Repository\TrickRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class TrickLoader
{
    private $request;

    private TrickRepository $trickRepository;

    public function __construct(RequestStack $requestStack, TrickRepository $trickRepository)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->trickRepository = $trickRepository;
    }

    public function arrayJson(): array
    {
        $arrayJson = [];
        $currentTrick = $this->request->getSession()->get('currentTrick', 0);
        $numberTricks = 8;
        $currentTrick = $currentTrick + $numberTricks;
        $tricks = $this->trickRepository->findBy([], [], $numberTricks, $currentTrick);

        foreach ($tricks as $trick) {
            $arrayTrick = [
                'id' => $trick->getId(),
                'name' => $trick->getName(),
                'cover' => $trick->getCover(),
                'slug' => $trick->getSlug(),
                'categorySlug' => $trick->getCategory()->getSlug(),
                'end' => 0
            ];
            array_push($arrayJson, $arrayTrick);
        }

        $this->request->getSession()->set('currentTrick', $currentTrick);

        if ($currentTrick + $numberTricks >= $this->trickRepository->count([])) {
            array_push($arrayJson, ['end' => 1]);
            $this->request->getSession()->remove('currentTrick');
        }

        return $arrayJson;
    }
}
