<?php

namespace App\Controller;

use App\Entity\MarketingAccount;
use App\Repository\MarketingAccountRepository;
use App\Repository\MarketingSourceRepository;
use Doctrine\ORM\EntityManagerInterface;
use FacebookAds\Object\User as FacebookUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MarketingController extends AbstractController
{
    protected EntityManagerInterface $entityManager;

    /**
     * Class constructor.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/marketing/{slug}', name: 'marketing_source', priority: -1)]
    public function index(
        string $slug,
        MarketingSourceRepository $marketingSourceRepository,
        MarketingAccountRepository $marketingAccountRepository
    ): Response {
        $shop = $this->getUser();
        $marketingSource = $marketingSourceRepository->findOneBy(['slug' => $slug]);

        if (empty($marketingSource)) {
            throw $this->createNotFoundException('Marketing source not found !');
        }

        $marketingAccount = $marketingAccountRepository->findOneBy([
            'shop' => $shop,
            'marketingSource' => $marketingSource,
        ]);

        $adAccounts = [];
        if ($marketingAccount) {
            $data = $marketingAccount->getData();
            $userId = $data['user_id'];
            $adAccountId = $data['account_id'] ?? null;
            $user = new FacebookUser($userId);
            $response = $user->getAdAccounts(['name', 'account_id'])
                ->getResponse()
                ->getContent();

            $adAccounts = $response['data'];
        }

        return $this->render("marketing/{$marketingSource->getSlug()}.html.twig", [
            'source' => $marketingSource,
            'account' => $marketingAccount,
            'adAccounts' => $adAccounts,
            'adAccountId' => $adAccountId ?? null,
            'facebookAppId' => $this->getParameter('facebook.app.id'),
        ]);
    }

    #[Route('/marketing/facebook-ads/get-user-ad-accounts', name: 'marketing_fb_user_accounts', methods: ['POST'])]
    public function getFacebookAdAccounts(
        Request $request,
    ): Response {
        $content = json_decode($request->getContent(), true);
        $userId = $content['userId'];

        $user = new FacebookUser($userId);
        $response = $user->getAdAccounts(['name', 'account_id'])
            ->getResponse()
            ->getContent();

        $adAccounts = $response['data'];

        return $this->json(['accounts' => $adAccounts]);
    }

    #[Route('/marketing/{slug}/set-data', name: 'marketing_set_data', methods: ['POST'])]
    public function setData(
        string $slug,
        Request $request,
        MarketingSourceRepository $marketingSourceRepository,
        MarketingAccountRepository $marketingAccountRepository
    ): Response {
        $shop = $this->getUser();
        $marketingSource = $marketingSourceRepository->findOneBy(['slug' => $slug]);
        if (empty($marketingSource)) {
            throw $this->createNotFoundException('Marketing source not found !');
        }

        $marketingAccount = $marketingAccountRepository->findOneBy([
            'shop' => $shop,
            'marketingSource' => $marketingSource,
        ]);

        if (empty($marketingAccount)) {
            $marketingAccount = new MarketingAccount();
            $marketingAccount->setShop($shop);
            $marketingAccount->setMarketingSource($marketingSource);
            $this->entityManager->persist($marketingAccount);
        }

        $content = json_decode($request->getContent(), true);
        $userId = $content['userId'] ?? null;
        $accountId = $content['accountId'] ?? null;

        $data = $marketingAccount->getData();
        if ($userId) {
            $data['user_id'] = $userId;
        }
        if ($accountId) {
            $data['account_id'] = $accountId;
        }
        $marketingAccount->setData($data);
        $this->entityManager->flush();

        return $this->json(['success' => true]);
    }

    #[Route('/marketing/{slug}/disconnect', name: 'marketing_source_disconnect')]
    public function disconnect(
        string $slug,
        MarketingSourceRepository $marketingSourceRepository,
        MarketingAccountRepository $marketingAccountRepository
    ): Response {
        $shop = $this->getUser();
        $marketingSource = $marketingSourceRepository->findOneBy(['slug' => $slug]);
        if (empty($marketingSource)) {
            throw $this->createNotFoundException('Marketing source not found !');
        }

        $marketingAccount = $marketingAccountRepository->findOneBy([
            'shop' => $shop,
            'marketingSource' => $marketingSource,
        ]);

        if (!empty($marketingAccount)) {
            $this->entityManager->remove($marketingAccount);
        }

        $this->entityManager->flush();

        return $this->redirectToRoute('marketing_source', ['slug' => $slug]);
    }
}
