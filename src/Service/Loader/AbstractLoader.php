<?php


namespace App\Service\Loader;


use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractLoader
{
    private SessionInterface $session;

    /**
     * @required
     * @param SessionInterface $session
     */
    public function setSession(SessionInterface $session): void
    {
        $this->session = $session;
    }

    abstract public function getData(array $options): array;

    abstract public function count(array $options): int;

    abstract public function current(): int;

    abstract public function number(): int;

    abstract public function getKey(): string;

    abstract public function offset(): int;

    public function configureOptions(OptionsResolver $resolver): void
    {

    }

    public function arrayCommentsByOffset(array $options): array
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $options = $resolver->resolve($options);

        $arrayCommentsByOffset = $this->getData($options);

        $this->session->set($this->getKey(), $this->offset());

        if ($this->offset() >= $this->count($options)){
            array_push($arrayCommentsByOffset, ['end' => 1]);
            $this->session->remove($this->getKey());
        }

        return $arrayCommentsByOffset;
    }
}