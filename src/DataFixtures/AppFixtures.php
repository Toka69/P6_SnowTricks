<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Trick;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{

    protected $slugger;

    public function __construct(SluggerInterface $slugger){
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager)
    {
        $user1 = new User;
        $user1 -> setFirstName("Eric")
            -> setLastName("Dupont")
            -> setPhoto("https://i.pravatar.cc/300")
            -> setEmail("edupont@gmial.com")
            -> setPassword("1234");
        $manager->persist($user1);

        $user2 = new User;
        $user2 -> setFirstName("Elodie")
            -> setLastName("Durand")
            -> setPhoto("https://i.pravatar.cc/300")
            -> setEmail("edurand@gmial.com")
            -> setPassword("1234");
        $manager->persist($user2);

        $user3 = new User;
        $user3 -> setFirstName("Jean")
            -> setLastName("Goldman")
            -> setPhoto("https://i.pravatar.cc/300")
            -> setEmail("jgoldman@gmial.com")
            -> setPassword("1234");
        $manager->persist($user3);

        $category1 = new Category;
        $category1 -> setName("Grabs")
            -> setSlug(strtolower($this->slugger->slug($category1->getName())));
        $manager->persist($category1);

        $category2 = new Category;
        $category2 -> setName("Rotations")
            -> setSlug(strtolower($this->slugger->slug($category2->getName())));
        $manager->persist($category2);

        $category3 = new Category;
        $category3 -> setName("Flips")
            -> setSlug(strtolower($this->slugger->slug($category3->getName())));
        $manager->persist($category3);

        $category4 = new Category;
        $category4 -> setName("Off-center rotations")
            -> setSlug(strtolower($this->slugger->slug($category4->getName())));
        $manager->persist($category4);

        $category5 = new Category;
        $category5 -> setName("Slides")
            -> setSlug(strtolower($this->slugger->slug($category5->getName())));
        $manager->persist($category5);

        $trick1 = new Trick;
        $trick1 -> setName("mute")
            -> setDescription("Grasping the frontside edge of the board between both feet with the front hand")
            -> setSlug(strtolower($this->slugger->slug($trick1->getName())))
            ->setCategory($category1)
            ->setUser($user1)
            ->setCreatedDate(new DateTime('NOW'));
        $manager->persist($trick1);

        $trick2 = new Trick;
        $trick2 -> setName("sad")
            -> setDescription("Grasp the backside edge of the board, between both feet, with the front hand")
            -> setSlug(strtolower($this->slugger->slug($trick2->getName())))
            ->setCategory($category1)
            ->setUser($user1)
            ->setCreatedDate(new DateTime('NOW'));
        $manager->persist($trick2);

        $trick3 = new Trick;
        $trick3 -> setName("indy")
            -> setDescription("Grasping the frontside edge of the board, between both feet, with the back hand")
            -> setSlug(strtolower($this->slugger->slug($trick3->getName())))
            ->setCategory($category1)
            ->setUser($user3)
            ->setCreatedDate(new DateTime('NOW'));
        $manager->persist($trick3);

        $trick4 = new Trick;
        $trick4 -> setName("stalefish")
            -> setDescription("Grasping the backside edge of the board between both feet with the back hand")
            -> setSlug(strtolower($this->slugger->slug($trick4->getName())))
            ->setCategory($category1)
            ->setUser($user2)
            ->setCreatedDate(new DateTime('NOW'));
        $manager->persist($trick4);

        $trick5 = new Trick;
        $trick5 -> setName("180")
            -> setDescription("A half-turn")
            -> setSlug(strtolower($this->slugger->slug($trick5->getName())))
            ->setCategory($category2)
            ->setUser($user2)
            ->setCreatedDate(new DateTime('NOW'));
        $manager->persist($trick5);

        $trick6 = new Trick;
        $trick6 -> setName("360")
            -> setDescription("A full turn")
            -> setSlug(strtolower($this->slugger->slug($trick6->getName())))
            ->setCategory($category2)
            ->setUser($user3)
            ->setCreatedDate(new DateTime('NOW'));
        $manager->persist($trick6);

        $trick7 = new Trick;
        $trick7 -> setName("540")
            -> setDescription("A turn and a half")
            -> setSlug(strtolower($this->slugger->slug($trick7->getName())))
            ->setCategory($category2)
            ->setUser($user1)
            ->setCreatedDate(new DateTime('NOW'));
        $manager->persist($trick7);

        $trick8 = new Trick;
        $trick8 -> setName("720")
            -> setDescription("Two full turns")
            -> setSlug(strtolower($this->slugger->slug($trick8->getName())))
            ->setCategory($category2)
            ->setUser($user2)
            ->setCreatedDate(new DateTime('NOW'));
        $manager->persist($trick8);

        $trick9 = new Trick;
        $trick9 -> setName("front flip")
            -> setDescription("Forward rotation")
            -> setSlug(strtolower($this->slugger->slug($trick9->getName())))
            ->setCategory($category3)
            ->setUser($user1)
            ->setCreatedDate(new DateTime('NOW'));
        $manager->persist($trick9);

        $trick10 = new Trick;
        $trick10 -> setName("nose slide")
            -> setDescription("The front of the board on the bar")
            -> setSlug(strtolower($this->slugger->slug($trick10->getName())))
            ->setCategory($category5)
            ->setUser($user3)
            ->setCreatedDate(new DateTime('NOW'));
        $manager->persist($trick10);

        $manager->flush();
    }
}
