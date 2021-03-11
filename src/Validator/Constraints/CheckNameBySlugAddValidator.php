<?php


namespace App\Validator\Constraints;

use App\Repository\TrickRepository;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use function Symfony\Component\String\u;

/**
 * Class CheckNameBySlugAddValidator
 * @package App\Validator\Constraints
 */
class CheckNameBySlugAddValidator extends ConstraintValidator
{
    private TrickRepository $trickRepository;

    private SluggerInterface $slugger;

    public function __construct(TrickRepository $trickRepository, SluggerInterface $slugger){
        $this->trickRepository = $trickRepository;
        $this->slugger = $slugger;
    }

    public function CheckNameBySlugAdd($value): bool
    {
        $violation = false;
        $slug = u($this->slugger->slug($value)->lower());
        if (!is_null($this->trickRepository->findOneBy(['slug' => $slug]))){
            $violation = true;
        }
        return $violation;
    }

    public function validate($value, Constraint $constraint){
        if (!is_null($value) && $this->CheckNameBySlugAdd($value)){
            $this->context->addViolation($constraint->message);
        }
    }
}