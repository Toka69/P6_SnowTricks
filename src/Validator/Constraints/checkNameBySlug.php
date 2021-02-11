<?php


namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class checkNameBySlug
 * @package App\Validator\Constraints
 * @Annotation
 */
class checkNameBySlug extends Constraint
{
    public string $message = "Name exist. Please choose another.";
}