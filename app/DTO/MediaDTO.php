<?php

namespace App\DTO;

class MediaDTO {
    private $post_id;
    private $data;

    public function getPost_id(){
        return $this->post_id;
    }

    public function setPost_id($post_id){
        $this->post_id = $post_id;
        return $this;
    }

    public function setData($data){
        $this->data = $data;
        return $this;
    }

    public function getData(){
        return $this->data;
    }

}
