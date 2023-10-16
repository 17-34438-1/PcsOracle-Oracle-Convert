<script>
function startTimer(duration, display)
 {
    var timeout = document.getElementById("timeout");
    var tm = document.getElementById("timer");    
    var timer = duration, minutes, seconds;
    var a=setInterval(function () {
    minutes = parseInt(timer / 60, 10);
    seconds = parseInt(timer % 60, 10);
    minutes = minutes < 10 ? "0" + minutes : minutes;
    seconds = seconds < 10 ? "0" + seconds : seconds;
    if(display.textContent!="00:00"|| duration<=0 ){
        display.textContent = minutes + ":" + seconds;

        if (--timer < 0) {
            timer = duration;
        }
    }
    else
        {
            clearInterval(a);
            tm.style.display="none";
            timeout.style.display="block";
          
        }
    }, 1000);
};

window.onload = function () {

  
    var timeout = document.getElementById("timeout");
    var tm = document.getElementById("timer"); 
    display = document.querySelector('#time');
    var validity_time ="<?php echo $validity_time;?>";
    var timestamp =+ new Date();
    var date = new Date(timestamp);
    var year = date.getFullYear();
    var month = date.getMonth() + 1;
    var day = date.getDate();
    var hours = date.getHours();
    var minutes = date.getMinutes();
    var seconds = date.getSeconds();
    var latest=year + "-" + month + "-" + day + " " + hours + ":" + minutes + ":" + seconds;
    v_year=parseInt(validity_time.substring(0,4) );
    v_month=parseInt(validity_time.substring(5,7) );
    v_day=parseInt(validity_time.substring(8,10) );
    v_hour= parseInt(validity_time.substring(11, 13) );
    v_minute= parseInt(validity_time.substring(14, 16) );
    v_second= parseInt(validity_time.substring(17, 19) );

    if(year==v_year && month==v_month && day==v_day && hours<=v_hour)//&& hours<=v_hour && minutes<=v_minute
    {
        var count=0;
        var sec=0;
        var  min=0;
        min=((v_hour*60+v_minute)-(hours*60+minutes))*60;
       if(seconds > v_second)
       {
         sec =seconds-v_second;
         count=min-sec;
           
       }
       else if(seconds <= v_second)
       {
         sec =v_second-seconds ;
         count=min+sec;
       }

       if(count<=0)
       {
        tm.style.display="none";
        timeout.style.display="block";

       }
       else{
        startTimer(count, display);

       }
      
        
       // startTimer(count, display);

    }
    else
    {    
        tm.style.display="none";
        timeout.style.display="block";
    }
};

</script>

<form method="POST" enctype="multipart/form-data" action="<?php echo site_url("Login/VerificationPerform") ?>">

    <div class="modal-header">
        <div class="modal-title" id="exampleModalLabel" style="color: #0dce0f; font-size:18px; font-family: Impact, Charcoal, sans-serif; text-transform: uppercase;">
            <img style="margin-top: 10px;margin-left:48%;" src="<?php echo ASSETS_WEB_PATH?>fimg/logocpa.png" height="50px" width="50px" alt="Logo"><br/>
            <span style="margin-left:39%;">Port Community System Registration</span>
            <!-- <span font-size:16px; color:red;font-family: 'Courier New', monospace;">Registration</span> -->
        
        </div>
    </div>

    <!--div class="modal-body" id="b_value" -->
    <div class="row">
        <div class="text-center">
        <?php echo $msg; ?>
        </div>
       
        <div class="text-center" style="display:none">
        <?php 
        echo $validity_time;
        ?>
        </div>
       
       
        
    </div>
    <div class="row">
        <div class="col-sm-4 col-md-offset-4">
            <div class="input-group mb-md" style="display:none;">
                <span class="input-group-addon span_width">Phone Number : </span>
                <input type="text"  id="phone_number" name="phone_number" class="form-control" value="<?php echo $phone_number; ?>">
            </div>
        </div>
    </div>
   
    <div class="row">
        <div class="col-sm-4 col-md-offset-4">
            <div class="input-group mb-md">
                <span class="input-group-addon span_width">Verify Code: </span>
                <input type="text"  id="varifycode" class="form-control" name="varifycode"/>
                <input type="hidden"  id="st" name="st" value="" class="form-control" name="varifycode"/>
            </div>
            <div  class="col-sm-12 text-center">
            <div id="timer">Registration closes in <span id="time">05:00</span> minutes!</div>
            <div id="timeout" style="display:none">Verification process time is out,try again</div>
            </div>
        </div>
    </div>
</div>

    <div class="modal-footer">
        <div class="row">
            
    
            <div class="col-sm-6 text-right" id="d_value">
            <input type="submit" class="btn btn-success btn-xs" name="btnadd" value="Next"/>

            <!-- <li data-toggle="modal" data-target="#register2" onclick="changeTextBo(this.value);">Register</li> -->
            <!-- <li data-toggle="modal" data-target="#register2" data-dismiss="modal" class="btn btn-success btn-xs">Send OTP</li> -->
            </div>
            
            
            <div class="col-sm-6 text-left">
            <a href="http://cpatos.gov.bd/pcs/" class="button" class="btn btn-secondary btn-xs"  style="text-decoration: none;">Close</a>
            </div>
        </div>
        
    </div>
   
     
     
      
       
</form>
				

