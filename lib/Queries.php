<?php

class Queries extends MySQL {

    static public function get_queue_size() {
        $query = parent::getInstance()->query("SELECT count(id) as count FROM queue");
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    static public function get_operation_counts() {
        $query = parent::getInstance()->query("SELECT type, count FROM operations WHERE type='send' or type='receive' ORDER BY type ASC");
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    static public function get_stats() {
        $stats = array('size' => 0, 'sends' => 0, 'receives' => 0);
        $stats['size'] = self::get_queue_size();
        $operation_counts = self::get_operation_counts();
        $stats['receives'] = $operation_counts[0]['count'];
        $stats['sends'] = $operation_counts[1]['count'];
        return $stats;
    }

    static public function log_operation($operation_type) {
        $query = parent::getInstance()->prepare("UPDATE operations SET count=count+1 WHERE type = :operation_type");
        $query->bindValue(':operation_type', $operation_type, PDO::PARAM_STR);
        $query->execute();
    }

    static public function receive_payload() {
        $query = parent::getInstance()->query("SELECT id, payload FROM queue ORDER BY id ASC LIMIT 1");
        $queued_payload = $query->fetch(PDO::FETCH_ASSOC);
        if ($queued_payload) {
            $query = parent::getInstance()->prepare("DELETE FROM queue WHERE id = :id");
            $query->bindValue(':id', $queued_payload['id'], PDO::PARAM_INT);
            $query->execute();
           Queries::log_operation('receive');
            return $queued_payload['payload'];
        } else {
            return null;
        }
    }

    static public function send_payload($payload) {
        $query = parent::getInstance()->prepare("INSERT INTO queue (payload) VALUES (:payload)");
        $query->bindValue(':payload', $payload, PDO::PARAM_STR);
        $query->execute();
        self::log_operation('send');
    }

}