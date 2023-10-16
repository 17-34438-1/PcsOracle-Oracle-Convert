<script>
    function getBlock(yard)
	{		
		// alert(yard);
		if (window.XMLHttpRequest) 
		{

		  xmlhttp=new XMLHttpRequest();
		} 
		else 
		{  
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}

		xmlhttp.onreadystatechange = function()
        {			
            if (xmlhttp.readyState==4 && xmlhttp.status==200) 
            {
                
                var val = xmlhttp.responseText;
                
                // alert(val);
                
                var selectList=document.getElementById("block");
                removeOptions(selectList);
                //alert(xmlhttp.responseText);
                var val = xmlhttp.responseText;
                var jsonData = JSON.parse(val);
                //alert(xmlhttp.responseText);
                for (var i = 0; i < jsonData.length; i++) 
                {
                    // alert(jsonData[i].block);
                    var option = document.createElement('option');
                    option.value = jsonData[i].block;  //value of option in backend
                    option.text = jsonData[i].block;	  //text of option in frontend
                    selectList.appendChild(option);
                }										
            }
        };

		xmlhttp.open("GET","<?php echo site_url('AjaxController/getBlock')?>?yard="+yard,false);
					
		xmlhttp.send();
	}

    function removeOptions(selectbox)
	{
		var i;
		for(i=selectbox.options.length-1;i>=1;i--)
		{
			selectbox.remove(i);
		}
	}

    function validate()
    {
        var yard = document.getElementById('yard_no').value;
        if(yard == ""){
            alert("Please select yard.");
            return false;
        }else{
            return true;
        }
        return false;
    }
	
	function getEquipment(val)
	{
		if (val=='NCY')
		{
			//alert("ok");
			$("#equipment option[value='RTG01']").hide();
			$("#equipment option[value='RTG02']").hide();
			$("#equipment option[value='RTG03']").hide();
			$("#equipment option[value='RTG04']").hide();
			$("#equipment option[value='RTG05']").hide();
			$("#equipment option[value='RTG06']").hide();
			$("#equipment option[value='RTG07']").hide();
			$("#equipment option[value='RTG08']").hide();
			$("#equipment option[value='RTG09']").hide();
			$("#equipment option[value='RTG10']").hide();
			$("#equipment option[value='RTG11']").hide();
			$("#equipment option[value='RTG12']").hide();
			$("#equipment option[value='RTG13']").hide();
			$("#equipment option[value='RTG14']").hide();
			$("#equipment option[value='RTG15']").hide();
			$("#equipment option[value='RTG16']").hide();
			$("#equipment option[value='RTG17']").hide();
			$("#equipment option[value='RTG18']").hide();
			$("#equipment option[value='RTG19']").hide();
			$("#equipment option[value='RTG20']").hide();
			$("#equipment option[value='RTG21']").hide();
			$("#equipment option[value='RTG22']").hide();
			$("#equipment option[value='RTG23']").hide();
			$("#equipment option[value='RTG24']").hide();
			$("#equipment option[value='RTG25']").hide();
			$("#equipment option[value='RTG26']").hide();
			$("#equipment option[value='RTG27']").hide();
			$("#equipment option[value='RTG28']").hide();
			$("#equipment option[value='RTG29']").hide();
			$("#equipment option[value='RTG30']").hide();
			$("#equipment option[value='RTG31']").hide();
			$("#equipment option[value='RTG32']").hide();
			$("#equipment option[value='RTG33']").hide();
			$("#equipment option[value='RTG34']").hide();
			$("#equipment option[value='RTG35']").hide();
			$("#equipment option[value='RTG36']").hide();
			$("#equipment option[value='RTG37']").hide();
			$("#equipment option[value='RTG38']").hide();
			$("#equipment option[value='RTG39']").hide();
			$("#equipment option[value='RTG40']").hide();
			$("#equipment option[value='RTG41']").hide();
			$("#equipment option[value='RTG200']").hide();
		}
		else
		{
			$("#equipment option[value='RTG01']").show();
			$("#equipment option[value='RTG02']").show();
			$("#equipment option[value='RTG03']").show();
			$("#equipment option[value='RTG04']").show();
			$("#equipment option[value='RTG05']").show();
			$("#equipment option[value='RTG06']").show();
			$("#equipment option[value='RTG07']").show();
			$("#equipment option[value='RTG08']").show();
			$("#equipment option[value='RTG09']").show();
			$("#equipment option[value='RTG10']").show();
			$("#equipment option[value='RTG11']").show();
			$("#equipment option[value='RTG12']").show();
			$("#equipment option[value='RTG13']").show();
			$("#equipment option[value='RTG14']").show();
			$("#equipment option[value='RTG15']").show();
			$("#equipment option[value='RTG16']").show();
			$("#equipment option[value='RTG17']").show();
			$("#equipment option[value='RTG18']").show();
			$("#equipment option[value='RTG19']").show();
			$("#equipment option[value='RTG20']").show();
			$("#equipment option[value='RTG21']").show();
			$("#equipment option[value='RTG22']").show();
			$("#equipment option[value='RTG23']").show();
			$("#equipment option[value='RTG24']").show();
			$("#equipment option[value='RTG25']").show();
			$("#equipment option[value='RTG26']").show();
			$("#equipment option[value='RTG27']").show();
			$("#equipment option[value='RTG28']").show();
			$("#equipment option[value='RTG29']").show();
			$("#equipment option[value='RTG30']").show();
			$("#equipment option[value='RTG31']").show();
			$("#equipment option[value='RTG32']").show();
			$("#equipment option[value='RTG33']").show();
			$("#equipment option[value='RTG34']").show();
			$("#equipment option[value='RTG35']").show();
			$("#equipment option[value='RTG36']").show();
			$("#equipment option[value='RTG37']").show();
			$("#equipment option[value='RTG38']").show();
			$("#equipment option[value='RTG39']").show();
			$("#equipment option[value='RTG40']").show();
			$("#equipment option[value='RTG41']").show();
			$("#equipment option[value='RTG200']").show();
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
                    <form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('Report/blockWiseEquipmentHandlingReport'); ?>" target="_blank" id="myform" onsubmit="return validate()">
                        <div class="form-group">
                            <label class="col-md-3 control-label">&nbsp;</label>
                            <div class="col-md-6">	
                                <div class="input-group mb-md">
                                    <span class="input-group-addon span_width">From Date <span class="required">*</span></span>
                                    <input type="date" name="fromdate" id="fromdate" class="form-control" value="<?php echo date("Y-m-d"); ?>">
                                </div>
                                <div class="input-group mb-md">
                                    <span class="input-group-addon span_width">To Date <span class="required">*</span></span>
                                    <input type="date" name="todate" id="todate" class="form-control" value="<?php echo date("Y-m-d"); ?>">
                                </div>
                                <div class="input-group mb-md">
                                    <span class="input-group-addon span_width">Yard Name <span class="required">*</span></span>
                                    <select name="yard_no" id="yard_no" onchange="getBlock(this.value)" class="form-control">
                                            <option value="">yard_no</option>
                                            <option value="CCT">CCT</option>
                                            <option value="NCT">NCT</option>
                                            <option value="GCB">GCB</option>
                                            <option value="SCY">SCY</option>
                                            <option value="OFY2">OFY2</option>
                                     </select>
                                </div>
                                <div class="input-group mb-md">
                                    <span class="input-group-addon span_width">Block <span class="required">*</span></span>
                                    <select name="block" id="block" onchange="getEquipment(this.value)" class="form-control">
                                        <option value="">-- All --</option>
                                    </select>
                                </div>

                                <div class="input-group mb-md">
                                    <span class="input-group-addon span_width">Equipment <span class="required">*</span></span>
                                    <select name="equipment" id="equipment" class="form-control">
                                        <option value="">-- All --</option>
                                        <?php
                                            for($i=0;count($equipment)>$i;$i++)
                                            {
                                        ?>
                                            <option value="<?php echo $equipment[$i]['logEquip']; ?>"><?php echo $equipment[$i]['logEquip']; ?></option>
                                        <?php
                                            }
                                        ?>
                                    </select>
                                </div>
                                
                                <div class="input-group mb-md">
                                    <span class="input-group-addon span_width">Shift <span class="required">*</span></span>
                                    <select name="shift" id="shift" class="form-control">
                                        <option value="">-- All --</option>
                                        <option value="a">A</option>
                                        <option value="b">B</option>
                                        <option value="c">C</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-offset-4 col-md-3">
                                <div class="radio-custom radio-success">
                                    <input type="radio" id="options" name="options" value="html" checked>
                                    <label for="radioExample3">HTML</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="radio-custom radio-success">
                                    <input type="radio" id="options" name="options" value="pdf" >
                                    <label for="radioExample3">PDF</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
                                    <button type="submit" id="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">Show</button>
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
</section>
</div>