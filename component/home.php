<script>

	if(Touno.Cookie('ACCESS') == ""){
		$('#btnCancel').hide();
		$('#btnEditor').hide();
	}
	var editor;

	function toggleEditor(unSave) {
		var config = { height: '600px' };
		if(!editor) {
			$('#btnEditor').val('Save');
			$('#contents').hide();
			$('#editor').show();
			$('#btnCancel').show();
			editor = CKEDITOR.appendTo( 'editor', config, $('#contents').html());
		} else {
			$('#contents').show();
			$('#editor').hide();
			$('#btnCancel').hide();
			$('#btnEditor').val('Editor');
			if(!unSave) {
				$('#contents').html(editor.getData());
				$.ajax({
					url: 'action/contant_editor.php',
					data: { title : 'home', des: editor.getData() },
					type: 'POST',
					dataType:"JSON",
					error: function(){ alert('fail.'); }	
				});
			}
			// Destroy the editor.
			editor.destroy();
			editor = null;
		}
	}

</script>
<p>
<input id="btnEditor" onclick="toggleEditor();" class="btn btn-primary" type="button" value="Editor">
<input id="btnCancel" onclick="toggleEditor(true);" class="btn" type="button" value="Cancel" style="display:none;">
</p>
<?php include("../libs/SyncDatabase.php"); $base = new SyncDatabase(); $home = $base->Query("SELECT title, description FROM contents WHERE title_id='home'"); ?>
<div id="contents">
	<?php echo $home[0]['description']; ?>
</div>
<div id="editor" style="display: none">
</div>
<h3>Product Recommended</h3>
<?php
$recommend = $base->Query("SELECT p.product_id, name_en, name_th, price, size, image_path FROM 
(SELECT product_id, COUNT(product_id) FROM billing_detail GROUP BY product_id ORDER BY COUNT(product_id) DESC, product_id ASC LIMIT 1, 6) db 
INNER JOIN product p ON p.product_id = db.product_id");

$new = $base->Query("SELECT product_id, name_en, name_th, price, size, image_path FROM product ORDER BY product_id DESC LIMIT 1, 6");
?>
<table cellpadding="4" cellspacing="15" border="0" width="100%">
<?php $loop = 1; foreach($recommend as $dRow): ?>
<?php if(($loop % 2) !== 0) echo '<tr>';?>
    <td style="padding-bottom:5px;">
      <table cellpadding="4" cellspacing="0" border="0" width="100%">
        <tr>
          <td>
            <div class="product-item" product_id="<?php echo $dRow['product_id']; ?>" style="background-image:url('<?php echo $dRow['image_path']; ?>')">
          </td>
          <td width="100%">
            <div class="product-th"><?php echo $dRow['name_th'].' ('.$dRow['size'].')'; ?></div>
            <div class="product-en"><?php echo $dRow['name_en']; ?></div>
            <div class="product-price"><strong>Price :</strong> £<?php echo $dRow['price']; ?></div>
            <div class="product-qty"><strong>Quality จำนวน : </strong><input type="number" value="1" id="txtQty" class="form-control" style="width:80px;" min="1" max="99"></div>
            <input type="button" class="btn btn-sm btn-warning" value="BUY" id="btnBuy" onClick="BuyItem(<?php echo $dRow['product_id'].',\''.$dRow['name_en'].'#'.$dRow['name_th'].'\','.$dRow['price']; ?>, $(this).parent().find('#txtQty').val());">
          </td>
        </tr>
      </table>
    </td>
<?php if(($loop % 2) == 0) echo '</tr>';?>
<?php $loop++; endforeach; ?>
</table>
<h3>Product New</h3>
<table cellpadding="4" cellspacing="15" border="0" width="100%">
<?php $loop = 1; foreach($new as $dRow): ?>
<?php if(($loop % 2) !== 0) echo '<tr>';?>
    <td style="padding-bottom:5px;">
      <table cellpadding="4" cellspacing="0" border="0" width="100%">
        <tr>
          <td>
            <div class="product-item" product_id="<?php echo $dRow['product_id']; ?>" style="background-image:url('<?php echo $dRow['image_path']; ?>')">
          </td>
          <td width="100%">
            <div class="product-th"><?php echo $dRow['name_th'].' ('.$dRow['size'].')'; ?></div>
            <div class="product-en"><?php echo $dRow['name_en']; ?></div>
            <div class="product-price"><strong>Price :</strong> £<?php echo $dRow['price']; ?></div>
            <div class="product-qty"><strong>Quality จำนวน : </strong><input type="number" value="1" id="txtQty" class="form-control" style="width:80px;" min="1" max="99"></div>
            <input type="button" class="btn btn-sm btn-warning" value="BUY" id="btnBuy" onClick="BuyItem(<?php echo $dRow['product_id'].',\''.$dRow['name_en'].'#'.$dRow['name_th'].'\','.$dRow['price']; ?>, $(this).parent().find('#txtQty').val());">
          </td>
        </tr>
      </table>
    </td>
<?php if(($loop % 2) == 0) echo '</tr>';?>
<?php $loop++; endforeach; ?>
</table>