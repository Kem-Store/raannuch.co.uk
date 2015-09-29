<?php ob_start(); session_start();  ?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="ProteusNet">
<link rel="icon" type="image/ico" href="images/icon/favicon.png">
<title>ADMINISTRATOR • RAANNUCH.CO.UK</title>
<link rel="stylesheet" type="text/css" href="../resources/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../resources/css/bootstrap-theme.min.css">
<link rel="stylesheet" type="text/css" href="../resources/css/default-template.css?v2">
<script src="../resources/jquery.min.1.9.1.js"></script>
<script src="../resources/jquery.ui.widget.js"></script>
<script src="../resources/Touno.Engine.js"></script>
<script src="../resources/jquery.iframe-transport.js"></script>
<script src="../resources/jquery.fileupload.js"></script>
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
<!-- Google fonts -->
<script type="text/javascript">
	$(function(){
		$('.panel-menu li').click(function(){
			Touno.Storage('COM', $(this).attr('item'));
			location.pathname = "/";
		});


		var ItemStore =  JSON.parse(Touno.Storage("STORE"));
		ItemStore = ItemStore == null ? [] : ItemStore;

		var ItemBill =  JSON.parse(Touno.Storage("BILL"));
		ItemBill = ItemBill == null ? [] : ItemBill;


		var BuyItem = function(id, name, price, qty){
			qty = isNaN(parseFloat(qty)) ? 1 : parseFloat(qty);
			price = parseFloat(price);
			ItemStore.push({ id:id, name:name, price:price, qty:qty });
			Touno.Storage("STORE", JSON.stringify(ItemStore, null, 2));
		}
		var FillBill = function() {
			$('#hfBillingID').val(ItemBill.billing_id);
			$('#hfInvoiceNo').val(ItemBill.invoice_no);
			$('#txt_no').html(ItemBill.invoice_no);
			$('#txt_date').html(ItemBill.invoice_date);
			$('#txt_name').html(ItemBill.firstname + ' ' + ItemBill.lastname);
			$('#txt_address').html(ItemBill.address1 + '<br>' + ItemBill.address2 + '<br>' + ItemBill.city + ', ' + ItemBill.country + '<br>' + ItemBill.zipcode);
			$('#txt_email').html(ItemBill.email);
			$('#txt_tel').html(ItemBill.tel);
			$('#txt_notes').html(ItemBill.notes);
			$('#ddlStatus').val(ItemBill.status);
		}
		var FillStore = function(){
			var len = ItemStore.length, total = 0, list = 1;
			for (var i=0; i<len; i++) {
				var Item = $('#template-cart').clone().show(0);
				Item.find('#No').html(list);
				Item.find('#No').attr('refid', ItemStore[i].id );
				Item.find('#X').attr('onClick','$(this).DELETE(' + ItemStore[i].id + ')');

				var Name = /(.*)#(.*)/ig.exec(ItemStore[i].name);
				Item.find('#TitleEN').val(Name[1]);
				Item.find('#TitleTH').val(Name[2]);

				Item.find('#Price').val(ItemStore[i].price);
				Item.find('#Quantity').val(ItemStore[i].qty);
				Item.find('#Total').html('£'+(Math.round((ItemStore[i].qty * ItemStore[i].price) * 100) / 100));
				$('#tb-edit tbody').append(Item);
				list++;
				total = Math.round((total + (ItemStore[i].qty * ItemStore[i].price)) * 100) / 100;
			}

			$('#shipping').html('<strong>£'+(parseFloat(ItemBill.delivery))+'</strong>');
			$('#cart-total').html('£'+total);
			$('#order-total').html('£'+(Math.round((total+(parseFloat(ItemBill.delivery))) * 100) / 100));


		}

		var ChangeMode = function(){
			if(Touno.Storage("ORDER") == null) {
				Touno.Storage("ORDER", "LIST");
			} else if(Touno.Storage("ORDER") === "LIST") {
				$('#panel-list').show(0);
				$('#panel-edit').hide(0);
			} else if(Touno.Storage("ORDER") === "EDIT") {
				$('#panel-list').hide(0);
				$('#panel-edit').show(0);
			}
		}
		ChangeMode();
		FillBill();
		FillStore();


		$('#Access').submit(function(e) {
			$.ajax({
				url: 'action/login.php',
				data: { username: $('#username').val(), password: $('#password').val(), ACCESS: '' },
				type: 'POST',
				dataType:"JSON",
				error: function(e,s,r){ console.log(e,s,r); },
				success: function(data){
					if(!data.onError) location.reload(); else alert(data.exMessage);
				},
			});
			return false;
        });

		$('#btnEditCategory').click(function(e) {
			if($('#btnEditCategory').val()=='Edit') {
				$('#ddlCategory').hide(0);
				$('#btnEditCategory').val('Cancel');
				$('#btnNewCategory').val('Save');
				$('#txtNewCategory').show(0);
				$('#txtNewCategory').attr('category_id',$('#ddlCategory option:selected').val());
				$('#txtNewCategory').val($('#ddlCategory option:selected').text());
			} else {
				$('#ddlCategory').show(0);
				$('#btnEditCategory').val('Edit');
				$('#btnNewCategory').val('Add');
				$('#txtNewCategory').hide(0);
			}
        });

		$('#btnNewCategory').click(function(e) {
			if($('#btnNewCategory').val()=='Save' && $('#txtNewCategory').val() !== '') {
				$.ajax({
					url: 'action/categody_update.php',
					data: {
						category_id: $('#txtNewCategory').attr('category_id') || 'NEW',
						name: $('#txtNewCategory').val()
					},
					type: 'POST',
					dataType:"JSON",
					error: function(e,s,r){ alert(e); },
					success: function(data){ location.reload(); },
				});
			} else {
				$('#ddlCategory').hide(0);
				$('#btnEditCategory').val('Cancel');
				$('#btnNewCategory').val('Save');
				$('#txtNewCategory').show(0);
				$('#txtNewCategory').attr('category_id', 'NEW');
				$('#txtNewCategory').val('');

			}
        });

		$('#btnNew').click(function(e) { location.reload(); });

		$('#btnSave').click(function(e) {
			$.ajax({
				url: 'action/product_save.php',
				data: {
					product_id: $('#btnSave').attr('product_id') || 'NEW',
					image: $('#fileProduct').attr('filename') || 'images/no-image.jpg',
					title_en: $('#txtTitleEN').val(),
					title_th: $('#txtTitleTH').val(),
					size: $('#txtSize').val(),
					category_id: $('#ddlCategory option:selected').val(),
					price: $('#txtPrice').val(),
					recommend: $('#chkRecommend').prop('checked'),
					show: $('#chkShow').prop('checked')
				},
				type: 'POST',
				dataType:"JSON",
				error: function(e,s,r){ alert(e); },
				success: function(data){
					alert(data.message);
					if(!data.error) location.reload();
				},
			});
        });

		$('#btnDelete').click(function(e) {
			if(($('#btnSave').attr('product_id') || 'NEW') != 'NEW') {
				$.ajax({
					url: 'action/product_save.php',
					data: {
						action: 'DELETE', product_id: $('#btnSave').attr('product_id') || 'NEW'
					},
					type: 'POST',
					dataType:"JSON",
					error: function(e,s,r){ alert(e); },
					success: function(data){
						alert(data.message);
						if(!data.error) location.reload();
					},
				});
			}
        });


		$('#btnDelete').hide(0);
		$('#txtNewCategory').hide(0);

		var upload = '#fileProduct', events = {
			done: function (e, data) {
				$(upload+' .fileinput-button span').html("DONE, OR RE-UPLOAD IMAGE.");
				$('#imgProduct').css('background-image','url(../'+data.filepath+')');
				$(upload).attr('filename', data.filepath);
			},
			fail: function () { $(c+'.progress-bar-success').css('background-color', '#de7272'); }
		};

        var w = $(upload).outerWidth() + 'px', h = $(upload).outerHeight() + 'px';
        var InputFile = $(upload).css({ 'position': 'absolute', 'top': '0px', 'left': '0px', 'background-color': 'transparent' });
        var e = {
            Panel: InputFile.parent(),
            PanelButton: $('<div>', { 'id': InputFile.attr('id'), 'class': 'input-button' }).css({ 'width': w, 'height': h }),
            PanelText: $('<span>', { 'class': 'input-text fileinput-button' }).css({ 'width': w, 'height': h }).html('<span>' + InputFile.val() + '</span>'),
            PanelInput: $('<input>', { 'id': 'input-file', 'name': 'input-file', 'type': 'file' }),
            ProgressBlock: $('<div>', { 'class': 'input-block' }).css({ 'width': w, 'height': h }),
            ProgressBar: $('<div>', { 'class': 'input-bar' }).css({ 'width': '0px', 'height': h })
        }
        InputFile.remove();
        e.ProgressBar.appendTo(e.ProgressBlock);
        e.PanelText.append(e.PanelInput);
        e.PanelButton.append(e.ProgressBlock);
        e.PanelButton.append(e.PanelText);
        e.PanelButton.appendTo(e.Panel);

        e.PanelText.data('text', InputFile.val());
        e.PanelInput.fileupload({
            url: 'action/product_upload.php',
            dataType: 'json',
            formData: events.formData,
            maxFileSize: 1048576,
            send: function () {
                e.PanelInput.attr('disabled','disabled');
                e.ProgressBlock.css('background-color', '#e5e5e5'); //#6a717a
                e.ProgressBar.css({ 'width': '0px', 'background-color': '#80b745' });
                e.PanelText.children().first().html('0%');
                if (events.send != undefined) events.send();
            },
            done: function (element, data) {
                e.PanelInput.removeAttr('disabled');
                var span = e.PanelText.children().first();
                span.Reset = function () {
                    e.ProgressBlock.css('background-color', '#6a717a');
                    e.ProgressBar.css({ 'width': '0px', 'background-color': '#80b745' });
                    e.PanelText.children().first().html(e.PanelText.data('text'));
                }
                span.Fail = function(){ e.ProgressBar.css({ 'width': '100%', 'background-color': '#a51b1b' }); }
                span.Success = function(){ e.ProgressBar.css({ 'width': '100%', 'background-color': '#6a717a' }) }

                span.html('DONE');
                if (events.done != undefined) events.done(span, data._response.result);
            },
            progressall: function (element, data) {
                e.PanelInput.removeAttr('disabled');
                var progress = parseInt(data.loaded / data.total * 100, 10);
                e.ProgressBar.css('width', progress + '%')
                e.PanelText.children().first().html(progress + '%');

                if (progress == NaN) e.PanelText.children().first().html('ERROR');
                if (events.progressall != undefined) events.progressall(e.PanelText.children().first(), data);
            },
            fail: function () {
                e.PanelText.children().first().html('ERROR');
                e.ProgressBar.css({ 'width': '100%', 'background-color': '#a51b1b' });
                if (events.fail != undefined) events.fail(e.PanelText.children().first());
            }
        });

		$.extend($.fn,{
			ChangeMenu: function(){
				var nav = $(this).attr('nav');
				$('ul li[role="presentation"]').removeClass('active');
				$(this).addClass('active');
				Touno.Storage('ADMIN', nav)

				switch(nav)
				{
					case 'order':
						$('#panel-order').show(0);
						$('#panel-add, #panel-product').hide(0);
						$('span.admin-menu[nav="' + nav + '"]').addClass('selected');
						break;
					case 'product':
						$('#panel-order').hide(0);
						$('#panel-add, #panel-product').show(0);
						$('span.admin-menu[nav="' + nav + '"]').addClass('selected');
						break;
				}



			},
			SELECT: function(){
				$('#btnDelete').show(0);
				$('.tb-product tbody tr').removeClass('selected');
				$(this).addClass('selected');
				$('#btnSave').attr('product_id', $(this).attr('product_id'))
				$.ajax({
					url: 'action/product_save.php',
					data: {
						action: 'SELECT', product_id: $('#btnSave').attr('product_id')
					},
					type: 'POST',
					dataType:"JSON",
					error: function(e,s,r){ alert(e); },
					success: function(data){
						if(data.message.length == 1) {
							data = data.message[0];
							$('#imgProduct').css('background-image','url(../'+data.image_path+')');
							$('#fileProduct').attr('filename', data.image_path);
							$('#txtTitleEN').val(data.name_en);
							$('#txtTitleTH').val(data.name_th);
							$('#txtSize').val(data.size);
							$('#ddlCategory').val(data.category_id);
							$('#txtPrice').val(data.price);
							$('#chkRecommend').prop('checked', (data.recommend==1)?true:false);
							$('#chkShow').prop('checked', (data.visible==1)?true:false)
						}
					},
				});
			},
			CATEGORY_CHANGE : function(id){
				$('div.cate-list').removeClass('selected');
				$(this).addClass('selected');
			},
			EDIT : function(billing_id){
				Touno.Storage("ORDER", "EDIT");

				$.ajax({
					url: 'action/billing_edit.php',
					data: { action : 'GET', billing_id: billing_id },
					type: 'POST',
					dataType:"JSON",
					error: function(e,s,r){ alert(e); },
					success: function(data){
						var detail = [];
						if(data.billing.length == 1) {
							ItemBill = [];

							ItemBill = data.billing[0];
							FillBill();
							Touno.Storage("BILL", JSON.stringify(ItemBill, null, 2));
						}
						if(data.detail.length > 0) {
							$('#tb-edit tbody').empty();
							ItemStore = [];

							var len = data.detail.length, total = 0;
							for (var i=0; i<len; i++) {
								total = Math.round((total + (data.detail[i].qty * data.detail[i].price)) * 100) / 100;
								BuyItem(data.detail[i].product_id, data.detail[i].description, data.detail[i].price, data.detail[i].qty);
							}
							FillStore();
							$('#shipping').html('<strong>£'+(parseFloat(ItemBill.delivery))+'</strong>');
							$('#cart-total').html('£'+total);
							$('#order-total').html('£'+(Math.round((total+(parseFloat(ItemBill.delivery))) * 100) / 100));
						}
						ChangeMode();
					},
				});
			},
			ORDER_SAVE : function(){
				$.ajax({
					url: 'action/billing_edit.php',
					data: { action : 'SAVE', bill : Touno.Storage("BILL"), store: Touno.Storage("STORE"), status: $('#ddlStatus option:selected').val() },
					type: 'POST',
					dataType:"JSON",
					error: function(e,s,r){ alert(e); },
					success: function(){
						ItemStore = [];
						ItemBill = [];
						Touno.Storage("STORE", "[]");
						Touno.Storage("BILL", "{}");
						Touno.Storage("ORDER", "LIST");
						location.reload();
					}
				});

			},
			ORDER_CANCEL : function(){
				ItemStore = [];
				ItemBill = [];
				Touno.Storage("STORE", "[]");
				Touno.Storage("BILL", "{}");
				Touno.Storage("ORDER", "LIST");
				ChangeMode();
			},
			DELETE : function(p_id){
				var len = ItemStore.length, total = 0;
				for (var i=0; i<len; i++) {
					if(ItemStore[i] != undefined) {
						if(ItemStore[i].id == p_id) ItemStore.splice(i, 1);
					}
				}
				Touno.Storage("STORE", JSON.stringify(ItemStore, null, 2));
				location.reload();

			},
			ADD : function(){
				Touno.Storage("COM", "shop");
				location.href = "/";
			},
			ChangeQuantity : function(){
				var len = ItemStore.length, total = 0, cart = $(this).closest('#template-cart'), id = cart.find('#No').attr('refid');
				var price = cart.find('#Price').val(), qty = cart.find('#Quantity').val(), desc = cart.find('#TitleEN').val() + '#' + cart.find('#TitleTH').val();
				qty = isNaN(parseFloat(qty)) ? 1 : parseFloat(qty);
				price = isNaN(parseFloat(price)) ? 0 : parseFloat(price);

				for (var i=0; i<len; i++) {
					if(ItemStore[i].id == id) {
						ItemStore[i].qty = qty;
						ItemStore[i].price = price;
						cart.find('#Total').html('£'+(Math.round((ItemStore[i].qty * ItemStore[i].price) * 100) / 100));
					}
					total = Math.round((total + (ItemStore[i].qty * ItemStore[i].price)) * 100) / 100;
				}

				Touno.Storage("STORE", JSON.stringify(ItemStore, null, 2));

				$('#menu-total, #cart-total').html('£'+total);
				$('#order-total').html('£'+(Math.round((total+parseFloat($('#shipping').text().replace('£',''))) * 100) / 100));
			}
		});

		var nav = Touno.Storage('ADMIN');
		switch(nav)
		{
			case 'order':
				$('#panel-add, #panel-product').hide(0);
				$('span.admin-menu[nav="' + nav + '"]').addClass('selected');
				break;
			case 'product':
				$('#panel-order').hide(0);
				$('span.admin-menu[nav="' + nav + '"]').addClass('selected');
				break;
			default:
				$('#panel-add, #panel-product').hide(0);
				$($('span.admin-menu').get(0)).addClass('selected');
				break;
		}

	});
	function ACCESS_ADMIN(e) {
		$(e).prop('disabled', true);
	}
	function Logout(){
		if(confirm("your want to logout?")){
			document.cookie = 'ACCESS=; expires=Thu, 01 Jan 1970 00:00:01 GM; path=/';
			location.href = "/";
		}
	}

</script>
</head>
<body>
<?php
include("../libs/SyncDatabase.php");
include("../libs/Session.php");
$base = new SyncDatabase();
$cookie = new Session();
?>
<div class="panel-menu">
  <div class="container">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
      <tr>
        <td><li item="home">HOME</li>
          <li item="shop">PRODUCT</li>
          <li item="about">ABOUT US</li>
          <li item="contact">CONTACT US</li></td>
        <td></td>
      </tr>
    </table>
  </div>
</div>
<?php if($cookie->Value('ACCESS')): ?>
<div class="panel-nav">
  <div class="container">
    <li>CONTROL PANEL</li>
  </div>
</div>
<?php endif ?>
<div class="panel-component">
  <?php if(!$cookie->Value('ACCESS')): ?>
  <div style="background: url(../images/dark_wood.jpg?1401782036) repeat center top;text-align: center;position: relative; height:400px;"></div>
  <?php endif ?>
  <div class="container" style="padding:50px 0px;">
    <?php if(!$cookie->Value('ACCESS')): ?>
    <form  id="Access"  name="Access" method="post" action="">
      <table border="0" cellpadding="3" cellspacing="0" width="400px" style="margin:auto; font-weight:bold; font-size:14px; ">
        <tr>
          <td style="padding:3px;">
            <div class="input-group">
              <div class="input-group-addon" style="width:130px;"><strong>Admin Access</strong></div>
              <input id="username"  type="text" class="form-control" placeholder="username">
            </div>
          </td>
        </tr>
        <tr>
          <td style="padding:3px;">
            <div class="input-group">
              <div class="input-group-addon" style="width:130px;"><strong>Password</strong></div>
              <input id="password"  type="password" class="form-control" placeholder="••••">
            </div>
          </td>
        </tr>
        <tr>
          <td style="padding:3px;"><input type="submit" class="btn btn-primary" value="Submit" style="width:150px;"></td>
        </tr>
      </table>
    </form>
    <?php else: ?>
    <style type="text/css">
		span.admin-menu {
			cursor:pointer;
			color:#6C6C6C;
		}
		span.admin-menu:hover {
			text-decoration:underline;
		}
		span.selected {
			color:#4EAC12;
		}
	</style>
    <ul class="nav nav-tabs">
      <li role="presentation" nav="order" onClick="$(this).ChangeMenu();"><a href="#order">Pre-Order</a></li>
      <li role="presentation" nav="product" onClick="$(this).ChangeMenu();"><a href="#product">PRODUCT</a></li>
      <li role="presentation" nav="product" onClick="Logout();"><a href="#product">LOGOUT</a></li>
    </ul>
    <br><br>
    <div id="panel-order">
      <div id="panel-list">
        <table id="tb-order" class="table table-striped" border="0" cellpadding="5" cellspacing="0" width="100%">
          <thead>
          <tr>
            <th width="80" align="center"><strong>No.</strong></th>
            <th width="180" align="center"><strong>Date</strong></th>
            <th><strong>Name</strong></td>
            <th width="200"><strong>Email</strong></th>
            <th width="120" align="center"><strong>Country</strong></th>
            <th width="100" align="center"><strong>Phone</strong></th>
            <th width="80" align="center"><strong>Status</strong></th>
          </tr>
          </thead>
          <tbody>
	      <?php foreach($base->Query("SELECT invoice_no, invoice_date, billing_id, firstname, lastname, country, email, tel, status FROM billing ORDER BY status DESC, invoice_date DESC LIMIT 50;") as $dRow): ?>
          <tr onClick="$(this).EDIT(<?php echo $dRow['billing_id']; ?>);">
            <td align="center"><?php echo $dRow['invoice_no']; ?></td>
            <td align="center"><?php echo $dRow['invoice_date']; ?></td>
            <td align="left"><?php echo $dRow['firstname'].' '.$dRow['lastname']; ?></td>
            <td align="left"><?php echo $dRow['email']; ?></td>
            <td align="center"><?php echo $dRow['country']; ?></td>
            <td align="center"><?php echo $dRow['tel']; ?></td>
            <td align="center"><?php echo $dRow['status']; ?></td>
          </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div id="panel-edit">
        <table id="tb-edit" class="tb-block" border="0" cellpadding="0" cellspacing="0" style="width:100%;margin:auto;">
          <thead>
          <tr>
            <td colspan="6" style="padding:30px !important;">
              <table border="0" class="tb-block" cellpadding="3" cellspacing="0" style="width:100%;margin:auto;">
                <thead>
                <tr>
                  <td width="150"><strong>Customer Name :</strong></td>
                  <td width="800" id="txt_name"></td>
                  <td width="100"><strong>Invoice NO :</strong></td>
                  <td width="180" id="txt_no"></td>
                </tr>
                <tr>
                  <td colspan="2"><strong>Address :</strong></td>
                  <td width="120"><strong>Invoice Date :</strong></td>
                  <td id="txt_date"></td>
                </tr>
                <tr>
                  <td colspan="2" id="txt_address"></td>
                  <td valign="top"><strong>Status :</strong></td>
                  <td valign="top">
                    <select id="ddlStatus" class="form-control-static">
                      <option value="PENDDING">Pendding</option>
                      <option value="CONFIRMED">Confirmed</option>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td><strong>Email :</strong></td>
                  <td colspan="3" id="txt_email"></td>
                </tr>
                <tr>
                  <td><strong>Phone :</strong></td>
                  <td colspan="3" id="txt_tel"></td>
                </tr>
                <tr>
                  <td colspan="4"><strong>Notes :</strong></td>
                </tr>
                <tr>
                  <td colspan="4" id="txt_notes"></td>
                </tr>
                <tr>
                  <td colspan="4">&nbsp;</td>
                </tr>
                </thead>
              </table>
            </td>
          </tr>
          <tr>
            <td width="50"><strong>No</strong></td>
            <td><strong>Description</strong></td>
            <td width="100"><strong>Price</strong></td>
            <td width="100"><strong>Quantity</strong></td>
            <td width="100"><strong>Total</strong></td>
            <td width="50"></td>
          </tr>
          <tr id="template-cart" style="display:none;">
            <td id="No"></td>
            <td>
              <input type="text" id="TitleTH" class="form-control">
              <input type="text" id="TitleEN" class="form-control">
            </td>
            <td><input type="text" id="Price" class="form-control" onChange="$(this).ChangeQuantity();"></td>
            <td><input type="text" id="Quantity" class="form-control" onChange="$(this).ChangeQuantity();"></td>
            <td id="Total"></td>
            <td align="center"><input type="button" class="btn" id="X" value="X"></td>
          </tr>
          </thead>
          <tbody>
          </tbody>
          <tfoot>
          <tr>
            <td colspan="5"></td>
            <td align="center"><input type="button" onClick="$(this).ADD()" class="btn" id="Add" value="Add"></td>
          </tr>
          <tr>
            <td colspan="4" style="border-top:#929292 solid 1px;"><strong>Cart Subtotal</strong></td>
            <td colspan="2" style="border-top:#929292 solid 1px;" id="cart-total">£0</td>
          </tr>
          <tr>
            <td colspan="4" colspan="6"><strong>Shipping</strong></td>
            <td colspan="2" id="shipping">£0</td>
          </tr>
          <tr>
            <td colspan="4" style="border-top:#929292 solid 1px;"><strong>Order Total</strong></td>
            <td colspan="2" style="border-top:#929292 solid 1px;" id="order-total">£0</td>
          </tr>
          <tr>
            <td colspan="6" align="center">
				<iframe name="hfExport" id="hfExport" style="display:none;"></iframe>
			  <form name="print" method="post" action="action/export_order.php" target="hfExport">
				  <input type="hidden" name="billing_id" id="hfBillingID" value="">
				  <input type="hidden" name="invoice_no" id="hfInvoiceNo" value="">
          	      <input type="submit" class="btn btn-warning" id="btnPrintOrder" value="Export XLS" style="margin-right:20px;">
				  <input type="button" class="btn btn-primary" onClick="$(this).ORDER_SAVE();"  id="btnSaveOrder" value="Save">
				  <input type="button" class="btn" onClick="$(this).ORDER_CANCEL();" value="Cancel">
			  </form>

            </td>
          </tr>
          </tfoot>
        </table>
      </div>
    </div>
    <div id="panel-add">
    <table border="0" cellpadding="3" cellspacing="0" width="800" style="margin:auto">
      <tr>
        <td><input type="text" id="fileProduct" style="width:430px;  height:34px;" value="UPLOAD PRODUCT IMAGE"></td>
        <td>
            <div class="input-group">
              <div class="input-group-addon" style="width:110px;"><strong>Category</strong></div>
              <input type="text" id="txtNewCategory" class="form-control" value="" style="width:243px;"/>
              <select id="ddlCategory" class="form-control">
                <?php foreach($base->Query("SELECT category_id, name FROM category ORDER BY name ASC;") as $dRow): ?>
                  <option value="<?php echo $dRow['category_id']; ?>"><?php echo $dRow['name']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
        </td>
      </tr>
      <tr>
        <td align="left" valign="top" rowspan="10" width="450">
          <div id="imgProduct" style="background:url('../images/no-image.jpg') no-repeat; background-size: cover; width:440px; height:260px;"></div>
        </td>
        <td>
          <input type="button" id="btnNewCategory" class="btn btn-primary" value="Add" style="width:80px;"/>
          <input type="button" id="btnEditCategory" class="btn btn-warning" value="Edit" style="width:80px;"/>
        </td>
      </tr>
      <tr>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>
            <div class="input-group">
              <div class="input-group-addon" style="width:130px;"><strong>Title</strong></div>
              <input type="text" id="txtTitleEN" class="form-control" value="" style="width:243px;"/>
            </div>
         </td>
      </tr>
      <tr>
        <td>
            <div class="input-group">
              <div class="input-group-addon" style="width:130px;"><strong>Title (Thai)</strong></div>
             <input type="text" id="txtTitleTH" class="form-control"  value="" style="width:243px;"/>
            </div>
        </td>
      </tr>
      <tr>
        <td>
            <div class="input-group">
              <div class="input-group-addon" style="width:110px;"><strong>Size</strong></div>
              <input type="text" id="txtSize" class="form-control"  value="" style="width:100px;"/>
            </div>
        </td>
      </tr>
      <tr>
        <td>
            <div class="input-group">
              <div class="input-group-addon" style="width:110px;"><strong>Price (£)</strong></div>
              <input type="text" id="txtPrice" class="form-control"  value="" style="width:100px; text-align:right;display: inline-block;"/>
            </div>
        </td>
      </tr>
      <tr>
        <td>
            <div class="input-group">
              <div class="input-group-addon" style="width:130px;"><strong>Status</strong></div>
              <label class="form-control" for="chkShow" style="width:243px;"><input type="checkbox" id="chkShow" name="chkShow" checked /> Show</label>
            </div>
        </td>
      </tr>
      <tr>
        <td>
            <div class="input-group">
              <div class="input-group-addon" style="width:130px;"><strong>Recommend</strong></div>
              <label class="form-control" for="chkRecommend" style="width:243px;"><input type="checkbox" id="chkRecommend" name="chkRecommend" /> Show at homepage</label>
            </div>
        </td>
      </tr>
      <tr>
        <td  align="center">
             <input type="button" id="btnNew" value="New" class="btn" />
             <input type="button" id="btnSave" value="Save" class="btn" />
             <input type="button" id="btnDelete" value="Delete" class="btn" />
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td >&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3" align="center">&nbsp;</td>
        </tr>
      <tr>
        <td width="222"></td>
        <td width="222"></td>
        <td></td>
      </tr>
    </table>
    </div>

    <style type="text/css">
		#tb-product thead tr {
			background-color:#ACACAC;
		}
		#tb-product tbody tr:hover {
			background-color:#F7F7F7;
			cursor:pointer;
		}
		#tb-product tbody tr.selected {
			background-color:#E7E7E7;
			font-weight:bold;
		}
	</style>
    <div id="panel-product">
    <h1>List Product</h1>
    <?php foreach($base->Query("SELECT category_id, name FROM category ORDER BY name ASC;") as $dCate): ?>
    <div class="panel panel-default">
    <div class="panel-heading"><?php echo $dCate['name']; $list = 1; ?></div>
    <table class="table tb-product" border="0" cellpadding="5" cellspacing="0" width="850">
      <thead>
      <tr>
        <th width="20" align="center"><strong>No.</strong></th>
        <th align="left"><strong>Title</strong></th>
        <th width="120" align="right"><strong>Size</strong></th>
        <th width="120" align="right"><strong>Price</strong></th>
        <th width="50" align="center"><strong>Show</strong></th>
        <th width="50" align="center"><strong>Recommend</strong></th>
      </tr>
      </thead>
      <tbody>
      <?php foreach($base->Query("SELECT product_id, name_en, name_th, price, size, recommend, visible FROM product WHERE category_id = ".$dCate['category_id']." ORDER BY name_en ASC;") as $dRow): ?>
      <tr onClick="$(this).SELECT();" product_id="<?php echo $dRow['product_id']; ?>">
        <td align="center"><?php echo $list; ?></td>
        <td align="left"><div class="cate-en"><?php echo $dRow['name_th']; ?></div><div class="cate-th"><?php echo $dRow['name_en']; ?></div></td>
        <td align="right"><?php echo $dRow['size']; ?></td>
        <td align="right">£<?php echo $dRow['price']; ?></td>
        <td align="center"><?php if($dRow['visible']==1) echo 'Yes'; else echo 'No'; ?></td>
        <td align="center"><?php if($dRow['recommend']==1) echo 'Yes'; else echo 'No'; ?></td>
      </tr>
      <?php $list++; endforeach; ?>
      </tbody>
    </table>
    </div>
    <?php endforeach; ?>
    </div>
    <?php endif ?>
  </div>
</div>
<div class="panel-footer">
  <div class="container">Copyright © 2015 raannuch.co.uk All rights reserved.</div>
</div>
</body>
</html>
