<?php
class Tag {
    private int $id_tag;
    private string $nom_tag;

    public function __construct(int $id_tag, string $nom_tag) {
        $this->id_tag = $id_tag;
        $this->nom_tag = $nom_tag;
    }

    public function getIdTag(): int {
        return $this->id_tag;
    }

    public function getNomTag(): string {
        return $this->nom_tag;
    }

    public function setNomTag(string $nom_tag): void {
        $this->nom_tag = $nom_tag;
    }
}
?>
