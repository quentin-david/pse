<?php

namespace FragosoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Reglement
 *
 * @ORM\Table(name="reglement")
 * @ORM\Entity(repositoryClass="FragosoBundle\Repository\ReglementRepository")
 */
class Reglement
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
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var float
     *
     * @ORM\Column(name="montant", type="float")
     * @Assert\Valid
     */
    private $montant;

	/**
     * @ORM\ManyToOne(targetEntity="FragosoBundle\Entity\Commande", inversedBy="reglements")
     * @Assert\Valid
     */
    private $commande;
    
    
    public function __construct()
    {
        // Par défaut, la date de la commande est la date d'aujourd'hui
        $this->date = new \Datetime();
    }
   
    
   /**
    * Verifie dans le formulaire que le montant est valide 
    * 
    * @Assert\Callback
    */
	public function isMontantValid(ExecutionContextInterface $context, $payload)
	{
		if($this->getMontant() > $this->getCommande()->getResteAPayer()){
			$context->buildViolation('Montant trop élevé ! (limite : '.$this->getCommande()->getResteAPayer())->atPath('montant')->addViolation();
		}
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Reglement
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set montant
     *
     * @param float $montant
     *
     * @return Reglement
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant
     *
     * @return float
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Set commande
     *
     * @param \FragosoBundle\Entity\Commande $commande
     *
     * @return Reglement
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
}
