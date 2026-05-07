<?php

namespace Softspring\SubscriptionBundle\Controller;

use App\Entity\CustomerSource;
use ArrayObject;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Softspring\Component\Events\DispatchGetResponseTrait;
use Softspring\Component\Events\ViewEvent;
use Softspring\CustomerBundle\Manager\SourceManagerInterface;
use Softspring\CustomerBundle\Model\SourceInterface;
use Softspring\PlatformBundle\Exception\SubscriptionException;
use Softspring\SubscriptionBundle\Event\PreSubscribeGetResponseEvent;
use Softspring\SubscriptionBundle\Event\SubscriptionFailedGetResponseEvent;
use Softspring\SubscriptionBundle\Event\SubscriptionGetResponseEvent;
use Softspring\SubscriptionBundle\Form\StripeAddCardForm;
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

class SubscribeController extends AbstractController
{
    use DispatchGetResponseTrait;

    public function __construct(
        protected SubscriptionManagerInterface $subscriptionManager,
        protected PlanManagerInterface $planManager,
        protected EventDispatcherInterface $eventDispatcher,
        protected EntityManagerInterface $em,
        protected SourceManagerInterface $sourcesManager,
    ) {
    }

    public function choosePlan(SubscriptionCustomerInterface $customer): Response
    {
        $repo = $this->planManager->getRepository();
        $plans = $repo->findBy(['active' => true, 'online' => true]);

        $viewData = new ArrayObject([
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
     * TODO: REFACTOR THIS METHOD AND FEATURE WHEN ADDED A SECOND ADAPTER, THIS IS STRIPE.
     */
    public function addStripeCard(SubscriptionCustomerInterface $customer, string $plan, Request $request): Response
    {
        /** @var PlanInterface $plan */
        $plan = $this->planManager->convert($plan);

        $form = $this->createForm(StripeAddCardForm::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $token = $form->get('stripeToken')->getData();

            /** @var CustomerSource $source */
            $source = $this->sourcesManager->createEntity();
            $source->setType(SourceInterface::TYPE_CARD);
            $source->setPlatformToken($token);
            $customer->addSource($source);
            $this->sourcesManager->saveEntity($source);

            return $this->redirectToRoute('sfs_subscription_subscribe', ['_account' => $customer, 'plan' => $plan]);
        }

        return $this->render('@SfsSubscription/subscribe/add_stripe_card.html.twig', [
            'plan' => $plan,
            'form' => $form->createView(),
        ]);
    }

    public function subscribe(SubscriptionCustomerInterface $customer, string $plan, Request $request): Response
    {
        /** @var PlanInterface $plan */
        $plan = $this->planManager->convert($plan);

        if (($response = $this->dispatchGetResponse(SfsSubscriptionEvents::SUBSCRIPTION_SUBSCRIBE_INITIALIZE, new PreSubscribeGetResponseEvent($customer, $plan, $request))) instanceof Response) {
            return $response;
        }

        try {
            /** @var SubscriptionInterface $subscription */
            $subscription = $customer->getActiveSubscriptions()->last();
            if ($subscription && SubscriptionInterface::STATUS_TRIALING == $subscription->getStatus()) {
                $this->subscriptionManager->finishTrial($customer, $subscription, $plan);

                if (($response = $this->dispatchGetResponse(SfsSubscriptionEvents::SUBSCRIPTION_SUBSCRIBE_SUCCESS, new SubscriptionGetResponseEvent($subscription, $request))) instanceof Response) {
                    $this->subscriptionManager->saveEntity($subscription);

                    return $response;
                }
            } else {
                $subscription = $this->subscriptionManager->subscribe($customer, $plan);

                if (($response = $this->dispatchGetResponse(SfsSubscriptionEvents::SUBSCRIPTION_SUBSCRIBE_SUCCESS, new SubscriptionGetResponseEvent($subscription, $request))) instanceof Response) {
                    $this->subscriptionManager->saveEntity($subscription);

                    return $response;
                }
            }

            throw new RuntimeException('After subscription success, SfsSubscriptionEvents::SUBSCRIPTION_SUBSCRIBE_SUCCESS event must set a response to continue');
        } catch (SubscriptionException $e) {
            if (($response = $this->dispatchGetResponse(SfsSubscriptionEvents::SUBSCRIPTION_SUBSCRIBE_FAILED, new SubscriptionFailedGetResponseEvent($customer, $plan, $e, $request))) instanceof Response) {
                return $response;
            }

            return $this->redirectToRoute('sfs_subscription_subscribe_choose_plan');
        }
    }

    public function trial(SubscriptionCustomerInterface $customer, string $plan, Request $request): Response
    {
        /** @var PlanInterface $plan */
        $plan = $this->planManager->convert($plan);

        // TODO check if plan allows trial mode
        //        if (! $plan->isTrialEnabled()) {
        //            return $this->redirectToRoute('sfs_subscription_subscribe_choose_plan');
        //        }

        if (($response = $this->dispatchGetResponse(SfsSubscriptionEvents::SUBSCRIPTION_TRIAL_INITIALIZE, new PreSubscribeGetResponseEvent($customer, $plan, $request))) instanceof Response) {
            return $response;
        }

        try {
            $subscription = $this->subscriptionManager->trial($customer, $plan);
            if (($response = $this->dispatchGetResponse(SfsSubscriptionEvents::SUBSCRIPTION_TRIAL_SUCCESS, new SubscriptionGetResponseEvent($subscription, $request))) instanceof Response) {
                $this->subscriptionManager->saveEntity($subscription);

                return $response;
            }

            throw new RuntimeException('After subscription success, SfsSubscriptionEvents::SUBSCRIPTION_TRIAL_SUCCESS event must set a response to continue');
        } catch (SubscriptionException $e) {
            if (($response = $this->dispatchGetResponse(SfsSubscriptionEvents::SUBSCRIPTION_TRIAL_FAILED, new SubscriptionFailedGetResponseEvent($customer, $plan, $e, $request))) instanceof Response) {
                return $response;
            }

            return $this->redirectToRoute('sfs_subscription_subscribe_choose_plan');
        }
    }
}
