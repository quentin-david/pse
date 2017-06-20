<?php
namespace FragosoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


/**
 * Formulaire de creation d'article
 */
class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('remise')
            ->add('articles', CollectionType::class, array(
				'entry_type'   => CommandeDetailType::class,
				'allow_add'    => true,
				'allow_delete' => true
			  ))
			  
            ->add('save', SubmitType::class); 
    }
	
	/**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FragosoBundle\Entity\Commande'
        ));
    }
}
