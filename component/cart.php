<?php include("../libs/SyncDatabase.php"); $base = new SyncDatabase();  ?>
<script>
$(function(){
	var len = ItemStore.length, total = 0;
	for (var i=0; i<len; i++) { 
		var Name = /(.*)#(.*)/ig.exec(ItemStore[i].name);
		var Item = $('#template-cart').clone().show(0);
		Item.find('#Product').attr('refid', ItemStore[i].id);
		Item.find('#X').attr('onClick','DELETE(' + ItemStore[i].id + ')');
		Item.find('#Product').html('<div><strong>' + Name[2] + '</strong></div><div>' + Name[1] + '</div>');
		Item.find('#Price').html('£'+ItemStore[i].price);
		Item.find('#Quantity').val(ItemStore[i].qty);
		Item.find('#Total').html('£'+(Math.round((ItemStore[i].qty * ItemStore[i].price) * 100) / 100));
		$('#tb-cart tbody').append(Item);
		total = Math.round((total + (ItemStore[i].qty * ItemStore[i].price)) * 100) / 100;
	}
	$('#shipping').html('£'+shipping);
	$('#menu-total, #cart-total').html('£'+total);
	$('#order-total').html('£'+(Math.round((total+shipping) * 100) / 100));
	
	$('#btnBackShop').click(function(e) {
        location.hash = '#shop-0';
		$(document).getComponent('shop');
    });
	
	$('#btnBilling').click(function(e) {
		if(Touno.Storage("ORDER") == "EDIT" && Touno.Cookie('ACCESS') == 'Admin') {
			Touno.Storage("ADMIN", "order");
			location.href = "/admin/"; 	
		} else {
			location.hash = '#billing';
			Touno.Storage("COM", "billing");
			$(document).getComponent('billing');	
		}
    });
	
	if(Touno.Storage("ORDER") == "EDIT" && Touno.Cookie('ACCESS') == 'Admin') {
		$('#btnBilling').val('SAVE PRODUCT ORDER AND BACK');
	} else {
		$('#btnBilling').val('PROCEED TO CHECKOUT');
	}
});

var ChangeQuantity = function(e) {
	var len = ItemStore.length, total = 0, cart = $(e).closest('#template-cart'), id = cart.find('#Product').attr('refid');
	BuyItem(id, "__KEPP__", 0, $(e).val())
	
	for (var i=0; i<len; i++) { 
		if(ItemStore[i].id == id) { cart.find('#Total').html('£'+(Math.round((ItemStore[i].qty * ItemStore[i].price) * 100) / 100)); }
		total = Math.round((total + (ItemStore[i].qty * ItemStore[i].price)) * 100) / 100;
	}
	$('#shipping').html('£'+shipping);
	$('#menu-total, #cart-total').html('£'+total);
	$('#order-total').html('£'+(Math.round((total+shipping) * 100) / 100));
}
var DELETE = function(p_id){
	var len = ItemStore.length, total = 0;
	for (var i=0; i<len; i++) { 
		if(ItemStore[i] != undefined) {
			if(ItemStore[i].id == p_id) ItemStore.splice(i, 1);
		}
	}
	Touno.Storage("STORE", JSON.stringify(ItemStore, null, 2));
	Touno.Storage("COM", "cart");
	location.reload(); 
	
}
</script>
<h1>Cart</h1>
<div class="well">
<table class="table" id="tb-cart" width="100%" style="margin:auto;">
  <thead>
  <tr>
    <th>Product</th>
    <th width="120">Price</th>
    <th width="120">Quantity</th>
    <th width="120">Total</th>
    <th width="50"></th>
  </tr>
  <tr id="template-cart" style="display:none;">
    <td id="Product"></td>
    <td id="Price"></td>
    <td>
      <input type="text" id="Quantity" class="form-control" value="" onChange="ChangeQuantity(this);" style="width:50px; text-align:center;">
    </td>
    <td id="Total"></td>
    <td><input type="button" class="btn" id="X" value="X"></td>
  </tr>
  </thead>
  <tbody>
  </tbody>
  <tfoot>
  <tr>
    <td align="right" colspan="4">
      <input type="button" class="btn btn-default" id="btnBackShop" value="UPDATE CART">
      <input type="button" class="btn btn-primary" id="btnBilling" value="PROCEED TO CHECKOUT">
    </td>
  </tr>
  </tfoot>
</table>
</div>
<div class="well" style="text-align:right; height:200px; margin-top:50px;">
<h2>Cart Totals</h2>
<table align="right" border="0" cellpadding="3" cellspacing="0" width="400px">
  <thead>
  <tr>
    <td><strong>Cart Subtotal</strong></td>
    <td id="cart-total">£0</td>
  </tr>
  <tr>
    <td><strong>Shipping</strong></td>
    <td id="shipping">£0</td>
  </tr>
  <tr>
    <td><strong>Order Total</strong></td>
    <td id="order-total">£0</td>
  </tr>
  </thead>
</table>
</div>