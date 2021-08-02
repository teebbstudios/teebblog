<?php


namespace App\Serializer\Normalizer;


use App\Entity\Post;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PostAwareNormalizer implements NormalizerAwareInterface, ContextAwareNormalizerInterface
{
    use NormalizerAwareTrait;

    /**
     * @var RequestStack
     */
    private RequestStack $requestStack;

    private const NORMALIZE_ALREADY_CALLED = 'normalize_already_called';

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        if (isset($context[self::NORMALIZE_ALREADY_CALLED])) {
            return false;
        }
        return $data instanceof Post;
    }

    /**
     * @param Post $object
     * @param string|null $format
     * @param array $context
     * @return array|\ArrayObject|bool|float|int|string|null
     * @throws ExceptionInterface
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        $context[self::NORMALIZE_ALREADY_CALLED] = true;

        $request = $this->requestStack->getCurrentRequest();

        $object->setPostImageUrl($request->getSchemeAndHttpHost() . '/uploads/images/' . $object->getPostImage());
        return $this->normalizer->normalize($object, $format, $context);
    }
}