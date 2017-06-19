<?php

namespace FragosoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FragosoBundle\Entity\Commande;
use FragosoBundle\Form\Type\CommandeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class CommandeController extends Controller
{
	/**
    * @Security("has_role('ROLE_ADMIN')")
    */
    public function indexAction($commande_etat=null)
    {
        //Entity Manager
        $em = $this->getDoctrine()->getManager();
        
        //Liste des toutes les categories et articles
        if ($commande_etat === null){
            $liste_commandes = $em->getRepository('FragosoBundle:Commande')->findAll();
        }elseif($commande_etat == 'en-cours'){
            $liste_commandes = $em->getRepository('FragosoBundle:Commande')->findBy(array('etat' => 'encours'));
        }
        
        return $this->render('FragosoBundle:Commande:index.html.twig', array(
                                    'liste_commandes' => $liste_commandes,
                                    'commande_etat' => $commande_etat,
                            ));
    }
    
    /**
     * Ajout ou edition d'une commande
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function editerCommandeAction(Request $request, $commande_num=null)
    {
        //Entity Manager
        $em = $this->getDoctrine()->getManager();
        
        //Creation d'un objet de type Categorie
        if($commande_num === null){
            $commande = new Commande;
        }else{
            $commande = $em->getRepository('FragosoBundle:Commande')->find($commande_num);
        }
        
        // Creation du formulaire générique de création d'une commande
		$formulaire = $this->createForm(CommandeType::class, $commande);
        
        //Enregistrement en base du formulaire
        if($request->isMethod('POST')){
            $formulaire->handleRequest($request);
            if($formulaire->isValid()){
                //$em->persist($commande);
                foreach ($formulaire->get('articles')->getData() as $article){
                    $article->setPrixApplique($article->getArticle()->getPrix());
                    $article->setTvaAppliquee($article->getArticle()->getTva());
                    $article->setCommande($commande);
                }
                $em->persist($commande);
                $em->flush();
                
				// Redirection vers la page catalogue
                return $this->redirectToRoute('commande_home');
            }
        }        
        
        return $this->render('FragosoBundle:Commande:commande_edition.html.twig', array(
                                    'formulaire' => $formulaire->createView(),
                                    'commande' => $commande
                            ));
    }
    
}
