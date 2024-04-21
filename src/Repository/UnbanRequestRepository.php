<?php

namespace App\Repository;

use App\Entity\UnbanRequest;
use App\Entity\User;
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

    /**
     * @return UnbanRequest[]
     */
    public function findOpenRequests(): array
    {
        return $this->findBy(
            ['admin_processing' => null]
        );
    }

    /**
     * Populate unban request from Descolar API
     * @param $json
     * @return void
     */
    public function populateDB($json): void
    {

    }

    /**
     * @param int|null $id
     * @param User|null $admin
     * @param bool $unban
     * @return void
     * @throws \Exception
     */
    public function closeReport(?int $id, ?User $admin, bool $unban): void
    {
        $unbanRequest = $this->find($id);

        $unbanRequest->setAdminProcessing($admin);
        $unbanRequest->setUnban($unban);
        $unbanRequest->setResultDate(new \DateTime('', new \DateTimeZone('Europe/Paris')));

        $this->getEntityManager()->persist($unbanRequest);
        $this->getEntityManager()->flush();

        //TODO Send infos to Descolar API
    }
}
