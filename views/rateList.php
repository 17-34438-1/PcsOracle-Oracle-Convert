<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
          
	<div class="row">
		<div class="col-md-12">
			<section class="panel">
				<header class="panel-heading">			
					<h2 class="panel-title">Rate List</h2>
				</header>
				<div class="panel-body">
					<table class="table table-bordered table-striped mb-none" id="datatable-default">
						<thead>
							<tr>
								<th>SL</th>
								<th>ID</th>
								<th>Ammount</th>
								<th>Action</th>								
							</tr>
						</thead>
						<tbody>
						<?php
						for($i=0;$i<count($result);$i++)
						{
						?>
							<tr class="gradeX">
								<td><?php echo $i+1; ?></td>
								<td><?php echo $result[$i]['id']; ?></td>
								<td><?php echo $result[$i]['amount']; ?></td>								
								<td>
									<form method='POST' action="<?php echo site_url('ShedBillController/rateAction'); ?>">
										<input type="hidden" name="gkey" value="<?php echo $result[$i]['gkey']; ?>">
										<input type="submit" name="editRate" value="Edit" class="btn btn-primary" style="width:60px;">
									</form>
								</td>								
							</tr>
						<?php
						}
						?>
						</tbody>
					</table>
				</div>
			</section>
		</div>
	</div>
</section>
