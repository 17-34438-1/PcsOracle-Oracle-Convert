<!doctype html>
<script type="text/javascript">
	function delete_bill()
	{
		if (confirm("Do you want to detete this Bill?") == true)
		{
			return true ;
		}
		else
		{
			return false;
		}
	}
</script>
<style>
th, tr, td, label
{
	color: black;
}
</style>
<html class="fixed">
	<head>

		<?php
		include("cssAssetsList.php");
		?>

	</head>
	<body>
<section class="body">

<?php
include("headerTop.php");
?>

<div class="inner-wrapper">
<!-- start: sidebar -->

<?php include dirname(__FILE__).'/../sidebar.php'; ?>

<!-- end: sidebar -->

<section role="main" class="content-body">
	<header class="page-header">
		<h2>Billing List</h2>
	
		<!--div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="index.html">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><span>Tables</span></li>
				<li><span>Advanced</span></li>
			</ol>
	
			<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
		</div-->
	</header>

	<!-- start: page -->
		<section class="panel">
			<header class="panel-heading">
				<!--div class="panel-actions">
					<a href="#" class="fa fa-caret-down"></a>
					<a href="#" class="fa fa-times"></a>
				</div-->
		
				<h3 class="panel-title"><?php echo $msg;?></h2>
			</header>
			<div class="panel-body">
				<!--div align="right">
					<form name="new_bill_generation" id="new_bill_generation" action="<?php echo site_url("Menu_Controller/new_bill_generation"); ?>" method="post" target="_blank">
						<input name="generate_new_bill" id="generate_new_bill" type="submit" value="Generate New Bill" class="btn btn-primary btn-xs" />
					</form>
				</div-->
				<!--table align="rignt">
					<tr style="border: 0px solid black;">
						<td colspan="11" align="right"  >
							<form name="new_bill_generation" id="new_bill_generation" action="<?php echo site_url("Menu_Controller/new_bill_generation"); ?>" method="post" target="_blank">
								<input name="generate_new_bill" id="generate_new_bill" type="submit" value="Generate New Bill" class="btn btn-primary btn-xs" />
							</form>
						</td>							
					</tr>
				</table-->
				<table class="table table-bordered table-striped mb-none" id="datatable-default">
					<thead>
	                  	<tr>
							<th  align="center">Draft</th>
							<th  align="center">MLO</th>
							<th  align="center">Import Rotation</th>
							<th  align="center">Export Rotation</th>
							<th  align="center">Bill Type</th>
							<th  align="center" class="text-center">Status</th>
							<th  align="center" class="text-center"> Bill</th>
							<th  align="center" class="text-center"> Details</th>
							<th  align="center"> Delete</th>
						</tr>
					
					</thead>
					<tbody>
					<?php for($i=0;$i<count($bill_list);$i++)
						{ ?>
						<tr class="gradeX">
							<td><?php echo $bill_list[$i]['draft']; ?></td>
							<td><?php echo $bill_list[$i]['mlo_code']; ?></td>
							<td><?php echo $bill_list[$i]['imp_rot']; ?></td>
							<td><?php echo $bill_list[$i]['exp_rot']; ?></td>
							<td><?php echo $bill_list[$i]['billtype']; ?></td>
							<td class="text-center"><?php echo $bill_list[$i]['draft_final_status']; ?></td>
							<td>
								<form name="view_bill" id="view_bill" action="<?php echo site_url("ContainerBill/viewContainerBill"); ?>" method="post" target="_blank">
									<input type="hidden" name="draftNumber" id="draftNumber" value="<?php echo $bill_list[$i]['draft']; ?>" />
									<input type="hidden" name="draft_view" id="draft_view" value="<?php echo $bill_list[$i]['pdf_draft_view_name']; ?>" />
									<!--<input type="hidden" name="printBtnValue" id="printBtnValue" value="0" />-->
									<input name="view_container_bill" id="view_container_bill" type="submit" value="Bill"  class="btn btn-primary btn-xs" />
								</form>
							</td>
							
							<td>
								<form name="view_bill_detail" id="view_bill_detail" action="<?php echo site_url("ContainerBill/viewContainerDetail"); ?>" method="post" target="_blank">
									<input name="draft_detail_view" id="draft_detail_view" type="hidden" value="<?php echo $bill_list[$i]['pdf_draft_view_name']; ?>" />
									<input name="draftNumberDetail" id="draftNumberDetail" type="hidden" value="<?php echo $bill_list[$i]['draft']; ?>" />
									<!--<input type="hidden" name="printBtnValue" id="printBtnValue" value="0" />-->
									<input name="view_container_detail" id="view_container_detail" value="Detail" type="submit" class="btn btn-primary btn-xs"/>
								</form>
							</td>
							
						     <td>
								<form name="delete_view_bill" id="delete_view_bill" action="<?php echo site_url("ContainerBill/delete_bil"); ?>" method="post" onsubmit="return(delete_bill());">
									<input name="bill_type" id="bill_type" type="hidden" value="<?php echo $bill_list[$i]['bill_type']; ?>" />
									<input name="draft_id" id="draft_id" type="hidden" value="<?php echo $bill_list[$i]['draft']; ?>" />
									<input name="view_container_detail" id="view_container_detail" value="Delete" type="submit" class="btn btn-danger btn-xs"/>
								</form>					
							</td>
							
				</tr>
				<?php } ?>
			</tbody>
				</table>
			</div>
		</section>					
						
			</div>
		</section>

		<?php
			include("jsAssetsList.php");
		?>
	</body>
</html>
