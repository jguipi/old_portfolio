<?php
// your-path-to-types/ContactType.php

namespace JC\ProfileBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactFRType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('attr' => array('placeholder' => 'Votre nom'),
                'constraints' => array(
                    new NotBlank(array("message" => "Veuillez entrer un nom")),
                )
            ))
            ->add('subject', TextType::class, array('attr' => array('placeholder' => 'Sujet'),
                'constraints' => array(
                    new NotBlank(array("message" => "Veuillez entrer un sujet")),
                )
            ))
            ->add('email', EmailType::class, array('attr' => array('placeholder' => 'Courriel'),
                'constraints' => array(
                    new NotBlank(array("message" => "Veuillez entrer courriel")),
                    new Email(array("message" => "Veuillez entrer un courriel valide")),
                )
            ))
            ->add('message', TextareaType::class, array('attr' => array('placeholder' => 'Sujet'),
                'constraints' => array(
                    new NotBlank(array("message" => "Veuillez entrer un sujet")),
                )
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'error_bubbling' => true
        ));
    }

    public function getName()
    {
        return 'contact_form';
    }
}