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
					data: { title : 'about', des: editor.getData() },
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
<?php include("../libs/SyncDatabase.php"); $base = new SyncDatabase(); $home = $base->Query("SELECT title, description FROM contents WHERE title_id='about'"); ?>
<h2><?php echo $home[0]['title']; ?></h2>
<div id="contents">
	<?php echo $home[0]['description']; ?>
</div>
<div id="editor" style="display: none">
</div>