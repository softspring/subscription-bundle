<?php

declare(strict_types=1);

namespace Softspring\SubscriptionBundle\Form\Admin;

use Softspring\PlatformBundle\Model\PlatformObjectInterface;
use Softspring\SubscriptionBundle\Manager\PlanManagerInterface;
use Softspring\SubscriptionBundle\Model\PlanInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlanCreateForm extends AbstractType implements PlanCreateFormInterface
{
    public function __construct(protected PlanManagerInterface $planManager)
    {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PlanInterface::class,
            'translation_domain' => 'sfs_subscription',
            'label_format' => 'admin_plans.create.form.%name%.label',
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name');
        //        $builder->add('product');

        if ($this->planManager->getEntityClassReflection()->implementsInterface(PlatformObjectInterface::class)) {
            $builder->add('platformId');
        }

        $builder->add('currency');
        $builder->add('amount');
        $builder->add('interval');
    }
}
