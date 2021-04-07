<?php


namespace App\Service\Loader;


use App\Repository\TrickRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class TrickLoader
{
    private TrickRepository $trickRepository;

    private SessionInterface $session;

    public function __construct(SessionInterface $session, TrickRepository $trickRepository)
    {
        $this->session = $session;
        $this->trickRepository = $trickRepository;
    }

    public function arrayJson(): array
    {
        $arrayJson = [];
        $currentTrick = $this->session->get('currentTrick', 0);
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

        $this->session->set('currentTrick', $currentTrick);

        if ($currentTrick + $numberTricks >= $this->trickRepository->count([])) {
            array_push($arrayJson, ['end' => 1]);
            $this->session->remove('currentTrick');
        }

        return $arrayJson;
    }
}
