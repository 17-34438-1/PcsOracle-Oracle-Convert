
				<section role="main" class="content-body">
					<header class="page-header">
						<h2>Commodity Information</h2>
					
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
											<h5 align="center" class="h4 mt-none mb-sm text-dark text-bold">
											<?php echo $UserName ;?>
										</h5>
										</div>
									</div>
								</div>
								<table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
									<thead>
										<tr>
											<th class="text-center">CODE</th>
											<th class="text-center">NAME</th>	
										</tr>
									</thead>
									<tbody>
										<?php 
											include('FrontEnd/dbConection.php');
											$i=0;
											$mlo="";
											$querystr="SELECT commudity_code, commudity_desc FROM ctmsmis.commudity_detail";
											$query=mysqli_query($con_sparcsn4,$querystr);
											while($row=mysqli_fetch_object($query)){
											$i++;
										?>
										<tr class="gradeX">
											<td align="center">
                                            <?php if($row->commudity_code) echo $row->commudity_code; else echo "&nbsp;";?>
											</td>
											<td align="center">
                                            <?php if($row->commudity_desc) echo $row->commudity_desc; else echo "&nbsp;";?>
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