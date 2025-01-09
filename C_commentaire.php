<?php
class Commentaire {
    private int $id_commentaire;
    private string $contenu;
    private DateTime $date_commentaire;
    private Client $auteur;
    private Article $article;

    public function __construct(int $id_commentaire, string $contenu, DateTime $date_commentaire, Client $auteur, Article $article) {
        $this->id_commentaire = $id_commentaire;
        $this->contenu = $contenu;
        $this->date_commentaire = $date_commentaire;
        $this->auteur = $auteur;
        $this->article = $article;
    }

    public function getIdCommentaire(): int {
        return $this->id_commentaire;
    }

    public function getContenu(): string {
        return $this->contenu;
    }

    public function setContenu(string $contenu): void {
        $this->contenu = $contenu;
    }
}
?>