<?php

namespace App\Repository;

use App\Entity\Player;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Player>
 */
class PlayerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Player::class);
    }

    public function findAllTeams(){
        $this->createQueryBuilder('t');
    }

    public function search(string $query): array
    {
        $query = $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.nom LIKE :nom')
            ->orWhere('p.username LIKE :username')
            ->orWhere('p.number = :number')
            ->setParameter('nom', '%' . $query . '%')
            ->setParameter('username', '%' . $query . '%')
            ->setParameter('number', is_numeric($query) ? (int)$query : -1)
            ->getQuery()
            ->getResult();

        return $query;
    }

    //    /**
    //     * @return Player[] Returns an array of Player objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Player
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
