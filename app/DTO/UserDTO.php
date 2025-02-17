<?php

namespace App\DTO;

class UserDTO{
    private $user_id;
    private $display_name;
    private $username;
    private $profile_image;
    private $bio;

    public function getUser_id(){
        return $this->user_id;
    }

    public function setUser_id($user_id){
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * Get the value of display_name
     */ 
    public function getDisplay_name()
    {
        return $this->display_name;
    }

    /**
     * Set the value of display_name
     *
     * @return  self
     */ 
    public function setDisplay_name($display_name)
    {
        $this->display_name = $display_name;

        return $this;
    }

    /**
     * Get the value of username
     */ 
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the value of username
     *
     * @return  self
     */ 
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the value of profile_image
     */ 
    public function getProfile_image()
    {
        return $this->profile_image;
    }

    /**
     * Set the value of profile_image
     *
     * @return  self
     */ 
    public function setProfile_image($profile_image)
    {
        $this->profile_image = $profile_image;

        return $this;
    }

    /**
     * Get the value of bio
     */ 
    public function getBio()
    {
        return $this->bio;
    }

    /**
     * Set the value of bio
     *
     * @return  self
     */ 
    public function setBio($bio)
    {
        $this->bio = $bio;

        return $this;
    }
}




