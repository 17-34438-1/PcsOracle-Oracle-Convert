 <style>
 #table-scroll {
  height:350px;
  overflow:auto;  
  margin-top:20px;
}
 </style>
<script>

	var slno="";
	
	function exchangeDone(sl) 
	{
		var answer = confirm("Are you want to Exchange Done?");
		slno=sl;
		// alert(sl);
		
		
		if (answer) 
		{
			var rotation = document.getElementById ("rotTdId_"+slno).innerHTML;
			var container = document.getElementById ("contTdId_"+slno).innerHTML;
		
			// alert(rotation);
			// alert(container);
			
			// return false;
			
			//console.log(rotation+"--"+container);
			//alert(rotation+"--"+container);
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
			xmlhttp.onreadystatechange=stateChangeValue;
			xmlhttp.open("GET","<?php echo site_url('ajaxController/ExchangeDoneStatusChange')?>?rotation="+rotation+"&container="+container,false);
			xmlhttp.send();
		}
		else 
		{
			
		}
		//alert("K");										 
	}
	
	function stateChangeValue()
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
				//document.getElementById("btnTr").style.visibility = 'hidden';
				//document.getElementById("btnTr").innerHTML = "";
				//document.getElementById("btnTr2").innerHTML = "";
				
				//document.getElementById("btnTr1").colSpan="4";

				alert("Exchange Done.");
				// deleteLastrow();
				// $("#exBtn").remove();
				// $("#tblInner tbody tr").find("td:eq(9)").remove();
				// //document.getElementById("vwBtn").style.width = "300px"; 
				// document.getElementById('excngeData').innerHTML = 
				// "<table><tr><td>Exchange Done.</td><td><a class='button' href='#popup1'  onclick='txttransfer()'><font color='white'>Upload Signature</font></a></td></tr></table>";
				// document.getElementById("btnView").style.visibility = 'block';
				
				document.getElementById("exBtn_"+slno).disabled = true; 
				document.getElementById("exBtn_"+slno).value = "Confirmed"; 
				
			}
			else
			{
				alert("Exchange Not Done.");
			}
		}
	}
</script>
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>
 <div class="content">
    <div class="content_resize">
      <div class="mainbar">
        <div class="article">
		
			<div class="row">
				<div class="col-lg-12">						
					<section class="panel">
						<div class="panel-body">
							<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/tallyFormList'; ?>" target="" id="myform" name="myform" onsubmit="return validate()">
								<div class="form-group">
									<label class="col-md-3 control-label">&nbsp;</label>
									<div class="col-md-6">		
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Rotation No <span class="required">*</span></span>
											<input type="text" name="ddl_imp_rot_no" id="ddl_imp_rot_no" class="form-control" placeholder="Rotation No">
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Container No <span class="required">*</span></span>
											<input type="text" name="ddl_cont_no" id="ddl_cont_no" class="form-control" placeholder="Container No">
										</div>												
									</div>
																					
									<div class="row">
										<div class="col-sm-12 text-center">
											<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
											<button type="submit" id="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success login_button">Show</button>
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

		 <!--</div>-->
		 </div>
		 
         
		  <?php echo form_close()?>

          <div class="clr"></div>
        </div>
       <div id="table-scroll" class="table-responsive">
			<table  class="table table-bordered table-responsive table-hover table-striped mb-none" >
					<tr class="gridDark" align="center">
						<!--td><b>View Appraisement</b></td-->
						<td ><b>Tally Sheet No</b></td>
						<td ><b>Container No</b></td>	
						<!--td ><b>Bl No</b></td-->						
						<td ><b>Rotation</b></td>
						<td ><b>Rcv Pkg</b></td>
						<td ><b>Fault Pack</b></td>
						<td ><b>Loc Fast</b></td>
						<td ><b>Position</b></td>
						<td ><b>Yard/Shed</b></td>
						<td ><b>Unstuffing Date</b></td>
						<td ><b>Report</b></td>
						<td ><b>Tally Confirm</b></td>
					</tr>
					<?php 
					for($i=0;$i<count($rtnContainerList);$i++)
					{
						$rot = $rtnContainerList[$i]['import_rotation'];
						$cont = $rtnContainerList[$i]['cont_number'];
					?>
					<tr class="gridLight" align="center">
					
						<!--td align="center"> 
							<form action="<?php echo site_url('report/appraisementCertifyList/'.str_replace("/","_",$rtnContainerList[$i]['BL_NO']).'/'.str_replace("/","_",$rtnContainerList[$i]['rotation']))?>" target="_blank" method="POST">						
								<input type="submit" value="View"  class="login_button" style="width:100%;">							
							</form> 
						</td--> 
						<td style="color:red"><?php echo $rtnContainerList[$i]['tally_sheet_number'];?></td>
						<td id="contTdId_<?php echo $i; ?>"><?php echo $rtnContainerList[$i]['cont_number'];?></td>
						<!--td><?php echo $rtnContainerList[$i]['BL_NO'];?></td-->
						<td id="rotTdId_<?php echo $i; ?>"><?php echo $rtnContainerList[$i]['import_rotation'];?></td>
						<td><?php echo $rtnContainerList[$i]['rcv_pack'];?></td>
						<td><?php echo $rtnContainerList[$i]['flt_pack'];?></td>
						<td><?php echo $rtnContainerList[$i]['loc_first'];?></td>
						<td><?php echo $rtnContainerList[$i]['shed_loc'];?></td>
						<td><?php echo $rtnContainerList[$i]['shed_yard'];?></td>
						<td><?php echo $rtnContainerList[$i]['wr_date'];?></td>
						<td>
							<form name="tallyreport" id="tallyreport" target="_blank" action="<?php echo site_url("ShedBillController/tallyReportPdf");?>" method="post">
								<input type="hidden" name="rotation" id="rotation" value="<?php echo $rtnContainerList[$i]['import_rotation'];?>">
								<input type="hidden" name="container" id="container" value="<?php echo $rtnContainerList[$i]['cont_number'];?>">
								<button type="submit" name="report" class="login_button btn btn-primary" >Report</button>
							</form>
						</td>
						<td>
							<?php 
							if($rtnContainerList[$i]['exChngStatus']==1)
							{
							?> 
							<input id="exBtn_<?php echo $i; ?>"  type="button" name="exBtn" value="Confirmed" class="login_button btn btn-primary" 
							onclick="exchangeDone(<?php echo $i; ?>)" disabled /> 
							<?php
							}
							else
							{
							?>
							<input  id="exBtn_<?php echo $i; ?>"  type="button" name="exBtn" value="Tally Confirm" class="login_button btn btn-primary" 
							onclick="exchangeDone(<?php echo $i; ?>)"/>
							<?php
							}
							?>
						</td>
					</tr>
					<?php }?>
				</table>
		 </div>
       <!-- <p class="pages"><small>Page 1 of 2</small> <span>1</span> <a href="#">2</a> <a href="#">&raquo;</a></p>-->
      </div>
      <!-- <div class="sidebar">
	   <?php include_once("mySideBar.php"); ?>
	  </div> -->
      <div class="clr"></div>
    </div>
	
  </div>
</section>