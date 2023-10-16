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
			alert(val);
		var tbl = document.getElementById("mytbl");
		var rowslenth = tbl.getElementsByTagName("tr").length;
		var rmvroLn = rowslenth-1;
		alert(rmvroLn);

		for(var i=rmvroLn;i>=0;i--)
		{
			tbl.deleteRow(i);
		}
		 
		var tr1 = document.createElement("tr");
		tr1.style.background="#c6d105";
		 
		var th1 = document.createElement("th");
		var txt1 = document.createTextNode("ID");
		th1.appendChild(txt1);
		 
        var th2 = document.createElement("th");
		var txt2 = document.createTextNode("Description");
		th2.appendChild(txt2);		
		
	/*	
		var th4 = document.createElement("th");
		var txt4 = document.createTextNode("Placed by");
		th4.appendChild(txt4);
	*/	
		var th5 = document.createElement("th");
		var txt5 = document.createTextNode("Placed Time");
		th5.appendChild(txt5);
	/*	
		var th6 = document.createElement("th");
		var txt6 = document.createTextNode("Creator");
		th6.appendChild(txt6);
	*/	
		var th7 = document.createElement("th");
		var txt7 = document.createTextNode("Created");
		th7.appendChild(txt7);
		
        tr1.appendChild(th1);
		tr1.appendChild(th2);
	 //	tr1.appendChild(th4);
	    tr1.appendChild(th5);
	  //  tr1.appendChild(th6);
	    tr1.appendChild(th7);
		
		tbl.appendChild(tr1);	 
		
		
		
        var jsonData = JSON.parse(val);
		for (var i = 0; i < jsonData.length; i++) 
		{
			//alert(jsonData[i].DESCRIPTION)
	        var tr = document.createElement("tr");
			tr.style.background="#f5e783"; 
			
			var td2 = document.createElement('td');
			var text2 = document.createTextNode(jsonData[i].ID);
			td2.appendChild(text2);
			
			var td3 = document.createElement('td');
			var text3 = document.createTextNode(jsonData[i].DESCRIPTION);
			td3.appendChild(text3);
			
			//alert((jsonData[i].CREATED);
			
		/*	
			var td4 = document.createElement('td');
			var text4 = document.createTextNode(jsonData[i].placed_by);
			td4.appendChild(text4);
		*/	
			var td5 = document.createElement('td');
			var text5 = document.createTextNode(jsonData[i].PLACED_TIME);
			td5.appendChild(text5);
				
		/*				
			var td6 = document.createElement('td');
			var text6 = document.createTextNode(jsonData[i].creator);
			td6.appendChild(text6);
		*/	
			var td7 = document.createElement('td');
			var text7 = document.createTextNode(jsonData[i].CREATED);
			td7.appendChild(text7);	
			
	

		    tr.appendChild(td2);
			tr.appendChild(td3);
		  //  tr.appendChild(td4);
			tr.appendChild(td5);
			//tr.appendChild(td6);
			tr.appendChild(td7);			
			
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
		<h2><?php echo $title; ?></h2>
	
		<div class="right-wrapper pull-right">
		
		</div>
	</header>

	<!-- start: page -->
		<section class="panel">
			<header class="panel-heading">
				<!--h2 class="panel-title" align="right">
					<a href="<?php echo site_url('misReport/mis_equipment_indent_list') ?>">
						<button style="margin-left: 35%" class="btn btn-primary btn-sm">
							<i class="fa fa-list"></i> GO TO INDENT LIST
						</button>
					</a>									
				</h2-->
			</header>
			<div class="panel-body">
				<form class="form-horizontal form-bordered" method="POST" name= "myForm"
					action="<?php echo site_url('report/containerEventHistoryReport') ?>">
					<div class="form-group">
						
						<div class="col-md-offset-3 col-md-6">
							<div class="input-group mb-md">
								<span class="input-group-addon span_width">Container No:</span>
								<input type="text" name="contNo" id="contNo" class="form-control" value="">
							</div>
						</div>
						
						<div class="col-sm-12 text-center">
							<!--button class="mb-xs mt-xs mr-xs btn btn-success" type="submit">Search</button-->
							<input type="submit" value="Search" class="mb-xs mt-xs mr-xs btn btn-primary"/>
						</div>													
					</div>
					<div class="form-group">
					</div>
				</form>
				<?php if($tableFlag==1){ ?>
				<table class="table table-bordered table-responsive table-hover table-striped mb-none" id="datatable-default">
					<thead>
						<tr>
							<th class="text-center" colspan="9"><?php echo $tableTitle; ?></th>
						</tr>
						<tr>
							<th class="text-center">Time Move</th>
							<th class="text-center">Time In</th>
							<th class="text-center">Time Out</th>
							<th class="text-center">Category</th>	
							<th class="text-center">Status</th>	
							<th class="text-center">MLO</th>
							<th class="text-center">Transit.State</th>
							<th class="text-center">Last Pos Name</th>
							<th class="text-center">Expand</th>
						</tr>
					</thead>
					<tbody>
						<?php for($i=0;$i<count($contHistory);$i++) { ?>
						<!--tr class="gradeX">
							<td align="center"> <?php echo $contHistory[$i]['time_move']?> </td>
							<td align="center"> <?php echo $contHistory[$i]['time_in']?> </td>
							<td align="center"> <?php echo $contHistory[$i]['time_out']?> </td>
							<td align="center"> <?php echo $contHistory[$i]['category']?> </td>
							<td align="center"> <?php echo $contHistory[$i]['freight_kind']?> </td>
							<td align="center"> <?php echo $contHistory[$i]['mlo']?> </td>
							<td align="center"> <?php echo $contHistory[$i]['transit_state']?> </td>
							<td align="center"> <?php echo $contHistory[$i]['last_pos_name']?> </td>
							<td align="center"> 
								<input type="hidden" name="contNo" id="contNo" value="<?php echo $contNo;?>">
								<button class="mb-xs mt-xs mr-xs btn btn-success"  style="width:100%;"  type="submit" value="<?php echo $contHistory[$i]['gkey'];?>" onclick ="getEventDetails(this.value);">Expand</button>
							</td>
						</tr-->
						<tr class="gradeX">
						
							<td align="center"> <?php echo $contHistory[$i]['TIME_MOVE']?> </td>
							<td align="center"> <?php echo $contHistory[$i]['TIME_IN']?> </td>
							<td align="center"> <?php echo $contHistory[$i]['TIME_OUT']?> </td>
							<td align="center"> <?php echo $contHistory[$i]['CATEGORY']?> </td>
							<td align="center"> <?php echo $contHistory[$i]['FREIGHT_KIND']?> </td>
							<td align="center"> <?php echo $contHistory[$i]['MLO']?> </td>
							<td align="center"> <?php echo $contHistory[$i]['TRANSIT_STATE']?> </td>
							<td align="center"> <?php echo $contHistory[$i]['LAST_POS_NAME']?> </td>
							<td align="center"> 
								<input type="hidden" name="contNo" id="contNo" value="<?php echo $contNo;?>">
								<?php $contgkey = $contHistory[$i]['GKEY'];?>
								<button class="mb-xs mt-xs mr-xs btn btn-success"  style="width:100%;"  type="submit" value="<?php echo $contgkey;?>" onclick ="getEventDetails('<?php echo $contgkey;?>');">Expandd</button>
							</td>
						</tr>
						<?php } ?>
						<!--tr class="gradeX">
							<td align="center" colspan="17">
								<?php echo $links?>
							</td>
						</tr-->
					</tbody>
				</table>
				<?php } ?>
				
				
				</table>
		
				<table class='contTable' cellspacing="1" cellpadding="1" align="center"  id="mytbl" style="overflow-y:scroll" >		
				</table>
			   
				</div>
				  <div class="clr"></div>
				</div>
			</div>
		</section>
	<!-- end: page -->
</section>
</div>