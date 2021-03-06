<?php


namespace App\Service\Manager;


use App\Entity\Photo;
use App\Entity\Trick;
use App\Repository\TrickRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
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

    private FlashBagInterface $flashBag;

    public function __construct(SluggerInterface $slugger, EntityManagerInterface $em, Security $security, TrickRepository $trickRepository,
    FlashBagInterface $flashBag)
    {
        $this->slugger = $slugger;
        $this->em = $em;
        $this->security = $security;
        $this->trickRepository = $trickRepository;
        $this->flashBag = $flashBag;
    }

    public function persistAddNewTrick($trick): void
    {
        $trick->setSlug($this->slugger->slug($trick->getName())->lower());
        $trick->setUser($this->security->getUser());
        $trick->setCreatedDate(new DateTimeImmutable());
        $trick->removeEmptyPhotoField();
        $trick->removeEmptyVideoField();

        $this->em->persist($trick);
        $this->em->flush();
    }

    public function updateTrick($trick):void
    {
        $trick->removeEmptyPhotoField();
        $trick->removeEmptyVideoField();
        $trick->setSlug(u($this->slugger->slug($trick->getName()))->lower());
        $trick->setModifiedDate(new DateTimeImmutable());
        $this->em->flush();
    }

    public function addCover($form, $trick): void
    {
        if ($form->getData()->getFileCover()){
            $this->em->persist((new Photo)
                ->setTrick($trick)
                ->setCover(true)
                ->setFile($form->getData()->getFileCover()));
        }
    }

    public function addNewPhotos($form): void
    {
        foreach ($form['photos']->getData() as $photo){
            if($photo->getFile() && ($photo->getId() === null))
            {
                $this->em->persist($photo);
            }
        }
    }

    public function trickNameBeforeChanged(Trick $trick, SessionInterface $session): void
    {
        if ($trick->getId() !== null) {
            $trickUnchanged = $this->trickRepository->findOneBy(['id' => $trick->getId()]);
            $session->set('slugTrickNameBeforeChanged', $trickUnchanged->getSlug());
        }
        else {
            $session->remove('slugTrickNameBeforeChanged');
        }
    }

    public function removeTrick($trick): void
    {
        $this->em->remove($trick);
        $this->em->flush();
    }

    public function addNewComment($comment, $trick): void
    {
        $comment->setCreatedDate(new DateTimeImmutable());
        $comment->setUser($this->security->getUser());
        $comment->setTrick($trick);
        $this->em->persist($comment);
        $this->em->flush();
    }

    public function errorsPhotoUploadFile($form)
    {
        $errors = $form['photos']->getErrors(true, false);
        foreach ($errors as $error){
            $this->flashBag->add('error', $error->getChildren()->getChildren()->getMessage());
        }
    }
}
