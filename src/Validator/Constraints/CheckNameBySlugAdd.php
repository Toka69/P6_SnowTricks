<?php


namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class CheckNameBySlugAdd
 * @package App\Validator\Constraints
 * @Annotation
 */
class CheckNameBySlugAdd extends Constraint
{
    public string $message = "Name exist. Please choose another.";
}