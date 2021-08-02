<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\CommentRepository;
use App\Utils\DateTimeTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 * @ORM\HasLifecycleCallbacks
 * @ORM\EntityListeners({"App\Listener\CommentListener"})
 * @Assert\EnableAutoMapping
 */
#[ApiResource]
#[ApiFilter(SearchFilter::class, properties: [
    'message' => 'partial',
    'post.id' => 'exact'
])]
class Comment
{
    use DateTimeTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\DisableAutoMapping()
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $message;

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
     * @ORM\ManyToOne(targetEntity=Post::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false, nullable=true)
     */
    private $post;

    /**
     * @ORM\ManyToOne(targetEntity=Comment::class, inversedBy="children")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="parent")
     */
    private $children;

    /**
     * @ORM\Column(type="integer")
     */
    private $level = 1;

    /**
     * @ORM\ManyToMany(targetEntity=FileManaged::class, cascade={"persist"})
     * @ORM\JoinTable(name="comments_files",
     *      joinColumns={@ORM\JoinColumn(name="comment_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="file_id", referencedColumnName="id", unique=true)}
     *      )
     *
     */
    private $files;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->files = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

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

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(self $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(self $child): self
    {
        if ($this->children->removeElement($child)) {
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    /**
     * @return Collection|FileManaged[]
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function addFile(FileManaged $file): self
    {
        if (!$this->files->contains($file)) {
            $this->files[] = $file;
        }

        return $this;
    }

    public function removeFile(FileManaged $file): self
    {
        $this->files->removeElement($file);

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status, $context = []): self
    {
        $this->status = $status;

        return $this;
    }
}
