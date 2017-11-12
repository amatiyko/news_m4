<?php

class Page extends Model {

    public function getList()
    {
        $sql = "SELECT * FROM categories";

        return $this->db->query($sql);
    }

    public function getArticlesList($category = null)
    {
        $sql = (is_null($category)) ? "SELECT * FROM articles ORDER BY date DESC" : "SELECT * FROM articles WHERE category = '{$category}' ORDER BY date DESC LIMIT 5 ";
        return $this->db->query($sql);
    }

    public function getArticle($id)
    {
        $sql = "SELECT * FROM articles WHERE id = '{$id}'";
        $result = $this->db->query($sql);
        return isset($result[0]) ? $result[0] : null;
    }
    public function getTags() {
        $sql = "SELECT * FROM tags";
        $result = $this->db->query($sql);
        return $result;
    }

    public function add_article($data) {
        $title = $this->db->escape($data['title']);
        $description = $this->db->escape($data['description']);
        $content = $this->db->escape($data['content']);
        $category = isset($data['category']) ? $this->db->escape($data['category']) : null;
        $image = isset($data['image']) ? 'webroot/img/'.$this->db->escape($data['image']) : null;
        $tags = isset($data['tags']) ? $data['tags'] : null;

        echo '<pre>';
        var_dump($tags);
        $sql = "
                    INSERT INTO articles
                    SET 
                    title = '{$title}',
                    description = '{$description}',
                    content = '{$content}', 
                    category = '{$category}', 
                    image = '{$image}'
                    ";

        $this->db->query($sql);
        $sql = "SELECT id FROM articles ORDER BY id DESC LIMIT 1";
        $last_added_article = $this->db->query($sql)[0]['id'];
        var_dump($last_added_article);

        foreach ($tags as $item) {
            $sql = "SELECT id FROM tags WHERE tag_name = '{$item}'";
            $tag_id = $this->db->query($sql)[0]['id'];
            $sql = "INSERT INTO tags_article (`tag_id`, `article_id`) VALUES ('{$tag_id}', '{$last_added_article}')";
            $this->db->query($sql);
        }
        return true;
    }

    public function add_category($data) {
        $category = isset($data['category']) ? $this->db->escape($data['category']) : null;
        $sql = "INSERT INTO categories SET category = '{$category}'";
        return $this->db->query($sql);
    }

    public function edit_article($data, $id) {
        $id = (int) $id;

        $title = $this->db->escape($data['title']);
        $description = $this->db->escape($data['description']);
        $content = $this->db->escape($data['content']);
        $category = isset($data['category']) ? $this->db->escape($data['category']) : null;

        $sql = "
            UPDATE articles
            SET title = '{$title}',
            description = '{$description}',
            content = '{$content}', 
            category = '{$category}',
            WHERE 
            id = '{$id}'
            ";

        return $this->db->query($sql);
    }

    public function delete($id) {
        $sql = "delete from articles where id = {$id}";
        return $this->db->query($sql);
    }
    public function getCommentsList() {
        $sql = "SELECT * FROM comments";
        $result = $this->db->query($sql);
        return $result;
    }
    public function getComments() {
        $sql = "SELECT * FROM comments WHERE published = FALSE ";
        $result = $this->db->query($sql);
        return $result;
    }
    public function getAnswers() {
        $sql = "SELECT * FROM answers WHERE published = FALSE ";
        $result = $this->db->query($sql);
        return $result;
    }

    public function approveComments($data) {
        foreach ($data as $id) {
            $sql = "UPDATE `comments` SET `published`=TRUE WHERE id = {$id}";
            $result[] = $this->db->query($sql);
        }
        return $result;
    }

    public function approveAnswers($data) {
        foreach ($data as $id) {
            $sql = "UPDATE `answers` SET `published`=TRUE WHERE id = {$id}";
            $result[] = $this->db->query($sql);
        }
        return $result;
    }

    public function topAuthors() {
        $sql = "SELECT COUNT(id) as count, author from comments GROUP BY author ORDER BY COUNT(id) DESC LIMIT 5";
        $result = $this->db->query($sql);
        return $result;
    }

    public function authorComments($author) {
        $sql = "SELECT * FROM comments WHERE author = '{$author}'";
        $result = $this->db->query($sql);
        return $result;
    }

    public function topTopics() {
        $sql = "SELECT COUNT(id) as count, topic FROM `comments` GROUP BY topic ORDER BY count DESC LIMIT 3";
        $result = $this->db->query($sql);
        return $result;
    }

    public function saveCommentChanges($data) {
        var_dump($data); die;
        $sql = "UPDATE comments SET comment = '{$data['description']}' WHERE id='{$data['comment-id']}'";
        $result = $this->db->query($sql);
        return $result;
    }

    public function getAdvert() {
        $sql = "SELECT * FROM promo";
        $result = $this->db->query($sql);
        return $result;
    }

    public function  saveAddChanges($data) {
        $price = (int) $data['price'];
        $sql = "UPDATE promo SET title = '{$data['title']}', price = '{$price}', brand = '{$data['brand']}', shop = 
'{$data['shop']}' WHERE id = '{$data['id']}'";
        $result = $this->db->query($sql);
        return $result;
    }

    public function getStyle() {
        $sql = "SELECT * FROM styles";
        return $result = $this->db->query($sql);
    }
    public function setColor($data) {
        $sql = "UPDATE styles SET in_use = 0 WHERE in_use = 1";
        $this->db->query($sql);
        $sql = "UPDATE styles SET in_use =1 WHERE bc_color = '{$data}'";
        return $this->db->query($sql);
    }
    public function newColor($data) {
        $sql = "INSERT INTO styles (`bc_color`) VALUES ('{$data}')";
        return $this->db->query($sql);
    }
}