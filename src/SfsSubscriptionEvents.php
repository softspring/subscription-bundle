<?php

namespace Softspring\SubscriptionBundle;

class SfsSubscriptionEvents
{
    /**
     * @Event("Softspring\SubscriptionBundle\Event\SubscriptionEvent")
     */
    const SUBSCRIPTION_SUBSCRIBE = 'sfs_subscription.subscription.subscribe';

    /**
     * @Event("Softspring\SubscriptionBundle\Event\SubscriptionEvent")
     */
    const SUBSCRIPTION_ADD_PLAN = 'sfs_subscription.subscription.add_plan';

    /**
     * @Event("Softspring\SubscriptionBundle\Event\SubscriptionEvent")
     */
    const SUBSCRIPTION_UNSUBSCRIBE = 'sfs_subscription.subscription.unsubscribe';

    /**
     * @Event("Softspring\SubscriptionBundle\Event\SubscriptionEvent")
     */
    const SUBSCRIPTION_CANCEL_RENOVATION = 'sfs_subscription.subscription.cancel_renovation';

    /**
     * @Event("Softspring\SubscriptionBundle\Event\SubscriptionEvent")
     */
    const SUBSCRIPTION_UNCANCEL_RENOVATION = 'sfs_subscription.subscription.uncancel_renovation';

    /**
     * @Event("Softspring\SubscriptionBundle\Event\SubscriptionEvent")
     */
    const SUBSCRIPTION_CANCEL = 'sfs_subscription.subscription.cancel';

    /**
     * @Event("Softspring\SubscriptionBundle\Event\SubscriptionEvent")
     */
    const SUBSCRIPTION_SYNC = 'sfs_subscription.subscription.sync';

    /**
     * @Event("Softspring\SubscriptionBundle\Event\SubscriptionUpgradeEvent")
     */
    const SUBSCRIPTION_UPGRADE = 'sfs_subscription.subscription.Upgrade';

    /**
     * @Event("Softspring\CoreBundle\Event\ViewEvent")
     */
    const SUBSCRIPTION_PRICING_LIST_VIEW = 'sfs_subscription.subscription.pricing.list_view';

    /**
     * @Event("Softspring\SubscriptionBundle\Event\PreSubscribeGetResponseEvent")
     */
    const SUBSCRIPTION_SUBSCRIBE_INITIALIZE = 'sfs_subscription.subscription.subscribe.initialize';

    /**
     * @Event("Softspring\SubscriptionBundle\Event\SubscriptionGetResponseEvent")
     */
    const SUBSCRIPTION_SUBSCRIBE_SUCCESS = 'sfs_subscription.subscription.subscribe.success';

    /**
     * @Event("Softspring\SubscriptionBundle\Event\SubscriptionFailedGetResponseEvent")
     */
    const SUBSCRIPTION_SUBSCRIBE_FAILED = 'sfs_subscription.subscription.subscribe.failed';

    /**
     * @Event("Softspring\SubscriptionBundle\Event\PreSubscribeGetResponseEvent")
     */
    const SUBSCRIPTION_TRIAL_INITIALIZE = 'sfs_subscription.subscription.trial.initialize';

    /**
     * @Event("Softspring\SubscriptionBundle\Event\SubscriptionGetResponseEvent")
     */
    const SUBSCRIPTION_TRIAL_SUCCESS = 'sfs_subscription.subscription.trial.success';

    /**
     * @Event("Softspring\SubscriptionBundle\Event\SubscriptionFailedGetResponseEvent")
     */
    const SUBSCRIPTION_TRIAL_FAILED = 'sfs_subscription.subscription.trial.failed';

    /**
     * @Event("Softspring\SubscriptionBundle\Event\UpgradeGetResponseEvent")
     */
    const SUBSCRIPTION_UPGRADE_INITIALIZE = 'sfs_subscription.subscription.upgrade.initialize';

    /**
     * @Event("Softspring\SubscriptionBundle\Event\UpgradeGetResponseEvent")
     */
    const SUBSCRIPTION_UPGRADE_TRIAL_SUCCESS = 'sfs_subscription.subscription.upgrade.trial_success';

    /**
     * @Event("Softspring\SubscriptionBundle\Event\UpgradeGetResponseEvent")
     */
    const SUBSCRIPTION_UPGRADE_PLAN_SUCCESS = 'sfs_subscription.subscription.upgrade.plan_success';

    /**
     * @Event("Softspring\SubscriptionBundle\Event\UpgradeGetResponseEvent")
     */
    const SUBSCRIPTION_UPGRADE_SUCCESS = 'sfs_subscription.subscription.upgrade.success';

    /**
     * @Event("Softspring\SubscriptionBundle\Event\UpgradeFailedGetResponseEvent")
     */
    const SUBSCRIPTION_UPGRADE_FAILED = 'sfs_subscription.subscription.upgrade.failed';

    /**
     * @Event("Softspring\CoreBundle\Event\ViewEvent")
     */
    const ADMIN_PRODUCTS_LIST_VIEW = 'sfs_subscription.admin.products.list_view';

    /**
     * @Event("Softspring\CrudlBundle\Event\GetResponseEntityEvent")
     */
    const ADMIN_PRODUCTS_CREATE_INITIALIZE = 'sfs_subscription.admin.products.create_initialize';

    /**
     * @Event("Softspring\CrudlBundle\Event\GetResponseFormEvent")
     */
    const ADMIN_PRODUCTS_CREATE_FORM_VALID = 'sfs_subscription.admin.products.create_form_valid';

    /**
     * @Event("Softspring\CrudlBundle\Event\GetResponseEntityEvent")
     */
    const ADMIN_PRODUCTS_CREATE_SUCCESS = 'sfs_subscription.admin.products.create_success';

    /**
     * @Event("Softspring\CrudlBundle\Event\GetResponseFormEvent")
     */
    const ADMIN_PRODUCTS_CREATE_FORM_INVALID = 'sfs_subscription.admin.products.create_form_invalid';

    /**
     * @Event("Softspring\CoreBundle\Event\ViewEvent")
     */
    const ADMIN_PRODUCTS_CREATE_VIEW = 'sfs_subscription.admin.products.create_view';

    /**
     * @Event("Softspring\CoreBundle\Event\ViewEvent")
     */
    const ADMIN_PLANS_LIST_VIEW = 'sfs_subscription.admin.plans.list_view';

    /**
     * @Event("Softspring\CrudlBundle\Event\GetResponseEntityEvent")
     */
    const ADMIN_PLANS_CREATE_INITIALIZE = 'sfs_subscription.admin.plans.create_initialize';

    /**
     * @Event("Softspring\CrudlBundle\Event\GetResponseFormEvent")
     */
    const ADMIN_PLANS_CREATE_FORM_VALID = 'sfs_subscription.admin.plans.create_form_valid';

    /**
     * @Event("Softspring\CrudlBundle\Event\GetResponseEntityEvent")
     */
    const ADMIN_PLANS_CREATE_SUCCESS = 'sfs_subscription.admin.plans.create_success';

    /**
     * @Event("Softspring\CrudlBundle\Event\GetResponseFormEvent")
     */
    const ADMIN_PLANS_CREATE_FORM_INVALID = 'sfs_subscription.admin.plans.create_form_invalid';

    /**
     * @Event("Softspring\CoreBundle\Event\ViewEvent")
     */
    const ADMIN_PLANS_CREATE_VIEW = 'sfs_subscription.admin.plans.create_view';

    /**
     * @Event("Softspring\CrudlBundle\Event\FilterEvent")
     */
    const ADMIN_SUBSCRIPTIONS_LIST_FILTER = 'sfs_subscription.admin.subscriptions.list_filter';

    /**
     * @Event("Softspring\CoreBundle\Event\ViewEvent")
     */
    const ADMIN_SUBSCRIPTIONS_LIST_VIEW = 'sfs_subscription.admin.subscriptions.list_view';

    /**
     * @Event("Softspring\CoreBundle\Event\ViewEvent")
     */
    const ADMIN_SUBSCRIPTIONS_READ_VIEW = 'sfs_subscription.admin.subscriptions.read_view';
}