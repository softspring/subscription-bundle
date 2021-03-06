<?php

namespace Softspring\SubscriptionBundle\Form\Admin;

use Softspring\PlatformBundle\Model\PlatformObjectInterface;
use Softspring\SubscriptionBundle\Model\ProductInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductUpdateForm extends AbstractType implements ProductUpdateFormInterface
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductInterface::class,
            'translation_domain' => 'sfs_subscription',
            'label_format' => 'admin_products.create.form.%name%.label',
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
        $builder->add('type');

        if ($this->productManager->getEntityClassReflection()->implementsInterface(PlatformObjectInterface::class)) {
            $builder->add('platformId');
        }
    }
}