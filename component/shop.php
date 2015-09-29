<?php include("../libs/SyncDatabase.php"); $base = new SyncDatabase();  ?>
<script>
$(function(){
	$.fn.extend({
		CATEGORY_CHANGE : function(id, page){
			if(page == undefined) {
				$('div.list-group-item').removeClass('active');
				$(this).addClass('active');
				Touno.Storage('SHOP', id);
				location.hash = 'shop-' + id;
			}
			$.ajax({
				url: 'action/product_by_cate.php',
				data: { category_id: id, page: (page == undefined ? 1 : page) },
				type: 'POST',
				dataType:"HTML",
				error: function(e,s,r){ alert(e); },
				success: function(data){ $('#product-list').html(data); },	
			});
			
			
		}
	});
	var shop = /#shop-(.*)/g.exec(location.hash) == null ? 0 : /#shop-(.*)/g.exec(location.hash)[1];
	$('div.cate-list[hash="' + shop + '"]').CATEGORY_CHANGE(shop);
	
	
});
</script>
<table border="0" cellpadding="3" cellspacing="0" width="100%">
  <tr>
    <td width="300" valign="top">
      <div class="list-group">
      <div class="list-group-item cate-list active" hash="0" onClick="$(this).CATEGORY_CHANGE(0);">ALL</div>
	  <?php foreach($base->Query("SELECT category_id, name FROM category WHERE sub_id = 0 ORDER BY name ASC;") as $dRow): ?>
      <div class="list-group-item cate-list" hash="<?php echo $dRow['category_id']; ?>" onClick="$(this).CATEGORY_CHANGE(<?php echo $dRow['category_id']; ?>);">
        <?php 
		$name_en = $dRow['name'];
		$name_th = "";
		if (preg_match("/(.*)#(.*)/i", $dRow['name'])) {
			preg_match("/(.*)#(.*)/i", $dRow['name'], $arr_name);
			$name_en = $arr_name[1];
			$name_th = $arr_name[2];
		}
		?>
	    <div class="cate-en"><?php echo $name_en; ?></div>
        <div class="cate-th"><?php echo $name_th; ?></div>
      </div>
	    <?php foreach($base->Query("SELECT category_id, name FROM category WHERE sub_id = $dRow[category_id] ORDER BY name ASC;") as $dSub): ?>
        <div class="list-group-item cate-list cate-sub" hash="<?php echo $dSub['category_id']; ?>" onClick="$(this).CATEGORY_CHANGE(<?php echo $dSub['category_id']; ?>);" style="padding-left: 40px;">
          <?php 
		  $name_en = $dSub['name'];
		  $name_th = "";
		  if (preg_match("/(.*)#(.*)/i", $dSub['name'])) {
			  preg_match("/(.*)#(.*)/i", $dSub['name'], $arr_name);
			  $name_en = $arr_name[1];
			  $name_th = $arr_name[2];
		  }
		  ?>
	      <div class="cate-en"><?php echo $name_en; ?></div>
          <div class="cate-th"><?php echo $name_th; ?></div>
        </div>
        <?php endforeach; ?>
      <?php endforeach; ?>
     </div>
    </td>
    <td id="product-list" valign="top">
    </td>
  </tr>
</table>