<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\Event;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UnionAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('unitName')
            ->add('role')
            ->add('fullName')
            ->add('dateOfBirth', null, [
                'widget' => 'single_text',
            ])
            ->add('gender')
            ->add('ethnicity')
            ->add('religion')
            ->add('citizenId')
            ->add('issueDate', null, [
                'widget' => 'single_text',
            ])
            ->add('placeOfIssue')
            ->add('hometownAddress')
            ->add('permanentAddress')
            ->add('regisNumber')
            ->add('joinPlace')
            ->add('cardPlace')
            ->add('joinDate', null, [
                'widget' => 'single_text',
            ])
            ->add('unionRole')
            ->add('association')
            ->add('honorMember')
            ->add('joinPartyDate', null, [
                'widget' => 'single_text',
            ])
            ->add('partyPosition')
            ->add('salaryStatus')
            ->add('unionBookNumber')
            ->add('eduLevel')
            ->add('proLevel')
            ->add('polTheory')
            ->add('itLevel')
            ->add('langLevel')
            ->add('job')
            ->add('phoneNumber')
            ->add('email')
            ->add('account', EntityType::class, [
                'class' => Account::class,
                'choice_label' => 'id',
            ])
            ->add('events', EntityType::class, [
                'class' => Event::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
