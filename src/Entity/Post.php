<?php

namespace App\Entity;

use App\Repository\PostRepository;
use App\Utils\DateTimeTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Post
{
    use DateTimeTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $summary;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $body;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $status;

//    /**
//     * @ORM\Column(type="datetime", nullable=true)
//     */
//    private $createdAt;
//
//    /**
//     * @ORM\Column(type="datetime", nullable=true)
//     */
//    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="post", orphanRemoval=true)
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private $comments;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $postImage;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

//    public function getCreatedAt(): ?\DateTimeInterface
//    {
//        return $this->createdAt;
//    }
//
//    /**
//     * @ORM\PrePersist
//     */
//    public function setCreatedAt(): self
//    {
//        $this->createdAt = new \DateTime();
//
//        return $this;
//    }
//
//    public function getUpdatedAt(): ?\DateTimeInterface
//    {
//        return $this->updatedAt;
//    }
//
//    /**
//     * @ORM\PrePersist
//     * @ORM\PreUpdate
//     */
//    public function setUpdatedAt(): self
//    {
//        $this->updatedAt = new \DateTime();
//
//        return $this;
//    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }

    public function getPostImage(): ?string
    {
        return $this->postImage;
    }

    public function setPostImage(?string $postImage): self
    {
        $this->postImage = $postImage;

        return $this;
    }

    public function __toString(): string
    {
        return $this->title;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }
}
