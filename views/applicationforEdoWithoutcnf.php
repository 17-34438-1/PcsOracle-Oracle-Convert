<script>

    function getSearchInfo()
	{	
		var rot_no = document.getElementById("rot_no").value;
		var bl_no = document.getElementById("bl_no").value;
		if(rot_no=="")
		{
			alert("Please select reg number");
			return false;
		}
		else if(bl_no=="")
		{
			alert("Please select BL number");
			return false;
		}
		else
		{
			
			
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
				
				var bltypedata = "";
				// alert(jsonData.mloName);
				// alert(jsonData.shippingAgentName);
				// alert(jsonData.ffName);
				// alert(jsonData.msgFlag);

				if(jsonData.msgFlag==0)
				{
					alert("Wrong Combination of Reg and BL");
                    document.getElementById("rot_no").value = "";
                    document.getElementById("bl_no").value = "";
					return false;
				}
				else
				{
					var mloId = jsonData.mloId;
					document.getElementById("mlo_id").value = mloId;
				}	
			}
		};
			
			var url = "<?php echo site_url('AjaxController/getIGMInfo')?>?rot_no="+rot_no+"&bl_no="+bl_no;	
			//alert(url);	
			xmlhttp.open("GET",url,false);
			xmlhttp.send();
		}
		
	}

    function validate()
    {
		var rot_no = document.getElementById("rot_no").value;
		var bl_no = document.getElementById("bl_no").value;		
		var ain_no = document.getElementById("ain_no").value;		
        if(rot_no=="")
		{
			alert("Please Enter Reg number");
			return false;
		}
		else if(bl_no=="")
		{
			alert("Please Enter BL number");
			return false;
		}else if(ain_no=="")
		{
			alert("Please Enter AIN of C&F");
			return false;
		}
	}

</script>

<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

	<!-- start: page -->
		<div class="row">
			<div class="col-lg-12">						
				<section class="panel">
					<div class="panel-body">
                        <form class="form-horizontal form-bordered" method="POST" 
                        action="<?php echo site_url("EDOController/appliedforEdoWithoutcnf") ?>" onsubmit="return validate();">
                            <div class="form-group">
                                <label class="col-md-3 control-label">&nbsp;</label>
                                <div class="col-md-6">		
                                    <div class="input-group mb-md">
                                        <span class="input-group-addon span_width">Reg No <span class="required">*</span></span>
                                        <input type="text" name="rot_no" id="rot_no" class="form-control login_input_text" autofocus= "autofocus" tabindex="1" placeholder="Reg No" required>

										<!-- <input type="hidden" name="mlo_id" id="mlo_id" tabindex="1" value="" required> -->

                                    </div>
                                    <div class="input-group mb-md">
                                        <span class="input-group-addon span_width">BL No <span class="required">*</span></span>
                                        <!-- <input type="text" name="bl_no" id="bl_no" class="form-control login_input_text" tabindex="2" placeholder="BL No" onblur="return getSearchInfo();" required> -->
										<input type="text" name="bl_no" id="bl_no" class="form-control login_input_text" tabindex="2" placeholder="BL No" required>
                                    </div>
                                    <div class="input-group mb-md">
                                        <span class="input-group-addon span_width">C&F AIN No <span class="required">*</span></span>
                                        <input type="text" name="ain_no" id="ain_no" class="form-control login_input_text" tabindex="2" placeholder="AIN No" required>
                                    </div>
                                </div>									
                                <div class="row" id="applyBtn">
                                    <div class="col-sm-12 text-center">
                                        <button type="submit" name="submit" class="mb-xs mt-xs mr-xs btn btn-primary">
                                            Submit
                                        </button>
                                    </div>													
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 text-center">
                                        <?php echo $msg; ?>
                                    </div>													
                                </div>						
                            </div>
                        </form>
					</div>
				</section>
			</div>
		</div>	
	<!-- end: page -->
</section>
</div>