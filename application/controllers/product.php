<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product extends MY_Controller {
    
  public function __construct() {
    parent::__construct();
    $this->load->model( 'Product_model' );
    $temp = $this->load->model( 'Collection_model' );  
        
    // Define the search values
    $this->_searchConf  = array(
        'name' => '',
        'sku' => '',
        'tier' => '',
        'collection' => '',
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
         'tier' => $this->_searchVal['tier'],
         'collection' => $this->_searchVal['collection'],
         'sort' => $this->_searchVal['sort_field'] . ' ' . $this->_searchVal['sort_direction'],
         'page_number' => $page,
         'page_size' => $this->_searchVal['page_size'],              
    );

    $data['query'] =  $this->Product_model->getList( $arrCondition );
    $data['total_count'] = $this->Product_model->getTotalCount();
    $data['page'] = $page;
    $temp = $this->Collection_model->getList();
    $collection[] = '';
    foreach($temp as $key=>$value){
        $collection[$key] = $value;
    }
    $data['collection'] = $collection;
      
    // Define the rendering data
    $data = $data + $this->setRenderData();
    
    //var_dump($this->_searchConf);  exit;  
    
    // Load Pagenation
    $this->load->library('pagination');

    $this->load->view('view_header');
    $this->load->view('view_product', $data );
    $this->load->view('view_footer');
  }
  
  public function sync( $page = 1 )
  {
    $this->load->model( 'Shopify_model' );
    $this->load->model( 'Collection_model' );
    
    // Get the lastest day
    $last_day = $this->Product_model->getLastUpdateDate();
      
    // Retrive Data from Shop
    $count = 0;

    // Make the action with update date or page
    $action = 'products.json?';
    if( $last_day != '' && $last_day != $this->config->item('CONST_EMPTY_DATE'))
    {
      $action .= 'updated_at_min=' . urlencode( $last_day );
    }
      
    // Retrive Data from Shop
    $productInfo = $this->Shopify_model->accessAPI( $action );
      
    var_dump($productInfo->products[0]);   exit;
                    
    // Store to database
    if( isset($productInfo->products) && is_array($productInfo->products) )
    {
      foreach( $productInfo->products as $product )
      {
        $action = 'collects.json?';
        if( $last_day != '' && $last_day != $this->config->item('CONST_EMPTY_DATE'))
        {
          $action .= 'updated_at_min=' . urlencode( $last_day );
        }
        
        $action .= '&product_id=' . $product->id;
        $collectionInfo = $this->Shopify_model->accessAPI( $action );
          
        $this->Product_model->addProduct( $product, $collectionInfo );
      }
    }
      
    //get collections  
    $last_day = $this->Collection_model->getLastUpdateDate();  
    $action1 = 'custom_collections.json?';
    $action2 = 'smart_collections.json?';  
      
    if( $last_day != '' && $last_day != $this->config->item('CONST_EMPTY_DATE'))
    {
      $action1 .= 'updated_at_min=' . urlencode( $last_day );
      $action2 .= 'updated_at_min=' . urlencode( $last_day );    
    }
            
    $collectionInfo1 = $this->Shopify_model->accessAPI( $action1 );
                
    if($collectionInfo1 != null)  
        $this->Collection_model->addCollection( $collectionInfo1 );  
      
    $collectionInfo2 = $this->Shopify_model->accessAPI( $action2 );
      
    if($collectionInfo2 != null)  
        $this->Collection_model->addCollection( $collectionInfo2 );  
      
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
    
  //Price update for ajax calling
  public function update(){
    
    $this->load->model( 'Shopify_model' );
    
    //data from GET  
    $id = $this->input->get('id');
    $variant_id = $this->input->get('variant_id');
    $price = $this->input->get('price');
     
    // Make the action with update date or page
    $action = 'variants/';
    if( isset($variant_id))
    {
      $action .= $variant_id . '.json';
    }
    
    $arrParam = array('variant'=>array('id'=>$variant_id, 'price'=>$price));  
          
    // Retrive Data from Shop
    $variantInfo = $this->Shopify_model->accessAPI( $action, $arrParam, 'PUT' );  

    //update the db
    if($variantInfo == null){
        echo 'fail';
    }
    else
    {
        $this->Product_model->updatePrice($id, $variantInfo);  
        echo 'success';            
    }
  }    
}            

