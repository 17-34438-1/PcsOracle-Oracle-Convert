
<script>       
    function validate() 
    {
        var phone_number = document.getElementById('phone_number').value;
        if(document.getElementById('phone_number').value=="")
		{
            alert("Please fill Up Phone_number field");	
            return false;
		}

        
        // if(phone_number >= 12){
        //     //b="invalid Input";
        //     alert("Please give 11 digit Phone Number");	
        //      return false; 
        // }
        
       
    }
    // function phoneno(){
    //     // alert("hellow");
    //             var phone_number = document.getElementById('phone_number').value;
    //     if(phone_number.value.length!=11){
    //         alert('Hellow');
    
    //     // message.innerHTML = "required 10 digits, match requested format!"
    //     }

    // }
   

    // function phoneno(phoneNumber) {
    //     var phoneno = /^\d{10}$/;
    //     if((inputtxt.value.match(phoneno))
    //     {
    // return true;
    //     }
    // else
    //     {
    //         alert("Please enter a valid phone number.");
    //         return false;
    //     }
    // }

</script>

<form method="POST" name="form1" enctype="multipart/form-data" action="<?php echo site_url("Login/VerificationPreparation") ?>"  onsubmit="return validate();">

    <div class="modal-header">
        <div class="modal-title" id="exampleModalLabel" style="color: #0dce0f; font-size:18px; font-family: Impact, Charcoal, sans-serif; text-transform: uppercase;">
            <img style="margin-top: 10px;margin-left:48%;" src="<?php echo ASSETS_WEB_PATH?>fimg/logocpa.png" height="50px" width="50px" alt="Logo"><br/>
            <span style="margin-left:39%;">Port Community System Registration</span>
            <!-- <span font-size:16px; color:red;font-family: 'Courier New', monospace;">Registration</span> -->
        
        </div>
    </div>

    <div class="modal-body" id="b_value">
        <div class="row">
            <div class="col-md-4 col-md-offset-4" >
                <div class="input-group mb-md">
                    <span class="input-group-addon span_width">PhonNumber : </span>
                    <input type="text" name="phone_number" id="phone_number" pattern="[0-9]+" title="numbers only, 11 digit" class="form-control" placeholder="Type Your Phone Number" minlength="11" maxlength="11"/>
                </div>
                
            </div>
        </div>
    </div>

    <div class="modal-footer">
            <div class="row">
                <div class="col-sm-6 text-right" id="d_value">
                <input type="submit" class="btn btn-success btn-xs" name="btnadd" value="Next"/>
            
            </div>
            <div class="row">
                    <div class="col-sm-6 text-left">
                    <a href='http://cpatos.gov.bd/pcs/' class="button" class="btn btn-secondary btn-xs"  style="text-decoration: none;">Close</a>
                        <!-- <button  onclick="document.location=">Close</button> -->
                    </div>
            </div>
            <div class="row">
                <div class="text-center">
                    <?php echo $msg;?>
                </div>
            </div>
    </div>
</form>