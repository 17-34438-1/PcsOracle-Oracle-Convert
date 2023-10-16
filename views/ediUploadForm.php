<script type="text/javascript">
function validate()
{
	if(document.myform.rotation.value == "")
	{
		alert( "Please provide rotation!" );
		document.myform.rotation.focus() ;
		return false;
	}
	
	if(document.myform.imp_voyage.value == "")
	{
		alert( "Please provide IMP Voyage!" );
		document.myform.imp_voyage.focus() ;
		return false;
	}
	
	if(document.myform.exp_voyage.value == "")
	{
		alert( "Please provide EXP Voyage!" );
		document.myform.exp_voyage.focus() ;
		return false;
	}
	
	if(document.myform.grt.value == "")
	{
		alert( "Please provide GRT!" );
		document.myform.grt.focus() ;
		return false;
	}
	
	if(document.myform.nrt.value == "")
	{
		alert( "Please provide NRT!" );
		document.myform.nrt.focus() ;
		return false;
	}
	
	if(document.myform.imo_no.value == "")
	{
		alert( "Please provide IMO No.!" );
		document.myform.imo_no.focus() ;
		return false;
	}
	
	if(document.myform.loa.value == "")
	{
		alert( "Please provide LOA!" );
		document.myform.loa.focus() ;
		return false;
	}
	
	if(document.myform.flag.value == "")
	{
		alert( "Please provide Flag!" );
		document.myform.flag.focus() ;
		return false;
	}
	
	if(document.myform.call_sign.value == "")
	{
		alert( "Please provide Call Sign!" );
		document.myform.call_sign.focus() ;
		return false;
	}
	
	/*if(document.myform.beam.value == "")
	{
		alert( "Please provide Beam!" );
		document.myform.beam.focus() ;
		return false;
	}*/
	
	if(document.myform.edi.value == "")
	{
		alert( "Please provide EDI file!" );
		document.myform.edi.focus() ;
		return false;
	}
	else
	{
		var file_name=document.myform.edi.value;
	
		var ext=file_name.split('.').pop();
	
		if(ext.toUpperCase()!="EDI")
		{
			alert("File extension should be .edi");
			return false;
		}		
	}
	
	/*if(document.myform.excel.value == "")
	{
		alert( "Please provide Stow file!" );
		document.myform.excel.focus() ;
		return false;
	}*/
	
	return true ;
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
						<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url('uploadExcel/ediUploadPerform');?>" target="_blank" id="myform" name="myform" enctype="multipart/form-data" onsubmit="return validate()">
							<div class="form-group">
								<label class="col-md-3 control-label">&nbsp;</label>
								<div class="col-md-6">	
                                    <?php
										include("mydbPConnection.php");
										$str_info="SELECT Import_Rotation_No,Voy_No,VoyNoExp,Vessel_Name,grt,nrt,imo,loa_cm,flag,radio_call_sign,beam_cm 
										FROM igm_masters WHERE id='$id'";
										
										$rslt_info=mysqli_query($con_cchaportdb,$str_info);
										$row=mysqli_fetch_object($rslt_info);
                                        $rotation = "";
                                        $voyImp = "";
                                        $voyExp = "";
                                        $vslName = "";		// intakhab - 2022-06-11
                                        $grt = "";
                                        $nrt = "";
                                        $imo = "";
                                        $loa = "";
                                        $flag = "";
                                        $call_sign = "";
                                        $beam = "";
                                        
                                        if(count($row)>0){
                                            $rotation = $row->Import_Rotation_No;
                                            $voyImp = $row->Voy_No;
                                            $voyExp = $row->VoyNoExp;
                                            $vslName = $row->Vessel_Name;		// intakhab - 2022-06-11
                                            $grt = $row->grt;
                                            $nrt = $row->nrt;
                                            $imo = $row->imo;
                                            $loa = $row->loa_cm;
                                            $flag = $row->flag;
                                            $call_sign = $row->radio_call_sign;
                                            $beam = $row->beam_cm;
                                        }
									?>	
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Rotation <span class="required">*</span></span>
										<input type="text" name="rotation" id="rotation" class="form-control" value="<?php echo $rotation;?>">
									</div>
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">IMP Voyage <span class="required">*</span></span>
										<input type="text" name="imp_voyage" id="imp_voyage" class="form-control" value="<?php echo $voyImp;?>">
									</div>
                                    <div class="input-group mb-md">
										<span class="input-group-addon span_width">EXP Voyage <span class="required">*</span></span>
										<input type="text" name="exp_voyage" id="exp_voyage" class="form-control" value="<?php echo $voyExp;?>">
									</div>
									<div class="input-group mb-md">	<!-- intakhab - 2022-06-11 -->
										<span class="input-group-addon span_width">Vessel Name <span class="required">*</span></span>
										<input type="text" name="vslName" id="vslName" class="form-control" value="<?php echo $vslName;?>">
									</div>
                                    <div class="input-group mb-md">
										<span class="input-group-addon span_width">GRT <span class="required">*</span></span>
										<input type="text" name="grt" id="grt" class="form-control" value="<?php echo $grt;?>">
									</div>	
                                    <div class="input-group mb-md">
										<span class="input-group-addon span_width">NRT <span class="required">*</span></span>
										<input type="text" name="nrt" id="nrt" class="form-control" value="<?php echo $nrt;?>">
									</div>	
                                    <div class="input-group mb-md">
										<span class="input-group-addon span_width">IMO No. <span class="required">*</span></span>
										<input type="text" name="imo_no" id="imo_no" class="form-control" value="<?php echo $imo;?>">
									</div>
                                    <div class="input-group mb-md">
										<span class="input-group-addon span_width">LOA <span class="required">*</span></span>
										<input type="text" name="loa" id="loa" class="form-control" value="<?php echo $loa;?>">
									</div>
                                    <div class="input-group mb-md">
										<span class="input-group-addon span_width">Flag <span class="required">*</span></span>
										<input type="text" name="flag" id="flag" class="form-control" value="<?php echo $flag;?>">
									</div>
                                    <div class="input-group mb-md">
										<span class="input-group-addon span_width">Call Sign <span class="required">*</span></span>
										<input type="text" name="call_sign" id="call_sign" class="form-control" value="<?php echo $call_sign;?>">
									</div>
                                    <div class="input-group mb-md">
										<span class="input-group-addon span_width">Beam <span class="required">*</span></span>
										<input type="text" name="beam" id="beam" class="form-control" value="<?php echo $beam;?>">
									</div>
                                    <div class="input-group mb-md">
										<span class="input-group-addon span_width">Browse EDI File <span class="required">*</span></span>
										<input type="file" name="edi" id="edi" class="form-control" value="<?php echo $voyImp;?>" required>
									</div>
                                    <div class="input-group mb-md">
										<span class="input-group-addon span_width">Browse Stow File <span class="required">*</span></span>
										<input type="file" name="excel" id="excel" class="form-control" value="<?php echo $voyImp;?>" >
									</div>
								</div>
																				
								<div class="row">
									<div class="col-sm-12 text-center">
										<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
										<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">Upload</button>
									</div>													
								</div>
								<div class="row">
									<div class="col-sm-12 text-center">
										<?php echo $msg;?>
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