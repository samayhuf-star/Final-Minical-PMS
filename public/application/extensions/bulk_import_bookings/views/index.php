<div class="container" style="padding:20px;">
	<h2><?php echo isset($page_title) ? $page_title : 'Bulk Import Bookings'; ?></h2>
	
	<div class="row">
		<div class="col-md-8">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4>Upload File</h4>
				</div>
				<div class="panel-body">
					<form id="uploadForm" enctype="multipart/form-data">
						<div class="form-group">
							<label for="file">Select CSV or Excel File:</label>
							<input type="file" class="form-control" id="file" name="file" accept=".csv,.xlsx,.xls" required>
							<small class="help-block">Supported formats: CSV, Excel (.xlsx, .xls). Maximum file size: 10MB</small>
						</div>
						<button type="submit" class="btn btn-primary">Upload File</button>
					</form>
				</div>
			</div>
		</div>
		
		<div class="col-md-4">
			<div class="panel panel-info">
				<div class="panel-heading">
					<h4>Import Instructions</h4>
				</div>
				<div class="panel-body">
					<h5>Required Fields:</h5>
					<ul>
						<li>Customer Name</li>
						<li>Check-in Date</li>
						<li>Check-out Date</li>
					</ul>
					
					<h5>Optional Fields:</h5>
					<ul>
						<li>Customer Email</li>
						<li>Customer Phone</li>
						<li>Room Type</li>
						<li>Adults Count</li>
						<li>Children Count</li>
						<li>Rate</li>
						<li>Booking Type (Book1/Book2)</li>
						<li>Booking Notes</li>
					</ul>
					
					<h5>Date Format:</h5>
					<p>Use YYYY-MM-DD format (e.g., 2024-01-15)</p>
				</div>
			</div>
		</div>
	</div>
	
	<div id="uploadResult" class="alert" style="display:none;"></div>
	
	<div id="mappingSection" style="display:none;">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4>Field Mapping</h4>
			</div>
			<div class="panel-body">
				<p>Map your file columns to booking fields:</p>
				<form id="mappingForm">
					<div id="mappingFields"></div>
					<button type="submit" class="btn btn-success">Preview Import</button>
				</form>
			</div>
		</div>
	</div>
	
	<div id="previewSection" style="display:none;">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4>Import Preview</h4>
			</div>
			<div class="panel-body">
				<div id="previewTable"></div>
				<button id="confirmImport" class="btn btn-primary">Confirm Import</button>
			</div>
		</div>
	</div>
	
	<div id="importResult" class="alert" style="display:none;"></div>
</div>

<script>
$(document).ready(function() {
	$('#uploadForm').on('submit', function(e) {
		e.preventDefault();
		
		var formData = new FormData(this);
		
		$.ajax({
			url: '<?php echo base_url(); ?>extensions/bulk_import_bookings/upload',
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			success: function(response) {
				var result = JSON.parse(response);
				
				if (result.status === 'success') {
					$('#uploadResult').removeClass('alert-danger').addClass('alert-success')
						.html('File uploaded successfully: ' + result.file_name).show();
					
					// Show mapping section
					showMappingSection(result.headers);
				} else {
					$('#uploadResult').removeClass('alert-success').addClass('alert-danger')
						.html('Error: ' + result.message).show();
				}
			},
			error: function() {
				$('#uploadResult').removeClass('alert-success').addClass('alert-danger')
					.html('Error uploading file').show();
			}
		});
	});
	
	function showMappingSection(headers) {
		var bookingFields = {
			'customer_name': 'Customer Name',
			'email': 'Customer Email',
			'phone': 'Customer Phone',
			'check_in_date': 'Check-in Date',
			'check_out_date': 'Check-out Date',
			'room_type': 'Room Type',
			'adult_count': 'Adults',
			'children_count': 'Children',
			'rate': 'Rate',
			'booking_kind': 'Booking Type',
			'booking_notes': 'Booking Notes',
			'address': 'Customer Address',
			'city': 'Customer City',
			'country': 'Customer Country'
		};
		
		var mappingHtml = '';
		for (var field in bookingFields) {
			mappingHtml += '<div class="form-group row">';
			mappingHtml += '<label class="col-sm-3 control-label">' + bookingFields[field] + ':</label>';
			mappingHtml += '<div class="col-sm-9">';
			mappingHtml += '<select class="form-control" name="mapping[' + field + ']">';
			mappingHtml += '<option value="">-- Select Column --</option>';
			
			for (var i = 0; i < headers.length; i++) {
				mappingHtml += '<option value="' + i + '">' + headers[i] + '</option>';
			}
			
			mappingHtml += '</select></div></div>';
		}
		
		$('#mappingFields').html(mappingHtml);
		$('#mappingSection').show();
	}
	
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
					showPreviewTable(result.preview_data);
					$('#previewSection').show();
				} else {
					alert('Error: ' + result.message);
				}
			},
			error: function() {
				alert('Error processing mapping');
			}
		});
	});
	
	function showPreviewTable(data) {
		if (data.length === 0) {
			$('#previewTable').html('<p>No data to preview</p>');
			return;
		}
		
		var tableHtml = '<table class="table table-striped table-bordered">';
		tableHtml += '<thead><tr>';
		
		// Headers
		for (var field in data[0]) {
			tableHtml += '<th>' + field.replace('_', ' ').toUpperCase() + '</th>';
		}
		tableHtml += '</tr></thead><tbody>';
		
		// Data rows
		for (var i = 0; i < data.length; i++) {
			tableHtml += '<tr>';
			for (var field in data[i]) {
				tableHtml += '<td>' + data[i][field] + '</td>';
			}
			tableHtml += '</tr>';
		}
		
		tableHtml += '</tbody></table>';
		$('#previewTable').html(tableHtml);
	}
	
	$('#confirmImport').on('click', function() {
		if (!confirm('Are you sure you want to import these bookings?')) {
			return;
		}
		
		$.ajax({
			url: '<?php echo base_url(); ?>extensions/bulk_import_bookings/import',
			type: 'POST',
			data: {},
			success: function(response) {
				var result = JSON.parse(response);
				
				if (result.status === 'success') {
					$('#importResult').removeClass('alert-danger').addClass('alert-success')
						.html('Import completed! Imported: ' + result.imported_count + 
							  ', Errors: ' + result.error_count).show();
					
					if (result.errors.length > 0) {
						$('#importResult').append('<br><strong>Errors:</strong><ul>');
						for (var i = 0; i < result.errors.length; i++) {
							$('#importResult').append('<li>' + result.errors[i] + '</li>');
						}
						$('#importResult').append('</ul>');
					}
				} else {
					$('#importResult').removeClass('alert-success').addClass('alert-danger')
						.html('Import failed: ' + result.message).show();
				}
			},
			error: function() {
				$('#importResult').removeClass('alert-success').addClass('alert-danger')
					.html('Error during import').show();
			}
		});
	});
});
</script>
