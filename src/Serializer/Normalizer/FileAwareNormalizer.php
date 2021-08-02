<?php


namespace App\Serializer\Normalizer;


use App\Entity\FileManaged;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;

class FileAwareNormalizer implements NormalizerAwareInterface, ContextAwareNormalizerInterface
{
    use NormalizerAwareTrait;

    /**
     * @var RequestStack
     */
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    private const FILE_NORMALIZER_ALREADY_CALLED = 'file_normalizer_already_called';

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        if (isset($context[self::FILE_NORMALIZER_ALREADY_CALLED]))
        {
            return false;
        }
        return $data instanceof FileManaged;
    }

    /**
     * @param FileManaged $object
     * @param string|null $format
     * @param array $context
     * @return array|\ArrayObject|bool|float|int|string|null
     * @throws ExceptionInterface
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        $context[self::FILE_NORMALIZER_ALREADY_CALLED] = true;

        $object->setFileUrl($this->requestStack->getCurrentRequest()->getSchemeAndHttpHost() . $object->getPath());
        return $this->normalizer->normalize($object, $format, $context);
    }
}