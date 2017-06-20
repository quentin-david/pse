<?php
namespace FragosoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


/**
 * Formulaire de creation du detail d'une commande
 */
class CommandeDetailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('article',  EntityType::class, array(
							'class' => 'FragosoBundle:Article',
							'choice_label' => 'libelle',
			))
            //->add('quantite', TextType::class, array('data' => '1'));
            ->add('quantite', TextType::class);
            //->add('tva_appliquee')
            //->add('prix_applique'); 
    }
	
	/**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FragosoBundle\Entity\CommandeDetail'
        ));
    }
}
