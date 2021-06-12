<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BetsBonus
 *
 * @ORM\Table(name="bets_bonus", uniqueConstraints={@ORM\UniqueConstraint(name="user_id", columns={"user_id"})}, indexes={@ORM\Index(name="team_id", columns={"team_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\BetsBonusRepository")
 */
class BetsBonus
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="top_scorer", type="string", length=255, nullable=true)
     */
    private $topScorer;

    /**
     * @var int|null
     *
     * @ORM\Column(name="points", type="integer", nullable=true)
     */
    private $points;

    /**
     * @var \Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var \Teams
     *
     * @ORM\ManyToOne(targetEntity="Teams")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="team_id", referencedColumnName="id")
     * })
     */
    private $team;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTopScorer(): ?string
    {
        return $this->topScorer;
    }

    public function setTopScorer(?string $topScorer): self
    {
        $this->topScorer = $topScorer;

        return $this;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(?int $points): self
    {
        $this->points = $points;

        return $this;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getTeam(): ?Teams
    {
        return $this->team;
    }

    public function setTeam(?Teams $team): self
    {
        $this->team = $team;

        return $this;
    }
}
