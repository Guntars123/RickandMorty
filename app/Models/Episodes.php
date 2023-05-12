<?php declare(strict_types=1);

namespace App\Models;

class Episodes
{
    private string $name;
    private string $airDate;
    private array $characters;

    public function __construct(string $name, string $airDate, array $characters)
    {
        $this->name = $name;
        $this->airDate = $airDate;
        $this->characters = $characters;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAirDate(): string
    {
        return $this->airDate;
    }

    public function getCharacters(): array
    {
        return $this->characters;
    }
}
