<?php
namespace FragosoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


/**
 * Formulaire de creation d'article
 */
class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('categorie',  EntityType::class, array(
							'class' => 'FragosoBundle:Categorie',
							'choice_label' => 'libelle',
			))
            ->add('libelle', TextType::class)
            ->add('prix')
            ->add('tva')
            ->add('save', SubmitType::class); 
    }
	
	/**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FragosoBundle\Entity\Article'
        ));
    }
}