<?php

namespace App\Form;

use App\Entity\TrainingEvaluation;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class TrainingEvaluationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user', EntityType::class, [
                'class' => User::class,
                'label' => 'Đoàn viên',
                'choice_label' => 'fullName',
                'placeholder' => 'Chọn đoàn viên',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('strengths', TextareaType::class, [
                'label' => 'Ưu điểm',
                'attr' => ['class' => 'form-control', 'rows' => 2],
                'required' => false,
            ])
            ->add('weaknesses', TextareaType::class, [
                'label' => 'Khuyết điểm',
                'attr' => ['class' => 'form-control', 'rows' => 2],
                'required' => false,
            ])
            ->add('reward', TextareaType::class, [
                'label' => 'Khen thưởng',
                'attr' => ['class' => 'form-control', 'rows' => 2],
                'required' => false,
            ])
            ->add('discipline', TextareaType::class, [
                'label' => 'Kỷ luật',
                'attr' => ['class' => 'form-control', 'rows' => 2],
                'required' => false,
            ])
            ->add('finalEvaluate', ChoiceType::class, [
                'choices' => [
                    'Chưa đánh giá' => 'not_evaluated',
                    'Hoàn thành xuất sắc nhiệm vụ' => 'Hoàn thành xuất sắc nhiệm vụ',
                    'Hoàn thành tốt nhiệm vụ' => 'Hoàn thành tốt nhiệm vụ',
                    'Hoàn thành nhiệm vụ' => 'Hoàn thành nhiệm vụ',
                    'Không hoàn thành nhiệm vụ' => 'Không hoàn thành nhiệm vụ',
                ],
                'label' => 'Đánh giá cuối cùng',
                'placeholder' => 'Chọn đánh giá',
                'expanded' => false,
                'multiple' => false,
                'attr' => ['class' => 'form-control'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TrainingEvaluation::class,
        ]);
    }
}
