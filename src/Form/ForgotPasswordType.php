<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ForgotPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control form-control-user',
                    'placeholder' => 'Nhập email của bạn',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Vui lòng nhập email.']),
                    new Email(['message' => 'Địa chỉ email không hợp lệ.']),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Gửi yêu cầu',
                'attr' => ['class' => 'btn btn-primary btn-user btn-block'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Account::class,
        ]);
    }
}
