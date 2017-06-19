<?php

namespace FragosoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CommandeDetail
 *
 * @ORM\Table(name="commande_detail")
 * @ORM\Entity(repositoryClass="FragosoBundle\Repository\CommandeDetailRepository")
 */
class CommandeDetail
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


	/**
	 * @ORM\ManyToOne(targetEntity="FragosoBundle\Entity\Article", cascade={"persist"})
	 */
	private $article;
	
	
	/**
	 * @ORM\ManyToOne(targetEntity="FragosoBundle\Entity\Commande", inversedBy="articles")
	 */
	private $commande;
	
	
    /**
     * @var int
     *
     * @ORM\Column(name="quantite", type="integer")
     */
    private $quantite;

    /**
     * @var float
     *
     * @ORM\Column(name="tva_appliquee", type="float")
     */
    private $tvaAppliquee;

    /**
     * @var float
     *
     * @ORM\Column(name="prix_applique", type="float")
     */
    private $prixApplique;

	
	/*public function __construct()
	{
		$this->prixApplique = $this->getArticle()->getPrix();
		$this->tvaAppliquee = $this->getArticle()->getTva();
	}*/

	/*
	 * Prix total TTC pour un produit
	 */
	public function getPrixTotalTTC()
	{
		return round($this->quantite * ($this->prixApplique * ( 1 + $this->tvaAppliquee / 100 )), 2);
	}
	
	
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set quantite
     *
     * @param integer $quantite
     *
     * @return CommandeDetail
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite
     *
     * @return int
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * Set tvaAppliquee
     *
     * @param float $tvaAppliquee
     *
     * @return CommandeDetail
     */
    public function setTvaAppliquee($tvaAppliquee)
    {
        $this->tvaAppliquee = $tvaAppliquee;

        return $this;
    }

    /**
     * Get tvaAppliquee
     *
     * @return float
     */
    public function getTvaAppliquee()
    {
        return $this->tvaAppliquee;
    }

    /**
     * Set prixApplique
     *
     * @param float $prixApplique
     *
     * @return CommandeDetail
     */
    public function setPrixApplique($prixApplique)
    {
        $this->prixApplique = $prixApplique;

        return $this;
    }

    /**
     * Get prixApplique
     *
     * @return float
     */
    public function getPrixApplique()
    {
        return $this->prixApplique;
    }

    /**
     * Set client
     *
     * @param \FragosoBundle\Entity\Client $client
     *
     * @return CommandeDetail
     */
    public function setClient(\FragosoBundle\Entity\Client $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \FragosoBundle\Entity\Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set commande
     *
     * @param \FragosoBundle\Entity\Commande $commande
     *
     * @return CommandeDetail
     */
    public function setCommande(\FragosoBundle\Entity\Commande $commande = null)
    {
        $this->commande = $commande;

        return $this;
    }

    /**
     * Get commande
     *
     * @return \FragosoBundle\Entity\Commande
     */
    public function getCommande()
    {
        return $this->commande;
    }

    /**
     * Set article
     *
     * @param \FragosoBundle\Entity\Article $article
     *
     * @return CommandeDetail
     */
    public function setArticle(\FragosoBundle\Entity\Article $article = null)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Get article
     *
     * @return \FragosoBundle\Entity\Article
     */
    public function getArticle()
    {
        return $this->article;
    }
}
