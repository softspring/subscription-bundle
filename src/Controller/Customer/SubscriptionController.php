<?php

namespace Softspring\SubscriptionBundle\Controller\Customer;

use ArrayObject;
use Doctrine\ORM\EntityManagerInterface;
use Softspring\Component\Events\DispatchGetResponseTrait;
use Softspring\PlatformBundle\Exception\SubscriptionException;
use Softspring\SubscriptionBundle\Event\UpgradeFailedGetResponseEvent;
use Softspring\SubscriptionBundle\Event\UpgradeGetResponseEvent;
use Softspring\SubscriptionBundle\Manager\PlanManagerInterface;
use Softspring\SubscriptionBundle\Manager\SubscriptionManagerInterface;
use Softspring\SubscriptionBundle\Model\PlanInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionCustomerInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionInterface;
use Softspring\SubscriptionBundle\SfsSubscriptionEvents;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionController extends AbstractController
{
    use DispatchGetResponseTrait;

    public function __construct(
        protected SubscriptionManagerInterface $subscriptionManager,
        protected PlanManagerInterface $planManager,
        protected EventDispatcherInterface $eventDispatcher,
        protected EntityManagerInterface $em,
    ) {
    }

    public function overview(SubscriptionCustomerInterface $customer, Request $request): Response
    {
        $viewData = new ArrayObject([
            'customer' => $customer,
        ]);

        // $this->eventDispatcher->dispatch(new ViewEvent($viewData), SfsSubscriptionEvents::SUBSCRIPTION_PRICING_LIST_VIEW);

        return $this->render('@SfsSubscription/customer/subscription/overview.html.twig', $viewData->getArrayCopy());
    }

    public function details(SubscriptionInterface $subscription, Request $request): Response
    {
        $viewData = new ArrayObject([
            'subscription' => $subscription,
        ]);

        // $this->eventDispatcher->dispatch(new ViewEvent($viewData), SfsSubscriptionEvents::SUBSCRIPTION_PRICING_LIST_VIEW);

        return $this->render('@SfsSubscription/customer/subscription/details.html.twig', $viewData->getArrayCopy());
    }

    public function cancel(SubscriptionInterface $subscription): Response
    {
        // TODO DISPATCH EVENT

        try {
            $this->subscriptionManager->cancelRenovation($subscription);
            // TODO DISPATCH EVENT
        } catch (SubscriptionException $e) {
            // TODO DISPATCH EVENT
        }

        return $this->redirectToRoute('sfs_subscription_customer_subscription_details', ['subscription' => $subscription]);
    }

    public function reactivate(SubscriptionInterface $subscription): Response
    {
        // TODO DISPATCH EVENT

        try {
            $this->subscriptionManager->uncancelRenovation($subscription);
            // TODO DISPATCH EVENT
        } catch (SubscriptionException $e) {
            // TODO DISPATCH EVENT
        }

        return $this->redirectToRoute('sfs_subscription_customer_subscription_details', ['subscription' => $subscription]);
    }

    public function chooseUpgrade(SubscriptionCustomerInterface $customer, SubscriptionInterface $subscription, Request $request): Response
    {
        $repo = $this->planManager->getRepository();
        $plans = $repo->findBy(['active' => true, 'online' => true]);

        $viewData = new ArrayObject([
            'customer' => $customer,
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

    public function upgradePlan(SubscriptionCustomerInterface $customer, SubscriptionInterface $subscription, PlanInterface $plan, Request $request): Response
    {
        $oldPlan = $subscription->getPlan();

        if (($response = $this->dispatchGetResponse(SfsSubscriptionEvents::SUBSCRIPTION_UPGRADE_INITIALIZE, new UpgradeGetResponseEvent($subscription, $oldPlan, $plan, $request))) instanceof Response) {
            return $response;
        }

        try {
            /** @var SubscriptionInterface $activeSubscription */
            $activeSubscription = $customer->getActiveSubscriptions()->last();
            if (SubscriptionInterface::STATUS_TRIALING == $activeSubscription->getStatus()) {
                $this->subscriptionManager->finishTrial($subscription, $plan);

                if (($response = $this->dispatchGetResponse(SfsSubscriptionEvents::SUBSCRIPTION_UPGRADE_TRIAL_SUCCESS, new UpgradeGetResponseEvent($subscription, $oldPlan, $plan, $request))) instanceof Response) {
                    $this->subscriptionManager->saveEntity($subscription);

                    return $response;
                }
            } else {
                $this->subscriptionManager->upgrade($subscription, $plan);

                if (($response = $this->dispatchGetResponse(SfsSubscriptionEvents::SUBSCRIPTION_UPGRADE_PLAN_SUCCESS, new UpgradeGetResponseEvent($subscription, $oldPlan, $plan, $request))) instanceof Response) {
                    $this->subscriptionManager->saveEntity($subscription);

                    return $response;
                }
            }

            if (($response = $this->dispatchGetResponse(SfsSubscriptionEvents::SUBSCRIPTION_UPGRADE_SUCCESS, new UpgradeGetResponseEvent($subscription, $oldPlan, $plan, $request))) instanceof Response) {
                $this->subscriptionManager->saveEntity($subscription);

                return $response;
            }
        } catch (SubscriptionException $e) {
            if (($response = $this->dispatchGetResponse(SfsSubscriptionEvents::SUBSCRIPTION_UPGRADE_FAILED, new UpgradeFailedGetResponseEvent($subscription, $plan, $e, $request))) instanceof Response) {
                return $response;
            }
        }

        return $this->redirectToRoute('sfs_subscription_customer_subscription_details', ['subscription' => $subscription]);
    }
}
