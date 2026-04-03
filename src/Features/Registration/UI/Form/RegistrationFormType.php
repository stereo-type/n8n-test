<?php

declare(strict_types=1);

namespace App\Features\Registration\UI\Form;

use App\Features\Registration\Application\DTO\RegistrationDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'registration.form.email',
                'attr' => ['autocomplete' => 'email'],
            ])
            ->add('firstName', TextType::class, [
                'label' => 'registration.form.first_name',
                'attr' => ['autocomplete' => 'given-name'],
            ])
            ->add('lastName', TextType::class, [
                'label' => 'registration.form.last_name',
                'attr' => ['autocomplete' => 'family-name'],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'registration.form.password',
                    'attr' => ['autocomplete' => 'new-password'],
                    'hash_property_path' => null,
                ],
                'second_options' => [
                    'label' => 'registration.form.confirm_password',
                    'attr' => ['autocomplete' => 'new-password'],
                ],
                'mapped' => false,
                'invalid_message' => 'registration.confirm_password.mismatch',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'registration.password.not_blank']),
                    new Assert\Length([
                        'min' => 8,
                        'minMessage' => 'registration.password.too_short',
                        'max' => 4096,
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/',
                        'message' => 'registration.password.weak',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RegistrationDTO::class,
            'translation_domain' => 'registration',
        ]);
    }
}
