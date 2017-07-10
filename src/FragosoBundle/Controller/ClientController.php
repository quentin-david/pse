<?php

namespace FragosoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FragosoBundle\Entity\Client;
use FragosoBundle\Form\Type\ClientType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ClientController extends Controller
{
	/**
    * @Security("has_role('ROLE_ADMIN')")
    */
    public function indexAction($client_etat=null)
    {
		$em = $this->getDoctrine()->getManager();
		$liste_clients = $em->getRepository('FragosoBundle:Client')->findAll();
		
        return $this->render('FragosoBundle:Client:index.html.twig', array(
									'liste_clients' => $liste_clients,
							));
    }
   
    
    /*
     * Ajout ou edition d'un client
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function editerClientAction(Request $request, $client_num=null)
    {
        //Entity Manager
        $em = $this->getDoctrine()->getManager();
        
        //Creation d'un objet de type Categorie
        if($client_num === null){
            $client = new Client;
        }else{
            $client = $em->getRepository('FragosoBundle:Client')->find($client_num);
            // Cas ou le client n'existe pas
            if(!$client){
				$this->get('session')->getFlashBag()->add('warning','Client inconnu... (pas touche aux URL !)');
				return $this->redirectToRoute('creer_client');
			}
        }
        
        // Creation du formulaire générique de création d'un client
		$formulaire = $this->createForm(ClientType::class, $client);
        
        if (empty($client->getRemise())) {
			$formulaire->getData()->setRemise('0');
		}
        $formulaire->setData($formulaire->getData());
        
        //Enregistrement en base du formulaire
        if($request->isMethod('POST')){
            $formulaire->handleRequest($request);
            if($formulaire->isValid()){
                $em->persist($client);
                $em->flush();
                
				return $this->redirectToRoute('afficher_client', array('client_num' => $client->getId()));
            }
        }        
        
        return $this->render('FragosoBundle:Client:client_edition.html.twig', array(
                                    'formulaire' => $formulaire->createView(),
                                    'client' => $client
                            ));
    }
    
    
    /*
     * Suppression d'un client (avec demande de confirmation)
     * @Security("has_role('ROLE_ADMIN')")
	 */
	public function supprimerClientAction(Request $request,$client_num)
	{
		//Entity Manager
        $em = $this->getDoctrine()->getManager();
		$client_a_supprimer = $em->getRepository('FragosoBundle:Client')->find($client_num);
        
        // Verification que le client existe bien
		if($request->isMethod('POST')){
            // TODO : que faire des services et produits liés à cette categorie !
			if($client_a_supprimer != ''){
				$em->remove($client_a_supprimer);
				$em->flush();
                // flash message
                $this->get('session')->getFlashBag()->add('info','Client '.$client_a_supprimer->getPrenom().' '.$client_a_supprimer->getNom().' supprimé avec succès');
			}
			return $this->redirectToRoute('client_home');
		}
        
        return $this->render('FragosoBundle:Client:client_suppression.html.twig',array(
                                    'client' => $client_a_supprimer
                            ));
	}
	
	/**
	 * Afficher les details d'un client (commandes, balance)
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function afficherClientAction(Request $request, $client_num)
	{
		//Entity Manager
        $em = $this->getDoctrine()->getManager();
		$client = $em->getRepository('FragosoBundle:Client')->find($client_num);
		
		//Liste des commandes en cours
		$liste_commandes_encours = $em->getRepository('FragosoBundle:Commande')->findBy(array('etat' => 'encours', 'client' => $client),array('dateCommande' => 'ASC'));
		
		$liste_commandes_terminees = array(); // Liste des commandes terminées et pas encore payées
		$liste_commandes_archivees = array(); // Liste des commandes terminées ET payées
		foreach($em->getRepository('FragosoBundle:Commande')->findBy(array('etat' => 'terminee', 'client' => $client)) as $commande)
		{
			if ($commande->getResteAPayer() > 0){
				array_push($liste_commandes_terminees, $commande);
			}else{
				array_push($liste_commandes_archivees, $commande);
			}
		}
		//$liste_commandes_archivees = $em->getRepository('FragosoBundle:Commande')->findBy(array('client' => $client, 'etat' => 'terminee'), array('dateCommande' => 'DESC'));
		
		return $this->render('FragosoBundle:Client:client_detail.html.twig', array(
									'client' => $client,
									'liste_commandes_encours' => $liste_commandes_encours,
									'liste_commandes_terminees' => $liste_commandes_terminees,
									'liste_commandes_archivees' => $liste_commandes_archivees,
							));
	}
}
