<?php


namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class CheckNameBySlugEdit
 * @package App\Validator\Constraints
 * @Annotation
 */
class CheckNameBySlugEdit extends Constraint
{
    public string $message = "Name exist. Please choose another.";
}