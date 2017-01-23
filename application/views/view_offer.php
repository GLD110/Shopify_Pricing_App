<?php
  $arrWhen = array(
    'cart' => 'Add to Cart',
    'checkout' => 'Checkout',
  );
?>
<!-- Content Header (Page header) -->
<section class="content-header">
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
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
        </div><!-- /.box-header -->
        <div class="col-md-12 column"  style = "border-bottom:solid 1px #ddd; margin-bottom:4px; padding-bottom: 5px;" >
          <a id="modal-666931" href="<?php echo base_url() ?>offer/view_edit" role="button" class="btn btn-default btn-sm" data-toggle="modal">
            <i class="glyphicon glyphicon-plus"></i>&nbsp; Add new Offer
          </a>&nbsp;
        </div>
        <div class="box-body">
          <table id="example2" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th class = "text-center" >S. NO.</th>
                <th class = "text-center" >Title</th>
                <th class = "text-center" >Url</th>
                <th class = "text-center" >Trigger</th>
                <th class = "text-center" >Created At</th>
                <th class = "text-center" >&nbsp;</th>
              </tr>
            </thead>
            <tbody>
            <?php $sno = 1; ?>
              <?php foreach ($query->result() as $row): ?>
              
              <tr class="tbl_view text-center" >
                <td>
                  <?php echo $sno; ?>
                </td>
                <td>
                  <a href="#" class="editText" data-type="text" data-pk="<?= $row->id?>" data-url="<?php echo base_url( 'offer/update/title/' . $row->id ) ?>" data-title="Enter new Title"><?php echo $row->title; ?></a>
                </td>
                <td>
                  <a href="#" class="editText" data-type="text" data-pk="<?= $row->id?>" data-url="<?php echo base_url( 'offer/update/url/' . $row->id ) ?>" data-title="Enter new URL"><?php echo $row->url; ?></a>
                </td>
                <td>
                  <a href="#" class="trigger" data-type="select" data-pk="<?= $row->id?>" data-url="<?php echo base_url( 'offer/update/when/' . $row->id ) ?>" data-title="Select Trigger"><?php echo $arrWhen[$row->when]; ?></a>
                </td>
                <td>
                  <?PHP echo date( 'Y-m-d', strtotime( $row->create_date )); ?>
                </td>
                <td>
                  <div class="btn-group">
                    <a href="<?php echo base_url() ?>offer/view_edit/<?=$row->id?>"  role="button" data-toggle="modal" class="btn btn-primary btn-sm" title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>
                    <button  class="btn btn-danger btn-sm btn_delete"  type="submit" title="Delete" del_id = '<?PHP echo $row->id; ?>' >
                    <i class="glyphicon glyphicon-remove"></i></button>
                   </div>
                </td>
              </tr>
             <?php $sno = $sno+1;  endforeach; ?>
            </tbody>
          </table>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div><!-- /.col -->
  </div><!-- /.row -->
</section><!-- /.content -->
        
<form method="POST" id='deluser' action="<?php echo base_url() ?>offer/del" >
    <input type="hidden" id = 'del_id' name="del_id" value=""/>
</form>

<a class="confirmLink" href="#"></a>
<div id="dialog" title="Confirmation Required" style = "display:none;">
  Are you sure want to delete?
</div>

<script>
$("#modal-container-666931").on('hidden.bs.modal', function(e){window.location.reload();});

var selProduct = '';
        
// Delete variant - Remove the tr
function delVariant( variant_id ){
  $('#tr_' + variant_id).remove();
}
        
$(document).ready(function (){
  // Editable
  $('.editText').editable();
  $('.trigger').editable({
     source: [
     <?php
     foreach( $arrWhen as $key => $val ) echo '{value: "' . $key . '", text: "' . $val . '"},';
     ?>
    ]
  });
                  
  // ********** Delete Action ********** //
  $(".btn_delete").on('click', function (e){
    e.preventDefault();
    console.log('dele');
    $('#del_id').val( $(this).attr( 'del_id' ) );
    $('.confirmLink').trigger('click'); return false;
  });    

  $("#dialog").dialog({
      autoOpen: false,
      modal: true
  });

  $(".confirmLink").click(function(e) {
    e.preventDefault();
    var targetUrl = $(this).attr("href");

    $("#dialog").dialog({
      buttons : {
      "Confirm" : function() {
        $(this).dialog("close");
        $("#deluser").submit();
      },
      "Cancel" : function() {
        $(this).dialog("close");
        return false;
        }
      }
    });

    $("#dialog").dialog("open");
  });
  
  // ********************************* //
});
</script>