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

    public function getId(): ?int
    {
        return $this->id;
    }
}
