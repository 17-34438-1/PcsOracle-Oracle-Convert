<script>
    function validate()
    {
        if (confirm("Are you sure!! Delete this record?") == true) {
		   return true ;
		} 
		else 
		{
			return false;
        }		 
    }
</script>

    <section role="main" class="content-body">
        <header class="page-header">
            <h2><?php echo $title;?></h2>
        </header>
                <div class="panel">
                    <?php
                        if(!is_null($this->session->flashdata('success'))){
                            echo $this->session->flashdata('success');
                        }

                        if(!is_null($this->session->flashdata('error'))){
                            echo $this->session->flashdata('error');
                        }
                    ?>
                </div>
                
                <!-- <div class="panel-body"> -->
                    <table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
                        <thead>
                            <tr>
                                <th class="text-center">Sl</th>
                                <th class="text-center">Demand Date</th>
                                <th class="text-center">Forwarded at</th>
                                <th class="text-center">Forwarded by</th>
                                <th class="text-center">Details</th>
                                <th class="text-center">Summary</th>
                                <th class="text-center">Forward</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $onestop_forward_st = 0;
                                $equip_sec_forward_st = 0;
                                $to_forward_st = 0;

                                $forward_at = null;
                                $forward_by = null;

                                for($i=0;$i<count($rslt_onestop);$i++) 
                                {
                                    $onestop_forward_st = $rslt_onestop[$i]['onestop_forward_st'];
                                    $equip_sec_forward_st = $rslt_onestop[$i]['equip_sec_forward_st'];
                                    $to_forward_st = $rslt_onestop[$i]['to_forward_st'];
                                    
                                    if($section == 20)
                                    {
                                        $forward_at = $rslt_onestop[$i]['onestop_forward_at'];
                                        $forward_by = $rslt_onestop[$i]['onestop_forward_by'];
                                    }
                                    elseif($section == 21)
                                    {
                                        $forward_at = $rslt_onestop[$i]['equip_sec_forward_at'];
                                        $forward_by = $rslt_onestop[$i]['equip_sec_forward_by'];
                                    }

                            ?>
                                <tr class="gradeX">
                                    <td align="center"> <?php echo $i+1;?> </td>
                                    <td align="center"> <?php echo $rslt_onestop[$i]['demand_dt'];?></td>
                                    <td align="center"> <?php echo $forward_at;?></td>
                                    <td align="center"> <?php echo $forward_by;?></td>
                                    <td align="center"> 
                                        <form action="<?php echo site_url('Report/equipList');?>" method="POST" class="text-left">			
                                            <input type="submit" value="View Details" target="_blank" class="mb-xs mt-xs mr-xs btn btn-primary">	
                                        </form>
                                    </td>
                                    <td align="center">
                                        <form action="<?php echo site_url('Report/equipDemandSummary');?>" method="POST" class="text-left">			
                                            <input type="submit" value="Summary" target="_blank" class="mb-xs mt-xs mr-xs btn btn-primary">	
                                        </form>
                                    </td>
                                    <td align="center">
                                        <?php
                                            if($section == 20 && $onestop_forward_st == 1 & $equip_sec_forward_st == 0){
                                        ?>
                                        <form action="<?php echo site_url('Report/equipDemandForward');?>" method="POST">
                                            <input type="hidden" name="id" id="id" value="<?php echo $rslt_onestop[$i]['id']; ?>">				
                                            <input type="submit" value="Forward"  class="mb-xs mt-xs mr-xs btn btn-primary text-center">	
                                        </form>
                                        <?php
                                            }else if($section == 21 & $equip_sec_forward_st == 1 && $to_forward_st == 0){
                                        ?>
                                        <form action="<?php echo site_url('Report/equipDemandForward');?>" method="POST">
                                            <input type="hidden" name="id" id="id" value="<?php echo $rslt_onestop[$i]['id']; ?>">				
                                            <input type="submit" value="Forward"  class="mb-xs mt-xs mr-xs btn btn-primary text-center">	
                                        </form>
                                        <?php
                                            }else{
                                        ?>
                                            <input type="submit" value="Forward"  class="mb-xs mt-xs mr-xs btn btn-primary text-center" disabled>
                                        <?php
                                            }
                                        ?>
                                    </td>

                                    
                                    <?php
                                        }
                                    ?>
                                </tr>
                        </tbody>
                    </table>
                <!-- </div> -->
            </section>
            
            
            
            
        <!-- end: page -->
    </section>
</div>








