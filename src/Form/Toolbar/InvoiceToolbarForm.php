<?php

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Form\Toolbar;

use App\Form\Type\InvoiceTemplateType;
use App\Repository\Query\InvoiceQuery;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Defines the form used for filtering timesheet entries for invoices.
 */
class InvoiceToolbarForm extends AbstractToolbarForm
{

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addTemplateChoice($builder);
        $this->addUserChoice($builder);
        $this->addStartDateChoice($builder);
        $this->addEndDateChoice($builder);
        $this->addCustomerChoice($builder);
        $this->addProjectChoice($builder);
        $this->addActivityChoice($builder);
    }

    protected function addTemplateChoice(FormBuilderInterface $builder)
    {
        $builder->add('template', InvoiceTemplateType::class, [
            'required' => true
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InvoiceQuery::class,
            'csrf_protection' => false,
        ]);
    }
}