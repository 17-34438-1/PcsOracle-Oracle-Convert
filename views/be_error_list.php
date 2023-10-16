<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
          
	<div class="row">
		<div class="col-md-12">
			<section class="panel">
				<header class="panel-heading">			
					<h2 class="panel-title"><?php echo $title; ?></h2>
				</header>
				<div class="panel-body">
					<?php
					if(count($rslt_list_of_be)!=0)
					{
					?>
					<table class="table table-bordered table-striped mb-none" id="datatable-default">
						<thead>
							<tr align="center">
								<td colspan="4"><b>Bill of Entry Error List</b></td>								
							</tr>
							<tr>
								<th class="gridDark">Sl</th>
								<th class="gridDark">File Name</th>
								<th class="gridDark">Message</th>
								<th class="gridDark">Entry Date</th>						
							</tr>
						</thead>
						<tbody>
						<?php
						for($i=0;$i<count($rslt_list_of_be);$i++)
						{
						?>
							<tr class="gradeX">
								<td><?php echo $i+1; ?></td>
								<td><?php echo $rslt_list_of_be[$i]['file_name']; ?></td>
								<td>
									<?php
									if(($rslt_list_of_be[$i]['message']=="Content is not allowed in prolog.") or ($rslt_list_of_be[$i]['message']=="Premature end of file."))
									{
									?>
									<font color="red"><?php echo $rslt_list_of_be[$i]['message']; ?></font>
									<?php
									}
									else
									{
										echo $rslt_list_of_be[$i]['message'];
									}
									?>
								</td>								
								<td>
									<nobr><?php echo $rslt_list_of_be[$i]['log_dt']; ?></nobr>
								</td>								
							</tr>
						<?php
						}
						?>
						</tbody>
					</table>
					<?php
					}
					?>
				</div>
			</section>
		</div>
	</div>
</section>
