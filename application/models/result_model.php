<?php
class Result_model extends Master_model
{
  protected $_tablename_purchase = 'result_purchase';
  protected $_tablename_view = 'result_view';
  protected $_tablename_order = 'orderlist';
  protected $_tablename_product = 'product';
  
  function __construct() {
      parent::__construct();
  }
  
  public function getStatis( $from = '', $to = '' )
  {
    $arrReturn = array(
      'totalPurchase' => 0,
      'totalPurchaseOffer' => 0,
      'arrOrder' => array(),
      'numCart' => 0,
      'numCheckout' => 0,
      'arrView' => array(),
      'arrOffer' => array(),
      'arrLine' => array(),
    );
    
    // Get the offer list
    $arrOffer = array();
    $CI =& get_instance();
    $CI->load->model( 'Offer_model' );
    
    $query = $CI->Offer_model->getList();
    if( $query->num_rows > 0 )
    foreach( $query->result() as $row ) $arrReturn['arrOffer'][ $row->id ] = $row;
    
    // Get the order list
    $arrOrder = array();
    
    $this->db->select( $this->_tablename_order . '.order_id' );
    $this->db->select( $this->_tablename_order . '.order_name' );
    $this->db->select( $this->_tablename_order . '.customer_name' );
    $this->db->select( $this->_tablename_order . '.created_at' );
    $this->db->select( $this->_tablename_order . '.amount' );
    $this->db->select( $this->_tablename_purchase . '.amount AS amount_offer' );
    $this->db->select( $this->_tablename_purchase . '.offer_id' );
    $this->db->from( $this->_tablename_order );
    $this->db->join( $this->_tablename_purchase, $this->_tablename_order . '.order_id = ' . $this->_tablename_purchase . '.order_id', 'INNER' );
    $this->db->group_by($this->_tablename_order . '.order_id');
    $this->db->where( $this->_tablename_purchase.'.shop', $this->_shop );
    if( $from != '' ) $this->db->where( $this->_tablename_purchase . '.process_date >= \'' . $from . '\'' );
    if( $to != '' ) $this->db->where( $this->_tablename_purchase . '.process_date <= \'' . $to . ' 23:59:59\'' );
    $this->db->order_by( $this->_tablename_order . '.created_at DESC' );
    
    $query = $this->db->get( $this->_tablename );
    
    if( $query->num_rows() > 0 )
    foreach( $query->result() as $row )
    {
      // Calculate the total sum
      $arrReturn[ 'totalPurchase' ] += $row->amount;
      $arrReturn[ 'totalPurchaseOffer' ] += $row->amount_offer;
      
      // build the item
      $arrReturn['arrOrder'][] = array(
        'order_name' => $row->order_name,
        'customer_name' => $row->customer_name,
        'created_at' => $row->created_at,
        'amount' => $row->amount,
        'amount_offer' => $row->amount_offer,
        'offer_id' => $row->offer_id,
      );
      
      // Add the line
      if( !isset( $arrReturn['arrLine'][ substr( $row->created_at, 0, 10 ) ] ) )
      {
        $arrReturn['arrLine'][ substr( $row->created_at, 0, 10 ) ] = array(
          'purchase' => $row->amount_offer,
          'view' => 0,
        );  
      }
      else
      {
        $arrReturn['arrLine'][ substr( $row->created_at, 0, 10 ) ]['purchase'] += $row->amount_offer;
      }
    }

    // Get the product list
    $arrProduct = array();
    $CI->load->model( 'Product_model' );
    
    $query = $CI->Product_model->getList( array() );
    foreach( $query as $row ) $arrProduct[$row->product_id] = $row->title;
    
    // Get the view list
    $this->db->select( $this->_tablename_view . '.offer_id' );
    $this->db->select( $this->_tablename_view . '.product_id' );
    $this->db->select( $this->_tablename_view . '.when' );
    $this->db->select( $this->_tablename_view . '.process_date' );
    $this->db->where( $this->_tablename_view . '.shop', $this->_shop );
    if( $from != '' ) $this->db->where( $this->_tablename_view . '.process_date >= \'' . $from . '\'' );
    if( $to != '' ) $this->db->where( $this->_tablename_view . '.process_date <= \'' . $to . ' 23:59:59\'' );
    $this->db->order_by( $this->_tablename_view . '.process_date DESC' );

    $query = $this->db->get( $this->_tablename_view );
    
    if( $query->num_rows() > 0 )
    foreach( $query->result() as $row )
    {
      // Count the offer number of used
      if( $row->when == 'cart' ) $arrReturn['numCart'] ++;
      if( $row->when == 'checkout' ) $arrReturn['numCheckout'] ++;
      
      // build the array
      $arrReturn['arrView'][] = array(
        'product_title' => $arrProduct[$row->product_id],
        'when' => $row->when,
        'process_date' => $row->process_date,
        'product_id' => $row->product_id,
        'offer_id' => $row->offer_id,
      );
      
      // Add the line
      if( !isset( $arrReturn['arrLine'][ substr( $row->process_date, 0, 10 ) ] ) )
      {
        $arrReturn['arrLine'][ substr( $row->process_date, 0, 10 ) ] = array(
          'purchase' => 0,
          'view' => 1,
        );  
      }
      else
      {
        $arrReturn['arrLine'][ substr( $row->process_date, 0, 10 ) ]['view'] ++;
      }      
    }

    // Re arrange  the line
    ksort( $arrReturn['arrLine'] );
    return $arrReturn;
  }
  
  // Add Purchase
  public function addPurchase( $offer_id, $order_id, $product_id, $amount, $checkout_date )
  {
    $data = array(
      'offer_id' => $offer_id,
      'order_id' => $order_id,
      'product_id' => $product_id,
      'amount' => $amount,
      'process_date' => $checkout_date,
      'shop' => $this->_shop,
    );
    
    $this->db->insert( $this->_tablename_purchase, $data);
  }
  
  // Add View
  public function addView( $offer_id, $product_id, $when, $view_date )
  {
    $data = array(
      'offer_id' => $offer_id,
      'product_id' => $product_id,
      'when' => $when,
      'process_date' => $view_date,
      'shop' => $this->_shop,
    );
    
    $this->db->insert( $this->_tablename_view, $data);
  }
}  
?>