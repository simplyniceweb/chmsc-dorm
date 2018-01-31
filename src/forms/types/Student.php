<?php

namespace forms\types;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class Student extends AbstractType
{
    var $em;
    
    public function __construct($app)
    {
        $this->em = $app['orm.em'];
    }

	public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // $id = $options['data']->getId();
        // $readonly = $id ? false : true;

        $builder
            ->add('student_id', TextType::class, [
                'required' => true,
                'attr' => [
                    'autofocus' => true,
                    'class' => 'form-control',
                ]
            ])
            ->add('first_name', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('middle_name', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('last_name', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('phone', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('email', EmailType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('address', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save',
                'attr' => [
                    'class' => 'btn btn-success btn-outline-success pull-right',
                ]
            ])
            ->getForm();
	}

    public function getName()
    {
        return 'student';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'models\Student',
        ));
    }
}