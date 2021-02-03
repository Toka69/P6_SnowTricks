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
     * @param string|null $order
     * @param int|null $limit
     * @param int|null $offset
     * @return int|mixed|string
     */
    public function getCommentsByTrickId(Trick $trick, ?int $limit = null, ?int $offset = null, ?string $order = null){
        $query = $this->createQueryBuilder('c')
            ->andWhere('c.trick = :trickId')
            ->setParameter('trickId', $trick->getId())
            ;
        if ($limit){$query->setMaxResults($limit);}
        if ($offset){$query->setFirstResult($offset);}
        if ($order){$query->orderBy('c.id',$order);}

        $query = $query->getQuery();
        return $query->getResult();
    }
}
