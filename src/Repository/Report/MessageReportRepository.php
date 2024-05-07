<?php

namespace App\Repository\Report;

use App\Entity\Report\MessageReport;
use App\Entity\Report\PostReport;
use App\Entity\Report\ReportCategory;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

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

    public function findByDescolarID(int $descolarId): ?MessageReport
    {
        $messageReport = $this->findOneBy(
            ['descolarId' => $descolarId]
        );

        return $messageReport;
    }

    /**
     * Populate reports from Descolar API
     * @param EntityManagerInterface $entityManager
     * @return void
     */
    public function populateDB(EntityManagerInterface $entityManager): void
    {
        try {
            $this->messagePopulateDB($entityManager);
            $this->groupMessagePopulateDB($entityManager);
        } catch (ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface|TransportExceptionInterface $e) {
        }
    }

    /** Populate DB with message reports
     * @param EntityManagerInterface $entityManager
     * @return void
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function messagePopulateDB(EntityManagerInterface $entityManager): void
    {
        $httpClient = HttpClient::create();

        $response = $httpClient->request('GET', 'https://internal-api.descolar.fr/v1/report/message')->getContent();
        $response = json_decode($response, true);

        foreach ($response['message_reports'] as $messageReport) {
            $descolarReportId = $messageReport['id'];

            if (is_null($this->findByDescolarID($descolarReportId))) { // Check if report is already inside table
                $mReport = new MessageReport();
                $mReport->setIsGroupMessage(false);
                $mReport->setDescolarId($descolarReportId);
                $mReport->setContent($messageReport['messageContent']);
                $mReport->setMessageId($messageReport['messageId']);
                $mReport->setUserName($messageReport['userReported']['username']);
                $mReport->setUserUuid($messageReport['userReported']['uuid']);

                $rCategory = $entityManager->getRepository(ReportCategory::class)->findCategoryByString($messageReport['reportCategory']);
                $mReport->setReportCategory($rCategory);

                $mReport->setDate(new \DateTime(
                    $messageReport['date']['date'],
                    new \DateTimeZone($messageReport['date']['timezone'])
                ));

                $mReport->setComment($messageReport['comment']);
                $mReport->setTreating(false);
                $mReport->setIgnored(false);
                $mReport->setDeleted(false);
                $mReport->setUserBan(false);

                $this->getEntityManager()->persist($mReport);
                $this->getEntityManager()->flush();
            }
        }
    }

    /** /** Populate DB with group message reports
     * @param EntityManagerInterface $entityManager
     * @return void
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function groupMessagePopulateDB(EntityManagerInterface $entityManager): void
    {
        $httpClient = HttpClient::create();

        $response = $httpClient->request('GET', 'https://internal-api.descolar.fr/v1/report/groupmessage')->getContent();
        $response = json_decode($response, true);

        foreach ($response['groupmessage_reports'] as $groupMessageReport) {
            $descolarReportId = $groupMessageReport['id'];

            if (is_null($this->findByDescolarID($descolarReportId))) { // Check if report is already inside table
                $gmReport = new MessageReport();
                $gmReport->setIsGroupMessage(true);
                $gmReport->setDescolarId($descolarReportId);
                $gmReport->setContent($groupMessageReport['messageContent']);
                $gmReport->setMessageId($groupMessageReport['groupMessageId']);
                $gmReport->setUserName($groupMessageReport['userReported']['username']);
                $gmReport->setUserUuid($groupMessageReport['userReported']['uuid']);

                $rCategory = $entityManager->getRepository(ReportCategory::class)->findCategoryByString($groupMessageReport['reportCategory']);
                $gmReport->setReportCategory($rCategory);

                $gmReport->setDate(new \DateTime(
                    $groupMessageReport['date']['date'],
                    new \DateTimeZone($groupMessageReport['date']['timezone'])
                ));

                $gmReport->setComment($groupMessageReport['comment']);
                $gmReport->setTreating(false);
                $gmReport->setIgnored(false);
                $gmReport->setDeleted(false);
                $gmReport->setUserBan(false);

                $this->getEntityManager()->persist($gmReport);
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

        $messageReport = $this->find($id);

        $messageReport->setTreating(1);
        $messageReport->setAdminProcessing($admin);
        $messageReport->setIgnored($ignored);
        $messageReport->setDeleted($deleted);
        $messageReport->setUserBan($userBan);
        $messageReport->setResultDate(new \DateTime('', new \DateTimeZone('Europe/Paris')));

        $this->getEntityManager()->persist($messageReport);
        $this->getEntityManager()->flush();

        $isGroupMessage = $messageReport->getIsGroupMessage();
        $descolarReportId = $messageReport->getDescolarId();

        //Send infos to Descolar API :
        if ($userBan) { // If user is banned
            $httpClient->request('PUT', "https://internal-api.descolar.fr/v1/user/disable/forever/{$messageReport->getUserUuid()}");
        }

        if ($isGroupMessage) { // Report concerning a message in a group
            if ($deleted) { // If message is taken down
                // TODO groupmessage delete link
                //$httpClient->request('DELETE', "https://internal-api.descolar.fr/v1/message/{$messageReport->getMessageId()}/delete");
            }
            // Close report
            $httpClient->request('DELETE', "https://internal-api.descolar.fr/v1/report/groupmessage/{$descolarReportId}/delete");
        } else {
            if ($deleted) { // If message is taken down
                $httpClient->request('DELETE', "https://internal-api.descolar.fr/v1/message/{$messageReport->getMessageId()}/delete");
            }
            // Close report
            $httpClient->request('DELETE', "https://internal-api.descolar.fr/v1/report/message/{$descolarReportId}/delete");
        }
    }
}
