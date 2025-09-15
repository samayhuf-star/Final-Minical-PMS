<div class="container" style="padding:20px;">
	<h2><?php echo isset($page_title) ? $page_title : 'Field Mapping'; ?></h2>
	
	<div class="panel panel-default">
		<div class="panel-heading">
			<h4>Map Your File Columns to Booking Fields</h4>
		</div>
		<div class="panel-body">
			<form id="mappingForm">
				<?php foreach($booking_fields as $field => $label): ?>
				<div class="form-group row">
					<label class="col-sm-3 control-label"><?php echo $label; ?>:</label>
					<div class="col-sm-9">
						<select class="form-control" name="mapping[<?php echo $field; ?>]">
							<option value="">-- Select Column --</option>
							<?php foreach($headers as $index => $header): ?>
							<option value="<?php echo $index; ?>"><?php echo htmlspecialchars($header); ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
				<?php endforeach; ?>
				
				<div class="form-group">
					<button type="submit" class="btn btn-success">Preview Import</button>
					<a href="<?php echo base_url(); ?>extensions/bulk_import_bookings" class="btn btn-default">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
$(document).ready(function() {
	$('#mappingForm').on('submit', function(e) {
		e.preventDefault();
		
		var mappingData = $(this).serialize();
		
		$.ajax({
			url: '<?php echo base_url(); ?>extensions/bulk_import_bookings/preview',
			type: 'POST',
			data: mappingData,
			success: function(response) {
				var result = JSON.parse(response);
				
				if (result.status === 'success') {
					// Redirect to preview page or show preview in modal
					window.location.href = '<?php echo base_url(); ?>extensions/bulk_import_bookings/preview';
				} else {
					alert('Error: ' + result.message);
				}
			},
			error: function() {
				alert('Error processing mapping');
			}
		});
	});
});
</script>
