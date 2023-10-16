<style>
	 #table-scroll {
	  height:500px;
	  width: 1000px;
	 <!-- overflow:auto;  -->
	  margin-top:0px;
      }

</style>

<section role="main" class="content-body">
    <header class="page-header">
        <h2><?php echo $title;?></h2>
    </header>
	
<div class="row">
	<div class="col-md-12">					
		<div class="panel-body">
			<form class="form-horizontal form-bordered" method="POST" 
				action="<?php echo site_url("Report/auctionContainers") ?>" onsubmit="return validate();">
				<div class="form-group">
					<input type="hidden" name="flag" id="flag" value="1" class="form-control login_input_text">
					<div class="col-md-6 col-md-offset-3">		
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Rotation No <span class="required">*</span></span>
							<input type="text" name="rot_no" id="rot_no" class="form-control login_input_text" autofocus= "autofocus" placeholder="Rotation No" required>
						</div>												
					</div>									
					<div class="row" id="applyBtn">
						<div class="col-sm-12 text-center">
							<button type="submit" name="btnApply" class="mb-xs mt-xs mr-xs btn btn-primary">
								Search
							</button>
						</div>													
					</div>
					<div class="row">
						<div class="col-sm-12 text-center">
							<?php echo $msg; ?>
						</div>													
					</div>						
				</div>
			</form>
		</div>
	</div>
</div>
</section>

<section role="main" class="content-body">
<div class="content">
    <div class="content_resize">
      <div class="mainbar">
        <div class="article">

		<div class="img">
		 	 <!--<div id="login_container">-->
			 
<div class="panel-body table-responsive">
	<table class="table table-bordered table-responsive table-hover table-striped mb-none" id="datatable-default">
	<thead>
		<tr class="gridDark" align="center">	
			<th>SL</th>
			<th>Rotation</th>
			<th><nobr>Vessel Name</nobr></th>
			<th><nobr>Common Landing Time</nobr></th>
			<th>Action</th>
			<th>Action</th>
		</tr>
	</thead>

	  <?php
	include("dbConection.php");
	//echo $ddl_imp_rot_no;
	$query_str="";
	$condition="";
	if($listType=="all")
	{
		$condition="";
	}
	else
	{
		$condition="AND ib_vyg='$rotation_no'";
	}
	 include("dbOracleConnection.php");
	
		$query_str="SELECT vvd_gkey,ib_vyg,vsl_vessels.name,vsl_vessel_visit_details.flex_date08 AS lading_dt,
		to_char(vsl_vessel_visit_details.flex_date08 ,'YYYY-MM-DD') AS cl_dt,extract(day from CURRENT_DATE-vsl_vessel_visit_details.flex_date08)AS diff
		FROM vsl_vessel_visit_details
		INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
		WHERE cast(vsl_vessel_visit_details.flex_date08 as date)< cast(CURRENT_DATE as date)-30
		ORDER BY flex_date08 DESC fetch first 1000 rows only";
	
	
      
	   $query= oci_parse($con_sparcsn4_oracle, $query_str);
	   oci_execute($query);
 
	$i=0;
	
	while(($row=oci_fetch_object($query))!=false){
	$i++;
		?>
		
	     <tr>
		  <td  align="center">
           <?php echo $i;?>
          </td>
          <td align="center">
				<?php if($row->IB_VYG) echo $row->IB_VYG; else echo "&nbsp;";?>
          </td>
          <td align="center">
				<?php if($row->NAME) echo $row->NAME; else echo "&nbsp;";?>
          </td> 
		   <td align="center" style="width:200px">
				<?php if($row->LADING_DT) echo $row->LADING_DT; else echo "&nbsp;";?>  
			</td> 	  
		  <td align="center">
           <form action="<?php echo site_url('Report/auctionHandOverView');?>" method="POST" target="_blank">
				<input type="hidden" id="rotation" name="rotation" value="<?php echo $row->IB_VYG;?>">							
				<input type="hidden" id="landingDt" name="landingDt" value="<?php echo $row->CL_DT;?>">							
				<input type="hidden" id="diff" name="diff" value="<?php echo $row->DIFF;?>">							
				<input type="hidden" id="printValue" name="printValue" value="0">							
               <button type="submit" value="View" name="start" class="btn btn-primary login_button";>View</button>
			</form> 
          </td>
		  <td align="center">
           <form action="<?php echo site_url('Report/auctionHandOverView');?>" method="POST" target="_blank">
				<input type="hidden" id="rotation" name="rotation" value="<?php echo $row->IB_VYG;?>">	
				<input type="hidden" id="landingDt" name="landingDt" value="<?php echo $row->CL_DT;?>">							
				<input type="hidden" id="diff" name="diff" value="<?php echo $row->DIFF;?>">
				<input type="hidden" id="printValue" name="printValue" value="1">											
               <button type="submit" value="Print" name="start" class="btn btn-primary login_button" >Print</button>
			</form> 
          </td> 
		  
         </tr>
         <?php
        }
       ?>
	</table>
   
    </div> 
        </div>

	   <?php 
            mysqli_close($con_sparcsn4);
			oci_close($con_sparcsn4_oracle);
       ?>
      <div class="clr"></div>
    </div>
  </div>
</section>
