<?php declare(strict_types=1);

namespace App;

use App\Models\CharactersCard;
use App\Models\Episode;
use App\Models\Episodes;
use App\Models\Location;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use stdClass;

class ApiClient
{
    private Client $client;
    private const BASE_URI = 'https://rickandmortyapi.com/api/';

    public function __construct()
    {
        $this->client = new Client();
    }

    public function getAllCharacters(string $page): ?array
    {
        try {
            if (!Cache::has('characters' . $page)) {
                $response = $this->client->get(self::BASE_URI . 'character/', [
                    'query' => [
                        'page' => $page,
                    ]
                ]);

                $responseJson = $response->getBody()->getContents();

                Cache::remember('characters' . $page, $responseJson);
            } else {
                $responseJson = Cache::get('characters' . $page);
            }

            return $this->createCollection(json_decode($responseJson)->results);

        } catch (GuzzleException $e) {
            return null;
        }
    }

    public function filterCharacters(string $search, string $status, string $species, string $gender): ?array
    {
        try {
            if (!Cache::has('filter' . $search . $status . $species . $gender)) {
                $response = $this->client->get(self::BASE_URI . 'character/', [
                    'query' => [
                        'name' => $search,
                        'status' => $status,
                        'species' => $species,
                        'gender' => $gender,
                    ]
                ]);

                $responseJson = $response->getBody()->getContents();

                Cache::remember('filter' . $search . $status . $species . $gender, $responseJson);
            } else {
                $responseJson = Cache::get('filter' . $search . $status . $species . $gender);
            }

            return $this->createCollection(json_decode($responseJson)->results);

        } catch (GuzzleException $e) {
            return null;
        }
    }

    public function getAllLocations(string $page): ?array
    {
        try {
            if (!Cache::has('location' . $page)) {
                $response = $this->client->get(self::BASE_URI . 'location/', [
                    'query' => [
                        'page' => $page,
                    ]
                ]);

                $responseJson = $response->getBody()->getContents();

                Cache::remember('location' . $page, $responseJson);
            } else {
                $responseJson = Cache::get('location' . $page);
            }

            $locationResults = json_decode($responseJson)->results;

            $locations = [];
            foreach ($locationResults as $location) {
                $residentsName = [];
                foreach ($location->residents as $residentUrl) {
                    $locationCacheKey = md5($residentUrl);
                    if (!Cache::has($locationCacheKey)) {
                        $response = $this->client->get($residentUrl);
                        $responseJson = $response->getBody()->getContents();
                        Cache::remember($locationCacheKey, $responseJson);
                    } else {
                        $responseJson = Cache::get($locationCacheKey);
                    }
                    $residentsName[] = json_decode($responseJson)->name;
                }
                $locations[] = new Location($location->name, $location->type, $location->dimension, $residentsName);
            }
            return $locations;

        } catch (GuzzleException $e) {
            return null;
        }
    }

    public function getAllEpisodes(string $page): ?array
    {
        try {
            if (!Cache::has('episode' . $page)) {
                $response = $this->client->get(self::BASE_URI . 'episode/', [
                    'query' => [
                        'page' => $page,
                    ]
                ]);

                $responseJson = $response->getBody()->getContents();

                Cache::remember('episode' . $page, $responseJson);
            } else {
                $responseJson = Cache::get('episode' . $page);
            }
            $episodeResults = json_decode($responseJson)->results;

            $episodes = [];
            foreach ($episodeResults as $episode) {
                $charactersName = [];
                foreach ($episode->characters as $episodeUrl) {
                    $episodeCacheKey = md5($episodeUrl);
                    if (!Cache::has($episodeCacheKey)) {
                        $response = $this->client->get($episodeUrl);
                        $responseJson = $response->getBody()->getContents();
                        Cache::remember($episodeCacheKey, $responseJson);
                    } else {
                        $responseJson = Cache::get($episodeCacheKey);
                    }
                    $charactersName[] = json_decode($responseJson)->name;
                }
                $episodes[] = new Episodes($episode->name, $episode->air_date, $charactersName);
            }
            return $episodes;

        } catch (GuzzleException $e) {
            return null;
        }
    }

    private function createCollection(array $fetchResults): ?array
    {
        if ($fetchResults != null) {
            $cards = [];
            foreach ($fetchResults as $card) {
                $episodeUri = $card->episode[0];
                $episodeCacheKey = md5($episodeUri);

                if (!Cache::has($episodeCacheKey)) {
                    $episodeJson = $this->client->get($episodeUri)->getBody()->getContents();
                    Cache::remember($episodeCacheKey, $episodeJson);
                } else {
                    $episodeJson = Cache::get($episodeCacheKey);
                }
                $episode = json_decode($episodeJson);

                $cards[] = $this->createCharacterCard($card, $episode->name);
            }
            return $cards;
        }
        return null;
    }

    private function createCharacterCard(stdClass $card, $episodeName): CharactersCard
    {
        return new CharactersCard(
            $card->id,
            $card->name,
            $card->status,
            $card->species,
            $card->gender,
            $card->origin->name,
            $card->location->name,
            new Episode($episodeName),
            $card->image
        );
    }
}



