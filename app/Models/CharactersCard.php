<?php declare(strict_types=1);

namespace App\Models;

class CharactersCard
{
    private int $id;
    private string $name;
    private string $status;
    private string $species;
    private string $gender;
    private string $origin;
    private string $location;
    private Episode $episode;
    private string $imgUrl;

    public function __construct
    (
        int $id,
        string $name,
        string $status,
        string $species,
        string $gender,
        string $origin,
        string $location,
        Episode $episode,
        string $imgUrl
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->status = $status;
        $this->species = $species;
        $this->gender = $gender;
        $this->origin = $origin;
        $this->location = $location;
        $this->episode = $episode;
        $this->imgUrl = $imgUrl;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getSpecies(): string
    {
        return $this->species;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function getOrigin(): string
    {
        return $this->origin;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function getEpisode(): Episode
    {
        return $this->episode;
    }

    public function getImgUrl(): string
    {
        return $this->imgUrl;
    }
}
