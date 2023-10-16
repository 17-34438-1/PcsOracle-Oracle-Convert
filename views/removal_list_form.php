 <script type="text/javascript">
   
	function validate()
	{
		if( document.removal_list_form.assignment_date.value == "" )
		{
			alert( "Please provide Assignment Date!" );
			document.removal_list_form.assignment_date.focus() ;
			return false;
		}
		return true ;
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
                     <form class="form-horizontal form-bordered" method="POST" action="<?php echo base_url().'index.php/Report/removal_list_report'; ?>" target="_blank" id="myform" name="myform" onsubmit="return(validate());">
                         <div class="form-group">
                             <label class="col-md-3 control-label">&nbsp;</label>
                             <div class="col-md-6">
                                 <div class="input-group mb-md">
                                     <span class="input-group-addon span_width">Assignment Date <span class="required">*</span></span>
                                     <input type="date" name="assignment_date" id="assignment_date" class="form-control" value="<?php date("Y-m-d"); ?>">
                                     <input type="hidden" id="modify" name="modify" class="form-control" value="<?php echo $modify; ?>"/>
                                 </div>

                             </div>
                             <div class="col-md-offset-4 col-md-2">
                                 <div class="radio-custom radio-success">
                                     <input type="radio" id="options" name="options" value="pdf" checked>
                                     <label for="radioExample3">PDF</label>
                                 </div>
                             </div>
                             <div class="col-md-2">
                                 <div class="radio-custom radio-success">
                                     <input type="radio" id="options" name="options" value="excel">
                                     <label for="radioExample3">Excel</label>
                                 </div>
                             </div>
                             <div class="col-md-2">
                                 <div class="radio-custom radio-success">
                                     <input type="radio" id="options" name="options" value="html" checked>
                                     <label for="radioExample3">HTML</label>
                                 </div>
                             </div>

                             <div class="row">
                                 <div class="col-sm-12 text-center">
                                     <!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
                                     <button type="submit" id="submit" name="detail" class="mb-xs mt-xs mr-xs btn btn-success login_button">View</button>
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