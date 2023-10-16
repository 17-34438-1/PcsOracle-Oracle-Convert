
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Routing Point</h2>
	
		<div class="right-wrapper pull-right">
		
		</div>
	</header>

	<!-- start: page -->
		<section class="panel">
			<!--header class="panel-heading">
				<h2 class="panel-title" align="right">
					<a href="<?php echo site_url('POSController/LiftingEntryForm') ?>">
						<button style="margin-left: 35%" class="btn btn-primary btn-sm">
							<i class="fa fa-plus"></i>
						</button>
					</a>									
				</h2>								
			</header-->
			<div class="panel-body">
				<div class="row">
					<div class="col-sm-12 text-center mt-md mb-md">
						<div class="ib">
							<img src="<?php echo ASSETS_WEB_PATH;?>fimg/cpaLogo.png" alt="Chattogram Port Authority Logo"/><br>
							<h4 class="h4 mt-none mb-sm text-dark text-bold">Chattogram Port Authority</h4>
						</div>
					</div>
				</div>
				<table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
					<thead>
						<tr>
							<th class="text-center">SlNo.</th>
							<th class="text-center">ID</th>
							<th class="text-center">UnLoc.</th>
							<th class="text-center">Actual POD.</th>
							<th class="text-center">UnLoc Place Name.</th>
							<th class="text-center">UnLoc Country Name.</th>	
						</tr>
					</thead>
					<tbody>
						<?php 
						
						include("dbOracleConnection.php");
					
						
							//include('FrontEnd/dbConection.php');
						$i=0;
						$mlo="";
						
						$querystr="select ref_routing_point.id,ref_unloc_code.id as lok,ref_unloc_code.place_code,ref_unloc_code.place_name,
						(select ref_country.cntry_name from ref_country where ref_country.cntry_code=ref_unloc_code.cntry_code) as cntname
						from ref_routing_point 
						inner join ref_unloc_code on ref_routing_point.unloc_gkey=ref_unloc_code.gkey
						order by 1";
						$query = oci_parse($con_sparcsn4_oracle, $querystr);
						oci_execute($query,OCI_DEFAULT);
						while (($row = oci_fetch_array($query, OCI_BOTH)) != false) {
							$i++;
						?>
						<tr class="gradeX">
							<td align="center"><?php  echo $i;?></td>
							<td align="center">
								<?php if($row[ID]) echo $row[ID]; else echo "&nbsp;";?>
							</td>
							<td align="center">
						  <?php if($row[LOK]) echo $row[LOK]; else echo "&nbsp;";?>
							</td>
							<td align="center">
							<?php if($row[PLACE_CODE]) echo $row[PLACE_CODE]; else echo "&nbsp;";?>
							
							</td>
							<td align="center">
							<?php if($row[PLACE_NAME]) echo $row[PLACE_NAME]; else echo "&nbsp;";?>
								
							</td>
							<td align="center">
							<?php if($row[CNTNAME]) echo $row[CNTNAME]; else echo "&nbsp;";?>
								
							</td> 
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</section>
		
		
		
		
	<!-- end: page -->
</section>
</div>