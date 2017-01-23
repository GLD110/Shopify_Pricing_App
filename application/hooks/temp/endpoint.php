<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Endpoint extends MY_Controller {
  
  private $_shop = '';
    
  public function __construct() {
    parent::__construct();
    
    // Define a header
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST");
    header('Content-Type: application/json');
    
    // Get the shop from the HTTP Header or private shop  
    $this->_shop = isset( $_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'] ) ? $_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'] : $this->config->item('PRIVATE_SHOP');

    // Get the Input Stream
    $this->_inputInfo = json_decode( file_get_contents('php://input') );

    if( !isset($this->_inputInfo->id ) )
    {
//      $strTemp = '{"id":6656143175,"title":"A046 360 Degrees Car Windscreen Holder Dashboard Mount Stand for Cell Phone GPS2","body_html":"\u003cdiv class=\"xxkkk\"\u003eLorem ipsum dolor sit amet, consectetuer adipiscing elit. Sed tempus diam non pede. Vivamus porttitor dui ac est fringilla porttitor. In luctus iaculis nunc. Praesent ac ligula. Cras rutrum cursus risus. Nunc tincidunt sapien dignissim massa. Nam facilisis. Mauris sit amet nisl a ante porta faucibus. Quisque commodo ante sed mauris. Integer ac dui. Curabitur varius lorem et tortor. Phasellus tristique turpis nec magna.\u003c\/div\u003e","vendor":"Chief Products","product_type":"Normal","created_at":"2016-05-19T20:44:56+10:00","handle":"a046-360-degrees-car-windscreen-holder-dashboard-mount-stand-for-cell-phone-gps","updated_at":"2016-07-16T00:44:51+10:00","published_at":"2016-05-19T20:44:00+10:00","template_suffix":null,"published_scope":"global","tags":"case, iphone","variants":[{"id":21168477383,"product_id":6656143175,"title":"HEAVY GRAY \/ Large \/ IRON","price":"12.00","sku":"159380902","position":1,"grams":95,"inventory_policy":"deny","compare_at_price":"1.40","fulfillment_service":"manual","inventory_management":"shopify","option1":"HEAVY GRAY","option2":"Large","option3":"IRON","created_at":"2016-05-19T20:44:56+10:00","updated_at":"2016-07-13T02:39:45+10:00","taxable":false,"barcode":"999999999","image_id":12708165575,"inventory_quantity":64,"weight":0.21,"weight_unit":"lb","old_inventory_quantity":64,"requires_shipping":true},{"id":21168477447,"product_id":6656143175,"title":"RED \/ Small \/ Summer","price":"13.25","sku":"159380901","position":2,"grams":206,"inventory_policy":"deny","compare_at_price":"1.40","fulfillment_service":"manual","inventory_management":"shopify","option1":"RED","option2":"Small","option3":"Summer","created_at":"2016-05-19T20:44:56+10:00","updated_at":"2016-07-13T02:13:51+10:00","taxable":false,"barcode":"","image_id":12708201671,"inventory_quantity":100,"weight":0.206,"weight_unit":"kg","old_inventory_quantity":100,"requires_shipping":true}],"options":[{"id":7986873735,"product_id":6656143175,"name":"Color","position":1,"values":["HEAVY GRAY","RED"]},{"id":9275480647,"product_id":6656143175,"name":"Size","position":2,"values":["Large","Small"]},{"id":9275485255,"product_id":6656143175,"name":"Materials","position":3,"values":["IRON","Summer"]}],"images":[{"id":12708165575,"product_id":6656143175,"position":1,"created_at":"2016-05-19T20:45:00+10:00","updated_at":"2016-05-19T20:45:00+10:00","src":"https:\/\/cdn.shopify.com\/s\/files\/1\/1154\/9496\/products\/1447796684602-P-3445015.jpeg?v=1463654700","variant_ids":[21168477383]},{"id":12708169735,"product_id":6656143175,"position":2,"created_at":"2016-05-19T20:45:06+10:00","updated_at":"2016-05-19T20:45:06+10:00","src":"https:\/\/cdn.shopify.com\/s\/files\/1\/1154\/9496\/products\/1447796684810-P-3445015.jpeg?v=1463654706","variant_ids":[]},{"id":12708172359,"product_id":6656143175,"position":3,"created_at":"2016-05-19T20:45:11+10:00","updated_at":"2016-05-19T20:45:11+10:00","src":"https:\/\/cdn.shopify.com\/s\/files\/1\/1154\/9496\/products\/1447796684751-P-3445015.jpeg?v=1463654711","variant_ids":[]},{"id":12708175175,"product_id":6656143175,"position":4,"created_at":"2016-05-19T20:45:16+10:00","updated_at":"2016-05-19T20:45:16+10:00","src":"https:\/\/cdn.shopify.com\/s\/files\/1\/1154\/9496\/products\/1447796684426-P-3445015.jpeg?v=1463654716","variant_ids":[]},{"id":12708177607,"product_id":6656143175,"position":5,"created_at":"2016-05-19T20:45:21+10:00","updated_at":"2016-05-19T20:45:21+10:00","src":"https:\/\/cdn.shopify.com\/s\/files\/1\/1154\/9496\/products\/1447796685962-P-3445015.jpeg?v=1463654721","variant_ids":[]},{"id":12708181639,"product_id":6656143175,"position":6,"created_at":"2016-05-19T20:45:26+10:00","updated_at":"2016-05-19T20:45:26+10:00","src":"https:\/\/cdn.shopify.com\/s\/files\/1\/1154\/9496\/products\/1447796685070-P-3445015.jpeg?v=1463654726","variant_ids":[]},{"id":12708183367,"product_id":6656143175,"position":7,"created_at":"2016-05-19T20:45:30+10:00","updated_at":"2016-05-19T20:45:30+10:00","src":"https:\/\/cdn.shopify.com\/s\/files\/1\/1154\/9496\/products\/1447796685980-P-3445015.jpeg?v=1463654730","variant_ids":[]},{"id":12708187335,"product_id":6656143175,"position":8,"created_at":"2016-05-19T20:45:36+10:00","updated_at":"2016-05-19T20:45:36+10:00","src":"https:\/\/cdn.shopify.com\/s\/files\/1\/1154\/9496\/products\/1447796685838-P-3445015.jpeg?v=1463654736","variant_ids":[]},{"id":12708191111,"product_id":6656143175,"position":9,"created_at":"2016-05-19T20:45:41+10:00","updated_at":"2016-05-19T20:45:41+10:00","src":"https:\/\/cdn.shopify.com\/s\/files\/1\/1154\/9496\/products\/1447796686966-P-3445015.jpeg?v=1463654741","variant_ids":[]},{"id":12708201671,"product_id":6656143175,"position":10,"created_at":"2016-05-19T20:45:59+10:00","updated_at":"2016-05-19T20:45:59+10:00","src":"https:\/\/cdn.shopify.com\/s\/files\/1\/1154\/9496\/products\/1447796594040-P-3445014.jpeg?v=1463654759","variant_ids":[21168477447]},{"id":12708204487,"product_id":6656143175,"position":11,"created_at":"2016-05-19T20:46:04+10:00","updated_at":"2016-05-19T20:46:04+10:00","src":"https:\/\/cdn.shopify.com\/s\/files\/1\/1154\/9496\/products\/1447796594769-P-3445014.jpeg?v=1463654764","variant_ids":[]},{"id":12708206919,"product_id":6656143175,"position":12,"created_at":"2016-05-19T20:46:08+10:00","updated_at":"2016-05-19T20:46:08+10:00","src":"https:\/\/cdn.shopify.com\/s\/files\/1\/1154\/9496\/products\/1447796595202-P-3445014.jpeg?v=1463654768","variant_ids":[]},{"id":12708210183,"product_id":6656143175,"position":13,"created_at":"2016-05-19T20:46:13+10:00","updated_at":"2016-05-19T20:46:13+10:00","src":"https:\/\/cdn.shopify.com\/s\/files\/1\/1154\/9496\/products\/1447796595991-P-3445014.jpeg?v=1463654773","variant_ids":[]},{"id":12708214343,"product_id":6656143175,"position":14,"created_at":"2016-05-19T20:46:18+10:00","updated_at":"2016-05-19T20:46:18+10:00","src":"https:\/\/cdn.shopify.com\/s\/files\/1\/1154\/9496\/products\/1447796595279-P-3445014.jpeg?v=1463654778","variant_ids":[]},{"id":12708217095,"product_id":6656143175,"position":15,"created_at":"2016-05-19T20:46:23+10:00","updated_at":"2016-05-19T20:46:23+10:00","src":"https:\/\/cdn.shopify.com\/s\/files\/1\/1154\/9496\/products\/1447796595770-P-3445014.jpeg?v=1463654783","variant_ids":[]},{"id":12708219335,"product_id":6656143175,"position":16,"created_at":"2016-05-19T20:46:27+10:00","updated_at":"2016-05-19T20:46:27+10:00","src":"https:\/\/cdn.shopify.com\/s\/files\/1\/1154\/9496\/products\/1447796595305-P-3445014.jpeg?v=1463654787","variant_ids":[]},{"id":12708223111,"product_id":6656143175,"position":17,"created_at":"2016-05-19T20:46:32+10:00","updated_at":"2016-05-19T20:46:32+10:00","src":"https:\/\/cdn.shopify.com\/s\/files\/1\/1154\/9496\/products\/1447796596084-P-3445014.jpeg?v=1463654792","variant_ids":[]},{"id":12708225223,"product_id":6656143175,"position":18,"created_at":"2016-05-19T20:46:37+10:00","updated_at":"2016-05-19T20:46:37+10:00","src":"https:\/\/cdn.shopify.com\/s\/files\/1\/1154\/9496\/products\/1447796596469-P-3445014.jpeg?v=1463654797","variant_ids":[]},{"id":12708227719,"product_id":6656143175,"position":19,"created_at":"2016-05-19T20:46:41+10:00","updated_at":"2016-05-19T20:46:41+10:00","src":"https:\/\/cdn.shopify.com\/s\/files\/1\/1154\/9496\/products\/1447796596106-P-3445014.jpeg?v=1463654801","variant_ids":[]}],"image":{"id":12708165575,"product_id":6656143175,"position":1,"created_at":"2016-05-19T20:45:00+10:00","updated_at":"2016-05-19T20:45:00+10:00","src":"https:\/\/cdn.shopify.com\/s\/files\/1\/1154\/9496\/products\/1447796684602-P-3445015.jpeg?v=1463654700","variant_ids":[21168477383]}}';
      $strTemp = '{"id":4587865223,"email":"denis.buhler@outlook.com","closed_at":null,"created_at":"2016-10-23T03:38:05+10:00","updated_at":"2016-10-23T03:55:31+10:00","number":36,"note":"aas","token":"f888ef541dda70bc2ab49413a667451c","gateway":"Bank Deposit","test":false,"total_price":"85.91","subtotal_price":"75.91","total_weight":361,"total_tax":"4.55","taxes_included":true,"currency":"AUD","financial_status":"pending","confirmed":true,"total_discounts":"0.00","total_line_items_price":"75.91","cart_token":"4caf7b97918c806ac86fe08ecc910407","buyer_accepts_marketing":false,"name":"#1036","referring_site":"","landing_site":"\/admin\/auth\/login","cancelled_at":null,"cancel_reason":null,"total_price_usd":"65.36","checkout_token":"f50d67040acca3457cef8ec9afbbfe2a","reference":null,"user_id":null,"location_id":null,"source_identifier":null,"source_url":null,"processed_at":"2016-10-23T03:38:05+10:00","device_id":null,"browser_ip":null,"landing_site_ref":null,"order_number":1036,"discount_codes":[],"note_attributes":[],"payment_gateway_names":["Bank Deposit"],"processing_method":"manual","checkout_id":10934584071,"source_name":"web","fulfillment_status":null,"tax_lines":[{"title":"MwSt","price":"4.55","rate":0.1}],"tags":"","contact_email":"denis.buhler@outlook.com","order_status_url":"https:\/\/checkout.shopify.com\/11549496\/checkouts\/f50d67040acca3457cef8ec9afbbfe2a\/thank_you_token?key=69aafba14b9574df67e13dfd7532578c","line_items":[{"id":8685517639,"variant_id":24597784199,"title":"Joey\'s Test Product","quantity":4,"price":"10.00","grams":0,"sku":"joey-test-sku","variant_title":"","vendor":"Chief Products","fulfillment_service":"manual","product_id":7764051719,"requires_shipping":true,"taxable":true,"gift_card":false,"name":"Joey\'s Test Product","variant_inventory_management":null,"properties":[],"product_exists":true,"fulfillable_quantity":4,"total_discount":"0.00","fulfillment_status":null,"tax_lines":[{"title":"MwSt","price":"3.64","rate":0.1}],"origin_location":{"id":876106887,"country_code":"AU","province_code":"QLD","name":"Chief Products","address1":"P.O. Box 278","address2":"","city":"Mudgeeraba","zip":"4213"},"destination_location":{"id":2105766343,"country_code":"DE","province_code":"","name":"denis buhler","address1":"werneuchener str 603","address2":"","city":"berlin","zip":"13055"}},{"id":8685517703,"variant_id":21168214023,"title":"Baseus Universal 3 in 1 Clip Fisheye Lens Wide Angle Macro Mobile Phone Lens","quantity":2,"price":"3.33","grams":61,"sku":"169149301","variant_title":"SILVER","vendor":"Ecango-CB","fulfillment_service":"manual","product_id":6656049479,"requires_shipping":true,"taxable":false,"gift_card":false,"name":"Baseus Universal 3 in 1 Clip Fisheye Lens Wide Angle Macro Mobile Phone Lens - SILVER","variant_inventory_management":"shopify","properties":[],"product_exists":true,"fulfillable_quantity":2,"total_discount":"0.00","fulfillment_status":null,"tax_lines":[{"title":"MwSt","price":"0.00","rate":0.1}],"origin_location":{"id":876106887,"country_code":"AU","province_code":"QLD","name":"Chief Products","address1":"P.O. Box 278","address2":"","city":"Mudgeeraba","zip":"4213"},"destination_location":{"id":2105766343,"country_code":"DE","province_code":"","name":"denis buhler","address1":"werneuchener str 603","address2":"","city":"berlin","zip":"13055"}},{"id":8685517767,"variant_id":21167640007,"title":"Cell Phone Case Back Cover for Sony S39H","quantity":1,"price":"5.25","grams":49,"sku":"169456101","variant_title":"BLACK","vendor":"Ecango-CB","fulfillment_service":"manual","product_id":6655773063,"requires_shipping":true,"taxable":false,"gift_card":false,"name":"Cell Phone Case Back Cover for Sony S39H - BLACK","variant_inventory_management":"shopify","properties":[],"product_exists":true,"fulfillable_quantity":1,"total_discount":"0.00","fulfillment_status":null,"tax_lines":[{"title":"MwSt","price":"0.00","rate":0.1}],"origin_location":{"id":876106887,"country_code":"AU","province_code":"QLD","name":"Chief Products","address1":"P.O. Box 278","address2":"","city":"Mudgeeraba","zip":"4213"},"destination_location":{"id":2105766343,"country_code":"DE","province_code":"","name":"denis buhler","address1":"werneuchener str 603","address2":"","city":"berlin","zip":"13055"}},{"id":8685517831,"variant_id":21168477383,"title":"A046 360 Degrees Car Windscreen Holder Dashboard Mount Stand for Cell Phone GPS2-","quantity":2,"price":"12.00","grams":95,"sku":"159380902","variant_title":"HEAVY GRAY \/ Large \/ IRON","vendor":"Chief Products","fulfillment_service":"manual","product_id":6656143175,"requires_shipping":true,"taxable":false,"gift_card":false,"name":"A046 360 Degrees Car Windscreen Holder Dashboard Mount Stand for Cell Phone GPS2- - HEAVY GRAY \/ Large \/ IRON","variant_inventory_management":"shopify","properties":[],"product_exists":true,"fulfillable_quantity":2,"total_discount":"0.00","fulfillment_status":null,"tax_lines":[{"title":"MwSt","price":"0.00","rate":0.1}],"origin_location":{"id":876106887,"country_code":"AU","province_code":"QLD","name":"Chief Products","address1":"P.O. Box 278","address2":"","city":"Mudgeeraba","zip":"4213"},"destination_location":{"id":2105766343,"country_code":"DE","province_code":"","name":"denis buhler","address1":"werneuchener str 603","address2":"","city":"berlin","zip":"13055"}}],"shipping_lines":[{"id":3803309191,"title":"Standard Shipping","price":"10.00","code":"Standard Shipping","source":"shopify","phone":null,"requested_fulfillment_service_id":null,"delivery_category":null,"carrier_identifier":null,"tax_lines":[{"title":"MwSt","price":"0.91","rate":0.1}]}],"billing_address":{"first_name":"denis","address1":"werneuchener str 603","phone":"5648564856","city":"Berlin","zip":"13055","province":null,"country":"Germany","last_name":"buhler","address2":"","company":null,"latitude":52.5408579,"longitude":13.4944052,"name":"denis buhler","country_code":"DE","province_code":null},"shipping_address":{"first_name":"denis","address1":"werneuchener str 603","phone":"5648564856","city":"Berlin","zip":"13055","province":null,"country":"Germany","last_name":"buhler","address2":"","company":null,"latitude":52.5408579,"longitude":13.4944052,"name":"denis buhler","country_code":"DE","province_code":null},"fulfillments":[],"client_details":{"browser_ip":"59.45.40.244","accept_language":"en-US,en;q=0.8","user_agent":"Mozilla\/5.0 (Windows NT 10.0; WOW64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/53.0.2785.143 Safari\/537.36","session_hash":"40c48876a58e31c49a7a482d094c4926","browser_width":1903,"browser_height":544},"refunds":[],"customer":{"id":3929218311,"email":"denis.buhler@outlook.com","accepts_marketing":false,"created_at":"2016-07-08T00:37:00+10:00","updated_at":"2016-10-23T03:38:05+10:00","first_name":"denis","last_name":"buhler","orders_count":5,"state":"disabled","total_spent":"0.00","last_order_id":4587865223,"note":null,"verified_email":true,"multipass_identifier":null,"tax_exempt":false,"tags":"","last_order_name":"#1036","default_address":{"id":5354198407,"first_name":"denis","last_name":"buhler","company":null,"address1":"werneuchener str 603","address2":"","city":"Berlin","province":null,"country":"Germany","zip":"13055","phone":"5648564856","name":"denis buhler","province_code":null,"country_code":"DE","country_name":"Germany","default":true}}}';
//      $strTemp = '{"rate":{"origin":{"country":"AU","postal_code":"4213","province":"QLD","city":"Mudgeeraba","name":null,"address1":"P.O. Box 278","address2":"","address3":null,"phone":"61755227340","fax":null,"address_type":null,"company_name":"KevShop"},"destination":{"country":"US","postal_code":"7000","province":"TAS","city":"Hobart","name":"Maree Anne  Davis","address1":"98 Argyle Street","address2":"","address3":null,"phone":"","fax":null,"address_type":null,"company_name":""},"items":[{"name":"Default","sku":"YO0625501","quantity":2,"grams":35,"price":1000,"vendor":"KevShop","requires_shipping":true,"taxable":true,"fulfillment_service":"manual","properties":null,"product_id":5309944903,"variant_id":16435836679},{"name":"Large","sku":"YO0625502","quantity":1,"grams":50,"price":2000,"vendor":"KevShop","requires_shipping":true,"taxable":true,"fulfillment_service":"manual","properties":null,"product_id":5309940615,"variant_id":16435812679}],"currency":"AUD"}}';
      
      $this->_inputInfo = json_decode( $strTemp );
    }
    
  }
  
  public function index(){
  }
  
  /** 
  * Checkout popup
  * 
  */
  public function order_create( $method = 'Order Created' )
  {
    $this->load->model( 'Order_model' );
    $this->Order_model->rewriteParam( $this->_shop );
    $this->Order_model->add( $this->_inputInfo );    
  }
  
  public function order_paid()
  {
    $this->order_create( 'Order Paid' );
  }

  public function product_create()
  {
    $this->load->model( 'Product_model' );
    $this->Product_model->rewriteParam( $this->_shop );
    $this->Product_model->addProduct( $this->_inputInfo );        

  }  
  
  // Request for when
  public function request( $shop, $variant_ids, $when  )
  {
    $this->_shop = $shop;
    
    // Load model
    $this->load->model( 'Product_model' );
    $this->Product_model->rewriteParam( $this->_shop );
    
    // Get the offer list
    $return = array(
      'offer_id' => 0,
      'product_id' => 0,
      'offer_url' => 0,
    );
    $arrVariantId = explode( '_', $variant_ids );
    $arrProduct = $this->Product_model->getProductOfferList( $when );
    
    foreach( $arrProduct as $item )
    {
      // Check the product is exist
      if( !in_array( $item['variant_id'], $arrVariantId ) ) continue;
      
      // Get the first matched offer
      $return = array(
        'offer_id' => $item['offer_id'],
        'product_id' => $item['product_id'],
        'offer_url' => $item['offer_url'],
      );
    }
    
    echo json_encode( $return );
  }
  
  public function add_view( $shop, $offer_id, $product_id, $when )
  {
    $this->_shop = $shop;

    // Load model
    $this->load->model( 'Result_model' );
    $this->Result_model->rewriteParam( $this->_shop );
    
    // Add View to result
    $this->Result_model->addView( $offer_id, $product_id, $when, date( $this->config->item('CONST_DATE_FORMAT') ) );
    
    echo json_encode( array( 'status' => 'success' ) );
  }
    /*
    Script should be in checkout page
    <script>
    var order_id = "{{order_id}}";
    var order_name = "{{order_name}}";
    </script>
    <script src = "https://www.bmbonlineassets.com/app_survey/asset/popup/checkout.js">
    </script>
    */    
}
    
