<?php
namespace FragosoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
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
			->add('quantite', IntegerType::class, array(
							'attr'=> array('class'=>'col-lg-3'),
							'label' => false,
			))
			->add('article',  EntityType::class, array(
							'class' => 'FragosoBundle:Article',
							'choice_label' => 'libelle',
							'attr'=> array('class'=>'col-lg-4'),
							'label' => false,
			));
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
