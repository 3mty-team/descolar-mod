<?php

namespace App\Repository\Report;

use App\Entity\Report\ReportCategory;
use App\Entity\Report\UserReport;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use function PHPUnit\Framework\isNull;

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
     * @return void
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function populateDB(EntityManagerInterface $entityManager): void
    {
        $httpClient = HttpClient::create();

        $response = $httpClient->request('GET', 'https://internal-api.descolar.fr/v1/report/user')->getContent();
        $response = json_decode($response, true);

        foreach ($response['user_reports'] as $userReport) {
            $descolarReportId = $userReport['id'];

            if(is_null($this->findByDescolarID($descolarReportId))){ // Check if report is already inside table
                $uReport = new UserReport();
                $uReport->setDescolarId($descolarReportId);
                $uReport->setUserName($userReport['userReported']['username']);
                $uReport->setUserUuid($userReport['userReported']['uuid']);

                $rCategory = $entityManager->getRepository(ReportCategory::class)->findCategoryByString($userReport['reportCategory']);
                $uReport->setReportCategory($rCategory);

                $uReport->setDate(new \DateTime(
                    $userReport['date']['date'],
                    new \DateTimeZone($userReport['date']['timezone'])
                ));

                $uReport->setComment($userReport['comment']);
                $uReport->setTreating(0);
                $uReport->setBanned(0);

                $this->getEntityManager()->persist($uReport);
                $this->getEntityManager()->flush();
            }
        }
    }

    /**
     * @param int|null $id
     * @param User|null $admin
     * @param bool $result
     * @return void
     * @throws Exception|TransportExceptionInterface
     */
    public function closeReport(?int $id, ?User $admin, bool $result): void
    {
        $httpClient = HttpClient::create();

        $userReport = $this->find($id);
        $userReport->setTreating(1);
        $userReport->setAdminProcessing($admin);
        $userReport->setBanned($result);
        $userReport->setResultDate(new \DateTime('', new \DateTimeZone('Europe/Paris')));

        $this->getEntityManager()->persist($userReport);
        $this->getEntityManager()->flush();

        //Send infos to Descolar API :

        if ($result == 1) { // If User is banned
            $httpClient->request('PUT', "https://internal-api.descolar.fr/v1/user/disable/forever/{$userReport->getUserUuid()}");
        }

        // Close report
        $descolarReportId = $userReport->getDescolarId();
        $httpClient->request('DELETE', "https://internal-api.descolar.fr/v1/report/user/{$descolarReportId}/delete");
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

    public function findByDescolarID(int $descolarId): ?UserReport
    {
        $userReport = $this->findOneBy(
            ['descolarId' => $descolarId]
        );

       return $userReport;
    }

    public function toString(): string
    {
        return "";
    }

}
