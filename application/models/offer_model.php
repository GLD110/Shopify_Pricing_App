<?php
class Offer_model extends Master_model
{
  protected $_tablename = 'offer';
  protected $_tablename_product = 'product';
  
  function __construct() {
      parent::__construct();
  }
  
  public function add(){
    $data = array(
        'title' => $this->input->post('title'),
        'when' => $this->input->post('when'),
        'url' => $this->input->post('url'),
        'create_date' => date( $this->config->item('CONST_DATE_FORMAT')),
    );
    
    parent::add( $data );
    return $this->db->insert_id();
  }
  
  public function update( $offer_id ){
    $data = array(
        'title' => $this->input->post('title'),
        'when' => $this->input->post('when'),
        'url' => $this->input->post('url'),
        'create_date' => date( $this->config->item('CONST_DATE_FORMAT')),
    );
    
    parent::update( $offer_id, $data );
  }  
}  
?>