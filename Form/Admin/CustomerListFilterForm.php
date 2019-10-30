<?php

namespace Softspring\SubscriptionBundle\Form\Admin;

use Softspring\AdminBundle\Form\AdminEntityListFilterForm;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerListFilterForm extends AdminEntityListFilterForm implements CustomerListFilterFormInterface
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'translation_domain' => 'sfs_subscription',
            'label_format' => 'admin_customers.list.filter_form.%name%.label',
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
    }
}