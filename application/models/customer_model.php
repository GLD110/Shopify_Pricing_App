<?php
class Customer_model extends Master_model
{
  protected $_tablename = 'customer';
  private $_total_count = 0;
  
  function __construct() {
      parent::__construct();
  }

  public function getTotalCount(){ return $this->_total_count; }
  
  public function getList( $arrCondition )
  {
      $where = array();

      // Build the where clause
      $where['shop'] = $this->_shop;
      //with tier
      if( !empty( $arrCondition['tier'] ) ) $where["tags LIKE '%" . str_replace( "'", "\\'", $arrCondition['tier'] ) . "%'"] = '';      
      if( !empty( $arrCondition['customer_name'] ) ) $where["customer_name LIKE '%" . str_replace( "'", "\\'", $arrCondition['customer_name'] ) . "%'"] = '';
      if( !empty( $arrCondition['address'] ) ) $where["address LIKE '%" . str_replace( "'", "\\'", $arrCondition['address'] ) . "%'"] = '';
      if( !empty( $arrCondition['company'] ) ) $where["company LIKE '%" . str_replace( "'", "\\'", $arrCondition['company'] ) . "%'"] = '';

      $get_customer = isset($arrCondition['data']) && $arrCondition['data']  ? ', data' : '';

      // Get the count of records
      foreach( $where as $key => $val )
      if( $val == '' )
          $this->db->where( $key );
      else
          $this->db->where( $key, $val );
      $query = $this->db->get( $this->_tablename);
      $this->_total_count = $query->num_rows();
        
      // Sort
      if( isset( $arrCondition['sort'] ) ) $this->db->order_by( $arrCondition['sort'] );
      $this->db->order_by( 'updated_at', 'DESC' );

      // Limit
      if( isset( $arrCondition['page_number'] ) )
      {
          $page_size = isset( $arrCondition['page_size'] ) ? $arrCondition['page_size'] : $this->config->item('PAGE_SIZE');
          $this->db->limit( $page_size, $arrCondition['page_number'] );
      }
      
      foreach( $where as $key => $val )
      if( $val == '' )
          $this->db->where( $key );
      else
          $this->db->where( $key, $val );
      $query = $this->db->get( $this->_tablename );
      
      return $query;
  }
  
  // Get the lastest order date
  public function getLastCustomerDate()
  {
      $return = '';
      
      $this->db->select( 'updated_at' );
      $this->db->order_by( 'updated_at DESC' );
      $this->db->limit( 1 );
      $this->db->where( 'shop', $this->_shop );
      
      $query = $this->db->get( $this->_tablename );
      
      if( $query->num_rows() > 0 )
      {
          $res = $query->result();
          
          $return = $res[0]->updated_at;
      }
      
      return $return;
  }
  
  /**
  * Add order and check whether it's exist already
  * 
  * @param mixed $order
  */
  public function add( $customer )
  {
    // Check the order is exist already
    $query = parent::getList('customer_id=' . $customer->id );
    if( $query->num_rows() > 0 ) return false;

    // Load Models
//    $CI =& get_instance();
//    $CI->load->model( 'Shopify_model' );
        
    // Get Order Information      
    $objAddress = isset( $customer->default_address ) ? $customer->default_address : $customer->addresses[0];
    $address = '';
    if( $objAddress->address1 != '' ) $address .= ', ' . $objAddress->address1;
    if( $objAddress->address2 != '' ) $address .= ', ' . $objAddress->address2;
    if( $objAddress->city != '' ) $address .= ', ' . $objAddress->city;
    if( $objAddress->province != '' ) $address .= ', ' . $objAddress->province;
    if( $objAddress->country != '' ) $address .= ', ' . $objAddress->country;
    if( $objAddress->zip != '' ) $address .= ', ' . $objAddress->zip;
    if( $address != '' ) $address = substr( $address, 2 );
      
    // Insert data
    $data = array(
        'customer_id' => $customer->id,
        'customer_name' => $customer->first_name . ' ' . $customer->last_name,
        'email' => $customer->email,
        'updated_at' => $customer->updated_at,
        'note' => isset( $customer->note ) ? $customer->note : '',
        'total_spent' => $customer->total_spent,
        'orders_count' => $customer->orders_count,
        'company' => $objAddress->company,
        'company_num' => '',
        'company_doc' => '',
        'tags' => $customer->tags,
        'address' => $address,
     );

    parent::add( $data );
    
    return true;
  }
  
  public function cancel( $order )
  {
    $this->db->where( 'shop', $this->_shop );
    $this->db->where( 'order_id', $order->id );
    $this->db->update( $this->_tablename, array( 'fulfillment_status' => 'cancelled'));
  }
  
  // Get order object fron order_id
  public function getOrderObject( $order_name )
  {
    $query = parent::getList( 'order_name = \'' . $order_name . '\'' );
    if( $query->num_rows() > 0 )
    foreach( $query->result() as $row )
    {
      return json_decode( base64_decode($row->data ));
    }
    
    return '';
  }
  
  // Get the line item_item Id from sku
  public function getLineItemId( $order, $sku )
  {
    foreach( $order->line_items as $line_item )
    {
      if( $line_item->sku == $sku ) return $line_item->id;
    }
    
    return '';
  }
  // ********************** //
}  
?>
