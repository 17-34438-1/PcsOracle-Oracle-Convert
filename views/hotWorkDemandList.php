<script>
    function selectAll() {
	
	  var checkBox = document.getElementById("checkAll");
	  var t = document.getElementById("total").value;
	  var total=parseInt(t);
	 if ( document.getElementById("checkAll").checked == true)
	 {
		//applyBtn.style.display="block";
		for(let i=0;i<total;i++)
		{
			id=i.toString(); 
            if(document.getElementById(id).disabled == true){

                document.getElementById(id).checked = false;
            }
            else{
                document.getElementById(id).checked = true;   
            }
			

		}
	   
	  } 
	  if (document.getElementById("checkAll").checked == false)
	  {
		//applyBtn.style.display="none";
		for(let i=0;i<total;i++)
		{
			var serialNo=i.toString(); 
			document.getElementById(serialNo).checked = false;

		}
	   
	  } 
	}
</script>
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
            <form action="<?php echo site_url('Vessel/hotWorkDemandListFoward')?>" method="POST" ">					
                        <section class=" panel">
                <table class="table table-bordered table-hover table-striped mb-none" >
                    <thead>
                        <tr class="gridDark">
                            <!--th class="text-center">#Sl</th-->
                             <th class="text-center"><font size='2'>All</font>
                               <input type="hidden" name="total" id="total" value="<?php  echo $value=count($result);?>"></input>
                                <input type="checkbox" name="checkAll" id="checkAll" onclick="selectAll();"></input>
                            </th>
                            <th class="text-center">Rotation</th>
                            <th class="text-center">Vessel name</th>
                            <th class="text-center">Start time</th>
                            <th class="text-center">Start date</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Placed Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                                        $tbl = "";
                                        
                                        /* for($i=0;count($result)>$i;$i++)
                                        {
                                            $tbl .= "<tr>";
                                            $sl = $i+1;
                                            $tbl.="<td align='center'> {$sl} </td>";
                                            $tbl.="<td align='center'> {$result[$i]['voy_no']} </td>";
                                            $tbl.="<td align='center'> {$result[$i]['vessel_name']} </td>";
                                            $tbl.="<td align='center'> {$result[$i]['start_time']} </td>";
                                            $tbl.="<td align='center'> {$result[$i]['start_date']} </td>";
                                            $tbl .= "</tr>";
                                        }

                                        echo $tbl;*/

                                        for($i=0;$i<count($result);$i++){
                                        $rotation=$result[$i]['rotation'];
                                        $query="SELECT *  FROM sparcsn4.vsl_vessel_visit_details 
                                        INNER JOIN sparcsn4.srv_event ON sparcsn4.srv_event.applied_to_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
                                        WHERE ib_vyg='$rotation' AND sparcsn4.srv_event.event_type_gkey=213";
                                        $queryresult = mysqli_query($con_sparcsn4,$query);
                                        $countResult=mysqli_num_rows($queryresult);
                                        $placeTime="";
                                       
                                        if($countResult>0){
                                            $row1=mysqli_fetch_object($queryresult);
                                            $placeTime=$row1->placed_time;

                                        }
                                    ?>
                        <tr>
                            <td align="center">
                                <input type="checkbox" name="list[]" id="<?php echo $i;?>"
                                    value="<?php echo $result[$i]['id'];?>" 
                                    <?php
                                
                                if($countResult==0){
                                     echo "disabled";
                                     }
                                     ?>
                                    >
                                </input>
                            </td>
                            <td align="center"><?php echo $result[$i]['rotation'];  ?></td>
                            <td align="center"><?php echo $result[$i]['vessel_name'];  ?></td>
                            <td align="center"><?php echo $result[$i]['start_time'];  ?></td>
                            <td align="center"><?php echo $result[$i]['start_date'];  ?></td>
                            <td align="center">
                                <?php
                                if($countResult==0){
                                     echo "No";
                                     }
                                     else if($countResult>0){echo "yes";
                                     }  ?>
                            </td>
                            

                            <td align="center"><?php echo $placeTime; ?></td>

                        </tr>
                        <?php } ?>

                    </tbody>
                </table>

</section>

<div class="row" align="center">
    <button type="submit" name="apply" class="mb-xs mt-xs mr-xs btn btn-primary" <?php if(count($result)<=0){ echo "disabled";} ?> >
    Forward
   </button>
</div>
</form>
</div>
</div>
<!-- end: page -->
</section>
</div>