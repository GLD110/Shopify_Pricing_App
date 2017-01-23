<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Install extends CI_Controller {
    
    private $_shop;
    private $_access_token;
        
    public function index(){
        
        if( isset( $_GET['code'] )  )
        {
            $code = $_GET['code'];
            $hmac = $_GET['hmac'];
            $timestamp = $_GET['timestamp'];
            $shop = $_GET['shop'];
            
            // ********** Access to Shopify oAuth Token ********** //
            
                // Build the Param Querystring             
                $strParam = 'client_id=' . $this->config->item( 'APP_CLIENT_ID' );
                $strParam .= '&client_secret=' . $this->config->item( 'APP_CLIENT_SECRET' );
                $strParam .= '&code=' . $code;

                $token_url = 'https://' . $shop . $this->config->item('API_TOKEN_URL');

                // Init the session
                $curl = curl_init();

                // Set configuration value
                curl_setopt($curl, CURLOPT_URL, $token_url );               // Required : Set the access url
                curl_setopt($curl, CURLOPT_POST, 1);                        // Optional : Set POST
                curl_setopt($curl, CURLOPT_POSTFIELDS, $strParam);          // Required : POST Parameter String
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true );          // Required : Enable the HTTP response as return value
                curl_setopt($curl, CURLOPT_USERAGENT, $this->config->item('APP_NAME') );           // Optional : Add the client Agent name as APP_NAME
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false );         // Required : Ignore the SSL Certificate Verify

                // Access the remove URL
                $result = curl_exec($curl);
                
                // Close the session
                curl_close($curl);
            
            //  ************************************************** //

            $tokenInfo = json_decode( $result );
            
            // Save the token info to the database
            if( isset($tokenInfo->access_token) )
            {
                // Save current token Cookie
                setcookie( 'access_token', $tokenInfo->access_token, mktime (0, 0, 0, 12, 31, 2017) );
                
                // Save the access token and shop domain to the session
                $this->_shop = $shop;
                $this->_access_token = $tokenInfo->access_token;

                $this->session->set_userdata( array( 
                    'shop' => $shop, 
                    'access_token' => $tokenInfo->access_token 
                ));
                
                // Save the token to database
                $this->load->model( 'Shopify_model' );
                $this->Shopify_model->rewriteParam( $this->_shop, $this->_access_token );
                $this->Shopify_model->setAccessToken( $this->_shop, $this->_access_token );
                
                // Init the configuration
                $this->register();
                
                // Redirect to main page
                redirect( 'home' );        
            }
            else
            {
                var_dump( $result );
            }
        }

    }
    
    public function register()
    {
        // ********* Register the Script Tags ********* //
        if( true )
        {
            $this->load->model( 'Shopify_model' );
            $this->Shopify_model->rewriteParam( $this->_shop, $this->_access_token );

            $base_url = $this->config->item('base_url') . $this->config->item('index_page');
            if( $this->config->item('index_page') != '' ) $base_url .= '/';

            // Add jquery
            $arrParam = array(
                'script_tag' => array(
                    'event' => 'onload',
                    'display_scope' => 'all',
                    'src' => 'https://code.jquery.com/jquery-1.12.3.min.js',
                ),
            );
            
            $return = $this->Shopify_model->accessAPI( 'script_tags.json', $arrParam, 'POST' );

            // Add jquery
            $arrParam = array(
                'script_tag' => array(
                    'event' => 'onload',
                    'display_scope' => 'all',
                    'src' => $base_url . 'asset/popup/upsell_video.js',
                ),
            );
            
            $return = $this->Shopify_model->accessAPI( 'script_tags.json', $arrParam, 'POST' );
                        
            $arrParam = array(
                'webhook' => array(
                    'topic' => 'orders/create',
                    'address' => $base_url . 'endpoint/order_create',
                    'format' => 'json',
                ),
            );
            $return = $this->Shopify_model->accessAPI( 'webhooks.json', $arrParam, 'POST' );

            $arrParam = array(
                'webhook' => array(
                    'topic' => 'orders/paid',
                    'address' => $base_url . 'endpoint/order_paid',
                    'format' => 'json',
                ),
            );
            $return = $this->Shopify_model->accessAPI( 'webhooks.json', $arrParam, 'POST' );
            
            $arrParam = array(
                'webhook' => array(
                    'topic' => 'orders/updated',
                    'address' => $base_url . 'endpoint/order_create',
                    'format' => 'json',
                ),
            );
            $return = $this->Shopify_model->accessAPI( 'webhooks.json', $arrParam, 'POST' );
                            
            $arrParam = array(
                'webhook' => array(
                    'topic' => 'product/create',
                    'address' => $base_url . 'endpoint/product_create',
                    'format' => 'json',
                ),
            );
            
            $return = $this->Shopify_model->accessAPI( 'webhooks.json', $arrParam, 'POST' );

            // Add Order Paid
            $arrParam = array(
                'webhook' => array(
                    'topic' => 'app/uninstalled',
                    'address' => $this->config->item('base_url') . 'install/uninstall',
                    'format' => 'json',
                ),
            );
            
            $return = $this->Shopify_model->accessAPI( 'webhooks.json', $arrParam, 'POST' );
        }
        
        // *************************** //

        // ********* Init the database ********* //
        $this->load->model( 'Settings_model' );
        $this->Settings_model->rewriteParam( $this->_shop );
        
        $this->Settings_model->install();
    }
    
    public function uninstall()
    {
        // Set the shop
        $inputText = file_get_contents('php://input');
        if( $inputText == '' ) return;
        
        $inputInfo = json_decode( $inputText );
        
        $fp = fopen( 'log.txt', 'w+');
        fwrite( $fp, $inputText );
        fwrite( $fp, "\r\n----------------\r\n" );
        
        $shop = $inputInfo->myshopify_domain;
        fwrite( $fp, $shop );
        
        // Uninstall the database
        /*
        $this->load->model( 'Settings_model' );
        $this->load->model( 'Answer_model' );
        $this->load->model( 'Coupon_model' );
        $this->load->model( 'Question_model' );
        $this->load->model( 'Result_model' );
        $this->load->model( 'User_model' );

        $this->Settings_model->rewriteParam( $shop );
        $this->Answer_model->rewriteParam( $shop );
        $this->Coupon_model->rewriteParam( $shop );
        $this->Question_model->rewriteParam( $shop );
        $this->Result_model->rewriteParam( $shop );
        $this->User_model->rewriteParam( $shop );
        
        $this->Settings_model->uninstall();
        $this->Answer_model->uninstall();
        $this->Coupon_model->uninstall();
        $this->Question_model->uninstall();
        $this->Result_model->uninstall();
        $this->User_model->uninstall();
        */
        
        // Get access token
        $this->load->model( 'Shopify_model' );
        $access_token = $Shopify_model->getAccessToken( $shop );
        $this->Shopify_model->rewriteParam( $shop, $access_token );
        
        // Delete webhooks
        $return = $this->Shopify_model->accessAPI( 'webhooks.json' );

        if( isset( $return->webhooks ) && count( $return->webhooks ) > 0 )
        foreach( $return->webhooks as $webhook )
        {
            $returnDelete = $this->Shopify_model->accessAPI( 'webhooks/' . $webhook->id . '.json', array(), 'DELETE' );
            fwrite( $fp, json_encode( $returnDelete));
        }
        
        // Delete Script Tag
        $return = $this->Shopify_model->accessAPI( 'script_tags.json' );
        
        if( isset( $return->script_tags ) && count( $return->script_tags ) > 0 )
        foreach( $return->script_tags as $tag )
        {
            $returnDelete = $this->Shopify_model->accessAPI( 'script_tags/' . $tag->id . '.json', array(), 'DELETE' );
            fwrite( $fp, json_encode( $returnDelete));
        }
        
        // Delete token
        $this->Shopify_model->deleteAccessToken( $shop );
        
        fwrite( $fp, 'SHOP:' . $shop );
        fclose( $fp );
    }
}            

