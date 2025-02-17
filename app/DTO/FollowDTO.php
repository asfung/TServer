<?php

namespace App\DTO;

class FollowDTO {
    private $user_id_follower;
    private $user_id_followed;

    public function setUser_id_follower($user_id_follower){
        $this->user_id_follower = $user_id_follower;
        return $this;
    }

    public function setUser_id_followed($user_id_followed){
        $this->user_id_followed = $user_id_followed;
        return $this;
    }

    public function getUser_id_follower(){
        return $this->user_id_follower;
    }

    public function getUser_id_followed(){
        return $this->user_id_followed;
    }

}




