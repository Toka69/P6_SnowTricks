<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Photo;
use App\Entity\Trick;
use App\Entity\User;
use App\Entity\Video;
use DateInterval;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{

    protected SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger){
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager)
    {
        /**** Users ****/
        $user1 = new User;
        $user1->setFirstName("Eric")
            ->setLastName("Dupont")
            ->setPhoto("https://i.pravatar.cc/300")
            ->setEmail("edupont@gmial.com")
            ->setPassword("1234");
        $manager->persist($user1);

        $user2 = new User;
        $user2->setFirstName("Elodie")
            ->setLastName("Durand")
            ->setPhoto("https://i.pravatar.cc/300")
            ->setEmail("edurand@gmial.com")
            ->setPassword("1234");
        $manager->persist($user2);

        $user3 = new User;
        $user3->setFirstName("Jean")
            ->setLastName("Goldman")
            ->setPhoto("https://i.pravatar.cc/300")
            ->setEmail("jgoldman@gmial.com")
            ->setPassword("1234");
        $manager->persist($user3);

        /**** Categories ****/
        $category1 = new Category;
        $category1->setName("Grabs")
            ->setSlug(strtolower($this->slugger->slug($category1->getName())));
        $manager->persist($category1);

        $category2 = new Category;
        $category2->setName("Rotations")
            ->setSlug(strtolower($this->slugger->slug($category2->getName())));
        $manager->persist($category2);

        $category3 = new Category;
        $category3->setName("Flips")
            ->setSlug(strtolower($this->slugger->slug($category3->getName())));
        $manager->persist($category3);

        $category4 = new Category;
        $category4->setName("Off-center rotations")
            ->setSlug(strtolower($this->slugger->slug($category4->getName())));
        $manager->persist($category4);

        $category5 = new Category;
        $category5->setName("Slides")
            ->setSlug(strtolower($this->slugger->slug($category5->getName())));
        $manager->persist($category5);

        /**** Tricks ****/
        $trick1 = new Trick;
        $trick1->setName("mute")
            ->setDescription("Grasping the frontside edge of the board between both feet with the front hand")
            ->setSlug(strtolower($this->slugger->slug($trick1->getName())))
            ->setCategory($category1)
            ->setUser($user1)
            ->setCreatedDate(new DateTimeImmutable())
            ->setModifiedDate($trick1->getCreatedDate()->add(new DateInterval('P2DT4H8M25S')));
        $manager->persist($trick1);

        $trick2 = new Trick;
        $trick2->setName("sad")
            ->setDescription("Grasp the backside edge of the board, between both feet, with the front hand")
            ->setSlug(strtolower($this->slugger->slug($trick2->getName())))
            ->setCategory($category1)
            ->setUser($user1)
            ->setCreatedDate(new DateTimeImmutable())
            ->setModifiedDate($trick2->getCreatedDate()->add(new DateInterval('P2DT4H8M25S')));
        $manager->persist($trick2);

        $trick3 = new Trick;
        $trick3->setName("indy")
            ->setDescription("Grasping the frontside edge of the board, between both feet, with the back hand")
            ->setSlug(strtolower($this->slugger->slug($trick3->getName())))
            ->setCategory($category1)
            ->setUser($user3)
            ->setCreatedDate(new DateTimeImmutable());
        $manager->persist($trick3);

        $trick4 = new Trick;
        $trick4->setName("stalefish")
            ->setDescription("Grasping the backside edge of the board between both feet with the back hand")
            ->setSlug(strtolower($this->slugger->slug($trick4->getName())))
            ->setCategory($category1)
            ->setUser($user2)
            ->setCreatedDate(new DateTimeImmutable());
        $manager->persist($trick4);

        $trick5 = new Trick;
        $trick5->setName("180")
            ->setDescription("A half-turn")
            ->setSlug(strtolower($this->slugger->slug($trick5->getName())))
            ->setCategory($category2)
            ->setUser($user2)
            ->setCreatedDate(new DateTimeImmutable())
            ->setModifiedDate($trick5->getCreatedDate()->add(new DateInterval('P2DT4H8M25S')));
        $manager->persist($trick5);

        $trick6 = new Trick;
        $trick6->setName("360")
            ->setDescription("A full turn")
            ->setSlug(strtolower($this->slugger->slug($trick6->getName())))
            ->setCategory($category2)
            ->setUser($user3)
            ->setCreatedDate(new DateTimeImmutable());
        $manager->persist($trick6);

        $trick7 = new Trick;
        $trick7->setName("540")
            ->setDescription("A turn and a half")
            ->setSlug(strtolower($this->slugger->slug($trick7->getName())))
            ->setCategory($category2)
            ->setUser($user1)
            ->setCreatedDate(new DateTimeImmutable())
            ->setModifiedDate($trick7->getCreatedDate()->add(new DateInterval('P2DT4H8M25S')));
        $manager->persist($trick7);

        $trick8 = new Trick;
        $trick8->setName("720")
            ->setDescription("Two full turns")
            ->setSlug(strtolower($this->slugger->slug($trick8->getName())))
            ->setCategory($category2)
            ->setUser($user2)
            ->setCreatedDate(new DateTimeImmutable())
            ->setModifiedDate($trick8->getCreatedDate()->add(new DateInterval('P2DT4H8M25S')));
        $manager->persist($trick8);

        $trick9 = new Trick;
        $trick9->setName("front flip")
            ->setDescription("Forward rotation")
            ->setSlug(strtolower($this->slugger->slug($trick9->getName())))
            ->setCategory($category3)
            ->setUser($user1)
            ->setCreatedDate(new DateTimeImmutable());
        $manager->persist($trick9);

        $trick10 = new Trick;
        $trick10->setName("nose slide")
            ->setDescription("The front of the board on the bar")
            ->setSlug(strtolower($this->slugger->slug($trick10->getName())))
            ->setCategory($category5)
            ->setUser($user3)
            ->setCreatedDate(new DateTimeImmutable());
        $manager->persist($trick10);

        /**** Videos ****/
        $video1 = new Video;
        $video1->setLocation("https://www.youtube.com/embed/jm19nEvmZgM")
            ->setTrick($trick1);
        $manager->persist($video1);

        $video2 = new Video;
        $video2->setLocation("https://www.youtube.com/embed/6yA3XqjTh_w")
            ->setTrick($trick3);
        $manager->persist($video2);

        $video3 = new Video;
        $video3->setLocation("https://www.youtube.com/embed/Sh3qT1INT_I")
            ->setTrick($trick6);
        $manager->persist($video3);

        $video4 = new Video;
        $video4->setLocation("https://www.youtube.com/embed/yyN1gQqsMwM")
            ->setTrick($trick1);
        $manager->persist($video4);

        /**** Photos ****/
        $photo1 = new Photo;
        $photo1->setLocation("https://miro.medium.com/max/700/1*bT_5SfWre2naQMUk8Usa5A.jpeg")
            ->setTrick($trick1);
        $manager->persist($photo1);

        $photo2 = new Photo;
        $photo2->setLocation("https://cdn.shopify.com/s/files/1/0230/2239/files/5_b564c467-2f8e-48f4-91d6-445d58c79a85_large.jpg?v=1517787201")
            ->setTrick($trick4)
            ->setCover(true);
        $manager->persist($photo2);

        $photo3 = new Photo;
        $photo3->setLocation("https://cdn.shopify.com/s/files/1/0230/2239/files/2_0a061ea2-2889-4f8e-8489-72b110c4e20c_large.jpg?v=1517787115")
            ->setTrick($trick3);
        $manager->persist($photo3);

        $photo4 = new Photo;
        $photo4->setLocation("https://cdn.shopify.com/s/files/1/0230/2239/files/4_07454a10-ad61-4d79-bcf2-991ef1b616bc_large.jpg?v=1517787145")
            ->setTrick($trick1)
            ->setCover(true);
        $manager->persist($photo4);

        $photo5 = new Photo;
        $photo5->setLocation("https://i.ytimg.com/vi/qvCHlylYmxc/maxresdefault.jpg")
            ->setTrick($trick5);
        $manager->persist($photo5);

        $photo6 = new Photo;
        $photo6->setLocation("https://i.ytimg.com/vi/_rS2i4-yb6E/maxresdefault.jpg")
            ->setTrick($trick6)
            ->setCover(true);
        $manager->persist($photo6);

        $photo7 = new Photo;
        $photo7->setLocation("https://1.bp.blogspot.com/-4b4wuXZqjGk/TkfY_Tb9LAI/AAAAAAAAAxs/g_KKxyxAuSs/s1600/backside360.jpg")
            ->setTrick($trick6);
        $manager->persist($photo7);

        $photo8 = new Photo;
        $photo8->setLocation("https://coresites-cdn-adm.imgix.net/whitelines_new/wp-content/uploads/2011/11/Whitelines-94-backside-rodeo-540-melon-620x215.jpg")
            ->setTrick($trick7);
        $manager->persist($photo8);

        $photo9 = new Photo;
        $photo9->setLocation("https://i.ytimg.com/vi/_hJX9HrdkeA/maxresdefault.jpg")
            ->setTrick($trick7)
            ->setCover(true);
        $manager->persist($photo9);

        $photo10 = new Photo;
        $photo10->setLocation("https://i.ytimg.com/vi/4JfBfQpG77o/maxresdefault.jpg")
            ->setTrick($trick8);
        $manager->persist($photo10);

        $photo11 = new Photo;
        $photo11->setLocation("https://coresites-cdn-adm.imgix.net/whitelines_new/wp-content/uploads/2012/12/frontflipknuckle.jpg?fit=crop")
            ->setTrick($trick9)
            ->setCover(true);
        $manager->persist($photo11);

        $photo12 = new Photo;
        $photo12->setLocation("https://coresites-cdn-adm.imgix.net/whitelines_new/wp-content/uploads/2012/12/IMG_7636-620x413.jpg")
            ->setTrick($trick9);
        $manager->persist($photo12);

        $photo13 = new Photo;
        $photo13->setLocation("https://cdn.shopify.com/s/files/1/0230/2239/files/1_d97094f7-cb01-4c2a-b077-dbf9cccb2b24_large.jpg?v=1507502797")
            ->setTrick($trick10);
        $manager->persist($photo13);

        $photo14 = new Photo;
        $photo14->setLocation("https://cdn.shopify.com/s/files/1/0230/2239/articles/Snowboard_Trick_Terminology_1024x1024.jpg?v=1556396922")
            ->setTrick($trick10)
            ->setCover(true);
        $manager->persist($photo14);

        $photo15 = new Photo;
        $photo15->setLocation("https://coresites-cdn-adm.imgix.net/whitelines_new/wp-content/uploads/2014/01/Trick-Mute-Grab-620x444.jpg")
            ->setTrick($trick1)
            ->setCover(true);
        $manager->persist($photo15);

        $photo16 = new Photo;
        $photo16->setLocation("https://c8.alamy.com/compfr/dyxkwd/snowboarder-avec-casque-et-lunettes-effectue-une-ponction-mute-saut-aerien-dyxkwd.jpg")
            ->setTrick($trick1)
            ->setCover(true);
        $manager->persist($photo16);

        $photo17 = new Photo;
        $photo17->setLocation("https://communaute.ucpa.com/legacyfs/online/uploads/2016/01/grab-simple-snowboard-freestyle-e1453203128225.jpg")
            ->setTrick($trick1)
            ->setCover(true);
        $manager->persist($photo17);

        $photo18 = new Photo;
        $photo18->setLocation("https://cdn.shopify.com/s/files/1/0230/2239/articles/Basic-Grabs-On-A-Snowboard_720x.jpg?v=1517794316")
            ->setTrick($trick1)
            ->setCover(true);
        $manager->persist($photo18);

        $photo19 = new Photo;
        $photo19->setLocation("https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT14aDzI9R6TY7ckfhlVIl2SmoCQ22q-PyduA&usqp=CAU")
            ->setTrick($trick1)
            ->setCover(true);
        $manager->persist($photo19);

        /**** Comments ****/
        $comment1 = new Comment;
        $comment1->setTrick($trick1)
            ->setUser($user2)
            ->setCreatedDate(new DateTimeImmutable())
            ->setContent("Hi, it's a wonderful trick!")
            ->setModifiedDate($comment1->getCreatedDate()->add(new DateInterval('P4DT6H8M37S')));
        $manager->persist($comment1);

        $comment2 = new Comment;
        $comment2->setTrick($trick1)
            ->setUser($user1)
            ->setCreatedDate(new DateTimeImmutable())
            ->setContent("Hello, great!!!");
        $manager->persist($comment2);

        $comment3 = new Comment;
        $comment3->setTrick($trick2)
            ->setUser($user1)
            ->setCreatedDate(new DateTimeImmutable())
            ->setContent("Yeah man.")
            ->setModifiedDate($comment3->getCreatedDate()->add(new DateInterval('P4DT6H8M37S')));
        $manager->persist($comment3);

        $comment4 = new Comment;
        $comment4->setTrick($trick3)
            ->setUser($user3)
            ->setCreatedDate(new DateTimeImmutable())
            ->setContent("Wahou");
        $manager->persist($comment4);

        $comment5 = new Comment;
        $comment5->setTrick($trick4)
            ->setUser($user2)
            ->setCreatedDate(new DateTimeImmutable())
            ->setContent("Too fun")
            ->setModifiedDate($comment5->getCreatedDate()->add(new DateInterval('P4DT6H8M37S')));
        $manager->persist($comment5);

        $comment6 = new Comment;
        $comment6->setTrick($trick5)
            ->setUser($user3)
            ->setCreatedDate(new DateTimeImmutable())
            ->setContent("Amazing")
            ->setModifiedDate($comment6->getCreatedDate()->add(new DateInterval('P4DT6H8M37S')));
        $manager->persist($comment6);

        $comment7 = new Comment;
        $comment7->setTrick($trick6)
            ->setUser($user3)
            ->setCreatedDate(new DateTimeImmutable())
            ->setContent("So beautiful");
        $manager->persist($comment7);

        $comment8 = new Comment;
        $comment8->setTrick($trick7)
            ->setUser($user1)
            ->setCreatedDate(new DateTimeImmutable())
            ->setContent("Why not")
            ->setModifiedDate($comment8->getCreatedDate()->add(new DateInterval('P4DT6H8M37S')));
        $manager->persist($comment8);

        $comment9 = new Comment;
        $comment9->setTrick($trick8)
            ->setUser($user1)
            ->setCreatedDate(new DateTimeImmutable())
            ->setContent("It's a good trick")
            ->setModifiedDate($comment9->getCreatedDate()->add(new DateInterval('P4DT6H8M37S')));
        $manager->persist($comment9);

        $comment10 = new Comment;
        $comment10->setTrick($trick9)
            ->setUser($user2)
            ->setCreatedDate(new DateTimeImmutable())
            ->setContent("Sorry, i don't understand")
            ->setModifiedDate($comment10->getCreatedDate()->add(new DateInterval('P4DT6H8M37S')));
        $manager->persist($comment10);

        $comment11 = new Comment;
        $comment11->setTrick($trick10)
            ->setUser($user1)
            ->setCreatedDate(new DateTimeImmutable())
            ->setContent("I like it");
        $manager->persist($comment11);

        $comment12 = new Comment;
        $comment12->setTrick($trick3)
            ->setUser($user1)
            ->setCreatedDate(new DateTimeImmutable())
            ->setContent("It's my favorite")
            ->setModifiedDate($comment12->getCreatedDate()->add(new DateInterval('P4DT6H8M37S')));
        $manager->persist($comment12);

        $comment13 = new Comment;
        $comment13->setTrick($trick5)
            ->setUser($user2)
            ->setCreatedDate(new DateTimeImmutable())
            ->setContent("Good luck")
            ->setModifiedDate($comment13->getCreatedDate()->add(new DateInterval('P4DT6H8M37S')));
        $manager->persist($comment13);

        $manager->flush();
    }
}
