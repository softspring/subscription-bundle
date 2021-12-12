<?php

namespace Softspring\SubscriptionBundle\Form\Admin;

use Softspring\CrudlBundle\Form\EntityListFilterForm;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductListFilterForm extends EntityListFilterForm implements ProductListFilterFormInterface
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'translation_domain' => 'sfs_subscription',
            'label_format' => 'admin_products.list.filter_form.%name%.label',
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
    }
}