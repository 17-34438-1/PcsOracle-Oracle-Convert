<script>
	function chkBlankField()
	{
		var cNo = document.getElementById('cNo').value;
		var cYear = document.getElementById('cYear').value;
		var rotNo = document.getElementById('rotNo').value;
		var blNo = document.getElementById('blNo').value;
		
		if(cNo=="" && cYear=="")
		{
			if(rotNo=="" && blNo=="")
			{
				alert("Please fill the form");
				return false;
			}
		}
		else if(rotNo=="" && blNo=="")
		{
			if(cNo=="" && cYear=="")
			{
				alert("Please fill the form");
				return false;
			}
		}
		else
			return true;
	}
	
	function confirmRelease()
	{
		if(confirm("Do you want to release?"))
		{
			return true;		
		}
		else
		{
			return false;
		}
	}
</script>
<style>
	 #table-scroll {
	  height:500px;
	  width: 1000px;
	 <!-- overflow:auto;  -->
	  margin-top:0px;
      }

</style>

<section role="main" class="content-body">
    <header class="page-header">
        <h2><?php echo $title;?></h2>
    </header>
	
	<div class="row">
		<div class="col-md-12">					
			<div class="panel-body">
				<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url("Report/dlvFromOffdockAction") ?>" onsubmit="return chkBlankField();">
					<div class="form-group">
						<div class="row">
							<div class="col-sm-12 text-center">
								<?php echo $msg; ?>
							</div>													
						</div>		
						<div class="col-md-6 col-md-offset-3">		
							<div class="input-group mb-md">
								<span class="input-group-addon span_width">B/E Number<span class="required">*</span></span>
								<input type="text" name="cNo" id="cNo" class="form-control login_input_text" autofocus= "autofocus" placeholder="B/E Number" />
							</div>												
						</div>	
						<div class="col-md-6 col-md-offset-3">		
							<div class="input-group mb-md">
								<span class="input-group-addon span_width">B/E Year<span class="required">*</span></span>
								<input type="text" name="cYear" id="cYear" class="form-control login_input_text" autofocus= "autofocus" placeholder="B/E Year"  />
							</div>												
						</div>	
						<div class="col-md-6 col-md-offset-3">		
							<div align="center">OR</div>						
						</div>
						<div class="col-md-6 col-md-offset-3">		
							<div class="input-group mb-md">
								<span class="input-group-addon span_width">Rotation No<span class="required">*</span></span>
								<input type="text" name="rotNo" id="rotNo" class="form-control login_input_text" autofocus= "autofocus" placeholder="Rotation No" />
							</div>
						</div>
						<div class="col-md-6 col-md-offset-3">		
							<div class="input-group mb-md">
								<span class="input-group-addon span_width">BL No<span class="required">*</span></span>
								<input type="text" name="blNo" id="blNo" class="form-control login_input_text" autofocus= "autofocus" placeholder="BL No" />
							</div>
						</div>
						<div class="row" id="applyBtn">
							<div class="col-sm-12 text-center">
								<button type="submit" name="btnSearch" class="mb-xs mt-xs mr-xs btn btn-primary">
									Search
								</button>
							</div>													
						</div>										
					</div>
				</form>
			</div>
		</div>
	</div>
</section>

<?php
if(count($rslt_igmInfo)>0)
{
?>
<section role="main" class="content-body">
	<div class="content">
		<div class="content_resize">
			<div class="mainbar">
				<div class="article">

					<div class="img">
					<!--<div id="login_container">-->
			 
						<div class="panel-body table-responsive">
							<!-- IGM Data -->
							<table class="table table-bordered" id="datatable-default">		
								<tr class="gridDark" align="center">	
									<td colspan="2"><b>Info</b></td>
								</tr>
								<tr align="left">
									<td width="50%">
										<b>Import Rotation :</b> <?php echo $rslt_igmInfo[0]['Import_Rotation_No']; ?>
										<br>
										<b>BL No :</b> <?php echo $rslt_igmInfo[0]['BL_No']; ?>
										<br>
										<b>Pack Number :</b> <?php echo $rslt_igmInfo[0]['Pack_Number']; ?>
										<br>
										<b>Pack Description :</b> <?php echo $rslt_igmInfo[0]['Pack_Description']; ?>
										<br>
										<b>Goods Description :</b> <?php echo $rslt_igmInfo[0]['Description_of_Goods']; ?>
									</td>
									<td width="50%">
										<?php
										for($i=0;$i<count($rslt_igmInfo);$i++)
										{
										?>
										<b>Container <?php echo $i+1; ?> :</b> <?php echo $rslt_igmInfo[$i]['cont_number']; ?><br>
										<?php
										}
										?>
									</td>									
								</tr>
							</table> 

							<!-- NTS Data -->
							<?php							
							if(count($rslt_ntsData)>0)
							{
							?>
							<table class="table table-bordered" id="datatable-default">		
								<tr class="gridDark" align="center">	
									<td><b>Verify No</b></td>
									<td><b>Verify Date</b></td>
									<td><b>CP No</b></td>
									<td><b>CP Date</b></td>
								</tr>
								<tr align="left">
									<td width="25%" align="center">
										<?php echo  $rslt_ntsData[0]['verify_no']; ?>
									</td>
									<td width="25%" align="center">
										<?php echo  $rslt_ntsData[0]['verify_date']; ?>
									</td>
									<td width="25%" align="center">
										<?php echo  $rslt_ntsData[0]['cp_no']; ?>
									</td>
									<td width="25%" align="center">
										<?php echo  $rslt_ntsData[0]['cp_date']; ?>
									</td>									
								</tr>
							</table>
							<?php
							}
							else
							{
							?>
							<table class="table table-bordered" id="datatable-default">		
								<tr class="gridDark" align="center">	
									<td><b>No NTS Data found</b></td>									
								</tr>								
							</table>
							<?php
							}
							?>
							
							<!-- Release Form -->
							<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('Report/releaseDlvCont'); ?>" onsubmit="return confirmRelease();">
								<div class="row" id="applyBtn">
									<div class="col-sm-12 text-center">
										<input type="hidden" name="rlsRotNo" id="rlsRotNo" value="<?php echo  $rslt_igmInfo[0]['Import_Rotation_No']; ?>" />
										<input type="hidden" name="rlsBlNo" id="rlsBlNo" value="<?php echo $rslt_igmInfo[0]['BL_No']; ?>" />
										<input type="hidden" name="igmId" id="igmId" value="<?php echo $igmId; ?>" />
										<input type="hidden" name="igmSupId" id="igmSupId" value="<?php echo $igmSupId; ?>" />
										<input type="hidden" name="releaseStat" id="releaseStat" value="<?php echo $releaseStat; ?>" />
										<button type="submit" name="btnRelease" class="mb-xs mt-xs mr-xs btn btn-success" <?php if(count($rslt_ntsData)==0 or $releaseStat>0){ ?>disabled<?php } ?>>
											Release
										</button>
									</div>													
								</div>
							</form>
						</div> 
					</div>

					<div class="clr"></div>
				</div>
			</div>
		</div>
	</div>
</section>
<?php
}
?>

