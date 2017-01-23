<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Settings
    <small>Manage</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Settings</li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
        </div><!-- /.box-header -->
        <div class="box-body">
          <table id="example2" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th class = "text-center" >S. NO.</th>
                    <th class = "text-center" >Name</th>
                    <th class = "text-center" >Description</th>
                    <th class = "text-center" >Value</th>
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
                        <?=$row->name ?>
                    </td>
                    <td>
                        <?=$row->description ?>
                    </td>
                    <td>
                        <?PHP if( $row->value_type == 'custom'): ?>
                            <input type = 'text' id = 'edit_<?PHP echo $row->name; ?>' db_id = '<?PHP echo $row->id; ?>' name = 'edit_<?PHP echo $row->name; ?>' value = '<?PHP echo $row->value; ?>' class=" my-colorpicker1 colorpicker-element" >
                            <button class = 'btn btn-primary' id = 'btn_<?PHP echo $row->name; ?>'>Save</button>
                        <?PHP else: ?>
                        <a href="#" id="edit_<?PHP echo $row->id; ?>" data-type="<?PHP echo $row->value_type; ?>" data-pk="<?= $row->id?>" data-url="<?php echo base_url( 'settings/updateValue/' . $row->id ) ?>" data-title="Select Value">
                            <?php
                            $value = $row->value;
                            switch( $row->value_type )
                            {
                                case 'custom':
                                    break;
                                case 'select':
                                    $temp = json_decode( $row->value_scope, true );
                                    $value = $temp[ $row->value ];
                                    break;
                                case 'text':
                                default:
                                    break;
                            }
                            echo $value;
                            ?>
                        </a>
                        <?PHP endif; ?>
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
        
<script>

$(document).ready(function (){
<?php
    foreach ($query->result() as $row)
    {
        switch( $row->value_type )       
        {
            case 'text':
                echo '$("#edit_' . $row->id . '").editable();';
                break;
            case 'select':
                $temp = json_decode( $row->value_scope, true );
                echo '$("#edit_' . $row->id . '").editable({';
                echo '   source: [';
                foreach( $temp as $key => $val )
                    echo '      {value: "' . $key . '", text: "' . $val . '"},';
                echo '  ]';
                echo '});';
                break;
        }
    }
?>    

    //Colorpicker
    $("#edit_headline_color").colorpicker();
    
    // Map Button Config
    $('#btn_headline_color').btn_init(
        'save',
        { class : 'btn-primary', caption : 'Map' },
        { class : 'btn-default fa fa-spinner', caption : '' },
        { class : 'btn-success', caption : 'Save' },
        { class : 'btn-danger', caption : 'Error' }
    );
    $("#btn_headline_color").click(function(){
       $(this).btn_action( 'save', 'pending' );
        $.ajax({
            url: '<?php echo base_url('settings/updateValue') ?>/' + $("#edit_headline_color").attr( 'db_id' ),
            data: {value:$("#edit_headline_color").val()},
            type: 'post'
        }).done(function(){
            $("#btn_headline_color").btn_action( 'save', 'success' );
        });
    });

});
</script>