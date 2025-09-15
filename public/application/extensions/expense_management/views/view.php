<div class="container" style="padding:20px;">
	<h2><?php echo isset($page_title) ? $page_title : 'View Expense'; ?></h2>
	
	<div class="row">
		<div class="col-md-8">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4>Expense Details</h4>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-6">
							<p><strong>Category:</strong> <?php echo htmlspecialchars($expense['expense_category']); ?></p>
							<p><strong>Amount:</strong> <?php echo $expense['currency'] . ' ' . number_format($expense['amount'], 2); ?></p>
							<p><strong>Date:</strong> <?php echo date('M d, Y', strtotime($expense['expense_date'])); ?></p>
							<p><strong>Payment Method:</strong> <?php echo htmlspecialchars($expense['payment_method'] ?: 'Not specified'); ?></p>
						</div>
						<div class="col-md-6">
							<p><strong>Vendor:</strong> <?php echo htmlspecialchars($expense['vendor_name'] ?: 'Not specified'); ?></p>
							<p><strong>Receipt Number:</strong> <?php echo htmlspecialchars($expense['receipt_number'] ?: 'Not specified'); ?></p>
							<p><strong>Created:</strong> <?php echo date('M d, Y H:i', strtotime($expense['created_at'])); ?></p>
							<p><strong>Created by:</strong> <?php echo $expense['first_name'] . ' ' . $expense['last_name']; ?></p>
						</div>
					</div>
					
					<?php if ($expense['expense_description']): ?>
					<div class="form-group">
						<label><strong>Description:</strong></label>
						<p><?php echo nl2br(htmlspecialchars($expense['expense_description'])); ?></p>
					</div>
					<?php endif; ?>
					
					<?php if ($expense['updated_at']): ?>
					<div class="form-group">
						<p><strong>Last Updated:</strong> <?php echo date('M d, Y H:i', strtotime($expense['updated_at'])); ?></p>
					</div>
					<?php endif; ?>
					
					<div class="form-group">
						<a href="<?php echo base_url(); ?>extensions/expense_management/edit/<?php echo $expense['expense_id']; ?>" class="btn btn-warning">Edit</a>
						<a href="<?php echo base_url(); ?>extensions/expense_management/delete/<?php echo $expense['expense_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this expense?')">Delete</a>
						<a href="<?php echo base_url(); ?>extensions/expense_management" class="btn btn-default">Back to List</a>
					</div>
				</div>
			</div>
		</div>
		
		<div class="col-md-4">
			<div class="panel panel-info">
				<div class="panel-heading">
					<h4>Quick Actions</h4>
				</div>
				<div class="panel-body">
					<a href="<?php echo base_url(); ?>extensions/expense_management/add" class="btn btn-success btn-block">Add New Expense</a>
					<a href="<?php echo base_url(); ?>extensions/expense_management" class="btn btn-default btn-block">View All Expenses</a>
				</div>
			</div>
		</div>
	</div>
</div>
