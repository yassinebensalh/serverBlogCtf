<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use App\Controller\AllBlogsController;
use App\Controller\FlagController;
use App\Controller\HomeBlogsController;
use App\Controller\oneBlogController;
use App\Controller\PersonalBlogsController;
use App\Controller\SearchWithCategoryController;
use App\Repository\BlogRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=BlogRepository::class)
 */

#[ApiResource(
    collectionOperations: [
        'POST',
        'oneBlog' => [
            'pagination_enabled' => false,
            'path' => '/oneBlog/{id}',
            'method' => 'get',
            'controller' => oneBlogController::class,
            'output_formats' => [
                'json' => ['text/csv'],
            ],
            'normalization_context' => ['groups' => ['one:Blog']]
        ],
        'Flag' => [
            'pagination_enabled' => false,
            'path' => '/Flag/{id}/{flag}',
            'method' => 'get',
            'controller' => FlagController::class,
            'output_formats' => [
                'json' => ['text/csv'],
            ],
            'normalization_context' => ['groups' => ['one:Flag']]
        ],
        'AllBlogs' => [
            'pagination_enabled' => false,
            'path' => '/AllBlogs',
            'method' => 'get',
            'controller' => AllBlogsController::class,
            'output_formats' => [
                'json' => ['text/csv'],
            ],
        ],
        'SearchWithCategory' => [
            'pagination_enabled' => false,
            'path' => '/SearchWithCategory/{id}',
            'method' => 'get',
            'controller' => SearchWithCategoryController::class,
            'output_formats' => [
                'json' => ['text/csv'],
            ],
            'normalization_context' => ['groups' => ['Filter:Category']]
        ],
        'HomeBlogs' => [
            'pagination_enabled' => false,
            'path' => '/homeBlogs',
            'method' => 'get',
            'controller' => HomeBlogsController::class,
            'read' => false,
            'normalization_context' => ['groups' => ['home:Blog']],
            'output_formats' => [
                'json' => ['text/csv'],
            ],
        ],
        'PersonalBlogs' => [
            'pagination_enabled' => false,
            'path' => '/PersonalBlogs',
            'method' => 'get',
            'controller' => PersonalBlogsController::class,
            'read' => false
        ]
],
    normalizationContext: ['groups' => ['read:collection', 'read:username']],
    itemOperations: [
        'put' => [
            'denormalization_context' => ['groups' => ['put:Blog']]
        ],
        'delete',
        'get' => [
            'normalization_context' => ['groups' => ['read:collection' , 'read:item', 'read:username']]
        ]
    ]
)]
//#[ApiFilter(OrderFilter::class, properties: ['DateCreatedAt' => 'DESC'])]

class Blog
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(['read:item','home:Blog','one:Blog','Filter:Category'])]
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="blogs")
     * @ORM\JoinColumn(nullable=false)
     */
    #[Groups(['read:collection'])]
    private $username;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @ORM\JoinColumn(nullable=true)
     */
    #[Groups(['read:item','read:collection','Filter:Category'])]
    private $DateCreatedAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     *  @ORM\JoinColumn(nullable=true)
     */
    #[Groups(['put:Blog'])]
    private $DateModifiedAt;

    /**
     * @ORM\Column(type="text")
     */
    #[Groups(['read:collection','read:item','put:Blog','one:Blog','Filter:Category','one:Flag'])]
    private $blogContent;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['read:collection','put:Blog', 'home:Blog','one:Blog','Filter:Category','one:Flag'])]
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['read:collection','put:Blog','home:Blog','one:Blog','Filter:Category','one:Flag'])]
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     *  @ORM\JoinColumn(nullable=true)
     */
    #[Groups(['read:item','put:Blog', 'home:Blog','one:Blog','Filter:Category','one:Flag'])]
    private $Category;

    #[Groups(['read:item','read:collection','one:Blog','put:Blog','Filter:Category','one:Flag'])]
    private $success = true;

    /**
     * @ORM\Column(type="string", length=80)
     */
    #[Groups(['read:item','read:collection', 'home:Blog','one:Blog','Filter:Category','one:Flag'])]
    private $owner;

    /**
     * @ORM\Column(type="string", length=50)
     */
    #[Groups(['one:Flag','put:Blog'])]
    private $flag;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getSuccess(): Bool
    {
        return $this->success;
    }
    public function getUsername(): ?User
    {
        return $this->username;
    }

    public function setUsername(?User $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getDateCreatedAt(): ?\DateTimeImmutable
    {
        return $this->DateCreatedAt;
    }

    public function setDateCreatedAt(\DateTimeImmutable $DateCreatedAt): self
    {
        $this->DateCreatedAt = $DateCreatedAt;

        return $this;
    }

    public function getDateModifiedAt(): ?\DateTimeImmutable
    {
        return $this->DateModifiedAt;
    }

    public function setDateModifiedAt(\DateTimeImmutable $DateModifiedAt): self
    {
        $this->DateModifiedAt = $DateModifiedAt;

        return $this;
    }

    public function getBlogContent()
    {
        return $this->blogContent;
    }

    public function setBlogContent(string $blogContent): self
    {
        $this->blogContent = $blogContent;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
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

    public function getCategory(): ?string
    {
        return $this->Category;
    }

    public function setCategory(string $Category): self
    {
        $this->Category = $Category;

        return $this;
    }

    public function getOwner(): ?string
    {
        return $this->owner;
    }

    public function setOwner(string $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getFlag(): ?string
    {
        return $this->flag;
    }

    public function setFlag(string $flag): self
    {
        $this->flag = $flag;

        return $this;
    }
}
