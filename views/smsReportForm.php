
<script type="text/javascript">


</script>
<style>
     #table-scroll {
	  height:500px;
	  overflow:auto;  
	  margin-top:0px;
      }
</style>
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
  <form method="POST" enctype="multipart/form-data"  target="_blank"  action="<?php echo site_url("Report/smsReportView") ?>">
     
	<div class="row row-centered">
              <div class="col-md-4">	
              </div>
              <div class="col-md-4">	
              <div class="input-group mb-md">
                    <span class="input-group-addon span_width" class="form-control">Date:</span>
                    <input type="date" class="form-control" id="date" name="date"  />
              </div>
              </div>
              <div class="col-md-4">		
                 	
             </div>
           	<div class="row">
                  <div class="col-sm-12 text-center">
                    <!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
                    <!--button type="submit" class="mb-xs mt-xs mr-xs btn btn-success">Save</button-->
			<input type="submit" target="_blank" class="mb-xs mt-xs mr-xs btn btn-success" value="View">
				
                  </div>													
              </div>
  </div>
  </form>
	
</section>