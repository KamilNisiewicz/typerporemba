<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Matches
 *
 * @ORM\Table(name="matches", uniqueConstraints={@ORM\UniqueConstraint(name="matches_constraint", columns={"home_team_id", "away_team_id"})}, indexes={@ORM\Index(name="away_team_constraint", columns={"away_team_id"}), @ORM\Index(name="IDX_62615BA9C4C13F6", columns={"home_team_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\MatchesRepository")
 */
class Matches
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $date = 'CURRENT_TIMESTAMP';

    /**
     * @var string|null
     *
     * @ORM\Column(name="phase", type="string", length=50, nullable=true)
     */
    private $phase;

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
     * @var bool|null
     *
     * @ORM\Column(name="finished", type="boolean", nullable=true)
     */
    private $finished = '0';

    /**
     * @var \Teams
     *
     * @ORM\ManyToOne(targetEntity="Teams")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="away_team_id", referencedColumnName="id")
     * })
     */
    private $awayTeam;

    /**
     * @var \Teams
     *
     * @ORM\ManyToOne(targetEntity="Teams")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="home_team_id", referencedColumnName="id")
     * })
     */
    private $homeTeam;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getPhase(): ?string
    {
        return $this->phase;
    }

    public function setPhase(?string $phase): self
    {
        $this->phase = $phase;

        return $this;
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

    public function getFinished(): ?bool
    {
        return $this->finished;
    }

    public function setFinished(?bool $finished): self
    {
        $this->finished = $finished;

        return $this;
    }

    public function getAwayTeam(): ?Teams
    {
        return $this->awayTeam;
    }

    public function setAwayTeam(?Teams $awayTeam): self
    {
        $this->awayTeam = $awayTeam;

        return $this;
    }

    public function getHomeTeam(): ?Teams
    {
        return $this->homeTeam;
    }

    public function setHomeTeam(?Teams $homeTeam): self
    {
        $this->homeTeam = $homeTeam;

        return $this;
    }


}
