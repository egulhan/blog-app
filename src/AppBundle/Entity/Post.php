<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Post
 *
 * @ORM\Table(name="posts", indexes={@ORM\Index(name="ix_user_id", columns={"user_id"}), @ORM\Index(name="ix_is_published", columns={"is_published"}), @ORM\Index(name="ix_created_time", columns={"created_time"}), @ORM\Index(name="ix_update_time", columns={"update_time"})})
 * @ORM\Entity
 */
class Post
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=200, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", length=16777215, nullable=false)
     */
    private $content;


    /**
     * @var string
     *
     * @ORM\Column(name="seo_url", type="string", length=200, nullable=false)
     */
    private $seo_url;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_published", type="integer", nullable=false)
     */
    private $is_published = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_time", type="datetime", nullable=true)
     */
    private $created_time = '0000-00-00 00:00:00';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="update_time", type="datetime", nullable=true)
     */
    private $update_time;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Post
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
     * Set content
     *
     * @param string $content
     *
     * @return Post
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }


    /**
     * Set SEO URL
     *
     * @param string $seo_url
     *
     * @return Post
     */
    public function setSeoUrl($seo_url)
    {
        $this->seo_url = $seo_url;

        return $this;
    }

    /**
     * Get SEO URL
     *
     * @return string
     */
    public function getSeoUrl()
    {
        return $this->seo_url;
    }

    /**
     * Set is_published
     *
     * @param integer $is_published
     *
     * @return Post
     */
    public function setIsPublished($is_published)
    {
        $this->is_published = $is_published;

        return $this;
    }

    /**
     * Get is_published
     *
     * @return integer
     */
    public function getIsPublished()
    {
        return $this->is_published;
    }

    /**
     * Set created_time
     *
     * @param \DateTime $created_time
     *
     * @return Post
     */
    public function setCreatedTime($created_time)
    {
        $this->created_time = $created_time;

        return $this;
    }

    /**
     * Get created_time
     *
     * @return \DateTime
     */
    public function getCreatedTime()
    {
        return $this->created_time;
    }

    /**
     * Set update_time
     *
     * @param \DateTime $update_time
     *
     * @return Post
     */
    public function setUpdateTime($update_time)
    {
        $this->update_time = $update_time;

        return $this;
    }

    /**
     * Get update_time
     *
     * @return \DateTime
     */
    public function getUpdateTime()
    {
        return $this->update_time;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Post
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Shortens content
     *
     * @return string
     */
    public function getShortContent()
    {
        $maxLen = 75;
        $newContent = $this->content;

        if (strlen($this->content) > $maxLen) {
            $newContent = substr($this->content, 0, $maxLen). '...';
        }

        return $newContent;
    }
}
