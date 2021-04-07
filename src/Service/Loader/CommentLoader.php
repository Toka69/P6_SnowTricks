<?php


namespace App\Service\Loader;


use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CommentLoader
{

    private CommentRepository $commentRepository;

    private SessionInterface $session;

    public function __construct(SessionInterface $session, CommentRepository $commentRepository)
    {
        $this->session = $session;
        $this->commentRepository = $commentRepository;
    }

    public function arrayJson($trick): array
    {
        $arrayJson = [];
        $currentComment = $this->session->get('currentComment', 0);
        $numberComments = 10;
        $currentComment = $currentComment + $numberComments;
        $comments = $this->commentRepository->getCommentsByTrickId($trick, $numberComments, $currentComment, "DESC");
        foreach ($comments as $comment){
            $arrayComment = [
                "content" => $comment->getContent(),
                "photo" => $comment->getUser()->getPhoto(),
                "firstName" => $comment->getUser()->getFirstName(),
                "lastName" => $comment->getUser()->getLastName(),
                "createdDate" => $comment->getCreatedDate(),
                "end" => 0
            ];
            array_push($arrayJson, $arrayComment);
        }

        $this->session->set('currentComment', $currentComment);

        if ($currentComment + $numberComments >= count($this->commentRepository->getCommentsByTrickId($trick))){
            array_push($arrayJson, ['end' => 1]);
            $this->session->remove('currentComment');
        }

        return $arrayJson;
    }
}
