//var endpoint = "http://172.16.200.10/survey";

var endpoint = "https://www.bmbonlineassets.com/app_video";

if (typeof(shop) == 'undefined') {
    var shop = Shopify.shop;
}

var upsellvideo_when = 'cart';
var upsellvideo_isAjax = false;

// continue process
function upsellvideo_continue()
{
  // If it's not ajax, jump the the cart page/checkout page
  if( !upsellvideo_isAjax )
  {
    switch( upsellvideo_when ){
      case 'cart':
        $('form[action="/cart/add"]').submit();
        break;
      case 'checkout':
        window.location.href = "/checkout";
        break;
    }
  }
}

// Display Video
function upsellvideo_display_offer( strVariantIds, when, isAjax )
{
  // Keep the status
  upsellvideo_isAjax = isAjax;
  upsellvideo_when = when;
  
  // Request the offer
  var access_url =  endpoint + '/endpoint/request/' + shop + '/' + strVariantIds + '/' + when;

  $.ajax({
    url: access_url,
    type: 'GET'
  }).done(function(data) {
    console.log( data );
    
    // If there is an offer
    if( data.offer_id == 0 )
    {
      upsellvideo_continue();
    }
    else
    {
      // Access the view Request
      var access_url =  endpoint + '/endpoint/add_view/' + shop + '/' + data.offer_id + '/' + data.product_id + '/' + when;

      $.ajax({
        url: access_url,
        type: 'GET',
        data : {
        }
      }).done(function(data1) {

        // Add  the video to iframe : http://www.youtube.com/embed/a0qMe7Z3EYg?rel=0
        $('#upsellvideo_mask').show();
        $('#upsellvideo_iframe').show();
        $('#upsellvideo_iframe').attr( 'src', data.offer_url );

      }).error(function(data1) {
        console.log( data1 );
      });
    }
  }).error(function(data1) {
    console.log( data1 );
  });
}

function upsellvideo_ajaxcart()
{
  // Get the variants from the cart
  jQuery.getJSON('/cart.js', function (cart, textStatus) {
    console.log( cart );

    if( cart.items.length > 0 )
    {
      // Access Main Process    
      upsellvideo_display_offer( data.cart[0].variant_id, 'cart', true );
    }
  });  
}

function upsellvideo_initialize()
{
  // Add the HTML to the body tag
  var html = '';
  html = html + "<div id = 'upsellvideo_mask'>";
  html = html + '<a id ="upsellvideo_close-modal">X</a>';
  html = html + "<div id = 'upsellvideo_wrapper'>";
  html = html + '<iframe width="480" height="360" src="" frameborder="0" allowfullscreen id = "upsellvideo_iframe"></iframe>';
  html = html + "</div>";
  html = html + "</div>";

  $('body').append(html);
  
  // Load the CSS
  $('body').append('<link rel="stylesheet" href="' + endpoint + '/asset/popup/style.css" >');
  
  // Add to cart event handler
  $('form[action="/cart/add"]').find('button[type=submit]').click( function( e ){
    e.preventDefault();

    console.log( 'click checkout' );
    
    var variant_id = $('form[action="/cart/add"]').find('select[name=id]').val();
    
    // Access Main Process    
    upsellvideo_display_offer( variant_id, 'cart', false );
  });
  
  
  // Checkout event handler
  var funcCheckoutClick = function(e){
    e.preventDefault();
    
    // Get the variants from the cart
    jQuery.getJSON('/cart.js', function (cart, textStatus) {
      console.log( cart );
      
      var arrVariantId = new Array();
      for( var i = 0; i < cart.items.length; i ++ )
      {
        arrVariantId.push( cart.items[i].variant_id );
      }
      
      // Access Main Process    
      upsellvideo_display_offer( arrVariantId.join('_'), 'checkout', false );
    });
  };
  
  $('input[name=checkout]').click( funcCheckoutClick );
  $('button[name=checkout]').click( funcCheckoutClick );
  
  // Close vent handler
  var funcCloseModal = function(){
    
    // Close the popup
    $('#upsellvideo_mask').hide();
    $('#upsellvideo_iframe').hide();
    
    // Continue Process
    upsellvideo_continue();
  }
  
  $('#upsellvideo_mask').click( funcCloseModal );
  $('#upsellvideo_close-modal').click( funcCloseModal );  
}

function yourFunctionToRun(){
  $(document).ready( function (){
    upsellvideo_initialize();
  });
}

function runYourFunctionWhenJQueryIsLoaded() {
  if (window.$){
    //possibly some other JQuery checks to make sure that everything is loaded here
    yourFunctionToRun();
  } else {
    setTimeout(runYourFunctionWhenJQueryIsLoaded, 100);
  }
}

runYourFunctionWhenJQueryIsLoaded();
