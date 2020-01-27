<?php

namespace Softspring\SubscriptionBundle\Form\Admin;

use Jhg\DoctrinePaginationBundle\Request\RequestParam;
use Softspring\CrudlBundle\Form\EntityListFilterForm;
use Softspring\SubscriptionBundle\Model\SubscriptionInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubscriptionListFilterForm extends EntityListFilterForm implements SubscriptionListFilterFormInterface
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'translation_domain' => 'sfs_subscription',
            'label_format' => 'admin_subscriptions.list.filter_form.%name%.label',
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('status', ChoiceType::class, [
            'required' => false,
            'choices' => [
                'trialing' => SubscriptionInterface::STATUS_TRIALING,
                'active' => SubscriptionInterface::STATUS_ACTIVE,
                'unpaid' => SubscriptionInterface::STATUS_UNPAID,
                'canceled' => SubscriptionInterface::STATUS_CANCELED,
                'suspended' => SubscriptionInterface::STATUS_SUSPENDED,
                'expired' => SubscriptionInterface::STATUS_EXPIRED,
            ]
        ]);

        $builder->add('submit', SubmitType::class, [
            'label' => 'admin_subscriptions.list.filter_form.actions.search'
        ]);
    }

    public function getOrder(Request $request): array
    {
        if (class_exists(RequestParam::class)) {
            $order = RequestParam::getQueryValidParam($request, self::getOrderFieldParamName(), 'startDate', ['id','startDate']);
            $sort = RequestParam::getQueryValidParam($request, self::getOrderDirectionParamName(), 'desc', ['asc','desc']);

            return [$order => $sort];
        }

        return [$request->query->get(self::getOrderFieldParamName(), 'id') => $request->query->get(self::getOrderDirectionParamName(), 'asc')];
    }
}