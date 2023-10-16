<script type="text/javascript">

function changeTextBox(v)
{
	    var search_value = document.getElementById("search_value");
		var fromdate = document.getElementById("fromdate");
		var todate = document.getElementById("todate");
		var shedNo = document.getElementById("shedNo");
		if(v=="dateRange")
		{
			search_value.value=null;
			shedNo.value=null;
			search_value.disabled=true;
			fromdate.disabled=false;
			todate.disabled=false;
			shedNo.disabled=true;
		
		}	
		else if(v=="")
		{
			search_value.value=null;
			fromdate.value=null;
			todate.value=null;
			shedNo.value=null;
			search_value.disabled=true;
			fromdate.disabled=true;
			todate.disabled=true;
			shedNo.disabled=true;

		}
		else if(v=="shedNo")
		{
			search_value.value=null;
			search_value.disabled=true;
			fromdate.disabled=false;
			todate.disabled=false;
			shedNo.disabled=false;	
		}
		else 
		{
			fromdate.value=null;
			todate.value=null;
			search_value.disabled=false;
			fromdate.disabled=true;
			todate.disabled=true;
			shedNo.disabled=true;
			
		}	
}
</script>
<style>
     #table-scroll {
	  height:500px;
	  overflow:auto;  
	  margin-top:0px;
      }
</style>
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>

	<div class="row">
		<div class="col-lg-12">						
			<section class="panel">
				<div class="panel-body">
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/CfsModule/lclAssignmentReportTablePerform'; ?>" id="myform" name="myform">
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Search By <span class="required">*</span></span>
									<select name="search_by" id="" class="form-control" onchange="changeTextBox(this.value);">
										<option value="" label="search_by" selected style="width:110px;">---Select-------</option>
										<option value="all" label="All" >All</option>
										<option value="rotation" label="Rotation" >Rotation</option>
										<option value="container" label="Container" >Container</option>
										<option value="dateRange" label="DateRange" >Date Range</option>	
										<option value="shedNo" label="ShedNo">Shed No</option>														
									</select>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Search Value <span class="required">*</span></span>
									<input type="text" name="search_value" id="search_value" class="form-control" placeholder="Search Value" disabled/>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">From Date: <span class="required">*</span></span>
									<input type="date" name="fromdate" id="fromdate" class="form-control" value="<?php date("Y-m-d"); ?>" disabled/>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">To Date: <span class="required">*</span></span>
									<input type="date" name="todate" id="todate" class="form-control" value="<?php date("Y-m-d"); ?>" disabled/>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Shed No <span class="required">*</span></span>
									<select name="shedNo" id="shedNo" class="form-control" disabled>
											<option value="" label="Shed_No" selected style="width:110px;">---Select-------</option>
											<option value="CFS/NCT" label="CFS/NCT" >CFS/NCT</option>
											<option value="CFS/CCT" label="CFS/CCT" >CFS/CCT</option>
											<option value="13 Shed" label="13 Shed" >13 Shed</option>	
											<option value="12 Shed" label="12 Shed" >12 Shed</option>												
											<option value="9 Shed" label="9 Shed" >9 Shed</option>												
											<option value="8 Shed" label="8 Shed" >8 Shed</option>												
											<option value="7 Shed" label="7 Shed" >7 Shed</option>												
											<option value="6 Shed" label="6 Shed" >6 Shed</option>	
                                            <option value="5 Shed" label="5 Shed" >5 Shed</option> 											
											<option value="4 Shed" label="4 Shed" >4 Shed</option>												
											<option value="N Shed" label="N Shed" >N Shed</option>												
											<option value="D Shed" label="D Shed" >D Shed</option>												
											<option value="P Shed" label="P Shed" >P Shed</option>
                                            <option value="F Shed" label="F Shed">F Shed</option> 	
                                            <option value="CFS/OFS" label="CFS/OFS">CFS/OFS</option> 											
										</select>
								</div>												
							</div>
																			
							<div class="row">
								<div class="col-sm-12 text-center">
									<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
									<button type="submit" name="View" class="mb-xs mt-xs mr-xs btn btn-success login_button">View</button>
									<button type="submit" name="Print" class="mb-xs mt-xs mr-xs btn btn-success login_button" formtarget="_blank">Print</button>
									<button type="submit" name="Excel" class="mb-xs mt-xs mr-xs btn btn-success login_button" formtarget="_blank">Download</button>
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

	<br/>
	<div id="table-scroll" class="panel-body table-responsive" width="100%">
	<table cellspacing="1" cellpadding="1" align="center" class="table table-bordered table-responsive table-hover table-striped mb-none">
		<tr class="gridDark" style="height:45px; bgcolor="#BDBDBD" >
			<th>SL</th>
			<th>Cont. No</th>
			<th>Size</th>
			<th>Height</th>
			<th>Rotation</th>
			<th>Vessel Name</th>
			<th><nobr>Assign Date</nobr></th>
			<th>MLO</th>
			<th>STV</th>
			<th><nobr>Cont.at Shed</nobr></th>
			<th><nobr>Cargo at Shed</nobr></th>
			<th>Desc.of Cargo</th>
            <th><nobr>Landing Date</nobr></th>
			<th>Remarks</th>
			<?php if($login_id=='admin'){?>			
			<th>Action</th>
			<?php }?>
			<th>Action</th>			
			
		</tr>
		
	  <?php
       
        for($i=0;$i<count($lclAssignmentList);$i++) { 
         ?>
         <tr class="gridLight">
          <td>
           <?php echo $i+1;?>
          </td>
         <!-- <td align="center" >
           <?php echo $lclAssignmentList[$i]['id']?>
          </td> 
		  -->
          <td align="center">
           <?php echo $lclAssignmentList[$i]['cont_number']?>
          </td>
          <td align="center">
           <?php echo $lclAssignmentList[$i]['cont_size']?>
          </td>
          <td align="center">
           <?php echo $lclAssignmentList[$i]['cont_height']?>
          </td>
          <td align="center">
           <?php echo $lclAssignmentList[$i]['Import_Rotation_No']?>
          </td> 
		  <td align="center">
           <?php echo $lclAssignmentList[$i]['Vessel_Name']?>
          </td> 		  
		 <td align="center">
           <?php echo $lclAssignmentList[$i]['assignment_date']?>
          </td>   		  
          <td align="center">
           <?php echo $lclAssignmentList[$i]['mlocode']?>
          </td>          
          <td align="center">
           <?php if( $lclAssignmentList[$i]['stv']=="SAIF POWERTEC") echo "SPL"; else echo $lclAssignmentList[$i]['stv'];?>
          </td>
          <td align="center">
           <?php echo $lclAssignmentList[$i]['cont_loc_shed']?>
          </td> 
		  <td align="center">
           <?php echo $lclAssignmentList[$i]['cargo_loc_shed']?>
          </td> 
		  <td align="center">
           <?php echo $lclAssignmentList[$i]['description_cargo']?>
          </td> 
		  <td align="center">
           <?php echo $lclAssignmentList[$i]['landing_time']?>
          </td> 
		  <td align="center">
           <?php echo $lclAssignmentList[$i]['remarks']?>
          </td> 
		  
		  <?php if($login_id=='admin'){ ?>
		  <td align="center">
			<form action="<?php echo site_url('cfsModule/lclAssignmentEdit');?>" method="POST">
				<input type="hidden" name="lclID" value="<?php echo $lclAssignmentList[$i]['id'];?>">							
				<input type="submit" value="Edit" name="start" class="login_button" style="width:100%;">							
			</form> 
        </td> 
		  <?php }?>
		<td align="center"> 
			<form action="<?php echo site_url('report/tallyEntryFormWithIgmContInfo/'.$lclAssignmentList[$i]['cont_number'].'/'.$lclAssignmentList[$i]['Import_Rotation_No'])?>" target="_blank" method="POST">						
				<button type="submit" class="btn btn-primary login_button">TallyEntry</button>							
			</form> 
        </td> 
		  
         </tr>
         <?php
        }
       ?>
	</table>
  </div>
</section>