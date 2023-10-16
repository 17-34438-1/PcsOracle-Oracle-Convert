<?php
	class UploadExcel extends CI_Controller {
		
	function __construct(){
		
		parent::__construct();	
		$this->load->library(array('session', 'form_validation'));
		$this->load->model(array('CI_auth', 'CI_menu'));
		$this->load->helper(array('html','form', 'url'));
		//$this->load->driver('cache');
		$this->load->model('CI_auth', 'bm', TRUE);
		$this->load->library("pagination");
		
		header("cache-Control: no-store, no-cache, must-revalidate");
		header("cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
	}
	
	function index()
	{
		$data['title']="UPLOAD EXCEL FILE FOR COPINO...";
		$msg = "";
		$data['msg']=$msg;
		$this->load->view('cssAssets');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('excelUpload',$data);
		$this->load->view('jsAssets');
		
		//$this->load->view('excelUpload');
	}

	function upload_copern_copino_form()
	{
		$data['title']="UPLOAD EXCEL FILE FOR COPARN & COPINO";
		$msg = "";
		$data['msg']=$msg;
		$this->load->view('cssAssets');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('upload_copern_copino_form',$data);
		$this->load->view('jsAssets');
		
	}
	
	function upload_copern_copino()
	{   
		$login_id = $this->session->userdata('login_id');
		$rotation = $this->input->post('rotation');
		$rot=str_replace("/","_",$rotation);
		$date = date('YmdHis');
		$dbDate = date('Y-m-d H:i:s');
		$ipaddr = $_SERVER['REMOTE_ADDR'];
		//echo $dbDate."<br>";
		error_reporting(E_ALL ^ E_NOTICE);
		
		$filetype=$_POST["file"];
		
		$filenm1=$_FILES["file"]["name"];
		 $ext1 = pathinfo($filenm1, PATHINFO_EXTENSION);
		//echo $ext1 = explode(".", $filenm1);	

		$filenm2=$_FILES["copinofile"]["name"];
		 $ext2 = pathinfo($filenm2, PATHINFO_EXTENSION);
		


		if ($_FILES["file"]["error"] > 0 && $_FILES["copinofile"]["error"]>0)
		{
			
			// echo "File Error.";
			$data['msg'] = "<font color='red'><b>Error: " . $_FILES["file"]["error"] . "<br />May be your file size exceeds 2MB.Please reduce file size and try again.<br/>To reduce file size-<br/>
			Step1:Save your Excel file into CSV(.csv) format.<br/>
			Step2:Now save your CSV file into Excel(.xls) format.<br/>
			Step3:Upload new Excel(.xls) file again.</b></font>";
			$data['title']="UPLOAD EXCEL FILE FOR COPARN & COPINO";
			// echo "error";return;
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('upload_copern_copino_form',$data);
			$this->load->view('jsAssets');
			return;
		}
		else if($ext1 != "csv" or $ext2 != "csv")
		{
			//echo "Both files should be in csv format";
			$data['msg'] = "<font color='red'><b>Both files should be in csv format</b></font>";
			$data['title']="UPLOAD EXCEL FILE FOR COPARN & COPINO";
			// echo "error";return;
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('upload_copern_copino_form',$data);
			$this->load->view('jsAssets');
			return;
		}
		else
		{
			$rotation_str="SELECT COUNT(*) AS rtnValue FROM exp_coparn_copino_xls WHERE rotation='$rotation'";			
			$rotCountRslt = $this->bm->dataReturnDb1($rotation_str);
			$rotCountRslt=$rotCountRslt+1;	
		 	$filenm_coparn=date(Y_m)."/".$rot.'_'.$rotCountRslt.'_coparn.'.$ext1;
		 	$filenm_copino=date(Y_m)."/".$rot.'_'.$rotCountRslt.'_copino.'.$ext2;

			$path = $_SERVER['DOCUMENT_ROOT']."/assets/uploadfile/".date(Y_m);

			if(!file_exists($path)){
				mkdir($path, 0777, true);
				chmod($path, 0777);
			}
			
			if(
				move_uploaded_file($_FILES["file"]["tmp_name"],$path."/".$_FILES["file"]["name"]) 
						and 
				move_uploaded_file($_FILES["copinofile"]["tmp_name"],$path."/".$_FILES["copinofile"]["name"])
			)
			{
				//File uploaded successfully...
				rename($path."/".$_FILES["file"]["name"],$_SERVER['DOCUMENT_ROOT']."/assets/uploadfile/".$filenm_coparn);
				rename($path."/".$_FILES["copinofile"]["name"],$_SERVER['DOCUMENT_ROOT']."/assets/uploadfile/".$filenm_copino);

				$pcsResult = file_get_contents('http://172.16.10.25/assets/uploadfile/pullFile.php?fileName='.$filenm_coparn.'&ip='.$ipaddr);
				$pcsResult = file_get_contents('http://172.16.10.25/assets/uploadfile/pullFile.php?fileName='.$filenm_copino.'&ip='.$ipaddr);

				// return;
				
				// read coparn file start
				$fileCoparn = fopen($_SERVER['DOCUMENT_ROOT']."/assets/uploadfile/".$filenm_coparn, 'r');
				$coparnArr=array();
				$l=0;
				while (($lineCoparn = fgetcsv($fileCoparn)) !== FALSE) 
				{
					$l++;
					if($l!=1)
					{
						$coparnBooking=trim($lineCoparn[8]);
						$coparnIso=trim($lineCoparn[11]);
						$bookingIsoStr = $coparnBooking."-".$coparnIso;
						if (!in_array($bookingIsoStr, $coparnArr))
						{
							//$coparnArr[] = $value; 
							array_push($coparnArr, $bookingIsoStr);
						}
					}
				}
				fclose($fileCoparn);
				//print_r($coparnArr);
				//die();
				// read coparn file end
				
				//file read start
				$file = fopen($_SERVER['DOCUMENT_ROOT']."/assets/uploadfile/".$filenm_copino, 'r');
				$i=0;
				$prob = "<table border='1'><tr><td>Container</td><td>Description</td></tr>";
				$st = 0;
				$totCont = "";
				$vvd_gkey = "";
				while (($line = fgetcsv($file)) !== FALSE) 
				{
					//$line is an array of the csv elements
					//for($l=0;$l<count($line);$l++)
					$i++;
					if($i!=1)
					{
						$shipAgent=trim($line[1]);
						$rot=trim($line[5]);
						$pod_chk=trim($line[17]);
						$containerNo=$line[8];
						$containerNo."<br>";
						$cont_mlo=$line[7];
						$cont_boking_no=$line[9];
						$cont_iso=$line[10];
						$cont_category=$line[20];
						$cont_friedKind=strtoupper(trim($line[21]));
						$cont_commodity=trim($line[48]);
						$vatNonvat=strtoupper(trim($line[50]));
						$cont_mlo = preg_replace('/[^A-Za-z0-9\. -]/', '', $cont_mlo);
						$cont_transOperator=trim($line[15]);
						
						$contbookingIsoStr = $cont_boking_no."-".$cont_iso;
						//print($pod_chk." ".$containerNo." ".);
						//print "<br>";	
						$sqlrot="select vsl_vessel_visit_details.vvd_gkey as rtnValue from vsl_vessel_visit_details where vsl_vessel_visit_details.ib_vyg='$rot'";
						// echo $sqlrot;return;
						$vvd_gkey=$this->bm->dataReturn($sqlrot);
			
						$strChkPOD="select count(ref_unloc_code.id) as rtnValue from ref_unloc_code where ref_unloc_code.id='$pod_chk'";
						//echo $strChkPOD."<br>";
						//$resChkPOD = mysql_query($strChkPOD);
						$rowChkPOD = $this->bm->dataReturn($strChkPOD);
						
						$strChkOffDoc="select count(id) as rtnValue from ctmsmis.offdoc where id='$cont_transOperator'";
						$offDocId = $this->bm->dataReturnDb2($strChkOffDoc);
						
						$strChkCom="select count(*) as rtnValue from ctmsmis.commudity_detail where commudity_code='$cont_commodity'";
						$resCom = $this->bm->dataReturnDb2($strChkCom);
						
						$vatSt = 0;
						if($vatNonvat=="VAT")
							$vatSt = 1;
						else if($vatNonvat=="NONVAT")
							$vatSt = 1;
						else
							$vatSt = 0;
						
						if($rowChkPOD==0)
						{		
							//echo $strChkPOD;
							$prob .= "<tr><td>".$containerNo."</td><td>Mismatch POD with system.$st</td></tr>";
							$st = $st+1;
							$totCont .=$containerNo.", ";
						}
						else if($vvd_gkey==null or $vvd_gkey=="" or $vvd_gkey==" ")
						{
							$prob .= "<tr><td>".$containerNo."</td><td>Rotation ".$rot." not exist in system.</td></tr>";
							$st = $st+1;
							$totCont .=$containerNo.", ";
						}
						else if($cont_mlo=="" or $cont_mlo==" ")
						{
							$prob .= "<tr><td>".$containerNo."</td><td>MLO should not be blank.</td></tr>";
							$st = $st+1;
							$totCont .=$containerNo.", ";
						}
						else if($containerNo=="" or $containerNo==" ")
						{
							$prob .= "<tr><td>&nbsp;</td><td>Container No. should not be blank.</td></tr>";
							$st = $st+1;
						}
						else if (!in_array($contbookingIsoStr, $coparnArr))
						{
							$prob .= "<tr><td>".$containerNo."</td><td>Booking ".$cont_boking_no." for ISO Code ".$cont_iso." not exist in coparn file.</td></tr>";
							$st = $st+1;
							$totCont .=$containerNo.", ";
						}
						else if($pod_chk=="" or $pod_chk==" ")
						{
							$prob .= "<tr><td>".$containerNo."</td><td>POD should not be blank.</td></tr>";
							$st = $st+1;
							$totCont .=$containerNo.", ";
						}
						else if($cont_iso=="" or $cont_iso==" ")
						{
							$prob .= "<tr><td>".$containerNo."</td><td>ISO Code should not be blank.</td></tr>";
							$st = $st+1;
							$totCont .=$containerNo.", ";
						}
						else if($cont_category=="" or $cont_category==" ")
						{
							$prob .= "<tr><td>".$containerNo."</td><td>Category should not be blank.</td></tr>";
							$st = $st+1;
							$totCont .=$containerNo.", ";
						}
						else if($cont_friedKind=="" or $cont_friedKind==" ")
						{
							$prob .= "<tr><td>".$containerNo."</td><td>Status(MTY/FCL) should not be blank.</td></tr>";
							$st = $st+1;
							$totCont .=$containerNo.", ";
						}
						else if($shipAgent=="" or $shipAgent==" ")
						{
							$prob .= "<tr><td></td><td>Shipping Agent should not be blank.</td></tr>";
							$st = $st+1;
						}
						else if($rot=="" or $rot==" ")
						{
							$prob .= "<tr><td></td><td>Import rotation should not be blank.</td></tr>";
							$st = $st+1;
						}
						else if($offDocId<1)
						{
							$prob .= "<tr><td>".$containerNo."</td><td>Mismatched offdoc id at Tranport Operator Column.</td></tr>";
							$st = $st+1;
							$totCont .=$containerNo.", ";
						}
						else if(($cont_friedKind=="FCL" or $cont_friedKind=="LCL") and ($cont_commodity=="" or $cont_commodity==" " or $resCom<1))
						{
							$prob .= "<tr><td></td>".$containerNo."<td>Loaded contariner(s) must have commodity code not description. </td></tr>";
							$st = $st+1;
						}
						else if(($cont_friedKind=="FCL" or $cont_friedKind=="LCL") and ($vatSt==0))
						{
							$prob .= "<tr><td>".$containerNo."</td><td>Loaded contariner(s) must be VAT or NONVAT valued. </td></tr>";
							$st = $st+1;
						}
					}
				}
				$prob .= "<tr><td colspan='2'>".$totCont."</td></tr>";
				$prob .= "</table>";
				fclose($file);
				
				if($st>0)
				{
					unlink($_SERVER['DOCUMENT_ROOT']."/assets/uploadfile/".$filenm_coparn);
					unlink($_SERVER['DOCUMENT_ROOT']."/assets/uploadfile/".$filenm_copino);
					
					$data['msg'] = "IN YOUR CSV FILE LISTED PROBLEM(S) FOUND<br>".$prob."<br> PLEASE CORRECT & TRY AGAIN...";
					$data['title']="UPLOAD EXCEL FILE FOR COPARN & COPINO";
					$this->load->view('cssAssets');
					$this->load->view('headerTop');
					$this->load->view('sidebar');
					$this->load->view('upload_copern_copino_form',$data);
					$this->load->view('jsAssets');
					return;
				}
				else
				{
					// file read again to insert data into database
					$fileRead = fopen($_SERVER['DOCUMENT_ROOT']."/assets/uploadfile/".$filenm_copino, 'r');
					$failStr = "<table border='1'><tr><td colspan='3'>Following containers are  not uploaded. Possible cause is unavailability in system or miss-spelling.</td></tr><tr><td>Rotation</td><td>Container</td><td>MLO</td></tr>";
					$flst = 0;
					$sst = 0;
					$r=0;
					$prevInboundCont = 0;
					while (($lineRead = fgetcsv($fileRead)) !== FALSE) 
					{
						$r++;
						//echo "<br>".$r;
						if($r!=1)
						{
							$mlo=$lineRead[7];
							$container_no=$lineRead[8];
							$container_no."<br>";
							$boking_no=$lineRead[9];
							$iso=$lineRead[10];
							$iso_grp=$lineRead[13];
							$modeTrans=$lineRead[14];
							$transport=$lineRead[14];
							$transOperator=$lineRead[15];
							$pod=$lineRead[17];
							$fpod=$lineRead[18];
							$weight=$lineRead[19];
							$category=$lineRead[20];
							$friedKind=trim($lineRead[21]);
							$imoClass1=$lineRead[24];
							$imoClass2=$lineRead[25];
							$imoClass3=$lineRead[26];
							$imoClass4=$lineRead[27];
							$imoClass5=$lineRead[28];
							$unNo1=$lineRead[29];
							$unNo2=$lineRead[30];
							$unNo3=$lineRead[31];
							$unNo4=$lineRead[32];
							$unNo5=$lineRead[33];
							$imoName1=$lineRead[34];
							$imoName2=$lineRead[35];
							$imoName3=$lineRead[36];
							$imoName4=$lineRead[37];
							$imoName5=$lineRead[38];
							$tempUnit=$lineRead[39];
							$minTemp=$lineRead[40];
							$maxTemp=$lineRead[41];
							$OOG=$lineRead[43];
							$OH=$lineRead[44];
							$OWL=$lineRead[45];
							$OWR=$lineRead[46];
							$OLF=$lineRead[47];
							$OLB=$lineRead[48];
							$seal1=$lineRead[49];
							$seal2=$lineRead[50];
							$seal3=$lineRead[51];
							$seal4=$lineRead[52];
							$commodity=$lineRead[53];
							$VatNvat=strtoupper($lineRead[55]);
							$row=$row+1;
							$container_no = preg_replace('/[^A-Za-z0-9\. -]/', '', $container_no);
							$container_no = trim($container_no);
							
							if($friedKind=="FCL" or $friedKind=="LCL")
								$friedKind = $friedKind;
							else
								$friedKind = "MTY";
							
							//$container_no = str_replace('\n','',$container_no);
							//echo "<br>".$mlo."-".$container_no."-".$boking_no."-".$iso."-".$size."-".$fpod."-".$seal1;


							$sql="SELECT 
							inv_unit.gkey AS rtnValue from inv_unit
							WHERE inv_unit.id = '$container_no' ORDER by inv_unit.gkey DESC FETCH FIRST 1 ROWS ONLY";
							//echo "<br>".$sql;				
							$gkey=$this->bm->dataReturn($sql);				
							//echo "<br>".$gkey."-".$container_no;
							$sqlSize="select cont_size as rtnValue from igm_detail_container where cont_number='$container_no' limit 1";
							//echo "<br>".$sql;				
							$size=$this->bm->dataReturnDb1($sqlSize);
							
							$sqlHeight="select cont_height as rtnValue from igm_detail_container where cont_number='$container_no' limit 1";
							//echo "<br>".$sql;				
							$height=$this->bm->dataReturnDb1($sqlHeight);		
							
							
							$sqlTrans = "select inv_unit_fcy_visit.transit_state,inv_unit.category 
							from inv_unit 
							inner join inv_unit_fcy_visit on inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
							where inv_unit.id='$container_no' order by inv_unit_fcy_visit.gkey";
							$preTransList = $this->bm->dataSelect($sqlTrans);
							$trans = "";	
							$cat = "";				
							for($t=0;$t<count($preTransList);$t++) {
								$trans = $preTransList[$t][transit_state];
								$cat = $preTransList[$t][category];
							}
										
							$preVvdGky = "";
							$cnt = 0;
							if($cat=="EXPRT" and $trans=="S20_INBOUND")
							{
								$prevInboundCont++;
								$sqlPreVvdGky = "select vvd_gkey from ctmsmis.mis_exp_unit_preadv_req  where cont_id='$container_no'";
								$preVvdGkyList = $this->bm->dataSelectDb2($sqlPreVvdGky);
								for($v=0;$v<count($preVvdGkyList);$v++) {
									$cnt=$cnt+1;
									$preVvdGky = $preVvdGkyList[$v][vvd_gkey];
								}
							}							
							if($cnt==0)
							{
								//echo "inserted";
								$qryInsert = "replace into ctmsmis.mis_exp_unit_preadv_req(gkey,cont_id,cont_category,cont_status,cont_mlo,isoType,cont_size,cont_height,isoGroup,bookingNo,vvd_gkey,rotation,voys_no,agent,callSign,last_update,
								user_id,seal_no,seal_no2,seal_no3,seal_no4,goods_and_ctr_wt_kg,pod,fpod,modeTrans,transport,transOp,preAddStat,imoClass1,imoClass2,imoClass3,imoClass4,imoClass5,unNo1,unNo2,unNo3,unNo4,unNo5,imoName1,
								imoName2,imoName3,imoName4,imoName5,OLF,OLB,OWL,OWR,OH,tempUnit,minTemp,maxTemp,commodity_code) values($gkey,'$container_no','$category','$friedKind','$mlo','$iso','$size','$height','$iso_grp','$boking_no',$vvd_gkey,
								'$rot','$voys_no','$shipAgent','$callSign',now(),'$login_id','$seal1','$seal2','$seal3','$VatNvat','$weight','$pod','$fpod','$modeTrans','$transport','$transOperator',1,'$imoClass1','$imoClass2','$imoClass3',
								'$imoClass4','$imoClass5','$unNo1','$unNo2','$unNo3','$unNo4','$unNo5','$imoName1','$imoName2','$imoName3','$imoName4','$imoName5','$OLF','$OLB','$OWL','$OWR','$OH','$tempUnit','$minTemp','$maxTemp','$commodity')";
								//echo $qryInsert;
								$yes=$this->bm->dataInsertDb2($qryInsert);
								//echo $yes;
								if($yes==0)
								{
									//$strLog = $rot."|".$container_no."|".$mlo."|".$friedKind."|".$size."|".$height."|".$shipAgent."\n";
									//write_file("preAddviseFail.txt", $strLog, 'a');
									$failStr = $failStr."<tr><td>".$rot."</td><td>".$container_no."</td><td>".$mlo."</td></tr>";
									$flst = $flst+1;
								}
								else
								{
									$sst = $sst+1;
								}
							}
							else
							{
								//echo "updated";
								$qryUpdate = "update ctmsmis.mis_exp_unit_preadv_req set gkey=$gkey,cont_id='$container_no',cont_category='$category',cont_status='$friedKind',cont_mlo='$mlo',isoType='$iso',cont_size='$size',cont_height='$height',
								isoGroup='$iso_grp',bookingNo='$boking_no',vvd_gkey=$vvd_gkey,rotation='$rot',voys_no='$voys_no',agent='$shipAgent',callSign='$callSign',last_update=now(),user_id='$login_id',seal_no='$seal1',seal_no2='$seal2',
								seal_no3='$seal3',seal_no4='$VatNvat',goods_and_ctr_wt_kg='$weight',pod='$pod',fpod='$fpod',modeTrans='$modeTrans',transport='$transport',transOp='$transOperator',preAddStat=1,imoClass1='$imoClass1',imoClass2='$imoClass2',
								imoClass3='$imoClass3',imoClass4='$imoClass4',imoClass5='$imoClass5',unNo1='$unNo1',unNo2='$unNo2',unNo3='$unNo3',unNo4='$unNo4',unNo5='$unNo5',imoName1='$imoName1',imoName2='$imoName2',imoName3='$imoName3',
								imoName4='$imoName4',imoName5='$imoName5',OLF='$OLF',OLB='$OLB',OWL='$OWL',OWR='$OWR',OH='$OH',tempUnit='$tempUnit',minTemp='$minTemp',maxTemp='$maxTemp',commodity_code='$commodity',updateStat=1
								where cont_id='$container_no' and vvd_gkey='$preVvdGky'";
								//echo $qryInsert;
								$yes=$this->bm->dataUpdateDb2($qryUpdate);
								if($yes==0)
								{
									//$strLog = $rot."|".$container_no."|".$mlo."|".$friedKind."|".$size."|".$height."|".$shipAgent."\n";
									//write_file("preAddviseFail.txt", $strLog, 'a');
									$failStr = $failStr."<tr><td>".$rot."</td><td>".$container_no."</td><td>".$mlo."</td></tr>";
									$flst = $flst+1;
								}
								else
								{
									$sst = $sst+1;
								}
							}
						}
					}
					//echo $sst;
					fclose($fileRead);
					//file read end
					
					$failStr = $failStr."<tr><td colspan='3'>Please check container(s) in N4</td></tr></table>";
					//echo "===".$i;
					$new = $i-$prevInboundCont;
					//return;
					if($sst>0)
					{
						$insert_str="INSERT INTO exp_coparn_copino_xls (rotation, file_coparn, file_copino, upload_by, upload_time, ip) VALUES ('$rotation','$filenm_coparn', '$filenm_copino', '$login_id', NOW(), '$ipaddr')";
						$stat = $this->bm->dataInsertDB1($insert_str);
						//fail message forcely stopped
						/*
						if($flst>0)
							$data['msg'] = "Total container <a href='".site_url('uploadExcel/showDetailPrevCont/'.$vvd_gkey)."/all' target='_blank'>$i</a>,<br/>Newly Pre-Advised <a href='".site_url('uploadExcel/showDetailPrevCont/'.$vvd_gkey)."/new' target='_blank'>$new</a>,<br/>Pre-Advised with Vessel change <a href='".site_url('uploadExcel/showDetailPrevCont/'.$vvd_gkey)."/old' target='_blank'>$prevInboundCont</a><br/>".$failStr;
						else
							$data['msg'] = "Total container <a href='".site_url('uploadExcel/showDetailPrevCont/'.$vvd_gkey)."/all' target='_blank'>$i</a>,<br/>Newly Pre-Advised <a href='".site_url('uploadExcel/showDetailPrevCont/'.$vvd_gkey)."/new' target='_blank'>$new</a>,<br/>Pre-Advised with Vessel change <a href='".site_url('uploadExcel/showDetailPrevCont/'.$vvd_gkey)."/old' target='_blank'>$prevInboundCont</a>";
						*/
						$data['msg'] = "<font color='green'><b>Files are succesfully uploaded...</b></font>";
					}
					else{
						$data['msg'] = "PLEASE TRY AGAIN...";
					}
					
					/*
					if($stat>0)
					{
						$data['msg'] = "<font color='green'><b>Files are succesfully uploaded...</b></font>";
					}
					*/
					$data['title']="UPLOAD EXCEL FILE FOR COPARN & COPINO";
					$this->load->view('cssAssets');
					$this->load->view('headerTop');
					$this->load->view('sidebar');
					$this->load->view('upload_copern_copino_form',$data);
					$this->load->view('jsAssets');
				}
			} 
			else 
			{
				//File was not uploaded...
				$data['msg'] = "<font color='red'><b>Files were not uploaded...</b></font>";
				$data['title']="UPLOAD EXCEL FILE FOR COPARN & COPINO";
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('upload_copern_copino_form',$data);
				$this->load->view('jsAssets');
				return;
			}
		}
	}	
	
	
	function copern_copino_list()					// 2020-06-08
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
				
			$query="select id,rotation,file_coparn,file_copino,upload_by,upload_time,n4_operation_st,n4_operation_by,n4_operation_time from exp_coparn_copino_xls";
			$rslt_copern_list=$this->bm->dataSelectDb1($query);
			
			$data['rslt_copern_list']=$rslt_copern_list;	
			$data['title']='Coparn Copino Declaration List';
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('copern_copino_list_view',$data);
			$this->load->view('jsAssets');
		
		}
	}
	
	function coparn_rot_search()
	{
		
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$rot_no = $this->input->post('rot_no');
		//$rot=str_replace("/","_",$rot_no);
		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();	
		}
		else
		{
			$query="select id,rotation,file_coparn,file_copino,upload_by,upload_time,n4_operation_st,n4_operation_by,n4_operation_time from exp_coparn_copino_xls where rotation='$rot_no'";
			$rslt_copern_list=$this->bm->dataSelectDb1($query);
			$data['rslt_copern_list']=$rslt_copern_list;	
			$data['title']='Coparn Copino Declaration List';
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('copern_copino_list_view',$data);
			$this->load->view('jsAssets');
		}
	}
	

	function update_cop_status(){
		$cop_id=$this->uri->segment(3);
		$login_id = $this->session->userdata('login_id');

		$copern_update="update exp_coparn_copino_xls set n4_operation_st=1,n4_operation_by='$login_id', n4_operation_time=NOW() WHERE  exp_coparn_copino_xls.id='$cop_id'";
		$updateST = $this->bm->dataUpdateDB1($copern_update);
		$query="select id,rotation,file_coparn,file_copino,upload_by,upload_time,n4_operation_st,n4_operation_by,n4_operation_time from exp_coparn_copino_xls";
		$rslt_copern_list=$this->bm->dataSelectDb1($query);
		
		$data['rslt_copern_list']=$rslt_copern_list;	
		$data['title']='Coparn Copino Declaration List';
		$this->load->view('cssAssets');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('copern_copino_list_view',$data);
		$this->load->view('jsAssets');

	}
	
	function bayView()
	{
		$data['title']="VESSEL BAY VIEW FORM...";
		$this->load->view('cssAssets');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('vslViewForm',$data);
		$this->load->view('jsAssets');
		//$this->load->view('excelUpload');
	}
	
	function bayViewPerformed()
	{
		//$data['title']="VESSEL VIEW FORM...";
		//$this->load->view('header2');
		$this->load->view('vslView');
		//$this->load->view('footer');
		//$this->load->view('excelUpload');
	}
	
	function impBayView()
	{
		$str = "select * from (
			select vsl_vessel_visit_details.vvd_gkey,concat(concat(vsl_vessels.name,'-'),vsl_vessel_visit_details.ib_vyg) as vsl
			from vsl_vessel_visit_details
			inner join argo_carrier_visit on argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
			inner join vsl_vessels on vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
			where argo_carrier_visit.phase not in('80CANCELED','70CLOSED') and vsl_vessels.name not like '%PANGAON%' 
			)  tbl order by vvd_gkey desc";
		$vessel=$this->bm->dataSelect($str);

		$data['title']="VESSEL BAY VIEW FORM...";
		$data['vessel'] = $vessel;
		$this->load->view('cssAssets');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('impBayViewForm',$data);
		$this->load->view('jsAssets');
	}
	
	function impBayViewPerformed()
	{
		$this->load->view('impVslView');
	}
	
	function upload()
	{				
		$login_id = $this->session->userdata('login_id');
		$date = date('YmdHis');
		$dbDate = date('Y-m-d H:i:s');
		//echo $dbDate."<br>";
		error_reporting(E_ALL ^ E_NOTICE);   

		$filenm=$login_id."_".$date.".xls";
		$filetype=$_POST["file"];
		
		if ($_FILES["file"]["error"] > 0)
		{
			
			// echo "File Error.";
			$data['msg'] = "<font color='red'><b>Error: " . $_FILES["file"]["error"] . "<br />May be your file size exceeds 2MB.Please reduce file size and try again.<br/>To reduce file size-<br/>
			Step1:Save your Excel file into CSV(.csv) format.<br/>
			Step2:Now save your CSV file into Excel(.xls) format.<br/>
			Step3:Upload new Excel(.xls) file again.</b></font>";
			$data['title']="UPLOAD EXCEL FILE FOR COPINO...";
			// echo "error";return;
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('excelUpload',$data);
			$this->load->view('jsAssets');
			return;
		}
		else
		{
			// echo "ok";return;
		move_uploaded_file($_FILES["file"]["tmp_name"],$_SERVER['DOCUMENT_ROOT']."/resources/uploadfile/".$_FILES["file"]["name"]);
		
		rename($_SERVER['DOCUMENT_ROOT']."/resources/uploadfile/".$_FILES["file"]["name"],$_SERVER['DOCUMENT_ROOT']."/resources/uploadfile/".$filenm);
		}
		// echo "Upload";return;
		// Load the spreadsheet reader library
		// echo $filenm;return;
		require_once('excel_reader2.php');
		// echo $filenm;
		$mydata = new Spreadsheet_Excel_Reader($_SERVER['DOCUMENT_ROOT']."/resources/uploadfile/".$filenm);
		// echo $filenm;return;
		$shipAgent=trim($mydata->value(3,3));
		$callSign=$mydata->value(5,3);
		$voys_no=$mydata->value(6,3);
		$rot=trim($mydata->value(7,3));
		$rot=str_replace(" ","",$rot);
		$expRot=$mydata->value(8,3);
		// echo "<br>";
		// echo $shipAgent;
		// echo "<br>";
		// echo $callSign;
		// echo "<br>";
		// echo $voys_no;
		// echo "<br>";
		// echo $rot;
		// echo "<br>";
		// echo $expRot;
		// echo "<br>";

		// return;
		//echo "Main=".$shipAgent."-".$rot;
		$sqlrot="select vsl_vessel_visit_details.vvd_gkey as rtnValue from vsl_vessel_visit_details where vsl_vessel_visit_details.ib_vyg='$rot'";
		// echo $sqlrot;return;
		$vvd_gkey=$this->bm->dataReturn($sqlrot);
		//echo "<br>".$vvd_gkey;
		// return;
		if($vvd_gkey==null or $vvd_gkey=="" or $vvd_gkey==" ")
		{
			$data['msg'] = "Rotation ".$rot." not exist in system.<br> PLEASE CORRECT & TRY AGAIN...";
			$data['title']="UPLOAD EXCEL FILE FOR COPINO...";
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('excelUpload',$data);
			$this->load->view('jsAssets');
			return;
		}
		
		// $strAgentChk = "select r.id as rtnValue 
		// from vsl_vessel_visit_details 
		// INNER JOIN
		// ( ref_bizunit_scoped r
		// LEFT JOIN ( ref_agent_representation X
		// LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )
		// ON r.gkey=X.bzu_gkey
		// )  ON r.gkey = vsl_vessel_visit_details.bizu_gkey
		// where vsl_vessel_visit_details.ib_vyg='$rot'";
		
		$strAgentChk = "select r.id as rtnValue 
		from vsl_vessel_visit_details 
		INNER JOIN
		( ref_bizunit_scoped r
		LEFT JOIN ( ref_agent_representation X
		LEFT JOIN ref_bizunit_scoped Y ON X.agent_gkey=Y.gkey )
		ON r.gkey=X.bzu_gkey
		)  ON r.gkey = vsl_vessel_visit_details.bizu_gkey
		where vsl_vessel_visit_details.ib_vyg='$rot'";
		
		$strAgent=$this->bm->dataReturn($strAgentChk);
		if($strAgent!=$shipAgent or $shipAgent=="" or $shipAgent==" ")
		{
			$data['msg'] = "Rotation ".$rot." Shipping agent shuld be ".$strAgent." instead of ".$shipAgent." or should not blank.<br> PLEASE CORRECT & TRY AGAIN...";
			$data['title']="UPLOAD EXCEL FILE FOR COPINO...";
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('excelUpload',$data);
			$this->load->view('jsAssets');
			return;
		}
		
		$totalrow=0;
		$excelrow=$mydata->rowcount(0);
		$i=13;

		while($i<=$excelrow){
			
			if( $mydata->value($i,3)!="" )
			$totalrow=$totalrow+1;
			$i=$i+1;
		}
		//echo "<br>".$totalrow;
		//return;
		$row=13;   
		$prob = "<table border='1'><tr><td>Container</td><td>Description</td></tr>";
		$st = 0;
		$totCont = "";			
		while($row<($totalrow+13))
		{
			$pod_chk=trim($mydata->value($row,13));
			$containerNo=$mydata->value($row,3);
			$cont_mlo=$mydata->value($row,2);
			$cont_boking_no=$mydata->value($row,4);
			$cont_iso=$mydata->value($row,5);
			$cont_category=$mydata->value($row,16);
			$cont_friedKind=strtoupper(trim($mydata->value($row,17)));
			$cont_commodity=trim($mydata->value($row,55));
			$vatNonvat=strtoupper(trim($mydata->value($row,56)));
			$cont_mlo = preg_replace('/[^A-Za-z0-9\. -]/', '', $cont_mlo);
			$cont_transOperator=trim($mydata->value($row,11));
			
			$strChkPOD="select count(ref_unloc_code.id) as rtnValue from ref_unloc_code where ref_unloc_code.id='$pod_chk'";
			//echo $strChkPOD."<br>";
			//$resChkPOD = mysql_query($strChkPOD);
			$rowChkPOD = $this->bm->dataReturn($strChkPOD);
			
			$strChkOffDoc="select count(id) as rtnValue from ctmsmis.offdoc where id='$cont_transOperator'";
			$offDocId = $this->bm->dataReturnDb2($strChkOffDoc);
			
			$strChkCom="select count(*) as rtnValue from ctmsmis.commudity_detail where commudity_code='$cont_commodity'";
			$resCom = $this->bm->dataReturnDb2($strChkCom);
			$vatSt = 0;
			if($vatNonvat=="VAT")
				$vatSt = 1;
			else if($vatNonvat=="NONVAT")
				$vatSt = 1;
			else
				$vatSt = 0;
			
			if($rowChkPOD==0)
			{		
				//echo $strChkPOD;
				$prob .= "<tr><td>".$containerNo."</td><td>Mismatch POD with system.$st</td></tr>";
				$st = $st+1;
				$totCont .=$containerNo.", ";
			}			
			else if($cont_mlo=="" or $cont_mlo==" ")
			{
				$prob .= "<tr><td>".$containerNo."</td><td>MLO should not be blank.</td></tr>";
				$st = $st+1;
				$totCont .=$containerNo.", ";
			}
			else if($containerNo=="" or $containerNo==" ")
			{
				$prob .= "<tr><td>&nbsp;</td><td>Container No. should not be blank.</td></tr>";
				$st = $st+1;
			}
			else if($pod_chk=="" or $pod_chk==" ")
			{
				$prob .= "<tr><td>".$containerNo."</td><td>POD should not be blank.</td></tr>";
				$st = $st+1;
				$totCont .=$containerNo.", ";
			}
			else if($cont_iso=="" or $cont_iso==" ")
			{
				$prob .= "<tr><td>".$containerNo."</td><td>ISO Code should not be blank.</td></tr>";
				$st = $st+1;
				$totCont .=$containerNo.", ";
			}
			else if($cont_category=="" or $cont_category==" ")
			{
				$prob .= "<tr><td>".$containerNo."</td><td>Category should not be blank.</td></tr>";
				$st = $st+1;
				$totCont .=$containerNo.", ";
			}
			else if($cont_friedKind=="" or $cont_friedKind==" ")
			{
				$prob .= "<tr><td>".$containerNo."</td><td>Status(MTY/FCL) should not be blank.</td></tr>";
				$st = $st+1;
				$totCont .=$containerNo.", ";
			}
			else if($shipAgent=="" or $shipAgent==" ")
			{
				$prob .= "<tr><td></td><td>Shipping Agent should not be blank.</td></tr>";
				$st = $st+1;
			}
			else if($rot=="" or $rot==" ")
			{
				$prob .= "<tr><td></td><td>Import rotation should not be blank.</td></tr>";
				$st = $st+1;
			}
			else if($offDocId<1)
			{
				$prob .= "<tr><td>".$containerNo."</td><td>Mismatched offdoc id at Tranport Operator Column.</td></tr>";
				$st = $st+1;
				$totCont .=$containerNo.", ";
			}
			else if(($cont_friedKind=="FCL" or $cont_friedKind=="LCL") and ($cont_commodity=="" or $cont_commodity==" " or $resCom<1))
			{
				$prob .= "<tr><td></td>".$containerNo."<td>Loaded contariner(s) must have commodity code not description. </td></tr>";
				$st = $st+1;
			}
			else if(($cont_friedKind=="FCL" or $cont_friedKind=="LCL") and ($vatSt==0))
			{
				$prob .= "<tr><td>".$containerNo."</td><td>Loaded contariner(s) must be VAT or NONVAT valued. </td></tr>";
				$st = $st+1;
			}
			$row=$row+1; 
		}
		$prob .= "<tr><td colspan='2'>".$totCont."</td></tr>";
		$prob .= "</table>";
		//echo $st;
		
		if($st>0)
		{
			$data['msg'] = "IN YOUR EXCEL FILE LISTED PROBLEM(S) FOUND<br>".$prob."<br> PLEASE CORRECT & TRY AGAIN...";
			$data['title']="UPLOAD EXCEL FILE FOR COPINO...";
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('excelUpload',$data);
			$this->load->view('jsAssets');
			return;
		}
		else
		{
			$row = 13;
		}
		//echo "<br>".$row;
		//echo "<br>".$totalrow;
		$failStr = "<table border='1'><tr><td colspan='3'>Following containers are  not uploaded. Possible cause is unavailability in system or miss-spelling.</td></tr><tr><td>Rotation</td><td>Container</td><td>MLO</td></tr>";
		$flst = 0;
		$sst = 0;
		$i=0;
		$prevInboundCont = 0;
		while($row<($totalrow+13))
		{
			$i++;
			//echo "<br>".$i;
			$mlo=$mydata->value($row,2);
			$container_no=$mydata->value($row,3);
			$boking_no=$mydata->value($row,4);
			$iso=$mydata->value($row,5);
			//$size=$mydata->value($row,6);
			//$height=$mydata->value($row,7);
			$iso_grp=$mydata->value($row,8);
			$modeTrans=$mydata->value($row,9);
			$transport=$mydata->value($row,10);
			$transOperator=$mydata->value($row,11);
			$pod=$mydata->value($row,13);
			$fpod=$mydata->value($row,14);
			$weight=$mydata->value($row,15);
			$category=$mydata->value($row,16);
			$friedKind=trim($mydata->value($row,17));
			$imoClass1=$mydata->value($row,20);
			$imoClass2=$mydata->value($row,21);
			$imoClass3=$mydata->value($row,22);
			$imoClass4=$mydata->value($row,23);
			$imoClass5=$mydata->value($row,24);
			$unNo1=$mydata->value($row,25);
			$unNo2=$mydata->value($row,26);
			$unNo3=$mydata->value($row,27);
			$unNo4=$mydata->value($row,28);
			$unNo5=$mydata->value($row,29);
			$imoName1=$mydata->value($row,30);
			$imoName2=$mydata->value($row,31);
			$imoName3=$mydata->value($row,32);
			$imoName4=$mydata->value($row,33);
			$imoName5=$mydata->value($row,34);
			$tempUnit=$mydata->value($row,36);
			$minTemp=$mydata->value($row,37);
			$maxTemp=$mydata->value($row,38);
			$OOG=$mydata->value($row,39);
			$OH=$mydata->value($row,40);
			$OWL=$mydata->value($row,41);
			$OWR=$mydata->value($row,42);
			$OLF=$mydata->value($row,43);
			$OLB=$mydata->value($row,44);
			$seal1=$mydata->value($row,51);
			$seal2=$mydata->value($row,52);
			$seal3=$mydata->value($row,53);
			$seal4=$mydata->value($row,54);
			$commodity=$mydata->value($row,55);
			$VatNvat=strtoupper($mydata->value($row,56));
			$row=$row+1;
			$container_no = preg_replace('/[^A-Za-z0-9\. -]/', '', $container_no);
			$container_no = trim($container_no);
			
			if($friedKind=="FCL" or $friedKind=="LCL")
				$friedKind = $friedKind;
			else
				$friedKind = "MTY";
			
			//$container_no = str_replace('\n','',$container_no);
			//echo "<br>".$mlo."-".$container_no."-".$boking_no."-".$iso."-".$size."-".$fpod."-".$seal1;


			$sql="select 
			inv_unit.gkey as rtnValue from 
			inv_unit
			where 
			inv_unit.id = '$container_no' order by inv_unit.gkey desc limit 1";


			//echo "<br>".$sql;				
			$gkey=$this->bm->dataReturn($sql);				
			//echo "<br>".$gkey."-".$container_no;
			$sqlSize="select cont_size as rtnValue from igm_detail_container where cont_number='$container_no' limit 1";
			//echo "<br>".$sql;				
			$size=$this->bm->dataReturnDb1($sqlSize);
			
			$sqlHeight="select cont_height as rtnValue from igm_detail_container where cont_number='$container_no' limit 1";
			//echo "<br>".$sql;				
			$height=$this->bm->dataReturnDb1($sqlHeight);		
			
			
			$sqlTrans = "select inv_unit_fcy_visit.transit_state,inv_unit.category from inv_unit 
			inner join inv_unit_fcy_visit on inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			where inv_unit.id='$container_no' order by inv_unit_fcy_visit.gkey";
			$preTransList = $this->bm->dataSelect($sqlTrans);
			$trans = "";	
			$cat = "";				
			for($t=0;$t<count($preTransList);$t++) {
				$trans = $preTransList[$t][transit_state];
				$cat = $preTransList[$t][category];
			}
						
			$preVvdGky = "";
			$cnt = 0;
			if($cat=="EXPRT" and $trans=="S20_INBOUND")
			{
				$prevInboundCont++;
				$sqlPreVvdGky = "select vvd_gkey from ctmsmis.mis_exp_unit_preadv_req  where cont_id='$container_no'";
				$preVvdGkyList = $this->bm->dataSelectDb2($sqlPreVvdGky);
				for($v=0;$v<count($preVvdGkyList);$v++) {
					$cnt=$cnt+1;
					$preVvdGky = $preVvdGkyList[$v][vvd_gkey];
				}
			}
			
			if($cnt==0)
			{
				//echo "inserted";
				$qryInsert = "replace into ctmsmis.mis_exp_unit_preadv_req(gkey,cont_id,cont_category,cont_status,cont_mlo,isoType,cont_size,cont_height,isoGroup,bookingNo,vvd_gkey,rotation,voys_no,agent,callSign,last_update,
				user_id,seal_no,seal_no2,seal_no3,seal_no4,goods_and_ctr_wt_kg,pod,fpod,modeTrans,transport,transOp,preAddStat,imoClass1,imoClass2,imoClass3,imoClass4,imoClass5,unNo1,unNo2,unNo3,unNo4,unNo5,imoName1,
				imoName2,imoName3,imoName4,imoName5,OLF,OLB,OWL,OWR,OH,tempUnit,minTemp,maxTemp,commodity_code) values($gkey,'$container_no','$category','$friedKind','$mlo','$iso','$size','$height','$iso_grp','$boking_no',$vvd_gkey,
				'$rot','$voys_no','$shipAgent','$callSign',now(),'$login_id','$seal1','$seal2','$seal3','$VatNvat','$weight','$pod','$fpod','$modeTrans','$transport','$transOperator',1,'$imoClass1','$imoClass2','$imoClass3',
				'$imoClass4','$imoClass5','$unNo1','$unNo2','$unNo3','$unNo4','$unNo5','$imoName1','$imoName2','$imoName3','$imoName4','$imoName5','$OLF','$OLB','$OWL','$OWR','$OH','$tempUnit','$minTemp','$maxTemp','$commodity')";
				//echo $qryInsert;
				$yes=$this->bm->dataInsertDb2($qryInsert);
				//echo $yes;
				if($yes==0)
				{
					$strLog = $rot."|".$container_no."|".$mlo."|".$friedKind."|".$size."|".$height."|".$shipAgent."\n";
					write_file("preAddviseFail.txt", $strLog, 'a');
					$failStr = $failStr."<tr><td>".$rot."</td><td>".$container_no."</td><td>".$mlo."</td></tr>";
					$flst = $flst+1;
				}
				else
				{
					$sst = $sst+1;
				}
			}
			else
			{
				//echo "updated";
				$qryUpdate = "update ctmsmis.mis_exp_unit_preadv_req set gkey=$gkey,cont_id='$container_no',cont_category='$category',cont_status='$friedKind',cont_mlo='$mlo',isoType='$iso',cont_size='$size',cont_height='$height',
				isoGroup='$iso_grp',bookingNo='$boking_no',vvd_gkey=$vvd_gkey,rotation='$rot',voys_no='$voys_no',agent='$shipAgent',callSign='$callSign',last_update=now(),user_id='$login_id',seal_no='$seal1',seal_no2='$seal2',
				seal_no3='$seal3',seal_no4='$VatNvat',goods_and_ctr_wt_kg='$weight',pod='$pod',fpod='$fpod',modeTrans='$modeTrans',transport='$transport',transOp='$transOperator',preAddStat=1,imoClass1='$imoClass1',imoClass2='$imoClass2',
				imoClass3='$imoClass3',imoClass4='$imoClass4',imoClass5='$imoClass5',unNo1='$unNo1',unNo2='$unNo2',unNo3='$unNo3',unNo4='$unNo4',unNo5='$unNo5',imoName1='$imoName1',imoName2='$imoName2',imoName3='$imoName3',
				imoName4='$imoName4',imoName5='$imoName5',OLF='$OLF',OLB='$OLB',OWL='$OWL',OWR='$OWR',OH='$OH',tempUnit='$tempUnit',minTemp='$minTemp',maxTemp='$maxTemp',commodity_code='$commodity',updateStat=1
				where cont_id='$container_no' and vvd_gkey='$preVvdGky'";
				//echo $qryInsert;
				$yes=$this->bm->dataUpdateDb2($qryUpdate);
				if($yes==0)
				{
					$strLog = $rot."|".$container_no."|".$mlo."|".$friedKind."|".$size."|".$height."|".$shipAgent."\n";
					write_file("preAddviseFail.txt", $strLog, 'a');
					$failStr = $failStr."<tr><td>".$rot."</td><td>".$container_no."</td><td>".$mlo."</td></tr>";
					$flst = $flst+1;
				}
				else
				{
					$sst = $sst+1;
				}
			}
			
		}
		$failStr = $failStr."<tr><td colspan='3'>Please check container(s) in N4</td></tr></table>";
		//echo "===".$i;
		$new = $i-$prevInboundCont;
		//return;
		if($sst>0)
		{
			if($flst>0)
				$data['msg'] = "Total container <a href='".site_url('uploadExcel/showDetailPrevCont/'.$vvd_gkey)."/all' target='_blank'>$i</a>,<br/>Newly Pre-Advised <a href='".site_url('uploadExcel/showDetailPrevCont/'.$vvd_gkey)."/new' target='_blank'>$new</a>,<br/>Pre-Advised with Vessel change <a href='".site_url('uploadExcel/showDetailPrevCont/'.$vvd_gkey)."/old' target='_blank'>$prevInboundCont</a><br/>".$failStr;
			else
				$data['msg'] = "Total container <a href='".site_url('uploadExcel/showDetailPrevCont/'.$vvd_gkey)."/all' target='_blank'>$i</a>,<br/>Newly Pre-Advised <a href='".site_url('uploadExcel/showDetailPrevCont/'.$vvd_gkey)."/new' target='_blank'>$new</a>,<br/>Pre-Advised with Vessel change <a href='".site_url('uploadExcel/showDetailPrevCont/'.$vvd_gkey)."/old' target='_blank'>$prevInboundCont</a>";
		}
		else{
		$data['msg'] = "PLEASE TRY AGAIN...";
		}
			
		$data['title']="UPLOAD EXCEL FILE FOR COPINO...";
		$this->load->view('cssAssets');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('excelUpload',$data);
		$this->load->view('jsAssets');
		
	}
	
	function uploadCoparnForm()
	{
		$data['title']="UPLOAD EXCEL FILE FOR COPARN...";
		$this->load->view('header2');
		$this->load->view('uploadCoparnForm',$data);
		$this->load->view('footer');
		//$this->load->view('excelUpload');
	}
	
	function uploadCoparn()
	{
		$login_id = $this->session->userdata('login_id');
		$date = date('YmdHis');
		$dbDate = date('Y-m-d H:i:s');
		//echo $dbDate."<br>";
		error_reporting(E_ALL ^ E_NOTICE);   

		$filenm=$login_id."_".$date.".xls";
		$filetype=$_POST["file"];
		
		if ($_FILES["file"]["error"] > 0)
		{
		echo "Error: " . $_FILES["file"]["error"] . "<br />";
		return;
		}
		else
		{
		move_uploaded_file($_FILES["file"]["tmp_name"],$_SERVER['DOCUMENT_ROOT']."/resources/uploadfile/".$_FILES["file"]["name"]);
		
		rename($_SERVER['DOCUMENT_ROOT']."/resources/uploadfile/".$_FILES["file"]["name"],$_SERVER['DOCUMENT_ROOT']."/resources/uploadfile/".$filenm);
		}
		//echo "Upload";
		// Load the spreadsheet reader library
		require_once('excel_reader2.php');
		$mydata = new Spreadsheet_Excel_Reader($_SERVER['DOCUMENT_ROOT']."/resources/uploadfile/".$filenm);
		$shipAgent=$mydata->value(3,3);
		$callSign=$mydata->value(5,3);
		$voys_no=$mydata->value(6,3);
		$rot=$mydata->value(7,3);
		$expRot=$mydata->value(8,3);
		//echo "Main=".$shipAgent."-".$rot;
		$sqlrot="select vsl_vessel_visit_details.vvd_gkey as rtnValue from vsl_vessel_visit_details where vsl_vessel_visit_details.ib_vyg='$rot'";
		//echo $sqlrot."<hr>";
		$vvd_gkey=$this->bm->dataReturn($sqlrot);
		//echo "<br>".$vvd_gkey;
		//return;
		
		mysql_query("delete from ctmsmis.mis_exp_unit_preadv_coparn where vvd_gkey=$vvd_gkey ");
		
		$totalrow=0;
		$excelrow=$mydata->rowcount(0);
		$i=13;

		while($i<=$excelrow)
		{
			if( $mydata->value($i,3)!="" )
			$totalrow=$totalrow+1;
			$i=$i+1;
		}
		//echo "<br>".$totalrow;
		//return;
		$row=13;   
		$prob = "<table border='1'><tr><td>MLO</td><td>Description</td></tr>";
		$st = 0;
		while($row<($totalrow+13))
		{
			$pod_chk=trim($mydata->value($row,10));
			$cont_quantity=$mydata->value($row,4);
			$cont_mlo=$mydata->value($row,2);
			$cont_boking_no=$mydata->value($row,3);
			$cont_iso=$mydata->value($row,6);
			$cont_category=$mydata->value($row,13);
			$cont_friedKind=$mydata->value($row,14);
			
			$strChkPOD="SELECT count(ref_routing_point.id) as cnt  
			from ref_routing_point 
			inner join ref_unloc_code on ref_unloc_code.gkey=ref_routing_point.unloc_gkey
			where ref_routing_point.id='$pod_chk' or ref_unloc_code.id='$pod_chk'";
			//echo $strChkPOD."<br>";
			$resChkPOD = mysql_query($strChkPOD);
			$rowChkPOD = mysql_fetch_object($resChkPOD);
			
			$strBookingGky="select booking0_.gkey from inv_eq_base_order booking0_ where booking0_.sub_type='BOOK' and (booking0_.created is null or booking0_.created>'2010-09-20 00:00:00') and booking0_.complex_gkey=1 and (booking0_.nbr = '$cont_boking_no')";
			//echo $strBookingGky."<br>";
			$resBookingGky = mysql_query($strBookingGky);
			$rowBookingGky = mysql_fetch_object($resBookingGky);
			$brow = mysql_num_rows($resBookingGky);
			$bGky = $rowBookingGky->gkey;
			//echo $containerNo."-".$rowChkPOD->cnt."<hr>";
			//echo $bGky."<br>";
			$strBookingInfo="select booking0_.nbr, scopedbizu1_.id as mlo, carriervis2_.id as visit_id, carriervis2_.phase ,carriervis2_.cvcvd_gkey
			from inv_eq_base_order booking0_ 
			left outer join ref_bizunit_scoped scopedbizu1_ on booking0_.line_gkey=scopedbizu1_.gkey 
			left outer join argo_carrier_visit carriervis2_ on booking0_.vessel_visit_gkey=carriervis2_.gkey 
			left outer join ref_bizunit_scoped scopedbizu3_ on booking0_.agent_gkey=scopedbizu3_.gkey 
			left outer join ref_bizunit_scoped scopedbizu4_ on booking0_.shipper_gkey=scopedbizu4_.gkey 
			left outer join ref_routing_point routingpoi5_ on booking0_.pol_gkey=routingpoi5_.gkey 
			left outer join ref_bizunit_scoped scopedbizu6_ on booking0_.truck_co_gkey=scopedbizu6_.gkey 
			left outer join ref_routing_point routingpoi7_ on booking0_.pod1_gkey=routingpoi7_.gkey 
			left outer join ref_routing_point routingpoi8_ on booking0_.pod2_gkey=routingpoi8_.gkey 
			left outer join ref_routing_point routingpoi9_ on booking0_.pod_optional_gkey=routingpoi9_.gkey 
			where booking0_.sub_type='BOOK' and (booking0_.gkey='$bGky') order by booking0_.nbr ASC";
			//echo $strBookingInfo."<br>";
			$resBookingInfo = mysql_query($strBookingInfo);
			$rowBookingInfo = mysql_fetch_object($resBookingInfo);
			//echo "Booking==".$cont_boking_no."<br>";
			//echo "Prev Used==".$rowBookingInfo->cvcvd_gkey."<br>";
			//echo "Curr=".$vvd_gkey."<br>";
			//echo "<hr>";
			if($rowBookingInfo->phase=="50COMPLETE" or $rowBookingInfo->phase=="60DEPARTED" or $rowBookingInfo->phase=="70CLOSED")
			{
				$prob .= "<tr><td>".$cont_mlo."</td><td>Booking '".$cont_boking_no."' is old</td></tr>";
				$st = $st+1;
			}
			else if(($rowBookingInfo->phase=="30ARRIVED" or $rowBookingInfo->phase=="40WORKING" or $rowBookingInfo->phase=="20INBOUND") and ($rowBookingInfo->cvcvd_gkey!=$vvd_gkey))
			{
				$prob .= "<tr><td>".$cont_mlo."</td><td>Booking '".$cont_boking_no."' is already used for another vessel</td></tr>";
				$st = $st+1;
			}
			else if($rowChkPOD->cnt==0)
			{
				//if($row%3==0)
				//$cont .= $containerNo.", ";
				//else
				$prob .= "<tr><td>".$cont_mlo."</td><td><b>'".$pod_chk."'</b> Mismatch POD with CTMS system</td></tr>";
				$st = $st+1;
			}	
			else if($cont_mlo=="" or $cont_mlo==" ")
			{
				$prob .= "<tr><td></td><td>MLO should not be blank</td></tr>";
				$st = $st+1;
			}
			else if($cont_quantity=="" or $cont_quantity==" ")
			{
				$prob .= "<tr><td>".$cont_mlo."</td><td>Quantity should not be blank</td></tr>";
				$st = $st+1;
			}
			else if($pod_chk=="" or $pod_chk==" ")
			{
				$prob .= "<tr><td>".$cont_mlo."</td><td>POD should not be blank</td></tr>";
				$st = $st+1;
			}
			else if($cont_boking_no=="" or $cont_boking_no==" ")
			{
				$prob .= "<tr><td>".$cont_mlo."</td><td>Booking No. should not be blank</td></tr>";
				$st = $st+1;
			}
			else if($cont_iso=="" or $cont_iso==" ")
			{
				$prob .= "<tr><td>".$cont_mlo."</td><td>ISO Code should not be blank</td></tr>";
				$st = $st+1;
			}
			else if($cont_category=="" or $cont_category==" ")
			{
				$prob .= "<tr><td>".$cont_mlo."</td><td>Category should not be blank</td></tr>";
				$st = $st+1;
			}
			else if($cont_friedKind=="" or $cont_friedKind==" ")
			{
				$prob .= "<tr><td>".$cont_mlo."</td><td>Status(MTY/FCL) should not be blank</td></tr>";
				$st = $st+1;
			}
			else if($shipAgent=="" or $shipAgent==" ")
			{
				$prob .= "<tr><td></td><td>Shipping Agent should not be blank</td></tr>";
				$st = $st+1;
			}
			else if($callSign=="" or $callSign==" ")
			{
				$prob .= "<tr><td></td><td>Call Sign should not be blank</td></tr>";
				$st = $st+1;
			}
			else if($voys_no=="" or $voys_no==" ")
			{
				$prob .= "<tr><td></td><td>Voys No. should not be blank</td></tr>";
				$st = $st+1;
			}
			else if($rot=="" or $rot==" ")
			{
				$prob .= "<tr><td></td><td>Import rotation should not be blank</td></tr>";
				$st = $st+1;
			}
			else if($expRot=="" or $expRot==" ")
			{
				$prob .= "<tr><td></td><td>Export rotation should not be blank</td></tr>";
				$st = $st+1;
			}
			$row=$row+1; 
		}
		$prob .= "</table>";
		//echo $st;
		
		if($st>0)
		{
			$data['msg'] = "IN YOUR EXCEL FILE LISTED PROBLEM(S) FOUND<br>".$prob."<br> PLEASE CORRECT & TRY AGAIN...";
			$data['title']="UPLOAD EXCEL FILE FOR COPARN...";
			$this->load->view('header2');
			$this->load->view('uploadCoparnForm',$data);
			$this->load->view('footer');
			return;
		}
		else
		{
			$row = 13;
		}
		
		$i=0;
		while($row<($totalrow+13)){
			
			$i++;
			$mlo=$mydata->value($row,2);
			$container_no=$mydata->value($row,5);
			$boking_no=$mydata->value($row,3);
			$quantity=$mydata->value($row,4);
			$iso=$mydata->value($row,6);
			$size=$mydata->value($row,7);
			$height=$mydata->value($row,8);
			$iso_grp=$mydata->value($row,9);
		
			$pod=$mydata->value($row,10);
			$fpod=$mydata->value($row,11);
			$weight=$mydata->value($row,12);
			$category=$mydata->value($row,13);
			$friedKind=$mydata->value($row,14);
			$imoClass1=$mydata->value($row,17);
			$imoClass2=$mydata->value($row,18);
			$imoClass3=$mydata->value($row,19);
			$imoClass4=$mydata->value($row,20);
			$imoClass5=$mydata->value($row,21);
			$unNo1=$mydata->value($row,22);
			$unNo2=$mydata->value($row,23);
			$unNo3=$mydata->value($row,24);
			$unNo4=$mydata->value($row,25);
			$unNo5=$mydata->value($row,26);
			$imoName1=$mydata->value($row,27);
			$imoName2=$mydata->value($row,28);
			$imoName3=$mydata->value($row,29);
			$imoName4=$mydata->value($row,30);
			$imoName5=$mydata->value($row,31);
			$tempUnit=$mydata->value($row,33);
			$minTemp=$mydata->value($row,34);
			$maxTemp=$mydata->value($row,35);
			$OOG=$mydata->value($row,37);
			$OH=$mydata->value($row,38);
			$OWL=$mydata->value($row,39);
			$OWR=$mydata->value($row,40);
			$OLF=$mydata->value($row,41);
			$OLB=$mydata->value($row,42);
			$seal1=$mydata->value($row,48);
			$seal2=$mydata->value($row,49);
			$seal3=$mydata->value($row,50);
			$seal4=$mydata->value($row,51);
			$row=$row+1; 
			$size = preg_replace('/[^A-Za-z0-9\. -]/', '', $size);
			$height = preg_replace('/[^A-Za-z0-9\. -]/', '', $height);
			
				$qryInsert = "insert into ctmsmis.mis_exp_unit_preadv_coparn(quantity,cont_id,cont_category,cont_status,cont_mlo,isoType,cont_size,cont_height,isoGroup,bookingNo,vvd_gkey,rotation,voys_no,agent,callSign,last_update,
				user_id,seal_no,seal_no2,seal_no3,seal_no4,goods_and_ctr_wt_kg,pod,fpod,imoClass1,imoClass2,imoClass3,imoClass4,imoClass5,unNo1,unNo2,unNo3,unNo4,unNo5,imoName1,
				imoName2,imoName3,imoName4,imoName5,OLF,OLB,OWL,OWR,OH,tempUnit,minTemp,maxTemp) values($quantity,'$container_no','$category','$friedKind','$mlo','$iso','$size','$height','$iso_grp','$boking_no',$vvd_gkey,
				'$rot','$voys_no','$shipAgent','$callSign',now(),'$login_id','$seal1','$seal2','$seal3','$seal4','$weight','$pod','$fpod','$imoClass1','$imoClass2','$imoClass3',
				'$imoClass4','$imoClass5','$unNo1','$unNo2','$unNo3','$unNo4','$unNo5','$imoName1','$imoName2','$imoName3','$imoName4','$imoName5','$OLF','$OLB','$OWL','$OWR','$OH','$tempUnit','$minTemp','$maxTemp')";
				//echo $qryInsert."<hr>";
				$yes=$this->bm->dataInsert($qryInsert);
			
			
		}
		//return;
		if($yes>0)
		$data['msg'] = "DATA SUCCESSFULLY UPLOADED...";
		else
		$data['msg'] = "PLEASE TRY AGAIN...";
			
		$data['title']="UPLOAD EXCEL FILE FOR COPARN...";
		$this->load->view('header2');
		$this->load->view('uploadCoparnForm',$data);
		$this->load->view('footer');
	}
	
	function convertCoparn(){
		//print_r($this->session->all_userdata());
		
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
	
		if($LoginStat!="yes")
		{
			$this->logout();
			
		}
		else
		{
			$data['title']="CONVERT COPARN...";
			$data['mystatus']="1";
			$this->load->view('header2');
			$this->load->view('myConvertCoparnForm',$data);
			$this->load->view('footer');
		}	
	}
	
	function convertCoparnPerformed()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
	
		if($LoginStat!="yes")
		{
			$this->logout();
			
		}
		else
		{
			$this->load->view('myConvertCoparnPerformed',$data);
		}
	}
	
	function convertCopino()
	{
		
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
	
		if($LoginStat!="yes")
		{
			$this->logout();
			
		}
		else
		{
			$data['title']="CONVERT COPINO...";
			$data['mystatus']="1";
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('myConvertCopinoForm',$data);
			$this->load->view('jsAssetsList');
		}	
	}

	
	
	function convertCopinoPerformed()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
	
		if($LoginStat!="yes")
		{
			$this->logout();
			
		}
		else
		{
			$this->load->view('myConvertCopinoPerformed',@$data);
		}
	}
	
	function preAdvisedRotList()
	{
		
		$session_id = $this->session->userdata('value');
		$login_id = $this->session->userdata('login_id');
		/*if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();
			
		}
		else
		{*/
			$data['title']="TODAY'S PRE-ADVISED ROTATION LIST...";
			$data['mystatus']="1";
			$data['login_id']=$login_id;
			
			//$this->load->view('header2');
			//$this->load->view('preAdvisedRotListHTML',$data);
			//$this->load->view('footer');

			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('preAdvisedRotListHTML',$data);
			$this->load->view('jsAssetsList');
		//}
	}
	
	function updateSNXStatus()
	{
		$login_id = $this->session->userdata('login_id');
		$vvdGkey = $this->uri->segment(3);
		$strUpdate = "update ctmsmis.mis_exp_unit_preadv_req set preAddStat=2,snx_uploaded_by='$login_id' where vvd_gkey=$vvdGkey and preAddStat=1";
		//echo $strUpdate;
		$this->bm->dataUpdate($strUpdate);
		
		$data['title']="TODAY'S PRE-ADVISED ROTATION LIST...";
		$data['mystatus']="1";
		
		$this->load->view('cssAssetsList');
		$this->load->view('preAdvisedRotListHTML',$data);
		$this->load->view('jsAssetsList');
	}
	
	function showDetailPrevCont()
	{
		$vvdGkey = $this->uri->segment(3);
		$opt = $this->uri->segment(4);
		$sql = "";
		$title = "";
		if($opt=="new")
		{
			$title  = "Newly Pre-Advised Container List";
			$sql = "select vvd_gkey,rotation,agent,cont_id,cont_mlo,isoType,cont_size,cont_height,transOp
			from ctmsmis.mis_exp_unit_preadv_req where preAddStat=1 and date(last_update)=date(now()) and vvd_gkey=$vvdGkey and updateStat=0";
		}
		else if($opt=="old")
		{
			$title  = "Pre-Advised Container List with vessel change";
			$sql = "select vvd_gkey,rotation,agent,cont_id,cont_mlo,isoType,cont_size,cont_height,transOp
			from ctmsmis.mis_exp_unit_preadv_req where preAddStat=1 and date(last_update)=date(now()) and vvd_gkey=$vvdGkey and updateStat=1";
		}
		else
		{
			$title  = "Updated Pre-Advised Container List";
			$sql = "select vvd_gkey,rotation,agent,cont_id,cont_mlo,isoType,cont_size,cont_height,transOp
			from ctmsmis.mis_exp_unit_preadv_req where preAddStat=1 and date(last_update)=date(now()) and vvd_gkey=$vvdGkey";
		}
		
		$preAddContList = $this->bm->dataSelect($sql);
		$data['preAddContList']=$preAddContList;
		$data['title']=$title;
		
		$this->load->view('cssAssetsList');
		$this->load->view('preAdvisedContListHTML',$data);
		$this->load->view('jsAssetsList');			
	}
	
	function showConverted()
	{
		$vvdGkey = $this->uri->segment(3);
		$sql = "";
		$title = "To be converted container list";		
		$data['vvdGkey']=$vvdGkey;
		$data['title']=$title;
		
		$this->load->view('cssAssetsList');
		$this->load->view('myConvertedContListHTML',$data);
		$this->load->view('jsAssetsList');		
	}
	
	function showNoConverted()
	{
		$vvdGkey = $this->uri->segment(3);
		$sql = "";
		$title = "Not converted container list";		
		$data['vvdGkey']=$vvdGkey;
		$data['title']=$title;
		
		$this->load->view('cssAssetsList');
		$this->load->view('myNoConvertedContListHTML',$data);
		$this->load->view('jsAssetsList');		
	}
	
	function logout(){
	
		$query="SELECT vsl_vessel_visit_details.vvd_gkey,vsl_vessels.name,vsl_vessel_visit_details.ib_vyg,vsl_vessel_visit_details.ob_vyg,
		LEFT(argo_carrier_visit.phase,2) AS phase_num,SUBSTR(argo_carrier_visit.phase,3) AS phase_str,argo_visit_details.eta,
		argo_visit_details.etd,argo_carrier_visit.ata,
		argo_carrier_visit.atd,ref_bizunit_scoped.id AS agent,argo_quay.id AS berth,
		IFNULL(vsl_vessel_visit_details.flex_string02,vsl_vessel_visit_details.flex_string03) AS berthop
		FROM argo_carrier_visit
		INNER JOIN argo_visit_details ON argo_visit_details.gkey=argo_carrier_visit.cvcvd_gkey
		INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_visit_details.gkey
		INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
		INNER JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=vsl_vessel_visit_details.bizu_gkey
		LEFT JOIN vsl_vessel_berthings ON vsl_vessel_berthings.vvd_gkey=vsl_vessel_visit_details.vvd_gkey
		LEFT JOIN argo_quay ON argo_quay.gkey=vsl_vessel_berthings.quay
		WHERE argo_carrier_visit.phase IN ('20INBOUND','30ARRIVED','40WORKING','50COMPLETE','60DEPARTED')
		ORDER BY argo_carrier_visit.phase";
		//echo $data['voysNo'];
		$rtnVesselList = $this->bm->dataSelect($query);
		$data['rtnVesselList']=$rtnVesselList;
		
		$data['body']="<font color='blue' size=2>LogOut Successfully....</font>";

		$this->session->sess_destroy();
		$this->cache->clean();
		//redirect(base_url(),$data);
		$this->load->view('cssVesselList');
		$this->load->view('jsVesselList');
		$this->load->view('FrontEnd/header');
		$this->load->view('FrontEnd/slider');
		$this->load->view('FrontEnd/index',$data);
		$this->load->view('FrontEnd/footer');
		$this->db->cache_delete_all();
	}
	
	function remove_numbers($string) 
	{
		$spchar = array("\n","&",'"',"'","/",">","<","^","  ","~");
		$string = str_replace($spchar, '', $string);				
		//$string=substr($string, 0, 80);
		return $string;
	}

	// SOURAV


	

	function blockWiseEquipmentList()
	{
		$session_id = $this->session->userdata('value');
		$login_id = $this->session->userdata('login_id');
		$type_of_Igm = $this->uri->segment(3);
		$search=$this->input->post('search');
		$this->load->model('ci_auth', 'bm', TRUE);
			
			$sql="select count(xps_che.short_name) as rtnValue
			from xps_che
			inner join xps_chezone on xps_chezone.che_id=xps_che.id
			 ";
			$config = array();
			$config["base_url"] = site_url("uploadExcel/blockWiseEquipmentList/$type_of_Igm");
			$config["total_rows"] = $this->bm->dataReturn($sql);
			$config["per_page"] = 20;
			$offset = $this->uri->segment(4, 0);
			$config["uri_segment"] = 4;
			$limit=$config["per_page"];
		
			$this->pagination->initialize($config);
			$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
			$start=$page;
			$equipmentList;
			/***********Pagination***************/
			if($this->input->post()){
				$search=$this->input->post('search');
				
				 $sql_data="Select distinct  sel_block Block,short_name equipement 
				 from xps_che
				 inner join xps_chezone on xps_chezone.che_id=xps_che.id  
				 where short_name = '$search'  order by 1";
				  $equipmnetRes1=$this->bm->dataSelect($sql_data);
				 $j=0;
				
				 for($i=0;$i<count( $equipmnetRes1);$i++){
					$set_block="";
					$set_block= $equipmnetRes1[$i]['BLOCK'];
			
					$sqlQuery1="SELECT COUNT(*) AS rtnValue FROM  ctmsmis.yard_block WHERE ctmsmis.yard_block.block='$set_block'";
					$numOfRow=$this->bm->dataReturnDb2($sqlQuery1);
					if($numOfRow>0){
						$equipmentList[$j]['Block']=$equipmnetRes1[$i]['BLOCK'];
						$equipmentList[$j]['equipement']=$equipmnetRes1[$i]['EQUIPEMENT'];
						$j++;

					}

				 }
							
			}
			else{
				
					$sql_data="SELECT *
					FROM (
					SELECT DISTINCT  sel_block Block,short_name equipement
					FROM xps_che
					INNER JOIN xps_chezone ON xps_chezone.che_id=xps_che.id
					WHERE short_name IS NOT NULL  AND short_name NOT LIKE 'HHT%' AND short_name NOT LIKE 'F%'
					AND short_name NOT LIKE 'SP%'
					UNION ALL
					SELECT DISTINCT  sel_block Block,short_name equipement
					FROM xps_che
					INNER JOIN xps_chezone ON xps_chezone.che_id=xps_che.id
					WHERE short_name IS NOT NULL  AND short_name LIKE 'FLT%'
								) tbl ORDER BY tbl.equipement DESC";
				 	$equipmnetRes1=$this->bm->dataSelect($sql_data);
					$j=0;
					for($i=0;$i<count( $equipmnetRes1);$i++){
						$set_block="";
					$set_block= $equipmnetRes1[$i]['BLOCK'];
					$sqlQuery1="SELECT COUNT(*)  AS rtnValue FROM ctmsmis.yard_block WHERE ctmsmis.yard_block.block='$set_block'
					AND ctmsmis.yard_block.terminal IS NOT NULL";
					$numOfRow=$this->bm->dataReturnDb2($sqlQuery1);
					if($numOfRow>0){
						$equipmentList[$j]['BLOCK']=$equipmnetRes1[$i]['BLOCK'];
						$equipmentList[$j]['EQUIPEMENT']=$equipmnetRes1[$i]['EQUIPEMENT'];
						$j++;

					}

				}
			}	
						
		
			
			//  $equipmentList = $this->bm->dataSelect($sql_data);
			
			//  $data['equipmentList']=$equipmentList;    
		 $data['equipmentList']=$equipmnetRes1;    
			$data['title']="Equipment List...";
			//$data['startChk']=$this->bm->dataReturn($strChkStartId);;
			$data['msg']="<a href='".site_url('report/containerHandlingView')."' target='_blank'>Yardwise Equipment Booking Report Today</a>";
			//$data['msg']="";
			$data['start']=$start;
			$data["links"] = $this->pagination->create_links();
			$data['login_id']=$login_id;
			
			//$this->load->view('header2');
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('EquipmentListRpt',$data);
			$this->load->view('jsAssetsList');
			//$this->load->view('footer');
		//}
	}


	
	function equipmentBookingPerform()
	{
		$ipaddr = $_SERVER['REMOTE_ADDR'];
		$login_id = $this->session->userdata('login_id');
		
		$block=$this->input->post('type');
		
		$equipment=$this->input->post('myval');
		
		$this->load->model('ci_auth', 'bm', TRUE);
			
		
		$strEqId = "select id as rtnValue from ctmsmis.mis_equip_detail where equipment='$equipment'";
		$eqiId=$this->bm->dataReturn($strEqId);
		
		$strInsertEq = "insert into ctmsmis.mis_equip_booking(equip_detail_id,block,booking_date,booking_by,ip_address) values('$eqiId','$block',now(),'$login_id','$ipaddr')";
		
		$stat = $this->bm->dataUpdate($strInsertEq);
		$data['msg']="";
		if($stat==1)
			$data['msg']="Booking successfully completed for the equipment ".$equipment." <a href='".site_url('report/dateWiseEqipAssignReport')."' target='_blank'>Block Wise Equipment Booking Lists for today</a>";
		else
			$data['msg']="Not booked yet.";
		
		$sql_data="select distinct equipment equipement from ctmsmis.mis_equip_detail
								where equipment like 'RTG%' or equipment like 'SC%' or equipment like 'RST%' order by 1";
		$equipmentList = $this->bm->dataSelect($sql_data);
		$data['equipmentList']=$equipmentList;   
		$data['title']="Update Equipment List...";
		$this->load->view('header2');
		$this->load->view('equipmentDemandListHTML',$data);
		$this->load->view('footer');
		
	}
	
	function updateEquipmentList()
	{
		$session_id = $this->session->userdata('value');
		$login_id = $this->session->userdata('login_id');
		$type_of_Igm = $this->uri->segment(3);
		$search=$this->input->post('search');
		//echo $type_of_Igm;
		$this->load->model('ci_auth', 'bm', TRUE);
		if($this->input->post()){
			$search=$this->input->post('search');
			$sql_data="select distinct equipment equipement from ctmsmis.mis_equip_detail
						where equipment='$search'";
		}
		else{
			$sql_data="select distinct equipment equipement from ctmsmis.mis_equip_detail
							where equipment like 'RTG%' or equipment like 'SC%' or equipment like 'RST%' or equipment like 'RMG%' or equipment like 'FLT%' order by 1";
			
		}	
						
		//echo("<script>console.log('QueryController: ".$sql_data."');</script>");		
		
		$equipmentList = $this->bm->dataSelectDb2($sql_data);
		$data['equipmentList']=$equipmentList;    
		$data['title']="Update Equipment List...";
		$data['msg']="";
		$data['type']=$type_of_Igm;
		$start = 0;
		$data['start']=$start;
		$data['mystatus']="1";
		$data['login_id']=$login_id;
		$this->load->view('cssAssetsList');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('updateEquipmentInformationHTML',$data);
		$this->load->view('jsAssetsList');
		//}
	}
	
	function updateEquipmentPerform()
	{
		$ipaddr = $_SERVER['REMOTE_ADDR'];
		$login_id = $this->session->userdata('login_id');
		$myval=$this->input->post('myval');
		$descValue=$this->input->post('descValue');
		$capacity=$this->input->post('capacity');
		//echo $descValue." ".$capacity;
		$strupdate = "replace into ctmsmis.mis_equip_detail(equipment,description,capacity,last_update,update_by,ip_address) values('$myval','$descValue','$capacity',now(),'$login_id','$ipaddr')";
		$stat = $this->bm->dataUpdatedb2($strupdate);
		$data['msg']="";
		if($stat==1)
			$data['msg']="Data successfully updated for the equipment ".$myval;
		else
			$data['msg']="Data not updated";
		
		$sql_data="select distinct equipment equipement from ctmsmis.mis_equip_detail
								where equipment like 'RTG%' or equipment like 'SC%' or equipment like 'RST%' order by 1";

				
		$equipmentList = $this->bm->dataSelectDb2($sql_data);
		$data['equipmentList']=$equipmentList;    
		$data['title']="Update Equipment List...";
		$this->load->view('cssAssetsList');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('updateEquipmentInformationHTML',$data);
		$this->load->view('jsAssetsList');
	}

	function equipmentStartWorkoutPerform()
	{	
		$ipaddr = $_SERVER['REMOTE_ADDR'];
		$login_id = $this->session->userdata('login_id');
		
		$block=$this->input->post('block');
		
		$equipment=$this->input->post('equipment');
		
		$this->load->model('ci_auth', 'bm', TRUE);
		$data['msg']='';
		// For Pagination***************/
		$sql="select count(xps_che.short_name) as rtnValue
		from xps_che
		inner join xps_chezone on xps_chezone.che_id=xps_che.id
		order by 1 ";
		
		$config = array();
		$config["base_url"] = site_url("uploadExcel/blockWiseEquipmentList/$type_of_Igm");
		$config["total_rows"] = $this->bm->dataReturn($sql);
		$config["per_page"] = 20;
		$offset = $this->uri->segment(4, 0);
		$config["uri_segment"] = 4;
		$limit=$config["per_page"];
		
		$this->pagination->initialize($config);
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		$start=$page;
		
		// get Equipment ID from Equipment Name...........
		$strEqId = "select id as rtnValue from ctmsmis.mis_equip_detail where equipment='$equipment'";
		$eqiId=$this->bm->dataReturnDb2($strEqId);
		if($eqiId==0)
		{
			$strInsert = "insert into ctmsmis.mis_equip_detail (equipment) values('$equipment')";
			$statInsert = $this->bm->dataUpdatedb2($strInsert);
			
			$strEqId = "select id as rtnValue from ctmsmis.mis_equip_detail where equipment='$equipment'";
			$eqiId=$this->bm->dataReturnDb2($strEqId);
		}
		// get Block Name from Equipment ID..............
		$strBlockName = "select block as rtnValue from ctmsmis.mis_equip_assign_detail where equip_detail_id='$eqiId' and Date(start_work_time)=DATE(NOW()) and start_state='1'";
		$blockName=$this->bm->dataReturnDb2($strBlockName);
		
		// Count ID from Assign Detail for insert and update............
			$strChkStartId='0';
			$strChkStartId = "select count(id) as rtnValue from ctmsmis.mis_equip_assign_detail where 
								equip_detail_id='$eqiId' and block='$blockName' and Date(start_work_time)=DATE(NOW()) and start_state='1'";
		
		$formSubmitWorkout = $this->input->post('workout');
		$formSubmitStart = $this->input->post('start');
		$formSubmitEnd = $this->input->post('end');
		
		//echo 'Work Out Button Clicked....'.$formSubmitWorkout;
		//echo 'Start Button Clicked....'.$formSubmitStart;
		//echo 'End Button Clicked....'.$formSubmitEnd;
		
		if( $formSubmitWorkout == 'Work Out' )//If Workout Button Clicked .........									
			{
				$id=$this->input->post('detailID');
			
				$strUpdateEq="update ctmsmis.mis_equip_assign_detail
							SET work_out_state='1',work_out_time=now()
							where id='$id'";
				$stat = $this->bm->dataUpdatedb2($strUpdateEq);
				if($stat==1)
					$data['msg']="Work Out for the equipment ".$equipment;
				else
					$data['msg']="Not WorkOut yet.";		
			}
		else if($formSubmitStart=='Start')
		{
			// If Start Button Clicked .............
				$jval=$this->input->post('jval');
				$shift=$this->input->post('shift'.$jval);
				//echo "ShiftName".$shift;
				
				$strInsertEq = "insert into ctmsmis.mis_equip_assign_detail (equip_detail_id,block,start_state,start_work_time,shift,assign_by,ip_address)
							values('$eqiId','$block',1,now(),'$shift','$login_id','$ipaddr')";
			
				$stat = $this->bm->dataUpdatedb2($strInsertEq);
				
				if($stat==1)
					$data['msg']="Started successfully for the equipment ".$equipment;
				else
					$data['msg']="Not started yet.";				
		}
		else if($formSubmitEnd=='End')
		{  //If End Button Clicked .........	
					$id=$this->input->post('detailID');
				
				$strUpdateEq="update ctmsmis.mis_equip_assign_detail
							SET end_state='1',end_work_time=now()
							where id='$id'";
				$stat = $this->bm->dataUpdatedb2($strUpdateEq);
				if($stat==1)
					$data['msg']="Ended Period for the equipment ".$equipment;
				else
					$data['msg']="Not started yet.";
			
		}
		else{
			
		}
		
		$sql_data="select distinct  sel_block Block,short_name equipement from xps_che
		inner join xps_chezone on xps_chezone.che_id=xps_che.id
		where short_name is not null and short_name not like 'HHT%' and short_name not like 'F%'
		and short_name not like 'SP%'
		order by 1";
		$equipmentRes = $this->bm->dataSelect($sql_data);
		$equipmentList;
		for($i=0;$i<count($equipmentRes);$i++){
			$equipmentList[$i]['Block']=$equipmentRes[$i]['BLOCK'];
			$equipmentList[$i]['equipement']=$equipmentRes[$i]['EQUIPEMENT'];

		}
		
	//	$equipmentList = $this->bm->dataSelect($sql_data);
		
		$data['equipmentList']=$equipmentList;    
		$data['title']="Equipment List...";
		$data['startChk']=$this->bm->dataReturnDb2($strChkStartId);
		$data['start']=$start;
		$data["links"] = $this->pagination->create_links();
		$data['login_id']=$login_id;
		$this->load->view('cssAssetsList');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('EquipmentListRpt',$data);
		$this->load->view('jsAssetsList');
		
	}
	
	function equipmentDemandList()
	{
		$session_id = $this->session->userdata('value');
		$login_id = $this->session->userdata('login_id');
		$type_of_Igm = $this->uri->segment(3);
		$search=$this->input->post('search');
		//echo $type_of_Igm;
		$this->load->model('ci_auth', 'bm', TRUE);
		if($this->input->post()){
			$search=$this->input->post('search');
			
			$sql_data="select distinct equipment equipement from ctmsmis.mis_equip_detail
						where equipment='$search'";
		}
		else{
			
				$sql_data="select distinct equipment equipement from ctmsmis.mis_equip_detail
							where equipment like 'RTG%' or equipment like 'SC%' or equipment like 'RST%' or equipment like 'RMG%' or  equipment like 'FLT%' order by 1";
		}	
					
		//echo("<script>console.log('QueryController: ".$sql_data."');</script>");		
		
		$equipmentList = $this->bm->dataSelect($sql_data);
		$data['equipmentList']=$equipmentList;    
		$data['title']="Equipment Demand List...";
		$data['msg']="";
		$data['type']=$type_of_Igm;
		$data['start']=$start;
		$data['mystatus']="1";
		$data['login_id']=$login_id;
		$this->load->view('header2');
		$this->load->view('equipmentDemandListHTML',$data);
		$this->load->view('footer');
	}
	
	function updateEquipmentDemandList()
	{
		$ipaddr = $_SERVER['REMOTE_ADDR'];
		$login_id = $this->session->userdata('login_id');
		$myval=$this->input->post('myval');
		
		$terminal=$this->input->post('terminalList');
		$block=$this->input->post('type');
		
		//echo("<script>console.log('Terminal: ".$terminal."Block: ".$block."');</script>");
		echo "Terminal : ".$terminal."Block ".$block."Value ".$myval;
		/*
		$strupdate = "replace into ctmsmis.mis_equip_detail(equipment,description,capacity,last_update,update_by,ip_address) values('$myval','$descValue','$capacity',now(),'$login_id','$ipaddr')";
		$stat = $this->bm->dataUpdate($strupdate);
		$data['msg']="";
		if($stat==1)
			$data['msg']="Data successfully updated for the equipment ".$myval;
		else
			$data['msg']="Data not updated";*/ 
					
		$sql_data="select distinct equipment equipement from ctmsmis.mis_equip_detail
								where equipment like 'RTG%' or equipment like 'SC%' or equipment like 'RST%' order by 1";
		$equipmentList = $this->bm->dataSelect($sql_data);
		$data['equipmentList']=$equipmentList;   
		$data['title']="Update Equipment List...";
		$this->load->view('header2');
		$this->load->view('equipmentDemandListHTML',$data);
		$this->load->view('footer');
	}
		
	function pangoanContUpload()				// 2020-06-21
	{
		$data['title']="UPLOAD EXCEL FILE FOR PANGOAN...";
		$data['msg']="";		
		
		$this->load->view('cssAssets');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('pangoanContUploadForm',$data);
		$this->load->view('jsAssets');
    }
		
	function pangoanContUploadPerform()			// 2020-06-21
	{
		$login_id = $this->session->userdata('login_id');
		$ipaddr = $_SERVER['REMOTE_ADDR'];
		$date = date('YmdHis');
		$dbDate = date('Y-m-d H:i:s');
		//echo $dbDate."<br>";
		error_reporting(E_ALL ^ E_NOTICE);   
    
		$filenm=$login_id."_".$date.".xls";
		$filetype=$_POST["file"];
			
		if ($_FILES["file"]["error"] > 0)
		{
			$data['msg'] = "<b>Error: " . $_FILES["file"]["error"] . "<br />May be your file size exceeds 2MB.Please reduce file size and try again.<br/>To reduce file size-<br/>
			Step1:Save your Excel file into CSV(.csv) format.<br/>
			Step2:Now save your CSV file into Excel(.xls) format.<br/>
			Step3:Upload new Excel(.xls) file again.</b>";
			$data['title']="UPLOAD EXCEL FILE FOR PANGOAN...";
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('pangoanContUploadForm',$data);
			$this->load->view('jsAssets');
			return;			
		}
		else
		{
			move_uploaded_file($_FILES["file"]["tmp_name"],$_SERVER['DOCUMENT_ROOT']."/resources/pangoan/uploadfile/".$_FILES["file"]["name"]);			
			rename($_SERVER['DOCUMENT_ROOT']."/resources/pangoan/uploadfile/".$_FILES["file"]["name"],$_SERVER['DOCUMENT_ROOT']."/resources/pangoan/uploadfile/".$filenm);
		}
		require_once('excel_reader2.php');
		$mydata = new Spreadsheet_Excel_Reader($_SERVER['DOCUMENT_ROOT']."/resources/pangoan/uploadfile/".$filenm);
			
		$totalrow=0;
		$excelrow=$mydata->rowcount(0);
		$i=2;
		while($i<=$excelrow)
		{
			if( trim($mydata->value($i,3))!="")
			$totalrow=$totalrow+1;
			$i=$i+1;
		}
		$row=2;  
		$i=0;
		$stat = 0;
		while($row<($totalrow+2))
		{
			$i++;
			$mlo=$mydata->value($row,2);
			$cont_no=$mydata->value($row,3);
			$visit=$mydata->value($row,7);
			$weight=$mydata->value($row,8);
			$category=$mydata->value($row,9);
			$status=$mydata->value($row,10);
			$seal=$mydata->value($row,11);	
				
			$cont_no = preg_replace('/[^A-Za-z0-9\. -]/', '', $cont_no);
			$cont_no = trim($cont_no);
				
			
            $sqlIso="SELECT ref_equip_type.id AS rtnValue,inv_unit.id FROM inv_unit
			INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey
			INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
			WHERE inv_unit.id='$cont_no' fetch FIRST 1 rows only";					
			$isoCode=$this->bm->dataReturn($sqlIso);
			
			$strQuery = "replace into ctmsmis.mis_pangoan_unit(cont_id,mlo,iso_code,visit_id,gross_weight,category,fried_kind,seal,last_update,user_id,ip_address) 
			values('$cont_no','$mlo','$isoCode','$visit','$weight','$category','$status','$seal',now(),'$login_id','$ipaddr')";
			$yes=$this->bm->dataInsertDb2($strQuery);
			if($yes==1)
				$stat = $stat+1;					
			else
				$stat = $stat;									
				
			$row=$row+1; 
		}
		if($stat>0)
			$data['msg'] ="Data successfully uploaded.";
		else
			$data['msg'] ="Data not uploaded.";	
				
		$data['title']="UPLOAD EXCEL FILE FOR PANGOAN...";		
		
		$this->load->view('cssAssets');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('pangoanContUploadForm',$data);
		$this->load->view('jsAssets');
	}
		
	function convertPanContForm()
	{
		$msg = "";
		$data['msg'] = $msg;
		$data['title']="PANGOAN CONTAINERS CONVERTING FORM...";
		$data['mystatus']="1";
		$this->load->view('cssAssets');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('convertPanContForm',$data);
		$this->load->view('jsAssets');
    }
		
	function convertPandContPerformed()
	{			
		// $this->load->view('convertPanContPerform',$data);
		$this->load->view('convertPanContPerform');
	}
		
	function uploadCnFSignatureForm()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
			if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$data['msg']="";
			//$data['unstuff_flag']="";
			//$data['verify_number']="-1";
			$data['title']="UPLOAD C&f SIGNATURE SECTION...";
			$this->load->view('header5');
			$this->load->view('uploadCnFSignatureForm',$data);
			$this->load->view('footer_1');
		}	
	}
	
	function cnfSignatureUpload()
	{
		$cnfLicense= $this->input->post('license_no'); // Get Cnf License
			
		$filenmPrefix= str_replace("/","_","$cnfLicense"); // Replace '/' to '_'
			
		$filenm=$filenmPrefix."_".basename($_FILES["file"]["name"]); // Create Signature File Name 
		$targetFile= $_SERVER['DOCUMENT_ROOT']."/pcs/resources/images/CnfSignature/".$filenm; // Target Folder where Signature file uploaded 
		$uploadOk = 1;
		$imgFileType = pathinfo($targetFile,PATHINFO_EXTENSION); // Get file extension
		
		$filetype=$_POST["file"];
		// Check if file already exists
		if (file_exists($targetFile)) 
		{
			$errMsg = "file already exists.";
			rename($_SERVER['DOCUMENT_ROOT']."/pcs/resources/images/CnfSignature/".$_FILES["file"]["name"],$targetFile);
			$uploadOk = 2;
		}
		// Check file size
		if ($_FILES["file"]["size"] > 500000) 
		{
			$errMsg = "file is too large.";
			$uploadOk = 0;
		}
		//echo "\n".$imgFileType;	
		// Allow certain file formats
		if($imgFileType != "png" && $imgFileType != "jpeg" && $imgFileType !="jpg" && $imgFileType != "gif") 
		{
			$errMsg= "only image files are allowed here.";
			$uploadOk = 0;
		}
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) 
		{
			$Msg = "<font color='red'><b>Sorry, your file was not uploaded. Cause ".$errMsg."</b></font>";
		}
		else if ($uploadOk == 2) 
		{ // if File Edited then try to reload file
			if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) 
			{
				$Msg= "<font color='green'><b>The file ". basename( $_FILES["file"]["name"]). " has been uploaded."."</b></font>";
			} 
			else 
			{
				$Msg = "<font color='red'><b>Sorry, there was an error uploading your file."."</b></font>";
			}
		}
		// if everything is ok, try to upload file & Insert Into dataBase
		else
		{
			if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) 
			{
				$strChk="select COUNT(id) as id from cnf_signature_data where cnf_license_no='$cnfLicense'";
				$chkData = $this->bm->dataSelectDb1($strChk);
				$chkVal=$chkData[0]['id'];
				if($chkVal>0)
				{
					$strInsertEq = "update cnf_signature_data set signature_path = '$filenm' where cnf_license_no='$cnfLicense'";
					$stat = $this->bm->dataInsertDB1($strInsertEq);
									
					if($stat==1)
						$Msg = "<font color='green'><b>The file ". basename( $_FILES["file"]["name"]). " has been uploaded."."</b></font>";
					else
						$Msg= "<font color='red'><b>Signature File not inserted.Please Check."."</b></font>";
				}
				else
				{
					$strInsertEq = "insert into cnf_signature_data (cnf_license_no,signature_path)
								values('$cnfLicense','$filenm')";
					$stat = $this->bm->dataInsertDB1($strInsertEq);
									
					if($stat==1)
						$Msg = "<font color='green'><b>The file ". basename( $_FILES["file"]["name"]). " has been uploaded."."</b></font>";
					else
						$Msg= "<font color='red'><b>Signature File not inserted.Please Check."."</b></font>";
				}
			} 
			else 
			{
				$Msg = "<font color='red'><b>Sorry, there was an error uploading your file."."</b></font>";
			}
		}
		//echo "Message : ".$Msg;
		$data['msg']=$Msg;
		//$data['unstuff_flag']="";
		//$data['verify_number']="-1";
		$data['title']="UPLOAD C&f SIGNATURE SECTION...";
		$this->load->view('header5');
		$this->load->view('uploadCnFSignatureForm',$data);
		$this->load->view('footer_1');
	}
		
	//-------------Export Excel Upload start---------------
	function exportExcelUpload()
	{
		$msg = "";
		$data['title']="UPLOAD EXCEL FILE...";
		$data['msg']=$msg;
		$this->load->view('cssAssets');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('myExportExcelUploadForm',$data);
		$this->load->view('jsAssets');
	}
		
	function exportExcelUploadPerform()
	{
		$login_id = $this->session->userdata('login_id');
		$ipaddr = $_SERVER['REMOTE_ADDR'];
		$date = date('YmdHis');
		$dbDate = date('Y-m-d H:i:s');
		
		error_reporting(E_ALL ^ E_NOTICE);   
    
		$filenm=$login_id."_".$date.".xls";
		$filetype=$_POST["file"];
			
		if ($_FILES["file"]["error"] > 0)
		{
			$data['msg'] = "<b>Error: " . $_FILES["file"]["error"] . "<br />May be your file size exceeds 2MB.Please reduce file size and try again.<br/>To reduce file size-<br/>
			Step1:Save your Excel file into CSV(.csv) format.<br/>
			Step2:Now save your CSV file into Excel(.xls) format.<br/>
			Step3:Upload new Excel(.xls) file again.</b>";
			$data['title']="UPLOAD EXCEL FILE...";
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('myExportExcelUploadForm',$data);
			$this->load->view('jsAssets');
			return;			
		}
		else
		{
			move_uploaded_file($_FILES["file"]["tmp_name"],$_SERVER['DOCUMENT_ROOT']."/resources/export/uploadfile/".$_FILES["file"]["name"]);			
			rename($_SERVER['DOCUMENT_ROOT']."/resources/export/uploadfile/".$_FILES["file"]["name"],$_SERVER['DOCUMENT_ROOT']."/resources/export/uploadfile/".$filenm);
		}
			
		require_once('excel_reader2.php');
		$mydata = new Spreadsheet_Excel_Reader($_SERVER['DOCUMENT_ROOT']."/resources/export/uploadfile/".$filenm);
			
		$rotation=$mydata->value(1,6);
		$str_vvd_gkey="SELECT vvd_gkey AS rtnValue FROM vsl_vessel_visit_details WHERE ib_vyg='$rotation'";
			
		$vvd_gkey=$this->bm->dataReturn($str_vvd_gkey);   //dataReturn for DB2
		
		if($vvd_gkey==null)
		{
			$data['msg']="Rotation ".$rotation." is not valid. Please provide correct rotation.";
			$data['title']="UPLOAD EXCEL FILE...";
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('myExportExcelUploadForm',$data);
			$this->load->view('jsAssets');
			return;
		}
		
		$totalrow=0;
		$excelrow=$mydata->rowcount(0);
		$i=3;
			
		while($i<=$excelrow)   //row count
		{
			if(trim($mydata->value($i,2))!="")  //3
			$totalrow=$totalrow+1;
			$i=$i+1;
		}
		
		//Validation start
		$row=3;
		$stat=0;
		$count_stowage=0;
	
		$table = "<table border='1' align='center'><tr><th>Field</th><th>Description</th></tr>";
		while($row<=($totalrow+2))  
		{
			$container=trim($mydata->value($row,2));
			$container = preg_replace('/[^A-Za-z0-9\. -]/', '', $container);
	
			$pod=$mydata->value($row,9);
			$stowage=$mydata->value($row,10);
			
			if($stowage!=null)
			{
				$stowage = preg_replace("/[^0-9,.]/", "", $stowage);
				
				if(strlen($stowage)==5)
					$stowage = "0".$stowage;
				else
					$stowage=$stowage;
				
				$sql_stow_chk="SELECT COUNT(*) AS rtnValue
				FROM ctmsmis.mis_exp_unit
				WHERE ctmsmis.mis_exp_unit.vvd_gkey='$vvd_gkey' AND ctmsmis.mis_exp_unit.stowage_pos='$stowage'";
			
				$count_stowage=$this->bm->dataReturnDb2($sql_stow_chk);  //dataReturn for DB2
			}
			
			$strcontchk="SELECT count(id) AS rtnValue FROM inv_unit WHERE id='$container'";
			
			$count_container=$this->bm->dataReturn($strcontchk);  //dataReturn for DB2
			
			// $sql_pod_check="SELECT count(ref_routing_point.id) as rtnValue FROM vsl_vessel_visit_details
			// INNER JOIN argo_visit_details ON argo_visit_details.gkey=vsl_vessel_visit_details.vvd_gkey
			// INNER JOIN ref_point_calls ON ref_point_calls.itin_gkey=argo_visit_details.itinereray
			// INNER JOIN ref_routing_point ON ref_point_calls.point_gkey=ref_routing_point.gkey 
			// WHERE vsl_vessel_visit_details.ib_vyg='$rotation' AND id='$pod'";
			
			
			$sql_pod_check="SELECT COUNT(ref_routing_point.id) AS rtnValue FROM vsl_vessel_visit_details
			INNER JOIN argo_visit_details ON argo_visit_details.gkey=vsl_vessel_visit_details.vvd_gkey
			INNER JOIN ref_point_calls ON ref_point_calls.itin_gkey=argo_visit_details.itinereray
			INNER JOIN ref_routing_point ON ref_point_calls.point_gkey=ref_routing_point.gkey 
			WHERE vsl_vessel_visit_details.ib_vyg='$rotation' AND id='$pod' ";
			
			$count_pod=$this->bm->dataReturn($sql_pod_check);  //dataReturn for DB2
				
			if($count_container==0)			//check in n4
			{
				$stat=$stat+1;
				$table .= "<tr><td>".$container."</td><td>Container is not available or Wrong Container No.</td></tr>";
			}
			else if($stowage==null)			//check blank stowage
			{
				$stat=$stat+1;
				$table .= "<tr><td>".$container."</td><td>Stowage position of container is blank</td></tr>";
			}
			else if($count_stowage>0)		//check duplicate stowage
			{
				$strStowCont="SELECT cont_id AS rtnValue
				FROM ctmsmis.mis_exp_unit
				WHERE ctmsmis.mis_exp_unit.vvd_gkey='$vvd_gkey' AND ctmsmis.mis_exp_unit.stowage_pos='$stowage'";
			
				$StowCont=$this->bm->dataReturnDb2($strStowCont);
				
				if($StowCont!=$container)
				{
					$stat=$stat+1;
					$table .= "<tr><td>".$container."</td><td>Stowage Position of container is duplicate</td></tr>";
				}					
			}
			else if($count_pod==0)			//check valid port of destination
			{
				$stat=$stat+1;
				$table .= "<tr><td>".$pod."</td><td>Port of destination is not valid</td></tr>";
			}
				
			$row++;
		}
		
		$table=$table."</table>";
		
		if($stat>0)
		{
			$data['msg']="Uploaded excel file has following errors<br>".$table."<br/>";
			$data['title']="UPLOAD EXCEL FILE...";
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('myExportExcelUploadForm',$data);
			$this->load->view('jsAssets');
			return;
		}
		
		//Validation end
			
		$row=3;  
		$i=0;
		$stat = 0;
		
		$chkShipmentTypeNotGivenCont="";
		$chkShipmentTypeStat="";
		$updateby=$this->session->userdata('login_id');
		
		while($row<=($totalrow+2))  //data insert
		{
			$container=trim($mydata->value($row,2));
			$container = preg_replace('/[^A-Za-z0-9\. -]/', '', $container);
				
			$str_gkey="SELECT gkey as rtnValue FROM inv_unit WHERE id='$container' ORDER BY gkey DESC LIMIT 1";
				
			$gkey=$this->bm->dataReturn($str_gkey);    //$gkey of inv_unit for particular container
				
			$iso=trim($mydata->value($row,3));
			$mlo=$mydata->value($row,6);
			
			if($mlo==null)
			{
				$sql_mlo="select ref_bizunit_scoped.id as rtnValue
				from inv_unit
				inner join ref_bizunit_scoped on ref_bizunit_scoped.gkey=inv_unit.line_op
				where inv_unit.id='$container' and inv_unit.category='IMPRT' order by inv_unit.gkey desc LIMIT 1";
				
				$mlo=$this->bm->dataReturn($sql_mlo);
			}
			
			$cont_status=$mydata->value($row,7);
			$weight=$mydata->value($row,8);
			$pod=$mydata->value($row,9);
			$stowage=$mydata->value($row,10);
			$stowage = preg_replace("/[^0-9,.]/", "", $stowage);
			if(strlen($stowage)==5)
				$stowage = "0".$stowage;
			else
				$stowage=$stowage;
			$loaded_time=$mydata->value($row,11);
			$seal_no=$mydata->value($row,12);
			$coming_from=$mydata->value($row,13);
			$truck_no=$mydata->value($row,14);
			$craine_id=$mydata->value($row,15);
			$commodity=$mydata->value($row,16);
			$shift=$mydata->value($row,17);
			$date=$mydata->value($row,18);
			//$shipment_type=$mydata->value($row,19);
			
			if($iso=="")
			{
				$getIsoTypeQry="SELECT ref_equip_type.id AS iso
				FROM inv_unit
				INNER JOIN inv_unit_equip ON inv_unit_equip.unit_gkey=inv_unit.gkey
				INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit_equip.eq_gkey
				INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
				WHERE inv_unit.gkey='$gkey'";
				
				$rslt_IsoType=$this->bm->dataSelect($getIsoTypeQry);
				$iso = $rslt_IsoType[0]['ISO'];
			}
				
			$str_count="SELECT COUNT(gkey) AS rtnValue FROM ctmsmis.mis_exp_unit WHERE vvd_gkey='$vvd_gkey' AND cont_id='$container' and snx_type=0";
					
			$count=$this->bm->dataReturnDb2($str_count);  //dataReturn for DB2
				
			$str_sizeheightgroup="SELECT 
			RIGHT(ref_equip_type.nominal_length,2) AS size,
			RIGHT(ref_equip_type.nominal_height,2) AS height,
			ref_equip_type.iso_group AS isogroup 
			FROM ref_equip_type WHERE id='$iso'";
				
			$rslt_sizeheightgroup=$this->bm->dataSelect($str_sizeheightgroup);
				
			$size=$rslt_sizeheightgroup[0]['SIZE'];
			$height=$rslt_sizeheightgroup[0]['HEIGHT'];
			$isoGroup=$rslt_sizeheightgroup[0]['ISOGROUP'];
					
			// if($count>0)
			// {
			// 	//last update not in update
			// 	$str_pgkey="SELECT gkey AS rtnValue FROM ctmsmis.mis_exp_unit WHERE vvd_gkey='$vvd_gkey' AND cont_id='$container' and snx_type=0";
					
			// 	$presentGky=$this->bm->dataReturnDb2($str_pgkey);  //$presentGky from excel file
			// 	$str_update = "";
			// 	if($presentGky!=$gkey)
			// 	{
			// 		$str_update="UPDATE ctmsmis.mis_exp_unit SET gkey='$gkey',cont_id='$container',isoType='$iso',cont_size='$size',cont_height='$height',isoGroup='$isoGroup',cont_status='$cont_status',cont_mlo='$mlo',vvd_gkey='$vvd_gkey',rotation='$rotation',stowage_pos='$stowage',user_id='$updateby',seal_no='$seal_no',goods_and_ctr_wt_kg='$weight',pod='$pod',truck_no='$truck_no',re_status=1,craine_id='$craine_id',last_update=NOW(),updated_in_n4=1,coming_from='$coming_from',shift='$shift',date='$date' WHERE cont_id='$container' AND vvd_gkey='$vvd_gkey' and snx_type=0";
			// 	}
			// 	else
			// 	{
			// 		$str_update="UPDATE ctmsmis.mis_exp_unit SET gkey='$gkey',cont_id='$container',isoType='$iso',cont_size='$size',cont_height='$height',isoGroup='$isoGroup',cont_status='$cont_status',cont_mlo='$mlo',vvd_gkey='$vvd_gkey',rotation='$rotation',stowage_pos='$stowage',user_id='$updateby',seal_no='$seal_no',goods_and_ctr_wt_kg='$weight',pod='$pod',truck_no='$truck_no',re_status=1,craine_id='$craine_id',updated_in_n4=1,coming_from='$coming_from',shift='$shift',date='$date' WHERE cont_id='$container' AND vvd_gkey='$vvd_gkey' and snx_type=0";
			// 	}
					
			// 	$yes=$this->bm->dataUpdateDb2($str_update); //dataUpdatedb2 for DB2
				
			// 	//keep log when a user (not admin) uploads container multiple times for a particular rotation
			// 	$sql_update_log="INSERT INTO ctmsmis.mis_exp_unit_update_log(cont_id,rotation,update_at,update_by,ip_address)
			// 					VALUES('$container','$rotation',NOW(),'$updateby','$ipaddr')";
								
			// 	$log_write=$this->bm->dataInsertDb2($sql_update_log); //dataInsert for DB2
			// }
			// else
			// {
			// 	$str_insert="INSERT INTO ctmsmis.mis_exp_unit(gkey,cont_id,cont_status,cont_mlo,isoType,cont_size,cont_height,isoGroup,vvd_gkey,rotation,stowage_pos,last_update,updated_in_n4,user_id,seal_no,goods_and_ctr_wt_kg,pod,truck_no,re_status,craine_id,coming_from,shift,date) 
			// 	VALUES ('$gkey','$container','$cont_status','$mlo','$iso','$size','$height','$isoGroup','$vvd_gkey','$rotation','$stowage',now(),1,'$updateby','$seal_no','$weight','$pod','$truck_no',1,'$craine_id','$coming_from','$shift','$date')";
					
			// 	$yes=$this->bm->dataInsertDb2($str_insert); //dataInsert for DB2
			// }

			if($yes==1)
				$stat = $stat+1;					
			else
				$stat = $stat;	
		
			$row=$row+1;
		}  
			
		if($stat>0)
			$data['msg'] ="Successful";
		else
			$data['msg'] ="Failed";	
		
		$data['title']="UPLOAD EXCEL FILE...";
		$this->load->view('cssAssets');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('myExportExcelUploadForm',$data);
		$this->load->view('jsAssets');
	}
	//-------------Export Excel Upload end---------------
	/*function exportExcelUpload()
	{
		$data['title']="UPLOAD EXCEL FILE...";
		$this->load->view('header2');
		$this->load->view('myExportExcelUploadForm',$data);
		$this->load->view('footer');
	}*/
		
	
	//-------------Export Excel Upload end---------------
		
	//EDI and Excel/PDF Upload start
	function ediUpload()
	{
		$id=$this->uri->segment(3);
			
		$data['title']="UPLOAD FILE...";
		$data['id']=$id;
		$data['msg']="";

		$this->load->view('cssAssets');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('ediUploadForm',$data);
		$this->load->view('jsAssets');
	}
		
	function ediUploadPerform()
	{
		$login_id = $this->session->userdata('login_id');
		$ipaddr = $_SERVER['REMOTE_ADDR'];
		$serverIp = $_SERVER['SERVER_ADDR'];
		
		error_reporting(E_ALL ^ E_NOTICE);
			
		if ($_FILES["edi"]["error"] > 0)
		{
			$data['msg'] = "<b>Error: Try again<b>";
			$data['title']="UPLOAD FILE...";
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('ediUploadForm',$data);
			$this->load->view('jsAssets');
			return;			
		}
		else
		{
			$rot=$this->input->post('rotation');
			$rotation=str_replace('/', '_', $rot); 
				
			$filenm1=$_FILES["edi"]["name"];
			$ext1 = explode(".", $filenm1);
			$fileExt1 = end($ext1);
			// $filenmedi=date(Y_m)."/".$rotation.".".$fileExt1;		//assigned new name for edi file
			$filenmedi=$rotation.".".$fileExt1;	

			// $path = $_SERVER['DOCUMENT_ROOT']."/resources/edi/".date(Y_m);
			$path = $_SERVER['DOCUMENT_ROOT']."/resources/edi";

			// if(!file_exists($path)){
			// 	mkdir($path, 0777, true);
			// 	chmod($path, 0777);
			// }

			move_uploaded_file($_FILES["edi"]["tmp_name"],$path."/".$_FILES["edi"]["name"]);		
			rename($path."/".$_FILES["edi"]["name"],$_SERVER['DOCUMENT_ROOT']."/resources/edi/".$filenmedi);

			// save file in 185 server 
			$pcsResult = file_get_contents('http://119.18.146.245/resources/edi/pullFile.php?ediFileName='.$filenmedi.'&ip='.$serverIp);
			return;
			if($_FILES["excel"]["error"] <= 0)
			{
				$filenm2=$_FILES["excel"]["name"];
				$ext2 = explode(".", $filenm2);
				$fileExt2 = end($ext2);
				// $filenmstow=date(Y_m)."/".$rotation.".".$fileExt2;	//assigned new name for	excel/pdf file
				$filenmstow=$rotation.".".$fileExt2;

				move_uploaded_file($_FILES["excel"]["tmp_name"],$path."/".$_FILES["excel"]["name"]);		
				rename($path."/".$_FILES["excel"]["name"],$_SERVER['DOCUMENT_ROOT']."/resources/edi/".$filenmstow);

				// save file in 185 server 
				$pcsResult = file_get_contents('http://119.18.146.245/resources/edi/pullFile.php?ediFileName='.$filenmstow.'&ip='.$ipaddr);
			}
			else
			{
				$filenmstow="";
			}

			return;
			
			$imp_voyage=$this->input->post('imp_voyage');
			$exp_voyage=$this->input->post('exp_voyage');
			$vslName=$this->input->post('vslName');		// intakhab - 2022-06-11
			$grt=$this->input->post('grt');
			$nrt=$this->input->post('nrt');
			$imo_no=$this->input->post('imo_no');
			$loa=$this->input->post('loa');
			$flag=$this->input->post('flag');
			$call_sign=$this->input->post('call_sign');
			$beam=$this->input->post('beam');
				
			$strid="SELECT id as rtnValue FROM igm_masters WHERE Import_Rotation_No='$rot'";
			
			$igm_masters_id=$this->bm->dataReturnDb1($strid);  
				
			$count_id="SELECT COUNT(igm_masters_id) AS rtnValue FROM edi_stow_info WHERE igm_masters_id='$igm_masters_id'";
				
			$rtn_count_id=$this->bm->dataReturnDb1($count_id);
				
			if($rtn_count_id>0)
			{
				$stow_info_update="UPDATE edi_stow_info SET file_name_edi='$filenmedi',file_name_stow='$filenmstow',file_upload_by='$login_id',file_upload_date=NOW() WHERE igm_masters_id='$igm_masters_id'";
					
				$update=$this->bm->dataUpdateDB1($stow_info_update);
			}
				
			else
			{
				$stow_info_insert="INSERT INTO edi_stow_info(igm_masters_id,file_name_edi,file_name_stow,file_upload_by,file_upload_date) VALUES('$igm_masters_id','$filenmedi','$filenmstow','$login_id',NOW())";
				
				$insert=$this->bm->dataInsertDB1($stow_info_insert);
			}
				
			// $igm_masters_update="UPDATE igm_masters
			// SET Voy_No='$imp_voyage',VoyNoExp='$exp_voyage',grt='$grt',nrt='$nrt',imo='$imo_no',loa_cm='$loa',flag='$flag',radio_call_sign='$call_sign',beam_cm='$beam' WHERE id='$igm_masters_id'";
			
			$igm_masters_update="UPDATE igm_masters
			SET Voy_No='$imp_voyage',VoyNoExp='$exp_voyage',Vessel_Name='$vslName',grt='$grt',nrt='$nrt',imo='$imo_no',loa_cm='$loa',flag='$flag',radio_call_sign='$call_sign',beam_cm='$beam' WHERE id='$igm_masters_id'";
				
			$update=$this->bm->dataUpdateDB1($igm_masters_update);
				
			$stat=1;
		}
			
		if($stat>0)
			$msg="Successful";
		else
			$msg="Failed";
			
		$data['title']="UPLOAD FILE...";
		$data['msg']=$msg;
		$this->load->view('cssAssets');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('ediUploadForm',$data);
		$this->load->view('jsAssets');
	}
	//EDI and Excel/PDF Upload end
		
	//ICD EXCEL FILE UPLOAD START
		
	function uploadIcdExcel()			// 2020-06-18
	{
		$data['title']="UPLOAD EXCEL FILE...";
		$data['msg'] ="";			
		
		$this->load->view('cssAssets');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('uploadIcdExcelHTML',$data);
		$this->load->view('jsAssets');
	}
		
	function uploadIcdExcelPerform()		// 2020-06-18
	{
		$login_id = $this->session->userdata('login_id');
		$ipaddr = $_SERVER['REMOTE_ADDR'];
		$date = date('YmdHis');
		$dbDate = date('Y-m-d H:i:s');
		//echo $dbDate."<br>";
		error_reporting(E_ALL ^ E_NOTICE);   

		$filenm=$login_id."_".$date.".xls";
		$filetype=$_POST["file"];
		
		if ($_FILES["file"]["error"] > 0)
		{
			$data['msg'] = "<b>Error: " . $_FILES["file"]["error"] . "<br />May be your file size exceeds 2MB.Please reduce file size and try again.<br/>To reduce file size-<br/>
			Step1:Save your Excel file into CSV(.csv) format.<br/>
			Step2:Now save your CSV file into Excel(.xls) format.<br/>
			Step3:Upload new Excel(.xls) file again.</b>";
			$data['title']="UPLOAD EXCEL FILE...";
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('uploadIcdExcelHTML',$data);
			$this->load->view('jsAssets');
			return;			
		}
		else
		{
			move_uploaded_file($_FILES["file"]["tmp_name"],$_SERVER['DOCUMENT_ROOT']."/resources/uploadExcelFile/".$_FILES["file"]["name"]);			
			rename($_SERVER['DOCUMENT_ROOT']."/resources/uploadExcelFile/".$_FILES["file"]["name"],$_SERVER['DOCUMENT_ROOT']."/resources/uploadExcelFile/".$filenm);
		}
		
		@require_once('excel_reader2.php');
		$mydata = new Spreadsheet_Excel_Reader($_SERVER['DOCUMENT_ROOT']."/resources/uploadExcelFile/".$filenm);
		
		
		$totalrow=3; //note: last 3 row not upload. i.e, number of total row increase by 3.
		$excelrow=$mydata->rowcount(0);
		$i=0;

		while($i<=$excelrow)   //row count
		{
			if($mydata->value($i,2)!="") 
			$totalrow=$totalrow+1;
			$i++;
		}
		
		$row=7;  
		$i=0;
		$stat = 0;
			
		$prob = "<table border='1'><tr><td>Container</td><td>Description</td></tr>";
		$st = 0;
		$totCont = "";			
		while($row<=($totalrow))
		{
			//$pod_chk=trim($mydata->value($row,13));
			$cont_id=trim($mydata->value($row,3));

			
			$chekCont="SELECT count(ID_FULL) as rtnValue FROM REF_EQUIPMENT WHERE REF_EQUIPMENT.ID_FULL='$cont_id'";
		//	echo $chekCont;
			$foundContainer = $this->bm->dataReturn($chekCont);
			
			if($foundContainer==0)
			{					
				$prob .= "<tr><td>".$cont_id."</td><td>Not Found in the system.</td></tr>";
				$st = $st+1; 
				$totCont .=$cont_id.", ";
			}			
			
			$row=$row+1; 
		}
		$prob .= "<tr><td colspan='2'>".$totCont."</td></tr>";
		$prob .= "</table>";
		
		
		if($st>0)
		{
			$data['msg'] = "IN YOUR EXCEL FILE LISTED PROBLEM(S) FOUND<br>".$prob."<br> PLEASE CORRECT & TRY AGAIN...";
			$data['title']="UPLOAD EXCEL FILE...";
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('uploadIcdExcelHTML',$data);
			$this->load->view('jsAssets');
			return;
		}
		else
		{
			$row=7;  
			$i=0;
			$stat = 0;
			
			while($row<=($totalrow)) 
			{
				$mlo=$mydata->value($row,2);
				$cont_id=$mydata->value($row,3);
				$iso=$mydata->value($row,4);
				$trans_mode=$mydata->value($row,5);
				$trans_type=$mydata->value($row,6);
				$trans_operator=$mydata->value($row,7);
				$train_visit_id=$mydata->value($row,8);
				$slot=$mydata->value($row,9);
				$transport_id=$mydata->value($row,10);
				$gross_wt=$mydata->value($row,11);
				$export=$mydata->value($row,12);
				$status=$mydata->value($row,13);
				$seal=$mydata->value($row,14);
				
				$updateby=$this->session->userdata('login_id');
				$ipaddr = $_SERVER['REMOTE_ADDR'];
				
				$insertStr="REPLACE INTO ctmsmis.mis_icd_unit(mlo, cont_id, iso_code, trans_mode, trans_type, trans_operator, visit_id, slot, transport_id, gross_weight, category, fried_kind, seal, last_update, user_id, ip_address) 
					VALUES ('$mlo','$cont_id','$iso','TRAIN','$trans_type', '$trans_operator','$train_visit_id','$slot','$transport_id','$gross_wt','$export','$status','$seal', now(), '$updateby','$ipaddr')";
				// echo $insertStr;return;	
				$yes=$this->bm->dataInsertDb2($insertStr);
				if($yes==1)
					$stat = $stat+1;					
				else
					$stat = $stat;	
				
		
				$row=$row+1;
			}				
				
			if($stat>0)
				$data['msg'] ="Successful";
			else
				$data['msg'] ="Failed";	
					
			$data['title']="UPLOAD EXCEL FILE...";
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('uploadIcdExcelHTML',$data);
			$this->load->view('jsAssets');
		}
	}
	//ICD EXCEL FILE UPLOAD END
		
	//ICD EXCEL FILE CONVERT TO XML START
			
	function convertIcdFileForm()			// 2020-06-18
	{
		$data['title']="ICD CONTAINERS CONVERTING FORM...";
		$data['mystatus']="1";
		$data['msg']="";
		
		$this->load->view('cssAssets');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('convertIcdFileForm',$data);
		$this->load->view('jsAssets');
    }
		
	function convertIcdFilePerform()
	{
		$data['msg']="";
		//$this->load->view('convertIcdFilePerform',$data);
		$this->load->view('convertIcdFile',$data);
	}
	
	//ICD EXCEL FILE CONVERT TO XML END
		
	/*----Last 24 hrs Report Statement*/
	function last24hrsStatements()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$login_id = $this->session->userdata('login_id');	
			$query="SELECT u_name as rtnValue FROM users WHERE login_id='$login_id'";
			$offDock= $this->bm->dataReturnDb1($query);
			//echo $query;
			//return;
			$msg="";
			$msgFlag=0;
			$editFlag=0;
			$ctime=8;
			$time=' 08:00:00';
			$offDockId = $this->Offdock($login_id);
			
			$sql_time="SELECT HOUR(NOW()) AS ctime,TIMEDIFF(CONCAT(DATE(NOW()),' 08:00:00'),NOW()) AS diff";			
			$rslt_time=$this->bm->dataSelectDb1($sql_time);
			$diff=$rslt_time[0]['diff'];
			$ctime=$rslt_time[0]['ctime'];
			
			
			$permit_time_query="SELECT  ctmsmis.offdock_upload_permission.permit_time as rtnValue from ctmsmis.offdock_upload_permission 
								where ctmsmis.offdock_upload_permission.offdock_code='$offDockId' and 
								ctmsmis.offdock_upload_permission.permit_date=date(now())";		
			@$permit_time=$this->bm->dataReturnDb2($permit_time_query);
			if ($permit_time!="")
			{
				$time=" ".$permit_time;
				$permit_time=" ".$permit_time;
			}
		
			if ($permit_time!="")
			{
				$sql_permission_time="SELECT TIMEDIFF(CONCAT(DATE(NOW()),'$permit_time'),NOW()) AS diff";			
				$permission_time=$this->bm->dataSelectDb1($sql_permission_time);
				$new_diff=$permission_time[0]['diff'];
			
				if($new_diff!="")
				{
					$diff=$new_diff;
				}
				$msgFlag=1;
				$data['msgFlag']=$msgFlag;
			}
			
			$data['msg']=$msg;
			$data['msgFlag']=$msgFlag;
			$data['editFlag']=$editFlag;
			$data['ctime']=$ctime;
			$data['diff']=$diff;
			$data['time']=$time;
			$data['rslt_time']=$rslt_time;
			$data['title']="Last 24hrs Statement";
			$data['offDock']=$offDock;
			$data['updateFlag']=0;
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('last24hrsStatements',$data);
			$this->load->view('jsAssets');
		}
	}
		
	function last24hrsOffDocStatement()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
			if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$login_id = $this->session->userdata('login_id');
			$akey=$this->input->post('akey');	

			$ipaddr = $_SERVER['REMOTE_ADDR'];
			$date=$this->input->post('date');
			$offDock=$this->input->post('offDock');
			$capacity=$this->input->post('capacity');
			$impCont=$this->input->post('impCont');
			$expCont=$this->input->post('expCont');
			$emptyCont=$this->input->post('emptyCont');
			$total=$this->input->post('total');
			$last24stuff=$this->input->post('last24stuff');
			$p2dLaden=$this->input->post('p2dLaden');
			$p2dEmpty=$this->input->post('p2dEmpty');
			$d2pLaden=$this->input->post('d2pLaden');
			$d2pEmpty=$this->input->post('d2pEmpty');
			$remarks=$this->input->post('remarks');
			//echo $total;
			//return; 
			$offDockId = $this->Offdock($login_id);
			//$query="SELECT id as rtnValue FROM users WHERE login_id='$login_id'";
			//$offDockId= $this->bm->dataReturnDb1($query);
				
			if($this->input->post('update'))
			{
				$strInsertEq = "UPDATE ctmsmis.offdoc_statement SET stmt_date ='$date', offdoc_code = '$offDockId',
									capacity='$capacity', imp_lying ='$impCont',exp_lying='$expCont', mty_lying='$emptyCont',
									total_teus='$total',last_24hrs='$last24stuff', port_to_depo_laden='$p2dLaden', port_to_depo_mty='$p2dEmpty', 
									depo_to_port_laden='$d2pLaden', depo_to_port_mty='$d2pEmpty',
									remarks='$remarks', last_update=now(), update_by='$login_id', ip_address='$ipaddr' WHERE ctmsmis.offdoc_statement.akey=$akey";
				//echo $strInsertEq ;
				//return; 
				$updateStat = $this->bm->dataUpdatedb2($strInsertEq);
				if($updateStat>=1)
				{
					$data['msg']="Successfully Updated"; 
				}
				else
					$data['msg']="Not Updated";
				
			}
			else
			{
				$strInsertEq = "insert into ctmsmis.offdoc_statement(stmt_date, offdoc_code, capacity, imp_lying, exp_lying, mty_lying, total_teus, last_24hrs,
				port_to_depo_laden, port_to_depo_mty, depo_to_port_laden, depo_to_port_mty, remarks, last_update, update_by, ip_address) 
				values('$date', '$offDockId', '$capacity', '$impCont', '$expCont', '$emptyCont', '$total', '$last24stuff', '$p2dLaden',
				'$p2dEmpty', '$d2pLaden', '$d2pEmpty', '$remarks', now(), '$login_id', '$ipaddr')";  
				$stat = $this->bm->dataInsertDb2($strInsertEq);
				if($stat>=1)
				{
					$data['msg']="Successfully Saved"; 
				}
				else
					$data['msg']="Not Saved";
				
			}
			$query="SELECT u_name as rtnValue FROM users WHERE login_id='$login_id'";
			$offDock= $this->bm->dataReturnDb1($query);
				
			$data['title']="Last 24hrs Statement";
			$data['offDock']=$offDock;
			$data['updateFlag']=0;
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('last24hrsStatements',$data);
			$this->load->view('jsAssets');
		}
	}
	
	function last24hrsStatementList()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
			if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$login_id = $this->session->userdata('login_id');	
			$query="SELECT * FROM ctmsmis.offdoc_statement WHERE update_by='$login_id'";
			$offDock= $this->bm->dataSelectDb2($query);
			//echo $offDock[0]['offdoc_code'];
			//return;
				
			$data['title']="Last 24hrs Statement List";
			$data['offDock']=$offDock;
			$data['delFlag']=0;
						
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('last24hrsStatementList',$data);
			$this->load->view('jsAssetsList');
		}
	}
		
	function last24hrsOffDocStatementEdit()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$akey=$this->input->post('akey');
			$login_id = $this->session->userdata('login_id');	
			$query="SELECT * FROM ctmsmis.offdoc_statement WHERE akey='$akey'";
			$offDockEditList= $this->bm->dataSelect($query);
			//echo $offDockEditList[0]['offdoc_code'];
			//return;
			$query="SELECT u_name as rtnValue FROM users WHERE login_id='$login_id'";
			$offDock= $this->bm->dataReturnDb1($query);
			$data['title']="Last 24hrs Statement List";
			$data['offDock']=$offDock;
			$data['editFlag']=1;
			$data['updateFlag']=1;
				
			$data['offDockEditList']=$offDockEditList;
						
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('last24hrsStatements',$data);
			$this->load->view('jsAssetsList');
		}
	}
		
	function last24hrsOffDocStatementDelete()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$akey=$this->input->post('akey1');
			$login_id = $this->session->userdata('login_id');	
			$query="delete from ctmsmis.offdoc_statement  WHERE akey='$akey'";
			$deleteStat= $this->bm->dataDelete($query);
			$data['delFlag']="1";
			if($deleteStat==1)
			{
				$data['msg2']="Successfully Deleted";
			}
			else
				$data['msg2']="Not Deleted";
				
			//echo $offDockEditList[0]['offdoc_code'];
			//return;
			$query="SELECT * FROM ctmsmis.offdoc_statement WHERE update_by='$login_id'";
			$offDock= $this->bm->dataSelect($query);
			//echo $offDock[0]['offdoc_code'];
			//return;
				
			$data['title']="Last 24hrs Statement List";
			$data['offDock']=$offDock;
						
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('last24hrsStatementList',$data);
			$this->load->view('jsAssetsList');
		}
	}
		
	function last24hrsOffDocStatementPdf()
	{
		$akey=$this->input->post('akey2');
		$offdockName=$this->input->post('offdockName');
					
		$login_id = $this->session->userdata('login_id');
		$stmt_date = "";
		$capacity = "";
		$imp_lying = "";
		$exp_lying = "";
		$mty_lying = "";
		$total_teus = "";
		$last_24hrs = "";
		$port_to_depo_laden = "";
		$port_to_depo_mty = "";
		$depo_to_port_laden = "";
		$depo_to_port_mty = "";
		$remarks = "";
		
		$query="SELECT * FROM ctmsmis.offdoc_statement WHERE akey='$akey'";
		$offDockData= $this->bm->dataSelectDb2($query);
		for($x=0; $x<count($offDockData); $x++)
		{
			$stmt_date=$offDockData[$x]['stmt_date'];
			$capacity=$offDockData[$x]['capacity'];
			$imp_lying=$offDockData[$x]['imp_lying'];
			$exp_lying=$offDockData[$x]['exp_lying'];
			$mty_lying=$offDockData[$x]['mty_lying'];
			$total_teus=$offDockData[$x]['total_teus'];
			$last_24hrs=$offDockData[$x]['last_24hrs'];
			$port_to_depo_laden=$offDockData[$x]['port_to_depo_laden'];
			$port_to_depo_mty=$offDockData[$x]['port_to_depo_mty'];
			$depo_to_port_laden=$offDockData[$x]['depo_to_port_laden'];
			$depo_to_port_mty=$offDockData[$x]['depo_to_port_mty'];
			$remarks=$offDockData[$x]['remarks'];
		}
		$this->data['offDockData']=$offDockData;
		$this->data['stmt_date']=$stmt_date;
		$this->data['capacity']=$capacity;
		$this->data['imp_lying']=$imp_lying;
		$this->data['exp_lying']=$exp_lying;
		$this->data['mty_lying']=$mty_lying;
		$this->data['total_teus']=$total_teus;
		$this->data['last_24hrs']=$last_24hrs;
		$this->data['port_to_depo_laden']=$port_to_depo_laden;
		$this->data['port_to_depo_mty']=$port_to_depo_mty;
		$this->data['depo_to_port_laden']=$depo_to_port_laden;
		$this->data['depo_to_port_mty']=$depo_to_port_mty;
		$this->data['remarks']=$remarks;

		$offdockQuery="SELECT u_name as rtnValue FROM users WHERE login_id='$login_id'";
		$offDock= $this->bm->dataReturnDb1($offdockQuery);		
				
		$offdockNameQueryForAdmin="SELECT u_name as rtnValue FROM users WHERE login_id='$offdockName'";
		$offDockName= $this->bm->dataReturnDb1($offdockNameQueryForAdmin);
		if($login_id=='admin')
		{
			$this->data['offDock']=$offDockName;
		}
		else
		{
			$this->data['offDock']=$offDock;
		}
		$this->data['title']="Last 24hrs Statement List";
		//$this->load->view('last24hrsStatementListPdf',$data);
		
		
		$this->load->library('M_pdf');
		$html=$this->load->view('last24hrsStatementListPdf',$this->data, true); 
		$pdfFilePath ="Last24hrsStatementListPdf-".time()."-download.pdf";
		$pdf = $this->m_pdf->load();
		$pdf->allow_charset_conversion = true;
		$pdf->charset_in = 'iso-8859-4';
		$stylesheet = file_get_contents('assets/stylesheets/test.css'); // external css
		$mpdf->shrink_tables_to_fit = 1;
		$pdf->WriteHTML($stylesheet,1);
		//$pdf->setFooter('Developed By : DataSoft|Page {PAGENO}|Date {DATE j-m-Y}');
		$pdf->WriteHTML($html,2);
		$pdf->Output($pdfFilePath, "I"); // For Show Pdf	
	}
	/*----Last 24 hrs Report Statement*/
		
	//DOWLOAD EXCEL SAMPLE FOR EDI DOWNLOAD START
		
	function ediDownloadSample()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$data['title']="DOWLOAD EXCEL SAMPLE FOR EDI DOWNLOAD";
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('ediDownloadSample',$data);
			$this->load->view('jsAssets');
					
			//$this->load->view('header2');
			//$this->load->view('ediDownloadSample',$data);
			//$this->load->view('footer');
		}
	}
		
	function ediDownloadSampleView()
	{
		$rotNo=$this->input->post('rotNo');
		$type=$this->input->post('options');
		//echo $type;
		$fileSample = str_replace("/", "_", $rotNo);
			
		$query="SELECT cont_id,isoType,cont_mlo,cont_status,goods_and_ctr_wt_kg,bookingNo,seal_no,stowage_pos,'BDCGP' as load_port,pod FROM ctmsmis.mis_exp_unit WHERE rotation='$rotNo'";
		$result= $this->bm->dataSelectDb2($query);	
		//print_r($result);	
		$query2="SELECT Voy_No from igm_masters WHERE Import_Rotation_No='$rotNo'";
		$result2= $this->bm->dataSelectDb1($query2);
			
		//echo $result2[0]['Voy_No'];
		//return;
			
		$query1="SELECT vsl_vessels.name,radio_call_sign, 'BDCGP' AS LOP
		FROM vsl_vessel_visit_details
		INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
		WHERE vsl_vessel_visit_details.ib_vyg='$rotNo' fetch first 1 rows only";
		$result1= $this->bm->dataSelect($query1);
		//echo  $query;
		//return;

		$data['type']=$type;
		$data['fileSample']=$fileSample;
		$data['result']=$result;
		$data['result1']=$result1;
		$data['result2']=$result2;
		$this->load->view('ediDownloadSampleView',$data);
	}
		
	//DOWLOAD EXCEL SAMPLE FOR EDI DOWNLOAD END
		
	//STUFFING CONTAINER Excel start
	function stuffingContExcel()			
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();	
		}
		else
		{
			$flag=1;
			
			$org_Type_id=$this->session->userdata('org_Type_id');
			
			$data['title']="UPLOAD EXCEL FOR LAST 24 HOURS STUFFING CONTAINER...";		
			$data['flag']=$flag;
			
			$login_id = $this->session->userdata('login_id');
			
			$depo_code=$this->Offdock($login_id);
			
			$sql_cnt_permit="select count(*) as rtnValue 
			from ctmsmis.offdock_upload_permission 
			where offdock_code='$depo_code' and permit_date=date(now())";
			
			$count=$this->bm->dataReturnDb2($sql_cnt_permit);
			
			if($count==1)
			{
				$sql_permit="select concat(permit_date,' ',permit_time) as upperLimitTmp,hour(permit_time) as upperLimit
				from ctmsmis.offdock_upload_permission 
				where offdock_code='$depo_code' and permit_date=date(now())";
				
				$rslt_permit=$this->bm->dataSelectDb2($sql_permit);
				
				$upperLimitTmp=$rslt_permit[0]['upperLimitTmp'];
				$upperLimit=$rslt_permit[0]['upperLimit'];
			
				$sql_time="SELECT HOUR(NOW()) AS ctime,TIME(NOW()) as ctimetmp,DATE(NOW()) as cdate";
		
				$rslt_time=$this->bm->dataSelectDb1($sql_time);
				$ctime=$rslt_time[0]['ctime'];						
				$cdate=$rslt_time[0]['cdate'];
				$ctimetmp=$rslt_time[0]['ctimetmp'];
				
				$lowerLimitTmp=$cdate.$ctimetmp;						
				$lowerLimit=$ctime;						
			
				$upperLimitTmp = strtotime($upperLimitTmp);
				$lowerLimitTmp = strtotime($lowerLimitTmp);
			//	$diff=round(abs($upperLimitTmp - $lowerLimitTmp) / 60,2);		
				$diff=round(($upperLimitTmp - $lowerLimitTmp) / 60,2);
				if($diff<0)
					$diff=null;
			}
			else
			{
			//	$sql_time="SELECT HOUR(NOW()) AS ctime,TIMEDIFF(CONCAT(DATE(NOW()),' 10:00:00'),NOW()) AS diff";
				$sql_time="SELECT HOUR(NOW()) AS ctime,TIMEDIFF(CONCAT(DATE(NOW()),' 08:00:00'),NOW()) AS diff";
			
				$rslt_time=$this->bm->dataSelectDb1($sql_time);
				$ctime=$rslt_time[0]['ctime'];
				$diff=$rslt_time[0]['diff'];
				
				$str=substr($diff,0,1); 
				
				if($str=="-")
					$diff=null;
				
				/* $lowerLimit=9;
				$upperLimit=10; */
				
				$lowerLimit=7;
				$upperLimit=8;
			}
			
			$data['lowerLimit']=$lowerLimit;
			$data['upperLimit']=$upperLimit;
			
			$data['ctime']=$ctime;
			$data['diff']=$diff;
			$data['org_Type_id']=$org_Type_id;		
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('stuffingContExcel',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function stuffingContExcelPerform()			
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();	
		}
		else
		{
			$login_id = $this->session->userdata('login_id');
			$ipaddr = $_SERVER['REMOTE_ADDR'];
			$date = date('YmdHis');
			$dbDate = date('Y-m-d H:i:s');
			//echo $dbDate."<br>";
			$offdock=$this->input->post('offdock');
			
			$org_Type_id=$this->session->userdata('org_Type_id');
			
			$data['org_Type_id']=$org_Type_id;
			
			/* $lowerLimit=9;
			$upperLimit=10; */
			
			$lowerLimit=7;
			$upperLimit=8;
			
			error_reporting(E_ALL ^ E_NOTICE);   
    
			$filenm=$login_id."_".$date.".xls";
			$filetype=$_POST["file"];
			
			if ($_FILES["file"]["error"] > 0)
			{
				$data['msg'] = "<b>Error: " . $_FILES["file"]["error"] . "<br />May be your file size exceeds 2MB.Please reduce file size and try again.<br/>To reduce file size-<br/>
				Step1:Save your Excel file into CSV(.csv) format.<br/>
				Step2:Now save your CSV file into Excel(.xls) format.<br/>
				Step3:Upload new Excel(.xls) file again.</b>";
				$data['title']="UPLOAD EXCEL FOR LAST 24 HOURS STUFFING CONTAINER...";
				
				$login_id = $this->session->userdata('login_id');
			
				$depo_code=$this->Offdock($login_id);
				
				$sql_cnt_permit="select count(*) as rtnValue 
				from ctmsmis.offdock_upload_permission 
				where offdock_code='$depo_code' and permit_date=date(now())";
				
				$count=$this->bm->dataReturnDb2($sql_cnt_permit);
			
				if($count==1)
				{
					$sql_permit="select concat(permit_date,' ',permit_time) as upperLimitTmp,hour(permit_time) as upperLimit
					from ctmsmis.offdock_upload_permission 
					where offdock_code='$depo_code' and permit_date=date(now())";
					
					$rslt_permit=$this->bm->dataSelectDb2($sql_permit);
					
					$upperLimitTmp=$rslt_permit[0]['upperLimitTmp'];
					$upperLimit=$rslt_permit[0]['upperLimit'];
				
					$sql_time="SELECT HOUR(NOW()) AS ctime,TIME(NOW()) as ctimetmp,DATE(NOW()) as cdate";
			
					$rslt_time=$this->bm->dataSelectDb1($sql_time);
					$ctime=$rslt_time[0]['ctime'];						
					$cdate=$rslt_time[0]['cdate'];
					$ctimetmp=$rslt_time[0]['ctimetmp'];
					
					$lowerLimitTmp=$cdate.$ctimetmp;						
					$lowerLimit=$ctime;						
				
					$upperLimitTmp = strtotime($upperLimitTmp);
					$lowerLimitTmp = strtotime($lowerLimitTmp);
				//	$diff=round(abs($upperLimitTmp - $lowerLimitTmp) / 60,2);		
					$diff=round(($upperLimitTmp - $lowerLimitTmp) / 60,2);
					if($diff<0)
						$diff=null;
				}
				else
				{
				//	$sql_time="SELECT HOUR(NOW()) AS ctime,TIMEDIFF(CONCAT(DATE(NOW()),' 10:00:00'),NOW()) AS diff";
					$sql_time="SELECT HOUR(NOW()) AS ctime,TIMEDIFF(CONCAT(DATE(NOW()),' 08:00:00'),NOW()) AS diff";
			
					$rslt_time=$this->bm->dataSelectDb1($sql_time);
					
					$ctime=$rslt_time[0]['ctime'];
					$diff=$rslt_time[0]['diff'];
					
					$str=substr($diff,0,1); 
				
					if($str=="-")
						$diff=null;
				}
				
				$data['ctime']=$ctime;
				$data['diff']=$diff;
				
				$data['lowerLimit']=$lowerLimit;
				$data['upperLimit']=$upperLimit;		
				
				$this->load->view('cssAssetsList');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('stuffingContExcel',$data);
				$this->load->view('jsAssetsList');
				return;			
			}
			
			else
			{
				move_uploaded_file($_FILES["file"]["tmp_name"],$_SERVER['DOCUMENT_ROOT']."/resources/offdockexcel/".$_FILES["file"]["name"]);			
				rename($_SERVER['DOCUMENT_ROOT']."/resources/offdockexcel/".$_FILES["file"]["name"],$_SERVER['DOCUMENT_ROOT']."/resources/offdockexcel/".$filenm);
			}
			
			@require_once('excel_reader2.php');
			$mydata = new Spreadsheet_Excel_Reader($_SERVER['DOCUMENT_ROOT']."/resources/offdockexcel/".$filenm);
			
			$row=4;
		
			$totalrow=0;
			$excelrow=$mydata->rowcount(0);
			$i=4;
			
			while($i<=$excelrow)
			{
				if(trim($mydata->value($i,2))!="")
				$totalrow=$totalrow+1;
				$i=$i+1;
			}
		//	echo $totalrow;
			$row=4;  
			$i=0;
			$stat = 0;
			
			while($row<($totalrow+4))
			{
				$cont_no=$mydata->value($row,2);
				$cont_no = preg_replace('/[^A-Za-z0-9\. -]/', '', $cont_no);
				
				$seal_no=$mydata->value($row,3);
				$mlo=$mydata->value($row,4);
				$stfdate=$mydata->value($row,5);
				
				$newdate = str_replace('/', '-', $stfdate);
				$stfdate = date("Y-m-d", strtotime($newdate)); 
				/*
				$stfdate=$mydata->value($row,5);
				$newdate = str_replace('/', '-', $stfdate);
				if(preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-[0-9]{4}$/",$newdate))
				{
					$stfdate = date("Y-m-d", strtotime($newdate));
				}
				else
				{
					$msg="Stuffing date format is not in dd/mm/yyyy format for container ".$cont_no.". Please correct it.";
					$data['msg']=$msg;
					
					$this->load->view('header2');
					$this->load->view('stuffingContExcel',$data);
					$this->load->view('footer');
					return;
				}
				*/
				$destport=$mydata->value($row,6);
				$commodity=$mydata->value($row,7);
				$commodity=str_replace("'","\'",$commodity);
			
				// $sql_ISOSizeHeight="SELECT ref_equip_type.id,RIGHT(ref_equip_type.nominal_length,2) AS size,RIGHT(ref_equip_type.nominal_height,2) AS height,ref_equip_type.iso_group
				// FROM inv_unit
				// INNER JOIN inv_unit_equip ON inv_unit.gkey=inv_unit_equip.unit_gkey 
				// INNER JOIN ref_equipment ON inv_unit_equip.eq_gkey=ref_equipment.gkey
				// INNER JOIN ref_equip_type ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey
				// WHERE inv_unit.id='$cont_no' LIMIT 1";
			
				$sql_ISOSizeHeight="
				SELECT  ref_equip_type.id,SUBSTR(ref_equip_type.nominal_length, 4, LENGTH( ref_equip_type.nominal_length)) AS sizes,SUBSTR(ref_equip_type.nominal_height, 4, LENGTH( ref_equip_type.nominal_height)) AS hight,ref_equip_type.iso_group
				FROM inv_unit
						INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey
				INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
				WHERE inv_unit.id='$cont_no'  FETCH FIRST 1 ROWS ONLY;
				";
				
				$rslt_ISOSizeHeight=$this->bm->dataSelect($sql_ISOSizeHeight);
				
				$iso=$rslt_ISOSizeHeight[0]['ID'];
				$size=$rslt_ISOSizeHeight[0]['SIZES'];
				$height=$rslt_ISOSizeHeight[0]['HIGHT'];
				$isoGroup=$rslt_ISOSizeHeight[0]['ISO_GROUP'];
				
			//	$depo_code=$this->Offdock($login_id);
			
				if($org_Type_id==28)		//works when user is admin
				{
					$sql_code="select id as rtnValue from ctmsmis.offdoc where code='$offdock'";
					$depo_code=$this->bm->dataReturnDb2($sql_code);
				}
				else						//works when user is an offdock
					$depo_code=$this->Offdock($login_id);
				
				$stfdate_chk="select count(*) as rtnValue from ctmsmis.exp_stuffing_unit where stuffing_date='$stfdate' and cont_id='$cont_no'";
				
				$count_stfdate=$this->bm->dataReturnDb2($stfdate_chk);
				
				if($count_stfdate==1)
				{
					$sql_update="update ctmsmis.exp_stuffing_unit
					set cont_id='$cont_no',seal_no='$seal_no',iso_type='$iso',size='$size',height='$height',mlo_code='$mlo',stuffing_date='$stfdate',dest_port='$destport',comodity_code='$commodity',last_update=now(),uploaded_by='$login_id',user_ip='$ipaddr',depo_code='$depo_code',iso_group='$isoGroup'
					where stuffing_date='$stfdate' and cont_id='$cont_no'";
					
					$yes=$this->bm->dataUpdateDb2($sql_update);
				}
				else
				{
					$sql_insert="insert into ctmsmis.exp_stuffing_unit(cont_id,seal_no,iso_type,size,height,mlo_code,stuffing_date,dest_port,comodity_code,last_update,uploaded_by,user_ip,depo_code,iso_group)
					values('$cont_no','$seal_no','$iso','$size','$height','$mlo','$stfdate','$destport','$commodity',now(),'$login_id','$ipaddr','$depo_code','$isoGroup')";
				
					$yes=$this->bm->dataInsertDb2($sql_insert);
				}
			
				if($yes==1)
					$stat = $stat+1;					
				else
					$stat = $stat;							
				
				$row=$row+1; 
			}
			
			if($stat>0)
				$data['msg'] ="Data successfully uploaded.";
			else
				$data['msg'] ="Data not uploaded.";	
			
		//	$sql_time="SELECT HOUR(NOW()) AS ctime,TIMEDIFF(CONCAT(DATE(NOW()),' 10:00:00'),NOW()) AS diff";
			$sql_time="SELECT HOUR(NOW()) AS ctime,TIMEDIFF(CONCAT(DATE(NOW()),' 08:00:00'),NOW()) AS diff";
			
			$rslt_time=$this->bm->dataSelectDb1($sql_time);
				
			$data['ctime']=$rslt_time[0]['ctime'];
			$data['diff']=$rslt_time[0]['diff'];
				
			$data['title']="UPLOAD EXCEL FOR LAST 24 HOURS STUFFING CONTAINER...";			

			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('stuffingContExcel',$data);
			$this->load->view('jsAssetsList');			
		}
	}
	//STUFFING CONTAINER Excel end
	
	//-------------Export Excel Upload for admin start---------------
	
	function exportExcelUploadForAdmin()				
	{
		$data['title']="UPLOAD EXCEL FILE FROM ADMIN PANEL...";
		
		$this->load->view('cssAssetsList');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('myExportExcelUploadFormForAdmin',$data);
		$this->load->view('jsAssetsList');
	}
	
	function exportExcelUploadPerformForAdmin()			
	{
		$login_id = $this->session->userdata('login_id');
		$ipaddr = $_SERVER['REMOTE_ADDR'];
		$date = date('YmdHis');
		$dbDate = date('Y-m-d H:i:s');
		
		error_reporting(E_ALL ^ E_NOTICE);   
    
		$filenm=$login_id."_".$date.".xls";
		$filetype=$_POST["file"];
				
		if ($_FILES["file"]["error"] > 0)
		{
			$data['msg'] = "<b>Error: " . $_FILES["file"]["error"] . "<br />May be your file size exceeds 2MB.Please reduce file size and try again.<br/>To reduce file size-<br/>
			Step1:Save your Excel file into CSV(.csv) format.<br/>
			Step2:Now save your CSV file into Excel(.xls) format.<br/>
			Step3:Upload new Excel(.xls) file again.</b>";
			$data['title']="UPLOAD EXCEL FILE FROM ADMIN PANEL...";

			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('myExportExcelUploadFormForAdmin',$data);
			$this->load->view('jsAssetsList');
			return;			
		}
		else
		{
			move_uploaded_file($_FILES["file"]["tmp_name"],$_SERVER['DOCUMENT_ROOT']."/resources/export/uploadfile/".$_FILES["file"]["name"]);			
			rename($_SERVER['DOCUMENT_ROOT']."/resources/export/uploadfile/".$_FILES["file"]["name"],$_SERVER['DOCUMENT_ROOT']."/resources/export/uploadfile/".$filenm);
		}
		
				
		require_once('excel_reader2.php');
		$mydata = new Spreadsheet_Excel_Reader($_SERVER['DOCUMENT_ROOT']."/resources/export/uploadfile/".$filenm);
			
		$rotation=$mydata->value(1,6);
		
		
		
		//delete old data start
		$sql_delete_old_data="DELETE FROM ctmsmis.mis_exp_unit WHERE rotation='$rotation' AND snx_type='0'";
		
		$rslt_delete_old_data=$this->bm->dataDeleteDb2($sql_delete_old_data);
		//delete old data end
		
		$str_vvd_gkey="SELECT vvd_gkey AS rtnValue FROM vsl_vessel_visit_details WHERE ib_vyg='$rotation'";
			
		$vvd_gkey=$this->bm->dataReturn($str_vvd_gkey);   //dataReturn for DB2
		
		
		
		if($vvd_gkey==null)
		{
			$data['msg']="Rotation ".$rotation." is not valid. Please provide correct rotation.";
			$data['title']="UPLOAD EXCEL FILE FROM ADMIN PANEL...";

			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('myExportExcelUploadFormForAdmin',$data);
			$this->load->view('jsAssetsList');
			return;
		}
		
		
		
		$totalrow=0;
		$excelrow=$mydata->rowcount(0);
		$i=3;
			
		while($i<=$excelrow)   //row count
		{
			if(trim($mydata->value($i,2))!="")  //3
			$totalrow=$totalrow+1;
			$i=$i+1;
		}
		
		
		
		//Validation start
		$row=3;
		$stat=0;
		$count_stowage=0;
	
		$table = "<table border='1'><tr><th>Field</th><th>Description</th></tr>";
		while($row<=($totalrow+2))  
		{
			$container=trim($mydata->value($row,2));
			$container = preg_replace('/[^A-Za-z0-9\. -]/', '', $container);
	
			$pod=$mydata->value($row,9);
			$stowage=$mydata->value($row,10);
			
			if($stowage!=null)
			{
				$stowage = preg_replace("/[^0-9,.]/", "", $stowage);
				
				if(strlen($stowage)<5 or strlen($stowage)>6)
				{
					$stat=$stat+1;
					$table .= "<tr><td>".$container."</td><td>Stowage position should have 6 digits</td></tr>";
				}
				else
				{
					if(strlen($stowage)==5)
						$stowage = "0".$stowage;
					else
						$stowage=$stowage;
					
					$sql_stow_chk="SELECT COUNT(*) AS rtnValue
					FROM ctmsmis.mis_exp_unit
					WHERE ctmsmis.mis_exp_unit.vvd_gkey='$vvd_gkey' AND ctmsmis.mis_exp_unit.stowage_pos='$stowage'";
				
					$count_stowage=$this->bm->dataReturnDb2($sql_stow_chk);  //dataReturn for DB2
				}
				
				// if(strlen($stowage)==5)
					// $stowage = "0".$stowage;
				// else
					// $stowage=$stowage;
				
				// $sql_stow_chk="SELECT COUNT(*) AS rtnValue
				// FROM ctmsmis.mis_exp_unit
				// WHERE ctmsmis.mis_exp_unit.vvd_gkey='$vvd_gkey' AND ctmsmis.mis_exp_unit.stowage_pos='$stowage'";
			
				// $count_stowage=$this->bm->dataReturn($sql_stow_chk);  //dataReturn for DB2
			}
			
			$strcontchk="SELECT count(id) AS rtnValue FROM inv_unit WHERE id='$container'";
			
			$count_container=$this->bm->dataReturn($strcontchk);  //dataReturn for DB2
			


			// $sql_pod_check="SELECT count(ref_routing_point.id) as rtnValue FROM vsl_vessel_visit_details
			// INNER JOIN argo_visit_details ON argo_visit_details.gkey=vsl_vessel_visit_details.vvd_gkey
			// INNER JOIN ref_point_calls ON ref_point_calls.itin_gkey=argo_visit_details.itinereray
			// INNER JOIN ref_routing_point ON ref_point_calls.point_gkey=ref_routing_point.gkey 
			// WHERE vsl_vessel_visit_details.ib_vyg='$rotation' AND id='$pod'";
			


			$sql_pod_check="SELECT count(ref_routing_point.id) as rtnValue FROM vsl_vessel_visit_details
			INNER JOIN argo_visit_details ON argo_visit_details.gkey=vsl_vessel_visit_details.vvd_gkey
			INNER JOIN ref_point_calls ON ref_point_calls.itin_gkey=argo_visit_details.itinereray
			INNER JOIN ref_routing_point ON ref_point_calls.point_gkey=ref_routing_point.gkey 
			WHERE vsl_vessel_visit_details.ib_vyg='$rotation' AND id='$pod'";


			
			$count_pod=$this->bm->dataReturn($sql_pod_check);  //dataReturn for DB2
				
			if($count_container==0)			//check in n4
			{
				$stat=$stat+1;
				$table .= "<tr><td>".$container."</td><td>Container is not available or Wrong Container No.</td></tr>";
			}
			else if($stowage==null)			//check blank stowage
			{
				$stat=$stat+1;
				$table .= "<tr><td>".$container."</td><td>Stowage position of container is blank</td></tr>";
			}
			else if($count_stowage>0)		//check duplicate stowage
			{
				$strStowCont="SELECT cont_id AS rtnValue
				FROM ctmsmis.mis_exp_unit
				WHERE ctmsmis.mis_exp_unit.vvd_gkey='$vvd_gkey' AND ctmsmis.mis_exp_unit.stowage_pos='$stowage'";
			
				$StowCont=$this->bm->dataReturnDb2($strStowCont);
				
				if($StowCont!=$container)
				{
					$stat=$stat+1;
					$table .= "<tr><td>".$container."</td><td>Stowage Position of container is duplicate</td></tr>";
				}					
			}
			else if($count_pod==0)			//check valid port of destination
			{
				$stat=$stat+1;
				$table .= "<tr><td>".$pod."</td><td>Port of destination is not valid</td></tr>";
			}
				
			$row++;
		}
		
		
		
		$row;
		$table=$table."</table>";
		
		
		
		if($stat>0)
		{
			$data['msg']="Uploaded excel file has following errors<br>".$table;
			$data['title']="UPLOAD EXCEL FILE FROM ADMIN PANEL...";

			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('myExportExcelUploadFormForAdmin',$data);
			$this->load->view('jsAssetsList');

			return;
		}
		
		//Validation end
			
		$row=3;  
		$i=0;
		$stat = 0;
		
		$chkShipmentTypeNotGivenCont="";
		$chkShipmentTypeStat="";
		$updateby=$this->session->userdata('login_id');
		
		
		while($row <= ($totalrow+2))  //data insert
		{
			$container=trim($mydata->value($row,2));
			
			$str_gkey="SELECT gkey as rtnValue FROM inv_unit WHERE id='$container' ORDER BY gkey DESC FETCH FIRST 1 ROWS ONLY";
			$gkey=$this->bm->dataReturn($str_gkey);    //$gkey of inv_unit for particular container
			
			$iso=trim($mydata->value($row,3));
			$mlo=$mydata->value($row,6);
			
			if($mlo==null)
			{				
				$sql_mlo="SELECT ref_bizunit_scoped.id AS rtnValue
							   FROM inv_unit
							   INNER JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=inv_unit.line_op
							   WHERE inv_unit.id='$container' AND inv_unit.category='IMPRT' 
							   ORDER BY inv_unit.gkey DESC FETCH FIRST 1 ROWS ONLY ";
				
				$mlo=$this->bm->dataReturn($sql_mlo);
			}
			
			$cont_status=$mydata->value($row,7);
			
			if($cont_status!="MTY" or $cont_status!="FCL")
				$cont_status="MTY";			
			
			$weight=$mydata->value($row,8);
			$pod=$mydata->value($row,9);
			$stowage=$mydata->value($row,10);
			$stowage = preg_replace("/[^0-9,.]/", "", $stowage);
			if(strlen($stowage)==5)
				$stowage = "0".$stowage;
			else
				$stowage=$stowage;
			$loaded_time=$mydata->value($row,11);
			$seal_no=$mydata->value($row,12);
			$coming_from=$mydata->value($row,13);
			$truck_no=$mydata->value($row,14);
			$craine_id=$mydata->value($row,15);
			$commodity=$mydata->value($row,16);
			$shift=$mydata->value($row,17);
			$date=$mydata->value($row,18);
			
			if($iso=="")
			{
			
				$getIsoTypeQry="select ctmsmis.mis_exp_unit.gkey 
				from ctmsmis.mis_exp_unit
			
				where mis_exp_unit.vvd_gkey='$vvd_gkey' and mis_exp_unit.cont_id='$container'
				AND mis_exp_unit.preAddStat='0' and snx_type=0 and mis_exp_unit.delete_flag ='0'";
				
				$strResult = $this->bm->dataSelectDb2($getIsoTypeQry);
				
				$j=0;
				$result;
				for($i=0;$i<count($strResult);$i++){
					$gkey="";
					$gkey=$strResult[$i]['gkey'];

					$strQuery2="
					SELECT  ref_equip_type.ID as iso
							FROM inv_unit
							  INNER JOIN ref_equipment ON ref_equipment.gkey=inv_unit.eq_gkey
					INNER JOIN ref_equip_type ON ref_equip_type.gkey=ref_equipment.eqtyp_gkey
					WHERE inv_unit.gkey='$gkey' ";
					$rslt_IsoType=$this->bm->dataSelect($getIsoTypeQry);
					if(count($rslt_IsoType )>0){
						$iso = $rslt_IsoType[0]['ISO'];
					}

				}
		
				

				// $rslt_IsoType=$this->bm->dataSelect($getIsoTypeQry);
				// $iso = $rslt_IsoType[0]['iso'];
			}
			
			$str_count="SELECT COUNT(gkey) AS rtnValue FROM ctmsmis.mis_exp_unit WHERE vvd_gkey='$vvd_gkey' AND cont_id='$container' and snx_type=0";
					
			$count=$this->bm->dataReturnDb2($str_count);  //dataReturn for DB2
			
			$str_sizeheightgroup="SELECT SUBSTR(nominal_length, 4, LENGTH( nominal_length)) e_size, 
			SUBSTR(nominal_height, 4, LENGTH( nominal_height)) height, 
			iso_group isogroup 
			FROM ref_equip_type WHERE id='$iso'";
			
			$rslt_sizeheightgroup=$this->bm->dataSelect($str_sizeheightgroup);
				
			$size=$rslt_sizeheightgroup[0]['E_SIZE'];
			$height=$rslt_sizeheightgroup[0]['HEIGHT'];
			$isoGroup=$rslt_sizeheightgroup[0]['ISOGROUP'];
			
			$str_insert = "";
			$str_update = "";
			
			echo $count."<br>";
			
			if($count>0)
			{
				//last update not in update
				$str_pgkey="SELECT gkey AS rtnValue FROM ctmsmis.mis_exp_unit WHERE vvd_gkey='$vvd_gkey' AND cont_id='$container' and snx_type=0";
					
				$presentGky=$this->bm->dataReturn($str_pgkey);  //$presentGky from excel file
				$str_update = "";
				if($presentGky!=$gkey)
				{
					$str_update="UPDATE ctmsmis.mis_exp_unit SET gkey='$gkey',cont_id='$container',isoType='$iso',cont_size='$size',cont_height='$height',isoGroup='$isoGroup',cont_status='$cont_status',cont_mlo='$mlo',vvd_gkey='$vvd_gkey',rotation='$rotation',stowage_pos='$stowage',user_id='$updateby',seal_no='$seal_no',goods_and_ctr_wt_kg='$weight',pod='$pod',truck_no='$truck_no',re_status=1,craine_id='$craine_id',last_update=NOW(),updated_in_n4=1,coming_from='$coming_from',shift='$shift',date='$date' WHERE cont_id='$container' AND vvd_gkey='$vvd_gkey' and snx_type=0";
				}
				else
				{
					$str_update="UPDATE ctmsmis.mis_exp_unit SET gkey='$gkey',cont_id='$container',isoType='$iso',cont_size='$size',cont_height='$height',isoGroup='$isoGroup',cont_status='$cont_status',cont_mlo='$mlo',vvd_gkey='$vvd_gkey',rotation='$rotation',stowage_pos='$stowage',user_id='$updateby',seal_no='$seal_no',goods_and_ctr_wt_kg='$weight',pod='$pod',truck_no='$truck_no',re_status=1,craine_id='$craine_id',updated_in_n4=1,coming_from='$coming_from',shift='$shift',date='$date' WHERE cont_id='$container' AND vvd_gkey='$vvd_gkey' and snx_type=0";
				}
					
				$yes=$this->bm->dataUpdatedb2($str_update); //dataUpdatedb2 for DB2
			}
			else
			{
				//echo "Insert"."<br>";
				echo $str_insert="INSERT INTO ctmsmis.mis_exp_unit(gkey,cont_id,cont_status,cont_mlo,isoType,cont_size,cont_height,isoGroup,vvd_gkey,rotation,stowage_pos,last_update,updated_in_n4,user_id,seal_no,goods_and_ctr_wt_kg,pod,truck_no,re_status,craine_id,coming_from,shift,date) 
				VALUES ('$gkey','$container','$cont_status','$mlo','$iso','$size','$height','$isoGroup','$vvd_gkey','$rotation','$stowage',now(),1,'$updateby','$seal_no','$weight','$pod','$truck_no',1,'$craine_id','$coming_from','$shift','$date')";
				
				echo "<br>";
					
				$yes=$this->bm->dataInsertDb2($str_insert); //dataInsert for DB2
			}

			// if($yes==1)
				// $stat = $stat+1;					
			// else
				// $stat = $stat;

			// echo $str_insert."______".$str_update."<br>";
			// echo $stat."<br>";
			
			$row=$row+1;		
		}	

		die();
			//echo "row : ".$row;
		if($stat>0)
			$data['msg'] ="Successful";
		else
			$data['msg'] ="Failed";	
		
		$data['title']="UPLOAD EXCEL FILE FROM ADMIN PANEL...";

		$this->load->view('cssAssetsList');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('myExportExcelUploadFormForAdmin',$data);
		$this->load->view('jsAssetsList');
	}
	
	//-------------Export Excel Upload for admin end---------------
	
	
	
	function equipmentHandlingDemandForm()
	{	
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
	
		if($LoginStat!="yes")
		{
			$this->logout();
			
		}
		else
		{
			if($this->input->post('delete'))
				{
					$eid=$this->input->post('eid');
					$deleteSql="DELETE FROM ctmsmis.mis_equip_demand WHERE ctmsmis.mis_equip_demand.id='$eid'";
					$deleteStat=$this->bm->dataDeleteDb2($deleteSql);
				}
			$rslt_sql="SELECT id, yard, equip_type, equip_demand, demand_by FROM ctmsmis.mis_equip_demand";
			$result=$this->bm->dataSelectDb2($rslt_sql);			
			$data['result']=$result;
			$data['editFlag']=0;
			$msg="";
			$data['title']="Equipment Demand Form...";
			$data['msg']=$msg;
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('equipmentHandlingDemandForm',$data);
			$this->load->view('jsAssetsList');
		}
	}
		
	function equipmentHandlingDemandFormPerform()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
	
		if($LoginStat!="yes")
		{
			$this->logout();
			
		}
		else
		{
			$login_id = $this->session->userdata('login_id');
			$yard=$this->input->post('yard');
			$equipment=$this->input->post('equipment');
			$demand=$this->input->post('demand');
			$ipaddr = $_SERVER['REMOTE_ADDR'];
			if($this->input->post('update'))
				{
				$equipID=$this->input->post('eqiID');
//                            echo $equipID;
//                            return;
				$updateSql="UPDATE ctmsmis.mis_equip_demand set yard ='$yard', equip_type='$equipment', equip_demand='$demand',
							demand_by='$login_id',  demand_date=now(), ip_address='$ipaddr' where ctmsmis.mis_equip_demand.id='$equipID'";
				$updateStat=$this->bm->dataUpdatedb2($updateSql);
				if($updateStat==1)
					$msg="<font color='green'><b>Succesfully Updated</b></font>";
				else
					$msg="<font color='red'><b>Updation failed. $equipment demand in $yard yard already assigned. Please Edit!</b></font>";

				}
				else{

				$insert_sql="INSERT INTO ctmsmis.mis_equip_demand(yard, equip_type, equip_demand, demand_by, demand_date, ip_address)
					VALUES('$yard','$equipment','$demand', '$login_id', NOW(), '$ipaddr')";


				$insert_stat=$this->bm->dataInsertDb2($insert_sql);

				if($insert_stat==1)
					$msg="<font color='green'><b>Succesfully inserted</b></font>";
				else
					$msg="<font color='red'><b>$equipment demand in $yard yard already assigned. Please Edit! </b></font>";
					}   
					//$msg="";
				$rslt_sql="SELECT id, yard, equip_type, equip_demand, demand_by FROM ctmsmis.mis_equip_demand";
				$result=$this->bm->dataSelectDb2($rslt_sql);			
				$data['result']=$result;

				$data['msg']=$msg;
				$data['editFlag']=0;
				$data['title']="Equipment Demand Form...";
				$this->load->view('cssAssetsList');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('equipmentHandlingDemandForm',$data);
				$this->load->view('jsAssetsList');
		}

	}
                
    function equipmentHandlingDemandFormEdit()
	{
        $session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
               
               $eqiID= $this->input->post('eqiID');
                
                $select_sql="SELECT id, yard, equip_type, equip_demand, demand_by FROM ctmsmis.mis_equip_demand
                        where mis_equip_demand.id='$eqiID'";
                
           //    echo $select_sql;
//                return;
                $select_result=$this->bm->dataSelectDb2($select_sql);			
                $data['select_result']=$select_result;
                
                $rslt_sql="SELECT id, yard, equip_type, equip_demand, demand_by FROM ctmsmis.mis_equip_demand";
                $result=$this->bm->dataSelectDb2($rslt_sql);			
                $data['result']=$result;

                $data['editFlag']=1;
                $msg="";
                $data['title']="Equipment Assign Entry Form";
                $data['msg']=$msg;
                $this->load->view('cssAssetsList');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
                $this->load->view('equipmentHandlingDemandForm',$data);
                $this->load->view('jsAssetsList');
            }
	}
	
	//TODAY'S EDI DECLARATION - start
	function todays_edi_declaration()
	{
		$session_id = $this->session->userdata('value');
		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();	
		}
		else
		{
			$sql_edi_list="SELECT id,file_name_edi,file_name_stow
						FROM edi_stow_info
						WHERE file_status='0'";
						
			$rslt_edi_list=$this->bm->dataSelectDb1($sql_edi_list);
			
			$data['rslt_edi_list']=$rslt_edi_list;
			$data['title']="TODAY'S EDI DECLARATION...";
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('todays_edi_declaration',$data);
			$this->load->view('jsAssetsList');
		}
	}
	function edi_declaration()
	{		
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$rot_no = $this->input->post('rot_no');
		$rot=str_replace("/","_",$rot_no);
		
		//echo($rot);
		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();	
		}
		else
		{
			$sql_edi_list="SELECT id,file_name_edi,file_name_stow
			FROM edi_stow_info
			WHERE file_status='0' AND file_name_edi LIKE '%$rot%'";
				//echo($sql_edi_list);		
			$rslt_edi_list=$this->bm->dataSelectDb1($sql_edi_list);
			//print_r($rslt_edi_list);
			//return;
			$data['rslt_edi_list']=$rslt_edi_list;
			$data['title']="TODAY'S EDI DECLARATION...";
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('todays_edi_declaration',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	
	function update_edi_status()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();	
		}
		else
		{
			$login_id = $this->session->userdata('login_id');
			$edi_id = $this->uri->segment(3);
			
			$strUpdate = "UPDATE edi_stow_info
			SET file_status='1',file_download_by='$login_id',file_download_date=NOW()
			WHERE id='$edi_id'";
			
			$this->bm->dataUpdateDB1($strUpdate);
			
			$sql_edi_list="SELECT id,file_name_edi,file_name_stow
						FROM edi_stow_info
						WHERE file_status='0'";
						
			$rslt_edi_list=$this->bm->dataSelectDb1($sql_edi_list);
			
			$data['rslt_edi_list']=$rslt_edi_list;
			
			$data['title']="TODAY'S EDI DECLARATION...";
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('todays_edi_declaration',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function show_edi_declaration()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();	
		}
		else
		{			
			$edi_id = $this->uri->segment(3);
			$sql_edi_list="SELECT Import_Rotation_No,Vessel_Name,Name_of_Master,Voy_No,VoyNoExp,grt,nrt,imo,loa_cm,flag,radio_call_sign,beam_cm,
			Organization_Name AS agent_name			
			FROM igm_masters 
			LEFT JOIN organization_profiles ON  organization_profiles.id = igm_masters.Submitee_Org_Id
			WHERE igm_masters.id = (SELECT igm_masters_id FROM edi_stow_info WHERE id='$edi_id')";
						
			$rslt_edi_list=$this->bm->dataSelectDb1($sql_edi_list);
			
			$data['rslt_edi_list']=$rslt_edi_list;
			
			$data['title']="TODAY'S EDI DECLARATION...";
			
			//$this->load->view('header2');
			$this->load->view('todays_edi_declaration_list',$data);
			//$this->load->view('footer');
		}
	}
	//TODAY'S EDI DECLARATION - end
	
	
	function last24hrPerformancePdfForm()			
	{
		$login_id = $this->session->userdata('login_id');
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else 
		{
			$data['title']="LAST 24 HR PERFORMANCE PDF FILE UPLOAD FORM ";
			$data['msg']="";
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('last24hrPerformancePdfForm',$data);
			$this->load->view('jsAssets');
		}
	}
        
    function last24hrPerformancePdfUpload()		// 2020-06-07
	{
		$login_id = $this->session->userdata('login_id');
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}		
		else 
		{
			$perform_date = $this->input->post('perform_date');
			$ipaddr = $_SERVER['REMOTE_ADDR'];
			error_reporting(E_ALL ^ E_NOTICE);   

			$pDate=str_replace('-','',$perform_date);
			$m_file=$pDate."_manual.pdf";
			$c_file=$pDate."_ctms.pdf";
			
			$manual_file=$_POST["manual_file"];
			$ctms_file=$_POST["ctms_file"];			
			
			if ($_FILES["manual_file"]["error"] > 0 and $_FILES["ctms_file"]["error"] > 0)
			{
				$data['msg']="<font color='red' size=4>Here is Problem!! To upload this file. Please! Check Again, That file is not Corrupted and ensure correct format</font>";
			}
			else
			{
				move_uploaded_file($_FILES["manual_file"]["tmp_name"],$_SERVER['DOCUMENT_ROOT']."/resources/manual_files/".$_FILES["manual_file"]["name"]);			
				rename($_SERVER['DOCUMENT_ROOT']."/resources/manual_files/".$_FILES["manual_file"]["name"],$_SERVER['DOCUMENT_ROOT']."/resources/manual_files/".$m_file);
			
				move_uploaded_file($_FILES["ctms_file"]["tmp_name"],$_SERVER['DOCUMENT_ROOT']."/resources/ctms_files/".$_FILES["ctms_file"]["name"]);			
				rename($_SERVER['DOCUMENT_ROOT']."/resources/ctms_files/".$_FILES["ctms_file"]["name"],$_SERVER['DOCUMENT_ROOT']."/resources/ctms_files/".$c_file);

				$insertStr="INSERT INTO ctmsmis.performance_file_upload (date, manual_file_path, ctms_file_path, ip_adddress, login_id, upload_time) VALUES
						 ('$perform_date','$m_file','$c_file','$ipaddr','$login_id',now())";
						 
				$stat =  $this->bm->dataInsertDb2($insertStr);
			
				if($stat==1)
				{
					$data['msg']="<font color='green' size='3'>PDF file uploaded Successfully for <b>".$perform_date."</b></font>";
				}
				else
				{
					$data['msg']="<font color='red' size='3'>Not Uploaded</font>";
				}			
			}

			$data['title']="LAST 24 HR PERFORMANCE PDF FILE UPLOAD FORM ";
		
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('last24hrPerformancePdfForm',$data);
			$this->load->view('jsAssets');			
		}
	}
	
	
	
	function Offdock($login_id)
	{
		$offdoc ="";
		if($login_id=='gclt')
		{
			$offdoc = "3328";
		}
		elseif($login_id=='saple')
		{
			$offdoc = "3450";
		}
		elseif($login_id=='saplw')
		{
			$offdoc = "2603";
		}
		elseif($login_id=='ebil')
		{
			$offdoc = "2594";
		}
		elseif($login_id=='cctcl')
		{
			$offdoc = "2595";
		}
		elseif($login_id=='ktlt')
		{
			$offdoc = "2596";
		}
		elseif($login_id=='qnsc')
		{
			$offdoc = "2597";
		}
		elseif($login_id=='ocl')
		{
			$offdoc = "2598";
		}
		elseif($login_id=='vlsl')
		{
			$offdoc = "2599";
		}
		elseif($login_id=='shml')
		{
			$offdoc = "2600";
		}
		elseif($login_id=='iqen')
		{
			$offdoc = "2601";
		}
		elseif($login_id=='iltd')
		{
			$offdoc = "2620";
		}
		elseif($login_id=='plcl')
		{
			$offdoc = "2643";
		}
		elseif($login_id=='shpm')
		{
			$offdoc = "2646";
		}
		elseif($login_id=='hsat')
		{
			$offdoc = "3697";
		}
		elseif($login_id=='ellt')
		{
			$offdoc = "3709";
		}
		elseif($login_id=='bmcd')
		{
			$offdoc = "3725";
		}
		elseif($login_id=='nclt')
		{
			$offdoc = "4013";
		}
		elseif($login_id=='kdsl')
		{
			$offdoc = "2624";
		}	
		elseif($login_id=='blcl')
		{
			$offdoc = "5885";
		}
		else
		{
			$offdoc = "";
		}
		return $offdoc;
	}
	
	function readObpcForm()
	{
		$data['title']="Upload Excel for OBPC & RL";
		$data['msg'] = "";
		
		$this->load->view('cssAssets');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('readObpcForm',$data);
		$this->load->view('jsAssets');
	}

	function readObpc()
	{
		$login_id = $this->session->userdata('login_id');
		$date = date('YmdHis');
		$dbDate = date('Y-m-d H:i:s');
		//echo $dbDate."<br>";
		error_reporting(E_ALL ^ E_NOTICE);   

		$filenm="obpc_rl_".$login_id."_".$date.".xls";
		$filetype=$_POST["obpc"];
		$msg = "";
		
		if ($_FILES["obpc"]["error"] > 0)
		{
			$msg = "<font color='red'>File Error.</font>";
			$data['msg'] = "<b>Error: " . $_FILES["file"]["error"] . "<br />May be your file size exceeds 2MB.Please reduce file size and try again.<br/>To reduce file size-<br/>
			Step1:Save your Excel file into CSV(.csv) format.<br/>
			Step2:Now save your CSV file into Excel(.xls) format.<br/>
			Step3:Upload new Excel(.xls) file again.</b>";
			$data['title']="UPLOAD EXCEL FILE FOR COPINO...";
			$data['msg']=$msg;
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('readObpcForm',$data);
			$this->load->view('jsAssets');
			return;
		}
		else
		{
			move_uploaded_file($_FILES["obpc"]["tmp_name"],$_SERVER['DOCUMENT_ROOT']."/assets/uploadfile/".$_FILES["obpc"]["name"]);
			
			rename($_SERVER['DOCUMENT_ROOT']."/assets/uploadfile/".$_FILES["obpc"]["name"],$_SERVER['DOCUMENT_ROOT']."/assets/uploadfile/".$filenm);
		}

		require_once('excel_reader2.php');
		$mydata = new Spreadsheet_Excel_Reader($_SERVER['DOCUMENT_ROOT']."/assets/uploadfile/".$filenm);
		$excelrow=$mydata->rowcount(0);
		$excelcol=$mydata->colcount(0);
		$update = 0;
		
		
		for($i=2;$i<=$excelrow;$i++){
			// for($i=2;$i<=2;$i++){
			// for($j=1;$j<$excelcol;$j++){
			// echo $mydata->value($i,$j)." ";
			$cont = $mydata->value($i,2);
			$reg = $mydata->value($i,4);
			$rlNo = $mydata->value($i,9);
			$rlDate = $mydata->value($i,10);
			$obpcNo = $mydata->value($i,11);
			$obpcDate = $mydata->value($i,12);

			// $query = "SELECT inv_unit_fcy_visit.gkey as rtnValue
			// FROM inv_unit 
			// INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			// INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv
			// INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
			// WHERE vsl_vessel_visit_details.ib_vyg='$reg' AND inv_unit.id='$cont'";
			
			
			$query = "SELECT inv_unit_fcy_visit.gkey as rtnValue
			FROM inv_unit 
			INNER JOIN inv_unit_fcy_visit ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			INNER JOIN argo_carrier_visit ON argo_carrier_visit.gkey=inv_unit_fcy_visit.actual_ib_cv
			INNER JOIN vsl_vessel_visit_details ON vsl_vessel_visit_details.vvd_gkey=argo_carrier_visit.cvcvd_gkey
			WHERE vsl_vessel_visit_details.ib_vyg='$reg' AND inv_unit.id='$cont'";




			
			$gkey = $this->bm->dataReturn($query);
			
			// echo $gkey;

			// $updtquery = "UPDATE inv_unit_fcy_visit
			// 		SET inv_unit_fcy_visit.flex_string04 = '$rlNo',inv_unit_fcy_visit.flex_string05 = '$rlDate',
			// 		inv_unit_fcy_visit.flex_string07 = '$obpcNo',inv_unit_fcy_visit.flex_string08 = '$obpcDate'
			// 		WHERE inv_unit_fcy_visit.gkey = '$gkey'";
			
			$updtquery = "UPDATE inv_unit_fcy_visit
			SET inv_unit_fcy_visit.flex_string04 = '$rlNo',inv_unit_fcy_visit.flex_string05 = '$rlDate',
			inv_unit_fcy_visit.flex_string07 = '$obpcNo',inv_unit_fcy_visit.flex_string08 = '$obpcDate'
			WHERE inv_unit_fcy_visit.gkey = '$gkey'";

			//$sts = $this->bm->dataUpdate($updtquery);
			
			if($sts == 1){
				$update++;
			}
				

			// }
		}


		$data['title']="Upload Excel for OBPC & RL";
		$data['flag'] = 1;
		$data['msg'] = "<font color='green'>".$update." Rows Updated.</font>";
		$this->load->view('header2');
		$this->load->view('readObpcForm',$data);
		$this->load->view('footer');
	}
	
	

	
}
	
?>





























