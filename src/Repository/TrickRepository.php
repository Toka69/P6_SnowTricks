<?php

namespace App\Repository;

use App\Entity\Trick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Trick|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trick|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trick[]    findAll()
 * @method Trick[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrickRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trick::class);
    }

    public function dataTransform($tricks): array
    {
        $arrayTricks = [];
        foreach ($tricks as $trick) {
            $arrayTrick = [
                'id' => $trick->getId(),
                'name' => $trick->getName(),
                'cover' => $trick->getCover(),
                'slug' => $trick->getSlug(),
                'categorySlug' => $trick->getCategory()->getSlug(),
                'end' => 0
            ];
            array_push($arrayTricks, $arrayTrick);
        }
        return $arrayTricks;
    }
}
