<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class WordValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var Word $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        $value = strtolower($value);

        foreach ($constraint->banWords as $word) {
            if (str_contains($value, $word)) {
                // TODO: implement the validation here
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ Word }}', $word)
                    ->addViolation();
            }
        }
    }
}


