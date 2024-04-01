<?php

namespace App\Repository;

use App\Entity\UnbanRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UnbanRequest>
 *
 * @method UnbanRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method UnbanRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method UnbanRequest[]    findAll()
 * @method UnbanRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UnbanRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UnbanRequest::class);
    }

//    /**
//     * @return UnbanRequest[] Returns an array of UnbanRequest objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UnbanRequest
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
