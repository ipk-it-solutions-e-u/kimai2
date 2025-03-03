<?php

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Form;

use App\Configuration\SystemConfiguration;
use App\Form\Type\QuickEntryWeekType;
use App\Form\Type\WeekPickerType;
use App\Model\QuickEntryWeek;
use App\Validator\Constraints\QuickEntryModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Valid;

class QuickEntryForm extends AbstractType
{
    private $configuration;

    public function __construct(SystemConfiguration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $startDate = new \DateTime();
        if ($builder->getData() !== null) {
            /** @var QuickEntryWeek $data */
            $data = $builder->getData();
            $startDate = $data->getDate();
        }

        $builder->add('date', WeekPickerType::class, [
            'model_timezone' => $options['timezone'],
            'view_timezone' => $options['timezone'],
            'start_date' => $options['start_date'],
            'label' => false,
        ]);

        $builder->add('rows', CollectionType::class, [
            'label' => false,
            'entry_type' => QuickEntryWeekType::class,
            'entry_options' => [
                'label' => false,
                'duration_minutes' => $this->configuration->getTimesheetIncrementDuration(),
                'start_date' => $startDate,
                'empty_data' => function (FormInterface $form) use ($options) {
                    return clone $options['prototype_data'];
                },
                'prototype_data' => clone $options['prototype_data'],
            ],
            'prototype_data' => $options['prototype_data'],
            'allow_add' => true,
            'constraints' => [
                new Valid(),
                new All(['constraints' => [new QuickEntryModel()]])
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'timesheet_quick_edit',
            'data_class' => QuickEntryWeek::class,
            'timezone' => date_default_timezone_get(),
            'start_date' => new \DateTime(),
            'prototype_data' => null,
        ]);
    }
}
