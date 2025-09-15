<div class="container" style="padding:20px;">
	<h2><?php echo isset($page_title) ? $page_title : 'Expense Management'; ?></h2>
	
	<?php if ($this->session->flashdata('success')): ?>
		<div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
	<?php endif; ?>
	
	<?php if ($this->session->flashdata('error')): ?>
		<div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
	<?php endif; ?>
	
	<div class="row">
		<div class="col-md-8">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4>Expenses</h4>
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered">
							<thead>
								<tr>
									<th>Date</th>
									<th>Category</th>
									<th>Description</th>
									<th>Amount</th>
									<th>Vendor</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($expenses)): ?>
									<?php foreach ($expenses as $expense): ?>
									<tr>
										<td><?php echo date('M d, Y', strtotime($expense['expense_date'])); ?></td>
										<td><?php echo htmlspecialchars($expense['expense_category']); ?></td>
										<td><?php echo htmlspecialchars(substr($expense['expense_description'], 0, 50)) . (strlen($expense['expense_description']) > 50 ? '...' : ''); ?></td>
										<td><?php echo $expense['currency'] . ' ' . number_format($expense['amount'], 2); ?></td>
										<td><?php echo htmlspecialchars($expense['vendor_name']); ?></td>
										<td>
											<a href="<?php echo base_url(); ?>extensions/expense_management/view/<?php echo $expense['expense_id']; ?>" class="btn btn-sm btn-info">View</a>
											<a href="<?php echo base_url(); ?>extensions/expense_management/edit/<?php echo $expense['expense_id']; ?>" class="btn btn-sm btn-warning">Edit</a>
											<a href="<?php echo base_url(); ?>extensions/expense_management/delete/<?php echo $expense['expense_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this expense?')">Delete</a>
										</td>
									</tr>
									<?php endforeach; ?>
								<?php else: ?>
									<tr>
										<td colspan="6" class="text-center">No expenses found</td>
									</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		
		<div class="col-md-4">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h4>Total Expenses</h4>
				</div>
				<div class="panel-body">
					<h3>USD <?php echo number_format($total_expenses, 2); ?></h3>
				</div>
			</div>
			
			<div class="panel panel-info">
				<div class="panel-heading">
					<h4>Expenses by Category</h4>
				</div>
				<div class="panel-body">
					<?php if (!empty($expenses_by_category)): ?>
						<?php foreach ($expenses_by_category as $category): ?>
						<div class="row">
							<div class="col-sm-6"><?php echo htmlspecialchars($category['expense_category']); ?></div>
							<div class="col-sm-6 text-right">USD <?php echo number_format($category['total_amount'], 2); ?></div>
						</div>
						<hr>
						<?php endforeach; ?>
					<?php else: ?>
						<p>No expenses by category</p>
					<?php endif; ?>
				</div>
			</div>
			
			<div class="panel panel-success">
				<div class="panel-heading">
					<h4>Quick Actions</h4>
				</div>
				<div class="panel-body">
					<a href="<?php echo base_url(); ?>extensions/expense_management/add" class="btn btn-success btn-block">Add New Expense</a>
				</div>
			</div>
		</div>
	</div>
</div>
