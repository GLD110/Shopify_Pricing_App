<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer extends MY_Controller {
  
  public function __construct() {
    parent::__construct();
    $this->load->model( 'Customer_model' );
    
    // Define the search values
    $this->_searchConf  = array(
      'tier' => '',    
      'customer_name' => '',
      'company' => '',
      'address' => '',
      'page_size' => $this->config->item('PAGE_SIZE'),
      'sort_field' => 'updated_at',
      'sort_direction' => 'DESC',
    );
    $this->_searchSession = 'customer_app_page';
  }
  
  public function index(){
    $this->is_logged_in();
        
    $this->manage();
  }

  public function manage( $page =  0 ){
    // Check the login
    $this->is_logged_in();

    // Init the search value
    $this->initSearchValue();

    // Get data
    $arrCondition =  array(
      'tier' => $this->_searchVal['tier'],
      'customer_name' => $this->_searchVal['customer_name'],
      'company' => $this->_searchVal['company'],
      'address' => $this->_searchVal['address'],
      'page_number' => $page,
      'page_size' => $this->_searchVal['page_size'],              
      'sort' => $this->_searchVal['sort_field'] . ' ' . $this->_searchVal['sort_direction'],
    );
        
    $data['query'] =  $this->Customer_model->getList( $arrCondition );
    $data['total_count'] = $this->Customer_model->getTotalCount();
    $data['page'] = $page;
      
    // Define the rendering data
    $data = $data + $this->setRenderData();
    
    // Load Pagenation
    $this->load->library('pagination');

    $this->load->view('view_header');
    $this->load->view('view_customer', $data );
    $this->load->view('view_footer');
  }
    
  public function sync()
  {
    $this->load->model( 'Shopify_model' );
    
    // Get the lastest day
    $last_day = $this->Customer_model->getLastCustomerDate();
          
    $param = '';
    if( $last_day != '' ) $param .= '&limit=250&updated_at_min=' . $last_day ;
    $action = 'customers.json?' . $param;

    // Retrive Data from Shop
    $customerInfo = $this->Shopify_model->accessAPI( $action );
      
        //var_dump($customerInfo->customers[0]); exit;
    
    foreach( $customerInfo->customers as $customer )
    {
      $this->Customer_model->add( $customer );
    }
    
    echo 'success';
  }
  
  public function update( $type, $pk )
  {
    $data = array();
    
    switch( $type )
    {
        case 'fulfillment_status' : $data['fulfillment_status'] = $this->input->post('value'); break;
    }
    $this->Customer_model->update( $pk, $data );
  }
}            

