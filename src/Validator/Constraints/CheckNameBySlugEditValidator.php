<?php


namespace App\Validator\Constraints;

use App\Repository\TrickRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use function Symfony\Component\String\u;

class CheckNameBySlugEditValidator extends ConstraintValidator
{
    private TrickRepository $trickRepository;

    private SluggerInterface $slugger;

    private $request;

    /**
     * CheckNameBySlugEditValidator constructor.
     * @param TrickRepository $trickRepository
     * @param SluggerInterface $slugger
     * @param RequestStack $requestStack
     */
    public function __construct(TrickRepository $trickRepository, SluggerInterface $slugger, RequestStack $requestStack){
        $this->trickRepository = $trickRepository;
        $this->slugger = $slugger;
        $this->request = $requestStack->getCurrentRequest();
    }

    public function CheckNameBySlugEdit($value): bool
    {
        $violation = false;
        $slug = u($this->slugger->slug($value)->lower());
        if (!is_null($this->trickRepository->findOneBy(['slug' => $slug])) && $slug != $this->request->getSession()->get('slugTrickNameBeforeChanged')){
            $violation = true;
        }
        return $violation;
    }

    public function validate($value, Constraint $constraint){
        if (!is_null($value) && $this->CheckNameBySlugEdit($value)){
            $this->context->addViolation($constraint->message);
        }
    }
}