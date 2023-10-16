<script>
function getIGMDtl()
	{		
	//alert("OK : "+rotation);
	var rotation_no=document.getElementById("rotation_no").value;
	var bl_no=document.getElementById("bl_no").value;
	
	//alert("hgh : "+rotation_no);
	
		if (window.XMLHttpRequest) 
		{

		  xmlhttp=new XMLHttpRequest();
		} 
		else 
		{  
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=stateChangeIgmDtlInfo;
		xmlhttp.open("GET","<?php echo site_url('ajaxController/getIGMDtlInfo')?>?rotation_no="+rotation_no+"&bl_no="+bl_no,false);
					
		xmlhttp.send();
	}
	
	
	function stateChangeIgmDtlInfo()
	{			
		if (xmlhttp.readyState==4 && xmlhttp.status==200) 
		{
			
			//var val = xmlhttp.responseText;
			
		    //alert("OK");
			
			//var line_no=document.getElementById("line_no");
			//removeOptions(selectList);
			//alert(xmlhttp.responseText);
			var val = xmlhttp.responseText;
			var jsonData = JSON.parse(val);
			console.log(jsonData);
			//clearForm();
			if(jsonData.status_mst==0)
				{
					alert("Rotation Number Not Valid");
				}
				else if(jsonData.status_dtl==0)
				{
					//alert("BL Number Not Exist");
					for (var i = 0; i < jsonData.rtnIGMMstList.length; i++) 
					{
						document.getElementById('IGM_id').value=jsonData.rtnIGMMstList[i].id;
					}
				}
				else
				{
					//alert("BL Details Already Exist");
					for (var i = 0; i < jsonData.rtnIGMDtlList.length; i++) 
					{
						document.getElementById('id').value=jsonData.rtnIGMDtlList[i].id;
						document.getElementById('IGM_id').value=jsonData.rtnIGMDtlList[i].IGM_id;
						document.getElementById('line_no').value=jsonData.rtnIGMDtlList[i].Line_No;

						
						document.getElementById('pck_num').value=jsonData.rtnIGMDtlList[i].Pack_Number;
						document.getElementById('pck_desc').value=jsonData.rtnIGMDtlList[i].Pack_Description;
						document.getElementById('pck_marks_num').value=jsonData.rtnIGMDtlList[i].Pack_Marks_Number;
						document.getElementById('goods_desc').value=jsonData.rtnIGMDtlList[i].Description_of_Goods;
						document.getElementById('weight').value=jsonData.rtnIGMDtlList[i].weight;
						document.getElementById('remarks').value=jsonData.rtnIGMDtlList[i].Remarks;
						document.getElementById('cons_desc').value=jsonData.rtnIGMDtlList[i].ConsigneeDesc;
						document.getElementById('not_desc').value=jsonData.rtnIGMDtlList[i].NotifyDesc;
						//document.getElementById('sub_id').value=jsonData.rtnIGMDtlList[i].Submitee_Id;
						//document.getElementById('sub_dt').value=jsonData.rtnIGMDtlList[i].Submission_Date;
						document.getElementById('mlo_code').value=jsonData.rtnIGMDtlList[i].mlocode;
						document.getElementById('exp_name').value=jsonData.rtnIGMDtlList[i].Exporter_name;
						document.getElementById('exp_addr').value=jsonData.rtnIGMDtlList[i].Exporter_address;
						document.getElementById('not_code').value=jsonData.rtnIGMDtlList[i].Notify_code;
						document.getElementById('not_name').value=jsonData.rtnIGMDtlList[i].Notify_name;
						document.getElementById('not_addr').value=jsonData.rtnIGMDtlList[i].Notify_address;
						
						document.getElementById('cons_code').value=jsonData.rtnIGMDtlList[i].Consignee_code;
						document.getElementById('cons_name').value=jsonData.rtnIGMDtlList[i].Consignee_name;
						document.getElementById('cons_addr').value=jsonData.rtnIGMDtlList[i].Consignee_address;
						document.getElementById('dg_stat').value=jsonData.rtnIGMDtlList[i].DG_status;
						document.getElementById('unload_code').value=jsonData.rtnIGMDtlList[i].place_of_unloading;
						document.getElementById('origine_code').value=jsonData.rtnIGMDtlList[i].port_of_origin;
						
					}
					
					document.getElementById('comment').value=jsonData.rtnComment[0].COMMENT;
				}
						
		}
	}
 

 
	function clearForm()
	{
		//document.getElementById('id').value="";
		document.getElementById('IGM_id').value="";
		document.getElementById('line_no').value="";
		//document.getElementById('BL_No').value="";
		document.getElementById('pck_num').value="";
		document.getElementById('pck_desc').value="";
		document.getElementById('pck_marks_num').value="";
		document.getElementById('goods_desc').value="";
		document.getElementById('weight').value="";
		document.getElementById('remarks').value="";
		document.getElementById('cons_desc').value="";
		document.getElementById('not_desc').value="";
		//document.getElementById('sub_id').value="";
		//document.getElementById('sub_dt').value="";
		//document.getElementById('Submitee_Org_Id').value=jsonData.rtnIGMDtlList[i].Submitee_Org_Id;
						
		//document.getElementById('last_update').value=jsonData.rtnIGMDtlList[i].last_update;
		//document.getElementById('type_of_igm').value="";
		//document.getElementById('weight_unit').value="";
		document.getElementById('mlo_code').value="";
		document.getElementById('exp_name').value="";
		document.getElementById('exp_addr').value="";
		document.getElementById('not_code').value="";
		document.getElementById('not_name').value="";
		document.getElementById('not_addr').value="";
					
		document.getElementById('cons_code').value="";
		document.getElementById('cons_name').value="";
		document.getElementById('cons_addr').value="";
		document.getElementById('dg_stat').value="";
		document.getElementById('unload_code').value="";
		document.getElementById('origine_code').value="";
	}
		
function validate()
      {
		  //alert("OK");
		if(confirm("Do you really want Save?"))
		{
			if( document.myform.er_date.value == "" )
			 {
				alert( "Please provide Rotation Number!" );
				document.myform.er_date.focus() ;
				return false;
			 }
			 else{
				 return( true );
			 }
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

  	<div class="row">
		<div class="col-lg-12">						
			<section class="panel">
				<div class="panel-body">
			<form onsubmit="return validate();" action="<?php echo site_url("IgmViewController/igmInfoProcess");?>" name="myForm" id="myForm" method="post">
				<div class="row">
					<div class="col-md-3">&nbsp;</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Rotation No </span>
							<input type="text" name="rotation_no" id="rotation_no" class="form-control" placeholder="Rotation no">
						</div>
					</div>
				</div>
			 
				<div class="row">
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Line No  </span>
							<input type="text" name="line_no" id="line_no" class="form-control read" placeholder="Line No">
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">B/L No  </span>
							<input type="text" name="bl_no" id="bl_no" class="form-control read" placeholder="B/L No" onblur="getIGMDtl()">
							<input type="hidden" id="id" name="id" class="form-control">
							<input type="hidden" id="IGM_id" name="IGM_id" class="form-control">
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Pack Number  </span>
							<input type="text" name="pck_num" id="pck_num" class="form-control read" placeholder="Pack Number">
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Pack Description </span>
							<input type="text" name="pck_desc" id="pck_desc" class="form-control read" placeholder="Pack Description">
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Pack Marks Number </span>
							<textarea name="pck_marks_num" id="pck_marks_num" class="form-control read" placeholder="Pack Marks Number"></textarea>
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Description of Goods </span>
							<textarea name="goods_desc" id="goods_desc" class="form-control read" placeholder="Description of Goods"></textarea>
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Weight </span>
							<input type="text" name="weight" id="weight" class="form-control read" placeholder="Weight">
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Remarks </span>
							<input type="text" name="remarks" id="remarks" class="form-control read" placeholder="Remarks">
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Consignee Description </span>
							<textarea name="cons_desc" id="cons_desc" class="form-control read" placeholder="Consignee Description"></textarea>
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Notify Description </span>
							<textarea name="not_desc" id="not_desc" class="form-control read" placeholder="Notify Description"></textarea>
						</div>
					</div>
		        </div>
		 
		 
		        <div class="row">
				 	<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Exporter Name </span>
							<input type="text" name="exp_name" id="exp_name" class="form-control read" placeholder="Exporter Name">
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Exporter Address </span>
							<textarea name="exp_addr" id="exp_addr" class="form-control read" placeholder="Exporter Address"></textarea>
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Notify Name </span>
							<input type="text" name="not_name" id="not_name" class="form-control read" placeholder="Notify Name">
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Notify Address </span>
							<textarea name="not_addr" id="not_addr" class="form-control read" placeholder="Notify Address"></textarea>
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Notify Code </span>
							<input type="text" name="netWeight" id="netWeight" class="form-control read" placeholder="Notify Code">
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Consignee Code </span>
							<input type="text" name="cons_code" id="cons_code" class="form-control read" placeholder="Consignee Code">
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Consignee Name </span>
							<input type="text" name="cons_name" id="cons_name" class="form-control read" placeholder="Consignee Name">
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Consignee Address </span>
							<textarea name="cons_addr" id="cons_addr" class="form-control read" placeholder="Consignee Address"></textarea>
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Origin Code </span>
							<input type="text" name="origine_code" id="origine_code" class="form-control read" placeholder="Origin Code">
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Unloading Code </span>
							<input type="text" name="unload_code" id="unload_code" class="form-control read" placeholder="Unloading Code">
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">MLO Code </span>
							<input type="text" name="mlo_code" id="mlo_code" class="form-control read" placeholder="MLO Code">
						</div>
					</div>
					<div class="col-md-6">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">DG Status </span>
							<input type="text" name="dg_stat" id="dg_stat" class="form-control read" placeholder="DG Status">
						</div>
					</div>
			  </div>
			  <div class="row">
					<div class="col-md-12">
						<div class="input-group mb-md">
							<span class="input-group-addon span_width">Comment </span>
							<input type="text" name="comment" id="comment" class="form-control" placeholder="Comment">
						</div>
					</div>
				</div>

			<div class="row">
				<div class="col-sm-12 text-center">
					<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">SAVE</button>
				</div>													
			</div>
			<div class="row">
				<div class="col-sm-12 text-center">
					<?PHP echo @$msg;?>
				</div>
			</div>		
		</form>	
	</div>

</section>