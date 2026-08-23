<?php
class Signatory {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    public function getSignatories(){
        $this->db->query('SELECT * FROM signatories ORDER BY is_default DESC, id ASC');
        return $this->db->resultSet();
    }

    public function getDefaultSignatory(){
        $this->db->query('SELECT * FROM signatories WHERE is_default = 1 LIMIT 1');
        $row = $this->db->single();
        if(!$row){
            $this->db->query('SELECT * FROM signatories ORDER BY id ASC LIMIT 1');
            $row = $this->db->single();
        }
        return $row;
    }

    public function getSignatoryById($id){
        $this->db->query('SELECT * FROM signatories WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function addSignatory($data){
        if(!empty($data['is_default'])){
            $this->resetDefaultSignatory();
        }

        $this->db->query('INSERT INTO signatories (name, title, signature_image, is_default) VALUES (:name, :title, :signature_image, :is_default)');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':title', $data['title'] ?? NULL);
        $this->db->bind(':signature_image', $data['signature_image'] ?? NULL);
        $this->db->bind(':is_default', !empty($data['is_default']) ? 1 : 0);

        if($this->db->execute()){
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function updateSignatory($id, $data){
        if(!empty($data['is_default'])){
            $this->resetDefaultSignatory();
        }

        if(array_key_exists('signature_image', $data) && $data['signature_image'] !== null){
            $this->db->query('UPDATE signatories SET name = :name, title = :title, signature_image = :signature_image, is_default = :is_default WHERE id = :id');
            $this->db->bind(':signature_image', $data['signature_image']);
        } else {
            $this->db->query('UPDATE signatories SET name = :name, title = :title, is_default = :is_default WHERE id = :id');
        }

        $this->db->bind(':name', $data['name']);
        $this->db->bind(':title', $data['title'] ?? NULL);
        $this->db->bind(':is_default', !empty($data['is_default']) ? 1 : 0);
        $this->db->bind(':id', $id);

        return $this->db->execute();
    }

    public function setDefaultSignatory($id){
        $this->resetDefaultSignatory();
        $this->db->query('UPDATE signatories SET is_default = 1 WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function deleteSignatory($id){
        // Get the signatory first to see if it was default
        $sig = $this->getSignatoryById($id);
        $wasDefault = ($sig && $sig->is_default == 1);

        $this->db->query('DELETE FROM signatories WHERE id = :id');
        $this->db->bind(':id', $id);

        if($this->db->execute()){
            if($wasDefault){
                // Set first remaining signatory as default
                $this->db->query('UPDATE signatories SET is_default = 1 ORDER BY id ASC LIMIT 1');
                $this->db->execute();
            }
            return true;
        }
        return false;
    }

    private function resetDefaultSignatory(){
        $this->db->query('UPDATE signatories SET is_default = 0');
        $this->db->execute();
    }
}
