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
                
                <div class="panel-body">
                    <h3>Pending List</h3>
                </div>
                
                <!-- <div class="panel-body"> -->
                    <table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
                        <thead>
                            <tr>
                                <th class="text-center">Sl</th>
                                <th class="text-center">C&F</th>
                                <th class="text-center">Rotation</th>
                                <th class="text-center">BL</th>
                                <th class="text-center">Action</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                for($i=0;$i<count($rslt_pending);$i++) {
                            ?>
                                <tr class="gradeX">
                                    <td align="center"> <?php echo $i+1;?> </td>
                                    <td align="center"> <?php echo $rslt_pending[$i]['u_name']; ?> </td>
                                    <td align="center"> <?php echo $rslt_pending[$i]['rotation']; ?> </td>
                                    <td align="center"> <?php echo $rslt_pending[$i]['bl_no'] ?> </td>
                                    <td align="center">
                                        <form action="<?php echo site_url('Report/equipDemandApprove');?>" method="POST">
                                            <input type="hidden" name="id" id="id" value="<?php echo $rslt_pending[$i]['id']; ?>">				
                                            <input type="submit" value="Approve"  class="mb-xs mt-xs mr-xs btn btn-primary text-center">	
                                        </form>
                                    </td>

                                    
                                    <?php
                                        }
                                    ?>
                                </tr>
                        </tbody>
                    </table>
                <!-- </div> -->
                
                <div class="panel-body">
                    <h3>Approved List</h3>
                </div>

                <!-- <div class="panel-body"> -->
                    <table class="table table-bordered table-hover table-striped mb-none" id="datatable-default">
                        <thead>
                            <tr>
                                <th class="text-center">Sl</th>
                                <th class="text-center">C&F</th>
                                <th class="text-center">Rotation</th>
                                <th class="text-center">BL</th>
                                <th class="text-center">Approved By</th>
                                <th class="text-center">Approved At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $count = 0;
                                for($i=0;$i<count($rslt_appoved);$i++) {
                            ?>
                                <tr class="gradeX">
                                    <td align="center"> <?php echo $i+1;?> </td>
                                    <td align="center"> <?php echo $rslt_appoved[$i]['u_name']; ?> </td>
                                    <td align="center"> <?php echo $rslt_appoved[$i]['rotation']; ?> </td>
                                    <td align="center"> <?php echo $rslt_appoved[$i]['bl_no'] ?> </td>
                                    <td align="center"> <?php echo $rslt_appoved[$i]['aprv_by'] ?> </td>
                                    <td align="center"> <?php echo $rslt_appoved[$i]['aprv_at'] ?> </td>
                                </tr>
                            <?php
                                $count++;
                                }
                            ?>
                        </tbody>
                    </table>

                    <div class="col-md-6">
                        <form action="<?php echo site_url('Report/equipDemandForward');?>" method="POST" class="text-right">			
                            <input type="submit" value="Forward"  class="mb-xs mt-xs mr-xs btn btn-primary" <?php if( $count == 0){ echo "disabled";}?>>	
                        </form>
                    </div>
                    
                    <div class="col-md-6">
                        <form action="<?php echo site_url('Report/equipList');?>" method="POST" class="text-left">			
                            <input type="submit" value="View Details"  target="_blank" class="mb-xs mt-xs mr-xs btn btn-primary" <?php if( $count == 0){ echo "disabled";}?>>	
                        </form>
                    </div>

                <!-- </div> -->
            </section>
            
            
            
            
        <!-- end: page -->
    </section>
</div>








