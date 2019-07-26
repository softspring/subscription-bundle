<?php

namespace Softspring\SubscriptionBundle;

class SfsSubscriptionEvents
{
    /**
     * @Event("Softspring\SubscriptionBundle\Event\GetResponseEvent")
     */
    const NOTIFY_INIT = 'sfs_subscription.nofify.initialize';

    /**
     * @Event("Softspring\SubscriptionBundle\Event\GetResponsePlanEvent")
     */
    const NOTIFY_PLAN_CREATE = 'sfs_subscription.notify.plan.create';

    /**
     * @Event("Softspring\SubscriptionBundle\Event\GetResponsePlanEvent")
     */
    const NOTIFY_PLAN_UPDATE = 'sfs_subscription.notify.plan.update';





    /**
     * @Event("Softspring\AdminBundle\Event\ViewEvent")
     */
    const ADMIN_PRODUCTS_LIST_VIEW = 'sfs_subscription.admin.products.list_view';

    /**
     * @Event("Softspring\AdminBundle\Event\GetResponseEntityEvent")
     */
    const ADMIN_PRODUCTS_CREATE_INITIALIZE = 'sfs_subscription.admin.products.create_initialize';

    /**
     * @Event("Softspring\AdminBundle\Event\GetResponseFormEvent")
     */
    const ADMIN_PRODUCTS_CREATE_FORM_VALID = 'sfs_subscription.admin.products.create_form_valid';

    /**
     * @Event("Softspring\AdminBundle\Event\GetResponseEntityEvent")
     */
    const ADMIN_PRODUCTS_CREATE_SUCCESS = 'sfs_subscription.admin.products.create_success';

    /**
     * @Event("Softspring\AdminBundle\Event\GetResponseFormEvent")
     */
    const ADMIN_PRODUCTS_CREATE_FORM_INVALID = 'sfs_subscription.admin.products.create_form_invalid';

    /**
     * @Event("Softspring\AdminBundle\Event\ViewEvent")
     */
    const ADMIN_PRODUCTS_CREATE_VIEW = 'sfs_subscription.admin.products.create_view';

    /**
     * @Event("Softspring\AdminBundle\Event\ViewEvent")
     */
    const ADMIN_PLANS_LIST_VIEW = 'sfs_subscription.admin.plans.list_view';

    /**
     * @Event("Softspring\AdminBundle\Event\GetResponseEntityEvent")
     */
    const ADMIN_PLANS_CREATE_INITIALIZE = 'sfs_subscription.admin.plans.create_initialize';

    /**
     * @Event("Softspring\AdminBundle\Event\GetResponseFormEvent")
     */
    const ADMIN_PLANS_CREATE_FORM_VALID = 'sfs_subscription.admin.plans.create_form_valid';

    /**
     * @Event("Softspring\AdminBundle\Event\GetResponseEntityEvent")
     */
    const ADMIN_PLANS_CREATE_SUCCESS = 'sfs_subscription.admin.plans.create_success';

    /**
     * @Event("Softspring\AdminBundle\Event\GetResponseFormEvent")
     */
    const ADMIN_PLANS_CREATE_FORM_INVALID = 'sfs_subscription.admin.plans.create_form_invalid';

    /**
     * @Event("Softspring\AdminBundle\Event\ViewEvent")
     */
    const ADMIN_PLANS_CREATE_VIEW = 'sfs_subscription.admin.plans.create_view';

    /**
     * @Event("Softspring\AdminBundle\Event\ViewEvent")
     */
    const ADMIN_SUBSCRIPTIONS_LIST_VIEW = 'sfs_subscription.admin.subscriptions.list_view';
}