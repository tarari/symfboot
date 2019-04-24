<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TagRepository")
 */
class Tag
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     *
     * @ORM\Column(type="string",length=100)
     */
    private $tag;
    /**
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Post",mappedBy="tags")
     *
     */
    private $posts;
    public function getPosts(){
        return $this->posts;
    }
    public function __construct() {
        $this->posts=new ArrayCollection();
    }

    public function getId() {
        return $this->id;
    }

    public function getTag() {
        return $this->tag;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setTag($tag) {
        $this->tag = $tag;
    }
    // we must add this functions to convert to string passing
    // data to post
    public function __toString() {
        return $this->tag;
    }
}
