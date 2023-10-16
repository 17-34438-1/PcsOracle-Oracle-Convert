
<?php include("mydbPConnectionn4.php");?>
<section role="main" class="content-body">
    <header class="page-header">
        <h2><?php echo $title;?></h2>
    </header>

    <!-- start: page -->
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-sm-12 text-center">
                    <?php echo $msg;?>
                </div>
            </div>
       			
            <section class=" panel">
                <table class="table table-bordered table-hover table-striped mb-none" id="datatable-default" >
                    <thead>
                        <tr class="gridDark">
                            <!--th class="text-center">#Sl</th-->
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
                                    
                         for($i=0;$i<count($result);$i++){
                                        
                        ?>
                        <tr>
                        <td align="center"><?php echo $i+1;  ?></td>
                            <td align="center"><?php echo $result[$i]['rotation'];  ?></td>
                            <td align="center"><?php echo $result[$i]['vessel_name'];  ?></td>
                            <td align="center"><?php echo $result[$i]['start_time'];  ?></td>
                            <td align="center"><?php echo $result[$i]['start_date'];  ?></td>
                            <td align="center">
                                <?php
                                if($result[$i]['fire_dpt_aprv_st']==0){
                                     echo "Not Approved";
                                     }
                                     else if($result[$i]['fire_dpt_aprv_st']==1){
                                        echo "Approved";
                                     }  ?>
                            </td>
                            

                          

                        </tr>
                        <?php } ?>

                    </tbody>
                </table>

</section>


</div>
</div>
<!-- end: page -->
</section>
</div>