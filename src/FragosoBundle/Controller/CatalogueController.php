<?php

namespace FragosoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FragosoBundle\Entity\Categorie;
use FragosoBundle\Form\Type\CategorieType;

class CatalogueController extends Controller
{
    public function indexAction()
    {
        //Entity Manager
        $em = $this->getDoctrine()->getManager();
        
        //Liste des toutes les categories
        $liste_categories = $em->getRepository('FragosoBundle:Categorie')->findAll();
        
        return $this->render('FragosoBundle:Catalogue:index.html.twig', array(
                                    'liste_categories' => $liste_categories                                                  
                            ));
    }
    
    /*
     * Ajout ou edition d'une categorie
     */
    public function editerCategorieAction(Request $request, $categorie_num=null)
    {
        //Entity Manager
        $em = $this->getDoctrine()->getManager();
        
        //Creation d'un objet de type Categorie
        if($categorie_num === null){
            $categorie = new Categorie;
        }else{
            $categorie = $em->getRepository('FragosoBundle:Categorie')->find($categorie_num);
        }
        
        // Creation du formulaire générique de création d'une categorie
		$formulaire = $this->createForm(CategorieType::class, $categorie);
        
        //Enregistrement en base du formulaire
        if($request->isMethod('POST')){
            $formulaire->handleRequest($request);
            if($formulaire->isValid()){
                $em->persist($categorie);
                $em->flush();
                
				// Redirection vers la page catalogue
                return $this->redirectToRoute('catalogue_home');
            }
        }        
        
        return $this->render('FragosoBundle:Catalogue:categorie_edition.html.twig', array(
                                    'formulaire' => $formulaire->createView(),
                                    'categorie' => $categorie
                            ));
    }
    
    
    /**
	 * Suppression d'une categorie (avec demande de confirmation)
	 */
	public function supprimerCategorieAction(Request $request,$categorie_num)
	{
		//Entity Manager
        $em = $this->getDoctrine()->getManager();
		$categorie_a_supprimer = $em->getRepository('FragosoBundle:Categorie')->find($categorie_num);
        
        // Verification que la categorie existe bien
		if($request->isMethod('POST')){
            // TODO : que faire des services et produits liés à cette categorie !
			if($categorie_a_supprimer != ''){
				$em->remove($categorie_a_supprimer);
				$em->flush();
                // flash message
                $this->get('session')->getFlashBag()->add('info','categorie <b>'.$categorie_a_supprimer->getLibelle().'</b> supprimée avec succès');
			}
			return $this->redirectToRoute('catalogue_home');
		}
        
        return $this->render('FragosoBundle:Catalogue:categorie_suppression.html.twig',array(
                                    'categorie' => $categorie_a_supprimer
                            ));
	}
}
