<?php

namespace App\Repository\Report;

use App\Entity\Report\UserReport;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserReport>
 *
 * @method UserReport|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserReport|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserReport[]    findAll()
 * @method UserReport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserReportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserReport::class);
    }

    /**
     * Populate user_table from Descolar API
     * @param $json
     * @return void
     */
    public function populateDB($json): void
    {

    }

    /**
     * @param int|null $id
     * @param User|null $admin
     * @param bool $result
     * @return void
     * @throws \Exception
     */
    public function closeReport(?int $id, ?User $admin, bool $result): void
    {
        $userReport = $this->find($id);

        $userReport->setTreating(1);
        $userReport->setAdminProcessing($admin);
        $userReport->setBanned($result);
        $userReport->setResultDate(new \DateTime('', new \DateTimeZone('Europe/Paris')));

        $this->getEntityManager()->persist($userReport);
        $this->getEntityManager()->flush();

        //TODO Send infos to Descolar API
    }

    /**
     * @return UserReport[]
     */
    public function findOpenReports(): array
    {
        return $this->findBy(
            ['treating' => 0]
        );
    }

    public function toString(): string
    {
        return "";
    }

    //    /**
    //     * @return UserReport[] Returns an array of UserReport objects
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

    //    public function findOneBySomeField($value): ?UserReport
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
