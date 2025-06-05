<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\EventRollCall;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;


class EventRollCallType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('event', EntityType::class, [
                'class' => Event::class,
                'choice_label' => 'eventName',
                'label' => 'Tên hoạt động',
                'attr' => ['class' => 'form-control']
            ])
            ->add('startTime', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Thời gian bắt đầu điểm danh',
                'attr' => ['class' => 'form-control']
            ])
            ->add('endTime', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Thời gian kết thúc điểm danh',
                'attr' => ['class' => 'form-control']
            ])
            ->add('rollCallLink', UrlType::class, [
                'label' => 'Link điểm danh',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EventRollCall::class,
        ]);
    }
}
