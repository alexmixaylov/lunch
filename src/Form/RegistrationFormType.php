<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {// кошмар. потом посмотришь как оно в ларавеле
        $builder
//            ->add('company', CollectionType::class, [
//                'entry_type'    => CompanyType::class,
//                'entry_options' => ['label' => false, 'allow_add' => true, 'prototype_name' => '__company__',],
//
//            ])
            ->add('name', null, ['label' => 'Имя'])
            ->add('phone', null, ['label' => 'Телефон'])
            ->add('type', ChoiceType::class, [
                'choices'  => [
                    'Выберите тип аккаунта'  => false,
                    'Частное лицо'           => 'private',
                    'Работник компании'      => 'employee',
                    'Компания' => 'corporate'
                ],
                'required' => true,
                'label'    => 'Тип аккаунта'
            ])
            ->add('email')
            ->add('agreeTerms', CheckboxType::class, [
                'mapped'      => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
                'label'       => 'Я согласен с условиями',
                'row_attr'    => ['class' => 'checkbox']
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped'      => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min'        => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max'        => 4096,
                    ]),
                ],
                'label'       => 'Пароль'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }
}
