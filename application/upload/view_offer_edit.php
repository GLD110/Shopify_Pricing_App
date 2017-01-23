<?php
  $arrWhen = array(
    'cart' => 'Add to Cart',
    'checkout' => 'Checkout',
  );
?>
<!-- Content Header (Page header) -->
<section class="content-header" style = "width:50%; margin: 0px auto;" >
  <h1>
    Offers
    <small>Manage</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Offers</li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-3"></div>
    <div class="col-xs-6">
      <div class="box">
        <div class="box-header">
          <?php echo $offer_id == 0 ? 'Add new offer' : 'Edit your offer'; ?>
        </div><!-- /.box-header -->
        <div class="box-body">
          <div id='retAddTransaction'></div>
        
          <form class="form-horizontal cus-form" id="Add_transaction" method="POST" action="<?php echo base_url().'offer/' . ( $offer_id == 0 ? 'add' : 'update_offer/' . $offer_id ) ?>"  data-parsley-validate>
            <table class="table table-bordered">
            <tr>
              <td colspan="2">
                <label>Title</label>
                <input type="text" name="title" id='title' class="form-control input-group-sm" value = "<?php if( $offer_id != 0 ) echo $obj->title; ?>" required/>
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <label>Youtube URL</label>
                <input type="text" name="url" id='url' class="form-control input-group-sm" value = "<?php if( $offer_id != 0 ) echo $obj->url; ?>" required/>
              </td>
            </tr>
            <tr>
              <td>
                <label>Trigger</label>
                <div class="tran-type">
                    <label>Add to Cart</label>&nbsp;&nbsp;<input type="radio" value="cart" name="when" <?php if( $offer_id != 0 && $obj->when == "cart" ) echo "checked"; ?> style="display: inline">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <label>Checkout</label>&nbsp;&nbsp;<input type="radio" value="checkout" name="when" <?php if( $offer_id != 0 && $obj->when == "checkout" ) echo "checked"; ?> style="display: inline">
                </div>
              </td>
            </tr>
            <tr>
              <td colspan = "2" >
                <label>Products</label>
                <table class="table table-bordered tblVariant">
                  <thead>
                    <tr>
                      <th>Title</th>
                      <th>Offer</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php if( $offer_id != 0 ): ?>
                  <?php foreach( $arrProduct as $product_id => $item ) :?>
                    <?php if( $item['offer_id_cart'] != $offer_id && $item['offer_id_checkout'] != $offer_id ) continue; ?>
                    <tr class = "tr_variant" variant_id = "<?php echo $item['product_id']; ?>" id = "tr_<?php echo $item['product_id']; ?>">
                      <td><?php echo $item['title']; ?></td>
                      <td><?php echo $item['offer']; ?></td>
                      <td class = "text-center" ><a href = "javascript:delVariant('<?php echo $item['product_id']; ?>');" class = "btn fa fa-minus-circle btn-danger btn_delete" variant_id = "' + variant_id + '" ></a></td>
                    </tr>
                  <?php endforeach; ?>
                  <?php endif ; ?>
                  <tr class = "tr_new">
                    <td colspan = '2'>
                      <div id='jqxWidget'>
                        <div id="jqxdropdownbutton">
                          <div style="border-color: transparent;" id="jqxgrid">
                          </div>
                        </div>
                      </div>
                    </td>
                    <td class = "text-center" ><a href = "#" class = "btn fa fa-plus-circle btn-success btn_add" ></a></td>
                  </tr>
                  </tbody>
                </table>                      
                <input type = 'hidden' name = 'product_ids' id = 'product_ids' value = '' >
              </td>
            </tr>
            </table>
            <div style="padding-left: 10px; padding-bottom: 10px; margin-top: -8px;">
              <button type="submit" name="submit" class="btn btn-success">Save</button>
              <button name="cancle" class="btn btn-warning btn_cancel" aria-hidden="true">Cancel</button>                
            </div>
          </form>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div><!-- /.col -->
    <div class="col-xs-3"></div>
  </div><!-- /.row -->
</section><!-- /.content -->
        
<link rel="stylesheet" href="<?PHP echo base_url( 'asset/jqwidgets' ); ?>/jqx.base.css" type="text/css" />
<script type="text/javascript" src="<?PHP echo base_url( 'asset/jqwidgets' ); ?>/jqxcore.js"></script>
<script type="text/javascript" src="<?PHP echo base_url( 'asset/jqwidgets' ); ?>/jqxdata.js"></script>
<script type="text/javascript" src="<?PHP echo base_url( 'asset/jqwidgets' ); ?>/jqxbuttons.js"></script>
<script type="text/javascript" src="<?PHP echo base_url( 'asset/jqwidgets' ); ?>/jqxscrollbar.js"></script>
<script type="text/javascript" src="<?PHP echo base_url( 'asset/jqwidgets' ); ?>/jqxmenu.js"></script>
<script type="text/javascript" src="<?PHP echo base_url( 'asset/jqwidgets' ); ?>/jqxgrid.js"></script>
<script type="text/javascript" src="<?PHP echo base_url( 'asset/jqwidgets' ); ?>/jqxgrid.selection.js"></script>
<script type="text/javascript" src="<?PHP echo base_url( 'asset/jqwidgets' ); ?>/jqxgrid.columnsresize.js"></script>
<script type="text/javascript" src="<?PHP echo base_url( 'asset/jqwidgets' ); ?>/jqxlistbox.js"></script>
<script type="text/javascript" src="<?PHP echo base_url( 'asset/jqwidgets' ); ?>/jqxdropdownbutton.js"></script>
<script type="text/javascript" src="<?PHP echo base_url( 'asset/jqwidgets' ); ?>/jqxgrid.pager.js"></script>
<script type="text/javascript" src="<?PHP echo base_url( 'asset/jqwidgets' ); ?>/jqxdropdownlist.js"></script>
<script type="text/javascript" src="<?PHP echo base_url( 'asset/jqwidgets' ); ?>/jqxgrid.filter.js"></script>
<script type="text/javascript" src="<?PHP echo base_url( 'asset/jqwidgets' ); ?>/demos.js"></script>

<script>

/************ Initialize for jqgrid *****************/
var data = <?PHP echo json_encode( $arrProduct ); ?>;
var selProduct = '';
        
// Delete variant - Remove the tr
function delVariant( variant_id ){
  $('#tr_' + variant_id).remove();
}
        
$(document).ready(function (){
  
  /********** JqGridx Init **********/
  var source =
  {
    localdata: data,
    datafields:
    [
        { name: 'title', type: 'string' },
        { name: 'offer', type: 'string' },
        { name: 'product_id', type: 'string' },
    ],
    datatype: "array",
    updaterow: function (rowid, rowdata) {
        // synchronize with the server - send update command   
    }
  };

  /********** Dropdown List **********/
  
    var dataAdapter = new $.jqx.dataAdapter(source);

    $("#jqxgrid").jqxGrid(
    {
      width: 700,
      source: dataAdapter,
      filterable: true,
      pageable: true,
      autoheight: true,
      columnsresize: true,
      columns: [
        { text: 'title', datafield: 'title', columntype: 'textbox', width: 500, cellsalign: 'left', align: 'center' },
        { text: 'offer', datafield: 'offer', columntype: 'textbox', width: 200, cellsalign: 'center', align: 'center' },
      ]
    });

    // initialize jqxGrid
    $("#jqxdropdownbutton").jqxDropDownButton({
        width: 750, height: 25
    });

    $("#jqxgrid").on('rowselect', function (event) {
      var args = event.args;
      var row = $("#jqxgrid").jqxGrid('getrowdata', args.rowindex);
      var dropDownContent = '<div style="position: relative; margin-left: 3px; margin-top: 5px;">' + row['title'] + '</div>';
      $("#jqxdropdownbutton").jqxDropDownButton('setContent', dropDownContent);
      
      // Save the selected variant_id
      selProduct = row['product_id'];
    });

    $("#jqxgrid").jqxGrid('selectrow', 0);
    
    // Add variant
    $('.btn_add').click( function(e){
      e.preventDefault();
      
      var variant_id = selProduct;
      
      // Check it's already exist
      if( $('#tr_' + variant_id ).length > 0 ) return;
      
      // Get the object                
      var str = "var obj = data._" + variant_id + ";";
      eval( str );
      
      // Add html
      var html = '<tr class = "tr_variant" variant_id = "' + variant_id + '" id = "tr_' + variant_id + '">';
      html = html + '<td>' + obj.title + '</td>';
      html = html + '<td>' + obj.offer + '</td>';
      html = html + '<td class = "text-center" ><a href = "javascript:delVariant(\'' + variant_id + '\');" class = "btn fa fa-minus-circle btn-danger btn_delete" variant_id = "' + variant_id + '" ></a></td>';
      html = html + '</tr>';
      
      $('.tblVariant .tr_new').before( html );
    });      
                  
  // ********************************* //
  
  // Add Action
  $( "#Add_transaction" ).submit(function( event ) {
    
    event.preventDefault();
    
    // Get the product list
    var strVariants = '';
    $('.tr_variant').each( function( index, value ){
        
      if( index > 0 ) strVariants = strVariants + ','; 
      strVariants = strVariants + $(this).attr( 'variant_id' );
      
      if( index == $('.tr_variant').length - 1 )
      {
        $('#product_ids').val( strVariants );
      }
    });

    // Access ajax
    var url = $(this).attr('action');
    $.ajax({
      url: url,
      data: $("#Add_transaction").serialize(),
      type: $(this).attr('method')
    }).done(function(data) {
      $('#retAddTransaction').html(data);
      $('#Add_transaction')[0].reset();
      setTimeout( function(){
          window.location.reload();
        },
        2000
      );
    });
  });
  
  $( ".btn_cancel" ).click( function(e){
    e.preventDefault();
    window.location.href = "<?php echo base_url( 'offer' ); ?>";
  });

});
</script>