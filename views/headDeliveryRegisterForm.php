<script>

	function fetchterminal(regterminal)
	{
		if(regterminal=="")
		{
			regblock.disabled=true;
			regassigntype.disabled=true;
		}
		else if(regterminal=="CCT" || regterminal=="NCT")
		{
			regblock.disabled=true;
			regassigntype.disabled=false;
			getAssignment2();
		}
		else if(regterminal=="GCB")
		{
			regblock.disabled=false;
			regassigntype.disabled=false;				
			getAssignment2();
			getBlock3();
		}
	}
	
	function getAssignment2()
	{
	    //alert(terminal);
		if (window.XMLHttpRequest) 
		{
		  xmlhttp=new XMLHttpRequest();
		} 
		else 
		{  
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		var assignDt = document.appraisementRegister.regdate.value;
		var terminal =document.appraisementRegister.regterminal.value;

	    //alert(url);
		xmlhttp.onreadystatechange=function()
        {			
            //alert(xmlhttp.readyState);
            if (xmlhttp.readyState==4 && xmlhttp.status==200) 
            {
                var val = xmlhttp.responseText;
                var selectList=document.getElementById("regassigntype");
                removeOptions(selectList);
                
                var val = xmlhttp.responseText;
                var jsonData = JSON.parse(val);
                
                
                for (var i = 0; i < jsonData.length; i++) 
                {
                    var option = document.createElement('option');
                    option.value = jsonData[i].mfdch_value;  //value of option in backend
                    option.text = jsonData[i].mfdch_desc;	  //text of option in frontend
                    selectList.appendChild(option);
                }										
            }
        }

        var url = "<?php echo site_url('AjaxController/getAssignmentType')?>?terminal="+terminal+"&assignDt="+assignDt;

		xmlhttp.open("GET",url,true);
					
		xmlhttp.send();
	}
	
	function getBlock3()
	{
	    //alert("ok");
		if (window.XMLHttpRequest) 
		{
			xmlhttp=new XMLHttpRequest();
		} 
		else 
		{  
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}

		var terminal=document.appraisementRegister.regterminal.value;
	    //alert(terminal);
		xmlhttp.onreadystatechange=function()
            {			
                if (xmlhttp.readyState==4 && xmlhttp.status==200) 
                {
                    var val = xmlhttp.responseText;
                    
                    var selectList=document.getElementById("regblock");
                    removeOptions(selectList);
                    
                    var val = xmlhttp.responseText;
                    var jsonData = JSON.parse(val);
                    
                    for (var i = 0; i < jsonData.length; i++) 
                    {
                        var option = document.createElement('option');
                        option.value = jsonData[i].Block_No;  //value of option in backend
                        option.text = jsonData[i].Block_No;	  //text of option in frontend
                        selectList.appendChild(option);
                    }										
                }
            }
		xmlhttp.open("GET","<?php echo site_url('AjaxController/loadBlock')?>?terminal="+terminal,true);
					
		xmlhttp.send();
	}

    function chkblock()
	{
		if(document.appraisementRegister.regterminal.value == "")
		{
			alert( "Please provide terminal!" );
			document.appraisementRegister.regterminal.focus() ;
			return false;
		}
		else if(document.appraisementRegister.regterminal.value=="GCB" && document.appraisementRegister.regblock.value == "")
		{
			alert( "Please provide block!" );
			document.appraisementRegister.regblock.focus() ;
			return false;
		}
		return true;
	}

    function removeOptions(selectbox)
	{
		var i;
		for(i=selectbox.options.length-1;i>=1;i--)
		{
			selectbox.remove(i);
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
                        <form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url("Report/appraisementRegisterPerform"); ?>" target="_blank" id="appraisementRegister" name="appraisementRegister" onsubmit="return chkblock();">
                            <div class="form-group">
                                <label class="col-md-3 control-label">&nbsp;</label>
                                <div class="col-md-6">	
                                    <div class="input-group mb-md">
                                        <span class="input-group-addon span_width">Date <span class="required">*</span></span>
                                        <input type="date" name="regdate" id="regdate" class="form-control" value="<?php date("Y-m-d"); ?>">
                                    </div>
                                    <div class="input-group mb-md">
                                        <span class="input-group-addon span_width">Terminal <span class="required">*</span></span>
                                        <select name="regterminal" id="regterminal" onchange="fetchterminal(this.value);" class="form-control">
                                            <option value="">--Select--</option>
                                            <option value="CCT">CCT</option>
                                            <option value="NCT">NCT</option>
                                            <option value="GCB">GCB</option>
                                        </select>
                                    </div>
                                    <div class="input-group mb-md">
                                        <span class="input-group-addon span_width">Block <span class="required">*</span></span>
                                        <select name="regblock" id="regblock" class="form-control" disabled>
                                            <option value="ALLBLOCK">--All Block--</option>
                                        </select>
                                    </div>
                                    <div class="input-group mb-md">
                                        <span class="input-group-addon span_width">Assignment Type <span class="required">*</span></span>
                                        <select name="regassigntype" id="regassigntype" class="form-control" disabled>
                                            <option value="ALLASSIGN">--All Assignment--</option>
                                        </select>
                                    </div>												
                                </div>

                                <div class="row">
                                    <div class="col-sm-12 text-center">
                                        <!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
                                        <button type="submit" name="report" class="mb-xs mt-xs mr-xs btn btn-success">View</button>
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
	<!-- end: page -->
</section>
</div>