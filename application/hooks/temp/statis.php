<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Statis extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Result_model');

        // Define the search values
        $this->_searchConf  = array(
            'date' => date( 'Y-m-01 - Y-m-d' ),
            'type' => 'M',
        );
        $this->_searchSession = 'statis';
    }
    
    public function index(){
        $this->is_logged_in();

        $this->manage();
    }

    function manage(){
        // Check the login
        $this->is_logged_in();

        // Init the search value
        $this->initSearchValue();

        // Get the statis data
        $arr = explode( '-', $this->_searchVal['date'] );
        $from = trim( $arr[0] ) . '-' . trim( $arr[1] ) . '-' . trim( $arr[2] );
        $to = trim( $arr[3] ) . '-' . trim( $arr[4] ) . '-' . trim( $arr[5] );
        $data['arrStatis'] =  $this->Result_model->getStatis( $from, $to );
        
        // Get the settings info
        $this->load->model( 'Settings_model' );
        $settingsInfo = $this->Settings_model->getSettingsValue( true );
        
        $data['settings_onoff'] = $settingsInfo['popup_enabled'];
        
        // Define the rendering data
        $data = $data + $this->setRenderData();
        
        $this->load->view('view_header');
        $this->load->view('view_statis', $data);
        $this->load->view('view_footer');
    }
}             