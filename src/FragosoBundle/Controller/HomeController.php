<?php

namespace FragosoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $liste_commandes_encours = $em->getRepository('FragosoBundle:Commande')->findBy(array('etat' => 'encours'));
        
        return $this->render('FragosoBundle:Home:index.html.twig', array(
                                    'liste_commandes_encours' => $liste_commandes_encours,
                            ));
    }
}
