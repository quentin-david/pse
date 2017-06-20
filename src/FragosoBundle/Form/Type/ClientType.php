<?php
namespace FragosoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


/**
 * Formulaire de creation de topic
 */
class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder 
            ->add('civilite', ChoiceType::class, array(
                        'choices' => array(
                            'Mr.' => 'mr',
                            'Mme.' => 'mme',
                            'Mlle.' => 'mlle',
                            'N.C' => 'nc',
                        ))
              )
            ->add('nom')
            ->add('prenom')
            ->add('adresse')
            ->add('ville')
            ->add('codePostal')
            ->add('numPortable')
            ->add('remise')
            ->add('save', SubmitType::class); 
    }
	
	/**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FragosoBundle\Entity\Client'
        ));
    }
}
