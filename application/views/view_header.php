<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>AdminLTE | Dashboard</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="<?PHP echo base_url(); ?>asset/adminlte/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?PHP echo base_url(); ?>asset/adminlte/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?PHP echo base_url(); ?>asset/adminlte/ionicons-2.0.1/css/ionicons.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="<?PHP echo base_url(); ?>asset/adminlte/plugins/iCheck/flat/blue.css">
    <!-- Morris chart -->
    <link rel="stylesheet" href="<?PHP echo base_url(); ?>asset/adminlte/plugins/morris/morris.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="<?PHP echo base_url(); ?>asset/adminlte/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <!-- Date Picker -->
    <link rel="stylesheet" href="<?PHP echo base_url(); ?>asset/adminlte/plugins/datepicker/datepicker3.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="<?PHP echo base_url(); ?>asset/adminlte/plugins/daterangepicker/daterangepicker-bs3.css">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="<?PHP echo base_url(); ?>asset/adminlte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="<?PHP echo base_url(); ?>asset/adminlte/plugins/colorpicker/bootstrap-colorpicker.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="<?PHP echo base_url(); ?>asset/adminlte/plugins/select2/select2.min.css">
    <!-- Data Tables bootstrap -->
    <link rel="stylesheet" href="<?PHP echo base_url(); ?>asset/adminlte/plugins/datatables/dataTables.bootstrap.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?PHP echo base_url(); ?>asset/adminlte/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?PHP echo base_url(); ?>asset/adminlte/dist/css/skins/_all-skins.min.css">

    <!-- jQuery 2.1.4 -->
    <script src="<?PHP echo base_url(); ?>asset/adminlte/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="<?PHP echo base_url(); ?>asset/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="<?PHP echo base_url(); ?>asset/adminlte/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url() ?>asset/bootstrap/js/bootstrap-editable.min.js"></script>
    <script src="<?php echo base_url() ?>asset/bootstrap/ui/jquery-ui.min.js" type="text/javascript"></script>
    <link rel="stylesheet" href="<?php echo base_url() ?>asset/bootstrap/ui/jquery-ui.min.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>asset/bootstrap/css/bootstrap-editable.css" rel="stylesheet"/>

    <!-- Bootstrap Multiselect -->
    <script src="<?php echo base_url() ?>asset/bootstrap/multiselect/bootstrap-multiselect.js"></script>
    <link rel="stylesheet" href="<?php echo base_url() ?>asset/bootstrap/multiselect/bootstrap-multiselect.css">
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <script>
    (function ( $ ) {
        // Button Init
        $.fn.btn_init = function( name, init_state, pending_state, success_state, error_state ){
            localStorage.setItem( name + '_init', JSON.stringify(init_state) );
            localStorage.setItem( name + '_pending', JSON.stringify(pending_state) );
            localStorage.setItem( name + '_success', JSON.stringify(success_state) );
            localStorage.setItem( name + '_error', JSON.stringify(error_state) );
        }

        // Button Pending    
        $.fn.btn_action = function( name, action ){
            
            // remove the init classes
            var old_state;
            if( action == 'pending')
            {
                old_state = JSON.parse(localStorage.getItem( name + '_' + 'init' ) );
                $(this).removeClass( old_state.class );
                old_state = JSON.parse(localStorage.getItem( name + '_' + 'success' ) );
                $(this).removeClass( old_state.class );
                old_state = JSON.parse(localStorage.getItem( name + '_' + 'error' ) );
                $(this).removeClass( old_state.class );
            }
            else
            {
                var old_state = JSON.parse(localStorage.getItem( name + '_' + 'pending' ) );
                $(this).removeClass( old_state.class );
            }
            
            // Add the success classes
            var new_state = JSON.parse(localStorage.getItem( name + '_' + action ) );
            $(this).addClass( new_state.class );
            
            // Add HTML
            $(this).html( new_state.caption );
        }
    }( jQuery ));    
    </script>
    <style type="text/css">
        @font-face {
            font-family: helveticaneue-light;
            src: url(<?PHP echo base_url( 'asset/fonts' ); ?>/helveticaneue-light-webfont.woff) format('woff'),
            url(<?PHP echo base_url( 'asset/fonts' ); ?>/helveticaneue-light-webfont.ttf) format('truetype'),
            url(<?PHP echo base_url( 'asset/fonts' ); ?>/helveticaneue-light-webfont.svg) format('svg');
            /* url(<?PHP echo base_url( 'asset/fonts' ); ?>/helveticaneue-light-webfont.eot') format('eot'); */
        }
    </style>
    <style type = "text/css" >
        td, th, div, a, span, h1, h2, h3, h4, h5, h6{ font-family:helveticaneue-light; }
        .sidebar-toggle span{ font-size:1.2em; margin-left:10px; }

        .main-header .sidebar-toggle:before{ content:' '}
        .main-header>.navbar { margin-left:0px; }
        .content-wrapper, .right-side, .main-footer{ margin-left:0px; }
    </style>
  </head>
  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

      <header class="main-header">
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <a href="<?PHP echo base_url('statis'); ?>" class="sidebar-toggle" >
            <i class="fa fa-line-chart"></i><span>Dashboard</span>
          </a>
          <a href="<?PHP echo base_url('product'); ?>" class="sidebar-toggle" >
            <i class="fa fa-car"></i><span>Products</span>
          </a>
          <a href="<?PHP echo base_url('customer'); ?>" class="sidebar-toggle" >
            <i class="fa fa-user"></i><span>Customers</span>
          </a>
          <a href="<?PHP echo base_url('offer'); ?>" class="sidebar-toggle" >
            <i class="fa fa-youtube"></i><span>Offers</span>
          </a>
          
        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->