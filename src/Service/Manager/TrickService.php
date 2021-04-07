<?php


namespace App\Service\Manager;


use App\Entity\Photo;
use App\Repository\TrickRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;
use function Symfony\Component\String\u;

class TrickService
{
    private SluggerInterface $slugger;

    private EntityManagerInterface $em;

    private Security $security;

    private TrickRepository $trickRepository;

    private $request;

    public function __construct(SluggerInterface $slugger, EntityManagerInterface $em, Security $security, TrickRepository $trickRepository, RequestStack $requestStack)
    {
        $this->slugger = $slugger;
        $this->em = $em;
        $this->security = $security;
        $this->trickRepository = $trickRepository;
        $this->request = $requestStack->getCurrentRequest();
    }

    public function persistAddNewTrick($trick)
    {
        $trick->setSlug($this->slugger->slug($trick->getName())->lower());
        $trick->setUser($this->security->getUser());
        $trick->setCreatedDate(new DateTimeImmutable());
        $trick->removeEmptyPhotoField();
        $trick->removeEmptyVideoField();

        $this->em->persist($trick);
        $this->em->flush();
    }

    public function updateTrick($trick)
    {
        $trick->removeEmptyPhotoField();
        $trick->removeEmptyVideoField();
        $trick->setSlug(u($this->slugger->slug($trick->getName()))->lower());
        $trick->setModifiedDate(new DateTimeImmutable());
        $this->em->flush();
    }

    public function addCover($form, $trick)
    {
        if ($form->getData()->getFileCover()){
            $this->em->persist((new Photo)
                ->setTrick($trick)
                ->setCover(true)
                ->setFile($form->getData()->getFileCover()));
        }
    }

    public function addNewPhotos($form)
    {
        foreach ($form['photos']->getData() as $photo){
            if($photo->getFile() && ($photo->getId() === null))
            {
                $this->em->persist($photo);
            }
        }
    }

    public function trickNameBeforeChanged($trick)
    {
        $trickUnchanged = $this->trickRepository->findOneBy(['id' => $trick->getId()]);
        $this->request->getSession()->set('slugTrickNameBeforeChanged', $trickUnchanged->getSlug());
    }

    public function removeTrick($trick)
    {
        $this->em->remove($trick);
        $this->em->flush();
    }

    public function addNewComment($comment, $trick)
    {
        $comment->setCreatedDate(new DateTimeImmutable());
        $comment->setUser($this->security->getUser());
        $comment->setTrick($trick);
        $this->em->persist($comment);
        $this->em->flush();
    }
}
