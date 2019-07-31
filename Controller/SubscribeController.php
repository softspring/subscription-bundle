<?php

namespace Softspring\SubscriptionBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Softspring\Account\Model\AccountInterface;
use Softspring\AdminBundle\Event\ViewEvent;
use Softspring\ExtraBundle\Controller\AbstractController;
use Softspring\Subscription\Model\ClientInterface;
use Softspring\Subscription\Model\PlanInterface;
use Softspring\SubscriptionBundle\Event\PreSubscribeGetResponseEvent;
use Softspring\SubscriptionBundle\Event\SubscriptionFailedGetResponseEvent;
use Softspring\SubscriptionBundle\Event\SubscriptionGetResponseEvent;
use Softspring\SubscriptionBundle\Manager\Exception\SubscriptionException;
use Softspring\SubscriptionBundle\Manager\PlanManagerInterface;
use Softspring\SubscriptionBundle\Manager\SubscriptionManagerInterface;
use Softspring\SubscriptionBundle\SfsSubscriptionEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SubscribeController extends AbstractController
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
     * SubscribeController constructor.
     * @param SubscriptionManagerInterface $subscriptionManager
     * @param PlanManagerInterface $planManager
     * @param EventDispatcherInterface $eventDispatcher
     * @param EntityManagerInterface $em
     */
    public function __construct(SubscriptionManagerInterface $subscriptionManager, PlanManagerInterface $planManager, EventDispatcherInterface $eventDispatcher, EntityManagerInterface $em)
    {
        $this->subscriptionManager = $subscriptionManager;
        $this->planManager = $planManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->em = $em;
    }

    /**
     * @param AccountInterface $_account
     * @return Response
     */
    public function choosePlan(AccountInterface $_account): Response
    {
        $client = $this->getClient($_account);

        $repo = $this->planManager->getRepository();
        $plans = $repo->findBy(['active' => true, 'online' => true]);

        $viewData = new \ArrayObject([
            'client' => $client,
            'plans' => $plans,
        ]);

        /** @var PlanInterface $plan */
        foreach ($plans as $plan) {
            $viewData["plan_{$plan->getPlanKey()}"] = $plan;
        }

        $this->eventDispatcher->dispatch(new ViewEvent($viewData), SfsSubscriptionEvents::SUBSCRIPTION_PRICING_LIST_VIEW);

        return $this->render('@SfsSubscription/subscribe/choose_plan.html.twig', $viewData->getArrayCopy());
    }

    public function addPaymentMethod(AccountInterface $_account, Request $request): Response
    {
         // this is needed ???????
    }

    /**
     * @param AccountInterface $_account
     * @param PlanInterface $plan
     * @param Request $request
     * @return Response
     */
    public function subscribe(AccountInterface $_account, PlanInterface $plan, Request $request): Response
    {
        $client = $this->getClient($_account);

        if ($response = $this->dispatchGetResponse(SfsSubscriptionEvents::SUBSCRIPTION_SUBSCRIBE_INITIALIZE, new PreSubscribeGetResponseEvent($client, $plan, $request))) {
            return $response;
        }

        try {
            $subscription = $this->subscriptionManager->subscribe($client, $plan);
            if ($response = $this->dispatchGetResponse(SfsSubscriptionEvents::SUBSCRIPTION_SUBSCRIBE_SUCCESS, new SubscriptionGetResponseEvent($subscription))) {
                $this->subscriptionManager->saveEntity($subscription);
                return $response;
            }

            throw new \RuntimeException('After subscription success, SfsSubscriptionEvents::SUBSCRIPTION_SUBSCRIBE_SUCCESS event must set a response to continue');
        } catch (SubscriptionException $e) {
            if ($response = $this->dispatchGetResponse(SfsSubscriptionEvents::SUBSCRIPTION_SUBSCRIBE_FAILED, new SubscriptionFailedGetResponseEvent($client, $plan, $e, $request))) {
                return $response;
            }

            return $this->redirectToRoute('sfs_subscription_subscribe_choose_plan');
        }
    }

    /**
     * @param AccountInterface $_account
     * @param PlanInterface $plan
     * @param Request $request
     * @return Response
     */
    public function trial(AccountInterface $_account, PlanInterface $plan, Request $request): Response
    {
        $client = $this->getClient($_account);

        // TODO check if plan allows trial mode
//        if (! $plan->isTrialEnabled()) {
//            return $this->redirectToRoute('sfs_subscription_subscribe_choose_plan');
//        }

        if ($response = $this->dispatchGetResponse(SfsSubscriptionEvents::SUBSCRIPTION_TRIAL_INITIALIZE, new PreSubscribeGetResponseEvent($client, $plan, $request))) {
            return $response;
        }

        try {
            $subscription = $this->subscriptionManager->trial($client, $plan);
            if ($response = $this->dispatchGetResponse(SfsSubscriptionEvents::SUBSCRIPTION_TRIAL_SUCCESS, new SubscriptionGetResponseEvent($subscription))) {
                $this->subscriptionManager->saveEntity($subscription);
                return $response;
            }

            throw new \RuntimeException('After subscription success, SfsSubscriptionEvents::SUBSCRIPTION_TRIAL_SUCCESS event must set a response to continue');
        } catch (SubscriptionException $e) {
            if ($response = $this->dispatchGetResponse(SfsSubscriptionEvents::SUBSCRIPTION_TRIAL_FAILED, new SubscriptionFailedGetResponseEvent($client, $plan, $e, $request))) {
                return $response;
            }

            return $this->redirectToRoute('sfs_subscription_subscribe_choose_plan');
        }
    }

    /**
     * Ensures AccountInterface entity implements subscription ClientInterface
     *
     * @param AccountInterface $_account
     * @return ClientInterface
     */
    private function getClient(AccountInterface $_account): ClientInterface
    {
        if (! $_account instanceof ClientInterface) {
            throw new \InvalidArgumentException(sprintf('Account "%s" class must implements "%s" to be used with subscriptions', get_class($_account), ClientInterface::class));
        }

        return $_account;
    }
}