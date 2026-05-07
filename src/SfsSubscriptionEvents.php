<?php

declare(strict_types=1);

namespace Softspring\SubscriptionBundle;

use Event;

class SfsSubscriptionEvents
{
    /**
     * @Event("Softspring\SubscriptionBundle\Event\SubscriptionEvent")
     */
    public const SUBSCRIPTION_SUBSCRIBE = 'sfs_subscription.subscription.subscribe';

    /**
     * @Event("Softspring\SubscriptionBundle\Event\SubscriptionEvent")
     */
    public const SUBSCRIPTION_ADD_PLAN = 'sfs_subscription.subscription.add_plan';

    /**
     * @Event("Softspring\SubscriptionBundle\Event\SubscriptionEvent")
     */
    public const SUBSCRIPTION_UNSUBSCRIBE = 'sfs_subscription.subscription.unsubscribe';

    /**
     * @Event("Softspring\SubscriptionBundle\Event\SubscriptionEvent")
     */
    public const SUBSCRIPTION_CANCEL_RENOVATION = 'sfs_subscription.subscription.cancel_renovation';

    /**
     * @Event("Softspring\SubscriptionBundle\Event\SubscriptionEvent")
     */
    public const SUBSCRIPTION_UNCANCEL_RENOVATION = 'sfs_subscription.subscription.uncancel_renovation';

    /**
     * @Event("Softspring\SubscriptionBundle\Event\SubscriptionEvent")
     */
    public const SUBSCRIPTION_CANCEL = 'sfs_subscription.subscription.cancel';

    /**
     * @Event("Softspring\SubscriptionBundle\Event\SubscriptionEvent")
     */
    public const SUBSCRIPTION_SYNC = 'sfs_subscription.subscription.sync';

    /**
     * @Event("Softspring\SubscriptionBundle\Event\SubscriptionUpgradeEvent")
     */
    public const SUBSCRIPTION_UPGRADE = 'sfs_subscription.subscription.Upgrade';

    /**
     * @Event("Softspring\Component\Events\ViewEvent")
     */
    public const SUBSCRIPTION_PRICING_LIST_VIEW = 'sfs_subscription.subscription.pricing.list_view';

    /**
     * @Event("Softspring\SubscriptionBundle\Event\PreSubscribeGetResponseEvent")
     */
    public const SUBSCRIPTION_SUBSCRIBE_INITIALIZE = 'sfs_subscription.subscription.subscribe.initialize';

    /**
     * @Event("Softspring\SubscriptionBundle\Event\SubscriptionGetResponseEvent")
     */
    public const SUBSCRIPTION_SUBSCRIBE_SUCCESS = 'sfs_subscription.subscription.subscribe.success';

    /**
     * @Event("Softspring\SubscriptionBundle\Event\SubscriptionFailedGetResponseEvent")
     */
    public const SUBSCRIPTION_SUBSCRIBE_FAILED = 'sfs_subscription.subscription.subscribe.failed';

    /**
     * @Event("Softspring\SubscriptionBundle\Event\PreSubscribeGetResponseEvent")
     */
    public const SUBSCRIPTION_TRIAL_INITIALIZE = 'sfs_subscription.subscription.trial.initialize';

    /**
     * @Event("Softspring\SubscriptionBundle\Event\SubscriptionGetResponseEvent")
     */
    public const SUBSCRIPTION_TRIAL_SUCCESS = 'sfs_subscription.subscription.trial.success';

    /**
     * @Event("Softspring\SubscriptionBundle\Event\SubscriptionFailedGetResponseEvent")
     */
    public const SUBSCRIPTION_TRIAL_FAILED = 'sfs_subscription.subscription.trial.failed';

    /**
     * @Event("Softspring\SubscriptionBundle\Event\UpgradeGetResponseEvent")
     */
    public const SUBSCRIPTION_UPGRADE_INITIALIZE = 'sfs_subscription.subscription.upgrade.initialize';

    /**
     * @Event("Softspring\SubscriptionBundle\Event\UpgradeGetResponseEvent")
     */
    public const SUBSCRIPTION_UPGRADE_TRIAL_SUCCESS = 'sfs_subscription.subscription.upgrade.trial_success';

    /**
     * @Event("Softspring\SubscriptionBundle\Event\UpgradeGetResponseEvent")
     */
    public const SUBSCRIPTION_UPGRADE_PLAN_SUCCESS = 'sfs_subscription.subscription.upgrade.plan_success';

    /**
     * @Event("Softspring\SubscriptionBundle\Event\UpgradeGetResponseEvent")
     */
    public const SUBSCRIPTION_UPGRADE_SUCCESS = 'sfs_subscription.subscription.upgrade.success';

    /**
     * @Event("Softspring\SubscriptionBundle\Event\UpgradeFailedGetResponseEvent")
     */
    public const SUBSCRIPTION_UPGRADE_FAILED = 'sfs_subscription.subscription.upgrade.failed';

    /**
     * @Event("Softspring\Component\Events\ViewEvent")
     */
    public const ADMIN_PRODUCTS_LIST_VIEW = 'sfs_subscription.admin.products.list_view';

    /**
     * @Event("Softspring\Component\CrudlController\Event\GetResponseEntityEvent")
     */
    public const ADMIN_PRODUCTS_CREATE_INITIALIZE = 'sfs_subscription.admin.products.create_initialize';

    /**
     * @Event("Softspring\Component\CrudlController\Event\GetResponseFormEvent")
     */
    public const ADMIN_PRODUCTS_CREATE_FORM_VALID = 'sfs_subscription.admin.products.create_form_valid';

    /**
     * @Event("Softspring\Component\CrudlController\Event\GetResponseEntityEvent")
     */
    public const ADMIN_PRODUCTS_CREATE_SUCCESS = 'sfs_subscription.admin.products.create_success';

    /**
     * @Event("Softspring\Component\CrudlController\Event\GetResponseFormEvent")
     */
    public const ADMIN_PRODUCTS_CREATE_FORM_INVALID = 'sfs_subscription.admin.products.create_form_invalid';

    /**
     * @Event("Softspring\Component\Events\ViewEvent")
     */
    public const ADMIN_PRODUCTS_CREATE_VIEW = 'sfs_subscription.admin.products.create_view';

    /**
     * @Event("Softspring\Component\Events\ViewEvent")
     */
    public const ADMIN_PLANS_LIST_VIEW = 'sfs_subscription.admin.plans.list_view';

    /**
     * @Event("Softspring\Component\CrudlController\Event\GetResponseEntityEvent")
     */
    public const ADMIN_PLANS_CREATE_INITIALIZE = 'sfs_subscription.admin.plans.create_initialize';

    /**
     * @Event("Softspring\Component\CrudlController\Event\GetResponseFormEvent")
     */
    public const ADMIN_PLANS_CREATE_FORM_VALID = 'sfs_subscription.admin.plans.create_form_valid';

    /**
     * @Event("Softspring\Component\CrudlController\Event\GetResponseEntityEvent")
     */
    public const ADMIN_PLANS_CREATE_SUCCESS = 'sfs_subscription.admin.plans.create_success';

    /**
     * @Event("Softspring\Component\CrudlController\Event\GetResponseFormEvent")
     */
    public const ADMIN_PLANS_CREATE_FORM_INVALID = 'sfs_subscription.admin.plans.create_form_invalid';

    /**
     * @Event("Softspring\Component\Events\ViewEvent")
     */
    public const ADMIN_PLANS_CREATE_VIEW = 'sfs_subscription.admin.plans.create_view';

    /**
     * @Event("Softspring\Component\CrudlController\Event\FilterEvent")
     */
    public const ADMIN_SUBSCRIPTIONS_LIST_FILTER = 'sfs_subscription.admin.subscriptions.list_filter';

    /**
     * @Event("Softspring\Component\Events\ViewEvent")
     */
    public const ADMIN_SUBSCRIPTIONS_LIST_VIEW = 'sfs_subscription.admin.subscriptions.list_view';

    /**
     * @Event("Softspring\Component\Events\ViewEvent")
     */
    public const ADMIN_SUBSCRIPTIONS_READ_VIEW = 'sfs_subscription.admin.subscriptions.read_view';
}
