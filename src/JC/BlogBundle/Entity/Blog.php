<?php

namespace JC\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Index;


/**
 * Blog
 *
 * @ORM\Table(name="blog")
 * @ORM\Entity(repositoryClass="JC\BlogBundle\Repository\BlogRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Blog
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
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $author;

    /**
     * @ORM\Column(type="text")
     */
    protected $blog;

    /**
     * @ORM\Column(type="string", length=300)
     */
    protected $image;

    /**
     * @ORM\Column(type="string", length=300)
     */
    protected $tag;

    /**
     * @ORM\OneToMany(targetEntity="JC\BlogBundle\Entity\Tagss", mappedBy="blog")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $tagss;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="blog")
     */
    protected $comments = array();

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $updated;

    /**
     * @ORM\Column(type="string")
     */
    protected $slug;




    public function __construct()
    {
        $this->comments = new ArrayCollection();

        $timeZone  = new \DateTimeZone('America/Toronto');
        $dateTime = new \DateTime();
        $dateTime ->setTimezone($timeZone);

        $this->setCreated($dateTime);
        $this->setUpdated($dateTime);
    }

    /**
     * @ORM\preUpdate
     */
    public function setUpdatedValue()
    {
        $timeZone  = new \DateTimeZone('America/Toronto');
        $dateTime = new \DateTime();
        $dateTime ->setTimezone($timeZone);

        $this->setUpdated($dateTime);
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

    public function addComment(Comment $comment)
    {
        $this->comments[] = $comment;
    }

    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Blog
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set author
     *
     * @param string $author
     *
     * @return Blog
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set blog
     *
     * @param string $blog
     *
     * @return Blog
     */
    public function setBlog($blog)
    {
        $this->blog = $blog;

        return $this;
    }

    /**
     * Get blog
     *
     * @return string
     */
    public function getBlog($length = null)
    {
        if (false === is_null($length) && $length > 0) {
            return substr($this->blog, 0, $length);
        }
        else {
            return $this->blog;
        }
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Blog
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }



    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Blog
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return Blog
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Remove comment
     *
     * @param \JC\BlogBundle\Entity\Comment $comment
     */
    public function removeComment(\JC\BlogBundle\Entity\Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Blog
     */
    public function setSlug($slug)
    {
        $slug2 = $this ->slugify($slug);
        $this->slug = $slug2;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    public function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('#[^\\pL\d]+#u', '-', $text);

        // trim
        $text = trim($text, '-');

        // transliterate
        if (function_exists('iconv'))
        {
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        }

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('#[^-\w]+#', '', $text);

        if (empty($text))
        {
            return 'n-a';
        }

        return $text;
    }


    /**
     * Add tagss
     *
     * @param \JC\BlogBundle\Entity\Tagss $tagss
     *
     * @return Blog
     */
    public function addTagss(\JC\BlogBundle\Entity\Tagss $tagss)
    {
        $this->tagss[] = $tagss;

        return $this;
    }

    /**
     * Remove tagss
     *
     * @param \JC\BlogBundle\Entity\Tagss $tagss
     */
    public function removeTagss(\JC\BlogBundle\Entity\Tagss $tagss)
    {
        $this->tagss->removeElement($tagss);
    }

    /**
     * Get tagss
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTagss()
    {
        return $this->tagss;
    }

    /**
     * Set tag
     *
     * @param string $tag
     *
     * @return Blog
     */
    public function setTag($tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get tag
     *
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }
}
