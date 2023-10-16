		
			<!-- start: page -->
			<section class="panel">
				<div class="panel-body">
					<div class="invoice">
						<header class="clearfix">
							<div class="row">
								<div class="col-sm-12 text-center mt-md mb-md">
									<div class="ib">
										<img src="<?php echo ASSETS_WEB_PATH;?>fimg/cpaLogo.png" alt="Chattogram Port Authority Logo"/><br>
										<h4 class="h4 mt-none mb-sm text-dark text-bold">Chattogram Port Authority</h4>
										<h5 align="center" class="h5 mt-none mb-sm text-dark text-bold">Port Code List</h5>
									</div>
								</div>
							</div>
						</header>
						<div class="panel-body">
							<table class="table table-bordered table-responsive table-hover table-striped mb-none" id="datatable-default">
								<thead>
									<tr class="gridDark">
										<th class="text-center">SlNo</th>
										<th class="text-center">PORT FULL NAME</th>									
										<th class="text-center">CODE</th>									
										<th class="text-center">NATION</th>									
										<th class="text-center">PORT CODE</th>									
									</tr>
								</thead>
								<tbody>
									<?php 
										include('dbOracleConnection.php');
										$i=0;
										$mlo="";
										$querystr="select * from
										(
										select
										(select id from ref_unloc_code where gkey=tbl.unloc_gkey) id,
										(select place_name from ref_unloc_code where gkey=tbl.unloc_gkey) name,
										(select cntry_code from ref_unloc_code where gkey=tbl.unloc_gkey) country_code,
										(select place_code from ref_unloc_code where gkey=tbl.unloc_gkey) port_code
										from 
										(
										select distinct unloc_gkey from ref_routing_point
										) tbl 
										) final
										WHERE REGEXP_LIKE(id, '[A-Za-z]')";
										$sqlRslt=oci_parse($con_sparcsn4_oracle,$querystr);
										oci_execute($sqlRslt,OCI_DEFAULT);
										while (($row = oci_fetch_object($sqlRslt)) != false){
										$i++;
									?>
									<tr class="gradeX">
										<td align="center"> <?php  echo $i;?> </td>
										<td align="center"> <?php if($row->NAME) echo $row->NAME; else echo "&nbsp;";?> </td>
										<td align="center"> <?php if($row->ID) echo $row->ID; else echo "&nbsp;";?> </td>
										<td align="center"> <?php if($row->COUNTRY_CODE) echo $row->COUNTRY_CODE; else echo "&nbsp;";?> </td>
										<td align="center"> <?php if($row->PORT_CODE) echo $row->PORT_CODE; else echo "&nbsp;";?> </td>
									</tr>
									<?php 
										} 
										oci_close($con_sparcsn4_oracle);
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</section>
			<!-- end: page -->
	</div>

