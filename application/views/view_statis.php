<?PHP
    $arrAlphabet = array( 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K' );
    
    $arrLine = array();
    foreach( $arrStatis['arrLine'] as $date => $item )
    {
      $arrLine[] = array(
        'date' => $date,
        'purchase' => $item['purchase'],
        'view' => $item['view'],
      );
    }
?>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Dashboard
    <small>Report</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Dashboard</li>
  </ol>
</section>

<!-- Main content -->

<section class="content">
  <div class = 'row'>
    <?PHP if( count( $arrStatis['arrOffer'] ) >= 0 ) : ?>
    <div class="col-xs-12">
      <div class="box box-warning">
        <div class="box-body" style = "font-size: 1.2em; font-weight: bold;">
          <img src = "<?PHP echo base_url( "asset/alert.png" );?>" style = "margin-left: 15px; margin-right: 30px;">To get started, please enter your offer &nbsp;<a href = "<?PHP echo base_url('offer'); ?>" ><b>here</b></a>
        </div>
      </div>
    </div>
    <?PHP endif; ?>    
    <div class="col-xs-4">
      <div class="box box-info">
        <div class="box-header with-border">
        <form style="display: inline" class = 'form-inline' id = 'frmSearch' action="<?php echo base_url('statis') ?>" method = "post" >
          <div class="input-group">
            <div class="input-group-addon">
              <i class="fa fa-calendar"></i>
            </div>
            <input type="text" class="form-control pull-right active" id="sel_date" name = 'sel_date' value = "<?PHP echo $sel_date; ?>" style = "width:200px;"  >
          </div><!-- /.input group -->
          &nbsp;
          
          <button type = "submit" class = "btn btn-info" ><i class="glyphicon glyphicon-search" ></i></button>
          &nbsp;&nbsp;
          <div class="btn-group">
            <button type="button" class="btn <?PHP echo $settings_onoff['value'] == 1 ? 'btn-default' : 'btn-success'; ?> btn-onoff" status = 'off' >OFF</button>
            <button type="button" class="btn <?PHP echo $settings_onoff['value'] != 1 ? 'btn-default' : 'btn-success'; ?> btn-onoff" status = 'on' >ON</button>
          </div>
        </form>
          
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <table id="tblKnob" class="table table-bordered table-hover">
            <tbody>
              <tr>
                <td class = 'title' valign="middle" >Total Purchase</td>
                <td class = 'text-center'>
                  <!--input type="text" class="knob" value="<?PHP echo $arrStatis['totalPurchaseOffer']; ?>" data-max="<?PHP echo $arrStatis['totalPurchase']; ?>" data-thickness="0.15" data-width="127" data-height="127" data-fgColor="#009fe3" readonly -->
                  $<?= $arrStatis['totalPurchaseOffer']; ?>
                </td>
              </tr>
              <tr valign="middle">
                <td class = 'title' >Number of Offer views</td>
                <td class = 'text-center'>
                  <!--div id = "numOfferView" style = "height:170px;" ></div-->
                  Cart : <?= $arrStatis['numCart'] ?><br>
                  Checkout : <?= $arrStatis['numCheckout'] ?><br>
                </td>
              </tr>
            </tbody>
          </table>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div>
    <div class="col-xs-8">
      <div class = "box" >
        <div class="box-body">
          <div class="chart">
            <div id="chartdiv" ></div>
          </div>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div>
    <div class="col-xs-12">
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Purchase</a></li>
          <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">Offer View</a></li>
          <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab_1">
            <table id="example2" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th class = "text-center" >S. NO.</th>
                  <th class = "text-center" >Order Name</th>
                  <th class = "text-center" >Customer Name</th>
                  <th class = "text-center" >Checkout Total</th>
                  <th class = "text-center" >Offer Total</th>
                  <th class = "text-center" >Offer Name</th>
                  <th class = "text-center" >Offer Url</th>
                  <th class = "text-center" >Checkout Date</th>
                </tr>
              </thead>
              <tbody>
              <?php $sno = 1; ?>
                <?php foreach ($arrStatis['arrOrder'] as $item): ?>
                
                <tr class="tbl_view text-center" >
                  <td>
                    <?php echo $sno; ?>
                  </td>
                  <td><?= $item['order_name']?></td>
                  <td><?= $item['customer_name']?></td>
                  <td>$<?= $item['amount']?></td>
                  <td><?= $item['amount_offer']?></td>
                  <td><?= $arrStatis['arrOffer'][$item['offer_id']]->title?></td>
                  <td><?= $arrStatis['arrOffer'][$item['offer_id']]->url?></td>
                  <td><?= $item['created_at']?></td>
                </tr>
               <?php $sno = $sno+1;  endforeach; ?>
              </tbody>
            </table>
          </div><!-- /.tab-pane -->
          <div class="tab-pane" id="tab_2">
            <table id="example2" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th class = "text-center" >S. NO.</th>
                  <th class = "text-center" >Product</th>
                  <th class = "text-center" >Trigger</th>
                  <th class = "text-center" >Offer Name</th>
                  <th class = "text-center" >Offer Url</th>
                  <th class = "text-center" >View Date</th>
                </tr>
              </thead>
              <tbody>
              <?php $sno = 1; ?>
                <?php foreach ($arrStatis['arrView'] as $item): ?>
                
                <tr class="tbl_view text-center" >
                  <td>
                    <?php echo $sno; ?>
                  </td>
                  <td><?= $item['product_title']?></td>
                  <td><?= $item['when']?></td>
                  <td><?= $arrStatis['arrOffer'][$item['offer_id']]->title?></td>
                  <td><?= $arrStatis['arrOffer'][$item['offer_id']]->url?></td>
                  <td><?= $item['process_date']?></td>
                </tr>
               <?php $sno = $sno+1;  endforeach; ?>
              </tbody>
            </table>
          </div><!-- /.tab-pane -->
        </div><!-- /.tab-content -->
      </div>    
    </div><!-- /.col -->    
  </div>

<style>
#chartdiv {
  width: 100%;
  height: 300px;
  font-size: 11px;
}

#tblKnob .title{ font-size:30px; text-align:center; }                            
</style>  
<script src="<?PHP echo base_url( 'js/amcharts/amcharts.js' ); ?>"></script>
<script src="<?PHP echo base_url( 'js/amcharts/serial.js' ); ?>"></script>
<script src="<?PHP echo base_url( 'js/amcharts/themes/light.js' ); ?>"></script>
<script>

$(document).ready(function(){
    
  /* jQueryKnob */

  $(".knob").knob({
    /*change : function (value) {
     //console.log("change : " + value);
     },
     release : function (value) {
     console.log("release : " + value);
     },
     cancel : function () {
     console.log("cancel : " + this.value);
     },*/
    draw: function () {

      // "tron" case
      if (this.$.data('skin') == 'tron') {

        var a = this.angle(this.cv)  // Angle
                , sa = this.startAngle          // Previous start angle
                , sat = this.startAngle         // Start angle
                , ea                            // Previous end angle
                , eat = sat + a                 // End angle
                , r = true;

        this.g.lineWidth = this.lineWidth;

        this.o.cursor
                && (sat = eat - 0.3)
                && (eat = eat + 0.3);

        if (this.o.displayPrevious) {
          ea = this.startAngle + this.angle(this.value);
          this.o.cursor
                  && (sa = ea - 0.3)
                  && (ea = ea + 0.3);
          this.g.beginPath();
          this.g.strokeStyle = this.previousColor;
          this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea, false);
          this.g.stroke();
        }

        this.g.beginPath();
        this.g.strokeStyle = r ? this.o.fgColor : this.fgColor;
        this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false);
        this.g.stroke();

        this.g.lineWidth = 2;
        this.g.beginPath();
        this.g.strokeStyle = this.o.fgColor;
        this.g.arc(this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false);
        this.g.stroke();

        return false;
      }
    }
  });
  /* END JQUERY KNOB */
  
  /*
   * DONUT CHART
   * -----------
   */
  /*
  var donutData = [
    {label: "Cart", data: <?php echo $arrStatis['numCart']; ?>, color: "#0073b7"},
    {label: "Checkout", data: <?php echo $arrStatis['numCheckout']; ?>, color: "#00c0ef"}
  ];
  $.plot("#numOfferView", donutData, {
    series: {
      pie: {
        show: true,
        radius: 1,
        innerRadius: 0.5,
        label: {
          show: true,
          radius: 2 / 3,
          formatter: labelFormatter,
          threshold: 0.1
        }

      }
    },
    legend: {
      show: false
    }
  });
  /*
   * END DONUT CHART
   */  

  /********* Data Chart **********/
  var chart = AmCharts.makeChart("chartdiv", {
    "type": "serial",
    "theme": "light",
    "legend": {
        "equalWidths": false,
        "useGraphSettings": true,
        "valueAlign": "left",
        "valueWidth": 120
    },
    "dataProvider": <?php echo json_encode( $arrLine ); ?>,
    "valueAxes": [{
        "id": "purchaseAxis",
        "axisAlpha": 0,
        "gridAlpha": 0,
        "position": "left",
        "title": "Total Purchase"
    }, {
        "id": "durationAxis",
        "axisAlpha": 0,
        "gridAlpha": 0,
        "inside": true,
        "position": "right",
        "title": "Offer Viewed"
    }],
    "graphs": [{
        "alphaField": "alpha",
        "bullet": "round",
        "balloonText": "$[[value]]",
        "dashLengthField": "dashLength",
        "fillAlphas": 0.7,
        "legendPeriodValueText": "total: $ [[value.sum]]",
        "legendValueText": "$[[value]]",
        "title": "Total Purchase",
        "valueField": "purchase",
        "valueAxis": "purchaseAxis"
    }, {
        "bullet": "round",
        "bulletBorderAlpha": 1,
        "useLineColorForBulletBorder": true,
        "bulletBorderThickness": 1,
        "bulletColor": "#FFFFFF",
        "dashLengthField": "dashLength",
        "legendValueText": "[[value]]",
        "title": "Offer Viewed",
        "fillAlphas": 0,
        "valueField": "view",
        "valueAxis": "durationAxis"
    }],
    "chartCursor": {
        "categoryBalloonDateFormat": "DD",
        "cursorAlpha": 0.1,
        "cursorColor":"#000000",
         "fullWidth":true,
        "valueBalloonsEnabled": false,
        "zoomable": false
    },
    "dataDateFormat": "YYYY-MM-DD",
    "categoryField": "date",
    "categoryAxis": {
        "dateFormats": [{
            "period": "DD",
            "format": "DD"
        }, {
            "period": "WW",
            "format": "MMM DD"
        }, {
            "period": "MM",
            "format": "MMM"
        }, {
            "period": "YYYY",
            "format": "YYYY"
        }],
        "parseDates": true,
        "autoGridCount": false,
        "axisColor": "#555555",
        "gridAlpha": 0.1,
        "gridColor": "#FFFFFF",
        "gridCount": 50
    },
    "export": {
      "enabled": true
     }
  });
  
  chart.addListener("rendered", zoomChart);

  zoomChart();

  function zoomChart() {
      chart.zoomToIndexes(chart.dataProvider.length - 40, chart.dataProvider.length - 1);
  }
  
  // ********************************* //
  //Initialize Select2 Elements
  $(".select2").select2();
  
  // On Off
  $('.btn-onoff').click( function(){
      
      // Change the color
      $('.btn-onoff').toggleClass( 'btn-default btn-success' );
      
      // Change the status
      $.ajax({
          url: '<?PHP echo base_url( 'settings/updateValue/' . $settings_onoff['pk'] ); ?>',
          type: 'POST',
          data : {
              value : $(this).attr('status') == 'on' ? 1 : 0,
          }
      }).done(function(data1) {
          console.log( data1 );
      });
  })
  $('#sel_date').daterangepicker(
      {
        ranges: {
          'Today': [moment(), moment()],
          'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days': [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month': [moment().startOf('month'), moment().endOf('month')],
          'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        format : 'YYYY-MM-DD',
        startDate: moment().subtract(29, 'days'),
        endDate: moment()
      },
      function (start, end) {
        $('#sel_date').html(start.format('YYYY-MM-D') + ' - ' + end.format('YYYY-MM-D'));
      }
  );    
 
  // Selection of question
  $('.select2').change( function(){
      drawChart( $(this).val() )
  });
  
});

function labelFormatter(label, series) {
  return '<div style="font-size:13px; text-align:center; padding:2px; color: #fff; font-weight: 600;">'
          + label
          + "<br>"
          + Math.round(series.percent) + "%</div>";
}

</script>