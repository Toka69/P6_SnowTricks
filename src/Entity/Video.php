<?php

namespace App\Entity;

use App\Repository\VideoRepository;
use Doctrine\ORM\Mapping as ORM;
use DOMDocument;

/**
 * @ORM\Entity(repositoryClass=VideoRepository::class)
 */
class Video
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $location;

    /**
     * @ORM\ManyToOne(targetEntity=Trick::class, inversedBy="videos")
     */
    private ?Trick $trick;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocation(): ?string
    {
        return '<iframe src="'.$this->location.'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
    }

    public function setLocation(string $location): self
    {
        $dom = new DOMDocument();
        $dom->loadHTML($location);
        $searchNode = $dom->getElementsByTagName("iframe");
        foreach($searchNode as $searchNode){
            $location = $searchNode->getAttribute("src");

            //for dailymotion
            if(str_contains($location, "autoplay")){
                $location = str_replace("?autoplay=1", "", $location);
            }

        }
        $this->location = $location;

        return $this;
    }

    public function getTrick(): ?Trick
    {
        return $this->trick;
    }

    public function setTrick(?Trick $trick): self
    {
        $this->trick = $trick;

        return $this;
    }

    public function __toString() {
        return $this->getLocation();
    }
}
