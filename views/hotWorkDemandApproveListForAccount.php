<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

	<!-- start: page -->
		<div class="row">
			<div class="col-lg-12">						
				<section class="panel">
                    <table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
                        <thead>
                            <tr class="gridDark">
                                <th class="text-center">#Sl</th>
                                <th class="text-center">Rotation</th>									
                                <th class="text-center">Vessel name</th>	
                                <th class="text-center">Start time</th>								
                                <th class="text-center">Start date</th>	
                                <th class="text-center">Status</th>										
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $tbl = "";
                                
                                for($i=0;count($result)>$i;$i++)
                                {
                                    $tbl .= "<tr>";
                                    $sl = $i+1;
                                    $tbl.="<td align='center'> {$sl} </td>";
                                    $tbl.="<td align='center'> {$result[$i]['rotation']} </td>";
                                    $tbl.="<td align='center'> {$result[$i]['vessel_name']} </td>";
                                    $tbl.="<td align='center'> {$result[$i]['start_time']} </td>";
                                    $tbl.="<td align='center'> {$result[$i]['start_date']} </td>";
                                    $tbl.="<td align='center'> Forwarded </td>";
                                    $tbl .= "</tr>";
                                }

                                echo $tbl;
                            ?>
                        </tbody>
                    </table>
				</section>
			</div>
		</div>	
	<!-- end: page -->
</section>
</div>