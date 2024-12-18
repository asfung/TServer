<?php

namespace App\DTO;

class PostDTO {
    private $user_id;
    private $content;
    private $parent_id;
    private $community_id;
    private $perPage;
    private $post_id;
    

    /**
     * Get the value of user_id
     */ 
    public function getUser_id()
    {
        return $this->user_id;
    }

    /**
     * Set the value of user_id
     *
     * @return  self
     */ 
    public function setUser_id($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * Get the value of content
     */ 
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set the value of content
     *
     * @return  self
     */ 
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get the value of parent_id
     */ 
    public function getParent_id()
    {
        return $this->parent_id;
    }

    /**
     * Set the value of parent_id
     *
     * @return  self
     */ 
    public function setParent_id($parent_id)
    {
        $this->parent_id = $parent_id;

        return $this;
    }

    /**
     * Get the value of community_id
     */ 
    public function getCommunity_id()
    {
        return $this->community_id;
    }

    /**
     * Set the value of community_id
     *
     * @return  self
     */ 
    public function setCommunity_id($community_id)
    {
        $this->community_id = $community_id;

        return $this;
    }

    /**
     * Get the value of perPage
     */ 
    public function getPerPage()
    {
        return $this->perPage;
    }

    /**
     * Set the value of perPage
     *
     * @return  self
     */ 
    public function setPerPage($perPage)
    {
        $this->perPage = $perPage;

        return $this;
    }

    /**
     * Get the value of post_id
     */ 
    public function getPost_id()
    {
        return $this->post_id;
    }

    /**
     * Set the value of post_id
     *
     * @return  self
     */ 
    public function setPost_id($post_id)
    {
        $this->post_id = $post_id;

        return $this;
    }
}
