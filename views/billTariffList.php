
<script>
   function conformation()
    {
        if (confirm("Are you sure!! Delete this record?") == true) {
		   return true ;
		} 
		else 
		{
			return false;
        }		 
    }

	</script>
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>

	</header>
          
	<div class="row">
		<div class="col-md-12">
			<section class="panel">
				<header class="panel-heading">			
					<h2 class="panel-title"><?php  echo $msg; ?></h2>
				</header>
								

				<div class="panel-body">
					<table class="table table-bordered table-responsive table-hover table-striped mb-none" id="datatable-default">
						<thead>
							<tr>
								<th>SL</th>
								<th>ID</th>
								<th>Description</th>
								<th>Long Description</th>
								<th>GL Code</th>
								<th>Bill Type</th>
								<th>Action</th>								
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
								<td><?php echo $result[$i]['description']; ?></td>
								<td><?php echo $result[$i]['long_description']; ?></td>
								<td><?php echo $result[$i]['gl_code']; ?></td>
								<td><?php echo $result[$i]['bill_type']; ?></td>								
								<td>
									<form method='POST' action="<?php echo site_url("ShedBillController/billTariffAction"); ?>">
										<input type="hidden" name="gkey" value="<?php echo $result[$i]['gkey']; ?>" />
										<input type="submit" name="editTarrif" value="Edit" class="btn btn-primary" />
									</form>
								</td>
								<td>
									<form method='POST' action="<?php echo site_url("ShedBillController/billTariffAction"); ?>" onsubmit="return conformation();">
										<input type="hidden" name="gkey" value="<?php echo $result[$i]['gkey']; ?>" />
										<input type="submit" name="delete" value="Delete" class="btn btn-primary" />
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





