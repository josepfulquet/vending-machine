$(document).ready(function(){
	
  $("#alert").hide();
	
  let apiUrl = "http://localhost/api.vending-machine/v1/";
  let _products;
  let _change;
  let _vending;
	
  try{
	  
    _products = new Products({'baseHref':apiUrl+'products.php'});
    _change = new Change({'baseHref':apiUrl+'change.php'});
    _vending = new Vending({'baseHref':apiUrl+'vending.php'});
    
    var resProducts = _products.get();
    resProducts.then(res => {
      if (res.data.status === "Accepted"){
	    renderProducts( res.data.message );
      }
      else{
        showMessage( res.data.message );
      }
	});
	
	var resChange = _change.get();
    resChange.then(res => {
      if (res.data.status === "Accepted"){
	    renderChange( res.data.message );
      }
      else{
        showMessage( res.data.message );
      }
	});
	
	var resCurrent = _change.getCurrent();
	resCurrent.then(res => {
      if (res.data.status === "Accepted"){
	    renderCurrent( res.data.message );
      }
      else{
        showMessage( res.data.message );
      }
	});
		
	$('#buttonInsertCoin').on('click', () => {
		var coin = $('#insertCoin').val();
		if (!isNaN(coin)){
			var resInsert = _vending.insertCoin(coin);
			resInsert.then(res => {
				if (res.data.status === "Accepted"){
					renderChange( res.data.message.change );
					renderCurrent( res.data.message.current );
				}
				else{
                  showMessage( res.data.message );
                }
			});
		}
		$("#insertCoin option:selected").prop("selected", false)		
	});
	
	$('#buttonReturnCoin').on('click', () => {
		hideMessage();
		var resReturn = _vending.returnCoins();
		resReturn.then(res => {
			if (res.data.status === "Accepted"){				
				renderChange( res.data.message.change );
				renderReturned( res.data.message.returned );				
			}
			else{
              showMessage( res.data.message );
            }
		});
	});
	
	$('#buttonCollectCoins').click(event => {
		hideMessage();
		$(event.currentTarget).prop('disabled', true);
		$('#amount').html("0,00");
		$('#purchased').html("");
	});
	
	$('.buttonBuy').click(event => {
		var product = $(event.currentTarget).attr('product');
		var productStock = parseInt($('#'+product+'Stock').html());
		var productPrice = parseFloat($('#'+product+'Price').html());
		var amount = parseFloat($('#amount').html());
		if (productStock > 0){
			if (amount >= productPrice){
				var resBuy = _vending.buy(product);
				resBuy.then(res => {
					if (res.data.status === "Accepted"){
						
						renderChange( res.data.message.change );
						renderReturned( res.data.message.returned );
						renderProducts( res.data.message.products );
						renderPurchased ( res.data.message.purchased );
					}
					else{
				        showMessage( res.data.message );
				      }
				});
			}
		}
	});	
	
  }
  catch(e){
    console.log(e);
  }
		   
});

function renderProducts( data ){
  if (Object.keys(data).length > 0){
    $.each ( data, function( kProduct, vProduct ) {
      $("#" + vProduct.product + "Price").html(vProduct.price);
      $("#" + vProduct.product + "Stock").html(vProduct.stock);
    });
  }
}

function renderChange( data ){	
  if (Object.keys( data ).length > 0){
    $.each ( data, function( kChange, vChange ) {
      $("#coin" + vChange.coin).val(vChange.count);	  
    });
  }
}


function renderCurrent( data ){
  var amount = 0;
  if (Object.keys( data ).length > 0){
    $.each ( data, function( kCurrent, vCurrent ) {
      amount = amount + parseFloat(vCurrent.value*vCurrent.count);   
    });
  }
  $('#amount').html( amount );	
}

function renderReturned( data ){
  var returnCoins = "";
  if (Object.keys( data ).length > 0){
    $.each ( data, function( returnedKey, returnedValue ) {
      if (returnedValue.count > 0){
        returnCoins += returnedValue.value + " * " + returnedValue.count + "<br>";
      }
    });
  }
  returnCoins = returnCoins || 0;
  $('#amount').html( 'Returned: ' + returnCoins );
  $('#buttonCollectCoins').removeAttr('disabled');
}

function renderPurchased( data ){
	$('#purchased').html( 'Purchased: ' + data );
}


function showMessage( data ){
	$("#alert").html("<p>"+data+"</p>").show();
}

function hideMessage(){
	$("#alert").html("").hide();
}
