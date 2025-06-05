<?php

namespace App\Form;

use App\Entity\Fee;
use App\Entity\User;
use App\Entity\YouthUnionSecretary;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;



class FeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('unitName', TextType::class, [
                'label' => 'Tên đơn vị',
                'data' => $options['data']->getUser()?->getUnitName(),
                'attr' => [
                    'class' => 'form-control',
                    'readonly' => true,
                    'placeholder' => 'Tên đơn vị đã được chọn',
                ],
                'mapped' => false,
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'fullName',
                'label' => 'Họ tên người đóng',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('memberCount', IntegerType::class, [
                'label' => 'Số lượng đoàn viên',
                'required' => true,
            ])
            ->add('amount', TextType::class, [
                'label' => 'Tổng tiền',
                'required' => false,
                'attr' => ['readonly' => true],
            ])
            ->add('paymentMethod', ChoiceType::class, [
                'label' => 'Phương thức thanh toán',
                'choices' => [
                    'Tiền mặt' => 'Tiền mặt',
                    'Chuyển khoản' => 'Chuyển khoản',
                ],
                'expanded' => false,
                'multiple' => false,
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'paymentMethod',
                ],
                'choice_attr' => [
                    'Tiền mặt' => ['class' => 'form-control'],
                    'Chuyển khoản' => ['class' => 'form-control'],
                ],
            ])
            ->add('paymentDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Ngày nộp',
                'required' => true,
                'input' => 'datetime',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('note', TextareaType::class, [
                'label' => 'Ghi chú',
                'attr' => ['class' => 'form-control', 'rows' => 3, 'placeholder' => 'Nhập ghi chú (nếu có)'],
                'required' => false,
            ]);
        if ($options['is_admin']) {
            $builder->add('status', ChoiceType::class, [
                'label' => 'Trạng thái',
                'choices' => [
                    'Đã duyệt' => 'approved',
                    'Không duyệt' => 'rejected',
                ],
                'expanded' => true,
                'multiple' => false,
                'required' => true,
                'data' => $options['data']->getStatus() ?? 'submitted',
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Fee::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'fee_form',
            'allow_extra_fields' => true,
            'is_admin' => false,
        ]);
    }
}
