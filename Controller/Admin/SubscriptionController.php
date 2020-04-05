<?php

namespace Softspring\SubscriptionBundle\Controller\Admin;

use Softspring\CoreBundle\Controller\AbstractController;
use Softspring\SubscriptionBundle\Manager\SubscriptionManagerInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionInterface;
use Softspring\SubscriptionBundle\Platform\Exception\SubscriptionException;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionController extends AbstractController
{
    /**
     * @var SubscriptionManagerInterface
     */
    protected $subscriptionManager;

    /**
     * SubscriptionController constructor.
     *
     * @param SubscriptionManagerInterface $subscriptionManager
     */
    public function __construct(SubscriptionManagerInterface $subscriptionManager)
    {
        $this->subscriptionManager = $subscriptionManager;
    }

    /**
     * @return Response
     * TODO restrict by permission
     */
    public function sync(SubscriptionInterface $subscription): Response
    {
        $this->subscriptionManager->sync($subscription);

        return $this->redirectToRoute('sfs_subscription_admin_subscriptions_read', ['subscription' => $subscription]);
    }

    /**
     * @param SubscriptionInterface $subscription
     *
     * @return Response
     */
    public function cancelRenovation(SubscriptionInterface $subscription): Response
    {
        // TODO DISPATCH EVENT

        try {
            $this->subscriptionManager->cancelRenovation($subscription);
            // TODO DISPATCH EVENT
        } catch (SubscriptionException $e) {
            // TODO DISPATCH EVENT
        }

        return $this->redirectToRoute('sfs_subscription_admin_subscriptions_read', ['subscription' => $subscription]);
    }

    /**
     * @param SubscriptionInterface $subscription
     *
     * @return Response
     */
    public function uncancelRenovation(SubscriptionInterface $subscription): Response
    {
        // TODO DISPATCH EVENT

        try {
            $this->subscriptionManager->uncancelRenovation($subscription);
            // TODO DISPATCH EVENT
        } catch (SubscriptionException $e) {
            // TODO DISPATCH EVENT
        }

        return $this->redirectToRoute('sfs_subscription_admin_subscriptions_read', ['subscription' => $subscription]);
    }

    /**
     * @param SubscriptionInterface $subscription
     *
     * @return Response
     */
    public function cancelNow(SubscriptionInterface $subscription): Response
    {
        // TODO DISPATCH EVENT

        try {
            $this->subscriptionManager->cancel($subscription);
            // TODO DISPATCH EVENT
        } catch (SubscriptionException $e) {
            // TODO DISPATCH EVENT
        }

        return $this->redirectToRoute('sfs_subscription_admin_subscriptions_read', ['subscription' => $subscription]);
    }

    public function subscriptionsCountWidget(): Response
    {
        return $this->render('@SfsSubscription/admin/subscription/widget-subscriptions-count.html.twig', [
            'subscriptions' => $this->subscriptionManager->getRepository()->count([]),
        ]);
    }

    public function expectedMonthlyIncomesWidget(): Response
    {
        $qb = $this->subscriptionManager->getRepository()->createQueryBuilder('s');
        $qb->leftJoin('s.plan', 'p');
        $qb->select('SUM(p.amount) AS total');
        $qb->where('s.status < '. SubscriptionInterface::STATUS_CANCELED);
        // $qb->select('SUM(IF(p.interval == 365 ; p.amount/12 ; p.amount) AS total');

        $incomes = $qb->getQuery()->getSingleScalarResult();

        return $this->render('@SfsSubscription/admin/subscription/widget-expected-monthly-incomes.html.twig', [
            'incomes' => $incomes,
        ]);
    }
}