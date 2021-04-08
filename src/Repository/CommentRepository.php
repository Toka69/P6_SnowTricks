<?php

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\Trick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
     * @param Trick $trick
     * @param int|null $limit
     * @param int|null $offset
     * @param string|null $order
     * @return mixed
     */
    public function getCommentsByTrickId(Trick $trick, ?int $limit = null, ?int $offset = null, ?string $order = null): mixed
    {
        $query = $this->createQueryBuilder('c')
            ->andWhere('c.trick = :trickId')
            ->setParameter('trickId', $trick->getId())
            ;
        if ($limit){$query->setMaxResults($limit);}
        if ($offset){$query->setFirstResult($offset);}
        if ($order){$query->orderBy('c.createdDate',$order);}

        $query = $query->getQuery();
        return $query->getResult();
    }

    public function dataTransform($comments): array
    {
        $arrayComments = [];
        foreach ($comments as $comment){
            $arrayComment = [
                "content" => $comment->getContent(),
                "photo" => $comment->getUser()->getPhoto(),
                "firstName" => $comment->getUser()->getFirstName(),
                "lastName" => $comment->getUser()->getLastName(),
                "createdDate" => $comment->getCreatedDate(),
                "end" => 0
            ];
            array_push($arrayComments, $arrayComment);
        }
         return $arrayComments;
    }
}
