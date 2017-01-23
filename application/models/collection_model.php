<?php
class Collection_model extends Master_model
{
  protected $_tablename = 'collection';
  private $_total_count = 0;
  private $_arrCollectionKey = array();
  
  function __construct() {
    parent::__construct();
    
    // Get the collection_id list
    $query = parent::getList();
    
    if( $query->num_rows > 0 )
    foreach( $query->result() as $row )
    {
      $this->_arrCollectionKey[] = $row->id;
    }  
   }

  public function getTotalCount(){ return $this->_total_count; }
  
  /**
  * Get the list of product/ varints
  * array(
  *     'supplier' => '',   // String
  *     'name' => '',       // String
  *     'sku' => '',        // String
  *     'supplier_category' => '',   // String
  *     'price' => '',               // String "{from} {to}"
  *     'product_id' => '',             // String
  *     'variant_id' => '',             // String
  *     'sort' => '',                   // String "{column} {order}"
  *     'product_only' => '',           // Boolean true/false : default :false
  *     'page_number' => '',            // Int, default : 0
  *     'page_size' => '',              // Int, default Confing['PAGE_SIZE'];
  *     'is_imported' => '',            // Int, 0: all, 1: published, 2: not-published / default : 0
  *     'is_queue' => '',               // Int, 0: all, 1: queue, 2: not-queue, / default : 0
  *     'is_stock' => '',               // Int, 0: all, 1: in stock, 2: out of stock / default 0
  );
  */
  public function getList()
  {
      $select = 'id, collection_title';
      $order_by = 'collection_title;';
      $where = '';
              
      $sql = 'SELECT ' . $select . ' FROM ' . $this->_tablename;
        $sql .= ' WHERE shop = \'' . $this->_shop . '\'';
        if( $where != '') $sql .= ' AND ' . $where;
        if( $order_by != '' )
            $sql .= ' ORDER BY ' . $order_by;
            
      $query = $this->db->query($sql);
      
      $result =array();
      
      foreach($query->result() as $row){
        $result[$row->id] = $row->collection_title;      
      }
      
      return $result;  
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
  

 // Add collection to database
  public function addCollection( $collectionInfo )
  { 
    if(isset($collectionInfo->custom_collections))
        $collections = $collectionInfo->custom_collections;
    else
        $collections = $collectionInfo->smart_collections;
            
    foreach( $collections as $collection )
    {  
      // Add the new collection
      $newCollectionInfo = array(
        'updated_at' => $collection->updated_at,         
        'collection_title' => $collection->title,
        'id' => $collection->id 
      );
        
      if( in_array( (string)$collection->id, $this->_arrCollectionKey ))
      {
        parent::update( $collection->id, $newCollectionInfo );
      }     
      else {
        parent::add( $newCollectionInfo );
      }
    }   
  }    
    
  // ********************** //
}  
?>
