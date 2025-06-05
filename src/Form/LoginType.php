<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('identifier', TextType::class, [
                'label' => 'Email hoặc Số điện thoại',
                'attr' => ['placeholder' => 'Nhập email hoặc số điện thoại'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Vui lòng nhập email hoặc số điện thoại.',
                    ]),
                    new Callback([
                        'callback' => function ($value, ExecutionContextInterface $context) {
                            $emailPattern = '/^[^@\s]+@[^@\s]+\.[^@\s]+$/';
                            $phonePattern = '/^(0[1-9][0-9]{8,9})$/';
                            if (!preg_match($emailPattern, $value) && !preg_match($phonePattern, $value)) {
                                $context->buildViolation('Vui lòng nhập email hoặc số điện thoại hợp lệ.')
                                    ->addViolation();
                            }
                        },
                    ]),
                ],
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mật khẩu',
                'attr' => ['placeholder' => 'Nhập mật khẩu'],
                'constraints' => [
                    new NotBlank(['message' => 'Vui lòng nhập mật khẩu.']),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
            'csrf_field_name' => '_csrf_token',
            'csrf_token_id' => 'authenticate',
        ]);
    }
}
