<script type="text/javascript">
	function delete_notice()
	{
		if (confirm("Do you want to detete this Notice?") == true)
		{
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
					<h2 class="panel-title"><?php  echo $title; ?></h2>
				</header>
				<div class="panel-body">
					<table class="table table-bordered table-striped mb-none" id="datatable-default">
						<thead>
							<tr>
								<th>Sl</th>
								<th>Title</th>
								<th>Organization</th>
								<th>Comment</th>
								<th>View</th>
								<th>Publish DateTime</th>
								<th>View Log</th>
								<th>Action</th>
								<th>Action</th>								
							</tr>
						</thead>
						<tbody>
						<?php 

						for($i=0;$i<count($rslt_notice_list);$i++)
						{
							$id=$rslt_notice_list[$i]['id'];
							$title=$rslt_notice_list[$i]['title'];
							$comment=$rslt_notice_list[$i]['comment'];
							$pdf_path=$rslt_notice_list[$i]['pdf_path'];
						//	$org_id=$rslt_notice_list[$i]['org_id'];
							$ent_date=$rslt_notice_list[$i]['entry_date'];	
						?>
						<tr>
							<td bgcolor="#f6ddcc " align="center"><?php echo $i+1; ?></td>
							<td bgcolor="#f6ddcc " align="center"><?php echo $title; ?></td>
							<td bgcolor="#f6ddcc " align="center">
								<?php 
									include("mydbPConnection.php");
								//	$j=0;
									$org_sql = "SELECT Org_Type 
									FROM tbl_org_types 
									INNER JOIN upload_notice_dtl ON tbl_org_types.id=upload_notice_dtl.org_id 
									WHERE notice_id='$id'";
									$sql_qury=mysqli_query($con_cchaportdb,$org_sql);
									while($org=mysqli_fetch_object($sql_qury))
									{
									//	$j++;
										echo $org->Org_Type.', ';			
									}
									
								?>
							</td>
							<td bgcolor="#f6ddcc " align="center"><?php echo $comment; ?></td>
							<td bgcolor="#f6ddcc " align="center"><a href="<?php echo BASE_PATH.'resources/notice/'.$pdf_path ?>" target="_blank" class="gridLight"> Notice</a></td>
							
							<td bgcolor="#f6ddcc " align="center"><?php echo $ent_date; ?></td>
							<td bgcolor="#f6ddcc " align="center">
									<form action="<?php echo site_url('report/notice_log_view');?>" method="POST" target="_blank">
										<input type="hidden" name="notice_id" value="<?php echo $id; ?>">
										<input type="submit" value="View log" name="Log View" class="mb-xs mt-xs mr-xs btn btn-primary" >
									</form>
							</td>
							<td bgcolor="#f6ddcc " align="center">
								<form id="motice_edit_form" name="motice_edit_form" method="post" action="<?php echo site_url("report/notice_edit_form"); ?>">
									<input id="notice_id" name="notice_id" type="hidden" value="<?php echo $id; ?>" />
									<input id="edit_btn" type="submit" name="edit" value="Edit" class="mb-xs mt-xs mr-xs btn btn-success"  />
								</form>
							</td>
							<td  bgcolor="#f6ddcc " align="center">
								<form id="notice_delete_form" name="notice_delete_form" method="post" action="<?php echo site_url("report/noticeUploadList"); ?>" onsubmit="return(delete_notice());">
									<input id="notice_id" name="notice_id" type="hidden" value="<?php echo $id; ?>" />
									<input id="delete_btn"  type="submit" name="delete" value="Delete" class="mb-xs mt-xs mr-xs btn btn-danger" />
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
