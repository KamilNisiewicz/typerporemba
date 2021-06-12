<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bets
 *
 * @ORM\Table(name="bets", uniqueConstraints={@ORM\UniqueConstraint(name="bets_constraint", columns={"user_id", "match_id"})}, indexes={@ORM\Index(name="match_constraint", columns={"match_id"}), @ORM\Index(name="IDX_7C28752BA76ED395", columns={"user_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\BetsRepository")
 */
class Bets
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
     * @var int|null
     *
     * @ORM\Column(name="home_team_score", type="integer", nullable=true)
     */
    private $homeTeamScore;

    /**
     * @var int|null
     *
     * @ORM\Column(name="away_team_score", type="integer", nullable=true)
     */
    private $awayTeamScore;

    /**
     * @var int|null
     *
     * @ORM\Column(name="points", type="integer", nullable=true)
     */
    private $points;

    /**
     * @var \Matches
     *
     * @ORM\ManyToOne(targetEntity="Matches")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="match_id", referencedColumnName="id")
     * })
     */
    private $match;

    /**
     * @var \Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHomeTeamScore(): ?int
    {
        return $this->homeTeamScore;
    }

    public function setHomeTeamScore(?int $homeTeamScore): self
    {
        $this->homeTeamScore = $homeTeamScore;

        return $this;
    }

    public function getAwayTeamScore(): ?int
    {
        return $this->awayTeamScore;
    }

    public function setAwayTeamScore(?int $awayTeamScore): self
    {
        $this->awayTeamScore = $awayTeamScore;

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

    public function getMatch(): ?Matches
    {
        return $this->match;
    }

    public function setMatch(?Matches $match): self
    {
        $this->match = $match;

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
}
