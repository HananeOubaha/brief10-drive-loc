<?php
class Favoris {
    private int $idFavori;
    private Client $client;
    private Article $article;

    public function __construct(Client $client, Article $article) {
        $this->client = $client;
        $this->article = $article;
    }

    public function getIdFavori(): int {
        return $this->idFavori;
    }

    public function getClient(): Client {
        return $this->client;
    }

    public function getArticle(): Article {
        return $this->article;
    }
}
?>