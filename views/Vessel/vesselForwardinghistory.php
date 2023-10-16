<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

	<!-- start: page -->
		<div class="row">
			<div class="col-lg-12">						
				<section class="panel">
					<div class="panel-body">
						<table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
                            <thead>
                                <tr>
                                    <th>#SL</th>
                                    <th>Rotation</th>
                                    <th>Vessel Name</th>
                                    <th>Vessel Type</th>
                                    <th>Forwarded To</th>
                                    <th>Forwarded By</th>
                                    <th>Forwarded Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    //var_dump($billHistory);
                                    for($i=0;$i<count($billHistory);$i++)
                                    {
                                           $basic_class="";
                                            $notes="";
                                            $basic_class = $billHistory[$i]['basic_class'];
                                            $notes = $billHistory[$i]['notes'];
                                            $vsl_class="";
                                            if($basic_class=='CELL'){
                                                $vsl_class="CONTAINER";	
                                            }
                                        
                                            else if($basic_class=='BBULK'){
                                                $vsl_class="BREAK BULK";
                                            }
                                            else if($basic_class=='PSNGR'){
                                                $vsl_class="PESSENGER";
                                            }
                                            else if($basic_class=='UNKNOWN'){

                                                if( strpos( $notes, 'LPG' ) !== false) {
                                                    $vsl_class="LPG";
                                                }
                                                else{
                                                    if( strpos( $notes, 'KUTUBDIA' ) !== false) {
                                                        $vsl_class="KUTUBDIA";
                                                    }
                                                    else if(strpos( $notes, 'DEMOLITION' ) !== false){
                                                        $vsl_class="BEACHING";

                                                    }
                                                    else{
                                                        $vsl_class=$basic_class;
                                                    }
                                                }
                                                
                                            }
                                            else if($basic_class=='VESSEL CANCELATION'){
                                                $vsl_class="VESSEL CANCELATION";

                                            }
                                            else{
                                                $vsl_class=$basic_class;
                                            }
                                ?>
                                <tr>
                                    <td class="text-center"><?= $i+1; ?></td>
                                    <td><?= $billHistory[$i]['rotation']; ?></td>
                                    <td><?= $billHistory[$i]['vsl_name']; ?></td>
                                    <td><?= $vsl_class; ?></td>
                                    <td>
                                        <?php 
                                            $login_id = $billHistory[$i]['forwarded_to'];
                                            $userNameQuery = "SELECT u_name as rtnValue FROM users WHERE login_id = '$login_id'";
                                            echo $this->bm->dataReturnDB1($userNameQuery);
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                            $login_id = $billHistory[$i]['forwarded_by'];
                                            $userNameQuery = "SELECT u_name as rtnValue FROM users WHERE login_id = '$login_id'";
                                            echo $this->bm->dataReturnDB1($userNameQuery);
                                        ?>
                                    </td>
                                    <td><?= $billHistory[$i]['forwarded_at']; ?></td>
                                </tr>
                                <?php
                                    }
                                ?>
                            </tbody>
                        </table>
					</div>
				</section>
			</div>
		</div>	
	<!-- end: page -->
</section>
</div>

<?php
    
        
?>