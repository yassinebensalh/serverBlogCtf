<?php

namespace App\Entity;

use App\Controller\ActivateAccountController;
use App\Controller\SignUpController;
use App\Controller\SignInController;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
/**
 * @ApiResource(
 *     formats={"json"}
 *
 * )
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email.")
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username please chose another one.")
 */
//#[ApiResource()]

#[ApiResource(
    collectionOperations: [
    'SignUp' => [
        'pagination_enabled' => false,
        'path' => '/signup',
        'method' => 'post',
        'normalization_context' => ['groups' => ['signup:user']],
        'controller' => SignUpController::class,
        'output_formats' => [
            'json' => ['text/csv'],
        ]
    ],
    'ActivateAccount' => [
        'pagination_enabled' => false,
        'path' => '/ActivateAccount/{token}',
        'method' => 'put',
        'normalization_context' => ['groups' => ['token:user']],
        'controller' => ActivateAccountController::class,
        'output_formats' => [
            'json' => ['text/csv'],
        ]
    ]
],
    itemOperations: [
        'delete',
        'get',
        'put' => [
            'denormalization_context' => ['groups' => ['put:user']]
        ]
    ]

)]

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(['read:username'])]
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Length(min="12" , minMessage="the email must contain at least 20 characters.")
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="4" , minMessage="first name must contain at least 4 characters.")
     * @Assert\Length(max="20" , maxMessage="first name must contain at most 15 characters.")
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="4" , minMessage="last name must contain at least 4 characters.")
     * @Assert\Length(max="15" , maxMessage="last name must contain at most 15 characters.")
     */
    private $lastname;

    /**
     *     @Assert\Length(min="5" , minMessage="password must contain at least 8 characters.")
     *     @Assert\Length(max="20" , maxMessage="password must contain at most 18 characters.")
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    #[Groups(['put:user'])]
    private $activation_token;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    private $reset_token;

    /**
     * @ORM\Column(type="datetime")
     */
    private $last_login_date;

    /**
     * @ORM\Column(type="string", length=65, nullable=true)
     */
    private $disable_token;

    /**
     * @ORM\OneToMany(targetEntity=UserLoginDate::class, mappedBy="id_user")
     */
    private $userLoginDates;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Length(min="8" , minMessage="Phone number must contain exactly 8 numbers")
     * @Assert\Length(max="8" , maxMessage="Phone number must contain exactly 8 numbers")
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="string", length=70)
     */
    #[Groups(['read:username', 'home:Blog'])]
    private $username;

    /**
     * @ORM\OneToMany(targetEntity=Blog::class, mappedBy="username", orphanRemoval=true)
     */
    private $blogs;

    #[Groups(['signup:user','token:user'])]
    private $success = true;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->userLoginDates = new ArrayCollection();
        $this->blogs = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->email;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getSuccess(): Bool
    {
        return $this->success;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
         $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getActivationToken(): ?string
    {
        return $this->activation_token;
    }

    public function setActivationToken(?string $activation_token): self
    {
        $this->activation_token = $activation_token;

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->reset_token;
    }

    public function setResetToken(?string $reset_token): self
    {
        $this->reset_token = $reset_token;

        return $this;
    }

    public function getLastLoginDate(): ?\DateTimeInterface
    {
        return $this->last_login_date;
    }

    public function setLastLoginDate(\DateTimeInterface $last_login_date): self
    {
        $this->last_login_date = $last_login_date;

        return $this;
    }

    public function getDisableToken(): ?string
    {
        return $this->disable_token;
    }

    public function setDisableToken(?string $disable_token): self
    {
        $this->disable_token = $disable_token;

        return $this;
    }

    /**
     * @return Collection|UserLoginDate[]
     */
    public function getUserLoginDates(): Collection
    {
        return $this->userLoginDates;
    }

    public function addUserLoginDate(UserLoginDate $userLoginDate): self
    {
        if (!$this->userLoginDates->contains($userLoginDate)) {
            $this->userLoginDates[] = $userLoginDate;
            $userLoginDate->setIdUser($this);
        }

        return $this;
    }

    public function removeUserLoginDate(UserLoginDate $userLoginDate): self
    {
        if ($this->userLoginDates->removeElement($userLoginDate)) {
            // set the owning side to null (unless already changed)
            if ($userLoginDate->getIdUser() === $this) {
                $userLoginDate->setIdUser(null);
            }
        }

        return $this;
    }

    public function getPhoneNumber(): ?int
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(int $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return Collection|Blog[]
     */
    public function getBlogs(): Collection
    {
        return $this->blogs;
    }

    public function addBlog(Blog $blog): self
    {
        if (!$this->blogs->contains($blog)) {
            $this->blogs[] = $blog;
            $blog->setUsername($this);
        }

        return $this;
    }

    public function removeBlog(Blog $blog): self
    {
        if ($this->blogs->removeElement($blog)) {
            // set the owning side to null (unless already changed)
            if ($blog->getUsername() === $this) {
                $blog->setUsername(null);
            }
        }

        return $this;
    }

}
