<?php

namespace App\Form;

use App\Entity\User;
use App\EventSubscriber\UserFormSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    /**
     * @var UserFormSubscriber
     */
    private $userPasswordEncoderSubscriber;

    public function __construct(UserFormSubscriber $userPasswordEncoderSubscriber)
    {
        $this->userPasswordEncoderSubscriber = $userPasswordEncoderSubscriber;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, ['label' => 'Prénom'])
            ->add('lastname', TextType::class, ['label' => 'Nom'])
            ->add('email', EmailType::class, ['label' => 'Email'])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'La confirmation du mot de passe ne correspond pas',
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Mot de passe (confirmation)'],
                'mapped' => false,
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 8, 'minMessage' => 'user.plainPassword.length.min'])
                ]
            ])
        ;

        $builder->addEventSubscriber($this->userPasswordEncoderSubscriber);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
