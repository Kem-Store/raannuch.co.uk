<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="description" content="">
<meta name="author" content="ProteusNet">
<link rel="icon" type="image/ico" href="images/icon/favicon.png">
<title>HOME • RAANNUCH.CO.UK</title>
<link rel="stylesheet" type="text/css" href="resources/css/default-template.css?v2">
<link rel="stylesheet" type="text/css" href="resources/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="resources/css/bootstrap-theme.min.css">
<link rel="stylesheet" type="text/css" href="resources/css/bootstrap-theme.min.css">
	
<script src="resources/ckeditor/ckeditor.js"></script>
<script src="resources/jquery.min.1.9.1.js"></script> 
<script src="resources/Touno.Engine.js"></script>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <!-- Google fonts -->
<script type="text/javascript">
	window.shipping = 6.95;
//      WebFontConfig = {
//        google: { families: [ 'Arvo:700:latin', 'Open+Sans:400,600,700:latin' ] }
//      };
//      (function() {
//        var wf = document.createElement('script');
//        wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
//          '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
//        wf.type = 'text/javascript';
//        wf.async = 'true';
//        var s = document.getElementsByTagName('script')[0];
//        s.parentNode.insertBefore(wf, s);
//      })();

	$(function(){
		var items = /#(\w+)/g.exec(location.hash || '#home'), component = {};
		
		$.fn.getComponent = function(items){
			if(items!=='home') $('#main-home').hide(0); else $('#main-home').show(0);
			component = $.ajax({
				url: 'component/'+items+'.php',
				data: {},
				type: 'POST',
				dataType:"HTML",
				error: function(){ $('.panel-component .container').html(component.statusText); },
				success: function(data){
					$('.panel-component .container').html(data);
				},	
			});
		}
		
		
		$('.panel-menu li').click(function(){
			if(component.readyState==4 || component.readyState == undefined) {
				$('.panel-menu li').removeClass('selected'); 
				$(this).addClass('selected');
				location.hash = $(this).attr('item');
				$('.panel-nav .container').html('<il>' + $(this).attr('item').toUpperCase() + '</li>');
				$(document).getComponent($(this).attr('item'));
			}
		});
		var com = Touno.Storage('COM') || items[1];
		$('.panel-menu li[item="'+com+'"]').addClass('selected');
		$('.panel-nav .container').html('<il>' + com.toUpperCase() + '</li>');
		$(document).getComponent(com);
		
		var len = ItemStore.length, total = 0.0;
		for (var i=0; i<len; i++) { 
			total = Math.round((total + (ItemStore[i].qty * ItemStore[i].price)) * 100) / 100;
		}
		$('#menu-total').html('£'+total);
		
	});
	var ItemStore =  JSON.parse(Touno.Storage("STORE"));
	ItemStore = ItemStore == null ? [] : ItemStore;
	

		
	var BuyItem = function(id, name, price, qty){
		qty = isNaN(parseFloat(qty)) ? 1 : parseFloat(qty);
		price = parseFloat(price);
		var pass = true;
		if(name !== "__KEPP__") {
			if(confirm('you want \"' + name + '\" ' + qty + ' unit')) pass = true; else pass = false;
		} 
		
		if(pass == true) {
			var addOn = false, len = ItemStore.length, total = 0;
			for (var i=0; i<len; i++) { 
				if(ItemStore[i].id == id) { 
					ItemStore[i].qty = ((name !== "__KEPP__") ? ItemStore[i].qty + qty : qty) ; 
					addOn = true; 
				} 
				total = Math.round((total + (ItemStore[i].qty * ItemStore[i].price)) * 100) / 100; 
			}
			total += qty * price; 
			total = Math.round(total * 100) / 100;
			$('#menu-total').html('£'+total);
			if(!addOn) ItemStore.push({ id:id, name:name, price:price, qty:qty })
			Touno.Storage("STORE", JSON.stringify(ItemStore, null, 2));
		}
	}
</script>
</head>
<body>
<div class="panel-menu">
  <div class="container">
  	<table border="0" cellpadding="0" cellspacing="0" width="100%">
      <tr>
        <td>
          <li item="home">HOME</li>
          <li item="shop">PRODUCT</li>
          <li item="about">ABOUT US</li>
          <li item="contact">CONTACT US</li>
        </td>
        <td align="right">
          <li item="cart" id="menu-cart"><div style="border-left:#C9C9C9 solid 1px;padding: 10px 20px 10px 65px;">CART <span id="menu-total">£0</span></div></li>
        </td>
      </tr>
    </table>
  </div>
</div>
<div class="panel-nav">
  <div class="container">
  </div>
</div>
  <div id="main-home" style="background: url(images/dark_wood.jpg?1401782036) repeat center top;text-align: center;position: relative; height:400px;"></div>
<div class="panel-component">
  <div class="container">
  </div>
</div>
<div class="panel-footer">
  <div class="container">Copyright © 2015 raannuch.co.uk All rights reserved.</div>
</div>
</body>
</html>