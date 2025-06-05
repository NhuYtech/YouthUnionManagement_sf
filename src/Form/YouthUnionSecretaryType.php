<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class YouthUnionSecretaryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('unionAdmin', EntityType::class, [
                'class' => UnionAdmin::class,
                'choice_label' => 'id', // Thay bằng 'name' hoặc trường khác nếu UnionAdmin có
                'placeholder' => 'Chọn UnionAdmin',
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => YouthUnionSecretary::class,
        ]);
    }
}
