<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Offer extends MY_Controller {
    
  public function __construct() {
    parent::__construct();
    $this->load->model('Offer_model');
    $this->load->model('Product_model');
  }
  
  public function index(){
    $this->is_logged_in();

    $this->manage();
  }

  function manage(){
    // Check the login
    $this->is_logged_in();

    // Get the list of Offer
    $data['query'] =  $this->Offer_model->getList();
    
    $this->load->view('view_header');
    $this->load->view('view_offer', $data);
    $this->load->view('view_footer');
  }
  
  function view_edit( $offer_id = 0 )
  {
    // Get the list of products
    $data['arrProduct'] = $this->Product_model->getProductIdListWithOffer();
    
    // Get the offer object
    if( $offer_id != 0 )
    {
      $data['obj'] = $this->Offer_model->getInfo( $offer_id );
    }
    $data['offer_id'] = $offer_id;
    
    $this->load->view('view_header');
    $this->load->view('view_offer_edit', $data);
    $this->load->view('view_footer');
  }
 
  function del(){
    $id = $this->input->get_post('del_id');
    $returnDelete = $this->Offer_model->delete( $id );
    if( $returnDelete === true ){
      $this->Product_model->delOffer( $id );
      $this->session->set_flashdata('falsh', '<p class="alert alert-success">One offer is deleted successfully</p>');    
    }
    else{
      $this->session->set_flashdata('falsh', '<p class="alert alert-danger">Sorry! deleted unsuccessfully : ' . $returnDelete . '</p>');    
    }
    
    redirect('offer');
    exit;
  }
 
  function add(){
    $this->form_validation->set_rules('url', 'URL', 'required');
    $this->form_validation->set_rules('title', 'Offer Title', 'required');
    $this->form_validation->set_rules('product_ids', 'Products', 'required');
    
    if ($this->form_validation->run() == FALSE){       
        echo validation_errors('<div class="alert alert-danger">', '</div>');
        exit;
    }
    else{
      // Add offer
      $offer_id = $this->Offer_model->add();
      
      // Get product ids
      $arrProductId = explode( ',', $this->input->post('product_ids') );
      
      if($offer_id){
        $this->Product_model->addOffer( $arrProductId, $offer_id, $this->input->post('when') );
        echo '<div class="alert alert-success">This offer is added successfully</div>';
        exit;
      }
      else{
        echo '<div class="alert alert-danger">Sorry ! something went wrong </div>';
        exit;
      }
    }
  }
  
  function update_offer( $offer_id ){
    $this->form_validation->set_rules('url', 'URL', 'required');
    $this->form_validation->set_rules('title', 'Offer Title', 'required');
    $this->form_validation->set_rules('product_ids', 'Products', 'required');
    
    if ($this->form_validation->run() == FALSE){       
        echo validation_errors('<div class="alert alert-danger">', '</div>');
        exit;
    }
    else{
      // update offer
      $this->Offer_model->update( $offer_id );
      
      // Get product ids
      $arrProductId = explode( ',', $this->input->post('product_ids') );
      
      if($offer_id){
        // Clear Product Ids
        $this->Product_model->delOffer( $offer_id );
        $this->Product_model->addOffer( $arrProductId, $offer_id, $this->input->post('when') );
        echo '<div class="alert alert-success">This offer is updated successfully</div>';
        exit;
      }
      else{
        echo '<div class="alert alert-danger">Sorry ! something went wrong </div>';
        exit;
      }
    }
  }  
      
  function update( $type, $pk ){
    $data = array();
    
    $data[$type] = $this->input->post('value'); break;
    $this->Offer_model->update( $pk, $data );
  }
}             