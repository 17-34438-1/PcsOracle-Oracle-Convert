<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php echo $title;?></h2>
	</header>

	<div class="row">
		<div class="col-lg-12">						
			<section class="panel">
				<div class="panel-body">
					<form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/IgmViewController/viewIGMList'; ?>" target="_blank" id="myform" name="myform" onsubmit="return validate()">
						<div class="form-group">
							<label class="col-md-3 control-label">&nbsp;</label>
							<div class="col-md-6">	
								<?php if($login_id!='cpaops') { ?>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Rotation No <span class="required">*</span></span>
									<input type="text" name="impno" id="impno" class="form-control" placeholder="Rotation No" onkeyup="valid(this.value)" value="<?php print(@$this->impno); ?>">
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Line No <span class="required">*</span></span>
									<input type="text" name="lineno" id="lineno" class="form-control" placeholder="Line No" value="<?php print(@$this->lineno); ?>">
								</div>	
								
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">IGM Type <span class="required">*</span></span>
									<input type="radio" name="options" value="igm">Main lineno
									<input type="radio" name="options" value="suppl">Supplementary
								</div>
								
								<div class="input-group mb-md">
									<span class="">OR </span>
								</div>
								<?php } ?>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">Rotation No <span class="required">*</span></span>
									<input type="text" name="impno1" id="impno1" class="form-control" placeholder="Rotation No" onkeyup="valid(this.value)" value="<?php print(@$this->impno1); ?>">
								</div>
								<div class="input-group mb-md">
									<span class="input-group-addon span_width">BL No <span class="required">*</span></span>
									<input type="text" name="blno" id="blno" class="form-control" placeholder="BL No" value="<?php if(@$this->blno) print(@$this->blno); ?>">
								</div>
							</div>
																			
							<div class="row">
								<div class="col-sm-12 text-center">
									<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
									<button type="submit" name="Add" class="mb-xs mt-xs mr-xs btn btn-success">View</button>
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

<section>