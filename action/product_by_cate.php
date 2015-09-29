<?php 
include("../libs/SyncDatabase.php"); 
$base = new SyncDatabase(); 
$table = null;
$PAGE = 1;
if(isset($_POST['page'])) $PAGE = $_POST['page'];
$MAX = 12;
$ROW = $PAGE * $MAX;
if($_POST['category_id']==0) {
	$count = $base->Query("SELECT COUNT(*) FROM product WHERE visible = 1;");
	$table = $base->Query("SELECT product_id, name_en, name_th, price, size, image_path FROM product WHERE visible = 1 ORDER BY name_en ASC LIMIT $ROW, $MAX;");
} else {
	$count = $base->Query("SELECT COUNT(*) FROM product WHERE visible = 1 AND category_id = ".$_POST['category_id'].";");
	$table = $base->Query("SELECT product_id, name_en, name_th, price, size, image_path FROM product WHERE visible = 1 AND category_id = ".$_POST['category_id']." ORDER BY name_en ASC LIMIT $ROW, $MAX;");
}
?>

<table cellpadding="4" cellspacing="15" border="0" width="100%">
<?php $loop = 1; foreach($table as $dRow): ?>
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
  <tr>
    <td colspan="2" style="border-top:solid 1px #DFDFDF; padding-top:15px;"><?php if(ceil($count / $MAX) > 1): ?><strong>Go to Page</strong> <?php endif; ?>
      <?php for ($x = 1; $x < ceil($count / $MAX); $x++): ?>
      <input type="button" onClick="$(this).CATEGORY_CHANGE(<?php echo $_POST['category_id'].','.$x; ?> )" value="<?php echo $x; ?>" class="btn btn-xs btn-default btn-page" <?php if($PAGE == $x): ?> disabled<?php endif; ?>>
      <?php endfor; ?>
    </td>
  </tr>
</table>
