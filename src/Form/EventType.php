<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Validator\Constraints\File;



class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('eventName', TextType::class, [
                'label' => 'Tên hoạt động',
                'attr' => ['class' => 'form-control']
            ])
            ->add('eventType', ChoiceType::class, [
                'label' => 'Loại hoạt động',
                'choices' => [
                    'Xã hội' => 'Xã hội',
                    'Tình nguyện' => 'Tình nguyện',
                    'Văn nghệ' => 'Văn nghệ',
                    'Thể thao' => 'Thể thao',
                    'Khác' => 'Khác'
                ],
                'placeholder' => 'Chọn loại hoạt động',
                'attr' => ['class' => 'form-control']
            ])
            ->add('organizationLevel', ChoiceType::class, [
                'label' => 'Cấp tổ chức',
                'choices' => [
                    'Trường' => 'Trường',
                    'Khoa' => 'Khoa',
                    'Lớp' => 'Chi đoàn',
                ],
                'placeholder' => 'Chọn cấp tổ chức',
                'attr' => ['class' => 'form-control']
            ])
            ->add('startDate', DateTimeType::class, [
                'label' => 'Ngày bắt đầu',
                'widget' => 'single_text',
                'html5' => true,
                'attr' => ['class' => 'form-control']
            ])
            ->add('endDate', DateTimeType::class, [
                'label' => 'Ngày kết thúc',
                'widget' => 'single_text',
                'html5' => true,
                'attr' => ['class' => 'form-control']
            ])
            ->add('location', TextType::class, [
                'label' => 'Địa điểm',
                'attr' => ['class' => 'form-control']
            ])
            ->add('image', FileType::class, [
                'label' => 'Poster/Banner',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'form-control-file']
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Mô tả',
                'attr' => ['class' => 'form-control', 'rows' => 3]
            ])
            ->add('rollCallTime', DateTimeType::class, [
                'label' => 'Thời gian điểm danh',
                'widget' => 'single_text',
                'html5' => true,
                'attr' => ['class' => 'form-control']
            ])
            ->add('participantLimit', IntegerType::class, [
                'label' => 'Giới hạn người tham gia',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('registerLink', UrlType::class, [
                'label' => 'Đăng ký tham gia',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('pdfFile', FileType::class, [
                'label' => 'Tải lên file PDF',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'form-control-file']
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Trạng thái',
                'choices' => [
                    'Chưa diễn ra' => 'Chưa diễn ra',
                    'Đang diễn ra' => 'Đang diễn ra',
                    'Đã kết thúc' => 'Đã kết thúc',
                ],
                'placeholder' => 'Chọn trạng thái',
                'attr' => ['class' => 'form-control']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
