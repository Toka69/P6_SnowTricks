<?php


namespace App\Service\Loader;


use App\Entity\Trick;
use App\Repository\CommentRepository;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentLoader extends AbstractLoader
{

    private CommentRepository $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function count(?array $options): int
    {
        return count($this->commentRepository->getCommentsByTrickId($options["trick"]));
    }

    public function number(): int
    {
        return 10;
    }

    public function current(): int
    {
        return $this->getSession()->get('currentComment', 0);
    }

    public function offset(): int
    {
        return $this->current()+$this->number();
    }

    public function getData(array $options): array
    {
        $query = $this->commentRepository->getCommentsByTrickId($options["trick"], $this->number(), $this->offset(), "DESC");

        return $this->commentRepository->dataTransform($query);
    }

    public function getKey(): string
    {
        return 'currentComment';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired("trick");
        $resolver->setAllowedTypes("trick", trick::class);
    }
}
