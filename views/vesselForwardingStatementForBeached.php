<table border="0" align="center" width="100%" style="border-collapse:collapse;">
    <tr>
        <td align="center" width="70%">
            <h1>CHATTOGRAM PORT AUTHORITY</h1>
            <h2>(MARINE DEPARTMENT)</h2>
            <h2>STATEMENT SHOWING THE PARTICULARS OF SHIPS BEACHED FROM OUTER ANCHORAGE WITHOUT ENTERING IN TO HARBOUR DURING THE MONTH 
            <?php 
                //echo $filedt;
                $time=strtotime($filedt);
                echo $month = numberToMonth(substr($filedt,3,2))."/";
                echo $year = substr($filedt,6,4);
            ?>
            ( __ - __ )
            </h2>
        </td>
    </tr>	
</table>

<table border="1" style="border-collapse:collapse;margin-top:12px;" align="center" width="100%">				
    <thead>
        <tr>
            <th rowspan="2" class="text-center"><h2>Sl</h2></th>
            <th rowspan="2" class="text-center" width="20%"><h2>Vessel Name</h2></th>
            <th rowspan="2" class="text-center"><h2>Date of Arrival</h2></th>
            <th rowspan="2" class="text-center"><h2>Date of Departure</h2></th>		
            <th rowspan="2" class="text-center"><h2>Rotation</h2></th>
            <th rowspan="2" class="text-center"><h2>Country</h2></th>
            <th colspan="2" class="text-center"><h2>Tonage</h2></th>   
            <th rowspan="2" class="text-center"><h2>Local Agent</h2></th>      
            <th rowspan="2" class="text-center"><h2>Forwarded to Acc at</h2></th> 
        </tr>
        <tr>
            <th><h2>GT</h2></th>
            <th><h2>NT</h2></th>
        </tr>
    </thead>

<?php 
    for($i=0;$i<count($departData);$i++){
?>
    <tr align="center">
        <td align="center" style="font-size: 12pt;"><?php echo $i+1;?></td>
        <td align="center" style="font-size: 12pt;"><?php echo $departData[$i]['vsl_name'];?></td>
        <td align="center" style="font-size: 12pt;"><?php echo $departData[$i]['ata'];?></td>
        <td align="center" style="font-size: 12pt;"><?php echo $departData[$i]['atd'];?></td>	
        <td align="center" style="font-size: 12pt;"><?php echo $departData[$i]['ib_vyg'];?></td>
        <td align="center" style="font-size: 12pt;"><?php echo $departData[$i]['cntry_name'];?></td>
        <td align="center" style="font-size: 12pt;"><?php echo $departData[$i]['grt'];?></td>
        <td align="center" style="font-size: 12pt;"><?php echo $departData[$i]['nrt'];?></td>
        <td align="center" style="font-size: 12pt;"><?php echo $departData[$i]['name'];?></td>
        <td align="center" style="font-size: 12pt;"><?php echo $departData[$i]['forwarded_dt'];?></td>
    </tr>
<?php 
    if($i>1 && $i%11 == 0 )
    {
?>
        </table>
        <!-- <pagebreak> -->
        <addpage/>

        <table border="0" align="center" width="100%" style="border-collapse:collapse;">
            <tr>
                <td align="center" width="70%">
                    <h1>CHATTOGRAM PORT AUTHORITY</h1>
                    <h2>(MARINE DEPARTMENT)</h2>
                    <h2>STATEMENT SHOWING THE PARTICULARS OF SHIPS BEACHED FROM OUTER ANCHORAGE WITHOUT ENTERING IN TO HARBOUR DURING THE MONTH 
                    <?php 
                        //echo $filedt;
                        $time=strtotime($filedt);
                        echo $month = numberToMonth(substr($filedt,3,2))."/";
                        echo $year = substr($filedt,6,4);
                    ?>
                    ( __ - __ )
                    </h2>
                </td>
            </tr>	
        </table>

        <table border="1" style="border-collapse:collapse;margin-top:12px;" align="center" width="100%">				
            <thead>
                <tr>
                    <th rowspan="2" class="text-center"><h2>Sl</h2></th>
                    <th rowspan="2" class="text-center" width="20%"><h2>Vessel Name</h2></th>
                    <th rowspan="2" class="text-center"><h2>Date of Arrival</h2></th>
                    <th rowspan="2" class="text-center"><h2>Date of Departure</h2></th>		
                    <th rowspan="2" class="text-center"><h2>Rotation</h2></th>
                    <th rowspan="2" class="text-center"><h2>Country</h2></th>
                    <th colspan="2" class="text-center"><h2>Tonage</h2></th>   
                    <th rowspan="2" class="text-center"><h2>Local Agent</h2></th>      
                    <th rowspan="2" class="text-center"><h2>Forwarded to Acc at</h2></th> 
                </tr>
                <tr>
                    <th><h2>GT</h2></th>
                    <th><h2>NT</h2></th>
                </tr>
            </thead>
<?php
        }
    } 
?>
                        
</table>

<table border="0" align="center" width="100%" style="border-collapse:collapse;margin-top:10px;">
    <tr>
        <th align="center" width="18%">
            <img height="50px" width="190px" src="http://cpatos.gov.bd/tosapi/ahm_pilot_signature/23026.png"/>
        </th>
        <th>&nbsp;</th>
        <th align="center" width="18%">					
            <img height="50px" width="120px" src="http://cpatos.gov.bd/tosapi/ahm_pilot_signature/24087.png" />
        </th>
        <th>&nbsp;</th>
        <!-- <th align="center" width="18%">					
            <img height="50px" width="120px" src="http://cpatos.gov.bd/tosapi/ahm_pilot_signature/HMaster.png" />
        </th>
        <th>&nbsp;</th> -->
        <th align="center" width="18%">					
            <img height="50px" width="120px" src="http://cpatos.gov.bd/tosapi/ahm_pilot_signature/12369.png" />
        </th>
        <th>&nbsp;</th>
        <th align="center" width="18%">					
            <img height="50px" width="120px" src="http://cpatos.gov.bd/tosapi/ahm_pilot_signature/HMaster.png"/>					
        </th>				
    </tr>
    <tr>
        <th align="center" width="18%" style="border-bottom: 1px solid black;"><h1>Initiator</h1></th>	
        <th>&nbsp;</th>
        <th align="center" width="18%" style="border-bottom: 1px solid black;"><h1>SR.VTSSO/VTMIS</h1></th>
        <th>&nbsp;</th>				
        <!-- <th align="center" width="18%" style="border-bottom: 1px solid black;"><h1>SUPDT(DM ESTB)</h1></th>
        <th>&nbsp;</th> -->
        <th align="center" width="18%" style="border-bottom: 1px solid black;"><h1>SUPDT(B)</h1></th>		
        <th>&nbsp;</th>
        <th align="center" width="18%" style="border-bottom: 1px solid black;"><h1>HM/CPA</h1></th>				
    </tr>			
</table>

<?php

    function numberToMonth($num){
        $monthName = null;

        if($num == '01'){
            $monthName = "January";
        }else if($num == '02'){
            $monthName = "February";
        }else if($num == '03'){
            $monthName = "March";
        }else if($num == '04'){
            $monthName = "April";
        }else if($num == '05'){
            $monthName = "May";
        }else if($num == '06'){
            $monthName = "June";
        }else if($num == '07'){
            $monthName = "July";
        }else if($num == '08'){
            $monthName = "August";
        }else if($num == '09'){
            $monthName = "September";
        }else if($num == '10'){
            $monthName = "October";
        }else if($num == '11'){
            $monthName = "November";
        }else if($num == '12'){
            $monthName = "December";
        }

        return $monthName;
    }
?>