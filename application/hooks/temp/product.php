<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product extends MY_Controller {
    
  public function __construct() {
    parent::__construct();
    $this->load->model( 'Product_model' );
    
    // Define the search values
    $this->_searchConf  = array(
        'name' => '',
        'sku' => '',
        'page_size' => $this->config->item('PAGE_SIZE'),
        'sort_field' => 'product_id',
        'sort_direction' => 'DESC',
    );
    $this->_searchSession = 'product_app_page';
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
         'name' => $this->_searchVal['name'],
         'sort' => $this->_searchVal['sort_field'] . ' ' . $this->_searchVal['sort_direction'],
         'page_number' => $page,
         'page_size' => $this->_searchVal['page_size'],              
    );
    $data['query'] =  $this->Product_model->getList( $arrCondition );
    $data['total_count'] = $this->Product_model->getTotalCount();
    $data['page'] = $page;
    
    // Define the rendering data
    $data = $data + $this->setRenderData();
    
    // Load Pagenation
    $this->load->library('pagination');

    $this->load->view('view_header');
    $this->load->view('view_product', $data );
    $this->load->view('view_footer');
  }
  
  public function sync( $page = 1 )
  {
    $this->load->model( 'Shopify_model' );
    
    // Get the lastest day
    $last_day = $this->Product_model->getLastUpdateDate();
    
    // Retrive Data from Shop
    $count = 0;

    // Make the action with update date or page
    $action = 'products.json?';
    if( $last_day != '' && $last_day != $this->config->item('CONST_EMPTY_DATE') && $page == 1 )
    {
      $action .= 'limit=250&updated_at_min=' . urlencode( $last_day );
    }
    else
    {
      $action .= 'limit=10&page=' . $page;
    } 

    // Retrive Data from Shop
    $productInfo = $this->Shopify_model->accessAPI( $action );

    // Store to database
    if( isset($productInfo->products) && is_array($productInfo->products) )
    {
      foreach( $productInfo->products as $product )
      {
        $this->Product_model->addProduct( $product );
      }
    }
    
    // Get the count of product
    if( $last_day != '' && $last_day != $this->config->item('CONST_EMPTY_DATE') && $page == 1 )
    {
      $count = 0;
    }
    else
    {
      if( isset( $productInfo->products )) $count = count( $productInfo->products );
      $page ++;  
    }

    if( $count == 0 )
      echo 'success';
    else
      echo $page . '_' . $count;
  }
}            

