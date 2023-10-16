<script type="text/javascript">
	function chk_rotation()
	{
		if( document.search_bill.search_by.value == "")
		{
			alert( "Please provide Search By option!" );
			document.search_bill.search_by.focus() ;
			return false;
		}
		else if( document.search_bill.search_by.value == "bill_type" && document.search_bill.bill_type.value == "")
		{
			alert( "Please provide Bill Type!" );
			document.search_bill.bill_type.focus() ;
			return false;
		}
		else if( document.search_bill.search_by.value == "draft_no" && document.search_bill.draft_no.value == "")
		{
			alert( "Please provide Draft No!" );
			document.search_bill.bill_type.focus() ;
			return false;
		}
		else if( document.search_bill.search_by.value == "imp_rotation_no" && document.search_bill.rotation_no.value == "")
		{
			alert( "Please provide Import Rotation No!" );
			document.search_bill.bill_type.focus() ;
			return false;
		}
		else if( document.search_bill.search_by.value == "exp_rotation_no" && document.search_bill.rotation_no.value == "")
		{
			alert( "Please provide Export Rotation No!" );
			document.search_bill.bill_type.focus() ;
			return false;
		}
		
		return true ;
	}
	
	function search_type(type)
	{
		if( document.search_bill.search_by.value == "" )
		{
			alert( "Please provide search type!" );
			document.myForm.date.focus() ;
			return false;
		}
		else
		{	
			if(type=="bill_type")
			{
				bill_type.disabled=false;
				draft_no.disabled=true;
				rotation_no.disabled=true;
				
				document.search_bill.draft_no.value="";
				document.search_bill.rotation_no.value="";
			}
			else if(type=="draft_no")
			{
				bill_type.disabled=true;
				draft_no.disabled=false;
				rotation_no.disabled=true;
				
				document.search_bill.bill_type.value="";
				document.search_bill.rotation_no.value="";
			}
			else if(type=="imp_rotation_no" || type=="exp_rotation_no")
			{
				bill_type.disabled=true;
				draft_no.disabled=true;
				rotation_no.disabled=false;
				
				document.search_bill.bill_type.value="";
				document.search_bill.draft_no.value="";
			}
		}
	}
</script> 


<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>


	<div class="row">
		<div class="col-lg-12">						
			<section class="panel">
				<div class="panel-body">
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Bill/searchBillofContainerVersion'; ?>" target="_blank" id="search_bill" name="search_bill" onsubmit="return(chk_rotation());">
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Search By <span class="required">*</span></span>
										<select name="search_by" id="search_by" class="form-control" onchange="search_type(this.value);">
											<option value="">--Select--</option>
											<option value="bill_type">Bill Type</option>
											<option value="draft_no">Draft No</option>
											<option value="imp_rotation_no">Rotation No (Import)</option>
											<option value="exp_rotation_no">Rotation No (Export)</option>
										</select>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Bill Type <span class="required">*</span></span>
										<select name="bill_type" id="bill_type" class="form-control" onchange="search_type(this.value);" disabled>
											<option value="">--Select--</option>
											<option value="ICD BILL">ICD BILL</option>
											<option value="CONTAINER LOADING">CONTAINER LOADING</option>
											<option value="STATUS CHANGE INVOICE (CPA TO PCT)">STATUS CHANGE INVOICE (CPA TO PCT)</option>
											<option value="REEFER CHARGE ON CONTAINER">REEFER CHARGE ON CONTAINER</option>
											<option value="PCT CONT LOAD">PCT CONT LOAD</option>
											<option value="CONTAINER DISCHARGE">CONTAINER DISCHARGE</option>
											<option value="PCT CONT DISCHARGE">PCT CONT DISCHARGE</option>
										</select>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Draft No <span class="required">*</span></span>
									<input type="text" name="draft_no" id="draft_no" class="form-control" placeholder="Container No" disabled>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Rotation No <span class="required">*</span></span>
									<input type="text" name="rotation_no" id="rotation_no" class="form-control" placeholder="Rotation No" disabled>
								</div>												
							</div>
																			
							<div class="row">
								<div class="col-sm-12 text-center">
									<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
									<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">Search</button>
								</div>													
							</div>
							<div class="row">
								<div class="col-sm-12 text-center">
									
								</div>
							</div>
						</div>
					</form>
					<br/>
					<br/>
					<br/>

					<table class="table table-bordered table-responsive table-hover table-striped mb-none">
						<tr class="gridDark">
							<th>Sl</th>
							<th>Draft No</th>
							<th>MLO</th>
							<th>Imprt. Rot</th>
							<th>Exp. Rot</th>
							<th>Bill Type</th>
							<th>Version</th>
							<th>Modification Type</th>
							<th>View Bill</th>
							<th>View Detail</th>
						</tr>
						<?php
						$j=@$start;
						for($i=0;$i<count($rslt_bill_list);$i++)
						{
							$j++;
						?>
						<tr <?php if($i%2==0){ ?> bgcolor="#58D3F7"<?php } else {?>  bgcolor="#F6E3CE" <?php } ?> bgcolor="#">
							<td width="20px"  align="center">
								<?php echo $j; ?>
							</td>
							<td align="center">
								<?php echo $rslt_bill_list[$i]['draft_id']; ?>
							</td>
							<td align="center">
								<?php echo $rslt_bill_list[$i]['mlo_code']; ?>
							</td>
							<td align="center">
								<?php echo $rslt_bill_list[$i]['imp_rot']; ?>
							</td>
							<td align="center">
							<?php echo $rslt_bill_list[$i]['exp_rot']; ?>				
							</td>
							<td  align="center">
								<?php echo $rslt_bill_list[$i]['billtype']; ?>
							</td>
							<td align="center">
								<?php echo $rslt_bill_list[$i]['version']; ?>
							</td>
							<td align="center">
								<?php echo $rslt_bill_list[$i]['modification_type']; ?>
							</td>
							<td class="gridLight" align="center">
								<form name="view_bill" id="view_bill" action="<?php echo site_url("bill/viewContainerBillVersion"); ?>" method="post" target="_blank">
									<input type="hidden" name="draftNumber" id="draftNumber" value="<?php echo $rslt_bill_list[$i]['draft_id']; ?>" />
									<input type="hidden" name="draft_view" id="draft_view" value="<?php echo $rslt_bill_list[$i]['pdf_draft_view_name']; ?>" />
									<input type="hidden" name="version" id="version" value="<?php echo $rslt_bill_list[$i]['version']; ?>" />
									<button type="submit" name="view_container_bill" id="view_container_bill" class="mb-xs mt-xs mr-xs btn btn-success">View Bill</button>
								</form>
							</td>
							<td class="gridLight" align="center">
								<form name="view_detail" id="view_detail" action="<?php echo site_url("bill/viewContainerDetailVersion"); ?>" method="post" target="_blank">
									<input name="draft_detail_view" id="draft_detail_view" type="hidden" value="<?php echo $rslt_bill_list[$i]['pdf_draft_view_name']; ?>" />
									<input name="draftNumberDetail" id="draftNumberDetail" type="hidden" value="<?php echo $rslt_bill_list[$i]['draft_id']; ?>" />
									<input name="version" id="version" type="hidden" value="<?php echo $rslt_bill_list[$i]['version']; ?>" />
									<button type="submit" name="view_container_detail" id="view_container_detail" class="mb-xs mt-xs mr-xs btn btn-success">View Detail</button>
								</form>
							</td>
						</tr>
						<?php
						}
						?>
						<tr>
							<td colspan="13" align="center"><p><?php echo @$links; ?></p></td>
						</tr>
					</table>
				</div>
			</section>
		</div>
	</div>	

</section>