<?php


namespace App\Namer;


use Symfony\Component\HttpFoundation\File\UploadedFile;
use Teebb\UploaderBundle\Namer\NamerInterface;

class BaseNamer implements NamerInterface
{

    public function rename(UploadedFile $file): string
    {
        return 'hello';
    }
}