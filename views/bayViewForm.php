		<script>
			function pairedState(value) 
			{
				if (value == 1) 
				{
					var bay = parseInt(document.getElementById("bay").value);
					var bayLimit = bay + 1;
					//alert(bayLimit);

					//document.getElementById("pDiv").style.display="inline";
					var pWith = document.getElementById("pWith");
					pWith.disabled = false;
					removeOptions(pWith);
					for (var i = bayLimit; i <= 50; i++) 
					{
						//alert(i.length);
						var iValue = 0;
						if (i < 10)
							iValue = "0" + i;
						else
							iValue = i;
						var option = document.createElement('option');
						option.value = iValue;
						option.text = iValue;
						pWith.appendChild(option);
					}
				} 
				else 
				{
					var pw = document.getElementById("pWith");
					pw.disabled = true;
					//removeOptions(pw);
				}
				//document.getElementById("demo").innerHTML = "You selected: " + x;*/
			}

			function removeOptions(selectbox) 
			{
				var i;
				for (i = selectbox.options.length - 1; i >= 1; i--) 
				{
					selectbox.remove(i);
				}
			}

			function createTable(value) 
			{
				upValue = parseInt(value);
				var lowValue = parseInt(document.getElementById("bdlrl").value);

				var table = document.getElementById("dynamicTable");
				removeTableElement(table);
				var trlbl = document.createElement('tr');

				var td1lbl1 = document.createElement('td');
				var textlbl1 = document.createTextNode('Row');
				td1lbl1.appendChild(textlbl1);
				trlbl.appendChild(td1lbl1);

				var td1lbl2 = document.createElement('td');
				var textlbl2 = document.createTextNode('Min Col Limit');
				td1lbl2.appendChild(textlbl2);
				trlbl.appendChild(td1lbl2);

				var td1lbl3 = document.createElement('td');
				var textlbl3 = document.createTextNode('Max Col Limit');
				td1lbl3.appendChild(textlbl3);
				trlbl.appendChild(td1lbl3);

				table.appendChild(trlbl);

				for (var i = lowValue; i <= upValue; i += 2) 
				{
					var ival = "";
					if (i < 10)
						ival = "0" + i;
					else
						ival = i;

					var tr = document.createElement('tr');

					var td1 = document.createElement('td');
					var text1 = document.createTextNode('Row ' + ival + ':');
					td1.appendChild(text1);

					var td2 = document.createElement('td');
					var input = document.createElement("input");
					input.type = "text";
					input.name = "minCol" + ival;
					input.value = "01";
					input.style.width = "100px";
					td2.appendChild(input);

					var td3 = document.createElement('td');
					var input = document.createElement("input");
					input.type = "text";
					input.name = "maxCol" + ival;
					input.style.width = "100px";
					td3.appendChild(input);

					tr.appendChild(td1);
					tr.appendChild(td2);
					tr.appendChild(td3);
					table.appendChild(tr);
				}
				//table.appendChild(table);
			}

			function createTableUp(value) 
			{
				upValue = parseInt(value);
				var lowValue = parseInt(document.getElementById("adlrl").value);

				var table = document.getElementById("dynamicTableUp");
				removeTableElement(table);
				var trlbl = document.createElement('tr');

				var td1lbl1 = document.createElement('td');
				var textlbl1 = document.createTextNode('Row');
				td1lbl1.appendChild(textlbl1);
				trlbl.appendChild(td1lbl1);

				var td1lbl2 = document.createElement('td');
				var textlbl2 = document.createTextNode('Min Col Limit');
				td1lbl2.appendChild(textlbl2);
				trlbl.appendChild(td1lbl2);

				var td1lbl3 = document.createElement('td');
				var textlbl3 = document.createTextNode('Max Col Limit');
				td1lbl3.appendChild(textlbl3);
				trlbl.appendChild(td1lbl3);

				table.appendChild(trlbl);

				for (var i = lowValue; i <= upValue; i += 2) 
				{
					var ival = "";
					if (i < 10)
						ival = "0" + i;
					else
						ival = i;

					var tr = document.createElement('tr');

					var td1 = document.createElement('td');
					var text1 = document.createTextNode('Row ' + ival + ':');
					td1.appendChild(text1);

					var td2 = document.createElement('td');
					var input = document.createElement("input");
					input.type = "text";
					input.name = "minColUp" + ival;
					input.value = "01";
					input.style.width = "100px";
					td2.appendChild(input);

					var td3 = document.createElement('td');
					var input = document.createElement("input");
					input.type = "text";
					input.name = "maxColUp" + ival;
					input.style.width = "100px";
					td3.appendChild(input);

					tr.appendChild(td1);
					tr.appendChild(td2);
					tr.appendChild(td3);
					table.appendChild(tr);
				}
				//table.appendChild(table);
			}

			function removeTableElement(table) 
			{
				var tblLen = table.rows.length;
				//alert(tblLen);
				for (var i = tblLen; i > 1; i--) 
				{
					table.deleteRow(i - 1);
				}
			}

			function getVslInfo(rot) 
			{
				//alert(rot);
				if (rot == "") 
				{
					alert("Please Provide Rotation No.");
					return false;
				}
				if (window.XMLHttpRequest) 
				{
					// code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp = new XMLHttpRequest();
				} 
				else 
				{
					// code for IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				//var BASE_URL="http://192.168.16.42/myportpanel/index.php/report/getVslLayout";
				var BASE_URL = "<?php echo site_url('Report/getVslLayout')?>";
				xmlhttp.onreadystatechange = stateChangeVslInfo;
				xmlhttp.open("GET", BASE_URL + "?rot=" + rot, false);

				xmlhttp.send();
			}

			function stateChangeVslInfo() 
			{

				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
				{
					var myVslInfo = document.getElementById("myVslInfo");
					//removeOptions(selectList);
					var val = xmlhttp.responseText;
					//alert(val);
					myVslInfo.innerHTML = val;
				}
			}

			function getContinue(check) 
			{
				//alert(check);
				var btn = document.getElementById("submit");
				if (check == "yes") 
				{
					btn.disabled = false;
					//btn.style.background = "#ccc";
				} 
				else 
				{
					btn.disabled = true;
					//btn.style.background = "none";
				}
			}

			function checkBelow(val) 
			{
				//alert(val);
				if (val == 1) 
				{
					document.getElementById("cLineB").disabled = false;
					document.getElementById("bdlrl").disabled = false;
					document.getElementById("bdurl").disabled = false;
					document.getElementById("lowerGap").disabled = false;
				} 
				else 
				{
					document.getElementById("cLineB").disabled = true;
					document.getElementById("bdlrl").disabled = true;
					document.getElementById("bdurl").disabled = true;
					document.getElementById("gapLineB").disabled = true;
					document.getElementById("lowerGap").disabled = true;

					var table = document.getElementById("dynamicTable");
					removeTableElement(table);
				}
				//pw.disabled=true;
			}

			function isGap(val1, val2) 
			{
				//alert(val1+" "+val2);
				if (val2 == 1)
					var field = document.getElementById("gapLineA");
				else
					var field = document.getElementById("gapLineB");

				if (val1 == "0")
					field.disabled = false;
				else
					field.disabled = true;
			}
		</script>
<section role="main" class="content-body">
    <header class="page-header">
        <h2><?php  echo $title; ?></h2>
    </header>
    <form action="<?php echo site_url("report/vslLayout"); ?>" method="POST">
        <section class="panel">
            <header class="panel-heading">
                <h2 class="panel-title">General</h2>
                <div class="panel-actions">
                    <a href="#" class="fa fa-caret-down"></a>
                </div>
            </header>
            <div class="panel-body">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <?php //echo $msg; ?>
                        </div>
                    </div>
                    <div class="col-md-6 col-md-offset-3">
                        <div class="input-group mb-md">
                            <span class="input-group-addon span_width"> ROTATION NO :</span>
                            <input type="text" id="rotation" name="rotation" tabindex="1" placeholder="0000/0000"
                                onblur="return getVslInfo(this.value);" class="form-control" required>
                        </div>
                        <div class="input-group mb-md" id="myVslInfo">

                        </div>
                        <div class="input-group mb-md">
                            <span class="input-group-addon span_width">Will You Continue ?</span>
                            <select class="form-control" name="radio" onchange="getContinue(this.value)">
                                <option value="">Select</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                        <div class="input-group mb-md">
                            <span class="input-group-addon span_width">Bay</span>
                            <select class="form-control" name="bay" id="bay">
                                <option value="">Select</option>
                                <?php for($i=0;$i<=50;$i++) { ?>
                                <option value="<?php if($i<10)echo "0".$i;else echo $i;?>">
                                    <?php if($i<10)echo "0".$i;else echo $i;?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="input-group mb-md">
                            <span class="input-group-addon span_width">Bay Status</span>
                            <select class="form-control" name="bayState" id="bayState"
                                onchange="pairedState(this.value)">
                                <option value="">Select</option>
                                <option value="1">Paired</option>
                                <option value="0">Single</option>
                            </select>
                        </div>
                        <div class="input-group mb-md">
                            <span class="input-group-addon span_width">Paired with :</span>
                            <select class="form-control" name="pWith" id="pWith" disabled>
                                <option value="">Select</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="panel">
            <header class="panel-heading">
                <h2 class="panel-title">Above Deck</h2>
                <div class="panel-actions">
                    <a href="#" class="fa fa-caret-down"></a>
                </div>
            </header>
            <div class="panel-body">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <?php //echo $msg; ?>
                        </div>
                    </div>
                    <div class="col-md-6 col-md-offset-3">
                        <div class="input-group mb-md">
                            <span class="input-group-addon span_width">Center Line</span>
                            <select class="form-control" name="cLineA" id="cLineA" onchange="isGap(this.value,1)">
                                <option value="">Select</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                        <div class="input-group mb-md">
                            <span class="input-group-addon span_width">Gap for Center Line ?</span>
                            <select class="form-control" name="gapLineA" id="gapLineA" disabled>
                                <option value="">Select</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                        <div class="input-group mb-md">
                            <span class="input-group-addon span_width">Lower Row Limit</span>
                            <select class="form-control" name="adlrl" id="adlrl">
                                <option value="">Select</option>
                                <?php for($i=76;$i<=98;$i+=2) { ?>
                                <option value="<?php echo $i;?>"><?php echo $i;?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="input-group mb-md">
                            <span class="input-group-addon span_width">Upper Row Limit</span>
                            <select class="form-control" name="adurl" id="adurl" onchange="createTableUp(this.value)">
                                <option value="">Select</option>
                                <?php for($i=76;$i<=98;$i+=2) { ?>
                                <option value="<?php echo $i;?>"><?php echo $i;?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="input-group mb-md">
                            <table id="dynamicTableUp">
                                <tr>
                                    <td></td>
                                </tr>
                            </table>
                        </div>
                        <div class="input-group mb-md">
                            <span class="input-group-addon span_width"> Make Gap Upper Row</span>
                            <textarea class="form-control" name="upperGap" id="upperGap"
                                style="resize:none;"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="panel">
            <header class="panel-heading">
                <h2 class="panel-title">Below Deck</h2>
                <div class="panel-actions">
                    <a href="#" class="fa fa-caret-down"></a>
                </div>
            </header>
            <div class="panel-body">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <?php //echo $msg; ?>
                        </div>
                    </div>
                    <div class="col-md-6 col-md-offset-3">
                        <div class="input-group mb-md">
                            <span class="input-group-addon span_width">Is Below Deck?</span>
                            <select class="form-control" name="isBelow" id="isBelow" onchange="checkBelow(this.value)">
                                <option value="">Select</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                        <div class="input-group mb-md">
                            <span class="input-group-addon span_width">Center Line</span>
                            <select class="form-control" name="cLineB" id="cLineB" disabled
                                onchange="isGap(this.value,2)">
                                <option value="">Select</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                        <div class="input-group mb-md">
                            <span class="input-group-addon span_width">Gap for Center Line?</span>
                            <select class="form-control" name="gapLineB" id="gapLineB" disabled>
                                <option value="">Select</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                        <div class="input-group mb-md">
                            <span class="input-group-addon span_width">Lower Row Limit</span>
                            <select class="form-control" name="bdlrl" id="bdlrl" disabled>
                                <option value="">Select</option>
                                <?php for($i=2;$i<=14;$i+=2) { ?>
                                <option value="<?php if($i<10)echo "0".$i; else echo $i;?>">
                                    <?php if($i<10)echo "0".$i; else echo $i;?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="input-group mb-md">
                            <span class="input-group-addon span_width">Upper Row Limit</span>
                            <select class="form-control" name="bdurl" id="bdurl" onchange="createTable(this.value)"
                                disabled>
                                <option value="">Select</option>
                                <?php for($i=2;$i<=14;$i+=2) { ?>
                                <option value="<?php if($i<10)echo "0".$i; else echo $i;?>">
                                    <?php if($i<10)echo "0".$i; else echo $i;?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="input-group mb-md">
                            <table id="dynamicTable">
                                <tr>
                                    <td></td>
                                </tr>
                            </table>
                        </div>
                        <div class="input-group mb-md">
                            <span class="input-group-addon span_width">Make Gap Lower Row</span>
                            <textarea class="form-control" name="lowerGap" id="lowerGap" style="resize:none;"
                                disabled></textarea>
                        </div>
                    </div>
                </div>
            </div>

        </section>
        <div class="row">
            <div class="col-sm-12 text-center">
                <button type="submit" name="submit" id="submit" class="mb-xs mt-xs mr-xs btn btn-success" disabled>Draw
                    Bay</button>
            </div>
        </div>
    </form>
    <div class="row">
        <div class="col-sm-12 text-center">
            <?php 
				if(isset($_POST['submit']))
				{
					include('dbConection.php');
					include("dbOracleConnection.php");	
					$rotation = $_POST['rotation'];
					$bay = $_POST['bay'];
					//echo intval($bay);
					//return;
					$bayState = $_POST['bayState'];
					$pWith =0;
					if($bayState!="" and $bayState==1)
					{
						$pWith = $_POST['pWith'];
					}
					$cLineA = $_POST['cLineA'];
					$gapLineA = 0;
					if($cLineA==0)
					{
						$gapLineA = $_POST['gapLineA'];
					}
					$adlrl = $_POST['adlrl'];
					$adurl = $_POST['adurl'];
					$upperGap = $_POST['upperGap'];
					/*$adlcl = $_POST['adlcl'];
					$aducl = $_POST['aducl'];*/
					$isBelow = $_POST['isBelow'];			
					if($isBelow==1)
					{
						$gapLineB = 0;
						$cLineB = $_POST['cLineB'];
						if($cLineB==0)
						{
							$gapLineB = $_POST['gapLineB'];
						}
						$bdlrl = $_POST['bdlrl'];
						$bdurl = $_POST['bdurl'];
						$lowerGap = $_POST['lowerGap'];
					}
					//echo 
					if($rotation=="")
					{
						echo "<div align='center'><font color='red' size='5'><b>Rotation should not be blank...</b></font></div>";
						return;
					}
					elseif($bay=="")
					{
						echo "<div align='center'><font color='red' size='5'><b>Must need to select bay...</b></font></div>";
						return;
					}
					elseif($bayState=="")
					{
						echo "<div align='center'><font color='red' size='5'><b>Must need to select bay status...</b></font></div>";
						return;
					}
					elseif($bayState!="" and $bayState==1 and $pWith=="")
					{
						echo "<div align='center'><font color='red' size='5'><b>Must need to select paired with...</b></font></div>";
						return;
					}
					elseif($cLineA=="")
					{
						echo "<div align='center'><font color='red' size='5'><b>Must need to select above deck center line...</b></font></div>";
						return;
					}
					elseif($cLineA==0 and $gapLineA=="")
					{
						echo "<div align='center'><font color='red' size='5'><b>Must need to select above deck gap for center line...</b></font></div>";
						return;
					}
					elseif($adlrl=="")
					{
						echo "<div align='center'><font color='red' size='5'><b>Must need to select above deck lower row limit...</b></font></div>";
						return;
					}
					elseif($adurl=="")
					{
						echo "<div align='center'><font color='red' size='5'><b>Must need to select above deck upper row limit...</b></font></div>";
						return;
					}			
					elseif($isBelow==1 and $cLineB=="")
					{
						echo "<div align='center'><font color='red' size='5'><b>Must need to select below deck center line...</b></font></div>";
						return;
					}
					elseif($isBelow==1 and $cLineB==0 and $gapLineB=="")
					{
						echo "<div align='center'><font color='red' size='5'><b>Must need to select below deck gap for center line...</b></font></div>";
						return;
					}
					elseif($isBelow==1 and $bdlrl=="")
					{
						echo "<div align='center'><font color='red' size='5'><b>Must need to select below deck lower row limit...</b></font></div>";
						return;
					}
					elseif($isBelow==1 and $bdurl=="")
					{
						echo "<div align='center'><font color='red' size='5'><b>Must need to select below upper lower row limit...</b></font></div>";
						return;
					}
					else
					{
						//echo  " rot:".$rotation." bay:".$bay." bstate:".$bayState." pWith:".$pWith." cLine:".$cLineA." bdlrl:".$bdlrl." bdurl:".$bdurl."<br>";
						
						$strVslInfo = "select vsl_vessels.id,vsl_vessels.name
						from vsl_vessel_visit_details
						inner join vsl_vessels on vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
						where vsl_vessel_visit_details.ib_vyg='$rotation'";
						$rtnVslInfo = oci_parse($con_sparcsn4_oracle,$strVslInfo);
						oci_execute($rtnVslInfo);
						$row = oci_fetch_object($rtnVslInfo);
						$vslId= $row->ID;
						$vslName= $row->NAME;
						
						$chkBay = intval($bay);
						$strChk = "select * from ctmsmis.misBayView where vslId='$vslId' and bay=$chkBay";
						//echo $strChk;
						$resChk = mysqli_query($con_sparcsn4,$strChk);
						$chkRows = mysqli_num_rows($resChk);
						//return;
						if($chkRows>0)
						{
							echo "<div align='center'><font color='red' size='5'><b>Bay $bay is already drawn before...</b></font></div>";
							echo "<div align='center'><a href='".site_url("report/blankBayView")."?get=yes&vslId=$vslId&vslName=$vslName' target='_blank'><font size='4'>View Layout</font></a></div>";
							return;
						}
						
						$j=intval($adlrl);
						//echo "i value=".$i."<br>";
						for($j;$j<=$adurl;$j+=2)
						{
							if(strlen($j)==1 and $j<10)
								$jval = "0".$j;
							else
								$jval = $j;
								
							//echo $jval."<br>";
							$minColUp = $_POST['minColUp'.$jval];
							$maxColUp = $_POST['maxColUp'.$jval];
							//echo $minColUp."<br>";
							//echo $maxColUp."<br>";
							if($cLineA==1)
								$di=0;
							else
								$di=intval($minColUp);
								
							for($di;$di<=intval($maxColUp);$di++)
							{
								if($di<10)
									$diValue = "0".$di;
								else
									$diValue = $di;
								$pos = $diValue.$jval;
								//echo $pos."<br>";
								//echo $pWith."<br>";
								mysqli_query($con_sparcsn4,"insert into ctmsmis.misBayDetail(vslId,bay,pairedWith,position) values('$vslId','$bay','$pWith','$pos')");
							}
								
							$strUpInfo = "insert into ctmsmis.misBayViewBelow(vslId,bay,bayRow,minColLimit,maxColLimit) values('$vslId',$bay,$jval,$minColUp,$maxColUp)";
							//echo $strBelowInfo;
							//echo"<br>";
							mysqli_query($con_sparcsn4,$strUpInfo);
						}
						
						if($isBelow==1)
						{
							$i=intval($bdlrl);
							//echo "i value=".$i."<br>";
							for($i;$i<=$bdurl;$i+=2)
							{
								if(strlen($i)==1 and $i<10)
									$ival = "0".$i;
								else
									$ival = $i;
								
								//echo $ival."<br>";
								$minCol = $_POST['minCol'.$ival];
								$maxCol = $_POST['maxCol'.$ival];
								//echo $minCol."<br>";
								if($cLineB==1)
									$dib=0;
								else
									$dib=intval($minCol);
									
								for($dib;$dib<=intval($maxCol);$dib++)
								{
									if($dib<10)
										$dibValue = "0".$dib;
									else
										$dibValue = $dib;
									$posb = $dibValue.$ival;
									//echo $posb."<br>";
									mysqli_query($con_sparcsn4,"insert into ctmsmis.misBayDetail(vslId,bay,pairedWith,position) values('$vslId','$bay','$pWith','$posb')");
								}
								
								$strBelowInfo = "insert into ctmsmis.misBayViewBelow(vslId,bay,bayRow,minColLimit,maxColLimit) values('$vslId',$bay,$ival,$minCol,$maxCol)";
								//echo $strBelowInfo;
								//echo"<br>";
								mysqli_query($con_sparcsn4,$strBelowInfo);
							}
						}
						
						if($isBelow==1)
						{
							$strInsert = "insert into ctmsmis.misBayView(vslId,bay,paired,pairedWith,centerLineA,gapLineA,minRowLimAbv,maxRowLimAbv,isBelow,centerLineB,gapLineB,minRowLimBlw,maxRowLimBlw,gapUpperRow,gapLowerRow) 
							values('$vslId',$bay,$bayState,$pWith,$cLineA,$gapLineA,$adlrl,$adurl,$isBelow,$cLineB,$gapLineB,$bdlrl,$bdurl,'$upperGap','$lowerGap')";
						}
						else
						{
							$strInsert = "insert into ctmsmis.misBayView(vslId,bay,paired,pairedWith,centerLineA,gapLineA,minRowLimAbv,maxRowLimAbv,isBelow,gapUpperRow) 
							values('$vslId',$bay,$bayState,$pWith,$cLineA,$gapLineA,$adlrl,$adurl,$isBelow,'$upperGap')";
						}
						
						$res = mysqli_query($con_sparcsn4,$strInsert);
						if($res)
						{
							echo "<div align='center'><font color='blue' size='5'><b>Bay $bay for vessel $vslName drawn successfully...</b></font></div>";
							echo "<div align='center'><a href='".site_url("report/blankBayView")."?get=yes&vslId=$vslId&vslName=$vslName' target='_blank'><font size='4'>View Layout</font></a></div>";
						}
						else
						{
							echo "<div align='center'><font color='blue' size='5'><b>Bay $bay for vessel $vslName drawn not successfully...</b></font></div>".mysqli_error();
						}
					}
				?>
            <?php } ?>
        </div>
    </div>

    </div>
</section>