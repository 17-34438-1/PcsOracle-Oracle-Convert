<script>
 function getEventDetails(gkey)
 {
	   //alert(gkey);
	//document.getElementById("cargoAtShed").value=shed;
		
		if (window.XMLHttpRequest) 
		{

		  xmlhttp=new XMLHttpRequest();
		} 
		else 
		{  
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=stateChangeShedInfo;
		xmlhttp.open("GET","<?php echo site_url('AjaxController/contEventDetails')?>?gkey="+gkey,false);
					
		xmlhttp.send();		
   }
   
   
   
   	function stateChangeShedInfo()
	{			
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{	
		var val = xmlhttp.responseText;
			//alert(val);
		var tbl = document.getElementById("mytbl");
		var rowslenth = tbl.getElementsByTagName("tr").length;
		var rmvroLn = rowslenth-1;
		//alert(rmvroLn);

		for(var i=rmvroLn;i>=0;i--)
		{
			tbl.deleteRow(i);
		}
		 
		var tr1 = document.createElement("tr");
		tr1.style.background="#c6d105";
		 
		var th1 = document.createElement("th");
		th1.style.textAlign = "center";
		var txt1 = document.createTextNode("ID");
		th1.appendChild(txt1);
		 
        var th2 = document.createElement("th");
		th2.style.textAlign = "center";
		var txt2 = document.createTextNode("Description");
		th2.appendChild(txt2);		
		
	/*	
		var th4 = document.createElement("th");
		var txt4 = document.createTextNode("Placed by");
		th4.appendChild(txt4);
	*/	
		var th5 = document.createElement("th");
		th5.style.textAlign = "center";		
		var txt5 = document.createTextNode("Placed Time");
		th5.appendChild(txt5);
	/*	
		var th6 = document.createElement("th");
		var txt6 = document.createTextNode("Creator");
		th6.appendChild(txt6);
	*/	
		/* var th7 = document.createElement("th");
		var txt7 = document.createTextNode("Created");
		th7.appendChild(txt7); */
		
 		var th8 = document.createElement("th");
		th8.style.textAlign = "center";		
		var txt8 = document.createTextNode("Prior Value");
		th8.appendChild(txt8); 	 

 		var th9 = document.createElement("th");
		th9.style.textAlign = "center";		
		var txt9 = document.createTextNode("New Value");
		th9.appendChild(txt9);   
		
        tr1.appendChild(th1);
		tr1.appendChild(th2);
		//tr1.appendChild(th4);
	    tr1.appendChild(th5);
	  //tr1.appendChild(th6);
	    //tr1.appendChild(th7);
	     tr1.appendChild(th8);
	    tr1.appendChild(th9); 
		
		tbl.appendChild(tr1);	 
		
		
		
        var jsonData = JSON.parse(val);
		for (var i = 0; i < jsonData.length; i++) 
		{
			//alert(jsonData[i].DESCRIPTION)
	        var tr = document.createElement("tr");
			tr.style.background="#f5e783"; 
			
			var td2 = document.createElement('td');
			td2.style.textAlign = "center";			
			var text2 = document.createTextNode(jsonData[i].ID);
			td2.appendChild(text2);
			
			var td3 = document.createElement('td');
			td3.style.textAlign = "center";		
			var text3 = document.createTextNode(jsonData[i].DESCRIPTION);
			td3.appendChild(text3);
			
			//alert((jsonData[i].CREATED);
			
		/*	
			var td4 = document.createElement('td');
			var text4 = document.createTextNode(jsonData[i].placed_by);
			td4.appendChild(text4);
		*/	
			var td5 = document.createElement('td');
			td5.style.textAlign = "center";		
			var text5 = document.createTextNode(jsonData[i].PLACED_TIME);
			td5.appendChild(text5);
				
		/*				
			var td6 = document.createElement('td');
			var text6 = document.createTextNode(jsonData[i].creator);
			td6.appendChild(text6);
		*/	
			/* var td7 = document.createElement('td');
			var text7 = document.createTextNode(jsonData[i].CREATED);
			td7.appendChild(text7);	 */
			
 			var td8 = document.createElement('td');
			td8.style.textAlign = "center";		
			var text8 = document.createTextNode(jsonData[i].PRIOR_VALUE);
			td8.appendChild(text8);
			
			var td9 = document.createElement('td');
			td9.style.textAlign = "center";		
			var text9 = document.createTextNode(jsonData[i].NEW_VALUE);
			td9.appendChild(text9);	 

		    tr.appendChild(td2);
			tr.appendChild(td3);
		  //  tr.appendChild(td4);
			tr.appendChild(td5);
			//tr.appendChild(td6);
			//tr.appendChild(td7);			
 			tr.appendChild(td8);
			tr.appendChild(td9); 

			tbl.appendChild(tr);
		}
					
		}
	}
</script>
<style>
	.contTable tr { background-color: #E1F0FF }
	.contTable tr:hover { background-color: #49e8ce };
</style>

<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

  	<div class="row">
		<div class="col-lg-12">						
			<section class="panel">
				<div class="panel-body">
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/Container_BL_BlockReleaseList'; ?>" target="" id="myform" name="myform" onsubmit="return validate()">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-12 text-center">
									
								</div>
							</div>

							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">		
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Container No <span class="required">*</span></span>
									<input type="text" name="ddl_im_cont_no" id="ddl_im_cont_no" class="form-control" placeholder="Container No">
								</div>
								<div class="input-group mb-md">
									<span class="">OR </span>
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">BL No <span class="required">*</span></span>
									<input type="text" name="ddl_im_bl_no" id="ddl_im_bl_no" class="form-control" placeholder="BL No">
								</div>												
							</div>
																			
							<div class="row">
								<div class="col-sm-12 text-center">
									<button type="submit" name="report" id="submit" class="mb-xs mt-xs mr-xs btn btn-success">Search</button>
								</div>													
							</div>
							<div class="row">
								<div class="col-sm-12 text-center">
								<?php echo @$msg; ?>
								</div>
							</div>
						</div>	
					</form>
				</div>
			</section>
			
			<?php
				if($flag == 1){
			?>

			<section class="panel">
				<div class="panel-body">
				<table class="table table-bordered table-hover">
				<h3  align="center"><?php echo $title; ?></h3>
				<h3 align="center"><span ><?php if($containerNo!="") echo "Container No: ".$containerNo; else echo "BL No: ".$blNo ?></span> </h3>
                    <table class="table table-bordered table-hover">
					
					
					<thead>
                        <tr>
                            <th>SL </th>
                            <th>Conainer No </th>
                            <th>Rotation No</th>
                            <th>BL Ref</th>
							<th>Position</th>
							<!--th>Block St</th-->
                            <th>Flag</th>
                            <th>Block Time</th>
                            <th>Release Time</th>
                            <th>History</th>
						</tr>
					</thead>
					
		
			<?php
			
			//include("mydbPConnection.php");
			include("dbConection.php");
			include("mydbPConnection.php");
			include("dbOracleConnection.php");
			
			
			// echo $rtnBlLis->last_pos_name."<hr>";
		

		?>
		<?php
		for($i=0; $i<count($rtnBlList) ; $i++) 
		{
			$val=$rtnBlList[$i]['cont_no'];
			$terminal=$rtnBlList[$i]['terminal'];
			$block=$rtnBlList[$i]['block'];
			$location=$rtnBlList[$i]['location'];

			 /* $sqlContainerPosition="SELECT inv_unit_fcy_visit.gkey,inv_unit_fcy_visit.last_pos_name
			 FROM inv_unit   
			 INNER JOIN inv_unit_fcy_visit  ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			 WHERE inv_unit.id='$val' ORDER BY inv_unit_fcy_visit.gkey DESC fetch first 1 rows only";
			$sqlConPosition=oci_parse($con_sparcsn4_oracle,$sqlContainerPosition);
			oci_execute($sqlConPosition);
			$rtnConList=oci_fetch_array($sqlConPosition);
			$yard = ""; */
			
			
			// var_dump($rtnBlLis);
			// return;

			// var_dump($rtnBlLis['last_pos_name']);
			// return;
			
			// Skip it cont_yard.. beacause it fetch from yard_lying_info table-----  Sumon -------

			/* for($j=0; $j<count($rtnConList) ; $j++) 
			{
				$last_pos_name="";
				$last_pos_name=$rtnConList[$i]['LAST_POS_NAME'];
				$yardStr="SELECT ctmsmis.cont_yard('$last_pos_name') AS Yard_No";
				$yardQuery= mysqli_query($con_sparcsn4,$yardStr);
				$yardRow = mysqli_fetch_object($yardQuery);
			    $yard = $yardRow->Yard_No;

			} */
			$st=$rtnBlList[$i]['release_flag'];
			$release_time=$rtnBlList[$i]['release_time'];
			$blockTime=$rtnBlList[$i]['blockTime'];
			$flag=$rtnBlList[$i]['release_flag'];
			$rot=$rtnBlList[$i]['rotation_no'];
			
			$sqlblockUnblock="SELECT  nbr_block_unblock_data.release_flag, 
			IF (nbr_block_unblock_log.release_flag='RELEASE', entry_time,'') AS release_time, 
			(SELECT entry_time  FROM nbr_block_unblock_log 
			WHERE nbr_block_unblock_log.block_unblock_id=nbr_block_unblock_data.id
			AND release_flag='DO_NOT_RELEASE' ORDER BY nbr_block_unblock_log.id DESC LIMIT 1) AS blockTime FROM nbr_block_unblock_data
			INNER JOIN nbr_block_unblock_cont_no ON nbr_block_unblock_cont_no.block_unblock_id=nbr_block_unblock_data.id
			INNER JOIN nbr_block_unblock_log ON nbr_block_unblock_log.block_unblock_id=nbr_block_unblock_data.id
			WHERE nbr_block_unblock_data.rotation_no='$rot' AND nbr_block_unblock_cont_no.cont_no='$val' 
			ORDER BY nbr_block_unblock_log.id DESC LIMIT 1";
			$rtnBlockUnblock = $this->bm->dataSelectDb1($sqlblockUnblock);
			for($k=0; $k<count($rtnBlockUnblock); $k++)
			{
				$flag=$rtnBlockUnblock[$k]['release_flag'];
				$st=$rtnBlockUnblock[$k]['release_flag'];
				$blockTime=$rtnBlockUnblock[$k]['blockTime'];
				$release_time=$rtnBlockUnblock[$k]['release_time'];
			}
			
			$contGkeystr=" SELECT inv_unit.GKEY            
				FROM inv_unit
				INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
				INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv
				INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
				AND vsl_vessel_visit_details.ib_vyg='$rot' AND inv_unit.id='$val'";
			$query = oci_parse($con_sparcsn4_oracle, $contGkeystr);
					oci_execute($query);	
			$cont_gkey="";	
			while(($row = oci_fetch_object($query))!= false)
			{				
					$cont_gkey=$row->GKEY;
			}					

		?>  
			<tr>
				<td><?php echo $i+1; ?></td>
				<td><?php echo $rtnBlList[$i]['cont_no']; ?></td>
				<td><?php echo $rot; ?></td>
				<td><?php echo $rtnBlList[$i]['bl_ref']; ?></td>
				
				<td><?php  echo $terminal.'-'.$block.'-'.$location; ?></td>
				<td <?php if($st=="DO_NOT_RELEASE") { ?> style="background-color:#ef8068" <?php }  else { ?> style="background-color:#83d67f" <?php } ?> ><?php echo $flag; ?></td> 

				<td><?php echo $blockTime; ?></td> 
				<td><?php echo $release_time; ?></td> 
				<td align="center"> 
					<button class="mb-xs mt-xs mr-xs btn btn-success"  style="width:100%;"  type="submit" value="<?php echo $cont_gkey;?>" onclick ="getEventDetails('<?php echo $cont_gkey;?>');">Expand</button>
				</td>

				 <?php /* 
				 if($rtnBlNo =="DO_NOT_RELEASE"){ ?>
					<td><?php echo $rtnBlNo; ?></td>
					<?php } 
				else { ?>
					<td><?php echo ""; ?></td>
				<?php } ?>

				<?php 
				 if($rtnBlNo =="DO_NOT_RELEASE"){ ?>
					<td><?php echo ""; ?></td>
					<?php } 
				else{ ?>
					<td><?php echo $rtnBlNo; ?></td>
				<?php }  */?>

				<!--td><?php echo $rtnBlList[$i]['time_stamp']; ?></td-->
			</tr>
		<?php
		}
		?>

		<?php 
		mysqli_close($con_sparcsn4);
		//mysqli_close($con_cchaportdb);
		?>
           </table>
		</table>
                </div>
            </section>
			<?php
				}
			?>
			
			</table>
	
			<table class='contTable' cellspacing="1" cellpadding="1" align="center"  id="mytbl" style="overflow-y:scroll" >		
			</table>
		   
			</div>
			  <div class="clr"></div>
			</div>

		</div>
	</div>


</section>
