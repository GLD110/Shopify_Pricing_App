<?php
class Product_model extends Master_model
{
  protected $_tablename = 'product';
  private $_total_count = 0;
  private $_arrProduct_VariantKey = array();
  private $_arrProduct_IdKey = array();    
  
  function __construct() {
    parent::__construct();
  }
    
  public function getTotalCount(){ return $this->_total_count; }
     
  public function getList( $arrCondition )
  {
      $where = array( 'shop' => $this->_shop );
      
      // Build the where clause
      //with tier
      if( !empty( $arrCondition['tier'] ) ) $where["product_option LIKE '%" . str_replace( "'", "\\'", $arrCondition['tier'] ) . "%'"] = '';
      //with collection
      if( !empty( $arrCondition['collection'] ) ) $where["collection LIKE '%" . str_replace( "'", "\\'", $arrCondition['collection'] ) . "%'"] = '';
      //with name
      if( !empty( $arrCondition['name'] ) ) $where["title LIKE '%" . str_replace( "'", "\\'", $arrCondition['name'] ) . "%'"] = '';
      
      // Product only - Group by, Get total records
      if( isset( $arrCondition['page_number'] ) )
      {
        // Get the count of records
        foreach( $where as $key => $val )
        if( $val == '' )
            $this->db->where( $key );
        else
            $this->db->where( $key, $val );
        $query = $this->db->get( $this->_tablename);
        $this->_total_count = $query->num_rows();
      }
      
      // Sort
      if( isset( $arrCondition['sort'] ) ) $this->db->order_by( $arrCondition['sort'] );
      $this->db->order_by( 'product_id', 'DESC' );

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
      $query = $this->db->get_where( $this->_tablename );
      
      $arrReturn = $query->result();
      
      return $arrReturn;
  }
    
  // Get last updated date
  public function getLastUpdateDate()
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
    
  public function getProductVariants($product_id){
      
      
    $this->_arrProduct_VariantKey = array();
    $this->_arrProduct_IdKey = array();
      
    $query = parent::getList('product_id='.$product_id, 'product_id', 'id,variant_id');
    
    if( $query->num_rows > 0 )
    foreach( $query->result() as $row )
    { 
      $this->_arrProduct_VariantKey[] = $row->variant_id;
      $this->_arrProduct_IdKey[] = $row->id;    
    }
  }   
     
  // Add product to database
  public function addProduct( $product, $collectionInfo )
  {
    // Get the images as array
    $arrImage = array();
    foreach( $product->images as $item ) $arrImage[ $item->id ] = $item->src;
      
    //Get the collections of product
    $arrCollection = array();
    $collections = $collectionInfo->collects;
    foreach( $collections as $collection ) 
      if(isset($collection->id))   { 
        array_push($arrCollection, $collection->collection_id); 
     }
     
      
    $collection_info = implode(",", $arrCollection);  
      
    $this->getProductVariants($product->id);  
    $Arr_variants = array();  
      
    foreach( $product->variants as $variant )
    {
      array_push($Arr_variants, $variant->id);    
      // Get image id
      $image_url = '';
      if( !empty($variant->image_id) ) $image_url = $arrImage[$variant->image_id];
      if( $image_url == '' && isset( $product->image->src ))
      {
        $image_url = $product->image->src;
      } 
      
      $option = '';
      if (isset($variant->option1)){
        $option = $variant->option1;
        if (isset($variant->option2)){
            $option .= ',' . $variant->option2;
            if(isset($variant->option3)){
                $option .= ',' . $variant->option3;
                if(isset($variant->option4)){
                    $option .= ',' . $variant->option4;
                }
            }
        }  
      }
          
      
      // Add the new variant
      $newProductInfo = array(
        'title' => $product->title,
        'product_id' => $product->id,
        'variant_id' => $variant->id,
        'sku' => $variant->sku,
        'handle' => $product->handle,
        'price' => $variant->price,
        'updated_at' => $variant->updated_at,
        'image_url' => $image_url,
        'collection' => $collection_info,
        'product_option' => $option  
      );
    
      // Update the existing product   
      if( in_array( (string)$variant->id, $this->_arrProduct_VariantKey ))
      {
        $key = array_search($variant->id, $this->_arrProduct_VariantKey); 
        $id = $this->_arrProduct_IdKey[$key]; 
        parent::update( $id, $newProductInfo ); 
      }    
      else{
        parent::add( $newProductInfo );      
      }  
    }   
    
    //and delete removing record.  
    foreach($this->_arrProduct_VariantKey as $key=>$variant_id){
        if(!(in_array($variant_id, $Arr_variants))){
            $id = $this->_arrProduct_IdKey[$key]; 
            parent::delete($id);
        }    
    }  
  }
    
  //delete variant with id
  public function deleteVariant( $id ){
    parent::delete($id);
  }  
    
  //delete variant with id
  public function updatePrice( $id, $updated ){
    
    $variant = $updated->variant;  
    $newProductInfo = array(
        'sku' => $variant->sku,
        'price' => $variant->price,
        'updated_at' => $variant->updated_at
      );  
    parent::update($id, $newProductInfo);
  }    
  
  // Delete the product from product_id
  public function deleteProduct( $product_id )
  {
    $this->db->delete( $this->_tablename, array( 'product_id' => $product_id, 'shop' => $this->_shop ) );
    if( $this->db->affected_rows() > 0 )
        return true;
    else
        return false;
    
  }
  
  public function getImageFromHandle( $product_handle )
  {
    $return = '';
    
    $query = parent::getList( 'handle=\'' . $product_handle . '\'' );
    if( $query->num_rows() > 0 )
    {
      $result = $query->result();
      $return = array(
        'product_name' => $result[0]->title,
        'image_url' => $result[0]->image_url,
      );
    }
    
    return $return;
    
  }
}  
?>
