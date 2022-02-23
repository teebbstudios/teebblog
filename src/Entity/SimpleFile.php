<?php

namespace App\Entity;

use App\Repository\SimpleFileRepository;
use Doctrine\ORM\Mapping as ORM;
use Teebb\UploaderBundle\Entity\File;
use Teebb\UploaderBundle\Entity\FileAnnotation;

/**
 * @ORM\Entity(repositoryClass=SimpleFileRepository::class)
 */
class SimpleFile extends File
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $path;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }
}
