<?php

namespace App\Repository;

use App\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Team>
 */
class TeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Team::class);
    }

    public function findAllTeams() :array
    {
        $qb = $this->createQueryBuilder('team')
            ->select('team')
            ->getQuery()
            ->getResult();
            
       return $qb;
    }

    public function findTeamById(int $id): ?Team
    {
        $query = $this->createQueryBuilder('t')
            ->select('t')
            ->where('t.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();

        return $query;
    } 

    public function findAllTeamsDesc()
    {
        $query = $this->createQueryBuilder('t')
            ->select('t')
            ->orderBy('t.name', 'DESC')
            ->getQuery()
            ->getResult();

        return $query;
    }

    public function findTeamsByLetterAndLenght(string $letter, int $length)
    {
        $query = $this->createQueryBuilder('t')
            ->select('t')
            ->where('t.name LIKE :letter')
            ->andWhere('LENGTH(t.name) > :length')
            ->setParameter('length', $length)
            ->setParameter('letter', $letter, '%')
            ->getQuery()
            ->getResult();
    }

}

