<?php

namespace App\Models;

use App\Database\Db;
use PDO;

class User extends Db
{


    public function insert($data)
    {

        $stmt = $this->db->prepare("INSERT INTO users (user_id, chat_id, data) VALUES (:user_id, :chat_id, :data)");

        $stmt->bindParam(':user_id', $data['user_id']);
        $stmt->bindParam(':chat_id', $data['chat_id']);
        $stmt->bindParam(':data', $data['data']);

        return $stmt->execute();

    }

    public function select($chatId)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE chat_id = :chat_id LIMIT 1");

        $stmt->bindParam(":chat_id", $chatId);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateAndGet($data)
    {
        $this->db->beginTransaction();

        $stmt = $this->db->prepare("UPDATE users SET data = :data WHERE id = :id");
        $stmt->execute([':data' => $data['data'], ':id' => $data['id']]);

        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $data['id']]);
        $updatedRow = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->db->commit();

        return $updatedRow;
    }

    

}