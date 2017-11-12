<?php

Class Tag extends Model {
    public function getTagArticles($tag) {
        $sql = "SELECT id FROM tags WHERE tag_name = '{$tag}'";
        $tag_id = $this->db->query($sql)[0]['id'];
        $sql = "SELECT * FROM `articles` JOIN tags_article ON id = tags_article.article_id WHERE tags_article.tag_id = '{$tag_id}'";
        return $this->db->query($sql);
    }
}