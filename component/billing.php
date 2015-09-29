<?php include("../libs/SyncDatabase.php"); $base = new SyncDatabase(); ?>
<script>
$(function(){
	
	var len = ItemStore.length, total = 0;
	for (var i=0; i<len; i++) { 
    var Item = $('#template-cart').clone().show(0);
		var Name = /(.*)#(.*)/ig.exec(ItemStore[i].name);
		Item.find('#Product').html('<div><strong>' + Name[2] + '</strong></div><div>' + Name[1] + '</div>');
		Item.find('#Total').html('£'+(Math.round((ItemStore[i].qty * ItemStore[i].price) * 100) / 100));
		$('#tb-order tbody').append(Item);
		total = Math.round((total + (ItemStore[i].qty * ItemStore[i].price)) * 100) / 100;
	}
	$('#shipping').html('<strong>£'+shipping+'</strong>');
	$('#cart-total').html('£'+total);
	$('#order-total').html('£'+(Math.round((total+shipping) * 100) / 100));
	
	$('#btnBackHome').click(function(){
		location.hash = '#home';
		location.reload();
	});
	
	$('#btnConfirmOrder').click(function(){
		$('#btnConfirmOrder').prop('disabled', true);
		$('#txt_name').html($('#firstname').val() + ' ' + $('#lastname').val());
		$('#txt_address').html($('#address1').val()+'<br>'+$('#address2').val()+'<br>'+$('#city').val()+', '+$('#country').val()+'<br>'+$('#zipcode').val())
		$('#txt_email').html($('#email').val());
		$('#txt_tel').html($('#tel').val());
		$('#txt_notes').html($('#notes').val());
		
		$.ajax({
			url: 'action/billing_order.php',
			data: { 
				firstname : $('#firstname').val(),
				lastname : $('#lastname').val(),
				address1 : $('#address1').val(),
				address2 : $('#address2').val(),
				zipcode : $('#zipcode').val(),
				city : $('#city').val(),
				country : $('#country').val(),
				email : $('#email').val(),
				notes : $('#notes').val(),
				tel : $('#tel').val(),
				shipping : window.shipping,
				store: Touno.Storage("STORE") 
			},
			type: 'POST',
			dataType:"JSON",
			error: function(e,s,r){ alert(e); $('#btnConfirmOrder').prop('disabled', false); },
			success: function(data){ 
				if(!data.error) {
					alert(data.message);
					Touno.StorageClear();
					$('#tb-billing').show(0);
					$('#checkout').hide(0);
					$('#btnConfirmOrder').hide(0);
					$('#btnBackHome').show(0);
				} else {
					alert('cannot send billing');
					$('#btnConfirmOrder').prop('disabled', false);
				}
			},	
		});

	});	
});
</script>
<div id="checkout">
<h1>Check out</h1>
<table class="tb-block" border="0" cellpadding="3" cellspacing="0" style="width:850px;margin:auto">
  <tr>
	<td colspan="2"><strong>Country</strong></td>
	<td><strong>Order notes</strong></td>
  </tr>
  <tr>
	<td colspan="2"><input id="country" type="text" class="form-control" value="" style="width:330px;"></td>
	<td rowspan="8" valign="top"><textarea id="notes" class="form-control" cols="50" rows="5" style="width:385px; resize:none;"></textarea></td>
  </tr>
  <tr>
	<td width="150"><strong>First Name</strong></td>
	<td width="250"><strong>Last Name</strong></td>
  </tr>
  <tr>
	<td><input id="firstname" type="text" class="form-control" value="" style="width:165px;"></td>
	<td><input id="lastname" type="text" class="form-control" value="" style="width:155px;"></td>
  </tr>
  <tr>
	<td colspan="2"><strong>Address</strong></td>
  </tr>
  <tr>
	<td colspan="2"><input id="address1" type="text" class="form-control" value="" style="width:330px;"></td>
  </tr>
  <tr>
	<td colspan="2"><input id="address2" type="text" class="form-control" value="" style="width:330px;"></td>
  </tr>
  <tr>
	<td colspan="2"><strong>Postcode / Zip</strong></td>
  </tr>
  <tr>
	<td colspan="2"><input id="zipcode" type="text" class="form-control" value="" style="width:330px;"></td>
  </tr>
  <tr>
	<td colspan="3"><strong>Town / City</strong></td>
  </tr>
  <tr>
	<td colspan="3"><input id="city" type="text" class="form-control" value="" style="width:330px;"></td>
  </tr>
  <tr>
	<td><strong>Email Address</strong></td>
	<td><strong>Phone</strong></td>
	<td>&nbsp;</td>
  </tr>
  <tr>
	<td><input id="email" type="text" class="form-control" value="" style="width:165px;"></td>
	<td><input id="tel" type="text" class="form-control" value="" style="width:155px;"></td>
	<td>&nbsp;</td>
  </tr>
</table>
</div>
<h1>Your order</h1>
<table id="tb-order" class="tb-block" border="0" cellpadding="0" cellspacing="0" style="width:850px;margin:auto">
  <thead>
  <tr>
    <td colspan="2">
      <table id="tb-billing" border="0" cellpadding="3" cellspacing="0" style="width:100%;margin:auto; display:none;">
        <thead>
        <tr>
          <td width="50"><strong>Name :</strong></td>
          <td id="txt_name"></td>
        </tr>
        <tr>
          <td colspan="2"><strong>Address :</strong></td>
        </tr>
        <tr>
          <td colspan="2" id="txt_address"></td>
        </tr>
        <tr>
          <td><strong>Email :</strong></td>
          <td id="txt_email"></td>
        </tr>
        <tr>
          <td><strong>Phone :</strong></td>
          <td id="txt_tel"></td>
        </tr>
        <tr>
          <td colspan="2"><strong>Notes :</strong></td>
        </tr>
        <tr>
          <td colspan="2" id="txt_notes"></td>
        </tr>
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        </thead>
      </table>
    </td>
  </tr>
  <tr>
    <td><strong>Product</strong></td>
    <td width="400"><strong>Total</strong></td>
  </tr>
  <tr id="template-cart" style="display:none;">
    <td id="Product"></td>
    <td id="Total"></td>
  </tr>
  </thead>
  <tbody>
  </tbody>
  <tfoot>
  <tr>
    <td style="border-top:#929292 solid 1px;"><strong>Cart Subtotal</strong></td>
    <td style="border-top:#929292 solid 1px;" id="cart-total">£0</td>
  </tr>
  <tr>
    <td><strong>Shipping</strong></td>
    <td id="shipping">£0</td>
  </tr>
  <tr>
    <td style="border-top:#929292 solid 1px;"><strong>Order Total</strong></td>
    <td style="border-top:#929292 solid 1px;" id="order-total">£0</td>
  </tr>
  <tr>
	<td colspan="2" align="center">
      <input type="button" class="btn" id="btnConfirmOrder" value="Confirm Order">
      <input type="button" class="btn" id="btnBackHome" value="Back Home" style="display:none;">
    </td>
  </tr>
  </tfoot>
</table>