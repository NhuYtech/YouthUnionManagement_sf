<?php

namespace App\Form;

use App\Entity\InstructionDocument;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class InstructionDocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Tên văn bản',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Loại văn bản',
                'choices' => [
                    'Chỉ đạo' => 'Chỉ đạo',
                    'Hướng dẫn' => 'Hướng dẫn',
                    'Thông báo' => 'Thông báo',
                ],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('unitName', TextType::class, [
                'label' => 'Đơn vị ban hành',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('issueDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Ngày ban hành',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Nội dung',
                'attr' => ['class' => 'form-control', 'rows' => 5],
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Trạng thái',
                'choices' => [
                    'Chưa ban hành' => 'Chưa ban hành',
                    'Ban hành' => 'Ban hành',
                ],
                'placeholder' => 'Chọn trạng thái',
                'expanded' => false,
                'multiple' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('pdfFile', FileType::class, [
                'label' => 'Tải lên file PDF',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'form-control-file']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InstructionDocument::class,
        ]);
    }
}
