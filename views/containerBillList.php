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
	function saveInfo(comment_txt,draftNumber)
	{
		
		//alert("Comments : "+comment_txt+" Draft : "+draftNumber);

		if(confirm('Do you want to save Information ? '))
		{
			
			if(comment_txt.length>0)
			{
				//alert("Comments : "+comment_txt+" Draft : "+draftNumber);
				//document.search_bill.bill_type.focus() ;
				if (window.XMLHttpRequest) 
				{
					// code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp=new XMLHttpRequest();
				} 
				else 
				{  
					// code for IE6, IE5
					xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange=stateChangeSaveInformation;
				xmlhttp.open("GET","<?php echo site_url('ajaxController/saveDisputeComments')?>?bill_no="+draftNumber+"&comment="+comment_txt,false);
				xmlhttp.send();
			}
			else
			{
				alert("Please Input Comments");
				//document.search_bill.bill_type.focus() ;
				
			}
			
			return true;
		}
		else
		{
			//document.search_bill.bill_type.focus() ;
			return false;
		}
		
	}
	function stateChangeSaveInformation()
	{
		//alert("ddfd");
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{
				  
			var val = xmlhttp.responseText;
			var jsonData = JSON.parse(val);
			//alert(jsonData.stat);
			//var cnfCodeTxt=document.getElementById("cnfName");
			if(jsonData.stat[0]=="1")
			{				
				alert("Dispute Comment Save Successfully.");

			}
			else
			{
				alert("Dispute Comment Not Save Successfully.");
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
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Bill/searchBillofContainer'; ?>" target="" id="search_bill" name="search_bill" onsubmit="return(chk_rotation());">
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Search By: <span class="required">*</span></span>
									<select name="search_by" id="search_by" class="form-control" onchange="search_type(this.value);">
										<option value="">--Select--</option>
										<option value="bill_type">Bill Type</option>
										<option value="draft_no">Draft No</option>
										<option value="imp_rotation_no">Rotation No (Import)</option>
										<option value="exp_rotation_no">Rotation No (Export)</option>
									</select>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Bill Type: <span class="required">*</span></span>
									<select name="bill_type" id="bill_type" onchange="search_type(this.value);" class="form-control" disabled>
										<option value="">--Select--</option>
										<option value="ICD BILL">ICD BILL</option>
										<option value="CONTAINER LOADING">CONTAINER LOADING</option>
										<option value="REEFER CHARGE ON CONTAINER">REEFER CHARGE ON CONTAINER</option>
										<option value="PCT CONT LOAD">PCT CONT LOAD</option>
										<option value="CONTAINER DISCHARGE">CONTAINER DISCHARGE</option>
										<option value="PCT CONT DISCHARGE">PCT CONT DISCHARGE</option>
										<option value="EXPORT STORAGE INVOICE">EXPORT STORAGE INVOICE</option>
										<option value="MISCELLANEOUS INVOICE">MISCELLANEOUS INVOICE</option>
										<option value="MUKTARPUR CONT DISCHARGE">MUKTARPUR CONT DISCHARGE</option>
										<option value="MUKTARPUR CONT LOAD">MUKTARPUR CONT LOAD</option>
										<option value="OFFHIRE CHARGES ON CONTAINER">OFFHIRE CHARGES ON CONTAINER</option>
										<option value="EXPORT STORAGE INVOICE">EXPORT STORAGE INVOICE</option>
										<option value="STATUS CHANGE INVOICE (OFFDOCK TO MUKTARPUR)">STATUS CHANGE INVOICE (OFFDOCK TO MUKTARPUR)</option>
										<option value="STATUS CHANGE INVOICE (CPA TO PCT)">STATUS CHANGE INVOICE (CPA TO PCT)</option>
										<option value="STATUS CHANGE INVOICE (PCT TO CPA)">STATUS CHANGE INVOICE (PCT TO CPA)</option>
										<option value="STATUS CHANGE INVOICE (CPA TO ICD)">STATUS CHANGE INVOICE (CPA TO ICD)</option>
										<option value="STATUS CHANGE INVOICE (FCL TO LCL)">STATUS CHANGE INVOICE (FCL TO LCL)</option>
										<option value="STATUS CHANGE INVOICE (LCL TO FCL)">STATUS CHANGE INVOICE (LCL TO FCL)</option>
									</select>
								</div>		
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Draft No: <span class="required">*</span></span>
									<input type="text" name="draft_no" id="draft_no" class="form-control" placeholder="Draft No" disabled />
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Rotation No: <span class="required">*</span></span>
									<input type="text" name="rotation_no" id="rotation_no" class="form-control" placeholder="Rotation No" disabled />
								</div>												
							</div>
																			
							<div class="row">
								<div class="col-sm-12 text-center">
									<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">Search</button>
								</div>													
							</div>
							<div class="row">
								<div class="col-sm-12 text-center">
									
								</div>
							</div>
						</div>	
					</form>
				</div>
			</section>
		</div>
	</div>

	<div class="table-responsive">
		<table class="table table-bordered table-responsive table-hover table-striped mb-none">
			<thead>
				<tr class="gridDark">
					<th>Sl</th>
					<th>Draft No</th>
					<th>MLO</th>
					<th>Imprt. Rot</th>
					<th>Exp. Rot</th>
					<th>Bill Type</th>
					<th>Status</th>
					<th>Dispute Comments</th>
					<th>View Bill</th>
					<th>View Detail</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$j=@$start;
				for($i=0;$i<count($rslt_bill_list);$i++)
				{
					$j++;
				?>
				<tr class="gradex">
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
						<?php echo $rslt_bill_list[$i]['draft_final_status']; ?>
					</td>

					<td align="center">
						<input type="text" id="dis_comment" name="dis_comment" class="form-control" value="<?php if($rslt_bill_list[$i]['disputeDetails']=="") echo ""; else echo $rslt_bill_list[$i]['disputeDetails'] ;?>"
						<?php if($rslt_bill_list[$i]['draft_final_status']<1) { ?> onblur="saveInfo(this.value,'<?php echo $rslt_bill_list[$i]['draft_id']; ?>')" <?php } ?>
						<?php if($rslt_bill_list[$i]['draft_final_status']>0) { ?> readonly <?php } ?>
						>
					</td>

					<td class="gridLight" align="center">
						<form name="view_bill" id="view_bill" action="<?php echo site_url("bill/viewContainerBill"); ?>" method="post" target="_blank">
							<input type="hidden" name="draftNumber" id="draftNumber" value="<?php echo $rslt_bill_list[$i]['draft_id']; ?>" />
							<input type="hidden" name="draft_view" id="draft_view" value="<?php echo $rslt_bill_list[$i]['pdf_draft_view_name']; ?>" />
							<button name="view_container_bill" id="view_container_bill" type="submit" class="btn btn-primary">View Bill</button>
						</form>
					</td>

					<td class="gridLight" align="center">
						<form name="view_bill_detail" id="view_bill_detail" action="<?php echo site_url("bill/viewContainerDetail"); ?>" method="post" target="_blank">
							<input name="draft_detail_view" id="draft_detail_view" type="hidden" value="<?php echo $rslt_bill_list[$i]['pdf_draft_view_name']; ?>" />
							<input name="draftNumberDetail" id="draftNumberDetail" type="hidden" value="<?php echo $rslt_bill_list[$i]['draft_id']; ?>" />
							<button name="view_container_detail" id="view_container_detail" type="submit" class="btn btn-primary">View Detail</button>
						</form>
					</td>
				</tr>
				<?php
				}
				?>
				<tr class="gradex">
					<td colspan="13" align="center"><p><?php echo @$links; ?></p></td>
				</tr>
			</tbody>
		</table>
	</div>
</section>