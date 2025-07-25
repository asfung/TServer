<?php

namespace App\DTO;

class UserDTO{
    private $user_id;
    private $display_name;
    private $username;
    private $email;
    private $profile_image;
    private $bio;
    private $address;
    private $password;
    private $roleId;
    private $q;

    private $old_password;
    private $new_password;

    // credentials

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

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of password
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of roleId
     */ 
    public function getRoleId()
    {
        return $this->roleId;
    }

    /**
     * Set the value of roleId
     *
     * @return  self
     */ 
    public function setRoleId($roleId)
    {
        $this->roleId = $roleId;

        return $this;
    }

    /**
     * Get the value of q
     */ 
    public function getQ()
    {
        return $this->q;
    }

    /**
     * Set the value of q
     *
     * @return  self
     */ 
    public function setQ($q)
    {
        $this->q = $q;

        return $this;
    }

    /**
     * Get the value of address
     */ 
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set the value of address
     *
     * @return  self
     */ 
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get the value of old_password
     */ 
    public function getOld_password()
    {
        return $this->old_password;
    }

    /**
     * Set the value of old_password
     *
     * @return  self
     */ 
    public function setOld_password($old_password)
    {
        $this->old_password = $old_password;

        return $this;
    }

    /**
     * Get the value of new_password
     */ 
    public function getNew_password()
    {
        return $this->new_password;
    }

    /**
     * Set the value of new_password
     *
     * @return  self
     */ 
    public function setNew_password($new_password)
    {
        $this->new_password = $new_password;

        return $this;
    }
}




