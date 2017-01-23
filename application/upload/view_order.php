<?php
$arrStatus = $this->config->item( 'ARR_ORDER_STATUS');

$config['base_url'] = base_url( $this->config->item('index_page') . '/order/' . $fulfillment_status . '/' );
$config['total_rows'] = $total_count;
$config['per_page'] = $sel_page_size; 
$config['num_links'] = 4;

$config['first_link'] = 'First';
$config['first_tag_open'] = '<li class="paginate_button previous" id="example1_previous">';
$config['first_tag_close'] = '</li>';

$config['last_link'] = 'Last';
$config['last_tag_open'] = '<li class="paginate_button next" id="example1_previous">';
$config['last_tag_close'] = '</li>';

$config['prev_link'] = '&lt;';
$config['prev_tag_open'] = '<li class="paginate_button ">';
$config['prev_tag_close'] = '</li>';

$config['next_link'] = '&gt;';
$config['next_tag_open'] = '<li class="paginate_button ">';
$config['next_tag_close'] = '</li>';

$config['num_tag_open'] = '<li class="paginate_button ">';
$config['num_tag_close'] = '</li>';

$config['cur_tag_open'] = '<li class="paginate_button active " disabled><a href = "#" disabled>';
$config['cur_tag_close'] = '</a></li>';

$this->pagination->initialize($config); 

$summary = 'Showing ' . ( $page + 1 ) . ' to ' . ( $page + $sel_page_size > $total_count ? $total_count : $page + $sel_page_size ) . ' of ' . $total_count . ' orders';
?>
<style>
</style>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Order
    <small>List</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Order</li>
  </ol>
</section>

<!-- Main content -->

<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
            <div class="col-md-12 column"  style = "border-bottom:solid 1px #ddd; margin-bottom:4px; padding-bottom: 5px;" >
            <form style="display: inline" class = 'form-inline' id = 'frmSearch' action="<?php echo base_url($this->config->item('index_page') . '/order/' . $fulfillment_status ) ?>" method = "post" >
                <label>Customer</label>&nbsp;:&nbsp;
                <input type = 'text' class="form-control input-group-sm" id = 'sel_customer_name' name = 'sel_customer_name' value = "<?PHP echo $sel_customer_name; ?>" style = "width:150px;" >
                &nbsp;&nbsp;
                <label>Company</label>&nbsp;:&nbsp;
                <input type = 'text' class="form-control input-group-sm" id = 'sel_company' name = 'sel_company' value = "<?PHP echo $sel_company; ?>" style = "width:150px;" >
                &nbsp;&nbsp;
                <label>Address</label>&nbsp;:&nbsp;
                <input type = 'text' class="form-control input-group-sm" id = 'sel_address' name = 'sel_address' value = "<?PHP echo $sel_address; ?>" style = "width:200px;" >
                &nbsp;&nbsp;
                <label>Page Size</label>&nbsp;:&nbsp;
                <?PHP echo form_dropdown('sel_page_size', array( 30 => 30, 50 => 50, 70 => 70, 100 => 100 ), $sel_page_size, 'id="sel_page_size" class="form-control input-group-sm"' ); ?>
                
                <button type = "submit" class = "btn btn-info" ><i class="glyphicon glyphicon-search" ></i></button>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <button type = "button" class = "btn btn-warning btn_sync" >Sync Orders</button>
                
                <input type = hidden id = 'sel_sort_field' name = 'sel_sort_field' value = '<?PHP echo $sel_sort_field;?>' >
                <input type = hidden id = 'sel_sort_direction' name = 'sel_sort_direction' value = '<?PHP echo $sel_sort_direction;?>' >
            </form>
            </div>
            <div id = 'ret' class="col-md-12 column" ></div>
        </div><!-- /.box-header -->
        
        <!-- Pagenation -->
        <div class = 'box-body' style = "padding:0px 10px;">
            <div class="col-sm-5">
                <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">
                    <?php echo $summary ; ?>    
                </div>
            </div>
            <div class="col-sm-7">
                <div class="dataTables_paginate paging_simple_numbers" id="example1_paginate">
                    <ul class="pagination">
                        <?php echo $this->pagination->create_links(); ?>
                    </ul>
              </div>
            </div>
        </div>
        <div class="box-body">
          <table id="example2" class="table table-bordered table-hover">
            <thead>
              <tr class = "text-center">
                <th class = "text-center" >No.</th>
                <th class = "text-center" >Order No</th>
                <th class = "text-center" ><a href = "javascript:sort('customer_name');" >Name</a></th>
                <th class = "text-center" ><a href = "javascript:sort('address');" >Address</a></th>
                <th class = "text-center" >Total</th>
                <th class = "text-center" >Status</th>
                <th class = "text-center" ><a href = "javascript:sort('created_at');" >Checkout Date</a></th>
              </tr>
            </thead>
            <tbody>
            <?php $sno = $page;
            foreach ($query->result() as $row): ?>
              <tr class="tbl_view text-center" >
                <td><?php echo $sno; ?></td>
                <td class = 'text-left' ><a href = "<?= base_url( 'order/detail/' . $row->id ) ?>" ><?=$row->order_name ?></a></td>
                <td><?=$row->customer_name ?></td>
                <td><?=$row->address ?></td>
                <td>$<?=$row->amount ?></td>
                <td><?=$row->financial_status ?></td>
                <td><?=$row->created_at ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div><!-- /.col -->
  </div><!-- /.row -->
  
  <!-- Pagenation -->
  <div class="row">
    <div class="col-sm-5">
        <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">
            <?php echo $summary ; ?>    
        </div>
    </div>
    <div class="col-sm-7">
        <div class="dataTables_paginate paging_simple_numbers" id="example1_paginate">
            <ul class="pagination">
                <?php echo $this->pagination->create_links(); ?>
            </ul>
      </div>
    </div>
  </div><!-- /.row -->  

<script>
var sel_product;

$(document).ready(function(){

  // ********************************* //

  // Sync Button Config
  $('.btn_sync').btn_init(
    'sync',
    { class : 'btn-warning', caption : 'Sync' },
    { class : 'btn-default fa fa-spinner', caption : '' },
    { class : 'btn-success', caption : 'Done' },
    { class : 'btn-danger', caption : 'Error' }
  );

  // Sync category
  $('.btn_sync').click(function(){
    $(this).btn_action( 'sync', 'pending' );
    $.ajax({
      url: '<?php echo base_url($this->config->item('index_page') . '/order/sync') ?>',
      type: 'GET'
    }).done(function(data) {
      console.log( data );
      if( data == 'success' )
      {
        $('.btn_sync').btn_action( 'sync', 'success' );
        
        setTimeout( function(){
                window.location.reload();
            }, 1000
        );
      }
      else
      {
        $('.btn_sync').btn_action( 'sync', 'error' );  
      }
    });
    
    event.preventDefault();
  });
  
  // Save button
  $('.btn_save').click( function(){
    var id = $(this).attr( 'data-id' );
    
    $.ajax({
      url: '<?php echo base_url($this->config->item('index_page') . '/order/updateDelivery') ?>/' + id,
      data : {
        delivery_date : $('#delivery_date_' + id).val(),
        delivery_time : $('#delivery_time_' + id).val(),
      },
      type: 'POST'
    }).done(function(data) {
      window.location.reload();
    });    
  });
  
  // Datepicker   
  $(".datepicker").datepicker({
    format: 'DD d MM'
  });
  
});

function sort( field )
{
    $('#sel_sort_field').val( field );
    $('#sel_sort_direction').val( $('#sel_sort_direction').val() == 'ASC' ? 'DESC' : 'ASC' );
    
    $('#frmSearch').submit();
}

</script>