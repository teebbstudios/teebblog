<?php


namespace App\Controller;


use App\Entity\FileManaged;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ApiFileController extends AbstractController
{
    public function __invoke(Request $request, EntityManagerInterface $em, string $projectDir)
    {
        /**@var UploadedFile $uploadedFile * */
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        $newFileName = $uploadedFile->getClientOriginalName() . '_' .
            substr(hash('sha1', $uploadedFile->getClientOriginalName()), 0, 8) .
            '.' . $uploadedFile->getClientOriginalExtension();
        $file = new FileManaged();
        $file->setMimeType($uploadedFile->getMimeType());
        $file->setOriginName($uploadedFile->getClientOriginalName());
        $file->setFileName($newFileName);
        $file->setFileSize($uploadedFile->getSize());
        $file->setPath('/uploads/images/' . $newFileName);

        $em->persist($file);
        $em->flush();

        $uploadedFile->move($projectDir . '/public/uploads/images', $newFileName);

        return $file;
    }
}