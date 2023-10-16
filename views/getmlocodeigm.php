
<select name="MLOCODE" maxlength="50" class="form-control" onFocus="gsLabelObj(lbl_org_id,'#61BCEF','white')" onBlur="gsLabelObj(lbl_org_id,'','')">
        
				<?php
				include_once("mydbPConnection.php");
				$imp_rotation=$_GET['imp_rotation'];
				$str="select distinct mlocode from igm_details where Import_Rotation_No='$imp_rotation'";
				//print($str);
				$resultcombo6 = mysqli_query($con_cchaportdb,$str);
				?>
				
				<?php
				while ($rowcombo6 = mysqli_fetch_object($resultcombo6)){
				?>
                <option value="<?php print ($rowcombo6->mlocode);?>"><?php print($rowcombo6->mlocode);?></option>
				
				<?php	
			    
				}	
               
				?>
				 <option value="All">All</option>
	<?php mysqli_close($con_cchaportdb);?>			
</select>
