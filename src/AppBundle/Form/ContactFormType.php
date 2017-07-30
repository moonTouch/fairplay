<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class ContactFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'required' => true,
                'label' => 'contact.username.label',
                ] )
            ->add('email', EmailType::class, [
                'required' => true,
                'label' => 'contact.email.label',
                ] )
            ->add('message', TextareaType::class, [
                'label' => 'contact.message.label',
                'attr' => [
                    'rows'=> 10
                    ],
                'required' => true
            ])  
        ;
    }
    
   /**
     * Contraintes de validation du formulaire
     */
    public function getValidation()
    {
        $collectionConstraint = new Collection(array(
            'username' => new NotBlank(array('message' => 'contact.username.not_blank')),
            'email' => [
                new NotBlank(array('message' => 'contact.email.not_blank')),
                new Email(array('message' => 'contact.email.email'))
                ],
            'message' => new NotBlank(array('message' => 'contact.message.not_blank')),
        ));
       
        return $collectionConstraint;
    }

    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null,
            'constraints' => $this->getValidation()
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_contact';
    }



}