<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class FileManagedValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
//        dd($value, $constraint);
        /* @var $constraint \App\Validator\FileManaged */

        if (null === $value || '' === $value) {
            return;
        }
//dd($value);
//        foreach ($value as $file)
//        {
            if ($value instanceof \App\Entity\FileManaged){
                $mimeType = $value->getMimeType();

                foreach ($constraint->mimeTypes as $allowMimeType){
                    if ($allowMimeType === $mimeType){
                        return;
                    }

                    if ($discrete = strstr($allowMimeType, '/*', true)) {
                        if (strstr($mimeType, '/', true) === $discrete) {
                            return;
                        }
                    }

                }
//                if ($prestr = strstr($mimeType, '/', true)){
//                    if ($prestr === 'image'){
//                        return;
//                    }
//                }
            }
//        }

        // TODO: implement the validation here
        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ name }}', $value->getOriginName())
            ->addViolation();
    }
}
