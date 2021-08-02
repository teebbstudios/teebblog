<?php

namespace App\Entity;

use ApiPlatform\Core\Action\NotFoundAction;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\PostRepository;
use App\Utils\DateTimeTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
#[ApiResource(
    collectionOperations: ['get', 'post'],
    itemOperations: [
        'get' => [
            'normalization_context' => ['groups' => ['post:item:get']]
        ]
//        'get' => [
//            'controller' => NotFoundAction::class,
//            'read' => false,
//            'output' => false,
//        ],
    ],
    denormalizationContext: ['groups'=> ['post:write']],
    normalizationContext: ['groups'=> ['post:read']]
)]
class Post
{
    use DateTimeTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(['post:read', 'post:item:get'])]
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['post:read','post:write', 'post:item:get'])]
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    #[Groups(['post:read','post:write', 'post:item:get'])]
    private $summary;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    #[Groups(['post:read','post:write', 'post:item:get'])]
    private $body;

    /**
     * @ORM\Column(type="array", nullable=true)
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
    #[ApiSubresource]
    private $comments;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[Groups(['post:read', 'post:item:get'])]
    private $postImage;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="posts", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    #[Groups(['post:item:get'])]
//    #[ApiProperty(readableLink: true, writableLink: true)]
    private $author;

    #[Groups(['post:read', 'post:item:get'])]
    private $postImageUrl;

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

    public function getStatus(): ?array
    {
        return $this->status;
    }

    public function setStatus(?array $status, $context = []): self
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

    /**
     * @return mixed
     */
    public function getPostImageUrl()
    {
        return $this->postImageUrl;
    }

    /**
     * @param mixed $postImageUrl
     */
    public function setPostImageUrl($postImageUrl): void
    {
        $this->postImageUrl = $postImageUrl;
    }


}
