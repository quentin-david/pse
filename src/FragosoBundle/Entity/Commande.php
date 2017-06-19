<?php

namespace FragosoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commande
 *
 * @ORM\Table(name="commande")
 * @ORM\Entity(repositoryClass="FragosoBundle\Repository\CommandeRepository")
 */
class Commande
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
     * @var \DateTime
     *
     * @ORM\Column(name="date_commande", type="datetime")
     */
    private $dateCommande;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="FragosoBundle\Entity\Client", inversedBy="commandes")
     */
    private $client;
    
    
    /**
     * @ORM\OneToMany(targetEntity="FragosoBundle\Entity\CommandeDetail", cascade={"persist"}, mappedBy="commande")
     */
    private $articles;
    
     
    /**
     * @var float
     *
     * @ORM\Column(name="remise", type="float", nullable=true)
     */
    private $remise;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_facture", type="datetime", nullable=true)
     */
    private $dateFacture;

    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=50)
     */
    private $etat;

    
    public function __construct()
    {
        // Par défaut, la date de la commande est la date d'aujourd'hui
        $this->dateCommande = new \Datetime();
    }
    
    /**
     * retourne prix total TTC de la commande
     */
    public function getPrixTotalAvantRemiseTTC()
	{
        $total = 0;
        foreach ($this->getArticles() as $article){
            $total += $article->getPrixTotalTTC();
        }
		return $total;
	}
	
	/**
	 * Retourne le montant de la remise
	 */
	public function getPrixRemise()
	{
		if($this->getRemise() === null){
			return 0;
		}else{
			return ($this->getPrixTotalAvantRemiseTTC() / 100) * $this->getRemise();
		}
	}
	
    /**
     * retourne prix total TTC de la commande avec la remise
     */
    public function getPrixTotalTTC()
	{
		return $this->getPrixTotalAvantRemiseTTC() - $this->getPrixRemise();
	}
	
	/**
	 * Retourne l'age d'une commande : différence entre date actuelle et la date de création
	 */
	public function getAge()
	{
		$date_actuelle = new \DateTime;
		return ($date_actuelle->diff($this->dateCommande));
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
     * Set dateCommande
     *
     * @param \DateTime $dateCommande
     *
     * @return Commande
     */
    public function setDateCommande($dateCommande)
    {
        $this->dateCommande = $dateCommande;

        return $this;
    }

    /**
     * Get dateCommande
     *
     * @return \DateTime
     */
    public function getDateCommande()
    {
        return $this->dateCommande;
    }

    /**
     * Set remise
     *
     * @param float $remise
     *
     * @return Commande
     */
    public function setRemise($remise)
    {
        $this->remise = $remise;

        return $this;
    }

    /**
     * Get remise
     *
     * @return float
     */
    public function getRemise()
    {
        return $this->remise;
    }

    /**
     * Set dateFacture
     *
     * @param \DateTime $dateFacture
     *
     * @return Commande
     */
    public function setDateFacture($dateFacture)
    {
        $this->dateFacture = $dateFacture;

        return $this;
    }

    /**
     * Get dateFacture
     *
     * @return \DateTime
     */
    public function getDateFacture()
    {
        return $this->dateFacture;
    }

    /**
     * Set etat
     *
     * @param string $etat
     *
     * @return Commande
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return string
     */
    public function getEtat()
    {
        return $this->etat;
    }



    /**
     * Set client
     *
     * @param \FragosoBundle\Entity\Client $client
     *
     * @return Commande
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
     * Add article
     *
     * @param \FragosoBundle\Entity\CommandeDetail $article
     *
     * @return Commande
     */
    public function addArticle(\FragosoBundle\Entity\CommandeDetail $article)
    {
        $this->articles[] = $article;

        return $this;
    }

    /**
     * Remove article
     *
     * @param \FragosoBundle\Entity\CommandeDetail $article
     */
    public function removeArticle(\FragosoBundle\Entity\CommandeDetail $article)
    {
        $this->articles->removeElement($article);
    }

    /**
     * Get articles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArticles()
    {
        return $this->articles;
    }
}
