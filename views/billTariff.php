<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
          
<div class="row">
	<div class="col-md-8">
		<!--form id="form1" class="form-horizontal"-->
		<form name="myForm" class="form-horizontal" id="myForm" action="<?php echo site_url("ShedBillController/getBillTariff");?>" method="post">
			<section class="panel">
				<header class="panel-heading">
					<h2 class="panel-title">Bill Tarrif Form</h2>					
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
						<label class="col-sm-3 control-label">ID:</label>
						<label class="col-sm-1 control-label">:</label>
						<div class="col-sm-8">
							<input type="text" name="id" id="id" class="form-control" value="<?php if(isset($_POST['editTarrif'])){echo $result[0]['id'];} ?>" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Description</label>
						<label class="col-sm-1 control-label">:</label>
						<div class="col-sm-8">
							<input type="text" id="desc" name="desc" value="<?php if(isset($_POST['editTarrif'])){echo $result[0]['description'];} ?>" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Long Description</label>
						<label class="col-sm-1 control-label">:</label>
						<div class="col-sm-8">
							<input type="text" id="ldesc" name="ldesc" value="<?php if(isset($_POST['editTarrif'])){echo $result[0]['long_description'];} ?>" class="form-control">
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
				</div>				
				<footer class="panel-footer" align="center">
				<?php
						if(isset($_POST['editTarrif']))
						{
							echo "<input type='hidden' name='gkey' value='".$result[0]['gkey']."'><input type='submit' name='update' value='Update' class='btn btn-primary' />";
						}
						else
						{
							echo "<input type='submit' name='save' value='Save' class='btn btn-primary' />";
						}
					?>


					<!-- <button class="btn btn-primary" type="submit" name="save">Save</button> -->
				</footer>
			</section>
		</form>
	</div>
</div>
