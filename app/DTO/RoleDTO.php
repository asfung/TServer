<?php
namespace App\DTO;

class RoleDTO {
    private $roleId;
    private $permissionId;
    private $resourceId;
    private $mode;

    // permission 
    private $key;
    private $name;
    private $endpoint;

    // resource
    private $iconSolid;
    private $iconOutlined;
    private $path;

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

    /**
     * Get the value of mode
     */ 
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * Set the value of mode
     *
     * @return  self
     */ 
    public function setMode($mode)
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * Get the value of iconSolid
     */ 
    public function getIconSolid()
    {
        return $this->iconSolid;
    }

    /**
     * Set the value of iconSolid
     *
     * @return  self
     */ 
    public function setIconSolid($iconSolid)
    {
        $this->iconSolid = $iconSolid;

        return $this;
    }

    /**
     * Get the value of iconOutlined
     */ 
    public function getIconOutlined()
    {
        return $this->iconOutlined;
    }

    /**
     * Set the value of iconOutlined
     *
     * @return  self
     */ 
    public function setIconOutlined($iconOutlined)
    {
        $this->iconOutlined = $iconOutlined;

        return $this;
    }

    /**
     * Get the value of path
     */ 
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set the value of path
     *
     * @return  self
     */ 
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }
}