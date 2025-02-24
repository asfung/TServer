<?php
namespace App\DTO;

class RoleDTO {
    private $roleId;
    private $permissionId;
    private $resourceId;

    // permission 
    private $key;
    private $name;
    private $endpoint;

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
     * Get the value of permissionId
     */ 
    public function getPermissionId()
    {
        return $this->permissionId;
    }

    /**
     * Set the value of permissionId
     *
     * @return  self
     */ 
    public function setPermissionId($permissionId)
    {
        $this->permissionId = $permissionId;

        return $this;
    }

    /**
     * Get the value of resourceId
     */ 
    public function getResourceId()
    {
        return $this->resourceId;
    }

    /**
     * Set the value of resourceId
     *
     * @return  self
     */ 
    public function setResourceId($resourceId)
    {
        $this->resourceId = $resourceId;

        return $this;
    }

    /**
     * Get the value of key
     */ 
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set the value of key
     *
     * @return  self
     */ 
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of endpoint
     */ 
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * Set the value of endpoint
     *
     * @return  self
     */ 
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;

        return $this;
    }
}