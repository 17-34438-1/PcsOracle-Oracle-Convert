<script>

    function myFunction(str){
        if (window.XMLHttpRequest){
            xmlhttp = new XMLHttpRequest();
        }else{
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = stateChangeValue;
        var url = "<?php echo site_url('ajaxController/getTarrifID');?>?r="+str;
        xmlhttp.open("GET",url,true);
        xmlhttp.send();
    }

    function stateChangeValue(){
        var val = xmlhttp.responseText;
        var jsonData = JSON.parse(val);
        document.getElementById("id").innerHTML = "<option value='' selected >--Select--</option>";
        var i;
        if(jsonData.length>0){
            for(i=0;i<=jsonData.length;i++){
                document.getElementById("id").innerHTML += "<option value="+jsonData[i].gkey+">"+jsonData[i].id+"</option>";
            }
        }else{
            document.getElementById("id").innerHTML += "<option value=''>No Data Found</option>";
        }
    }

</script>
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
          
<div class="row">
	<div class="col-md-8">
		<!--form id="form1" class="form-horizontal"-->
		<form name="myForm" class="form-horizontal" id="myForm" action="<?php echo site_url("report/billTarriffRateAction");?>" method="post">
			<section class="panel">
				<header class="panel-heading">
					<h2 class="panel-title">Bill Tarrif Rate Form</h2>					
				</header>
				<div class="panel-body">					
					<?php
					if($msg!="")
					{
					?>
					<div class="form-group">
						<label class="col-sm-12 control-label"><?php echo $msg; ?></label>						
					</div>
					<?php
					}
					?>											
					<div class="form-group">
						<label class="col-sm-3 control-label">Bill Type</label>
						<label class="col-sm-1 control-label">:</label>
						<div class="col-sm-8">
							<select name="bill_type" id="bill_type" value="" style="width:305px;" onchange="myFunction(this.value)">
							<?php if(isset($_POST['editRate'])){}else{echo "<option value='' selected>--Select--</option>";} ?>
								<option value="fcl" <?php if(isset($_POST['editRate'])){if($result[0]['bill_type'] == "FCL"){echo "SELECTED";}} ?> >FCL</option>
								<option value="lcl" <?php if(isset($_POST['editRate'])){if($result[0]['bill_type'] == "LCL"){echo "SELECTED";}} ?> >LCL</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">ID</label>
						<label class="col-sm-1 control-label">:</label>
						<div class="col-sm-8">
							<select name="id" id="id" class="" style="width:305px;">
								<?php if(isset($_POST['editRate'])){echo "<option value='{$result[0]['gkey']}' selected >{$result[0]['id']}</option>";}else{echo "<option value='' selected >--Select--</option>";} ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Rate Type</label>
						<label class="col-sm-1 control-label">:</label>
						<div class="col-sm-8">
							<select name="rate_type" id="rate_type" class="" style="width:305px;">
								<?php if(isset($_POST['editRate'])){}else{echo "<option value='' selected>--Select--</option>";} ?>
								<option value='REGULAR' <?php if(isset($_POST['editRate'])){if($result[0]['rate_type'] == "REGULAR"){echo "SELECTED";}} ?> >REGULAR</option>
								<option value='TIER' <?php if(isset($_POST['editRate'])){if($result[0]['rate_type'] == "TIER"){echo "SELECTED";}} ?> >TIER</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">GL Code</label>
						<label class="col-sm-1 control-label">:</label>
						<div class="col-sm-8">
							<input type="text" id="glcode" name="glcode" value="<?php if(isset($_POST['editTarrif'])){echo $result[0]['gl_code'];} ?>" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Bill Type</label>
						<label class="col-sm-1 control-label">:</label>
						<div class="col-sm-8">
							<select name="bill_type" id="bill_type" class="">
								<?php if(isset($_POST['editTarrif'])){}else{echo "<option value='' selected style='width:110px;'>--Select--</option>";}?>
								<option value="FCL" <?php if(isset($_POST['editTarrif'])){if($result[0]['bill_type'] == 'FCL'){echo "selected";}} ?> >FCL</option>
								<option value="LCL" <?php if(isset($_POST['editTarrif'])){if($result[0]['bill_type'] == 'LCL'){echo "selected";}} ?> >LCL</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Currency</label>
						<label class="col-sm-1 control-label">:</label>
						<div class="col-sm-8">
							<select name="currency" id="currency" style="width:305px;" class="">
								<?php if(isset($_POST['editRate'])){}else{echo "<option value='' selected>--Select--</option>";} ?>
								<option value='2' <?php if(isset($_POST['editRate'])){if($result[0]['currency_gkey'] == 2){echo "SELECTED";}} ?>>USD</option>
								<option value='1' <?php if(isset($_POST['editRate'])){if($result[0]['currency_gkey'] == 1){echo "SELECTED";}} ?> >BDT</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Effective Date</label>
						<label class="col-sm-1 control-label">:</label>
						<div class="col-sm-8">
							<input type="text" style="width:300px;" id="effective_date" name="effective_date" value="<?php if(isset($_POST['editRate'])){echo $result[0]['effective_date'];} ?>"  class="form-control" />
							<script>
								$(function() {
									$( "#effective_date" ).datepicker({
										changeMonth: true,
										changeYear: true,
										dateFormat: 'yy-mm-dd'
									});
								});
							</script>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Amount</label>
						<label class="col-sm-1 control-label">:</label>
						<div class="col-sm-8">
							<input type="text" style="width:300px;" id="amount" name="amount" value="<?php if(isset($_POST['editRate'])){echo $result[0]['amount'];} ?>"  class="form-control"/>
						</div>
					</div>
				</div>
				<footer class="panel-footer" align="center">
					<?php
						if(isset($_POST['editRate']))
						{
							echo "<input type='hidden' name='gkey' value='".$result[0]['gkey']."'><input type='submit' name='update' value='Update' class='btn btn-primary' />";
						}
						else
						{
							echo "<input type='submit' name='save' value='Save' class='btn btn-primary' />";
						}
					?>
					<!--button type="submit" name="" class="btn btn-primary" type="submit">Save</button-->					
				</footer>
			</section>
		</form>
	</div>
</div>
