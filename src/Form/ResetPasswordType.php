<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Mật khẩu mới',
                    'attr' => ['class' => 'form-control'],
                ],
                'second_options' => [
                    'label' => 'Xác nhận mật khẩu',
                    'attr' => ['class' => 'form-control'],
                ],
                'invalid_message' => 'Mật khẩu xác nhận không khớp.',
                'constraints' => [
                    new NotBlank(['message' => 'Vui lòng nhập mật khẩu.']),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Mật khẩu phải có ít nhất {{ limit }} ký tự.',
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Đặt lại mật khẩu',
                'attr' => ['class' => 'btn btn-primary'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}