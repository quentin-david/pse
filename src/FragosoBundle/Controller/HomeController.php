<?php

namespace FragosoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $liste_commandes_encours = $em->getRepository('FragosoBundle:Commande')->findBy(array('etat' => 'encours'));
        $liste_clients_debiteurs = array();
        foreach($em->getRepository('FragosoBundle:Client')->findAll() as $client)
		{
			if ($client->getSolde() > 0){
				array_push($liste_clients_debiteurs, $client);
			}
		}
        
        
        return $this->render('FragosoBundle:Home:index.html.twig', array(
                                    'liste_commandes_encours' => $liste_commandes_encours,
                                    'liste_clients_debiteurs' => $liste_clients_debiteurs,
                            ));
    }
}
