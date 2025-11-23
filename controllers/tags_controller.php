<?php
class TagsController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Listar todas as tags
    public function listar() {
        $query = $this->db->query("SELECT * FROM tb_tags");
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar tag por ID
    public function buscarPorId($id) {
        $query = $this->db->prepare("SELECT * FROM tb_tags WHERE idTags = ?");
        $query->execute([$id]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    // Criar nova tag
    public function criar($nomeTag) {
        $query = $this->db->prepare("INSERT INTO tb_tags (nomeTag) VALUES (?)");
        $query->execute([$nomeTag]);
        return ["status" => "ok", "idNovo" => $this->db->lastInsertId()];
    }

    // Atualizar tag existente
    public function atualizar($id, $nomeTag) {
        $query = $this->db->prepare("UPDATE tb_tags SET nomeTag = ? WHERE idTags = ?");
        $query->execute([$nomeTag, $id]);
        return ["status" => "atualizado"];
    }

    // Deletar tag
    public function deletar($id) {
        $query = $this->db->prepare("DELETE FROM tb_tags WHERE idTags = ?");
        $query->execute([$id]);
        return ["status" => "deletado"];
    }
}
