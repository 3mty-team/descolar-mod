<?php

namespace App\Repository\Report;

use App\Entity\Report\PostReport;
use App\Entity\Report\ReportCategory;
use App\Entity\User;
use App\Repository\Report\EnvReader\EnvReader;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

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

    public function findByDescolarID(int $descolarId): ?PostReport
    {
        $postReport = $this->findOneBy(
            ['descolarId' => $descolarId]
        );

        return $postReport;
    }

    /**
     * Populate post_report table from Descolar API
     * @param EntityManagerInterface $entityManager
     * @return void
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function populateDB(EntityManagerInterface $entityManager): void
    {
        $httpClient = HttpClient::create();

        $response = $httpClient->request('GET', 'https://internal-api.descolar.fr/v1/report/post', [
            'headers' => [
                'Authorization' => 'Bearer ' . EnvReader::getInstance()->get('TOKEN')
            ]
        ])->getContent();
        $response = json_decode($response, true);

        foreach ($response['post_reports'] as $postReport) {
            $descolarReportId = $postReport['id'];

            if (is_null($this->findByDescolarID($descolarReportId))) { // Check if report is already inside table
                $pReport = new PostReport();
                $pReport->setDescolarId($descolarReportId);
                $pReport->setPostContent($postReport['postContent']);
                $pReport->setPostId($postReport['postId']);
                $pReport->setUserName($postReport['userReported']['username']);
                $pReport->setUserUuid($postReport['userReported']['uuid']);

                $rCategory = $entityManager->getRepository(ReportCategory::class)->findCategoryByString($postReport['reportCategory']);
                $pReport->setReportCategory($rCategory);

                $pReport->setDate(new \DateTime(
                    $postReport['date']['date'],
                    new \DateTimeZone($postReport['date']['timezone'])
                ));

                $pReport->setComment($postReport['comment']);
                $pReport->setTreating(false);
                $pReport->setIgnored(false);
                $pReport->setDeleted(false);
                $pReport->setUserBan(false);

                $this->getEntityManager()->persist($pReport);
                $this->getEntityManager()->flush();
            }
        }
    }

    /**
     * @param int|null $id
     * @param User|null $admin
     * @param bool $ignored
     * @param bool $deleted
     * @param bool $userBan
     * @return void
     * @throws \Exception|TransportExceptionInterface
     */
    public function closeReport(?int $id, ?User $admin, bool $ignored, bool $deleted, bool $userBan): void
    {
        $httpClient = HttpClient::create();

        $postReport = $this->find($id);

        $postReport->setTreating(true);
        $postReport->setAdminProcessing($admin);
        $postReport->setIgnored($ignored);
        $postReport->setDeleted($deleted);
        $postReport->setUserBan($userBan);
        $postReport->setResultDate(new \DateTime('', new \DateTimeZone('Europe/Paris')));

        $this->getEntityManager()->persist($postReport);
        $this->getEntityManager()->flush();

        //Send infos to Descolar API :
        if ($userBan) { // If user is banned
            $httpClient->request('PUT', "https://internal-api.descolar.fr/v1/user/disable/forever/{$postReport->getUserUuid()}", [
                'headers' => [
                    'Authorization' => 'Bearer ' . EnvReader::getInstance()->get('TOKEN')
                ]
            ]);
        }

        if ($deleted) { // If post is taken down
            $httpClient->request('DELETE', "https://internal-api.descolar.fr/v1/post/{$postReport->getPostId()}", [
                'headers' => [
                    'Authorization' => 'Bearer ' . EnvReader::getInstance()->get('TOKEN')
                ]
            ]);
        }

        // Close report
        $descolarReportId = $postReport->getDescolarId();
        $httpClient->request('DELETE', "https://internal-api.descolar.fr/v1/report/post/{$descolarReportId}/delete", [
            'headers' => [
                'Authorization' => 'Bearer ' . EnvReader::getInstance()->get('TOKEN')
            ]
        ]);
    }
}