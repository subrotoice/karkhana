// Voca Details will show on button click 44
$( document ).on( 'click', 'a#activePayment', function(evt) {
  evt.preventDefault();
  var values = $(this).data('userid');
  var url = "activePayment.php?q=" + values;
  alert(url); 
  $.get( url, function( data ) {
    $('.studentEntry').hide();
    $( ".studentDetails" ).show( );
    $( ".studentDetails" ).html( data );
  });
});