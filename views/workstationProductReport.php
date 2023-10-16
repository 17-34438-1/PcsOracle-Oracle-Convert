
	
				<section role="main" class="content-body">
					<header class="page-header">
						<h2><?php echo $title;?></h2>
					
						<div class="right-wrapper pull-right">
							
						</div>
					</header>

					<!-- start: page -->
						<div class="row">
							<div class="col-lg-12">						
								<section class="panel">
								
									
									
						<div class="panel-body" align="center">
					<form action="<?php echo site_url('Report/workstationReportPerform');?>" method="POST" target="_blank" >

						<table>		
							
							
							<tr>
								<td>
									<div class="input-group mb-md">
										<span class="input-group-addon span_width" style="width:170px;">Search By :   <span class="required">*</span></span>
											<select name="search_by" id="search_by">
												<option value="ALL" label="All" selected style="width:110px;">ALL</option>
												<option value="serial" label="Serial No">Serial No</option>
												<option value="ip_addr" label="IP Address">IP Address</option>
											 </select>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="input-group mb-md">
										<span class="input-group-addon span_width" style="width:170px;">Search Value:   <span class="required">*</span></span>
										<input type="text" style="width:170px" id="searchInput" name="searchInput" autofocus />

									</div>
								</td>
							</tr>
												
							<tr>
								<td colspan="3" align="center" >
								<div class="row">
									<div class="col-sm-12 text-center">
									<input type="submit" value="View PDF" name="View " class="mb-xs mt-xs mr-xs btn btn-success">
									<!--button type="submit" class="mb-xs mt-xs mr-xs btn btn-success">Save</button-->
									</div>													
									</div>     
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
							</tr>
						</table>
						
						   
					</form>			
		
					</div>
				</section>
		
			</div>
		</div>	
	<!-- end: page -->
	</section>
</div>