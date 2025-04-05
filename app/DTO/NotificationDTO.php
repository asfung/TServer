<?php

namespace App\DTO;

class NotificationDTO {

  private $page;
  private $per_page;
  private $limit;


  /**
   * Get the value of per_page
   */ 
  public function getPer_page()
  {
    return $this->per_page;
  }

  /**
   * Set the value of per_page
   *
   * @return  self
   */ 
  public function setPer_page($per_page)
  {
    $this->per_page = $per_page;

    return $this;
  }

  /**
   * Get the value of limit
   */ 
  public function getLimit()
  {
    return $this->limit;
  }

  /**
   * Set the value of limit
   *
   * @return  self
   */ 
  public function setLimit($limit)
  {
    $this->limit = $limit;

    return $this;
  }

  /**
   * Get the value of page
   */ 
  public function getPage()
  {
    return $this->page;
  }

  /**
   * Set the value of page
   *
   * @return  self
   */ 
  public function setPage($page)
  {
    $this->page = $page;

    return $this;
  }
}
