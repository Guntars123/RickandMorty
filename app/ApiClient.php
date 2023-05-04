<?php declare(strict_types=1);

namespace App;

use App\Models\CharactersCard;
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

    public function getAllCharacters($search, $page): ?array
    {
        try {
            $response = $this->client->get(self::BASE_URI, [
                'query' => [
                    'name' => $search,
                    'page' => $page,
                ]
            ]);
            return $this->createCollection(json_decode($response->getBody()->getContents())->results);
        } catch (GuzzleException $e) {
            return null;
        }
    }

    private function createCollection(array $fetchResults): ?array
    {
        if ($fetchResults != null) {
            $cards = [];
            foreach ($fetchResults as $card) {
                $episode = json_decode($this->client->get
                ("{$card->episode[0]}")->getBody()->getContents())->name;

                $cards[] = $this->createCharacterCard($card, $episode);
            }
            return $cards;
        }
        return null;
    }

    private function createCharacterCard(stdClass $card, string $episode): CharactersCard
    {
        return new CharactersCard(
            $card->name,
            $card->status,
            $card->species,
            $card->gender,
            $card->origin->name,
            $card->location->name,
            $episode,
            $card->image
        );
    }
}



