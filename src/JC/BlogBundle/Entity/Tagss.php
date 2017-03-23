<?php

namespace JC\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tagss
 *
 * @ORM\Table(name="tagss")
 * @ORM\Entity(repositoryClass="JC\BlogBundle\Repository\TagssRepository")
 */
class Tagss
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
     * @ORM\Column(name="value", type="string", length=255)
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="Blog", inversedBy="blog")
     * @ORM\JoinColumn(name="blog_id", referencedColumnName="id")
     */
    private $blog_id;
    
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
     * Set value
     *
     * @param string $value
     *
     * @return Tagss
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set blogId
     *
     * @param \JC\BlogBundle\Entity\Blog $blogId
     *
     * @return Tagss
     */
    public function setBlogId(\JC\BlogBundle\Entity\Blog $blogId = null)
    {
        $this->blog_id = $blogId;

        return $this;
    }

    /**
     * Get blogId
     *
     * @return \JC\BlogBundle\Entity\Blog
     */
    public function getBlogId()
    {
        return $this->blog_id;
    }
}
