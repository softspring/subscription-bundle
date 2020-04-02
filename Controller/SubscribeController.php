<?php

namespace Softspring\SubscriptionBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Softspring\CoreBundle\Event\ViewEvent;
use Softspring\CoreBundle\Controller\AbstractController;
use Softspring\CustomerBundle\Platform\Adapter\CustomerAdapterInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionCustomerInterface;
use Softspring\SubscriptionBundle\Model\PlanInterface;
use Softspring\SubscriptionBundle\Event\PreSubscribeGetResponseEvent;
use Softspring\SubscriptionBundle\Event\SubscriptionFailedGetResponseEvent;
use Softspring\SubscriptionBundle\Event\SubscriptionGetResponseEvent;
use Softspring\SubscriptionBundle\Platform\Exception\SubscriptionException;
use Softspring\SubscriptionBundle\Form\StripeAddCardForm;
use Softspring\SubscriptionBundle\Manager\PlanManagerInterface;
use Softspring\SubscriptionBundle\Manager\SubscriptionManagerInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionInterface;
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
     * @var CustomerAdapterInterface
     */
    protected $customerAdapter;

    /**
     * SubscribeController constructor.
     *
     * @param SubscriptionManagerInterface $subscriptionManager
     * @param PlanManagerInterface         $planManager
     * @param EventDispatcherInterface     $eventDispatcher
     * @param EntityManagerInterface       $em
     * @param CustomerAdapterInterface     $customerAdapter
     */
    public function __construct(SubscriptionManagerInterface $subscriptionManager, PlanManagerInterface $planManager, EventDispatcherInterface $eventDispatcher, EntityManagerInterface $em, CustomerAdapterInterface $customerAdapter)
    {
        $this->subscriptionManager = $subscriptionManager;
        $this->planManager = $planManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->em = $em;
        $this->customerAdapter = $customerAdapter;
    }

    /**
     * @param SubscriptionCustomerInterface $customer
     *
     * @return Response
     */
    public function choosePlan(SubscriptionCustomerInterface $customer): Response
    {
        $repo = $this->planManager->getRepository();
        $plans = $repo->findBy(['active' => true, 'online' => true]);

        $viewData = new \ArrayObject([
            'customer' => $customer,
            'plans' => $plans,
        ]);

        /** @var PlanInterface $plan */
        foreach ($plans as $plan) {
            $viewData["plan_{$plan->getPlanKey()}"] = $plan;
        }

        $this->eventDispatcher->dispatch(new ViewEvent($viewData), SfsSubscriptionEvents::SUBSCRIPTION_PRICING_LIST_VIEW);

        return $this->render('@SfsSubscription/subscribe/choose_plan.html.twig', $viewData->getArrayCopy());
    }

    /**
     * TODO: REFACTOR THIS METHOD AND FEATURE WHEN ADDED A SECOND ADAPTER, THIS IS STRIPE
     *
     * @param SubscriptionCustomerInterface $customer
     * @param string                        $plan
     * @param Request                       $request
     *
     * @return Response
     */
    public function addStripeCard(SubscriptionCustomerInterface $customer, string $plan, Request $request): Response
    {
        /** @var PlanInterface $plan */
        $plan = $this->planManager->convert($plan);

        $form = $this->createForm(StripeAddCardForm::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $token = $form->get('stripeToken')->getData();

            $customer = $this->customerAdapter->customerAddToken($customer, $token);

            return $this->redirectToRoute('sfs_subscription_subscribe', ['_account' => $customer, 'plan' => $plan]);
        }

        return $this->render('@SfsSubscription/subscribe/add_stripe_card.html.twig', [
            'plan' => $plan,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param SubscriptionCustomerInterface $customer
     * @param string                        $plan
     * @param Request                       $request
     *
     * @return Response
     */
    public function subscribe(SubscriptionCustomerInterface $customer, string $plan, Request $request): Response
    {
        /** @var PlanInterface $plan */
        $plan = $this->planManager->convert($plan);

        if ($response = $this->dispatchGetResponse(SfsSubscriptionEvents::SUBSCRIPTION_SUBSCRIBE_INITIALIZE, new PreSubscribeGetResponseEvent($customer, $plan, $request))) {
            return $response;
        }

        try {
            /** @var SubscriptionInterface $subscription */
            $subscription = $customer->getActiveSubscriptions()->last();
            if ($subscription && $subscription->getStatus() == SubscriptionInterface::STATUS_TRIALING) {
                $this->subscriptionManager->finishTrial($customer, $subscription, $plan);

                if ($response = $this->dispatchGetResponse(SfsSubscriptionEvents::SUBSCRIPTION_SUBSCRIBE_SUCCESS, new SubscriptionGetResponseEvent($subscription, $request))) {
                    $this->subscriptionManager->saveEntity($subscription);
                    return $response;
                }
            } else {
                $subscription = $this->subscriptionManager->subscribe($customer, $plan);

                if ($response = $this->dispatchGetResponse(SfsSubscriptionEvents::SUBSCRIPTION_SUBSCRIBE_SUCCESS, new SubscriptionGetResponseEvent($subscription, $request))) {
                    $this->subscriptionManager->saveEntity($subscription);
                    return $response;
                }
            }

            throw new \RuntimeException('After subscription success, SfsSubscriptionEvents::SUBSCRIPTION_SUBSCRIBE_SUCCESS event must set a response to continue');
        } catch (SubscriptionException $e) {
            if ($response = $this->dispatchGetResponse(SfsSubscriptionEvents::SUBSCRIPTION_SUBSCRIBE_FAILED, new SubscriptionFailedGetResponseEvent($customer, $plan, $e, $request))) {
                return $response;
            }

            return $this->redirectToRoute('sfs_subscription_subscribe_choose_plan');
        }
    }

    /**
     * @param SubscriptionCustomerInterface $customer
     * @param string                        $plan
     * @param Request                       $request
     *
     * @return Response
     */
    public function trial(SubscriptionCustomerInterface $customer, string $plan, Request $request): Response
    {
        /** @var PlanInterface $plan */
        $plan = $this->planManager->convert($plan);

        // TODO check if plan allows trial mode
//        if (! $plan->isTrialEnabled()) {
//            return $this->redirectToRoute('sfs_subscription_subscribe_choose_plan');
//        }

        if ($response = $this->dispatchGetResponse(SfsSubscriptionEvents::SUBSCRIPTION_TRIAL_INITIALIZE, new PreSubscribeGetResponseEvent($customer, $plan, $request))) {
            return $response;
        }

        try {
            $subscription = $this->subscriptionManager->trial($customer, $plan);
            if ($response = $this->dispatchGetResponse(SfsSubscriptionEvents::SUBSCRIPTION_TRIAL_SUCCESS, new SubscriptionGetResponseEvent($subscription, $request))) {
                $this->subscriptionManager->saveEntity($subscription);
                return $response;
            }

            throw new \RuntimeException('After subscription success, SfsSubscriptionEvents::SUBSCRIPTION_TRIAL_SUCCESS event must set a response to continue');
        } catch (SubscriptionException $e) {
            if ($response = $this->dispatchGetResponse(SfsSubscriptionEvents::SUBSCRIPTION_TRIAL_FAILED, new SubscriptionFailedGetResponseEvent($customer, $plan, $e, $request))) {
                return $response;
            }

            return $this->redirectToRoute('sfs_subscription_subscribe_choose_plan');
        }
    }
}