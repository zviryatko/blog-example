<?php
/**
 * @file
 *
 */

namespace Article\Entity;

use Application\Entity\File;
use Doctrine\ORM\Mapping as ORM;
use User\Entity\User;

/**
 * @ORM\Entity
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @var string
     */
    protected $title;

    /**
     * @ORM\Column(type="text", nullable=false)
     * @var string
     */
    protected $text;

    /**
     * @ORM\ManyToOne(targetEntity="User\Entity\User")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     * @var User
     */
    protected $author;

    /**
     *
     * @ORM\Column(type="datetime", nullable=false)
     * @var \DateTime
     */
    protected $created;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\File", cascade={"persist"})
     * @ORM\JoinColumn(name="thumbnail_id", referencedColumnName="id")
     * @var File
     */
    protected $thumbnail;

    public function __construct()
    {
        $this->setCreated(new \DateTime());
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return Article
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     *
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     *
     * @return Article
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param User $author
     *
     * @return Article
     */
    public function setAuthor(User $author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     *
     * @return Article
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasThumbnail()
    {
        return !is_null($this->thumbnail);
    }

    /**
     * @return File
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * @param File $thumbnail
     *
     * @return Article
     */
    public function setThumbnail(File $thumbnail)
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }
}