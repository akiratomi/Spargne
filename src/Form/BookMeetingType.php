<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Entity\AdvisorSchedule;
use App\Entity\MeetingTopic;
use App\Entity\User;

class BookMeetingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateTimeType::class)
            ->add('duration', TimeType::class)
            ->add('topic', EntityType::class, array(
                    'class'=>MeetingTopic::class,
                    'choice_label'=>'libelle'
                ))
            ->add('advisor', EntityType::class, array(
                'class'=>User::class,
                'choice_label'=>'id'
            ))
            ->add('customer', EntityType::class, array(
                'class'=>User::class,
                'choice_label'=>'id'
            ))
            ->add('envoyer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AdvisorSchedule::class,
        ]);
    }
}
