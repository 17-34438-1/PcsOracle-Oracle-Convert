<script type="text/javascript">
	function chk_user()
	{
		if( document.search_user.search_by.value == "")
		{
			alert( "Please provide search type!" );
			document.search_user.login_id_search.focus() ;
			return false;
		}
		else if( document.search_user.login_id_search.value == "" && document.search_user.org_type_id.value == "")
		{
			alert( "Please provide User or Org Type!" );
			document.search_user.login_id_search.focus() ;
			return false;
		}
		
		return true ;
	}

	function del_user()
	{
		if (confirm("Do you want to detete this entry?") == true)
		{
			return true ;
		}
		else
		{
			return false;
		}
	}

	function validate(){
		var login = "";
		login = document.getElementById("login").value.trim();

		if(login == "" || login == null){
			alert("Please enter Login Id!");
			return false;
		}
	}

</script>
<section role="main" class="content-body">
	<header class="page-header">
		<h2><?php  echo $title; ?></h2>
	</header>
    
	<div class="row">
			<div class="col-lg-12">						
				<section class="panel">
					<div class="panel-body">
						<form class="form-horizontal form-bordered" method="POST" action="<?php echo site_url("Report/userListSearch"); ?>" id="myform" name="myform" onsubmit="return validate()">
							<div class="form-group">
								<label class="col-md-3 control-label">&nbsp;</label>
								<input type="hidden" name="search" id="search" />
								<div class="col-md-6">		
									<div class="input-group mb-md">
										<span class="input-group-addon span_width">Login id <span class="required">*</span></span>
										<input type="text" name="login" id="login" class="form-control" placeholder="login id">
										<input type="hidden" name="listType" id="listType" value="<?php echo $listType ;?>">
									</div>
																					
								</div>
																				
								<div class="row">
									<div class="col-sm-12 text-center">
										<!--button type="button" class="mb-xs mt-xs mr-xs btn btn-primary">Primary</button-->
										<button type="submit" name="submit_login" class="mb-xs mt-xs mr-xs btn btn-success">Search</button>
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

	<div class="row">
		<div class="col-md-12">
			<section class="panel">
			
				<header class="panel-heading">			
					<h2 class="panel-title"><?php  echo $title; ?></h2>
				</header>				
				<div class="panel-body">
					<table class="table table-bordered table-striped mb-none" id="datatable-default">
						<thead>
							<tr>
								<th>Sl</th>
								<th>User</th>
								<th>Login ID</th>
								<th>Organization Name</th>
								<th>Created At</th>
								<th>Created By</th>
								<th>Signature</th>
								<th>Action</th>
								<th>Action</th>
								<th>Action</th>								
							</tr>
						</thead>
						<tbody>
						<?php
						for($i=0;$i<count($rslt_user_list);$i++)
						{
						?>
							<!--tr class="gradeX">
								<td><?php echo $i+1; ?></td>
								<td><?php echo $rslt_user_list[$i]['id']; ?></td>
								<td><?php echo $rslt_user_list[$i]['login_id']; ?></td>								
								<td>
									<form method='POST' action="<?php echo site_url('Report/rateAction'); ?>">
										<input type="hidden" name="gkey" value="<?php echo $rslt_user_list[$i]['gkey']; ?>">
										<input type="submit" name="editRate" value="Edit" class="btn btn-primary" style="width:60px;">
									</form>
								</td>								
							</tr--->
							
							<tr class="gradeX">
								<td width="10px" align="center">
									<?php echo $i+1; ?>
								</td>

								<td width="200px">
									<?php echo $rslt_user_list[$i]['u_name']; ?>
								</td>

								<td width="200px">
									<?php echo $rslt_user_list[$i]['login_id']; ?>
								</td>

								<td width="200px">
									<?php echo $rslt_user_list[$i]['Organization_Name']; ?>
								</td>
								<td width="200px">
									<?php echo $rslt_user_list[$i]['entrydate']; ?>
								</td>
								<td width="200px">
									<?php echo $rslt_user_list[$i]['entry_by']; ?>
								</td>
								<td align="center"  width="200px">
									<img align="middle" width="100px" height="30px" src="<?php echo IMG_PATH?><?php echo $rslt_user_list[$i]['image_path']; ?>" >
								</td>

								<td align="center"  width="200px">
									<?php
										if($listType == "active"){
									?>
										<form name="editUser" id="editUser" action="<?php echo site_url('Report/UserStsCng');?>" method="post">
											<input type="hidden" name="login_id" id="login_id" value="<?php echo $rslt_user_list[$i]['login_id'] ;?>">
											<input type="hidden" name="listType" id="listType" value="<?php echo $listType ;?>">
											<input type="submit" value="Inactive" name="status" class="mb-xs mt-xs mr-xs btn btn-warning">
										</form>
									<?php
										}else{
									?>
										<form name="editUser" id="editUser" action="<?php echo site_url('Report/UserStsCng');?>" method="post">
											<input type="hidden" name="login_id" id="login_id" value="<?php echo $rslt_user_list[$i]['login_id'] ;?>">
											<input type="hidden" name="listType" id="listType" value="<?php echo $listType ;?>">
											<input type="submit" value="Active" name="status" class="mb-xs mt-xs mr-xs btn btn-success">
										</form>
									<?php
										}
									?>
								</td>

								<td>
									<form name="editUser" id="editUser" action="<?php echo site_url('Report/editUser');?>" method="post">
										<input type="hidden" name="id_edit" id="id_edit" value="<?php echo $rslt_user_list[$i]['id'] ;?>">
										<input type="hidden" name="login_id_edit" id="login_id_edit" value="<?php echo $rslt_user_list[$i]['login_id'];?>">
										<input type="submit" value="Edit" name="edit" class="mb-xs mt-xs mr-xs btn btn-primary">
									</form>
								</td>

								<td>
									<form name="deleteUser" id="deleteUser" action="<?php echo site_url("Report/deleteUser");?>" onsubmit="return(del_user());" method="post">
										<input type="hidden" name="id_delete" id="id_delete" value="<?php echo $rslt_user_list[$i]['id'] ;?>">
										<input type="hidden" name="login_id_delete" id="login_id_delete" value="<?php echo $rslt_user_list[$i]['login_id'];?>">
										<input type="submit" value="Delete" name="delete" class="mb-xs mt-xs mr-xs btn btn-danger">
									</form>
								</td>

							</tr>
						<?php
						}
						?>
						</tbody>
					</table>
				</div>
			</section>
		</div>
	</div>
</section>
