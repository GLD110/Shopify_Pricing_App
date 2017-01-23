<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Order extends MY_Controller {
  
  private $_fulfillment_status = 'pending';
    
  public function __construct() {
    parent::__construct();
    $this->load->model( 'Order_model' );
    
    // Define the search values
    $this->_searchConf  = array(
      'customer_name' => '',
      'company' => '',
      'address' => '',
      'date' => date( 'Y-m-01 - Y-m-31' ),
      'page_size' => $this->config->item('PAGE_SIZE'),
      'sort_field' => 'created_at',
      'sort_direction' => 'DESC',
    );
    $this->_searchSession = 'order_sel_calendar';
  }
  
  public function index(){
    $this->is_logged_in();
    
    $this->pending();
  }

  public function pending( $page = 0 ){
    $this->is_logged_in();
    $this->_fulfillment_status = 'pending';
    $this->manage();
  }
  
  public function completed( $page = 0 ){
    $this->is_logged_in();
    $this->_fulfillment_status = 'completed';
    $this->manage();
  }
      
  public function cancelled( $page = 0 ){
    $this->is_logged_in();
    $this->_fulfillment_status = 'cancelled';
    $this->manage();
  }

  public function manage( $page =  0 ){
    // Check the login
    $this->is_logged_in();

    // Init the search value
    $this->initSearchValue();

    // Get data
    $arrCondition =  array(
      'customer_name' => $this->_searchVal['customer_name'],
      'company' => $this->_searchVal['company'],
      'address' => $this->_searchVal['address'],
      'fulfillment_status' => $this->_fulfillment_status,
      'page_number' => $page,
      'page_size' => $this->_searchVal['page_size'],              
      'sort' => $this->_searchVal['sort_field'] . ' ' . $this->_searchVal['sort_direction'],
    );
    $data['query'] =  $this->Order_model->getList( $arrCondition );
    $data['total_count'] = $this->Order_model->getTotalCount();
    $data['page'] = $page;
    $data['fulfillment_status'] = $this->_fulfillment_status;
    
    // Define the rendering data
    $data = $data + $this->setRenderData();
    
    // Load Pagenation
    $this->load->library('pagination');

    // Renter to view
//        $view_product_list = $this->load->view( 'view_product_list', $data, true );
    
    $this->load->view('view_header');
    $this->load->view('view_order', $data );
    $this->load->view('view_footer');
  }
  
  public function calendar(){
    // Check the login
    $this->is_logged_in();

    // Init the search value
    $this->initSearchValue();

    // Get data
    $arr = explode( '-', $this->_searchVal['date'] );
    $from = trim( $arr[0] ) . '-' . trim( $arr[1] ) . '-' . trim( $arr[2] );
    $to = trim( $arr[3] ) . '-' . trim( $arr[4] ) . '-' . trim( $arr[5] );
    
    $arrCondition =  array(
      'fulfillment_status' => $this->_searchVal['fulfillment_status'],
      'delivery_from' => $from,
      'delivery_to' => $to,
    );
    $data['query'] =  $this->Order_model->getList( $arrCondition );
    
    // Define the rendering data
    $data = $data + $this->setRenderData();
    
    $this->load->view('view_header');
    $this->load->view('view_calendar', $data );
    $this->load->view('view_footer');
  }
  
  public function delivery_report(){
    // Check the login
    $this->is_logged_in();

    // Init the search value
    $this->initSearchValue();

    // Get data
    $from = $this->_searchVal['delivery_report_date'];
    $to = $this->_searchVal['delivery_report_date'];
    
    $arrCondition =  array(
      'fulfillment_status' => $this->_searchVal['fulfillment_status'],
      'delivery_from' => $from,
      'delivery_to' => $to,
      'data' => true,
    );
    $query =  $this->Order_model->getList( $arrCondition );
    
    // Sort the data
    $arrData = array();
    $index = 0;
    if( $query->num_rows() > 0 )
    foreach( $query->result() as $row ){
      $index ++;
      $arr = explode( '~', $row->delivery_time );
      $strTime = strtotime( $arr[0] ) . '_' . $index;
      $arrData[ $strTime ] = $row;
    }
    ksort( $arrData );
    
    $data['arrData'] =  $arrData;
    
    // Define the rendering data
    $data = $data + $this->setRenderData();
    
    $this->load->view('view_header');
    $this->load->view('view_delivery_report', $data );
    $this->load->view('view_footer');
  }
  
  public function production_report(){
    // Check the login
    $this->is_logged_in();

    // Get the list of products
    $arrProduct = array();
    $this->load->model( 'Product_model' );
    $query = $this->Product_model->getList( array() );
    
    if( $query->num_rows() > 0 )
    foreach( $query->result() as $row ) $arrProduct[ '_' . $row->variant_id ] = $row->categories;
    
    // Init the search value
    $this->initSearchValue();

    // Get data
    $from = $this->_searchVal['production_report_date'];
    $to = $this->_searchVal['production_report_date'];
    
    $arrCondition =  array(
      'fulfillment_status' => $this->_searchVal['fulfillment_status'],
      'delivery_from' => $from,
      'delivery_to' => $to,
      'data' => true,
    );
    $query =  $this->Order_model->getList( $arrCondition );
    
    // Make the matrix
    $result = array(
      'products' => array(),
      'orders' => array(),
    );
    
    if( $query->num_rows() > 0 )
    foreach( $query->result() as $row )
    {
      $arr = explode( '~', $row->delivery_time );
      $item = array(
        'id' => $row->id,
        'order_name' => $row->order_name,
        'company' => $row->company,
      );
      
      // products
      $objData = json_decode( base64_decode( $row->data ) );
      foreach( $objData->line_items as $line_item )
      {
        // Make the product array
        if( !array_key_exists( '_' . $line_item->variant_id, $result['products'] ) ) 
        {
          $category = isset( $arrProduct[ '_' . $line_item->variant_id ] ) ? $arrProduct[ '_' . $line_item->variant_id ] : 'ZZZ';
          $result['products'][ $category . '_' . $line_item->variant_id ] = array(
            'name' => $line_item->title . ( trim($line_item->variant_title) == '' ? '' : ' - ' . $line_item->variant_title ),
            'category' => $category,
          );  
        }
        
        // Make the products for order
        $item['products'][ '_' . $line_item->variant_id] = $line_item->quantity;
        
        // Add to order list
        $result['orders'][ strtotime( $arr[0] ) . '_' . trim( $arr[0] ) . '_' . $row->order_id ] = $item;
      }
    }
    
    // Repordt arrays
    ksort( $result['orders'] );
    ksort( $result['products'] );
    
    // Send to view
    $data['result'] = $result;

    // Define the rendering data
    $data = $data + $this->setRenderData();
    
    $this->load->view('view_header');
    $this->load->view('view_production_report', $data );
    $this->load->view('view_footer');
  }
    
  public function detail( $id ){
    // Check the login
    $this->is_logged_in();

    $data['row'] =  $this->Order_model->getInfo( $id );
    
    $this->load->view('view_header');
    $this->load->view('view_order_detail', $data );
    $this->load->view('view_footer');
  }
  
  public function sync()
  {
    $this->load->model( 'Shopify_model' );
    
    // Get the lastest day
    $last_day = $this->Order_model->getLastOrderDate();
    
    $param = 'status=any';
    if( $last_day != '' ) $param .= '&limit=250&processed_at_min=' . urlencode( $last_day );
    $action = 'orders.json?' . $param;

    // Retrive Data from Shop
    $orderInfo = $this->Shopify_model->accessAPI( $action );
    
    foreach( $orderInfo->orders as $order )
    {
      $this->Order_model->add( $order );
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
    $this->Order_model->update( $pk, $data );
  }
  
  // Update the delivery date /  time
  public function updateDelivery( $pk )
  {
    $data = array(
      'delivery_date' => date( 'Y-m-d', strtotime($this->input->post('delivery_date'))),
      'delivery_time' => $this->input->post('delivery_time'),
    );
    
    $this->Order_model->update( $pk, $data );
  }
  
  // Map
  public function map(){

    // Init the search value
    $this->initSearchValue();

    // Get data
    $from = $this->_searchVal['delivery_report_date'];
    $to = $this->_searchVal['delivery_report_date'];
    
    $arrCondition =  array(
      'fulfillment_status' => $this->_searchVal['fulfillment_status'],
      'delivery_from' => $from,
      'delivery_to' => $to,
      'data' => true,
    );
    $query =  $this->Order_model->getList( $arrCondition );
    
    // Sort the data
    $arrData = array();
    $index = 0;
    if( $query->num_rows() > 0 )
    foreach( $query->result() as $row ){
      $index ++;
      $arr = explode( '~', $row->delivery_time );
      $strTime = strtotime( $arr[0] ) . '_' . $index;
      $arrData[ $strTime ] = $row;
    }
    ksort( $arrData );
    
    $data['arrData'] =  $arrData;
    
    // Define the rendering data
    $data = $data + $this->setRenderData();
    
    $this->load->view('view_map', $data );
  }  
}            

