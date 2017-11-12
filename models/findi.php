<?php
class Findi extends Model {
    public function getTagsList(){
        $sql = "SELECT * FROM tags";
        return $this->db->query($sql);
    }
    public function getCategoryList() {
        $sql = "SELECT * FROM categories";
        return $this->db->query($sql);
    }

    public function getNewsByFilter($data) {
        $full_sql = null;
        $date_sql = null;

        if (isset($data['category'])) {
            $categories = $data['category'];
            foreach ($categories as $key=>$value) {
                if (count($full_sql) > 0)
                    $full_sql .= ' AND ';
                $full_sql .= " category = '{$value}' ";
            }
        }



        if (isset($data['tag'])) {
            $tags = $data['tag'];
            foreach ($tags as $key=>$value) {
                $tag_sql = "SELECT * FROM tags WHERE tag_name = '{$value}'";
                $tag_id = $this->db->query($tag_sql)[0]['id'];
                if (count($full_sql) > 0)
                    $full_sql .= ' AND ';
                $full_sql .= " tag_id = '{$tag_id}' ";
            }
        }


        if($data['date_ot'] != 0 && $data['date_do'] != 0 ) {
            $date_sql = " BETWEEN '{$data['date_ot']}' AND'{$data['date_do']}'";
        } elseif ($data['date_ot'] != 0) {
            if (count($full_sql) > 0)
                $full_sql .= ' AND ';
            $full_sql .= " date = '{$data['date_ot']}'";
        } elseif ($data['date_do'] != 0) {
            if (count($full_sql) > 0)
                $full_sql .= ' AND ';
            $full_sql .= " date = '{$data['date_ot']}'";
        }

        if (count($full_sql) > 0)
            $where = ' WHERE ';

        $sql = "SELECT * FROM articles LEFT JOIN tags_article ON articles.id = tags_article.article_id".$where
            .$full_sql.$date_sql;

        $result = $this->db->query($sql);
        return $result;
    }



}