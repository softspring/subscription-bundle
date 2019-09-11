<?php

namespace Softspring\SubscriptionBundle\Form\Admin;

use Softspring\SubscriptionBundle\Model\PlanInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlanUpdateForm extends AbstractType implements PlanUpdateFormInterface
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PlanInterface::class,
            'translation_domain' => 'sfs_subscription',
            'label_format' => 'admin_plans.create.form.%name%.label',
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
        $builder->add('product');
        $builder->add('platformId');
        $builder->add('currency');
        $builder->add('amount');
        $builder->add('interval');
    }
}