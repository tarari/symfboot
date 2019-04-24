<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\User;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 */
class Post
{

    const NUM_ITEMS=10;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $contents;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $author;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $publishedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modifiedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\user", inversedBy="posts")
     */
    private $user;

    /**
     *
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag",inversedBy="posts",cascade={"persist"})
     * @ORM\JoinTable(name="post_tags")
     * @ORM\OrderBy({"tag":"ASC"})
     * @Assert\Count(max="4",maxMessage="post.too_many_tags")
     */
    private $tags;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isPublished;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $summary;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="post", orphanRemoval=true)
     */
    private $comments;
    public function getTags(){
        return $this->tags;
    }
    public function addTag(Tag ...$tags){
        foreach ($tags as $tag){
            if(!$this->tags->contains($tag)){
                $this->tags->add($tag);

            }
        }

    }
    public function removeTag(Tag $tag){
        $this->tags->removeElement($tag);
    }
    public function __construct() {

        $this->createdAt=new \Datetime();
        $this->tags=new ArrayCollection();
        $this->comments=new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContents(): ?string
    {
        return $this->contents;
    }

    public function setContents(?string $contents): self
    {
        $this->contents = $contents;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeInterface $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getModifiedAt(): ?\DateTimeInterface
    {
        return $this->modifiedAt;
    }

    public function setModifiedAt(?\DateTimeInterface $modifiedAt): self
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(?bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }
    public function addSummary():?string
    {
        return substr($this->contents,0,80);
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }
}
