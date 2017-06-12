<?php

namespace FragosoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FragosoBundle\Entity\Client;
use FragosoBundle\Form\Type\ClientType;

class ClientController extends Controller
{
    public function indexAction()
    {
		$em = $this->getDoctrine()->getManager();
		$liste_clients = $em->getRepository('FragosoBundle:Client')->findAll();
		
        return $this->render('FragosoBundle:Client:index.html.twig', array(
									'liste_clients' => $liste_clients,
							));
    }
   
    
    /*
     * Ajout ou edition d'un client
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
        }
        
        // Creation du formulaire générique de création d'un client
		$formulaire = $this->createForm(ClientType::class, $client);
        
        //Enregistrement en base du formulaire
        if($request->isMethod('POST')){
            $formulaire->handleRequest($request);
            if($formulaire->isValid()){
                $em->persist($client);
                $em->flush();
                
                // Redirection vers la page catalogue
                return $this->redirectToRoute('client_home');
            }
        }        
        
        return $this->render('FragosoBundle:Client:client_edition.html.twig', array(
                                    'formulaire' => $formulaire->createView(),
                                    'client' => $client
                            ));
    }
    
    
    /*
     * Suppression d'un client (avec demande de confirmation)
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
}
