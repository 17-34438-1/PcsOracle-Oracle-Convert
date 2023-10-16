<?php
$_SESSION['Control_Panel']=$this->session->userdata('Control_Panel');
?>
<section role="main" class="content-body">
			<header class="page-header">
				<h2><?php echo $title; ?></h2>
			</header>

			<!-- start: page -->
			<div class="row">
				
					<section class="panel">
                        <div class="panel-body">
							<div class="row">
								<div class="col-sm-12 text-center mt-md mb-md">
									<div class="ib">
										<img src="<?php echo ASSETS_WEB_PATH;?>fimg/cpaLogo.png" alt="Chattogram Port Authority Logo"/><br>
										<h4 class="h4 mt-none mb-sm text-dark text-bold">Chattogram Port Authority</h4>
									</div>
								</div>
							</div>
						</div>
						<div class="panel-body">
							
									<table class="table table-responsive table-bordered mb-none" id="datatable-default">
										<thead>
											<tr class="gridDark">
												<th class="text-center">Sl No</th>
												<th class="text-center">User</th>									
												<th class="text-center">Host</th>									
												<th class="text-center">DB</th>									
												<th class="text-center">Command</th>									
												<th class="text-center">Time</th>									
												<th class="text-center">State</th>
											</tr>
										</thead>
										<tbody>
											<?php 
												//$j=$start;
												for($i=0;$i<count($processList);$i++) {
													//$j++;
											?>
                                            <tr <?php if($processList[$i]['TIME']>2000) { ?> bgcolor="red" <?php } else if($processList[$i]['TIME']>1000 and $processList[$i]['TIME']<2000){ ?>bgcolor="orange" <?php }else { ?> bgcolor="green" <?php }?> style="color:white;">
												<td align="center"> <?php  echo $i+1; ?> </td>
												<td align="center"> <?php if($processList[$i]['USER']) echo $processList[$i]['USER']; else echo "&nsp;"; ?> </td>
												<td align="center"> <?php if($processList[$i]['HOST']) echo $processList[$i]['HOST']; else echo "&nbsp;"; ?> </td>
												<td align="center"> <?php if($processList[$i]['DB']) echo $processList[$i]['DB']; else echo "&nbsp;"; ?> </td>
												<td align="center"> <?php if($processList[$i]['COMMAND']) echo $processList[$i]['COMMAND']; else echo "&nbsp;"; ?> </td>
												<td align="center"> <?php if($processList[$i]['TIME']) echo $processList[$i]['TIME']; else echo "&nbsp;"; ?> </td>
												<td align="center"> <?php if($processList[$i]['STATE']) echo $processList[$i]['STATE']; else echo "&nbsp;"; ?> </td>
											</tr>
											<?php } ?>
                                        </tbody>
									</table>
						</div>
					</section>

				
			</div>
			<!-- end: page -->
		</section>