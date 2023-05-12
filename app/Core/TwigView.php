<?php declare(strict_types=1);

namespace App\Core;

class TwigView extends View
{
    private string $template;
    private array $data;

    public function __construct(string $template, array $data = null)
    {
        $this->template = $template;
        $this->data = $data;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
