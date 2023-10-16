<script>
    function clickCheckBox()
	{
		var applyBtn = document.getElementById("applyBtn");
		var t = document.getElementById("total").value;
		var arr=[];
		var total=parseInt(t);
		var state=0;
		var btnState=0;
	   //applyBtn.style.display="block";
	   for( var i=0;i<total;i++)
	   {
		var  serialNo=i.toString();
	   
	   if((document.getElementById(serialNo).checked) == false)
	   {
		   state=1;
		   //btnState=0;
	   }

	  }
	  if(state==1)
	  {
		
		document.getElementById("checkAll").checked = false;
	  }
	  else if(state==0)
	  {
		
		document.getElementById("checkAll").checked = true;
	  }

	}
	function selectAll() {
	  var applyBtn = document.getElementById("applyBtn");
	  var checkBox = document.getElementById("checkAll");
	  var t = document.getElementById("total").value;
	  var total=parseInt(t);
	 if ( document.getElementById("checkAll").checked == true)
	 {
		//applyBtn.style.display="block";
		for(let i=0;i<total;i++)
		{
			id=i.toString(); 
			document.getElementById(id).checked = true;

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
	function validityExtend(){

		var validityExtend = document.getElementById("validity_extend");
		var uploadId = document.getElementById("uploadId").value;  	
		var t = document.getElementById("total").value;
		var arr=[];
		var total=parseInt(t);
		validity=validityExtend.value;
		if(validity==""){
		alert("Extend Validity is Empty");
		return false;
		}
		else{
		  return true;  
		 
		}

	}

</script>
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
<div class="row">
<div class="col-lg-12">						
    <div class="panel-body">
			<div class="row">
				<div class="col-sm-12 col-md-12 text-center">
					<h4 class="h4 mt-none mb-sm text-dark"><b><u>Validity Extend Request</u></b></h4>
				</div>
				<div class="col-sm-12 col-md-12">
					<h6 class="h6 mt-none mb-sm">
						<div class="row">
							<div class="col-md-4 text-right"><b>ROTATION<b></div>
							<div class="col-md-1 ">:</div>
							<div class="col-md-6 text-left"><label id="igmtype"><?php echo $imp_rot;?></label></div>
						</div>
					</h6>
					<h6 class="h6 mt-none mb-sm">
						<div class="row">
							<div class="col-md-4 text-right"><b>BL NO</b></div> 
							<div class="col-md-1">:</div>
							<div class="col-md-6 text-left"><label id="bltype"><?php echo $blNo;?></label></div>
						</div>
					</h6>
                    <form action="<?php echo site_url('EDOController/validityExtendHBL')?>" method="POST" onsubmit="return validityExtend(); ">
                    <h6 class="h6 mt-none mb-sm">
						<div class="row">
							<div class="col-md-4 text-right"><b>Valid Upto</b></div> 
							<div class="col-md-1">:</div>
							<div class="col-md-6 text-left">
                                <label id="bltype"><input type="date" name="validity_extend" id="validity_extend" value="" class="form-control"/></label>
                            </div>
						</div>
					</h6>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<?php include("mydbPConnection.php"); ?>
	<div class="col-lg-12">						
		<div class="panel-body">
			<section class="panel">
               <div class="col-sm-12 col-md-12 text-center">
                   <h3 class="h3 mt-none mb-sm text-dark"><b>Container List</b></h3>
               </div>
				<table class="table table-bordered table-striped mb-none" style="background-color:#7ecef8;">
					<thead>
						<tr>						
							<th class="text-center">
								<font size='4'>All</font>
								<input type="hidden" name="uploadId" id="uploadId" value="<?php  echo $uploadId;?>"></input>
								<input type="hidden" name="total" id="total" value="<?php  echo $value=count($result);?>"></input>
								<input type="checkbox" name="checkAll" id="checkAll" onclick="selectAll();" checked></input>
							</th>							
							<th class="text-center"><font size='4'>Container No</font></th>
						</tr>
					</thead>					
					<tbody>                      
						<?php for($i=0;$i<count($result);$i++){
                             $selectedContainerQuery="SELECT * FROM edo_applied_validity_date WHERE edo_id='$edo_id'";
                             $selectedContainerResult = mysqli_query($con_cchaportdb,$selectedContainerQuery);
			                 $countSelectedContainerResult=mysqli_num_rows($selectedContainerResult);
                            
                            ?>

						<tr>
							<td align="center"> 
								<!--input type="checkbox" name="list[]" id="<?php echo $i;?>"
								value="<?php echo $result[$i]['cId'];?>" <?php if($countSelectedContainerResult>0){
                                    while($row = mysqli_fetch_array( $selectedContainerResult)){
                                        if( $result[$i]['cId']==$row['cont_igm_id']){
                                            echo "checked";
                                        }

                                    }
                                }?>></input-->

								<input type="checkbox" name="list[]" id="<?php echo $i;?>"
								value="<?php echo $result[$i]['cId'];?>" onclick="clickCheckBox();" checked ></input>
							</td>
							<td align="center"><?php echo $result[$i]['cont_number']; ?></td>
						</tr>
						<?php } ?>						
					</tbody>					
				</table>                	
			</section>
			
              
                          
                    <input type="hidden" name="total" id="total" value="<?php  echo $value=count($result);?>">
                    <input type="hidden" name="uploadId" id="uploadId" value="<?php  echo $uploadId;?>">
                    <input type="hidden" name="edo_id" id="edo_id" value="<?php  echo $edo_id;?>">
                    <div class="col-sm-12 text-center" id="applyBtn">
						<button type="submit" name="apply"  class="mb-xs mt-xs mr-xs btn btn-primary">
							Apply
						</button>
					</div>	
               </form> 
		</div>
	</div>
</div>
                
</section>