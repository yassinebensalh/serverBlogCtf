<?php

namespace App\Entity;

use App\Repository\UserLoginDateRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserLoginDateRepository::class)
 */
class UserLoginDate
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $day_name;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="userLoginDates")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $login_date;

    /**
     * @ORM\Column(type="integer")
     */
    private $week;

    /**
     * @ORM\Column(type="string", length=7, nullable=true)
     */
    private $color;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDayName(): ?string
    {
        return $this->day_name;
    }

    public function setDayName(string $day_name): self
    {
        $this->day_name = $day_name;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->id_user;
    }

    public function setIdUser(?User $id_user): self
    {
        $this->id_user = $id_user;

        return $this;
    }

    public function getLoginDate(): ?\DateTimeInterface
    {
        return $this->login_date;
    }

    public function setLoginDate(\DateTimeInterface $login_date): self
    {
        $this->login_date = $login_date;

        return $this;
    }

    public function getWeek(): ?int
    {
        return $this->week;
    }

    public function setWeek(int $week): self
    {
        $this->week = $week;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

}
