<?php

namespace App\Form;

use App\Entity\Account;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class AccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class, [
                'label' => 'Họ và tên',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Họ và tên không được để trống']),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Họ và tên phải có ít nhất 3 ký tự',
                        'max' => 255,
                        'maxMessage' => 'Họ và tên không được quá 255 ký tự'
                    ])
                ],
                'attr' => ['placeholder' => 'Nhập họ và tên']
            ])
            ->add('email', EmailType::class, [
                'label' => 'Nhập email',
                'required' => false,
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[\w\.-]+@[\w\.-]+\.\w{2,4}$/',
                        'message' => 'Email không hợp lệ',
                        'groups' => ['email_validation'],
                    ])
                ],
                'attr' => ['placeholder' => 'Nhập email']
            ])
            ->add('phoneNumber', TelType::class, [
                'label' => 'Vui lòng điền số điện thoại',
                'required' => false,
                'constraints' => [
                    new Regex([
                        'pattern' => '/^(0[3|5|7|8|9])+([0-9]{8})$/',
                        'message' => 'Số điện thoại không hợp lệ',
                        'groups' => ['phone_validation'],
                    ])
                ],
                'attr' => ['placeholder' => 'Nhập số điện thoại']
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => true,
                'first_options' => [
                    'label' => 'Mật khẩu',
                    'constraints' => [
                        new NotBlank(['message' => 'Mật khẩu không được để trống']),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Mật khẩu phải có ít nhất 6 ký tự'
                        ]),
                        new Regex([
                            'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/',
                            'message' => 'Mật khẩu phải có ít nhất 1 chữ thường, 1 chữ hoa, 1 số và 1 ký tự đặc biệt.'
                        ])
                    ],
                    'attr' => ['placeholder' => 'Nhập mật khẩu']
                ],
                'second_options' => [
                    'label' => 'Xác nhận mật khẩu',
                    'attr' => ['placeholder' => 'Nhập lại mật khẩu']
                ],
                'invalid_message' => 'Mật khẩu xác nhận không khớp!',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Đăng ký',
                'attr' => ['class' => 'btn btn-primary btn-user btn-block']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Account::class,
        ]);
    }
}
