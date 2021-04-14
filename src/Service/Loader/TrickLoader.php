<?php


namespace App\Service\Loader;


use App\Entity\Trick;
use App\Repository\TrickRepository;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrickLoader extends AbstractLoader
{
    private TrickRepository $trickRepository;

    public function __construct(TrickRepository $trickRepository)
    {
        $this->trickRepository = $trickRepository;
    }

    public function count(?array $options): int
    {
        return $this->trickRepository->count([]);
    }

    public function number(): int
    {
        return 8;
    }

    public function current(): int
    {
        return $this->getSession()->get('currentTrick', 0);
    }

    public function offset(): int
    {
        return $this->current()+$this->number();
    }

    public function getData(?array $options): array
    {
        $query = $this->trickRepository->findBy([], [], $this->number(), $this->offset());

        return $this->trickRepository->dataTransform($query);
    }

    public function getKey(): string
    {
        return 'currentTrick';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired("trick");
        $resolver->setAllowedTypes("trick", trick::class);
    }
}
