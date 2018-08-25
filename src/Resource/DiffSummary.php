<?php declare(strict_types=1);

namespace App\Resource;

class DiffSummary
{
    private $forks;
    private $stars;
    private $lastUpdate;
    private $openPullRequests;
    private $closedPullRequestes;
    private $latestRelease;

    public function getForks() : array
    {
        return $this->forks;
    }

    public function setForks(array $forks)
    {
        $this->forks = $forks;
    }

    public function getStars() : array
    {
        return $this->stars;
    }

    public function setStars(array $stars)
    {
        $this->stars = $stars;
    }

    public function getLastUpdate() : array
    {
        return $this->lastUpdate;
    }

    public function setLastUpdate(array $lastUpdate)
    {
        $this->lastUpdate = $lastUpdate;
    }

    public function getOpenPullRequests() : array
    {
        return $this->openPullRequests;
    }

    public function setOpenPullRequests(array $openPullRequests)
    {
        $this->openPullRequests = $openPullRequests;
    }

    public function getClosedPullRequestes() : array
    {
        return $this->closedPullRequestes;
    }

    public function setClosedPullRequestes(array $closedPullRequestes)
    {
        $this->closedPullRequestes = $closedPullRequestes;
    }

    public function getLatestRelease() : array
    {
        return $this->latestRelease;
    }

    public function setLatestRelease(array $latestRelease)
    {
        $this->latestRelease = $latestRelease;
    }
}