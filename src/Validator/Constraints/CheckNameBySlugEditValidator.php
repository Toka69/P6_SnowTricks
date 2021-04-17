<?php


namespace App\Validator\Constraints;

use App\Repository\TrickRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use function Symfony\Component\String\u;

/**
 * Class CheckNameBySlugEditValidator
 * @package App\Validator\Constraints
 */
class CheckNameBySlugEditValidator extends ConstraintValidator
{
    private TrickRepository $trickRepository;

    private SluggerInterface $slugger;

    private SessionInterface $session;

    /**
     * CheckNameBySlugEditValidator constructor.
     * @param TrickRepository $trickRepository
     * @param SluggerInterface $slugger
     * @param SessionInterface $session
     */
    public function __construct(TrickRepository $trickRepository, SluggerInterface $slugger, SessionInterface $session){
        $this->trickRepository = $trickRepository;
        $this->slugger = $slugger;
        $this->session = $session;
    }

    public function CheckNameBySlugEdit($value): bool
    {
        $violation = false;
        $slug = u($this->slugger->slug($value)->lower());
        if ($this->trickRepository->findOneBy(['slug' => $slug]) !== null && $slug != $this->session->get('slugTrickNameBeforeChanged')){
            $violation = true;
        }
        return $violation;
    }

    public function validate($value, Constraint $constraint){
        if ($value !== null && $this->CheckNameBySlugEdit($value)){
            $this->context->addViolation($constraint->message);
        }
    }
}
