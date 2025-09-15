<div class="container" style="padding:20px;">
	<h2>Multi-Property Manager</h2>
	<p>Select a property to switch context.</p>
	<table class="table table-striped">
		<thead>
			<tr>
				<th>ID</th>
				<th>Name</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
		<?php if(!empty($companies)) { foreach($companies as $c) { ?>
			<tr>
				<td><?php echo $c['company_id']; ?></td>
				<td><?php echo htmlspecialchars($c['name']); ?></td>
				<td><a class="btn btn-primary btn-sm" href="<?php echo base_url().'extensions/multi_property_manager/switch/'.$c['company_id']; ?>">Switch</a></td>
			</tr>
		<?php } } else { ?>
			<tr><td colspan="3">No properties found.</td></tr>
		<?php } ?>
		</tbody>
	</table>
</div>


