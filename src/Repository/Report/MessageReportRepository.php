<?php

namespace App\Repository\Report;

use App\Entity\Report\MessageReport;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MessageReport>
 *
 * @method MessageReport|null find($id, $lockMode = null, $lockVersion = null)
 * @method MessageReport|null findOneBy(array $criteria, array $orderBy = null)
 * @method MessageReport[]    findAll()
 * @method MessageReport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageReportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MessageReport::class);
    }

    /**
     * @return MessageReport[]
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
        $messageReport = $this->find($id);

        $messageReport->setTreating(1);
        $messageReport->setAdminProcessing($admin);
        $messageReport->setIgnored($ignored);
        $messageReport->setDeleted($deleted);
        $messageReport->setUserBan($userBan);
        $messageReport->setResultDate(new \DateTime('', new \DateTimeZone('Europe/Paris')));

        $this->getEntityManager()->persist($messageReport);
        $this->getEntityManager()->flush();

        //TODO Send infos to Descolar API
    }
}
