<style>
 #table-scroll {
  height:350px;
  overflow:auto;  
  margin-top:20px;
}
 </style>
<script>

	function chkLic(){

		var cnfLic = "";
		cnfLic = document.getElementById("cnfLic").value.trim();
		
		if (window.XMLHttpRequest) 
		{
			xmlhttp=new XMLHttpRequest();
		} 
		else 
		{  
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		
		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4 && xmlhttp.status==200) 
			{						  
				var val = xmlhttp.responseText;
				var jsonData = JSON.parse(val);

				if(jsonData.rslt_CNFName.length>0)
				{
					var isCnfExist = jsonData.rslt_CNFName[0].rtnValue;
					if(isCnfExist == 1){
						document.getElementById("calidCnf").value=isCnfExist; 
						document.getElementById("feedback").innerHTML = "";
						// document.getElementById("feedback").innerHTML = "Valid C&F License";
						// document.getElementById("feedback").style.color="green";
					}else{
						document.getElementById("calidCnf").value=isCnfExist;
						document.getElementById("feedback").innerHTML = "Invalid C&F License";
						document.getElementById("feedback").style.color="red";
					}
				}
				else
				{
					alert("C&F License is not valid!");
				}
			}
		};
		
		var url = "<?php echo site_url('AjaxController/isCNFExist')?>?cnf_lic_no="+cnfLic;
		xmlhttp.open("GET",url,false);
		xmlhttp.send();
	}

	function validate(){
		var cp = "";
		var cnfLic = "";
		var noOfTruck = ""; 
		var validCnf = "";

		cp = document.getElementById("cp").value.trim();
		cnfLic = document.getElementById("cnfLic").value.trim();
		noOfTruck = document.getElementById("noOfTruck").value.trim();
		validCnf = document.getElementById("calidCnf").value.trim();

		if(cp == "" || cnfLic == "" || noOfTruck == "" )
		{
			alert("Please fill all The Field");
			return false;
		}
		else if(validCnf == 0)
		{
			alert("Invalid C&F License");
			return false;
		}
		return false;
	}

</script>
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>
 <div class="content">
    <div class="content_resize">
      <div class="mainbar">
        <div class="article">
		
			<div class="row">
				<div class="col-lg-12">						
					<section class="panel">
						<div class="panel-body">
							<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/entryDlvInfo'; ?>" target="" id="myform" name="myform" onsubmit="return validate();">
								<div class="form-group">
									<label class="col-md-3 control-label">&nbsp;</label>
									<div class="col-md-6">	
                                        <input type="hidden" name="igm_sup_dtl_id" id="igm_sup_dtl_id" value="<?php echo $igm_sup_dtl_id;?>">	
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Rotation No</span>
											<input type="text" name="rot_no" id="rot_no" class="form-control" value="<?php echo $rot; ?>" readonly>
										</div>
										<div class="input-group mb-md">
											<span class="input-group-addon span_width">Bl No</span>
											<input type="text" name="bl_no" id="bl_no" class="form-control" value="<?php echo $bl; ?>" readonly>
										</div>
                                        <div class="input-group mb-md">
											<span class="input-group-addon span_width">CP <span class="required">*</span></span>
											<input type="text" name="cp" id="cp" class="form-control" value="" placeholder="CP">
										</div>
                                        <div class="input-group">
											<span class="input-group-addon span_width">C&F lic <span class="required">*</span></span>
											<input type="hidden" name="validCnf" id="calidCnf" value="0">
											<input type="text" name="cnfLic" id="cnfLic" class="form-control" value="" placeholder="C&F lic" onblur="chkLic()">
										</div>
										<div class="input group">
											<p id="feedback" style="margin-left:150px;"><p>
										</div>
										<div class="input-group mb-md mt-md">
											<span class="input-group-addon span_width">No. Of Truck <span class="required">*</span></span>
											<input type="text" name="noOfTruck" id="noOfTruck" class="form-control" value="" placeholder="no. of truck">
										</div>											
									</div>
																					
									<div class="row">
										<div class="col-sm-12 text-center">
											<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
											<button type="submit" id="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success login_button">Submit</button>
										</div>													
									</div>

									<div class="row">
										<div class="col-sm-12 text-center">
											
										</div>
									</div>
								</div>	
							</form>
						</div>

						
					</section>
				</div>
			</div>

		 <!--</div>-->
		 </div>
		 

        </div>
       
       <!-- <p class="pages"><small>Page 1 of 2</small> <span>1</span> <a href="#">2</a> <a href="#">&raquo;</a></p>-->
      </div>
    </div>
	
  </div>
</section>