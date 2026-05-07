<?php

namespace Softspring\SubscriptionBundle\Form\Admin;

use Softspring\Component\CrudlController\Form\EntityListFilterForm;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductListFilterForm extends EntityListFilterForm implements ProductListFilterFormInterface
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'translation_domain' => 'sfs_subscription',
            'label_format' => 'admin_products.list.filter_form.%name%.label',
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
    }
}
