<?php declare(strict_types=1);

namespace App\Models;

class Location
{
    private string $name;
    private string $type;
    private string $dimension;
    private array $residents;

    public function __construct(string $name, string $type, string $dimension, array $residents)
    {
        $this->name = $name;
        $this->type = $type;
        $this->dimension = $dimension;
        $this->residents = $residents;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getDimension(): string
    {
        return $this->dimension;
    }

    public function getResidents(): array
    {
        return $this->residents;
    }
}