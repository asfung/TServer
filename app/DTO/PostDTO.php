<?php

namespace App\DTO;

class PostDTO {
    private $user_id;
    private $content;
    private $parent_id;
    private $community_id;
    private $perPage;
    private $post_id;
    private $type;
    private $q;
    private $for;
    private $activity;
    private $media;
    private $sort;

    private $isDeep;
    private $maxDepth;
    

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

    /**
     * Get the value of type
     */ 
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @return  self
     */ 
    public function setType($type)
    {
        $this->type = $type;

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
     * Get the value of for
     */ 
    public function getFor()
    {
        return $this->for;
    }

    /**
     * Set the value of for
     *
     * @return  self
     */ 
    public function setFor($for)
    {
        $this->for = $for;

        return $this;
    }

    /**
     * Get the value of activity
     */ 
    public function getActivity()
    {
        return $this->activity;
    }

    /**
     * Set the value of activity
     *
     * @return  self
     */ 
    public function setActivity($activity)
    {
        $this->activity = $activity;

        return $this;
    }

    /**
     * Get the value of media
     */ 
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Set the value of media
     *
     * @return  self
     */ 
    public function setMedia($media)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * Get the value of sort
     */ 
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * Set the value of sort
     *
     * @return  self
     */ 
    public function setSort($sort)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * Get the value of isDeep
     */ 
    public function getIsDeep()
    {
        return $this->isDeep;
    }

    /**
     * Set the value of isDeep
     *
     * @return  self
     */ 
    public function setIsDeep($isDeep)
    {
        $this->isDeep = $isDeep;

        return $this;
    }

    /**
     * Get the value of maxDepth
     */ 
    public function getMaxDepth()
    {
        return $this->maxDepth;
    }

    /**
     * Set the value of maxDepth
     *
     * @return  self
     */ 
    public function setMaxDepth($maxDepth)
    {
        $this->maxDepth = $maxDepth;

        return $this;
    }
}
