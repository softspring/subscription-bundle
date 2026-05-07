<?php

declare(strict_types=1);

namespace Softspring\SubscriptionBundle\Controller\Admin;

use Softspring\PlatformBundle\Exception\SubscriptionException;
use Softspring\SubscriptionBundle\Manager\SubscriptionManagerInterface;
use Softspring\SubscriptionBundle\Model\SubscriptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionController extends AbstractController
{
    public function __construct(protected SubscriptionManagerInterface $subscriptionManager)
    {
    }

    public function sync(SubscriptionInterface $subscription): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUBSCRIPTION_ADMIN_SUBSCRIPTIONS_SYNC', $subscription);

        $this->subscriptionManager->sync($subscription);

        return $this->redirectToRoute('sfs_subscription_admin_subscriptions_read', ['subscription' => $subscription]);
    }

    public function cancelRenovation(SubscriptionInterface $subscription): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUBSCRIPTION_ADMIN_SUBSCRIPTIONS_CANCEL_RENOVATION', $subscription);

        // TODO DISPATCH EVENT

        try {
            $this->subscriptionManager->cancelRenovation($subscription);
            // TODO DISPATCH EVENT
        } catch (SubscriptionException $e) {
            // TODO DISPATCH EVENT
        }

        return $this->redirectToRoute('sfs_subscription_admin_subscriptions_read', ['subscription' => $subscription]);
    }

    public function uncancelRenovation(SubscriptionInterface $subscription): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUBSCRIPTION_ADMIN_SUBSCRIPTIONS_UNCANCEL_RENOVATION', $subscription);

        // TODO DISPATCH EVENT

        try {
            $this->subscriptionManager->uncancelRenovation($subscription);
            // TODO DISPATCH EVENT
        } catch (SubscriptionException $e) {
            // TODO DISPATCH EVENT
        }

        return $this->redirectToRoute('sfs_subscription_admin_subscriptions_read', ['subscription' => $subscription]);
    }

    public function cancelNow(SubscriptionInterface $subscription): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUBSCRIPTION_ADMIN_SUBSCRIPTIONS_CANCEL_NOW', $subscription);

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
        $qb->where('s.status < '.SubscriptionInterface::STATUS_CANCELED);
        $qb->where('p.interval = 30');
        $monthlyIncomes = $qb->getQuery()->getSingleScalarResult();

        $qb = $this->subscriptionManager->getRepository()->createQueryBuilder('s');
        $qb->leftJoin('s.plan', 'p');
        $qb->select('SUM(p.amount) AS total');
        $qb->where('s.status < '.SubscriptionInterface::STATUS_CANCELED);
        $qb->where('p.interval = 365');
        $yearlyIncomes = $qb->getQuery()->getSingleScalarResult();

        return $this->render('@SfsSubscription/admin/subscription/widget-expected-monthly-incomes.html.twig', [
            'incomes' => $monthlyIncomes + ($yearlyIncomes ? $yearlyIncomes / 12 : 0),
        ]);
    }

    public function expectedYearlyIncomesWidget(): Response
    {
        $qb = $this->subscriptionManager->getRepository()->createQueryBuilder('s');
        $qb->leftJoin('s.plan', 'p');
        $qb->select('SUM(p.amount) AS total');
        $qb->where('s.status < '.SubscriptionInterface::STATUS_CANCELED);
        $qb->where('p.interval = 30');
        $monthlyIncomes = $qb->getQuery()->getSingleScalarResult();

        $qb = $this->subscriptionManager->getRepository()->createQueryBuilder('s');
        $qb->leftJoin('s.plan', 'p');
        $qb->select('SUM(p.amount) AS total');
        $qb->where('s.status < '.SubscriptionInterface::STATUS_CANCELED);
        $qb->where('p.interval = 365');
        $yearlyIncomes = $qb->getQuery()->getSingleScalarResult();

        return $this->render('@SfsSubscription/admin/subscription/widget-expected-yearly-incomes.html.twig', [
            'incomes' => $monthlyIncomes * 12 + $yearlyIncomes,
        ]);
    }
}
