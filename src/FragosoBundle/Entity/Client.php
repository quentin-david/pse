<?php

namespace FragosoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Client
 *
 * @ORM\Table(name="client")
 * @ORM\Entity(repositoryClass="FragosoBundle\Repository\ClientRepository")
 */
class Client
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
     * @var string
     *
     * @ORM\Column(name="civilite", type="string", length=5)
     */
    private $civilite;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255)
     */
    private $adresse;

	/**
     * @var string
     *
     * @ORM\Column(name="code_postal", type="string", length=5)
     */
    private $codePostal;
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=255)
     */
    private $ville;

    /**
     * @var string
     *
     * @ORM\Column(name="num_portable", type="string", length=14, nullable=true)
     * @Assert\Regex("/([0-9][0-9]\.){4}[0-9][0-9]/")
     */
    private $numPortable;
    
    /**
     * @var int
     *
     * @ORM\Column(name="remise", type="integer")
     */
    private $remise;
    
    /**
     * @ORM\OneToMany(targetEntity="FragosoBundle\Entity\Commande", mappedBy="client")
     */
    private $commandes;


	/**
	 * Retourne le solde du client : la somme des commandes qui n'ont pas encore été payées
	 */
	public function getSolde()
	{
		$solde = 0;
		foreach($this->getCommandes() as $commande){
			//if($commande->getEtat() == 'encours'){
				//$solde += $commande->getPrixTotalTTC();
				$solde += $commande->getResteAPayer();
			//}
		}
		return $solde;
	}
	
	/**
	 * Retourne le montant total des commandes du client
	 */
	public function getMontantTotalCommandes()
	{
		$montant = 0;
		foreach($this->getCommandes() as $commande){
			$montant += $commande->getPrixTotalTTC();
		}
		return $montant;
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
     * Set civilite
     *
     * @param string $civilite
     *
     * @return Client
     */
    public function setCivilite($civilite)
    {
        $this->civilite = $civilite;

        return $this;
    }

    /**
     * Get civilite
     *
     * @return string
     */
    public function getCivilite()
    {
        return $this->civilite;
    }
    
    /**
     * Get nom complet
     */
    public function getNomComplet()
    {
        return ucfirst($this->prenom).' '.strtoupper($this->nom); 
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Client
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Client
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Client
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }
	
	/**
	 * Retourne 
	 */
	public function getAdresseComplete()
	{
		return $this->adresse.' à '.strtoupper($this->ville).' '.$this->codePostal;
	}

    /**
     * Set ville
     *
     * @param string $ville
     *
     * @return Client
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set numPortable
     *
     * @param string $numPortable
     *
     * @return Client
     */
    public function setNumPortable($numPortable)
    {
        $this->numPortable = $numPortable;

        return $this;
    }

    /**
     * Get numPortable
     *
     * @return string
     */
    public function getNumPortable()
    {
        return $this->numPortable;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->commandes = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Add commande
     *
     * @param \FragosoBundle\Entity\Commande $commande
     *
     * @return Client
     */
    public function addCommande(\FragosoBundle\Entity\Commande $commande)
    {
        $this->commandes[] = $commande;

        return $this;
    }

    /**
     * Remove commande
     *
     * @param \FragosoBundle\Entity\Commande $commande
     */
    public function removeCommande(\FragosoBundle\Entity\Commande $commande)
    {
        $this->commandes->removeElement($commande);
    }

    /**
     * Get commandes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCommandes()
    {
        return $this->commandes;
    }

    /**
     * Set codePostal
     *
     * @param string $codePostal
     *
     * @return Client
     */
    public function setCodePostal($codePostal)
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * Get codePostal
     *
     * @return string
     */
    public function getCodePostal()
    {
        return $this->codePostal;
    }

    /**
     * Set remise
     *
     * @param integer $remise
     *
     * @return Client
     */
    public function setRemise($remise)
    {
        $this->remise = $remise;

        return $this;
    }

    /**
     * Get remise
     *
     * @return integer
     */
    public function getRemise()
    {
        return $this->remise;
    }
}
