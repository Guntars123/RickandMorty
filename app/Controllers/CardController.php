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
        $page = $_GET['page'] ?? "1";
        return new TwigView("index", ['page' => $page]);
    }

    public function characters(): TwigView
    {
        $page = $_GET['page'] ?? "1";
        $cards = $this->apiClient->getAllCharacters($page);

        return new TwigView("characters", ['cards' => $cards, 'page' => $page]);
    }

    public function filter(): TwigView
    {
        $search = $_GET['name'] ?? "";
        $status = $_GET['status'] ?? "";
        $species = $_GET['species'] ?? "";
        $gender = $_GET['gender'] ?? "";

        $cards = $this->apiClient->filterCharacters($search, $status, $species, $gender);

        return new TwigView("filterCharacter", ['cards' => $cards]);
    }

    public function locations(): TwigView
    {
        $page = $_GET['page'] ?? "1";
        $locations = $this->apiClient->getAllLocations($page);

        return new TwigView("locations", ['locations' => $locations, 'page' => $page]);
    }

    public function episodes(): TwigView
    {
        $page = $_GET['page'] ?? "1";
        $episodes = $this->apiClient->getAllEpisodes($page);

        return new TwigView("episodes", ['episodes' => $episodes, 'page' => $page]);
    }
}

