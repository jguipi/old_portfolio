<?php

namespace JC\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Connectes
 *
 * @ORM\Table(name="connectes")
 * @ORM\Entity(repositoryClass="JC\BlogBundle\Repository\ConnectesRepository")
 */
class Connectes
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
     * @ORM\Column(name="ip", type="string", length=15)
     */
    private $ip;

    /**
     * @ORM\Column(type="datetime")
     */
    private $timestamp;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $position;


    protected $visitor = array();

    public function __construct()
    {
        $this->comments = new ArrayCollection();
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
     * Set ip
     *
     * @param string $ip
     *
     * @return Connectes
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set timestamp
     *
     * @param integer $timestamp
     *
     * @return Connectes
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Get timestamp
     *
     * @return int
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     *

    public function removeComment(\JC\BlogBundle\Entity\Connectes $comment)
    {
        $this->visitor->removeElement($comment);
    }

    public function addVisitor(Comment $comment)
    {
        $this->visitor[] = $comment;
    }

    public function getComments()
    {
        return $this->comments;
    }
*/

    /**
     * Set position
     *
     * @param string $position
     *
     * @return Connectes
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }
}
