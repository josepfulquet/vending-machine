$(document).ready(function(){
	
  let _products;
  let _change;
	
  try{
    _products = new Products({'baseHref':apiUrl+'products.php'});
    _change = new Change({'baseHref':apiUrl+'change.php'});
    
    var resGetProducts = _products.get();
    resGetProducts.then(res => {
      if (res.data.status === "Accepted"){
	      
        var _data = res.data.message;
        
        if (Object.keys(_data).length > 0){
          $.each ( _data, function( kProduct, vProduct ) {
		    $("#" + vProduct.product).val(vProduct.stock);
          });
        }
      }
	});
	
	var resGetChange = _change.get();
    resGetChange.then(res => {
      if (res.data.status === "Accepted"){
	      
        var _data = res.data.message;
        
        if (Object.keys(_data).length > 0){
          $.each ( _data, function( kChange, vChange ) {
	        $("#coin" + vChange.coin).val(vChange.count);	    
          });
        }
      }
	});
	
	 $("form").on("submit", (e) => {
	    e.preventDefault();

	    var resSetProducts = _products.set($("#water").val(), $("#juice").val(), $("#soda").val());
	    resSetProducts.then(res => {
          $( "#alert-box" ).append( "<p>SETTING PRODUCTS: "+res.data.status+"</p>" ).show();
	    });
	    

	    var resSetChange = _change.set($("#coin005").val(), $("#coin010").val(), $("#coin025").val());
	    resSetChange.then(res => {
          $( "#alert-box" ).append( "<p>SETTING CHANGE: "+res.data.status+"</p>" ).show();
	    });
	    
	  });
	  
	  $("input").on("click", () => {
		if ( $( "#alert-box" ).is(":visible")){
			 $( "#alert-box" ).hide().html("");
		}
		
	  });
  }
  catch(e){
    console.log(e);
  }
		   
});
