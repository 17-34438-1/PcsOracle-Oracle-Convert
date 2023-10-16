
<form method="POST" enctype="multipart/form-data" action="<?php echo site_url("Login/UserOtpVerification") ?>">

<div class="modal-header">
    <div class="modal-title" id="exampleModalLabel" style="color: #0dce0f; font-size:18px; font-family: Impact, Charcoal, sans-serif; text-transform: uppercase;">
        <img style="margin-top: 10px;margin-left:48%;" src="<?php echo ASSETS_WEB_PATH?>fimg/logocpa.png" height="50px" width="50px" alt="Logo"><br/>
        <span style="margin-left:39%;">Port Community System Registration</span>
        <!-- <span font-size:16px; color:red;font-family: 'Courier New', monospace;">Registration</span> -->
    
    </div>
</div>

<!--div class="modal-body"-->
<div class="row">
    <div class="text-center">
    <?php echo $msg; ?>
    </div>
   
    <div class="text-center" style="display:none">
    <?php 
   //echo $msg;
    ?>
    </div>
   
   
    
</div>
<div class="row">
    <div class="col-sm-4 col-md-offset-4">
        <div class="input-group mb-md" style="display:none">
            <span class="input-group-addon span_width">Phone Number : </span>
            <input type="hidden"  id="phone_number" name="phone_number" class="form-control" value="<?php echo $phone_number; ?>">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4 col-md-offset-4">
        <div class="input-group mb-md" style="display:none">
            <span class="input-group-addon span_width">User Id : </span>
            <input type="hidden"  id="user_id" name="username" class="form-control" value="<?php echo $user_id; ?>">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4 col-md-offset-4">
        <div class="input-group mb-md" style="display:none">
            <span class="input-group-addon span_width">User Password : </span>
            <input type="hidden"  id="user_id" name="password" class="form-control" value="<?php echo $user_password; ?>">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-4 col-md-offset-4">
        <div class="input-group mb-md">
            <span class="input-group-addon span_width">Verify Code: </span>
            <input type="text"  id="varifycode" class="form-control" name="varifycode"/>
            
        </div>
      
    </div>
</div>
</div>

<div class="modal-footer">
    <div class="row">
        

        <div class="col-sm-12 text-center" id="d_value">
        <input type="submit" class="btn btn-success btn-xs" name="btnadd" value="Next"/>

        <!-- <li data-toggle="modal" data-target="#register2" onclick="changeTextBo(this.value);">Register</li> -->
        <!-- <li data-toggle="modal" data-target="#register2" data-dismiss="modal" class="btn btn-success btn-xs">Send OTP</li> -->
        </div>
        
       
    </div>
    
</div>

 
 
  
   
</form>