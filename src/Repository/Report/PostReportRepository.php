<?php

namespace App\Repository\Report;

use App\Entity\Report\PostReport;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PostReport>
 *
 * @method PostReport|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostReport|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostReport[]    findAll()
 * @method PostReport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostReportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostReport::class);
    }

    /**
     * @return PostReport[]
     */
    public function findOpenReports(): array
    {
        return $this->findBy(
            ['treating' => 0]
        );
    }

    /**
     * Populate reports from Descolar API
     * @param $json
     * @return void
     */
    public function populateDB($json): void
    {

    }

    /**
     * @param int|null $id
     * @param User|null $admin
     * @param bool $ignored
     * @param bool $deleted
     * @param bool $userBan
     * @return void
     * @throws \Exception
     */
    public function closeReport(?int $id, ?User $admin, bool $ignored, bool $deleted, bool $userBan): void
    {
        $postReport = $this->find($id);

        $postReport->setTreating(1);
        $postReport->setAdminProcessing($admin);
        $postReport->setIgnored($ignored);
        $postReport->setDeleted($deleted);
        $postReport->setUserBan($userBan);
        $postReport->setResultDate(new \DateTime('', new \DateTimeZone('Europe/Paris')));

        $this->getEntityManager()->persist($postReport);
        $this->getEntityManager()->flush();

        //TODO Send infos to Descolar API
    }
}