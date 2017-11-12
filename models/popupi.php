<?php

class Popupi extends Model {

    public function saveSubscriber($data) {
        $username = $this->db->escape($data['name']);
        $email = $this->db->escape($data['email']);

        $sql = "INSERT INTO `subscribers`( `name`, `email`) VALUES ('{$username}', '{$email}')";
        $result = $this->db->query($sql);
        return $result;
    }
}