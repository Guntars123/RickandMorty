<?php declare(strict_types=1);

namespace App;

use App\Models\CharactersCard;
use App\Models\Episode;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use stdClass;

class ApiClient
{
    private Client $client;
    private const BASE_URI = 'https://rickandmortyapi.com/api/character/';

    public function __construct()
    {
        $this->client = new Client();
    }

    public function getAllCharacters(string $search, string $page): ?array
    {
        try {
            if (!Cache::has('characters' . $page . $search)) {
                $response = $this->client->get(self::BASE_URI, [
                    'query' => [
                        'name' => $search,
                        'page' => $page,
                    ]
                ]);

                $responseJson = $response->getBody()->getContents();

                Cache::remember('characters' . $page . $search, $responseJson);
            } else {
                $responseJson = Cache::get('characters' . $page . $search);
            }

            return $this->createCollection(json_decode($responseJson)->results);

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



