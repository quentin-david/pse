<?php

namespace FragosoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FragosoBundle\Entity\Commande;
use FragosoBundle\Entity\Reglement;
use FragosoBundle\Form\Type\CommandeType;
use FragosoBundle\Form\Type\ReglementType;
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
		$liste_commandes_encours = $em->getRepository('FragosoBundle:Commande')->findBy(array('etat' => 'encours'));

		$liste_commandes_terminees = array(); // Liste des commandes terminées et pas encore payées
		$liste_commandes_archivees = array(); // Liste des commandes terminées ET payées
		foreach($em->getRepository('FragosoBundle:Commande')->findBy(array('etat' => 'terminee')) as $commande)
		{
			if ($commande->getResteAPayer() > 0){
				array_push($liste_commandes_terminees, $commande);
			}else{
				array_push($liste_commandes_archivees, $commande);
			}
		}
		
		$liste_commandes_archivees = $em->getRepository('FragosoBundle:Commande')->findAll();
        
        return $this->render('FragosoBundle:Commande:index.html.twig', array(
                                    'liste_commandes_encours' => $liste_commandes_encours,
									'liste_commandes_terminees' => $liste_commandes_terminees,
									'liste_commandes_archivees' => $liste_commandes_archivees,
                                    'commande_etat' => $commande_etat,
                            ));
    }
	
	/**
    * @Security("has_role('ROLE_ADMIN')")
    */
    public function listerCommandeAction($commande_etat=null)
    {
        //Entity Manager
        $em = $this->getDoctrine()->getManager();
        
        //Liste des toutes les categories et articles
        if($commande_etat == 'en-cours'){
            $liste_commandes = $em->getRepository('FragosoBundle:Commande')->findBy(array('etat' => 'encours'));
        }elseif($commande_etat == 'terminees'){
			//$liste_commandes = $em->getRepository('FragosoBundle:Commande')->findBy(array('etat' => 'terminees'));
			// Liste des commandes terminées et pas encore payées
			$liste_commandes = array();
			foreach($em->getRepository('FragosoBundle:Commande')->findBy(array('etat' => 'terminee')) as $commande)
			{
				if ($commande->getResteAPayer() > 0){
					array_push($liste_commandes, $commande);
				}
			}
		}elseif($commande_etat == 'archivees'){
			//$liste_commandes = $em->getRepository('FragosoBundle:Commande')->findAll();
			$liste_commandes = array();
			foreach($em->getRepository('FragosoBundle:Commande')->findBy(array('etat' => 'terminee')) as $commande)
			{
				if ($commande->getResteAPayer() == 0){
					array_push($liste_commandes, $commande);
				}
			}
		}
        
        return $this->render('FragosoBundle:Commande:liste_commande.html.twig', array(
                                    'liste_commandes' => $liste_commandes,
                                    'commande_etat' => $commande_etat,
                            ));
    }
    
    /**
     * Ajout ou edition d'une commande
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function editerCommandeAction(Request $request, $client_num=null, $commande_num=null)
    {
        //Entity Manager
        $em = $this->getDoctrine()->getManager();
        
        $client = $em->getRepository('FragosoBundle:Client')->find($client_num);
        
        //Creation d'un objet de type Categorie
        if($commande_num === null){
            $commande = new Commande;
        }else{
            $commande = $em->getRepository('FragosoBundle:Commande')->find($commande_num);
        }
        
        // Creation du formulaire générique de création d'une commande
		$formulaire = $this->createForm(CommandeType::class, $commande);
		// On met le champ remise à la valeur par défaut du client
        $formulaire->getData()->setRemise($client->getRemise());
        $formulaire->setData($formulaire->getData());
        
        
        //Enregistrement en base du formulaire
        if($request->isMethod('POST')){
            $formulaire->handleRequest($request);
            if($formulaire->isValid()){
				$commande->setClient($client);
                $commande->setEtat('encours');
                foreach ($formulaire->get('articles')->getData() as $article){
                    $article->setPrixApplique($article->getArticle()->getPrix());
                    $article->setTvaAppliquee($article->getArticle()->getTva());
                    $article->setCommande($commande);
                }
                $em->persist($commande);
                $em->flush();
                
				// Redirection vers la page catalogue
                //return $this->redirectToRoute('commande_home');
				return $this->redirectToRoute('afficher_client', array('client_num' => $client_num));
            }
        }        
        
        return $this->render('FragosoBundle:Commande:commande_edition.html.twig', array(
                                    'formulaire' => $formulaire->createView(),
                                    'client' => $client,
                                    'commande' => $commande
                            ));
    }
    
    /**
	 * Suppression d'une commande (avec demande de confirmation)
	 */
	public function supprimerCommandeAction(Request $request,$commande_num)
	{
		//Entity Manager
        $em = $this->getDoctrine()->getManager();
		$commande_a_supprimer = $em->getRepository('FragosoBundle:Commande')->find($commande_num);
        
        // Verification que la commande existe bien
		if($request->isMethod('POST')){
			if($commande_a_supprimer != ''){
				$em->remove($commande_a_supprimer);
				$em->flush();
                // flash message
                $this->get('session')->getFlashBag()->add('info','Commande de '.$commande_a_supprimer->getClient()->getNomComplet().' supprimée avec succès');
			}
			return $this->redirectToRoute('commande_home');
		}
        
        return $this->render('FragosoBundle:Commande:commande_suppression.html.twig',array(
                                    'commande' => $commande_a_supprimer
                            ));
	}
    
    
    /**
     * Ajout d'un paiement à une commande
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function payerCommandeAction(Request $request, $client_num, $commande_num)
    {
        //Entity Manager
        $em = $this->getDoctrine()->getManager();
        
        $client = $em->getRepository('FragosoBundle:Client')->find($client_num);
        $commande = $em->getRepository('FragosoBundle:Commande')->find($commande_num);
        
        //Creation d'un objet de type Reglement
        $reglement = new Reglement;
        
        // test 
        $reglement->setCommande($commande);
        
        // Creation du formulaire générique de création d'un reglement
		$formulaire = $this->createForm(ReglementType::class, $reglement);
        
        //Enregistrement en base du formulaire
        if($request->isMethod('POST')){
            $formulaire->handleRequest($request);
            if($formulaire->isValid()){
                //$reglement->setCommande($commande);
                if ($formulaire["montant"]->getData() > $commande->getResteAPayer()){	
					$this->get('session')->getFlashBag()->add('warning','Paiement trop elevé'.$formulaire["montant"]->getData());
                }else{
					$this->get('session')->getFlashBag()->add('info','Paiement de '.$formulaire["montant"]->getData().' euros accepté');
					if ($formulaire["montant"]->getData() == $commande->getResteAPayer()){
						$commande->setEtat('terminee');
						$this->get('session')->getFlashBag()->add('info','La commande est entièrement payée');
					}
					$em->persist($reglement);
					$em->persist($commande);
					$em->flush();
				}
                
				// Redirection vers la page catalogue
                return $this->redirectToRoute('afficher_client', array('client_num' => $client->getId()));
            }
        }        
        
        return $this->render('FragosoBundle:Commande:reglement_edition.html.twig', array(
                                    'formulaire' => $formulaire->createView(),
                                    'client' => $client,
                                    'commande' => $commande
                            ));
    }
    
    /**
     * 
     */
    public function terminerCommandeAction(Request $request, $commande_num)
    {
		//Entity Manager
        $em = $this->getDoctrine()->getManager();
        $commande = $em->getRepository('FragosoBundle:Commande')->find($commande_num);
        if($commande){
			$commande->setEtat('terminee');
			$em->persist($commande);
			$em->flush();
			$this->get('session')->getFlashBag()->add('info','La commande de '.$commande->getClient()->getNomComplet().' est maintenant terminée');
		}else{
			$this->get('session')->getFlashBag()->add('info','Erreur : commande introuvable');
		}
		return $this->redirectToRoute('commande_home');
	}
}
