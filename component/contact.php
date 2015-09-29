<script>

	if(Touno.Cookie('ACCESS') == ""){
		$('#btnCancel').hide();
		$('#btnEditor').hide();
	}
	var editor;

	function toggleEditor(unSave) {
		var config = { height: '350px' };
		if(!editor) {
			$('#email').hide();
			$('#btnEditor').val('Save');
			$('#contents').hide();
			$('#editor').show();
			$('#btnCancel').show();
			editor = CKEDITOR.appendTo( 'editor', config, $('#contents').html());
		} else {
			$('#email').show();
			$('#contents').show();
			$('#editor').hide();
			$('#btnCancel').hide();
			$('#btnEditor').val('Editor');
			if(!unSave) {
				$('#contents').html(editor.getData());
				$.ajax({
					url: 'action/contant_editor.php',
					data: { title : 'contact', des: editor.getData() },
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
	
	function sending() {
		$('#btnSend').prop('disable', true);
		$.ajax({
			url: 'action/sending_email.php',
			data: { 
				name : $('#txtName').val(),
				email : $('#txtEmail').val(),
				subject : $('#txtSubject').val(),
				message : $('#txtMessage').val()
			},
			type: 'POST',
			dataType:"HTML",
			error: function(){ alert('fail.'); },
			success: function(data){ alert(data); $('#btnSend').prop('disable', false); }	
		});
	}

</script>
<p>
<input id="btnEditor" onclick="toggleEditor();" class="btn btn-primary" type="button" value="Editor">
<input id="btnCancel" onclick="toggleEditor(true);" class="btn" type="button" value="Cancel" style="display:none;">
</p>

<?php include("../libs/SyncDatabase.php"); $base = new SyncDatabase(); $home = $base->Query("SELECT title, description FROM contents WHERE title_id='contact'"); ?>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td width="50%" valign="top">
        <h2><?php echo $home[0]['title']; ?></h2>
		<div id="contents">
			<?php echo $home[0]['description']; ?>
		</div>
		<div id="editor" style="display: none">
		</div>
    </td>
    <td id="email" width="50%" valign="top">
        <table class="tb-block" border="0" cellpadding="3" cellspacing="0">
          <tr>
            <td><strong>Name</strong></td>
          </tr>
          <tr>
            <td><input type="text" id="txtName" class="form-control" value="" style="width:200px;"></td>
          </tr>
          <tr>
            <td><strong>Email</strong></td>
          </tr>
          <tr>
            <td><input type="text" id="txtEmail" class="form-control" value="" style="width:200px;"></td>
          </tr>
          <tr>
            <td><strong>Subject</strong></td>
          </tr>
          <tr>
            <td><input type="text" id="txtSubject" class="form-control" value="" style="width:400px;"></td>
          </tr>
          <tr>
            <td><strong>Message</strong></td>
          </tr>
          <tr>
            <td><textarea class="form-control" id="txtMessage" cols="50" rows="10" style="width:398px; resize:none;"></textarea></td>
          </tr>
          <tr>
            <td><input type="button" id="btnSend" class="btn btn-lg btn-primary btn-block" value="Send Email" onclick="sending()"></td>
          </tr>
        </table>
    </td>
  </tr>
</table>

