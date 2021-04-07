<?php


namespace App\Service\Loader;


use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CommentLoader
{
    private $request;

    private CommentRepository $commentRepository;

    public function __construct(RequestStack $requestStack, CommentRepository $commentRepository)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->commentRepository = $commentRepository;
    }

    public function arrayJson($trick): array
    {
        $arrayJson = [];
        $currentComment = $this->request->getSession()->get('currentComment', 0);
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

        $this->request->getSession()->set('currentComment', $currentComment);

        if ($currentComment + $numberComments >= count($this->commentRepository->getCommentsByTrickId($trick))){
            array_push($arrayJson, ['end' => 1]);
            $this->request->getSession()->remove('currentComment');
        }

        return $arrayJson;
    }
}
