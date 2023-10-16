<select name="ddl_mlo" maxlength="50" class="form-control" onFocus="gsLabelObj(lbl_org_id,'#61BCEF','white')" onBlur="gsLabelObj(lbl_org_id,'','')">
        
				<?php
				include_once("mydbPConnection.php");
		        //print("select * from tbl_org_types");
				//$year=$_GET['year'];
				$rot=$_GET['rot'];
				$man=$_GET['man'];
				$agent=$_GET['agent'];
				
				//if($agent=='2619')
				//$agent='2619 or Submitee_Org_Id=153';

				$str="select DISTINCT mlocode from igm_details 
					where Import_Rotation_No='$rot' 
					and type_of_igm='$man' and Submitee_Org_Id=$agent
						";
						//print($str);
				$resultcombo6 = mysqli_query($con_cchaportdb,$str);
				?>
				<option value="All">All</option>
				<?php
				while ($rowcombo6 = mysqli_fetch_object($resultcombo6)){
				?>
                <option value="<?php print ($rowcombo6->mlocode);?>"><?php print($rowcombo6->mlocode);?></option>
				
				<?php			    
				}	
                ?>
				<?php mysqli_close($con_cchaportdb);?>
		</select>