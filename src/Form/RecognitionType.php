<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Recognition;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RecognitionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'fullName',
                'label' => 'Tên đoàn viên',
                'attr' => ['class' => 'form-control'],
                'placeholder' => 'Chọn đoàn viên',
            ])
            ->add('decisionNumber', TextType::class, [
                'label' => 'Số QĐ',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('level', ChoiceType::class, [
                'label' => 'Cấp khen thưởng/kỷ luật',
                'choices' => [
                    'Trường' => 'Trường',
                    'Khoa' => 'Khoa',
                ],
                'placeholder' => 'Chọn cấp',
                'expanded' => false,
                'multiple' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('effectiveDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Ngày có hiệu lực',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('reason', TextareaType::class, [
                'label' => 'Nội dung',
                'attr' => ['class' => 'form-control', 'rows' => 2],
            ])
            ->add('pdfFile', FileType::class, [
                'label' => 'File đính kèm',
                'required' => false,
                'mapped' => false,
                'attr' => ['class' => 'form-control-file'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recognition::class,
        ]);
    }
}
