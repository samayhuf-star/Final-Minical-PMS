<div class="container" style="padding:20px;">
	<h2><?php echo isset($page_title) ? $page_title : 'Edit Expense'; ?></h2>
	
	<?php if (isset($errors) && $errors): ?>
		<div class="alert alert-danger"><?php echo $errors; ?></div>
	<?php endif; ?>
	
	<div class="row">
		<div class="col-md-8">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4>Edit Expense</h4>
				</div>
				<div class="panel-body">
					<form method="post" action="<?php echo base_url(); ?>extensions/expense_management/edit/<?php echo $expense['expense_id']; ?>">
						<div class="form-group">
							<label for="expense_category">Expense Category *</label>
							<input type="text" class="form-control" id="expense_category" name="expense_category" value="<?php echo set_value('expense_category', $expense['expense_category']); ?>" required>
						</div>
						
						<div class="form-group">
							<label for="expense_description">Description</label>
							<textarea class="form-control" id="expense_description" name="expense_description" rows="3"><?php echo set_value('expense_description', $expense['expense_description']); ?></textarea>
						</div>
						
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="amount">Amount *</label>
									<input type="number" class="form-control" id="amount" name="amount" step="0.01" min="0" value="<?php echo set_value('amount', $expense['amount']); ?>" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="currency">Currency *</label>
									<select class="form-control" id="currency" name="currency" required>
										<option value="USD" <?php echo set_select('currency', 'USD', $expense['currency'] == 'USD'); ?>>USD</option>
										<option value="EUR" <?php echo set_select('currency', 'EUR', $expense['currency'] == 'EUR'); ?>>EUR</option>
										<option value="GBP" <?php echo set_select('currency', 'GBP', $expense['currency'] == 'GBP'); ?>>GBP</option>
										<option value="CAD" <?php echo set_select('currency', 'CAD', $expense['currency'] == 'CAD'); ?>>CAD</option>
									</select>
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<label for="expense_date">Expense Date *</label>
							<input type="date" class="form-control" id="expense_date" name="expense_date" value="<?php echo set_value('expense_date', $expense['expense_date']); ?>" required>
						</div>
						
						<div class="form-group">
							<label for="payment_method">Payment Method</label>
							<select class="form-control" id="payment_method" name="payment_method">
								<option value="">-- Select Payment Method --</option>
								<option value="Cash" <?php echo set_select('payment_method', 'Cash', $expense['payment_method'] == 'Cash'); ?>>Cash</option>
								<option value="Credit Card" <?php echo set_select('payment_method', 'Credit Card', $expense['payment_method'] == 'Credit Card'); ?>>Credit Card</option>
								<option value="Debit Card" <?php echo set_select('payment_method', 'Debit Card', $expense['payment_method'] == 'Debit Card'); ?>>Debit Card</option>
								<option value="Bank Transfer" <?php echo set_select('payment_method', 'Bank Transfer', $expense['payment_method'] == 'Bank Transfer'); ?>>Bank Transfer</option>
								<option value="Check" <?php echo set_select('payment_method', 'Check', $expense['payment_method'] == 'Check'); ?>>Check</option>
								<option value="Other" <?php echo set_select('payment_method', 'Other', $expense['payment_method'] == 'Other'); ?>>Other</option>
							</select>
						</div>
						
						<div class="form-group">
							<label for="receipt_number">Receipt Number</label>
							<input type="text" class="form-control" id="receipt_number" name="receipt_number" value="<?php echo set_value('receipt_number', $expense['receipt_number']); ?>">
						</div>
						
						<div class="form-group">
							<label for="vendor_name">Vendor Name</label>
							<input type="text" class="form-control" id="vendor_name" name="vendor_name" value="<?php echo set_value('vendor_name', $expense['vendor_name']); ?>">
						</div>
						
						<div class="form-group">
							<button type="submit" class="btn btn-primary">Update Expense</button>
							<a href="<?php echo base_url(); ?>extensions/expense_management" class="btn btn-default">Cancel</a>
						</div>
					</form>
				</div>
			</div>
		</div>
		
		<div class="col-md-4">
			<div class="panel panel-info">
				<div class="panel-heading">
					<h4>Expense Details</h4>
				</div>
				<div class="panel-body">
					<p><strong>Created:</strong> <?php echo date('M d, Y H:i', strtotime($expense['created_at'])); ?></p>
					<p><strong>Created by:</strong> <?php echo $expense['first_name'] . ' ' . $expense['last_name']; ?></p>
					<?php if ($expense['updated_at']): ?>
					<p><strong>Last Updated:</strong> <?php echo date('M d, Y H:i', strtotime($expense['updated_at'])); ?></p>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>
