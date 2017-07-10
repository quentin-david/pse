<?php

namespace FragosoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FragosoBundle\Entity\Categorie;
use FragosoBundle\Entity\Article;
use FragosoBundle\Form\Type\CategorieType;
use FragosoBundle\Form\Type\ArticleType;

class CatalogueController extends Controller
{
    public function indexAction()
    {
        //Entity Manager
        $em = $this->getDoctrine()->getManager();
        
        //Liste des toutes les categories et articles
        $liste_categories = $em->getRepository('FragosoBundle:Categorie')->findAll();
		$liste_articles = $em->getRepository('FragosoBundle:Article')->findAll();
        
        return $this->render('FragosoBundle:Catalogue:index.html.twig', array(
                                    'liste_categories' => $liste_categories,
									'liste_articles' => $liste_articles,
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
            // Cas ou le client n'existe pas
            if(!$categorie){
				$this->get('session')->getFlashBag()->add('info','Categorie inconnue... (pas touche aux URL !)');
				return $this->redirectToRoute('creer_categorie');
			}
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
	
	/**********************   Articles   *****************/
	
	/*
     * Ajout ou edition d'une categorie
     */
    public function editerArticleAction(Request $request, $article_num=null)
    {
        //Entity Manager
        $em = $this->getDoctrine()->getManager();
        
        //Creation d'un objet de type Categorie
        if($article_num === null){
            $article = new Article;
        }else{
            $article = $em->getRepository('FragosoBundle:Article')->find($article_num);
            // Cas ou l'article n'existe pas
            if(!$article){
				$this->get('session')->getFlashBag()->add('warning','Article inconnu... (pas touche aux URL !)');
				return $this->redirectToRoute('creer_categorie');
			}
        }
        
        // Creation du formulaire générique de création d'un article
		$formulaire = $this->createForm(ArticleType::class, $article);
        
        //Enregistrement en base du formulaire
        if($request->isMethod('POST')){
            $formulaire->handleRequest($request);
            if($formulaire->isValid()){
                $em->persist($article);
                $em->flush();
                
				// Redirection vers la page catalogue
                return $this->redirectToRoute('catalogue_home');
            }
        }        
        
        return $this->render('FragosoBundle:Catalogue:article_edition.html.twig', array(
                                    'formulaire' => $formulaire->createView(),
                                    'article' => $article,
                            ));
    }
	
}
