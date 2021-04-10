<?php

namespace App\Entity;

use App\Repository\TrickRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as CustomAssert;

/**
 * @ORM\Entity(repositoryClass=TrickRepository::class)
 */
class Trick
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="The trick name is required !")
     * @Assert\Length(min=3, max=255, minMessage="Trick name must be at least three characters long !")
     * @CustomAssert\CheckNameBySlugAdd(groups={"addTrick"})
     * @CustomAssert\CheckNameBySlugEdit(groups={"editTrick"})
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=4000)
     * @Assert\NotBlank(message="The trick description is required !")
     * @Assert\Length(min=3, minMessage="Trick description must be at least three characters long !")
     */
    private string $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $slug;

    private $fileCover;

    public function getFileCover()
    {
        return $this->fileCover;
    }

    public function setFileCover(UploadedFile $file = null)
    {
        $this->fileCover = $file;

        return $this;
    }

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="tricks")
     */
    private ?Category $category;

    /**
     * @ORM\OneToMany(targetEntity=Photo::class, mappedBy="trick", cascade={"persist"}, orphanRemoval=true)
     */
    private Collection $photos;

    /**
     * @ORM\OneToMany(targetEntity=Video::class, mappedBy="trick", cascade={"persist"}, orphanRemoval=true)
     */
    private Collection $videos;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="trick", orphanRemoval=true)
     */
    private Collection $comments;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTimeInterface $createdDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $modifiedDate;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="tricks")
     */
    private ?User $user;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
        $this->videos = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photo $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
            $photo->setTrick($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): self
    {
        if ($this->photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getTrick() === $this) {
                $photo->setTrick(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(Video $video): self
    {
        if (!$this->videos->contains($video)) {
            $this->videos[] = $video;
            $video->setTrick($this);
        }

        return $this;
    }

    public function removeVideo(Video $video): self
    {
        if ($this->videos->removeElement($video)) {
            // set the owning side to null (unless already changed)
            if ($video->getTrick() === $this) {
                $video->setTrick(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setTrick($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getTrick() === $this) {
                $comment->setTrick(null);
            }
        }

        return $this;
    }

    public function getCreatedDate(): ?DateTimeInterface
    {
        return $this->createdDate;
    }

    public function setCreatedDate(DateTimeInterface $createdDate): self
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    public function getModifiedDate(): ?DateTimeInterface
    {
        return $this->modifiedDate;
    }

    public function setModifiedDate(?DateTimeInterface $modifiedDate): self
    {
        $this->modifiedDate = $modifiedDate;

        return $this;
    }

    public function getCover(): string
    {
        $photos = $this->getPhotos();
        $cover = new Photo;
        foreach ($photos as $photo){
            if ($photo->getCover() === true){
                $cover = $photo;
            }
            if($cover->getId() === null) {
                $cover = $photo;
            }
        }
        if($cover->getId() === null) {
            $cover->setlocation("cover.jpg");
        }
        $cover = $cover->getLocation();

        if(str_contains($cover, 'https://')){
            return $cover;
        }

        return '/uploads/'.$cover;
    }

    public function removeEmptyPhotoField()
    {
        foreach ($this->getPhotos() as $trickPhoto){
            if($trickPhoto->getLocation() === null){
                $this->getPhotos()->removeElement($trickPhoto);
            }
        }

        return $this->getPhotos();
    }

    public function removeEmptyVideoField()
    {
        foreach ($this->getVideos() as $trickVideo){
            if($trickVideo->getLocation() === null){
                $this->getVideos()->removeElement($trickVideo);
            }
        }

        return $this->getVideos();
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}

