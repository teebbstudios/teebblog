<?php

namespace App\Serializer\Normalizer;

use App\Entity\Post;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Exception\BadMethodCallException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\ExtraAttributesException;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Exception\RuntimeException;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class PostNormalizer implements DenormalizerInterface, CacheableSupportsMethodInterface
{
    private $normalizer;
    /**
     * @var RequestStack
     */
    private RequestStack $requestStack;
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    public function __construct(ObjectNormalizer $normalizer, RequestStack $requestStack, UserRepository $userRepository)
    {
        $this->normalizer = $normalizer;
        $this->requestStack = $requestStack;
        $this->userRepository = $userRepository;
    }

//    public function normalize($object, $format = null, array $context = []): array
//    {
//        $data = $this->normalizer->normalize($object, $format, $context);
//
//        $request = $this->requestStack->getCurrentRequest();
//        // Here: add, edit, or delete some data
//        $data['post_image_url'] = $request->getSchemeAndHttpHost() .'/uploads/images/'. $data['postImage'];
//        return $data;
//    }
//
//    public function supportsNormalization($data, $format = null): bool
//    {
//        return $data instanceof \App\Entity\Post;
//    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        $data = $this->normalizer->denormalize($data, $type, $format, $context);
        if ($data instanceof Post)
        {
            $admin = $this->userRepository->findOneBy(['username'=> 'admin']);
            $data->setAuthor($admin);
        }
        return $data;
    }

    public function supportsDenormalization($data, string $type, string $format = null)
    {
        return $this->normalizer->supportsDenormalization($data, $type, $format);
    }
}
