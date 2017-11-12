<?php

class Cat extends Model {

    public function getArticleList($category)
    {
        $sql = "SELECT * FROM articles WHERE category = '{$category}'";

        return $this->db->query($sql);
    }

    public function getArticle($id)
    {
        $sql = "SELECT * FROM articles WHERE id = '{$id}'";
        $result = $this->db->query($sql);
        return isset($result[0]) ? $result[0] : null;
    }

    public function getArticleTags($id) {
        $sql = "SELECT tag_name FROM tags JOIN tags_article ON tags_article.tag_id = tags.id WHERE article_id = '{$id}'";
        $result = $this->db->query($sql);
        return $result;
    }

    public function saveComment($data) {
        $article_id = $data['article_id'];
        $comment = $this->db->escape($data['comment']);
        $published = isset($data['published']) ? FALSE : TRUE;
        $author = $_SESSION['login'];
        $title = $data['title'];

        $sql = "INSERT INTO comments(`article_id`, `comment`, `published`, `author`, `topic`) VALUES ('{$article_id}',
'{$comment}','{$published}', '{$author}', '{$title}')";
        $result = $this->db->query($sql);
        return $result;
    }

    public function saveAnswer($data) {
        $comment_id = $data['comment_id'];
        $answer = $this->db->escape($data['answer']);
        $published = isset($data['published']) ? FALSE : TRUE;
        $author = $_SESSION['login'];

        $sql = "INSERT INTO `answers` (`comment_id`, `answer`, `published`, `author`) VALUES ('{$comment_id}',
'{$answer}','{$published}', '{$author}')";
        $result = $this->db->query($sql);
        return $result;
    }

    public function getComments($id) {
        $sql = "SELECT * FROM comments WHERE article_id = '{$id}' AND published = 1 ORDER BY raiting DESC";
        $result = $this->db->query($sql);
        return $result;
    }

    public function getAnswers($id) {
        $sql = "SELECT * FROM answers WHERE comment_id = '{$id}' AND published = TRUE";
        $result = $this->db->query($sql);
        return $result;
    }

    public function plusRaiting($data) {
        $sql = "SELECT raiting FROM comments WHERE id = '{$data['comment_id']}'";
        $raiting = $this->db->query($sql)[0]['raiting'];
        $raiting++;
        var_dump($raiting);
        $sql = "UPDATE comments SET raiting = '{$raiting}' WHERE id = '{$data['comment_id']}'";
        return  $this->db->query($sql);
    }
    public function minusRaiting($data) {
        $sql = "SELECT raiting FROM comments WHERE id = '{$data['comment_id']}'";
        $raiting = $this->db->query($sql)[0]['raiting'];
        $raiting--;
        var_dump($raiting);
        $sql = "UPDATE comments SET raiting = '{$raiting}' WHERE id = '{$data['comment_id']}'";
        return  $this->db->query($sql);
    }
    public function saveReads($amount, $id){
        $sql = "UPDATE articles SET read_amount = '{$amount}' WHERE id = '{$id}'";
        return  $this->db->query($sql);
    }
}


