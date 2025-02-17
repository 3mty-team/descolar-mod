<?php

namespace App\Repository\Report;

use App\Entity\Report\ReportCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReportCategory>
 *
 * @method ReportCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReportCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReportCategory[]    findAll()
 * @method ReportCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReportCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReportCategory::class);
    }

    public function findCategoryByString(string $name): ?ReportCategory
    {
        return $this->createQueryBuilder('category')
            ->where('category.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }

    //    /**
    //     * @return ReportCategory[] Returns an array of ReportCategory objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ReportCategory
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
