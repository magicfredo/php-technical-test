<?php

namespace App\Form;

use App\Entity\Run;
use App\Entity\User;
use Doctrine\DBAL\Types\StringType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

class RunType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder
            ->add('type', ChoiceType::class, [
                'label' => 'Type',
                'choices'  => [
                    'Training' => Run::TYPE_TRAINING,
                    'Leisure' => Run::TYPE_LEISURE,
                    'Running' => Run::TYPE_RUNNING,
                ]
            ])
            ->add('startedAt', DateTimeType::class, [
                'label' => 'Start datetime',
            ])
            ->add('duration', TimeType::class, [
                'input'  => 'timestamp',
                'with_seconds' => true,
                'placeholder' => [
                    'hour' => 'Hour', 'minute' => 'Minute', 'second' => 'Second',
                ]
            ])
            ->add('distance', NumberType::class, [
                'label' => 'Distance (km)',
            ])
            ->add('comment', TextType::class, [
                'label' => 'Comment',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Register',
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(
        OptionsResolver $resolver
    ): void {
        $resolver->setDefaults([
            'data_class' => Run::class,
        ]);
    }
}