<?php

namespace Softspring\SubscriptionBundle\Controller\Account;

use Doctrine\ORM\EntityManagerInterface;
use Softspring\AccountBundle\Model\AccountInterface;
use Softspring\AdminBundle\Event\ViewEvent;
use Softspring\SubscriptionBundle\Model\ClientInterface;
use Softspring\SubscriptionBundle\Model\PlanInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionInterface;
use Softspring\SubscriptionBundle\Adapter\ClientAdapterInterface;
use Softspring\SubscriptionBundle\Adapter\Stripe\StripeClientAdapter;
use Softspring\SubscriptionBundle\Controller\AbstractController;
use Softspring\SubscriptionBundle\Exception\SubscriptionException;
use Softspring\SubscriptionBundle\Manager\PlanManagerInterface;
use Softspring\SubscriptionBundle\Manager\SubscriptionManagerInterface;
use Softspring\SubscriptionBundle\SfsSubscriptionEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionController extends AbstractController
{
    /**
     * @var SubscriptionManagerInterface
     */
    protected $subscriptionManager;

    /**
     * @var PlanManagerInterface
     */
    protected $planManager;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @ var ClientAdapterInterface
     * @var StripeClientAdapter
     */
    protected $clientAdapter;

    /**
     * SubscribeController constructor.
     * @param SubscriptionManagerInterface $subscriptionManager
     * @param PlanManagerInterface $planManager
     * @param EventDispatcherInterface $eventDispatcher
     * @param EntityManagerInterface $em
     * @param ClientAdapterInterface $clientAdapter
     */
    public function __construct(SubscriptionManagerInterface $subscriptionManager, PlanManagerInterface $planManager, EventDispatcherInterface $eventDispatcher, EntityManagerInterface $em, ClientAdapterInterface $clientAdapter)
    {
        $this->subscriptionManager = $subscriptionManager;
        $this->planManager = $planManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->em = $em;
        $this->clientAdapter = $clientAdapter;
    }

    /**
     * @param AccountInterface $_account
     * @param Request $request
     * @return Response
     */
    public function details(AccountInterface $_account, Request $request): Response
    {
        /** @var ClientInterface $_account */

        $defaultSource = null;

        if (!$_account->getActiveSubscriptions()->count()) {
            return $this->redirectToRoute('sfs_subscription_subscribe_choose_plan', ['_account' => $_account]);
        }

        $subscription = $_account->getActiveSubscriptions()->first();

        $viewData = new \ArrayObject([
            'businessAccount' => $_account,
            'defaultSource' => $defaultSource ?? [],
            'subscription' => $subscription ?? null,
            'account' => $_account,
        ]);

        // $this->eventDispatcher->dispatch(new ViewEvent($viewData), SfsSubscriptionEvents::SUBSCRIPTION_PRICING_LIST_VIEW);

        return $this->render('@SfsSubscription/account/subscription/details.html.twig', $viewData->getArrayCopy());
    }

    /**
     * @param AccountInterface $_account
     * @param SubscriptionInterface $subscription
     * @return Response
     */
    public function cancel(AccountInterface $_account, SubscriptionInterface $subscription): Response
    {
        $client = $this->getClient($_account);

        // TODO DISPATCH EVENT

        try {
            $this->subscriptionManager->cancel($client, $subscription);
            // TODO DISPATCH EVENT
        } catch (SubscriptionException $e) {
            // TODO DISPATCH EVENT
        }

        return $this->redirectToRoute('sfs_subscription_account_subscription_details', ['_account' => $_account]);
    }

    /**
     * @param AccountInterface $_account
     * @param SubscriptionInterface $subscription
     * @return Response
     */
    public function reactivate(AccountInterface $_account, SubscriptionInterface $subscription): Response
    {
        $client = $this->getClient($_account);

        // TODO DISPATCH EVENT

        try {
            $this->subscriptionManager->uncancel($client, $subscription);
            // TODO DISPATCH EVENT
        } catch (SubscriptionException $e) {
            // TODO DISPATCH EVENT
        }

        return $this->redirectToRoute('sfs_subscription_account_subscription_details', ['_account' => $_account]);
    }

    /**
     * @param AccountInterface $_account
     * @param SubscriptionInterface $subscription
     * @param Request $request
     * @return Response
     */
    public function chooseUpgrade(AccountInterface $_account, SubscriptionInterface $subscription, Request $request): Response
    {
        $client = $this->getClient($_account);

        $repo = $this->planManager->getRepository();
        $plans = $repo->findBy(['active' => true, 'online' => true]);

        $viewData = new \ArrayObject([
            'client' => $client,
            'plans' => $plans,
            'subscription' => $subscription,
        ]);

        /** @var PlanInterface $plan */
        foreach ($plans as $plan) {
            $viewData["plan_{$plan->getPlanKey()}"] = $plan;
        }

        // $this->eventDispatcher->dispatch(new ViewEvent($viewData), SfsSubscriptionEvents::SUBSCRIPTION_PRICING_LIST_VIEW);

        return $this->render('@SfsSubscription/account/subscription/choose_upgrade.html.twig', $viewData->getArrayCopy());
    }

    /**
     * @param AccountInterface $_account
     * @param SubscriptionInterface $subscription
     * @param PlanInterface $plan
     * @param Request $request
     * @return Response
     */
    public function upgradePlan(AccountInterface $_account, SubscriptionInterface $subscription, PlanInterface $plan, Request $request): Response
    {
        $client = $this->getClient($_account);

        // TODO DISPATCH EVENT

        try {
            /** @var SubscriptionInterface $activeSubscription */
            $activeSubscription = $client->getActiveSubscriptions()->last();
            if ($activeSubscription->getStatus() == SubscriptionInterface::STATUS_TRIALING) {
                $this->subscriptionManager->finishTrial($client, $subscription, $plan);
            } else {
                $this->subscriptionManager->upgrade($client, $subscription, $plan);
            }

        } catch (SubscriptionException $e) {
            // TODO DISPATCH EVENT
        }

        return $this->redirectToRoute('sfs_subscription_account_subscription_details', ['_account' => $_account]);
    }
}