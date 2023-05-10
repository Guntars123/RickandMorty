<?php declare(strict_types=1);

namespace App\Controllers;

use App\ApiClient;
use App\Core\TwigView;

class CardController
{
    private ApiClient $apiClient;

    public function __construct()
    {
        $this->apiClient = new ApiClient();
    }

    public function index(): TwigView
    {
        $search = $_GET['name'] ?? '';
        $page = $_GET['page'] ?? "1";
        $cards = $this->apiClient->getAllCharacters($search, $page);

        return new TwigView("index", ['query' => [$search, $page], 'cards' => $cards, 'page' => $page]);
    }
}

