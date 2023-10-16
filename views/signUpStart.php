
<script>       

function closeWin() {
  myWindow.close();
}
      

function validate() {

   
    var userName = document.getElementById("username").value;
    var userNID = document.getElementById("userNID").value;
    var nid_front_image = document.getElementById("nid_front_image").value;
    var nid_back_image = document.getElementById("nid_back_image").value;
    var user_picture = document.getElementById("user_picture").value;
    var userPnone = document.getElementById("userPnone").value;
    var userPin = document.getElementById("userPin").value;
    var userConfirmPin = document.getElementById("userConfirmPin").value;
     
    // if( userName == "" || userNID=="" || nid_front_image  == "" || nid_back_image=="" || user_picture== "" ||  userPnone=="" || userPin=="" || userConfirmPin=="" )
    if( userName == "" || userNID=="" || userPnone=="" || userPin=="" || userConfirmPin=="" )
    {
        alert("Fill All The Field");
        return false;
    }
    else
    {
      // if(userNID=>10 && userNID<=17)
       //{ 
        if( userPin.length >= 4 ) 
        {
            if(userConfirmPin==userPin)
            {
                return true;
            }
            else
            {
                alert("Pin And Confrimation Pin is not Matching");
                return false;
            } 
        }
        else
        {
                alert("Pin Should Be Atleast 4 Digits");
                return false;
        }
       //}
       /*else
       {
           if(userNID<10)
           {
               alert("Nid number must be atleast 10 digits");
               return false; 
           }
           else if(userNID>17 )
           {
               alet("Nid number is not more than 17 digits ");
               return false;
           }

       }*/
     

    }
}

</script>

<div class="row">
  <div class="col-lg-12">						
	<section class="panel">
		<div class="panel-body">
          <form method="POST" enctype="multipart/form-data" action="<?php echo site_url("Login/registrationPerformed") ?>" onsubmit="return validate();">
        
            <div class="modal-header">
                <div class="modal-title" id="exampleModalLabel" style="color: #0dce0f; font-size:18px; font-family: Impact, Charcoal, sans-serif; text-transform: uppercase;">
                    <img style="margin-top: 10px;margin-left:48%;" src="<?php echo ASSETS_WEB_PATH?>fimg/logocpa.png" height="50px" width="50px" alt="Logo"><br/>
                    <span style="margin-left:39%;">Port Community System Registration</span>
                    <!-- <span font-size:16px; color:red;font-family: 'Courier New', monospace;">Registration</span> -->
                
                </div>
            </div>
           
    <div class="row">
        <div class="col-sm-4 col-md-offset-4">
            <div class="row">
                <div class="text-center">
                    <?php echo $msg; ?>
                </div>
            </div>

            <div class="input-group mb-md">
                <span class="input-group-addon span_width">Name : </span>
                <input type="text" name="userName" id="username" class="form-control" value="" placeholder="Type Your Name" autofocus>
            </div>
            <div class="input-group mb-md">
                <span class="input-group-addon span_width">User Id : </span>
                <input type="text" name="phoneNo" id="phoneNo" class="form-control" value="<?php echo $phone_number; ?>" readonly>
            </div>
            <div class="input-group mb-md">
                <span class="input-group-addon span_width">NID : </span>
                <input type="number" name="userNID" id="userNID" class="form-control" value="" placeholder="Type Your NID" autofocus>
            </div>
            <div class="input-group mb-md">
                <span class="input-group-addon span_width">NID Front Image : </span>
                <input type="file"  id="nid_front_image" name="nid_front_image" class="form-control" value="" >
            </div>
            <div class="input-group mb-md">
                <span class="input-group-addon span_width">NID Back Image: </span>
                <input type="file" id="nid_back_image" name="nid_back_image" class="form-control" value="" >
            </div>
            <div class="input-group mb-md">
                <span class="input-group-addon span_width">User Picture: </span>
                <input type="file" id="user_picture" name="user_picture" class="form-control" value="" >
            </div>
            <div class="input-group mb-md">
                <span class="input-group-addon span_width">UserPhone : </span>
                
                <input type="text" name="userPnone" id="userPnone" pattern="[0-9]+" title="numbers only, 11 digit" class="form-control" placeholder="Type Your Phone Number" minlength="11" maxlength="11"/>
            
                <!-- <input type="text" name="phonNumber" id="username" class="form-control" placeholder="Type Your Phon Number" autofocus> -->
            </div>
            <div class="input-group mb-md">
                <span class="input-group-addon span_width">Pin : </span>
                <input type="password" name="userPin" id="userPin" class="form-control" value="" placeholder="Type Your Pin " autofocus>
            </div>
            <div class="input-group mb-md">
                <span class="input-group-addon span_width">Confirm Pin : </span>
                <input type="password" name="userConfirmPin" id="userConfirmPin" class="form-control" value="" placeholder="Type Your Confirm Pin" autofocus>
            </div>
            
        </div>
    </div>



        <div class="row">
            
            <div class="col-sm-12 text-center" id="d_value">
                <button type="submit" class="btn btn-success ">Save</button>

                <!-- <li data-toggle="modal" data-target="#register2" onclick="changeTextBo(this.value);">Register</li> -->
                <!-- <li data-toggle="modal" data-target="#register2" data-dismiss="modal" class="btn btn-success btn-xs">Send OTP</li> -->
            </div>
                
        </div>
    </div>
</form>
</div>
</section>
</div>
				

