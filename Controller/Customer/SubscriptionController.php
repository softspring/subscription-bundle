<?php

namespace Softspring\SubscriptionBundle\Controller\Customer;

use Doctrine\ORM\EntityManagerInterface;
use Softspring\CoreBundle\Controller\AbstractController;
use Softspring\CustomerBundle\Platform\Adapter\CustomerAdapterInterface;
use Softspring\SubscriptionBundle\Event\UpgradeFailedGetResponseEvent;
use Softspring\SubscriptionBundle\Event\UpgradeGetResponseEvent;
use Softspring\SubscriptionBundle\Model\SubscriptionCustomerInterface;
use Softspring\SubscriptionBundle\Model\PlanInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionInterface;
use Softspring\SubscriptionBundle\Platform\Exception\SubscriptionException;
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
     * @var CustomerAdapterInterface
     */
    protected $clientAdapter;

    /**
     * SubscribeController constructor.
     *
     * @param SubscriptionManagerInterface $subscriptionManager
     * @param PlanManagerInterface         $planManager
     * @param EventDispatcherInterface     $eventDispatcher
     * @param EntityManagerInterface       $em
     * @param CustomerAdapterInterface     $clientAdapter
     */
    public function __construct(SubscriptionManagerInterface $subscriptionManager, PlanManagerInterface $planManager, EventDispatcherInterface $eventDispatcher, EntityManagerInterface $em, CustomerAdapterInterface $clientAdapter)
    {
        $this->subscriptionManager = $subscriptionManager;
        $this->planManager = $planManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->em = $em;
        $this->clientAdapter = $clientAdapter;
    }

    /**
     * @param SubscriptionCustomerInterface $customer
     * @param Request                       $request
     *
     * @return Response
     */
    public function overview(SubscriptionCustomerInterface $customer, Request $request): Response
    {
        $viewData = new \ArrayObject([
            'customer' => $customer,
        ]);

        // $this->eventDispatcher->dispatch(new ViewEvent($viewData), SfsSubscriptionEvents::SUBSCRIPTION_PRICING_LIST_VIEW);

        return $this->render('@SfsSubscription/customer/subscription/overview.html.twig', $viewData->getArrayCopy());
    }

    /**
     * @param SubscriptionInterface $subscription
     * @param Request               $request
     *
     * @return Response
     */
    public function details(SubscriptionInterface $subscription, Request $request): Response
    {
        $viewData = new \ArrayObject([
            'subscription' => $subscription,
        ]);

        // $this->eventDispatcher->dispatch(new ViewEvent($viewData), SfsSubscriptionEvents::SUBSCRIPTION_PRICING_LIST_VIEW);

        return $this->render('@SfsSubscription/customer/subscription/details.html.twig', $viewData->getArrayCopy());
    }

    /**
     * @param SubscriptionInterface $subscription
     *
     * @return Response
     */
    public function cancel(SubscriptionInterface $subscription): Response
    {
        // TODO DISPATCH EVENT

        try {
            $this->subscriptionManager->cancel($subscription);
            // TODO DISPATCH EVENT
        } catch (SubscriptionException $e) {
            // TODO DISPATCH EVENT
        }

        return $this->redirectToRoute('sfs_subscription_customer_subscription_details', ['subscription' => $subscription]);
    }

    /**
     * @param SubscriptionInterface $subscription
     *
     * @return Response
     */
    public function reactivate(SubscriptionInterface $subscription): Response
    {
        // TODO DISPATCH EVENT

        try {
            $this->subscriptionManager->uncancel($subscription);
            // TODO DISPATCH EVENT
        } catch (SubscriptionException $e) {
            // TODO DISPATCH EVENT
        }

        return $this->redirectToRoute('sfs_subscription_customer_subscription_details', ['subscription' => $subscription]);
    }

    /**
     * @param SubscriptionCustomerInterface $client
     * @param SubscriptionInterface         $subscription
     * @param Request                       $request
     *
     * @return Response
     */
    public function chooseUpgrade(SubscriptionCustomerInterface $client, SubscriptionInterface $subscription, Request $request): Response
    {
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

        return $this->render('@SfsSubscription/customer/subscription/choose_upgrade.html.twig', $viewData->getArrayCopy());
    }

    /**
     * @param SubscriptionCustomerInterface $client
     * @param SubscriptionInterface         $subscription
     * @param PlanInterface                 $plan
     * @param Request                       $request
     *
     * @return Response
     */
    public function upgradePlan(SubscriptionCustomerInterface $client, SubscriptionInterface $subscription, PlanInterface $plan, Request $request): Response
    {
        $oldPlan = $subscription->getPlan();

        if ($response = $this->dispatchGetResponse(SfsSubscriptionEvents::SUBSCRIPTION_UPGRADE_INITIALIZE, new UpgradeGetResponseEvent($subscription, $oldPlan, $plan, $request))) {
            return $response;
        }

        try {
            /** @var SubscriptionInterface $activeSubscription */
            $activeSubscription = $client->getActiveSubscriptions()->last();
            if ($activeSubscription->getStatus() == SubscriptionInterface::STATUS_TRIALING) {
                $this->subscriptionManager->finishTrial($client, $subscription, $plan);

                if ($response = $this->dispatchGetResponse(SfsSubscriptionEvents::SUBSCRIPTION_UPGRADE_TRIAL_SUCCESS, new UpgradeGetResponseEvent($subscription, $oldPlan, $plan, $request))) {
                    $this->subscriptionManager->saveEntity($subscription);
                    return $response;
                }
            } else {
                $this->subscriptionManager->upgrade($client, $subscription, $plan);

                if ($response = $this->dispatchGetResponse(SfsSubscriptionEvents::SUBSCRIPTION_UPGRADE_PLAN_SUCCESS, new UpgradeGetResponseEvent($subscription, $oldPlan, $plan, $request))) {
                    $this->subscriptionManager->saveEntity($subscription);
                    return $response;
                }
            }

            if ($response = $this->dispatchGetResponse(SfsSubscriptionEvents::SUBSCRIPTION_UPGRADE_SUCCESS, new UpgradeGetResponseEvent($subscription, $oldPlan, $plan, $request))) {
                $this->subscriptionManager->saveEntity($subscription);
                return $response;
            }
        } catch (SubscriptionException $e) {
            if ($response = $this->dispatchGetResponse(SfsSubscriptionEvents::SUBSCRIPTION_UPGRADE_FAILED, new UpgradeFailedGetResponseEvent($subscription, $plan, $e, $request))) {
                return $response;
            }
        }

        return $this->redirectToRoute('sfs_subscription_customer_subscription_details', ['_customer' => $client]);
    }
}