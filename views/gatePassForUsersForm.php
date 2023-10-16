
<script>       
    function validate() 
    {
        var phone_number = document.getElementById('phone_number').value;
        if(document.getElementById('phone_number').value=="")
		{
            alert("Please fill Up Phone_number field");	
            return false;
		}
    }
</script>

<form method="POST" name="form1" enctype="multipart/form-data" action="<?php echo site_url("ShedBillController/gatePassForUsers") ?>"  onsubmit="return validate();">

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
                    <span class="input-group-addon span_width">Ticket id : </span>
                    <input type="text" name="visit_id" id="visit_id" class="form-control" placeholder="Type Your ticket id"/>
                </div>
                
            </div>
        </div>
    </div>

    <div class="modal-footer">
            <div class="row">
                <div class="text-center" id="d_value">
                    <input type="submit" class="btn btn-success btn-md" name="pass" value="Gate Pass"/>
                </div>
            </div>
            <!-- <div class="row">
                    <div class="col-sm-6 text-left">
                    <a href='http://localhost/pcsTest/' class="button" class="btn btn-secondary btn-md"  style="text-decoration: none;">Close</a>
                    </div>
            </div> -->
            <div class="row">
                <div class="text-center">
                    <?php echo $msg;?>
                </div>
            </div>
    </div>
</form>