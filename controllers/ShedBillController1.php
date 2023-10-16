<?php

class ShedBillController extends CI_Controller {
	function __construct()
	{
	    parent::__construct();	
            $this->load->library(array('session', 'form_validation'));
            $this->load->model(array('CI_auth', 'CI_menu'));
            $this->load->helper(array('html','form', 'url'));
			//$this->load->driver('cache');
			$this->load->model('ci_auth', 'bm', TRUE);
			
			header("cache-Control: no-store, no-cache, must-revalidate");
			header("cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");
			header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
			
	}
	function index(){
		 $this->shedBillView();
	}

	function shedBillView()
	{
		$session_id = $this->session->userdata('value');
		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();
		}
		else
		{
			$data['title']="Shed Bill FORM...";
			$this->load->view('header5');
			$this->load->view('shedBillForm',$data);
			$this->load->view('footer_1');
		}	
    }
	
	function shedBillGenerate()
	{
		$session_id = $this->session->userdata('value');
		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();
		}
		else
		{
			if($this->input->post('verifyNo')=="")
			{
				if($this->uri->segment(3)=="")
				{
					$data['msg']="<font color='red'><b>Please input verify No.</b></font>";
				}
				else{
					$billVerify=$this->uri->segment(3);
					$data['msg']="Generate Bill For The Verification Number ".$billVerify;
				}
				
			}
			else
			{
				$billVerify=$this->input->post('verifyNo');
				$data['msg']="Generate Bill For The Verification Number ".$billVerify;
			}
			//$billVerify=$this->input->post('verifyNo');
			
			$data['title']="Shed Bill FORM...";
			
			
			//$tariffData=$this->tariffGenerate($billVerify);
			$this->tariffGenerate($billVerify);
			
			$contQuery="select cont_number from shed_tally_info where verify_number='$billVerify'";
			$contNum = $this->bm->dataSelectDb1($contQuery);
			$container = $contNum[0]['cont_number'];	
			
			$getDateDiffQuery= "select IFNULL(DATEDIFF(sparcsn4.inv_unit_fcy_visit.time_out,DATE_ADD(sparcsn4.inv_unit_fcy_visit.time_in,INTERVAL 4 day)),0) as dif
									from sparcsn4.inv_unit
									inner join sparcsn4.inv_unit_fcy_visit on sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
									where sparcsn4.inv_unit.id='$container' and sparcsn4.inv_unit.category='STRGE'";
			$getDateDiff = $this->bm->dataSelect($getDateDiffQuery);
			$dateDiffValue=$getDateDiff[0]['dif'];
			//$dateDiffValue=15;
			
						$qry= "select verify_no,tarrif_id,bil_tariffs.description,bil_tariffs.gl_code,IFNULL(bil_tariff_rates.amount,0) as tarrif_rate,
							ifnull(verify_other_data.update_ton,CEIL(igm_sup_detail_container.Cont_gross_weight/1000)) as Qty,
							igm_sup_detail_container.Cont_gross_weight,
					(case 
						when 
							tarrif_id like '%1ST%'
						then 
							 if($dateDiffValue<7,$dateDiffValue,7)
						else 
							case 
								when 
									tarrif_id like '%2ND%'
								then 
									if($dateDiffValue<14,$dateDiffValue-7,7)
								else  
									if(tarrif_id like '%3RD%',$dateDiffValue-14,1)
							end
					end) as qday,
										(select tarrif_rate*Qty*qday) as amt,
										(select amt*15/100) as vatTK
										from shed_bill_tarrif
										inner join bil_tariffs on 
										shed_bill_tarrif.tarrif_id= bil_tariffs.id
										inner join bil_tariff_rates on
										bil_tariffs.gkey=bil_tariff_rates.tariff_gkey
										inner join shed_tally_info on
										shed_tally_info.verify_number=shed_bill_tarrif.verify_no
										inner join igm_sup_detail_container on igm_sup_detail_container.igm_sup_detail_id = shed_tally_info.igm_sup_detail_id
										inner join verify_other_data on verify_other_data.shed_tally_id=shed_tally_info.id
										where verify_no='$billVerify'";
				
			$chargeList = $this->bm->dataSelectDb1($qry); 
			//$data['tariffData']=$tariffData;
			$data['chargeList']= $chargeList;
			
			$contQuery="select COUNT(verify_no) as verify_no from shed_bill_master where verify_no='$billVerify'";
			$contNum = $this->bm->dataSelectDb1($contQuery);
			$verify_no = $contNum[0]['verify_no'];
			if($verify_no>0)
			{
				$data['stat']="<font color='red'><b>Bill Already Generated.</b></font>";
				$data['chkGenerate']=1;
			}
			//"Status : ".$tariffData[1]['contStat'];

			$this->load->view('header5');
			$this->load->view('shedBillForm',$data);
			$this->load->view('footer_1');
		}	
    }
	function tariffGenerate($billVerify)
	{
		
		$qry="select igm_sup_detail_container.cont_status,loc_first,shed_tally_info.cont_number	 
				from  igm_supplimentary_detail
				inner join igm_sup_detail_container on igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
				left join  shed_tally_info on shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
				where shed_tally_info.verify_number='$billVerify'
				group by igm_sup_detail_container.id";
				
		$conStatus = $this->bm->dataSelectDb1($qry); 
		$cont_status = $conStatus[0]['cont_status'];
		$loc_first = $conStatus[0]['loc_first'];
		$cont_number = $conStatus[0]['cont_number'];
		//echo "Starus==".$loc_first;
		if($cont_status='LCL')
		{
			$strRiverDues="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
				values('$billVerify',(select get_shed_bill_tarrif('$billVerify',1)),1,1)";
			$statRiverDues=$this->bm->dataInsertDB1($strRiverDues);
				
			$strStuffUnStuff="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
				values('$billVerify',(select get_shed_bill_tarrif('$billVerify',2)),1,2)";
			$statStuffUnStuff=$this->bm->dataInsertDB1($strStuffUnStuff);
			
			$strHostingCharge="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
				values('$billVerify',(select get_shed_bill_tarrif('$billVerify',3)),1,3)";
			$statHostingCharge=$this->bm->dataInsertDB1($strHostingCharge);
				
			if($loc_first==1)
			{
				/********************Add 4 Days*************************/
				$getDateDiffQuery= "select IFNULL(DATEDIFF(sparcsn4.inv_unit_fcy_visit.time_out,DATE_ADD(sparcsn4.inv_unit_fcy_visit.time_in,INTERVAL 4 day)),0) as dif
									from sparcsn4.inv_unit
									inner join sparcsn4.inv_unit_fcy_visit on sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
									where sparcsn4.inv_unit.id='$cont_number' and sparcsn4.inv_unit.category='STRGE'";
				$getDateDiff = $this->bm->dataSelect($getDateDiffQuery);
				$dateDiffValue=$getDateDiff[0]['dif'];
				if($dateDiffValue>14)
				{
					//9
					$strStuffUnStuff="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						values('$billVerify',(select get_shed_bill_tarrif('$billVerify',9)),1,9)";
					$statStuffUnStuff=$this->bm->dataInsertDB1($strStuffUnStuff);
				}
				else if($dateDiffValue>7 and $dateDiffValue<=14)
				{
					//8
					$strStuffUnStuff="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						values('$billVerify',(select get_shed_bill_tarrif('$billVerify',8)),1,8)";
					$statStuffUnStuff=$this->bm->dataInsertDB1($strStuffUnStuff);
				}
				else if($dateDiffValue>0 and $dateDiffValue<=7)
				{
					//7
					$strStuffUnStuff="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						values('$billVerify',(select get_shed_bill_tarrif('$billVerify',7)),1,7)";
					$statStuffUnStuff=$this->bm->dataInsertDB1($strStuffUnStuff);
				}
			}
			else if($loc_first==0)
			{
				/********************Add 4 Days*************************/
				$getDateDiffQuery= "select IFNULL(DATEDIFF(sparcsn4.inv_unit_fcy_visit.time_out,DATE_ADD(sparcsn4.inv_unit_fcy_visit.time_in,INTERVAL 4 day)),0) as dif
									from sparcsn4.inv_unit
									inner join sparcsn4.inv_unit_fcy_visit on sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
									where sparcsn4.inv_unit.id='$cont_number' and sparcsn4.inv_unit.category='STRGE'";
				$getDateDiff = $this->bm->dataSelect($getDateDiffQuery);
				//$dateDiffValue=$getDateDiff[0]['dif'];
				$dateDiffValue = 18;
				if($dateDiffValue>14)
				{
					//4
					$strStuffUnStuff="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						values('$billVerify',(select get_shed_bill_tarrif('$billVerify',4)),1,4)";
					$statStuffUnStuff=$this->bm->dataInsertDB1($strStuffUnStuff);
					//5
					$strStuffUnStuff="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						values('$billVerify',(select get_shed_bill_tarrif('$billVerify',5)),1,5)";
					$statStuffUnStuff=$this->bm->dataInsertDB1($strStuffUnStuff);
					//6
					$strStuffUnStuff="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						values('$billVerify',(select get_shed_bill_tarrif('$billVerify',6)),1,6)";
					$statStuffUnStuff=$this->bm->dataInsertDB1($strStuffUnStuff);
				}
				else if($dateDiffValue>7 and $dateDiffValue<=14)
				{
					//4
					$strStuffUnStuff="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						values('$billVerify',(select get_shed_bill_tarrif('$billVerify',4)),1,4)";
					$statStuffUnStuff=$this->bm->dataInsertDB1($strStuffUnStuff);
					//5
					$strStuffUnStuff="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						values('$billVerify',(select get_shed_bill_tarrif('$billVerify',5)),1,5)";
					$statStuffUnStuff=$this->bm->dataInsertDB1($strStuffUnStuff);
				}
				else if($dateDiffValue>0 and $dateDiffValue<=7)
				{
					//4
					$strStuffUnStuff="replace into shed_bill_tarrif(verify_no,tarrif_id,billType,event_type) 
						values('$billVerify',(select get_shed_bill_tarrif('$billVerify',4)),1,4)";
					$statStuffUnStuff=$this->bm->dataInsertDB1($strStuffUnStuff);
				}
				//echo "Diff :  ".$getDateDiff[0]['dif'];
			}
		}
		else
		{
			echo 'FCL';
		}
		
		//$callFunctionQry="select get_shed_bill_tarrif($billVerify,1) as tarrifHead";
		//$getTarrif = $this->bm->dataSelectDb1($callFunctionQry);
		//$data['contStat']=$callFunction ;
		//$data['amount']=$amount;
		//$data['qty']=$qty;
		//$data['rcv']=$rcv;
		//$cars = array("$amount", "$qty", "$rcv");
		//return $getTarrif;
	}
	function saveGeneratedBilltoDb()
	{
		//echo "Tot ".$this->input->post('verifyNo');
			
			/*if($this->input->post('verifyNo')=="")
			{
				$data['msg']="<font color='red'><b>Please Generate Bill First.</b></font>";
			}
			else{*/

			$billVerify=$this->input->post('verifyNo');
			
			$contQuery="select COUNT(verify_no) as verify_no from shed_bill_master where verify_no='$billVerify'";
			$contNum = $this->bm->dataSelectDb1($contQuery);
			$verify_no = $contNum[0]['verify_no'];
			if($verify_no>0)
			{
				$data['stat']="<font color='red'><b>Bill Already Generated.</b></font>";
				$data['chkGenerate']=1;
			}
			else{
			
			/****************** Save Data For Shed Bill Master START ***************************************/
			$str="select  import_rotation,cont_number,verify_number,Vessel_Name,Line_No,BL_No,wr_date,wr_upto_date,cnf_lic_no,be_no,be_date,notify_name 
					from shed_tally_info
					inner join igm_supplimentary_detail on igm_supplimentary_detail.id = shed_tally_info.igm_sup_detail_id
					inner join igm_masters on igm_supplimentary_detail.igm_master_id=igm_masters.id
					left join verify_other_data on shed_tally_info.id=verify_other_data.shed_tally_id
					where verify_number=$billVerify";
					//echo $str;
			$rtnContainerList = $this->bm->dataSelectDb1($str);
			$cnf_lic_no = $rtnContainerList[0]['cnf_lic_no'];
			$container = $rtnContainerList[0]['cont_number'];
			$import_rotation = $rtnContainerList[0]['import_rotation'];
			$verify_number = $rtnContainerList[0]['verify_number'];
			$Vessel_Name = $rtnContainerList[0]['Vessel_Name'];
			$Line_No = $rtnContainerList[0]['Line_No'];
			$BL_No = $rtnContainerList[0]['BL_No'];
			$wr_date = $rtnContainerList[0]['wr_date'];
			$wr_upto_date = $rtnContainerList[0]['wr_upto_date'];
			$be_no = $rtnContainerList[0]['be_no'];
			$be_date = $rtnContainerList[0]['be_date'];
			$notify_name = $rtnContainerList[0]['notify_name'];
			
			
				$getCnfNameQuery= "SELECT ref_bizunit_scoped.name
									FROM inv_unit 
									INNER JOIN inv_goods ON inv_goods.gkey=inv_unit.goods
									LEFT JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=inv_goods.consignee_bzu
									WHERE inv_unit.id='$container' AND ref_bizunit_scoped.id LIKE '$cnf_lic_no'";
				$getCnfName = $this->bm->dataSelect($getCnfNameQuery);
				$getCnfNameValue=$getCnfName[0]['name'];
				
				$arraivalDateQry="select date(sparcsn4.argo_carrier_visit.ata) as ata from sparcsn4.vsl_vessel_visit_details
									inner join sparcsn4.argo_carrier_visit 
									on sparcsn4.argo_carrier_visit.cvcvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
									where sparcsn4.vsl_vessel_visit_details.ib_vyg='$import_rotation'";
				$arraivalDate = $this->bm->dataSelect($arraivalDateQry);
				$arraivalDateValue=$arraivalDate[0]['ata'];
				
				$getExRateQuery= "select rate from bil_currency_exchange_rates where DATE(effective_date)= '$arraivalDateValue'";
				$getExRate = $this->bm->dataSelectDb1($getExRateQuery);
				$getExRateValue=$getExRate[0]['rate'];
				
				$getManifestQtyQuery="select cont_size,cont_height,IFNULL(CEIL(igm_sup_detail_container.Cont_gross_weight/1000),0) as Qty from igm_sup_detail_container
										inner join shed_tally_info on
										shed_tally_info.igm_sup_detail_id = igm_sup_detail_container.id 
										where verify_number=$billVerify";
				$getManifest = $this->bm->dataSelectDb1($getManifestQtyQuery);
				$cont_size=$getManifest[0]['cont_size'];
				$cont_height=$getManifest[0]['cont_height'];
				$manifest_qty=$getManifest[0]['Qty'];
				
				//echo "eytr".$getExRateQuery;
				//for($i=0;$i<count($rtnContainerList);$i++) 
				//{
					/*
						echo "Rotation : ".$rtnContainerList[$i]['import_rotation']."Cont : ".$rtnContainerList[$i]['cont_number']."BL : ".$rtnContainerList[$i]['BL_No']
						."verifyNo : ".$rtnContainerList[$i]['verify_number']."Vessel : ".$rtnContainerList[$i]['Vessel_Name']."LINE : ".$rtnContainerList[$i]['Line_No']
						."WR DATE : ".$rtnContainerList[$i]['wr_date']."WR upto : ".$rtnContainerList[$i]['wr_upto_date']
						."CNF LIC No : ".$rtnContainerList[$i]['cnf_lic_no']."BE No : ".$rtnContainerList[$i]['be_no']
						."BE DT : ".$rtnContainerList[$i]['be_date']."Cnf Name : ".$getCnfNameValue					
						;
					*/
					$shedMasterInsertQry="insert into shed_bill_master (verify_no,unit_no,cpa_vat_reg_no,ex_rate,bill_date,
										arraival_date,import_rotation,vessel_name,
										cl_date,bl_no,wr_date,wr_upto_date,importer_vat_reg_no,importer_name,cnf_lic_no,cnf_agent,
										be_no,be_date,ado_no,ado_date,ado_valid_upto,
										manifest_qty,cont_size,cont_height) 
										values 
										('$verify_number','1','2041001546','$getExRateValue',now(),'$arraivalDateValue','$import_rotation',
										 '$Vessel_Name','','$BL_No','$wr_date',
										 '$wr_upto_date','','$notify_name','$cnf_lic_no','$getCnfNameValue',
										 '$be_no','$be_date','','','','$manifest_qty','$cont_size','$cont_height'
										)";
					//echo $shedMasterInsertQry;
					$shedMasterInsert=$this->bm->dataInsertDB1($shedMasterInsertQry);
					
					if($shedMasterInsert==1)
					{
						/****************** Save Data For Shed Bill Details START ***************************************/
						$getShedMasterIDQuery= "select bill_no from shed_bill_master where verify_no=$billVerify";
						$getShedMasterID = $this->bm->dataSelectDb1($getShedMasterIDQuery);
						$getShedMasterIDValue=$getShedMasterID[0]['bill_no'];
						
						if($wr_date=="")
							{
								$getDateDiffQuery= "SELECT IFNULL(DATEDIFF(valid_up_to_date,DATE_ADD(wr_date,INTERVAL 4 day)),0) as dif from shed_tally_info
													left join verify_other_data on shed_tally_info.id=verify_other_data.shed_tally_id
													where shed_tally_info.verify_number='$billVerify'";
							}
							else
							{
								$getDateDiffQuery= "SELECT IFNULL(DATEDIFF(valid_up_to_date,DATE_ADD('$wr_date',INTERVAL 4 day)),0) as dif from shed_tally_info
													left join verify_other_data on shed_tally_info.id=verify_other_data.shed_tally_id
													where shed_tally_info.verify_number='$billVerify'";
							}
						
						/*$getDateDiffQuery= "select IFNULL(DATEDIFF(sparcsn4.inv_unit_fcy_visit.time_out,DATE_ADD(sparcsn4.inv_unit_fcy_visit.time_in,INTERVAL 4 day)),0) as dif
												from sparcsn4.inv_unit
												inner join sparcsn4.inv_unit_fcy_visit on sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
												where sparcsn4.inv_unit.id='$container' and sparcsn4.inv_unit.category='STRGE'";*/
						$getDateDiff = $this->bm->dataSelectDb1($getDateDiffQuery);
						$dateDiffValue=$getDateDiff[0]['dif'];
						//$dateDiffValue=15;
						
						
						$qry= "select verify_no,tarrif_id,bil_tariffs.description,bil_tariffs.gl_code,IFNULL(bil_tariff_rates.amount,0) as tarrif_rate,
							ifnull(verify_other_data.update_ton,CEIL(igm_sup_detail_container.Cont_gross_weight/1000)) as Qty,
							igm_sup_detail_container.Cont_gross_weight,
					(case 
						when 
							tarrif_id like '%1ST%'
						then 
							 if($dateDiffValue<7,$dateDiffValue,7)
						else 
							case 
								when 
									tarrif_id like '%2ND%'
								then 
									if($dateDiffValue<14,$dateDiffValue-7,7)
								else  
									if(tarrif_id like '%3RD%',$dateDiffValue-14,1)
							end
					end) as qday,
										(select tarrif_rate*Qty*qday) as amt,
										(select amt*15/100) as vatTK
										from shed_bill_tarrif
										inner join bil_tariffs on 
										shed_bill_tarrif.tarrif_id= bil_tariffs.id
										inner join bil_tariff_rates on
										bil_tariffs.gkey=bil_tariff_rates.tariff_gkey
										inner join shed_tally_info on
										shed_tally_info.verify_number=shed_bill_tarrif.verify_no
										inner join igm_sup_detail_container on igm_sup_detail_container.igm_sup_detail_id = shed_tally_info.igm_sup_detail_id
										inner join verify_other_data on verify_other_data.shed_tally_id=shed_tally_info.id
										where verify_no='$billVerify'";
							//echo $qry;
						$chargeList = $this->bm->dataSelectDb1($qry);
						
						for($i=0;$i<count($chargeList);$i++) 
						{
							if($chargeList[$i]['qday']>0)
							{
							$gl_code=$chargeList[$i]['gl_code'];
							$description=$chargeList[$i]['description'];
							$tarrif_rate=$chargeList[$i]['tarrif_rate'];
							$Qty=$chargeList[$i]['Qty'];
							$qday=$chargeList[$i]['qday'];
							$amt=$chargeList[$i]['amt'];
							$vatTK=$chargeList[$i]['vatTK'];
							
							
							$shedDetailInsertQry="insert into shed_bill_details (verify_no,bill_no,gl_code,description,tarrif_rate,Qty,qday,amt,vatTK,mlwfTK) 
													values 
													('$billVerify','$getShedMasterIDValue','$gl_code','$description','$tarrif_rate',
														'$Qty','$qday','$amt','$vatTK','')";
							//echo $shedDetailInsertQry;
							
							$shedDetailInsert=$this->bm->dataInsertDB1($shedDetailInsertQry);
							
							if($shedDetailInsert==1)
							{
								$data['stat']="<font color='green'><b>Bill Successfully Saved.</b></font>";
							}
							/*echo "GL : ".$chargeList[$i]['gl_code']."Description : ".$chargeList[$i]['description']."Rate : ".$chargeList[$i]['tarrif_rate']
								."Qty : ".$chargeList[$i]['Qty']."Days : ".$chargeList[$i]['qday']."Port(tk) : ".$chargeList[$i]['amt']
								."Vat(tk) : ".$chargeList[$i]['vatTK']."MLWF(tk) : ".$chargeList[$i]['gl_code']
								."Verify No : ".$chargeList[$i]['verify_no']
								;*/
							}
						}
						
						/****************** Save Data For Shed Bill Details END***************************************/
					}
					else
					{
						$data['stat']="<font color='red'><b>Not inserted</b></font>";
					}
				}	
			
			
			
			
			
			/****************** Save Data For Shed Bill Master END ***************************************/
			
				if($this->input->post('verifyNo')=="")
				{
					$billVerify=$this->uri->segment(3);
					$data['msg']="Generate Bill For The Verification Number ".$billVerify;
				}
				else
				{
					$billVerify=$this->input->post('verifyNo');
					$data['msg']="Generate Bill For The Verification Number ".$billVerify;
				}
			//$billVerify=$this->input->post('verifyNo');
			//}
			$data['title']="Shed Bill FORM...";
			
			
			//$tariffData=$this->tariffGenerate($billVerify);
			//$this->tariffGenerate($billVerify);
			
			$contQuery="select cont_number from shed_tally_info where verify_number='$billVerify'";
			//echo "Query : ".$contQuery;
			$contNum = $this->bm->dataSelectDb1($contQuery);
			$container = $contNum[0]['cont_number'];	
			
			/*$getDateDiffQuery= "select IFNULL(DATEDIFF(sparcsn4.inv_unit_fcy_visit.time_out,DATE_ADD(sparcsn4.inv_unit_fcy_visit.time_in,INTERVAL 4 day)),0) as dif
									from sparcsn4.inv_unit
									inner join sparcsn4.inv_unit_fcy_visit on sparcsn4.inv_unit_fcy_visit.unit_gkey=sparcsn4.inv_unit.gkey
									where sparcsn4.inv_unit.id='$container' and sparcsn4.inv_unit.category='STRGE'";*/
			if($wr_date=="")
			{
				$getDateDiffQuery= "SELECT IFNULL(DATEDIFF(valid_up_to_date,DATE_ADD(wr_date,INTERVAL 4 day)),0) as dif from shed_tally_info
									left join verify_other_data on shed_tally_info.id=verify_other_data.shed_tally_id
									where shed_tally_info.verify_number='$billVerify'";
			}
			else
			{
				$getDateDiffQuery= "SELECT IFNULL(DATEDIFF(valid_up_to_date,DATE_ADD('$wr_date',INTERVAL 4 day)),0) as dif from shed_tally_info
									left join verify_other_data on shed_tally_info.id=verify_other_data.shed_tally_id
									where shed_tally_info.verify_number='$billVerify'";
			}						
			$getDateDiff = $this->bm->dataSelectDb1($getDateDiffQuery);
			$dateDiffValue=$getDateDiff[0]['dif'];
			//$dateDiffValue=15;
			
			
			$qry= "select verify_no,tarrif_id,bil_tariffs.description,bil_tariffs.gl_code,IFNULL(bil_tariff_rates.amount,0) as tarrif_rate,
							ifnull(verify_other_data.update_ton,CEIL(igm_sup_detail_container.Cont_gross_weight/1000)) as Qty,
							igm_sup_detail_container.Cont_gross_weight,
					(case 
						when 
							tarrif_id like '%1ST%'
						then 
							 if($dateDiffValue<7,$dateDiffValue,7)
						else 
							case 
								when 
									tarrif_id like '%2ND%'
								then 
									if($dateDiffValue<14,$dateDiffValue-7,7)
								else  
									if(tarrif_id like '%3RD%',$dateDiffValue-14,1)
							end
					end) as qday,
										(select tarrif_rate*Qty*qday) as amt,
										(select amt*15/100) as vatTK
										from shed_bill_tarrif
										inner join bil_tariffs on 
										shed_bill_tarrif.tarrif_id= bil_tariffs.id
										inner join bil_tariff_rates on
										bil_tariffs.gkey=bil_tariff_rates.tariff_gkey
										inner join shed_tally_info on
										shed_tally_info.verify_number=shed_bill_tarrif.verify_no
										inner join igm_sup_detail_container on igm_sup_detail_container.igm_sup_detail_id = shed_tally_info.igm_sup_detail_id
										inner join verify_other_data on verify_other_data.shed_tally_id=shed_tally_info.id
										where verify_no='$billVerify'";
				
			$chargeList = $this->bm->dataSelectDb1($qry); 
			
			$contQuery="select COUNT(verify_no) as verify_no from shed_bill_master where verify_no='$billVerify'";
			$contNum = $this->bm->dataSelectDb1($contQuery);
			$verify_no = $contNum[0]['verify_no'];
			if($verify_no>0)
			{
				//$data['stat']="<font color='red'><b>Bill Already Generated.</b></font>";
				$data['chkGenerate']=1;
			}
			
			
			$data['title']="Shed Bill FORM...";
			$data['chargeList']= $chargeList;
			$this->load->view('header5');
			$this->load->view('shedBillForm',$data);
			$this->load->view('footer_1');
			
			
			
			
			//$data['tariffData']=$tariffData;
			//$data['chargeList']= $chargeList;
		
	}
	function exchangeRateForm()
		{
			$session_id = $this->session->userdata('value');
			if($session_id!=$this->session->userdata('session_id'))
			{
				$this->logout();
			}
			else
			{
				$excngQry="select gkey,id from bil_currencies order by gkey";
				$gkeyList = $this->bm->dataSelectDb1($excngQry); 			
				$data['gkeyList']= $gkeyList;
			
				$data['title']="Add Exchange Rate...";
				$this->load->view('header5');
				$this->load->view('addExchangeRateForm',$data);
				$this->load->view('footer_1');
			}	
		}
	function addExchangeRate()
	{
		$session_id = $this->session->userdata('value');
			if($session_id!=$this->session->userdata('session_id'))
			{
				$this->logout();
			}
			else
			{
				$frmCurrID= $this->input->post('frmCurrency');
				$toCurrID= $this->input->post('toCurrency');
				$excngDt= $this->input->post('excngDt');
				$excngRate= $this->input->post('excngRate');
				$notes= $this->input->post('notes');
				$login_id = $this->session->userdata('login_id');
				
				$excngInsertQry="replace into bil_currency_exchange_rates (rate,notes,effective_date,from_currency_gkey,to_currency_gkey,created,creator)
									values ($excngRate,'$notes','$excngDt',$frmCurrID,$toCurrID,NOW(),'$login_id') ";
				//echo $excngInsertQry;
				$stat=$this->bm->dataInsertDB1($excngInsertQry);
				if($stat==1)
				{
					$data['stat']="<font color='green'><b>Sucessfully inserted</b></font>";
				}else{
					$data['stat']="<font color='red'><b>Not inserted</b></font>";
				}
							
				$excngQry="select gkey,id from bil_currencies order by gkey";
				$gkeyList = $this->bm->dataSelectDb1($excngQry); 			
				$data['gkeyList']= $gkeyList;
				
				$data['title']="Add Exchange Rate...";
				$this->load->view('header5');
				$this->load->view('addExchangeRateForm',$data);
				$this->load->view('footer_1');
			}	
	}
	
	function logout()
	{ 
		$data['body']="<font color='blue' size=2>LogOut Successfully....</font>";
		$this->session->sess_destroy();
		$this->cache->clean();
		//redirect(base_url(),$data);
		$this->load->view('header');
		$this->load->view('welcomeview_1', $data);
		$this->load->view('footer');
		$this->db->cache_delete_all();
	}
	


	public function getShedBillPdf()
	{ 
		//load mPDF library
		$login_id = $this->session->userdata('login_id');
		$this->load->library('m_pdf');
		//$mpdf->use_kwt = true;
				$billVerify = "";
				if($this->input->post('sendVerifyNo'))
				{
					$billVerify=$this->input->post('sendVerifyNo');
					/*$shedbill=$this->input->post('shedbill');  //bill no
					$rcvstat=$this->input->post('rcvstat');
					$cpnoview=$this->input->post('cpnoview');
					$cpbankcode=$this->input->post('cpbankcode');
					if($cpbankcode=="OB")
						$cpbankname="ONE BANK LIMITED";*/
					
				}
				else if($this->input->post('verify_num'))
				{
					$billVerify=$this->input->post('verify_num');
					
				}
				else{
					$billVerify=str_replace("_","/",$this->uri->segment(3));
					//$ddl_imp_rot_no=str_replace("_","/",$this->uri->segment(4));
					//$ddl_imp_rot_no=$this->uri->segment(4);
					//$rot_year=$this->uri->segment(4);
					//$rot_no=$this->uri->segment(5);
					//$ddl_imp_rot_no=$rot_year.'/'.$rot_no;
					
					//ECHO $billVerify;
				}
		$strBankPaymentInfo = "select shed_bill_master.bill_no,bill_rcv_stat,cp_bank_code,user,
		concat(cp_bank_code,cp_unit,'/',right(cp_year,2),'-',concat(if(length(cp_no)=1,'000',if(length(cp_no)=2,'00',if(length(cp_no)=3,'0',''))),cp_no)) as cp_no
		from shed_bill_master 
		left join bank_bill_recv on bank_bill_recv.bill_no=shed_bill_master.bill_no
		where verify_no='$billVerify'";
		$rtnBankPaymentInfo = $this->bm->dataSelectDb1($strBankPaymentInfo);
		
		$rcvstat="";
		$cpnoview="";
		$cpbankcode="";
		$shedbill="";
		$bill_clerk="";
		
		if($rtnBankPaymentInfo!=null){
			$rcvstat=$rtnBankPaymentInfo[0]['bill_rcv_stat'];
			$cpnoview=$rtnBankPaymentInfo[0]['cp_no'];
			$cpbankcode=$rtnBankPaymentInfo[0]['cp_bank_code'];
			$shedbill=$rtnBankPaymentInfo[0]['bill_no'];
			$bill_clerk=$rtnBankPaymentInfo[0]['user'];
		}
		$cpbankname="";
		if($cpbankcode=="OB")
			$cpbankname="ONE BANK LIMITED";
		//load mPDF library
	   /*if($this->input->post('sendVerifyNo'))
		{
		  $billVerify=$this->input->post('sendVerifyNo');
		}
		else
		{
		$billVerify=$this->uri->segment(3);
		}*/
		
		$str="select concat(right(YEAR(bill_date),2),'/',
							concat(if(length(bill_generation_no)=1,'00000',if(length(bill_generation_no)=2,'0000',if(length(bill_generation_no)=3,'000',
							if(length(bill_generation_no)=4,'00',if(length(bill_generation_no)=5,'0',''))))),bill_generation_no)) as bill_no,verify_no,unit_no,cpa_vat_reg_no,ex_rate,bill_date,arraival_date,import_rotation,vessel_name,
			cl_date,bl_no,wr_date,wr_upto_date,importer_vat_reg_no,importer_name,cnf_lic_no,cnf_agent,be_no,
			be_date,ado_no,ado_date,ado_valid_upto,manifest_qty,cont_size,cont_height from shed_bill_master 
			where verify_no='$billVerify'";
				//echo $str;
				//echo $str;
		$rtnContainerList = $this->bm->dataSelectDb1($str);
		$unit_no=$rtnContainerList[0]['unit_no'];
		$cpa_vat_reg_no=$rtnContainerList[0]['cpa_vat_reg_no'];
		$ex_rate=$rtnContainerList[0]['ex_rate'];
		
		$qry="select verify_no,bill_no,gl_code,description,tarrif_rate,Qty,qday,amt,vatTK,mlwfTK from shed_bill_details
					where verify_no='$billVerify'";
				//echo $qry;
			$chargeList = $this->bm->dataSelectDb1($qry); 
			
			$qry_sum="select SUM(amt) as amt from shed_bill_details
					where verify_no='$billVerify'";
				//echo $qry;
			$sumAll = $this->bm->dataSelectDb1($qry_sum);
			$tot_sum=$sumAll[0]['amt'];
			
			$qry_qday="select IFNULL(SUM(qday),0) as qday from shed_bill_details
					where verify_no='$billVerify' AND gl_code not in('501005','502000N','503000N')";
				//echo $qry;
			$qdayAll = $this->bm->dataSelectDb1($qry_qday);
			$tot_qday=$qdayAll[0]['qday'];
			
		$sqlrcvdate="SELECT recv_by,DATE(recv_time) AS recv_time FROM bank_bill_recv WHERE bill_no='$shedbill'";
		$rtnrcvdate = $this->bm->dataSelectDb1($sqlrcvdate);
		
		$recv_by="";
		$recv_time = "";
		
		if($rtnrcvdate!=null){
			$recv_by=$rtnrcvdate[0]['recv_by'];
			$recv_time=$rtnrcvdate[0]['recv_time'];
		}
		
		 $this->data['rtnContainerList']=$rtnContainerList;
		 $this->data['chargeList']=$chargeList;

		//now pass the data//
		 $this->data['title']="Shed Bill";
		 $this->data['verify_number']=$billVerify;
		 $this->data['rcvstat']=$rcvstat;
		$this->data['cpnoview']=$cpnoview;
		$this->data['cpbankname']=$cpbankname;
		$this->data['recv_time']=$recv_time;
		$this->data['recv_by']=$recv_by;
		 $this->data['tot_sum']=$tot_sum;
		 $this->data['tot_qday']=$tot_qday;
		 
		 $this->data['bill_clerk']=$bill_clerk;
		 $this->data['bill_print_times']=1;
		 //$this->data['amountInwords']=convert_number_to_words(5000);
		 
		 $this->data['unit_no']=$unit_no;
		 $this->data['cpa_vat_reg_no']=$cpa_vat_reg_no;
		 $this->data['ex_rate']=$ex_rate;
		
		$html=$this->load->view('shedBillPdfOutput',$this->data, true); //load the pdf_output.php by passing our data and get all data in $html varriable.
		
		//this the the PDF filename that user will get to download
		$pdfFilePath ="shedBill-".time()."-download.pdf";
		
		//actually, you can pass mPDF parameter on this load() function
		$pdf = $this->m_pdf->load();
		//$pdf->mirrorMargins = 1;
		//generate the PDF!
		//$stylesheet = file_get_contents('assets/css/main.css');
        //$mpdf->WriteHTML($stylesheet,1);
		$stylesheet = file_get_contents('assets/stylesheets/shedBill.css'); // external css
		$pdf->useSubstitutions = true; // optional - just as an example
		//$pdf->SetHeader($url . "\n\n" . 'Date {DATE j-m-Y}');  // optional - just as an example
		//echo "SheetAdd : ".$stylesheet;
		//$pdf->setFooter('Prepared By :'.$bill_clerk.'|Page {PAGENO}|Date {DATE j-m-Y}');
		//$footerHtml='<pagefooter name="MyFooter1" content-left="{DATE j-m-Y}" content-center="{PAGENO}/{nbpg}" content-right="My document" footer-style="font-family: serif; font-size: 8pt; font-weight: bold; font-style: italic; color: #000000;" />';
		$pdf->WriteHTML($stylesheet,1);
		$pdf->WriteHTML($html,2);
		//$pdf->WriteHTML('<pagebreak resetpagenum="1" pagenumstyle="1" suppress="off" />');
		//offer it to user via browser download! (The PDF won't be saved on your server HDD)
		//$pdf->Output($pdfFilePath, "D"); /// For Direct Download 
		$pdf->Output($pdfFilePath, "I"); // For Show Pdf
	}
	

	

	
	
	
	function getIgmDetailsByVerifyNumber()
	{
		
		//$verifyNo=$this->input->post('sendVerifyNo');
		
		if($this->input->post('sendVerifyNo'))
		{
		  $verifyNo=$this->input->post('sendVerifyNo');
		}
		else
		{			
	      $verifyNo=$this->uri->segment(3);
		}
			
		$data['title']="IGM DETAILS FOR THE VERIFY NO: ".$verifyNo;
		
		$verifyReport = "select igm_supplimentary_detail.id,IFNULL(SUM(shed_tally_info.rcv_pack)+SUM(shed_tally_info.loc_first),0) as rcv_pack,shed_tally_info.cont_number,shed_tally_info.import_rotation,Pack_Marks_Number,shed_loc,shed_yard,
            Description_of_Goods,ConsigneeDesc,NotifyDesc,cont_size,cont_weight,cont_seal_number,cont_status,cont_height,cont_iso_type,IFNULL(shed_tally_info.verify_number,0) as verify_number,
            shed_tally_info.wr_upto_date,shed_tally_info.verify_by,shed_tally_info.verify_time,shed_tally_info.wr_upto_date,IFNULL(shed_tally_info.id,0) as verify_id,
            (select Organization_Name from organization_profiles where organization_profiles.id=igm_sup_detail_container.off_dock_id) as offdock_name,
             agent_do,do_date,be_no,be_date,cnf_lic_no,update_ton
             from  igm_supplimentary_detail
             inner join igm_sup_detail_container on igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
             left join  shed_tally_info on shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
             left join verify_other_data on shed_tally_info.id=verify_other_data.shed_tally_id
             where shed_tally_info.verify_number='$verifyNo'";
		
		$rtnVerifyReport = $this->bm->dataSelectDb1($verifyReport);
			
		$data['rtnVerifyReport']=$rtnVerifyReport;
	
		$this->load->view('getIgmDetailsByVerifyForm',$data);

		
	}
	
	   //-----BILL SEARCH BY VERIFY NUMBER start
	function billSearchByVerifyForm()
	{
		$session_id = $this->session->userdata('value');
		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();
		}
		else
		{	$data['tblFlag']=0;
			$search = 0;
			$data['search']=$search;
			$data['title']="BILL SEARCH BY VERIFY NUMBER...";

			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('billSearchByVerifyForm',$data);
			$this->load->view('jsAssets');
		}
	}
	
	
	
	
	
	// function bilSearchByVerifyNumber()
	// {
	// 	$session_id = $this->session->userdata('value');
	// 	if($session_id!=$this->session->userdata('session_id'))
	// 	{
	// 		$this->logout();
	// 	}
	// 	else
	// 	{	
	// 		$search = 1;
	// 		$data['search']=$search;
	// 		$data['title']="BILL SEARCH BY VERIFY NUMBER...";
	// 		$verifyNo="";
	// 	if($this->uri->segment(3) != null )
	// 	{
	// 		$verifyNo = $this->uri->segment(3);
	// 	}
	// 	else
	// 	{
	// 		$verifyNo = $this->input->post('verifyNo');
	// 	}


    //     $checkFclSql = "SELECT igm_detail_container.cont_status 
	// 		FROM igm_details
	// 		INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
	// 		INNER JOIN certify_info_fcl ON certify_info_fcl.igm_detail_id=igm_details.id
	// 		INNER JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
	// 		INNER JOIN appraisement_info_fcl ON appraisement_info_fcl.rotation=igm_details.Import_Rotation_No
    //         AND appraisement_info_fcl.BL_NO=igm_details.BL_No
	// 		INNER JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
	// 		WHERE verify_info_fcl.verify_number='$verifyNo'";
	// 	$checkFCLData = $this->bm->dataSelectDb1($checkFclSql);



    //         if(@$checkFCLData[0]['cont_status']){  //===========================================================FCL Block
    //             $fclFlagValue = 1;

    //             //$verify = substr($verifyNo, -4);

    //             $doReportQuery ="SELECT count(verify_no) as total, sum(delv_pack) as total_do_pack, count(truck_id) as total_truck_assign 
	// 	                from do_information where verify_no='$verifyNo'";
    //             $checkDo = $this->bm->dataSelectDb1($doReportQuery);

    //             $deliverd_truck = $checkDo[0]['total_truck_assign'];
    //             //$total_pack_in_DO= $checkDo[0]['total_do_pack'];
    //             //$total_truck_assign_in_DO = $checkDo[0]['total_truck_assign'];


    //             //$rem_truck=$truckNum-$deliverd_truck;

    //             $verifyReport = "SELECT shed_bill_master.bill_no,verify_info_fcl.verify_number,verify_info_fcl.verify_unit AS unit_no,igm_details.Import_Rotation_No AS import_rotation,
	// 				igm_masters.Vessel_Name AS vessel_name,igm_details.BL_No AS bl_no,
	// 				igm_details.Description_of_Goods,Qty,bill_rcv_stat,IF(bill_rcv_stat=1,'Paid','Not Paid') AS paid_status,igm_detail_container.cont_number,
	// 				igm_details.Pack_Number,igm_details.Pack_Description,
	// 				verify_info_fcl.no_of_truck
	// 				FROM igm_details
	// 				INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
	// 				INNER JOIN certify_info_fcl ON certify_info_fcl.igm_detail_id=igm_details.id
	// 				INNER JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
	// 				INNER JOIN shed_bill_master ON shed_bill_master.verify_no = verify_info_fcl.verify_number
	// 				LEFT JOIN shed_bill_details ON shed_bill_master.bill_no =shed_bill_details.bill_no
	// 				INNER JOIN appraisement_info_fcl ON appraisement_info_fcl.rotation=igm_details.Import_Rotation_No AND appraisement_info_fcl.BL_NO=igm_details.BL_No
	// 				INNER JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
	// 				WHERE verify_info_fcl.verify_number='$verifyNo'
	// 				GROUP BY bill_no";
    //             //query-1: shed_bill_master.unit_no ki?
    //             $rtnVerifyReport = $this->bm->dataSelectDb1($verifyReport);
    //             $truck_num=$rtnVerifyReport[0]['no_of_truck'];
    //             $data['rtnVerifyReport'] = $rtnVerifyReport;



    //             $rem_truck=$truck_num-$deliverd_truck;
    //             $data['deliverd_truck']=$deliverd_truck;
    //             $data['rem_truck']=$rem_truck;

    //             if($deliverd_truck>=0)
    //             {
    //                 $doQuery="SELECT verify_no, delv_pack, truck_id, gate_no from do_information where verify_no='$verifyNo' order by id";
    //                 $doInfo = $this->bm->dataSelectDb1($doQuery);
    //                 $data['doInfo']=$doInfo;
    //                 $doShowFlag=1;
    //                 $data['doShowFlag']=$doShowFlag;
    //                 if($truck_num>$deliverd_truck)
    //                 {
    //                     $data['dlv_btn_status']=1;
    //                 }
    //                 else
    //                 {
    //                     //$dlv_btn_status=1;
    //                     $data['dlv_btn_status']=0;
    //                 }
    //                 $data['tblFlag']=1;
    //             }else
    //             {
    //                 $data['dlv_btn_status']=1;
    //                 $data['doShowFlag']=0;
    //                 $data['tblFlag']=1;
    //             }

    //         }else{                              //=============================================================LCL Block
    //             $fclFlagValue = 0;

    //             //$verify = substr($verifyNo, -4);

    //             $doReportQuery ="SELECT count(verify_no) as total, sum(delv_pack) as total_do_pack, count(truck_id) as total_truck_assign 
	// 	                from do_information where verify_no='$verifyNo'";
    //             $checkDo = $this->bm->dataSelectDb1($doReportQuery);

    //             $deliverd_truck = $checkDo[0]['total_truck_assign'];
    //             //$total_pack_in_DO= $checkDo[0]['total_do_pack'];
    //             //$total_truck_assign_in_DO = $checkDo[0]['total_truck_assign'];
	// 	//      echo "<pre>";
	// 	//      print_r($deliverd_truck);
	// 	//      echo "</pre>";
	// 	//      exit();

    //             //$rem_truck=$truckNum-$deliverd_truck;

    //             $verifyReport = "SELECT shed_bill_master.bill_no, shed_tally_info.verify_number, unit_no,shed_tally_info.import_rotation,
	// 	vessel_name,shed_bill_master.bl_no,igm_supplimentary_detail.Description_of_Goods,Qty, shed_loc, shed_yard,
	// 	if(bill_rcv_stat=1,'Paid','Not Paid') as paid_status, cont_number,
    //             igm_supplimentary_detail.Pack_Number, igm_supplimentary_detail.Pack_Description,
    //             (shed_tally_info.rcv_pack+ ifnull(shed_tally_info.loc_first,0)) as rcv_pack,shed_tally_info.rcv_unit, no_of_truck
	// 	FROM shed_tally_info 
	// 	left JOIN shed_bill_master ON shed_tally_info.verify_number=shed_bill_master.verify_no
	// 	left JOIN shed_bill_details ON shed_bill_master.bill_no =shed_bill_details.bill_no
	// 	left JOIN verify_other_data ON shed_tally_info.id=verify_other_data.shed_tally_id
    //     inner join igm_supplimentary_detail on igm_supplimentary_detail.id=shed_tally_info.igm_sup_detail_id
	//     left join igm_details on igm_details.id=igm_supplimentary_detail.igm_detail_id
	// 	WHERE shed_tally_info.verify_number='$verifyNo'
	// 	GROUP BY bill_no";
    //             //query-1: shed_bill_master.unit_no ki?
    //             $rtnVerifyReport = $this->bm->dataSelectDb1($verifyReport);
    //             $truck_num=$rtnVerifyReport[0]['no_of_truck'];
    //             $data['rtnVerifyReport'] = $rtnVerifyReport;



    //             $rem_truck=$truck_num-$deliverd_truck;
    //             $data['deliverd_truck'] = $deliverd_truck;
    //             $data['rem_truck']=$rem_truck;


    //             if($deliverd_truck>=0)
    //             {
    //                 $doQuery="SELECT verify_no, delv_pack, truck_id, gate_no from do_information where verify_no='$verifyNo' order by id";
    //                 $doInfo = $this->bm->dataSelectDb1($doQuery);
    //                 $data['doInfo']=$doInfo;
    //                 $doShowFlag=1;
    //                 $data['doShowFlag']=$doShowFlag;
    //                 if($truck_num>$deliverd_truck)
    //                 {
    //                     $data['dlv_btn_status']=1;
    //                 }
    //                 else
    //                 {
    //                     //$dlv_btn_status=1;
    //                     $data['dlv_btn_status']=0;
    //                 }
    //                 $data['tblFlag']=1;
    //             }else
    //             {
    //                 $data['dlv_btn_status']=1;
    //                 $data['doShowFlag']=0;
    //                 $data['tblFlag']=1;
    //             }
    //         }
    //         $data['fclFlagValue'] = $fclFlagValue;



	// 	//echo $deliverd_truck;
	// 	//$rcv_pack=$rtnVerifyReport[0]['rcv_pack'];
	// 	//$no_of_truck=$rtnVerifyReport[0]['no_of_truck'];
    //   /*
	//   if($total_truck_assign_in_DO >=$no_of_truck)
	// 	{
	// 		$data['rtnVerifyReport']=$rtnVerifyReport;
	// 		$data['msg']="Already Delivered or Truck already assiged.";
	// 		$data['verifyNo']=$verifyNo;
	// 		$data['tblFlag']=0;
	// 		$data['dlv_btn_status']=0;
	// 	}
	// 	else if($total_pack_in_DO>=$rcv_pack)
	// 	{
	// 		$data['rtnVerifyReport']=$rtnVerifyReport;
	// 		//$data['msg']="No any pack remain of the verify: ".$verifyNo;
	// 		echo "No any pack remain of the verify no: ".$verifyNo;
	// 		$data['verifyNo']=$verifyNo;
	// 		$data['tblFlag']=0;
	// 		$data['dlv_btn_status']=0;
	// 	}
	// 	else{
		
	// 		$data['verifyNo']=$verifyNo;				
	// 		$data['rtnVerifyReport']=$rtnVerifyReport;
	// 		$data['truckNum']=2;
	// 		$data['tblFlag']=1;
	// 		$data['dlv_btn_status']=1;
	// 	}	
	// 	*/

	// 	    $data['verifyNo']=$verifyNo;
		    
	// 		$this->load->view('cssAssets');
	// 		$this->load->view('headerTop');
	// 		$this->load->view('sidebar');
	// 		$this->load->view('billSearchByVerifyForm',$data);
	// 		$this->load->view('jsAssets');
	// 	}
	// }

	// truck entry for fcl with verify number	- 2021-01-16
	// function bilSearchByVerifyNumber()
	// {
		// $session_id = $this->session->userdata('value');
		// if($session_id!=$this->session->userdata('session_id'))
		// {
			// $this->logout();
		// }
		// else
		// {	
			// $search = 1;
			// $data['search']=$search;
			
			// $data['title']="TRUCK DETAIL ENTRY FORM";
			// $verifyNo="";
		
			// if($this->uri->segment(3) != null )
			// {
				// $verifyNo = $this->uri->segment(3);
			// }
			// else
			// {
				// // $verifyNo = $this->input->post('verifyNo');
				// $contNo = $this->input->post('contNo');
			// }

			// $checkFclSql = "SELECT DISTINCT cont_status
			// FROM  igm_supplimentary_detail
			// INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
			// INNER JOIN shed_tally_info ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
			// WHERE verify_number='$verifyNo'

			// UNION ALL

			// SELECT DISTINCT cont_status
			// FROM  igm_details
			// INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
			// INNER JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
			// WHERE verify_number='$verifyNo'";
			// $checkFCLData = $this->bm->dataSelectDb1($checkFclSql);
			
			// $cont_status = "";
			
			// if($checkFCLData!=null){
				// $cont_status=$checkFCLData[0]['cont_status'];
			// }
			
			// $data['cont_status']=$cont_status;
			// $fclFlagValue=0;
			// if($cont_status=="FCL")
			// {  								//===========================================================FCL Block
                // $fclFlagValue = 1;

                // //$verify = substr($verifyNo, -4);

                // // $doReportQuery ="SELECT count(verify_no) as total, sum(delv_pack) as total_do_pack, count(truck_id) as total_truck_assign 
		                // // from do_information where verify_no='$verifyNo'";
						
				// $doReportQuery ="SELECT count(verify_number) as total, sum(delv_pack) as total_do_pack, count(truck_id) as total_truck_assign 
		                // from do_truck_details_entry where verify_number='$verifyNo'";
                // $checkDo = $this->bm->dataSelectDb1($doReportQuery);

                // $deliverd_truck = $checkDo[0]['total_truck_assign'];             

                // //$rem_truck=$truckNum-$deliverd_truck;
					
				// // 2020-04-30 - intakhab - start	
				// $sql_contNumber="SELECT DISTINCT cont_number FROM(SELECT igm_detail_container.cont_number
				// FROM igm_details
				// INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
				// INNER JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
				// WHERE verify_info_fcl.verify_number='$verifyNo') AS tbl";
				// $rslt_contNumber=$this->bm->dataSelectDb1($sql_contNumber);
				
				// for($i=0;$i<count($rslt_contNumber);$i++)
				// {
					// $rsltTmp[]=$rslt_contNumber[$i]['cont_number'];
				// }
				
				// $containerSet=join(", ",$rsltTmp);
				// $data['containerSet']=$containerSet;
				
				// // 2020-04-30 - intakhab - end

				// // 2021-01-13 - comment - intakhab
				// // $verifyReport = "SELECT shed_bill_master.bill_no,verify_info_fcl.verify_number,verify_info_fcl.verify_unit AS unit_no,igm_details.Import_Rotation_No AS import_rotation,
				// // igm_masters.Vessel_Name AS vessel_name,igm_details.BL_No AS bl_no,
				// // igm_details.Description_of_Goods,Qty,bill_rcv_stat,IF(bill_rcv_stat=1,'Paid','Not Paid') AS paid_status,igm_detail_container.cont_number,
				// // igm_details.Pack_Number,igm_details.Pack_Description,
				// // verify_info_fcl.no_of_truck
				// // FROM igm_details
				// // INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
				// // INNER JOIN certify_info_fcl ON certify_info_fcl.igm_detail_id=igm_details.id
				// // INNER JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
				// // INNER JOIN shed_bill_master ON shed_bill_master.verify_no = verify_info_fcl.verify_number
				// // LEFT JOIN shed_bill_details ON shed_bill_master.bill_no =shed_bill_details.bill_no
				// // INNER JOIN appraisement_info_fcl ON appraisement_info_fcl.rotation=igm_details.Import_Rotation_No AND appraisement_info_fcl.BL_NO=igm_details.BL_No
				// // INNER JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
				// // WHERE verify_info_fcl.verify_number='$verifyNo'
				// // GROUP BY bill_no";
				
				// $verifyReport = "SELECT shed_bill_master.bill_no,verify_info_fcl.verify_number,verify_info_fcl.verify_unit AS unit_no,igm_details.Import_Rotation_No AS import_rotation, igm_masters.Vessel_Name AS vessel_name,igm_details.BL_No AS bl_no, igm_details.Description_of_Goods,Qty,bill_rcv_stat,IF(bill_rcv_stat=1,'Paid','Not Paid') AS paid_status,igm_detail_container.cont_number, igm_details.Pack_Number,igm_details.Pack_Description, verify_info_fcl.no_of_truck 
				// FROM igm_details 
				// INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id 
				
				// INNER JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id 
				// LEFT JOIN shed_bill_master ON shed_bill_master.verify_no = verify_info_fcl.verify_number 
				// LEFT JOIN shed_bill_details ON shed_bill_master.bill_no =shed_bill_details.bill_no 
				// INNER JOIN igm_masters ON igm_masters.id=igm_details.IGM_id 
				// WHERE verify_info_fcl.verify_number='$verifyNo'";
				// //query-1: shed_bill_master.unit_no ki?
				// $rtnVerifyReport = $this->bm->dataSelectDb1($verifyReport);
				// $truck_num=$rtnVerifyReport[0]['no_of_truck'];
				// $data['rtnVerifyReport'] = $rtnVerifyReport;

                // $rem_truck=$truck_num-$deliverd_truck;
                // $data['deliverd_truck']=$deliverd_truck;
                // $data['rem_truck']=$rem_truck;

                // if($deliverd_truck>=0)
                // {
                    // // $doQuery="SELECT verify_no, delv_pack, truck_id, gate_no from do_information where verify_no='$verifyNo' order by id";
                    // $doQuery="SELECT verify_number, delv_pack, truck_id, gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass from do_truck_details_entry where verify_number='$verifyNo' order by id";
                    // $doInfo = $this->bm->dataSelectDb1($doQuery);
                    // $data['doInfo']=$doInfo;
                    // $doShowFlag=1;
                    // $data['doShowFlag']=$doShowFlag;
                    // if($truck_num>$deliverd_truck)
                    // {
                        // $data['dlv_btn_status']=1;
                    // }
                    // else
                    // {
                        // //$dlv_btn_status=1;
                        // $data['dlv_btn_status']=0;
                    // }
                    // $data['tblFlag']=1;
                // }
				// else
                // {
                    // $data['dlv_btn_status']=1;
                    // $data['doShowFlag']=0;
                    // $data['tblFlag']=1;
                // }
            // }
			// else
			// {                              //============================================================= LCL Block
                // $fclFlagValue = 0;               

                // // $doReportQuery ="SELECT count(verify_no) as total, sum(delv_pack) as total_do_pack, count(truck_id) as total_truck_assign 
		                // // from do_information where verify_no='$verifyNo'";

				// $doReportQuery ="SELECT count(verify_number) as total, sum(delv_pack) as total_do_pack, count(truck_id) as total_truck_assign 
		                // from do_truck_details_entry where verify_number='$verifyNo'";
                // $checkDo = $this->bm->dataSelectDb1($doReportQuery);

                // $deliverd_truck = $checkDo[0]['total_truck_assign'];               

				// $verifyReport = "SELECT shed_bill_master.bill_no, shed_tally_info.verify_number, unit_no,shed_tally_info.import_rotation,
				// vessel_name,shed_bill_master.bl_no,igm_supplimentary_detail.Description_of_Goods,Qty, shed_loc, shed_yard,
				// if(bill_rcv_stat=1,'Paid','Not Paid') as paid_status, cont_number,
				// igm_supplimentary_detail.Pack_Number, igm_supplimentary_detail.Pack_Description,
				// (shed_tally_info.rcv_pack+ ifnull(shed_tally_info.loc_first,0)) as rcv_pack,shed_tally_info.rcv_unit, no_of_truck
				// FROM shed_tally_info 
				// left JOIN shed_bill_master ON shed_tally_info.verify_number=shed_bill_master.verify_no
				// left JOIN shed_bill_details ON shed_bill_master.bill_no =shed_bill_details.bill_no
				// left JOIN verify_other_data ON shed_tally_info.id=verify_other_data.shed_tally_id
				// inner join igm_supplimentary_detail on igm_supplimentary_detail.id=shed_tally_info.igm_sup_detail_id
				// left join igm_details on igm_details.id=igm_supplimentary_detail.igm_detail_id
				// WHERE shed_tally_info.verify_number='$verifyNo'
				// GROUP BY bill_no";
				// //query-1: shed_bill_master.unit_no ki?
				// $rtnVerifyReport = $this->bm->dataSelectDb1($verifyReport);
				// $truck_num=$rtnVerifyReport[0]['no_of_truck'];
				// $data['rtnVerifyReport'] = $rtnVerifyReport;

                // $rem_truck=$truck_num-$deliverd_truck;
                // $data['deliverd_truck'] = $deliverd_truck;
                // $data['rem_truck']=$rem_truck;

                // if($deliverd_truck>=0)
                // {
                    // // $doQuery="SELECT verify_no, delv_pack, truck_id, gate_no from do_information where verify_no='$verifyNo' order by id";
                    // $doQuery="SELECT verify_number, delv_pack, truck_id, gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass from do_truck_details_entry where verify_number='$verifyNo' order by id";
                    // $doInfo = $this->bm->dataSelectDb1($doQuery);
                    // $data['doInfo']=$doInfo;
                    // $doShowFlag=1;
                    // $data['doShowFlag']=$doShowFlag;
                    // if($truck_num>$deliverd_truck)
                    // {
                        // $data['dlv_btn_status']=1;
                    // }
                    // else
                    // {
                        // //$dlv_btn_status=1;
                        // $data['dlv_btn_status']=0;
                    // }
                    // $data['tblFlag']=1;
                // }
				// else
                // {
                    // $data['dlv_btn_status']=1;
                    // $data['doShowFlag']=0;
                    // $data['tblFlag']=1;
                // }
            // }
            // $data['fclFlagValue'] = $fclFlagValue;		

		    // $data['verifyNo']=$verifyNo;
		    // $data['contNo']=$contNo;
		    
			// $this->load->view('cssAssets');
			// $this->load->view('headerTop');
			// $this->load->view('sidebar');
			// $this->load->view('billSearchByVerifyForm',$data);
			// $this->load->view('jsAssets');
		// }
	// }
	
	function bilSearchByVerifyNumber_2($rotNo=null,$contNo=null,$fclFlagValue=null,$cont_status=null)
	{
		$session_id = $this->session->userdata('value');
		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();
		}
		else
		{				
			$org_license = $this->session->userdata('org_license');
			$login_id = $this->session->userdata('login_id');
			
			$data['title']="TRUCK DETAIL ENTRY FORM";
			// $rotNo="";
			// $contNo="";
			$verifyNo="";
			$beNo="";		
			$data['actSt']=1;
			$data['cont_status']=$cont_status;
			if($cont_status=="FCL")			//FCL Block
			{  								
                $fclFlagValue = 1;
				
				// $this->addVryInfoFCL($rotNo,$contNo,$beNo,$unit_gkey);
				
				// common info
				$verifyReport = "";
				$sql_posYardBlock = "";
				
				// driver helper info
				$sql_driverInfo = "SELECT id,card_number,agent_name
				FROM vcms_vehicle_agent
				WHERE agent_type='Driver'";
				$rslt_driverInfo = $this->bm->dataSelectDB1($sql_driverInfo);
				$data['rslt_driverInfo']=$rslt_driverInfo;
				
				$sql_helperInfo = "SELECT id,card_number,agent_name
				FROM vcms_vehicle_agent
				WHERE agent_type='Helper'";
				$rslt_helperInfo = $this->bm->dataSelectDB1($sql_helperInfo);
				$data['rslt_helperInfo']=$rslt_helperInfo;
				
				if($verifyNo == "")		
				{
					$verifyReport = "SELECT shed_bill_master.bill_no,verify_info_fcl.verify_number,verify_info_fcl.verify_unit AS unit_no,igm_details.Import_Rotation_No AS import_rotation, igm_masters.Vessel_Name AS vessel_name,igm_details.BL_No AS bl_no, igm_details.Description_of_Goods,Qty,bill_rcv_stat,IF(bill_rcv_stat=1,'Paid','Not Paid') AS paid_status,igm_detail_container.cont_number,igm_detail_container.cont_size,igm_details.Pack_Number,igm_details.Pack_Description, verify_info_fcl.no_of_truck 
					FROM igm_detail_container 
					LEFT JOIN igm_details ON igm_details.id=igm_detail_container.igm_detail_id
					LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_cont_id=igm_detail_container.id
					LEFT JOIN shed_bill_master ON shed_bill_master.verify_no = verify_info_fcl.verify_number 
					LEFT JOIN shed_bill_details ON shed_bill_master.bill_no =shed_bill_details.bill_no
					LEFT JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
					WHERE igm_details.Import_Rotation_No='$rotNo' AND igm_detail_container.cont_number='$contNo'";
					
					$sql_posYardBlock = "SELECT slot AS currentPos,Yard_No,Block_No 
					FROM ctmsmis.tmp_vcms_assignment 
					WHERE rot_no='$rotNo' AND cont_no='$contNo'";
				}
				else
				{	
					$verifyReport = "SELECT shed_bill_master.bill_no,verify_info_fcl.verify_number,verify_info_fcl.verify_unit AS unit_no,igm_details.Import_Rotation_No AS import_rotation, igm_masters.Vessel_Name AS vessel_name,igm_details.BL_No AS bl_no, igm_details.Description_of_Goods,Qty,bill_rcv_stat,IF(bill_rcv_stat=1,'Paid','Not Paid') AS paid_status,igm_detail_container.cont_number,igm_detail_container.cont_size,igm_details.Pack_Number,igm_details.Pack_Description, verify_info_fcl.no_of_truck 
					FROM igm_details 
					INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id 
					INNER JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id 
					LEFT JOIN shed_bill_master ON shed_bill_master.verify_no = verify_info_fcl.verify_number 
					LEFT JOIN shed_bill_details ON shed_bill_master.bill_no =shed_bill_details.bill_no 
					INNER JOIN igm_masters ON igm_masters.id=igm_details.IGM_id 
					WHERE verify_info_fcl.verify_number='$verifyNo'";
				}
				// echo $verifyReport; return;
				$rtnVerifyReport = $this->bm->dataSelectDb1($verifyReport);
				$data['rtnVerifyReport'] = $rtnVerifyReport;
				
				if($rtnVerifyReport[0]['cont_size']==20)
					$totTruck = 2;
				else if($rtnVerifyReport[0]['cont_size']==40)
					$totTruck = 3;
				$data['totTruck'] = $totTruck;
				
				$rslt_posYardBlock = $this->bm->dataSelect($sql_posYardBlock);
				$data['rslt_posYardBlock'] = $rslt_posYardBlock;
				
				$sql_contNumber="";
				
				if($verifyNo == "")
				{
					$sql_contNumber="SELECT DISTINCT cont_number FROM(
					SELECT igm_detail_container.cont_number 
					FROM igm_detail_container 
					INNER JOIN igm_details ON igm_details.id=igm_detail_container.igm_detail_id 
					LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_cont_id=igm_detail_container.id 
					WHERE igm_details.Import_Rotation_No='$rotNo' AND igm_detail_container.cont_number='$contNo') AS tbl";
				}
				else
				{
					$sql_contNumber="SELECT DISTINCT cont_number FROM(SELECT igm_detail_container.cont_number
					FROM igm_details
					INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
					INNER JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
					WHERE verify_info_fcl.verify_number='$verifyNo') AS tbl";
				}
				
				$rslt_contNumber=$this->bm->dataSelectDb1($sql_contNumber);
				
				for($i=0;$i<count($rslt_contNumber);$i++)
				{
					$rsltTmp[]=$rslt_contNumber[$i]['cont_number'];
				}
				
				$containerSet=join(", ",$rsltTmp);
				$data['containerSet']=$containerSet;
				
				// tab 1 - added truck (paid or not paid)
				$sql_vrfyInfoFclId = "SELECT id FROM verify_info_fcl 
				WHERE rotation='$rotNo' AND cont_number='$contNo'";
				$rslt_vrfyInfoFclId = $this->bm->dataSelectDB1($sql_vrfyInfoFclId);
				$vrfyInfoFclId = $rslt_vrfyInfoFclId[0]['id'];
				$data['vrfyInfoFclId']=$vrfyInfoFclId;
				
				$sql_tmpTrkData = "SELECT * FROM(SELECT id,verify_info_fcl_id,truck_id,pkg_qty,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,'no' AS pay,'0' AS paid_status,emrgncy_flag,emrgncy_approve_stat  
				FROM vcms_tmp_truck_dtl 
				WHERE verify_info_fcl_id = '$vrfyInfoFclId'
				UNION ALL
				SELECT id,verify_info_fcl_id,truck_id,delv_pack AS pkg_qty,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,'yes' AS pay,paid_status,emrgncy_flag,emrgncy_approve_stat
				FROM do_truck_details_entry
				WHERE verify_info_fcl_id = '$vrfyInfoFclId') AS tbl ORDER BY id";
				// echo $sql_tmpTrkData; 
				// return;
				$rslt_tmpTrkData = $this->bm->dataSelectDB1($sql_tmpTrkData);
				$data['rslt_tmpTrkData']=$rslt_tmpTrkData;
				
				$emrgncyFlag = 0;
				// $paymentAmt = 0;
				for($i=0;$i<count($rslt_tmpTrkData);$i++)
				{
					if($rslt_tmpTrkData[$i]['emrgncy_flag']==1)
						$emrgncyFlag=1;
					
					// if($rslt_tmpTrkData[$i]['pay']=="no")
						// $paymentAmt = $paymentAmt + 57.5;
				}
				// echo $emrgncyFlag;
				// return;
				$data['emrgncyFlag']=$emrgncyFlag;
				// $data['paymentAmt']=$paymentAmt;
																
				
				// no of truck assigned
				// $sql_noOfTruckAssign = "SELECT no_of_truck FROM verify_info_fcl WHERE id='$vrfyInfoFclId'";
				$sql_noOfTruckAssign = "SELECT no_of_truck,jetty_sirkar_id,agent_code AS js_lic_no,mobile_number AS cell_no
				FROM verify_info_fcl
				LEFT JOIN vcms_vehicle_agent ON vcms_vehicle_agent.id=verify_info_fcl.jetty_sirkar_id
				WHERE verify_info_fcl.id='$vrfyInfoFclId'";
				$rslt_noOfTruckAssign = $this->bm->dataSelectDB1($sql_noOfTruckAssign);
				
				$noOfTruckAssign="";
				$jetty_sirkar_id="";
				$js_lic_no="";
				$cell_no="";
				
				for($jt = 0;$jt<count($rslt_noOfTruckAssign);$jt++)
				{
					$noOfTruckAssign = $rslt_noOfTruckAssign[$jt]['no_of_truck'];					
					$jetty_sirkar_id = $rslt_noOfTruckAssign[$jt]['jetty_sirkar_id'];
					$js_lic_no = $rslt_noOfTruckAssign[$jt]['js_lic_no'];
					$cell_no = $rslt_noOfTruckAssign[$jt]['cell_no'];
				}			
				$data['noOfTruckAssign']=$noOfTruckAssign;
				
				$data['jetty_sirkar_id']=$jetty_sirkar_id;
				$data['js_lic_no']=$js_lic_no;
				$data['cell_no']=$cell_no;
				
				// importer mobile no
				$sql_importerMobile = "SELECT importer_mobile_no FROM verify_info_fcl WHERE id='$vrfyInfoFclId'";
				$rslt_importerMobile = $this->bm->dataSelectDB1($sql_importerMobile);
				$importerMobile = $rslt_importerMobile[0]['importer_mobile_no'];
				$data['importerMobile']=$importerMobile;
				
				// tab 2 - js info
				$sql_jsInfo = "SELECT vcms_vehicle_agent.id,agent_name AS js_name,agent_type
				FROM vcms_vehicle_agent
				INNER JOIN vcms_vehicle_agency ON vcms_vehicle_agency.id=vcms_vehicle_agent.agency_id
				WHERE agency_code = '$org_license' AND (agent_type = 'Jetty Sircar' OR agent_type = 'Asst. Jetty Sircar')";

				$rslt_jsInfo = $this->bm->dataSelectDB1($sql_jsInfo);
				$data['rslt_jsInfo']=$rslt_jsInfo;
				
				// tab 3 - payment info
		
				$paymentAmt = 0;		// check here for payment after pay
				for($i=0;$i<count($rslt_tmpTrkData);$i++)
				{
					if($rslt_tmpTrkData[$i]['emrgncy_flag'] == 0 and $rslt_tmpTrkData[$i]['emrgncy_approve_stat'] == 0)
					{
						if($rslt_tmpTrkData[$i]['paid_status'] == 0)
						{
							$paymentAmt = $paymentAmt + 57.50;
						}
					}
					else if($rslt_tmpTrkData[$i]['emrgncy_flag'] == 1 and $rslt_tmpTrkData[$i]['emrgncy_approve_stat'] == 1)
					{
						if($rslt_tmpTrkData[$i]['paid_status'] == 0)
						{
							$paymentAmt = $paymentAmt + 57.50;
						}
					}
				}
				$data['paymentAmt']=$paymentAmt;		

				// tab 4 - disable save button
				$sql_cntPaid = "SELECT COUNT(*) AS cntPaid FROM(SELECT id,verify_info_fcl_id,truck_id,pkg_qty,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,'no' AS pay 
				FROM vcms_tmp_truck_dtl 
				WHERE verify_info_fcl_id = '$vrfyInfoFclId'
				UNION ALL
				SELECT id,verify_info_fcl_id,truck_id,delv_pack AS pkg_qty,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,'yes' AS pay
				FROM do_truck_details_entry
				WHERE verify_info_fcl_id = '$vrfyInfoFclId') AS tbl WHERE pay='yes' ORDER BY id;";
				$rslt_cntPaid = $this->bm->dataSelectDB1($sql_cntPaid);
				$cntPaid = $rslt_cntPaid[0]['cntPaid'];
				$data['cntPaid']=$cntPaid;
				
				// edit truck
				$sql_trkEditInfo = "";
				
				$btnType = $this->input->post('btnType');
				$trkEditId = $this->input->post('editId');
				if($trkEditId!="")
					$emrgncyFlag = 0;
				
				// $sql_trkEditInfo = "SELECT id,truck_id,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass
				// FROM vcms_tmp_truck_dtl
				// WHERE id='$trkEditId'";
				
				$sql_trkEditInfo = "SELECT id,truck_id,driver_name,driver_gate_pass,
				(SELECT mobile_number FROM vcms_vehicle_agent WHERE agent_name=driver_name LIMIT 1) AS driver_mobile_number,
				assistant_name,assistant_gate_pass,
				(SELECT mobile_number FROM vcms_vehicle_agent WHERE agent_name=driver_name LIMIT 1) AS helper_mobile_number
				FROM vcms_tmp_truck_dtl
				WHERE id='$trkEditId'
				UNION ALL
				SELECT id,truck_id,driver_name,driver_gate_pass,
				(SELECT mobile_number FROM vcms_vehicle_agent WHERE agent_name=driver_name LIMIT 1) AS driver_mobile_number,
				assistant_name,assistant_gate_pass,
				(SELECT mobile_number FROM vcms_vehicle_agent WHERE agent_name=driver_name LIMIT 1) AS helper_mobile_number
				FROM do_truck_details_entry
				WHERE id='$trkEditId'";
					
				
				$rslt_trkEditInfo = $this->bm->dataSelectDB1($sql_trkEditInfo);
				
				$data['rslt_trkEditInfo']=$rslt_trkEditInfo;
				$data['btnType']=$btnType;
				$data['emrgncyFlag']=$emrgncyFlag;
				
            }
			else
			{                              //============================================================= LCL Block
                $fclFlagValue = 0;               

                // $doReportQuery ="SELECT count(verify_no) as total, sum(delv_pack) as total_do_pack, count(truck_id) as total_truck_assign 
		                // from do_information where verify_no='$verifyNo'";

				$doReportQuery ="SELECT count(verify_number) as total, sum(delv_pack) as total_do_pack, count(truck_id) as total_truck_assign 
		                from do_truck_details_entry where verify_number='$verifyNo'";
                $checkDo = $this->bm->dataSelectDb1($doReportQuery);

                $deliverd_truck = $checkDo[0]['total_truck_assign'];               

				$verifyReport = "SELECT shed_bill_master.bill_no, shed_tally_info.verify_number, unit_no,shed_tally_info.import_rotation,
				vessel_name,shed_bill_master.bl_no,igm_supplimentary_detail.Description_of_Goods,Qty, shed_loc, shed_yard,
				if(bill_rcv_stat=1,'Paid','Not Paid') as paid_status, cont_number,
				igm_supplimentary_detail.Pack_Number, igm_supplimentary_detail.Pack_Description,
				(shed_tally_info.rcv_pack+ ifnull(shed_tally_info.loc_first,0)) as rcv_pack,shed_tally_info.rcv_unit, no_of_truck
				FROM shed_tally_info 
				left JOIN shed_bill_master ON shed_tally_info.verify_number=shed_bill_master.verify_no
				left JOIN shed_bill_details ON shed_bill_master.bill_no =shed_bill_details.bill_no
				left JOIN verify_other_data ON shed_tally_info.id=verify_other_data.shed_tally_id
				inner join igm_supplimentary_detail on igm_supplimentary_detail.id=shed_tally_info.igm_sup_detail_id
				left join igm_details on igm_details.id=igm_supplimentary_detail.igm_detail_id
				WHERE shed_tally_info.verify_number='$verifyNo'
				GROUP BY bill_no";
				//query-1: shed_bill_master.unit_no ki?
				$rtnVerifyReport = $this->bm->dataSelectDb1($verifyReport);
				$truck_num=$rtnVerifyReport[0]['no_of_truck'];
				$data['rtnVerifyReport'] = $rtnVerifyReport;

                $rem_truck=$truck_num-$deliverd_truck;
                $data['deliverd_truck'] = $deliverd_truck;
                $data['rem_truck']=$rem_truck;

                if($deliverd_truck>=0)
                {
                    // $doQuery="SELECT verify_no, delv_pack, truck_id, gate_no from do_information where verify_no='$verifyNo' order by id";
                    $doQuery="SELECT verify_number, delv_pack, truck_id, gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass from do_truck_details_entry where verify_number='$verifyNo' order by id";
                    $doInfo = $this->bm->dataSelectDb1($doQuery);
                    $data['doInfo']=$doInfo;
                    $doShowFlag=1;
                    $data['doShowFlag']=$doShowFlag;
                    if($truck_num>$deliverd_truck)
                    {
                        $data['dlv_btn_status']=1;
                    }
                    else
                    {
                        //$dlv_btn_status=1;
                        $data['dlv_btn_status']=0;
                    }
                    $data['tblFlag']=1;
                }
				else
                {
                    $data['dlv_btn_status']=1;
                    $data['doShowFlag']=0;
                    $data['tblFlag']=1;
                }
            }
            $data['fclFlagValue'] = $fclFlagValue;		

		    $data['frmType']="New";
		    $data['verifyNo']=$verifyNo;
		    $data['contNo']=$contNo;
		    
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('billSearchByVerifyForm',$data);
			$this->load->view('jsAssets');
		}
	}
	
	function bilSearchByVerifyNumber($rotNo=null,$contNo=null,$fclFlagValue=null,$cont_status=null)
	{
		$session_id = $this->session->userdata('value');
		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();
		}
		else
		{				
			$org_license = $this->session->userdata('org_license');
			$login_id = $this->session->userdata('login_id');
			
			$data['title']="TRUCK DETAIL ENTRY FORM";
			// $rotNo="";
			// $contNo="";
			// $verifyNo="";
			// $beNo="";
			
		
		// echo "cont_status 1 ".$cont_status;
			if($this->uri->segment(3) != null )
			{
				$verifyNo = $this->uri->segment(3);
			}
			else
			{
				$verifyNo = $this->input->post('verifyNo');
				$cont_status = $this->input->post('cont_status');
				$rotNo = $this->input->post('rotNo');
				$contNo = $this->input->post('contNo');
				$beNo=$this->input->post('beNo');
				$unit_gkey=$this->input->post('unit_gkey');
			}
						
			$data['cont_status']=$cont_status;
			$data['unit_gkey']=$unit_gkey;
			// echo "cont_status 2 ".$cont_status;
			// return;
			if($cont_status=="FCL")			//FCL Block
			{  								
                $fclFlagValue = 1;	
				$this->addVryInfoFCL($rotNo,$contNo,$beNo,$unit_gkey);
				
				// driver helper info
				$sql_driverInfo = "SELECT id,card_number,agent_name
				FROM vcms_vehicle_agent
				WHERE agent_type='Driver'";
				$rslt_driverInfo = $this->bm->dataSelectDB1($sql_driverInfo);
				$data['rslt_driverInfo']=$rslt_driverInfo;
				
				$sql_helperInfo = "SELECT id,card_number,agent_name
				FROM vcms_vehicle_agent
				WHERE agent_type='Helper'";
				$rslt_helperInfo = $this->bm->dataSelectDB1($sql_helperInfo);
				$data['rslt_helperInfo']=$rslt_helperInfo;
				
				// common info
				$verifyReport = "";
				$sql_posYardBlock = "";
				
				if($verifyNo == "")		
				{
					$verifyReport = "SELECT shed_bill_master.bill_no,verify_info_fcl.verify_number,verify_info_fcl.verify_unit AS unit_no,igm_details.Import_Rotation_No AS import_rotation, igm_masters.Vessel_Name AS vessel_name,igm_details.BL_No AS bl_no, igm_details.Description_of_Goods,Qty,bill_rcv_stat,IF(bill_rcv_stat=1,'Paid','Not Paid') AS paid_status,igm_detail_container.cont_number,igm_detail_container.cont_size,igm_details.Pack_Number,igm_details.Pack_Description, verify_info_fcl.no_of_truck 
					FROM igm_detail_container 
					LEFT JOIN igm_details ON igm_details.id=igm_detail_container.igm_detail_id
					LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_cont_id=igm_detail_container.id
					LEFT JOIN shed_bill_master ON shed_bill_master.verify_no = verify_info_fcl.verify_number 
					LEFT JOIN shed_bill_details ON shed_bill_master.bill_no =shed_bill_details.bill_no
					LEFT JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
					WHERE igm_details.Import_Rotation_No='$rotNo' AND igm_detail_container.cont_number='$contNo'";
					
					$sql_posYardBlock = "SELECT slot AS currentPos,Yard_No,Block_No 
					FROM ctmsmis.tmp_vcms_assignment 
					WHERE rot_no='$rotNo' AND cont_no='$contNo'";
				}
				else
				{	
					$verifyReport = "SELECT shed_bill_master.bill_no,verify_info_fcl.verify_number,verify_info_fcl.verify_unit AS unit_no,igm_details.Import_Rotation_No AS import_rotation, igm_masters.Vessel_Name AS vessel_name,igm_details.BL_No AS bl_no, igm_details.Description_of_Goods,Qty,bill_rcv_stat,IF(bill_rcv_stat=1,'Paid','Not Paid') AS paid_status,igm_detail_container.cont_number,igm_detail_container.cont_size,igm_details.Pack_Number,igm_details.Pack_Description, verify_info_fcl.no_of_truck 
					FROM igm_details 
					INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id 
					INNER JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id 
					LEFT JOIN shed_bill_master ON shed_bill_master.verify_no = verify_info_fcl.verify_number 
					LEFT JOIN shed_bill_details ON shed_bill_master.bill_no =shed_bill_details.bill_no 
					INNER JOIN igm_masters ON igm_masters.id=igm_details.IGM_id 
					WHERE verify_info_fcl.verify_number='$verifyNo'";
				}
				// echo $verifyReport; return;
				$rtnVerifyReport = $this->bm->dataSelectDb1($verifyReport);
				$data['rtnVerifyReport'] = $rtnVerifyReport;
				
				if($rtnVerifyReport[0]['cont_size']==20)
					$totTruck = 2;
				else if($rtnVerifyReport[0]['cont_size']==40)
					$totTruck = 3;
				$data['totTruck'] = $totTruck;
				
				$rslt_posYardBlock = $this->bm->dataSelect($sql_posYardBlock);
				$data['rslt_posYardBlock'] = $rslt_posYardBlock;
				
				$sql_contNumber="";
				
				if($verifyNo == "")
				{
					$sql_contNumber="SELECT DISTINCT cont_number FROM(
					SELECT igm_detail_container.cont_number 
					FROM igm_detail_container 
					INNER JOIN igm_details ON igm_details.id=igm_detail_container.igm_detail_id 
					LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_cont_id=igm_detail_container.id 
					WHERE igm_details.Import_Rotation_No='$rotNo' AND igm_detail_container.cont_number='$contNo') AS tbl";
				}
				else
				{
					$sql_contNumber="SELECT DISTINCT cont_number FROM(SELECT igm_detail_container.cont_number
					FROM igm_details
					INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
					INNER JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
					WHERE verify_info_fcl.verify_number='$verifyNo') AS tbl";
				}
				
				$rslt_contNumber=$this->bm->dataSelectDb1($sql_contNumber);
				
				for($i=0;$i<count($rslt_contNumber);$i++)
				{
					$rsltTmp[]=$rslt_contNumber[$i]['cont_number'];
				}
				
				$containerSet=join(", ",$rsltTmp);
				$data['containerSet']=$containerSet;
				
				// tab 1 - added truck (paid or not paid)
				$sql_vrfyInfoFclId = "SELECT id FROM verify_info_fcl 
				WHERE rotation='$rotNo' AND cont_number='$contNo'";
				$rslt_vrfyInfoFclId = $this->bm->dataSelectDB1($sql_vrfyInfoFclId);
				$vrfyInfoFclId = $rslt_vrfyInfoFclId[0]['id'];
				$data['vrfyInfoFclId']=$vrfyInfoFclId;
				
				$sql_tmpTrkData = "SELECT * FROM(SELECT id,verify_info_fcl_id,truck_id,pkg_qty,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,'no' AS pay,'0' AS paid_status,emrgncy_flag,emrgncy_approve_stat  
				FROM vcms_tmp_truck_dtl 
				WHERE verify_info_fcl_id = '$vrfyInfoFclId'
				UNION ALL
				SELECT id,verify_info_fcl_id,truck_id,delv_pack AS pkg_qty,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,'yes' AS pay,paid_status,emrgncy_flag,emrgncy_approve_stat
				FROM do_truck_details_entry
				WHERE verify_info_fcl_id = '$vrfyInfoFclId') AS tbl ORDER BY id";
				// echo $sql_tmpTrkData; return;
				$rslt_tmpTrkData = $this->bm->dataSelectDB1($sql_tmpTrkData);
				$data['rslt_tmpTrkData']=$rslt_tmpTrkData;
				
				$emrgncyFlag = 0;
				// $paymentAmt = 0;
				
				for($i=0;$i<count($rslt_tmpTrkData);$i++)
				{
					if($rslt_tmpTrkData[$i]['emrgncy_flag']==1)
						$emrgncyFlag=1;										
				}
				// echo $emrgncyFlag;
				// return;
				$data['emrgncyFlag']=$emrgncyFlag;
				// $data['paymentAmt']=$paymentAmt;
				
				// no of truck assigned
				// $sql_noOfTruckAssign = "SELECT no_of_truck FROM verify_info_fcl WHERE id='$vrfyInfoFclId'";
				$sql_noOfTruckAssign = "SELECT no_of_truck,jetty_sirkar_id,agent_code AS js_lic_no,mobile_number AS cell_no
				FROM verify_info_fcl
				LEFT JOIN vcms_vehicle_agent ON vcms_vehicle_agent.id=verify_info_fcl.jetty_sirkar_id
				WHERE verify_info_fcl.id='$vrfyInfoFclId'";
				$rslt_noOfTruckAssign = $this->bm->dataSelectDB1($sql_noOfTruckAssign);
				
				$noOfTruckAssign="";
				$jetty_sirkar_id="";
				$js_lic_no="";
				$cell_no="";
				
				for($jt = 0;$jt<count($rslt_noOfTruckAssign);$jt++)
				{
					$noOfTruckAssign = $rslt_noOfTruckAssign[$jt]['no_of_truck'];					
					$jetty_sirkar_id = $rslt_noOfTruckAssign[$jt]['jetty_sirkar_id'];
					$js_lic_no = $rslt_noOfTruckAssign[$jt]['js_lic_no'];
					$cell_no = $rslt_noOfTruckAssign[$jt]['cell_no'];
				}			
				$data['noOfTruckAssign']=$noOfTruckAssign;
				
				$data['jetty_sirkar_id']=$jetty_sirkar_id;
				$data['js_lic_no']=$js_lic_no;
				$data['cell_no']=$cell_no;
				
				// importer mobile no
				$sql_importerMobile = "SELECT importer_mobile_no FROM verify_info_fcl WHERE id='$vrfyInfoFclId'";
				$rslt_importerMobile = $this->bm->dataSelectDB1($sql_importerMobile);
				$importerMobile = $rslt_importerMobile[0]['importer_mobile_no'];
				$data['importerMobile']=$importerMobile;
				
				// tab 2 - js info				
				
				$sql_jsInfo = "SELECT vcms_vehicle_agent.id,agent_name AS js_name,agent_type
				FROM vcms_vehicle_agent
				INNER JOIN vcms_vehicle_agency ON vcms_vehicle_agency.id=vcms_vehicle_agent.agency_id
				WHERE agency_code = '$org_license' AND (agent_type = 'Jetty Sircar' OR agent_type = 'Asst. Jetty Sircar')";

				$rslt_jsInfo = $this->bm->dataSelectDB1($sql_jsInfo);
				$data['rslt_jsInfo']=$rslt_jsInfo;
				
				// tab 3 - payment info
				$paymentAmt = 0;
				for($i=0;$i<count($rslt_tmpTrkData);$i++)
				{					
					// if($rslt_tmpTrkData[$i]['paid_status']==0)
						// $paymentAmt = $paymentAmt + 57.50;
					
					if($rslt_tmpTrkData[$i]['emrgncy_flag'] == 0 and $rslt_tmpTrkData[$i]['emrgncy_approve_stat'] == 0)
					{
						if($rslt_tmpTrkData[$i]['paid_status'] == 0)
						{
							$paymentAmt = $paymentAmt + 57.50;
						}
					}
					else if($rslt_tmpTrkData[$i]['emrgncy_flag'] == 1 and $rslt_tmpTrkData[$i]['emrgncy_approve_stat'] == 1)
					{
						if($rslt_tmpTrkData[$i]['paid_status'] == 0)
						{
							$paymentAmt = $paymentAmt + 57.50;
						}
					}
				}
				$data['paymentAmt']=$paymentAmt;	

				// tab 4 - disable save button
				$sql_cntPaid = "SELECT COUNT(*) AS cntPaid FROM(SELECT id,verify_info_fcl_id,truck_id,pkg_qty,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,'no' AS pay 
				FROM vcms_tmp_truck_dtl 
				WHERE verify_info_fcl_id = '$vrfyInfoFclId'
				UNION ALL
				SELECT id,verify_info_fcl_id,truck_id,delv_pack AS pkg_qty,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,'yes' AS pay
				FROM do_truck_details_entry
				WHERE verify_info_fcl_id = '$vrfyInfoFclId') AS tbl WHERE pay='yes' ORDER BY id;";
				$rslt_cntPaid = $this->bm->dataSelectDB1($sql_cntPaid);
				$cntPaid = $rslt_cntPaid[0]['cntPaid'];
				$data['cntPaid']=$cntPaid;
				
				// edit truck
				$sql_trkEditInfo = "";
				
				$trkEditId = $this->input->post('editId');
				if($trkEditId!="")
					$emrgncyFlag = 0;								
				
				// $sql_trkEditInfo = "SELECT id,truck_id,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass
				// FROM do_truck_details_entry
				// WHERE id='$trkEditId'";
				
				if($trkEditId=="")
					$frmType = "New";
				else
				{
					$btnType = $this->input->post('btnType');
					$frmType = $btnType;
				}
				
				$sql_trkEditInfo = "SELECT id,truck_id,driver_name,driver_gate_pass,
				(SELECT mobile_number FROM vcms_vehicle_agent WHERE agent_name=driver_name LIMIT 1) AS driver_mobile_number,
				assistant_name,assistant_gate_pass,
				(SELECT mobile_number FROM vcms_vehicle_agent WHERE agent_name=driver_name LIMIT 1) AS helper_mobile_number
				FROM vcms_tmp_truck_dtl
				WHERE id='$trkEditId'
				UNION ALL
				SELECT id,truck_id,driver_name,driver_gate_pass,
				(SELECT mobile_number FROM vcms_vehicle_agent WHERE agent_name=driver_name LIMIT 1) AS driver_mobile_number,
				assistant_name,assistant_gate_pass,
				(SELECT mobile_number FROM vcms_vehicle_agent WHERE agent_name=driver_name LIMIT 1) AS helper_mobile_number
				FROM do_truck_details_entry
				WHERE id='$trkEditId'";
				// echo $sql_trkEditInfo;return;	
				
				$rslt_trkEditInfo = $this->bm->dataSelectDB1($sql_trkEditInfo);
				
				$data['rslt_trkEditInfo']=$rslt_trkEditInfo;
				$data['emrgncyFlag']=$emrgncyFlag;
				
            }
			else
			{                              //============================================================= LCL Block
                $fclFlagValue = 0;               

                // $doReportQuery ="SELECT count(verify_no) as total, sum(delv_pack) as total_do_pack, count(truck_id) as total_truck_assign 
		                // from do_information where verify_no='$verifyNo'";

				$doReportQuery ="SELECT count(verify_number) as total, sum(delv_pack) as total_do_pack, count(truck_id) as total_truck_assign 
		                from do_truck_details_entry where verify_number='$verifyNo'";
                $checkDo = $this->bm->dataSelectDb1($doReportQuery);

                $deliverd_truck = $checkDo[0]['total_truck_assign'];               

				$verifyReport = "SELECT shed_bill_master.bill_no, shed_tally_info.verify_number, unit_no,shed_tally_info.import_rotation,
				vessel_name,shed_bill_master.bl_no,igm_supplimentary_detail.Description_of_Goods,Qty, shed_loc, shed_yard,
				if(bill_rcv_stat=1,'Paid','Not Paid') as paid_status, cont_number,
				igm_supplimentary_detail.Pack_Number, igm_supplimentary_detail.Pack_Description,
				(shed_tally_info.rcv_pack+ ifnull(shed_tally_info.loc_first,0)) as rcv_pack,shed_tally_info.rcv_unit, no_of_truck
				FROM shed_tally_info 
				left JOIN shed_bill_master ON shed_tally_info.verify_number=shed_bill_master.verify_no
				left JOIN shed_bill_details ON shed_bill_master.bill_no =shed_bill_details.bill_no
				left JOIN verify_other_data ON shed_tally_info.id=verify_other_data.shed_tally_id
				inner join igm_supplimentary_detail on igm_supplimentary_detail.id=shed_tally_info.igm_sup_detail_id
				left join igm_details on igm_details.id=igm_supplimentary_detail.igm_detail_id
				WHERE shed_tally_info.verify_number='$verifyNo'
				GROUP BY bill_no";
				//query-1: shed_bill_master.unit_no ki?
				$rtnVerifyReport = $this->bm->dataSelectDb1($verifyReport);
				$truck_num=$rtnVerifyReport[0]['no_of_truck'];
				$data['rtnVerifyReport'] = $rtnVerifyReport;

                $rem_truck=$truck_num-$deliverd_truck;
                $data['deliverd_truck'] = $deliverd_truck;
                $data['rem_truck']=$rem_truck;

                if($deliverd_truck>=0)
                {
                    // $doQuery="SELECT verify_no, delv_pack, truck_id, gate_no from do_information where verify_no='$verifyNo' order by id";
                    $doQuery="SELECT verify_number, delv_pack, truck_id, gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass from do_truck_details_entry where verify_number='$verifyNo' order by id";
                    $doInfo = $this->bm->dataSelectDb1($doQuery);
                    $data['doInfo']=$doInfo;
                    $doShowFlag=1;
                    $data['doShowFlag']=$doShowFlag;
                    if($truck_num>$deliverd_truck)
                    {
                        $data['dlv_btn_status']=1;
                    }
                    else
                    {
                        //$dlv_btn_status=1;
                        $data['dlv_btn_status']=0;
                    }
                    $data['tblFlag']=1;
                }
				else
                {
                    $data['dlv_btn_status']=1;
                    $data['doShowFlag']=0;
                    $data['tblFlag']=1;
                }
            }
            $data['fclFlagValue'] = $fclFlagValue;		
			
			$data['frmType']=$frmType;
			
		    $data['verifyNo']=$verifyNo;
		    $data['contNo']=$contNo;
		    $data['actSt']=0;
		    
			// $this->load->view('cssAssetsWizard');
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('billSearchByVerifyForm',$data);
			// $this->load->view('jsAssetsWizard');
			$this->load->view('jsAssets');
		}
	}
	
	function truckPayment()
	{
		$vrfyInfoFclId = $this->input->post('vrfyInfoFclId');
		$rotNo = $this->input->post('rotNo');
		$contNo = $this->input->post('contNo');
		$fclFlagValue = $this->input->post('fclFlagValue');
		$cont_status = $this->input->post('cont_status');
		$paymentMethod = $this->input->post('paymentMethod');
		
		$sql_tmpTrkData = "SELECT id
		FROM do_truck_details_entry 
		WHERE verify_info_fcl_id = '$vrfyInfoFclId' ORDER BY id";
		$rslt_tmpTrkData = $this->bm->dataSelectDB1($sql_tmpTrkData);		
		// $data['rslt_tmpTrkData']=$rslt_tmpTrkData;		

		$cnt = 0;
		for($i=0;$i<count($rslt_tmpTrkData);$i++)
		{
			$cnt++;

			$id = $rslt_tmpTrkData[$i]['id'];
											
			$payAmt = 57.5;

			// $sql_updatePayment = "UPDATE do_truck_details_entry
			// SET paid_amt='$payAmt',paid_status='1',paid_method='$paymentMethod'
			// WHERE id='$id'";
			
			$sql_updatePayment = "UPDATE do_truck_details_entry
			SET paid_amt='$payAmt',paid_method='$paymentMethod'
			WHERE id='$id'";
			$this->bm->dataUpdateDB1($sql_updatePayment);
			
			
			// $sql_deleteTmpData = "DELETE FROM vcms_tmp_truck_dtl WHERE id='$id'";
			// $this->bm->dataDeleteDB1($sql_deleteTmpData);	
		}
		$this->bilSearchByVerifyNumber_2($rotNo,$contNo,$fclFlagValue,$cont_status);
	}
	//========================================================== deliver Start

	function addVryInfoFCL($rotNo,$contNo,$unit_gkey,$assignmentType)
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
			$org_license = $this->session->userdata('org_license');
			$sql_chkExist = "SELECT COUNT(*) AS rtnValue 
			FROM verify_info_fcl 
			WHERE rotation='$rotNo' AND cont_number='$contNo'";
			$rslt_chkExist = $this->bm->dataSelectDb1($sql_chkExist);
			$cnt = $rslt_chkExist[0]['rtnValue'];
			
			$sql_igmDtlContId = "SELECT igm_details.id AS igm_dtl_id,igm_detail_container.id AS igm_dtl_cont_id,cont_size
			FROM igm_details
			INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
			WHERE Import_Rotation_No='$rotNo' AND cont_number='$contNo'";
			$rslt_igmDtlContId = $this->bm->dataSelectDb1($sql_igmDtlContId);
			$igmDtlId = $rslt_igmDtlContId[0]['igm_dtl_id'];				
			$igmDtlContId = $rslt_igmDtlContId[0]['igm_dtl_cont_id'];
			$cont_size = $rslt_igmDtlContId[0]['cont_size'];
		
			if($cont_size == 20)
				$truck_qty = 2;
			// else if($cont_size == 40)
			else
				$truck_qty = 3;
			
			$sql_smsNo = "SELECT cf_sms_number 
			FROM ctmsmis.tmp_vcms_assignment
			WHERE cf_lic='$org_license' AND cf_sms_number IS NOT NULL";
			$rslt_smsNo = $this->bm->dataSelect($sql_smsNo);
			$smsNo = @$rslt_smsNo[0]['cf_sms_number'];	
			
			if($cnt==0)
			{
				// $sql_smsNo = "SELECT sms_number AS cf_sms_number
				// FROM ref_bizunit_scoped
				// WHERE id='$org_license'";

				// $sql_smsNo = "SELECT cf_sms_number 
				// FROM ctmsmis.tmp_vcms_assignment
				// WHERE cf_lic='$org_license' AND cf_sms_number IS NOT NULL";
				// $rslt_smsNo = $this->bm->dataSelect($sql_smsNo);
				// $smsNo = $rslt_smsNo[0]['cf_sms_number'];			

				$sql_insertQtyTruck = "INSERT INTO verify_info_fcl(igm_detail_id,igm_detail_cont_id,assignment_type,cnf_lic_no,cnf_mobile_no,unit_gkey,rotation,cont_number,no_of_truck,truck_no_by,truck_no_time)
				VALUES('$igmDtlId','$igmDtlContId','$assignmentType','$org_license','$smsNo','$unit_gkey','$rotNo','$contNo','$truck_qty','$login_id',NOW())";
				
				if($this->bm->dataInsertDB1($sql_insertQtyTruck))
					$msg = "<font color='green'><b>Truck number entered succesfully.</b></font>";
			}
			else
			{
				// update	cnf_mobile_no,unit_gkey,no_of_truck,truck_no_by,truck_no_time
				$sql_updateQtyTruck = "UPDATE verify_info_fcl
				SET cnf_mobile_no='$smsNo',unit_gkey='$unit_gkey',no_of_truck='$truck_qty',truck_no_by='$login_id',truck_no_time=NOW()
				WHERE rotation='$rotNo' AND cont_number='$contNo'";
				
				if($this->bm->dataUpdateDB1($sql_updateQtyTruck))
					$msg = "<font color='green'><b>Truck number updated succesfully.</b></font>";
			}
		}
	}
	
	function deliver_2()
	{
		$session_id = $this->session->userdata('value');
		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();
		}
		else
		{
			$msg="";
			$stat="";
			$search = 1;
			$data['search']=$search;
			$data['title']="BILL SEARCH BY VERIFY NUMBER...";
			
			$data['actSt']=1;
			$jsName = $this->input->post('jsName');
			// $paymentMethod = $this->input->post('paymentMethod');
			// $paymentAmt = $this->input->post('paymentAmt');
			
			
			
			$org_license = $this->session->userdata('org_license');
						
			$truckNum=$this->input->post('numTruc');
			
			$vrfyInfoFclId = $this->input->post('vrfyInfoFclId');
			$contNo=$this->input->post('contNo');
			$rotNo=$this->input->post('rotNo');
			$fclFlagValue = $this->input->post('fclFlagValue');
			$unit_gkey = $this->input->post('unit_gkey');
			
			$verifyNo=$this->input->post('verifyNo');
			
			$ipaddr = $_SERVER['REMOTE_ADDR'];
			$login_id = $this->session->userdata('login_id');				
			$data['vrfyInfoFclId']=$vrfyInfoFclId;
			$cont_status="";
			if($fclFlagValue == 1)		// FCL Code Block
			{   
				$cont_status = "FCL";
				
				$sql_jsInfo = "SELECT id,js_name,js_lic_no,cell_no,adress
				FROM vcms_jetty_sirkar
				WHERE cnf_lic_no='$org_license'";

				$rslt_jsInfo = $this->bm->dataSelectDB1($sql_jsInfo);
				$data['rslt_jsInfo']=$rslt_jsInfo;
				
				if($verifyNo == "")
				{					
					$doReportQuery = "SELECT (SELECT COUNT(*) FROM do_truck_details_entry WHERE import_rotation='$rotNo' AND cont_no='$contNo') AS total, SUM(delv_pack) AS total_do_pack, (SELECT COUNT(*) FROM do_truck_details_entry WHERE import_rotation='$rotNo' AND cont_no='$contNo') AS total_truck_assign 
					FROM do_truck_details_entry 
					INNER JOIN igm_details ON igm_details.Import_Rotation_No=do_truck_details_entry.import_rotation
					INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
					WHERE do_truck_details_entry.import_rotation='$rotNo' AND cont_no='$contNo'";
				}
				else
				{
					$doReportQuery="SELECT count(verify_number) as total, sum(delv_pack) as total_do_pack, count(truck_id) as total_truck_assign 
					from do_truck_details_entry where verify_number='$verifyNo'";
				}
				
				$checkDo = $this->bm->dataSelectDb1($doReportQuery);
				
				$deliverd_truck = $checkDo[0]['total_truck_assign'];
				$delivered_total_truck = $checkDo[0]['total'];
				
				// 2020-04-30 - intakhab - start
				$sql_contNumber = "";
				
				if($verifyNo == "")
				{
					$sql_contNumber="SELECT DISTINCT cont_number FROM(
					SELECT igm_detail_container.cont_number 
					FROM igm_detail_container 
					INNER JOIN igm_details ON igm_details.id=igm_detail_container.igm_detail_id 
					LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_cont_id=igm_detail_container.id 
					WHERE igm_details.Import_Rotation_No='$rotNo' AND igm_detail_container.cont_number='$contNo') AS tbl";
				}
				else				
				{
					$sql_contNumber="SELECT DISTINCT cont_number FROM(SELECT igm_detail_container.cont_number
					FROM igm_details
					INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
					INNER JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
					WHERE verify_info_fcl.verify_number='$verifyNo') AS tbl";
				}
				
				$rslt_contNumber=$this->bm->dataSelectDb1($sql_contNumber);
				
				for($i=0;$i<count($rslt_contNumber);$i++)
				{
					$rsltTmp[]=$rslt_contNumber[$i]['cont_number'];
				}
				
				$containerSet=join(", ",$rsltTmp);
				$data['containerSet']=$containerSet;
				
				// 2020-04-30 - intakhab - end

				$verifyReport = "";
				
				if($verifyNo == "")
				{
					$verifyReport = "SELECT shed_bill_master.bill_no,verify_info_fcl.verify_number,verify_info_fcl.verify_unit AS unit_no,igm_details.Import_Rotation_No AS import_rotation, igm_masters.Vessel_Name AS vessel_name,igm_details.BL_No AS bl_no, igm_details.Description_of_Goods,Qty,bill_rcv_stat,IF(bill_rcv_stat=1,'Paid','Not Paid') AS paid_status,igm_detail_container.cont_number,igm_detail_container.cont_size,igm_details.Pack_Number,igm_details.Pack_Description, verify_info_fcl.no_of_truck 
					FROM igm_detail_container 
					LEFT JOIN igm_details ON igm_details.id=igm_detail_container.igm_detail_id
					LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_cont_id=igm_detail_container.id
					LEFT JOIN shed_bill_master ON shed_bill_master.verify_no = verify_info_fcl.verify_number 
					LEFT JOIN shed_bill_details ON shed_bill_master.bill_no =shed_bill_details.bill_no
					LEFT JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
					WHERE igm_details.Import_Rotation_No='$rotNo' AND igm_detail_container.cont_number='$contNo'";
				}
				else
				{
					$verifyReport = "SELECT shed_bill_master.bill_no,verify_info_fcl.verify_number,verify_info_fcl.verify_unit AS unit_no,igm_details.Import_Rotation_No AS import_rotation, igm_masters.Vessel_Name AS vessel_name,igm_details.BL_No AS bl_no, igm_details.Description_of_Goods,Qty,bill_rcv_stat,IF(bill_rcv_stat=1,'Paid','Not Paid') AS paid_status,igm_detail_container.cont_number,igm_detail_container.cont_size,igm_details.Pack_Number,igm_details.Pack_Description, verify_info_fcl.no_of_truck 
					FROM igm_details 
					INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
					INNER JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id 
					LEFT JOIN shed_bill_master ON shed_bill_master.verify_no = verify_info_fcl.verify_number 
					LEFT JOIN shed_bill_details ON shed_bill_master.bill_no =shed_bill_details.bill_no 
					INNER JOIN igm_masters ON igm_masters.id=igm_details.IGM_id 
					WHERE verify_info_fcl.verify_number='$verifyNo'";
				}
				
				//query-1: shed_bill_master.unit_no ki?
				$rtnVerifyReport = $this->bm->dataSelectDb1($verifyReport);
				$truck_num=$rtnVerifyReport[0]['no_of_truck'];
				$data['rtnVerifyReport'] = $rtnVerifyReport;
				
				if($rtnVerifyReport[0]['cont_size']==20)
					$totTruck = 2;
				else if($rtnVerifyReport[0]['cont_size']==40)
					$totTruck = 3;
				$data['totTruck'] = $totTruck;
				

				// if($delivered_total_truck>=0 and $delivered_total_truck<$truck_num)
				// {					
					$insert_OK=1;
					$data['msgFlag']=0;
					
					//--2021-01-19				
					$sql_updateJS = "UPDATE verify_info_fcl
					SET jetty_sirkar_id='$jsName'
					WHERE id='$vrfyInfoFclId'";
					$this->bm->dataUpdateDB1($sql_updateJS);
					
					// $sql_tmpTrkData = "SELECT vcms_tmp_truck_dtl.id,verify_info_fcl_id,truck_id,pkg_qty,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,emrgncy_flag,emrgncy_approve_stat,rotation,cont_number
					// FROM vcms_tmp_truck_dtl
					// INNER JOIN verify_info_fcl ON verify_info_fcl.id=vcms_tmp_truck_dtl.verify_info_fcl_id					
					// WHERE verify_info_fcl_id = '$vrfyInfoFclId' ORDER BY vcms_tmp_truck_dtl.id";

					$sql_tmpTrkData = "SELECT vcms_tmp_truck_dtl.id,verify_info_fcl_id,truck_id,pkg_qty,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,emrgncy_flag,emrgncy_approve_stat,rotation,cont_number
					FROM vcms_tmp_truck_dtl
					INNER JOIN verify_info_fcl ON verify_info_fcl.id=vcms_tmp_truck_dtl.verify_info_fcl_id					
					WHERE verify_info_fcl_id = '$vrfyInfoFclId' AND (emrgncy_flag='0' OR (emrgncy_flag='1' AND emrgncy_approve_stat='1')) ORDER BY vcms_tmp_truck_dtl.id";
					$rslt_tmpTrkData = $this->bm->dataSelectDB1($sql_tmpTrkData);
					$data['rslt_tmpTrkData']=$rslt_tmpTrkData;
					
					// no of truck assigned
					$sql_noOfTruckAssign = "SELECT no_of_truck FROM verify_info_fcl WHERE id='$vrfyInfoFclId'";
					$rslt_noOfTruckAssign = $this->bm->dataSelectDB1($sql_noOfTruckAssign);
					$noOfTruckAssign = $rslt_noOfTruckAssign[0]['no_of_truck'];
					$data['noOfTruckAssign']=$noOfTruckAssign;
					
					$cnt = 0;
					for($i=0;$i<count($rslt_tmpTrkData);$i++)
					{
						$cnt++;
						
						$id = $rslt_tmpTrkData[$i]['id'];
						$trucId = $rslt_tmpTrkData[$i]['truck_id'];
						$packQty = $rslt_tmpTrkData[$i]['pkg_qty'];
						$gateNo = $rslt_tmpTrkData[$i]['gate_no'];
						$driverName = $rslt_tmpTrkData[$i]['driver_name'];
						$driverPass = $rslt_tmpTrkData[$i]['driver_gate_pass'];									
						$assistantName = $rslt_tmpTrkData[$i]['assistant_name'];									
						$assistantPass = $rslt_tmpTrkData[$i]['assistant_gate_pass'];									
						$emrgncy_flag = $rslt_tmpTrkData[$i]['emrgncy_flag'];									
						$emrgncy_approve_stat = $rslt_tmpTrkData[$i]['emrgncy_approve_stat'];									
						$rotation = $rslt_tmpTrkData[$i]['rotation'];									
						$cont_number = $rslt_tmpTrkData[$i]['cont_number'];		

						$sql_timeSlot = "SELECT assignment_slot,assignmentDate,DATE_ADD(assignmentDate, INTERVAL 1 DAY) AS nxtDt
						FROM ctmsmis.tmp_vcms_assignment
						WHERE rot_no='$rotation' AND cont_no='$cont_number' AND assignmentDate>=DATE(NOW())";
						$rslt_timeSlot = $this->bm->dataSelect($sql_timeSlot);
						
						$asDt = "";
						$asSlot = "";
						$nxtDt = "";
						
						for($j=0;$j<count($rslt_timeSlot);$j++)
						{
							$asDt = $rslt_timeSlot[$j]['assignmentDate'];
							$asSlot = $rslt_timeSlot[$j]['assignment_slot'];
							$nxtDt = $rslt_timeSlot[$j]['nxtDt'];
						}
						$sSlot = "";
						$eSlot = "";
						if($asSlot==1)
						{
							$sSlot = $asDt." 08:00:00";
							$eSlot = $asDt." 15:59:59";
						}
						else if($asSlot==2)
						{
							$sSlot = $asDt." 16:00:00";
							$eSlot = $asDt." 23:59:59";
						}
						else
						{
							$sSlot = $nxtDt." 00:00:00";
							$eSlot = $nxtDt." 07:59:59";
						}
						$payAmt = 57.5;						
						
						$strInsertEq = "INSERT INTO do_truck_details_entry(verify_info_fcl_id,verify_number,import_rotation,cont_no,truck_id,delv_pack,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,update_by,ip_addr,last_update,emrgncy_flag,emrgncy_approve_stat,visit_time_slot_start,visit_time_slot_end)
						VALUES('$vrfyInfoFclId','$verifyNo','$rotNo','$contNo','$trucId','$packQty','$gateNo','$driverName','$driverPass','$assistantName','$assistantPass','$login_id','$ipaddr',NOW(),'$emrgncy_flag','$emrgncy_approve_stat','$sSlot','$eSlot')";								
						$stat = $this->bm->dataInsertDB1($strInsertEq);
						
						$sql_deleteTmpData = "DELETE FROM vcms_tmp_truck_dtl WHERE id='$id'";
						$this->bm->dataDeleteDB1($sql_deleteTmpData);	
					}
					
					$this->bilSearchByVerifyNumber_2($rotNo,$contNo,$fclFlagValue,$cont_status);
					return;
			}
			else
			{                    //=============================== LCL Code Block
				$cont_status = "LCL";
				// $doReportQuery="SELECT count(verify_no) as total, sum(delv_pack) as total_do_pack, count(truck_id) as total_truck_assign 
								// from do_information where verify_no='$verifyNo'";

				$doReportQuery="SELECT count(verify_no) as total, sum(delv_pack) as total_do_pack, count(truck_id) as total_truck_assign 
								from do_information where verify_no='$verifyNo'";
				$checkDo = $this->bm->dataSelectDb1($doReportQuery);
				$delivered_pack=$checkDo[0]['total_do_pack'];
				$deliverd_truck = $checkDo[0]['total_truck_assign'];
				$delivered_total_truck = $checkDo[0]['total'];

				$verifyReport = "SELECT shed_bill_master.bill_no, shed_tally_info.verify_number, unit_no,shed_tally_info.import_rotation,
				vessel_name,shed_bill_master.bl_no,igm_supplimentary_detail.Description_of_Goods,Qty, shed_loc, shed_yard,
				if(bill_rcv_stat=1,'Paid','Not Paid') as paid_status, cont_number,
				igm_supplimentary_detail.Pack_Number, igm_supplimentary_detail.Pack_Description,
				(shed_tally_info.rcv_pack+ ifnull(shed_tally_info.loc_first,0)) as rcv_pack,shed_tally_info.rcv_unit, no_of_truck
				FROM shed_tally_info 
				left JOIN shed_bill_master ON shed_tally_info.verify_number=shed_bill_master.verify_no
				left JOIN shed_bill_details ON shed_bill_master.bill_no =shed_bill_details.bill_no
				left JOIN verify_other_data ON shed_tally_info.id=verify_other_data.shed_tally_id
				inner join igm_supplimentary_detail on igm_supplimentary_detail.id=shed_tally_info.igm_sup_detail_id
				left join igm_details on igm_details.id=igm_supplimentary_detail.igm_detail_id
				WHERE shed_tally_info.verify_number='$verifyNo' GROUP BY bill_no";

				$rtnVerifyReport = $this->bm->dataSelectDb1($verifyReport);
				$truck_num = $rtnVerifyReport[0]['no_of_truck'];
				$data['rtnVerifyReport'] = $rtnVerifyReport;

				if($delivered_total_truck>=0 and $delivered_total_truck<$truck_num)
				{
					$verifyReport = "SELECT distinct(rcv_pack +ifnull(shed_tally_info.loc_first,0)) as rcv_pack, shed_tally_info.rcv_unit,
					igm_supplimentary_detail.Pack_Number, igm_supplimentary_detail.Pack_Description
					FROM shed_bill_details INNER JOIN shed_bill_master 
					ON shed_bill_master.bill_no=shed_bill_details.bill_no
					INNER JOIN shed_tally_info ON shed_bill_details.verify_no=shed_tally_info.verify_number
					inner join igm_supplimentary_detail on igm_supplimentary_detail.id=shed_tally_info.igm_sup_detail_id
					left join igm_details on igm_details.id=igm_supplimentary_detail.igm_detail_id
					WHERE shed_bill_master.verify_no='$verifyNo'";
					$rtnVerifyReport = $this->bm->dataSelectDb1($verifyReport);

					$realRecivedPackNumber=$rtnVerifyReport[0]['rcv_pack'];
					$realRecivedPackUnit=$rtnVerifyReport[0]['rcv_unit'];
					$igmPackNumber=$rtnVerifyReport[0]['Pack_Number'];
					$igmPackUnit=$rtnVerifyReport[0]['Pack_Description'];

					$pack=0;
					for($i=$deliverd_truck+1; $i<$deliverd_truck+2;$i++)
					{
						$trucId = $this->input->post('truck'.$i);
						$packQty = $this->input->post('pkQty'.$i);
						$gateNo = $this->input->post('gateNo'.$i);
						
						$pack=$delivered_pack+$packQty;
					}
					
					if($realRecivedPackUnit==$igmPackUnit)
					{
						if($realRecivedPackNumber>=$pack)
						{
							$insert_OK=1;
							$data['msgFlag']=0;
						}

						if($realRecivedPackNumber<$pack)
						{
							$data['msgFlag']=2;
							
							$data['msg']= "<font color=red>Sorry! You are delivering more packages than received packages.</br> Please correct package quantity and Try again!!.</font>";
						}

						if($insert_OK==1)
						{							
							for($i=($deliverd_truck+1); $i<($deliverd_truck+2);$i++)
							{							
								$trucId=$this->input->post('truck'.$i);
								$packQty= $this->input->post('pkQty'.$i);
								$gateNo= $this->input->post('gateNo'.$i);
								
								// $driverName= $this->input->post('driverName'.$i);
								// $driverPass= $this->input->post('driverPass'.$i);
								// $assistantName= $this->input->post('assistantName'.$i);
								// $assistantPass= $this->input->post('assistantPass'.$i);		
							
								if($trucId=="")
								{
									$trucId=0;
									$packQty=0;
								}
								else
								{
									$strInsertEq = "insert into do_information(verify_no, import_rotation, cont_number, truck_id, delv_pack, update_by, ip_addr, last_update, gate_no)
									values('$verifyNo','$rotNo', '$contNo', '$trucId', $packQty, '$login_id', '$ipaddr',  now(), '$gateNo')";
									
									// $strInsertEq = "INSERT INTO do_truck_details_entry(verify_number,import_rotation,cont_no,truck_id,delv_pack,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,update_by,ip_addr,last_update)
									// VALUES('$verifyNo','$rotNo','$contNo','$trucId',$packQty,'$gateNo','$driverName','$driverPass','$assistantName','$assistantPass','$login_id','$ipaddr',now())";
									$stat = $this->bm->dataInsertDB1($strInsertEq);									
								}
							}

							if($stat==1)
							{
								$sql="UPDATE shed_tally_info SET delivery_status = '1' WHERE verify_number = '$verifyNo'";
								$update = $this->bm->dataInsertDB1($sql);
								$data['msgFlag']=1;
								
								$data['msg']=  "<font color=green>Successfully Truck Added</font>";
							}
						}
					}
					else
					{
						if($igmPackNumber>=$pack)
						{
							$insert_OK=1;
							$data['msgFlag']=0;
							if($insert_OK==1)
							{		
								$stat="";						
								for($i=($deliverd_truck+1); $i<=($deliverd_truck+2);$i++)
								{									
									$trucId=$this->input->post('truck'.$i);
									$packQty= $this->input->post('pkQty'.$i);
									$gateNo= $this->input->post('gateNo'.$i);
									if($trucId=="")
									{
										$trucId=0;
										$packQty=0;
									}
									else
									{
										$strInsertEq = "insert into do_information(verify_no, import_rotation, cont_number, truck_id, delv_pack, update_by, ip_addr, last_update, gate_no)
										values('$verifyNo','$rotNo', '$contNo', '$trucId', $packQty, '$login_id', '$ipaddr',  now(), '$gateNo')";

										// $strInsertEq = "insert into do_truck_details_entry(verify_number, import_rotation, cont_no, truck_id, delv_pack, update_by, ip_addr, last_update, gate_no)
										// values('$verifyNo','$rotNo', '$contNo', '$trucId', $packQty, '$login_id', '$ipaddr',  now(), '$gateNo')";
										$stat = $this->bm->dataInsertDB1($strInsertEq);									
									}
								}
								
								if($stat==1)
								{
									$sql="UPDATE shed_tally_info SET delivery_status = '1' WHERE verify_number = '$verifyNo'";
									$update = $this->bm->dataInsertDB1($sql);
									$data['msgFlag']=1;
									
									$data['msg']=  "<font color=green>Successfully Truck Added</font>";
								}
							}
						}
						else
						{
							$data['msgFlag']=2;
							$data['msg']= "<font color=red>Sorry! You are delivering more packages than IGM packages.</br> Please correct package quantity and try again!!.</font>";
						}
					}

					if($deliverd_truck>=0)
					{
						$doQuery="SELECT verify_no, delv_pack, truck_id, gate_no from do_information where verify_no='$verifyNo' order by id";
						// $doQuery="SELECT verify_number,delv_pack,truck_id,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass
						// from do_truck_details_entry where verify_number='$verifyNo' order by id";
						$doInfo = $this->bm->dataSelectDb1($doQuery);
						$data['doInfo']=$doInfo;
						$doShowFlag=1;
						$data['doShowFlag']=$doShowFlag;
						if($truck_num>$deliverd_truck)
						{
							$data['dlv_btn_status']=1;
						}
						else
						{						
							$data['dlv_btn_status']=0;
						}

						$data['tblFlag']=1;
						$doReportQuery="SELECT count(verify_no) as total, sum(delv_pack) as total_do_pack, count(truck_id) as total_truck_assign 
							from do_information where verify_no='$verifyNo'";

						// $doReportQuery="SELECT count(verify_number) as total, sum(delv_pack) as total_do_pack, count(truck_id) as total_truck_assign 
						// 	from do_truck_details_entry where verify_number='$verifyNo'";
						$checkDo = $this->bm->dataSelectDb1($doReportQuery);
						$delivered_pack=$checkDo[0]['total_do_pack'];
						$deliverd_truck = $checkDo[0]['total_truck_assign'];
						$rem_truck=$truck_num-$deliverd_truck;
						$data['deliverd_truck']=$deliverd_truck;
						$data['rem_truck']=$rem_truck;
					}
					else
					{
						$doQuery="SELECT verify_no, delv_pack, truck_id, gate_no from do_information where verify_no='$verifyNo' order by id";
						// $doQuery="SELECT verify_number,delv_pack,truck_id,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass
						// from do_truck_details_entry where verify_number='$verifyNo' order by id";
						$doInfo = $this->bm->dataSelectDb1($doQuery);
						$data['doInfo']=$doInfo;
						$data['dlv_btn_status']=1;
						$data['doShowFlag']=1;
						$data['tblFlag']=1;
					}
				}
				else
				{
					$doQuery="SELECT verify_no, delv_pack, truck_id, gate_no from do_information where verify_no='$verifyNo' order by id";
					
					$doInfo = $this->bm->dataSelectDb1($doQuery);
					$data['doInfo']=$doInfo;
					$data['dlv_btn_status']=0;
					$data['doShowFlag']=1;
					$data['tblFlag']=1;
					$data['msgFlag']=2;
					$data['msg']= "<font color=red>Sorry! Already delivered all assign trucks.</font>";
				}
			}
			
			$data['fclFlagValue'] = $fclFlagValue;
			// $this->load->view('header5');
			// $this->load->view('billSearchByVerifyForm',$data);
			// $this->load->view('footer_1');
			
			$data['verifyNo']=$verifyNo;
			$data['contNo']=$contNo;
			$data['cont_status']=$cont_status;
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('billSearchByVerifyForm',$data);
			$this->load->view('jsAssets');
		}
	}
	
	function addTruckToTmp()
	{								
		$login_id = $this->session->userdata('login_id');
		
		$truckNo = $this->input->post('truckNo');
		$driverName = $this->input->post('driverName');
		$driverPassNo = $this->input->post('driverPassNo');
		$assistantName = $this->input->post('assistantName');
		$assistantPassNo = $this->input->post('assistantPassNo');
		$importerMobileNo = $this->input->post('importerMobileNo');
		// $emrgncyTrk = $this->input->post('emrgncyTrk');
		$addBtn = $this->input->post('addBtn');

		$vrfyInfoFclId = $this->input->post('vrfyInfoFclId');
		$rotNo = $this->input->post('rotNo');
		$contNo = $this->input->post('contNo');
		$fclFlagValue = $this->input->post('fclFlagValue');
		$totTruck = $this->input->post('totTruck');
		$cont_status = $this->input->post('cont_status');
		
		$emrgncy_flag = 0;
		$emrgncy_approve_stat = 0;				
		
		$sql_updateImporterMbl = "UPDATE verify_info_fcl
		SET importer_mobile_no='$importerMobileNo'
		WHERE id='$vrfyInfoFclId'";
		$this->bm->dataUpdateDB1($sql_updateImporterMbl);				
			
		if(trim($addBtn) == "Emergency")
		{
			$emrgncy_flag = 1;
			$emrgncy_approve_stat = 0;
		}
		
		$stat = "";
		$sql_updateTrukTmp = "";
		$sql_insertTruckTmp = "";
		
		if($this->input->post('editFormId'))
		{
			$editFormId = $this->input->post('editFormId');						
			$frmType = $this->input->post('frmType');						
			// $sql_updateTrukTmp = "UPDATE do_truck_details_entry
			// SET truck_id='$truckNo',driver_name='$driverName',driver_gate_pass='$driverPassNo',assistant_name='$assistantName',assistant_gate_pass='$assistantPassNo'
			// WHERE id='$editFormId'";
			
			if($frmType=="Replace")
			{
				$sql_replaceInfo = "SELECT id,truck_id,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,paid_amt,paid_method,paid_collect_dt
				FROM do_truck_details_entry
				WHERE id='$editFormId'";
				$rslt_replaceInfo = $this->bm->dataSelectDB1($sql_replaceInfo);
				
				$repVisitId = $rslt_replaceInfo[0]['id'];
				$repTruckId = $rslt_replaceInfo[0]['truck_id'];
				$repDriverName = $rslt_replaceInfo[0]['driver_name'];
				$repDriverGatePass = $rslt_replaceInfo[0]['driver_gate_pass'];
				$repAssistantName = $rslt_replaceInfo[0]['assistant_name'];
				$repAssistantGatePass = $rslt_replaceInfo[0]['assistant_gate_pass'];
				$repPaidAmt = $rslt_replaceInfo[0]['paid_amt'];
				$repPaidMethod = $rslt_replaceInfo[0]['paid_method'];
				$repPaidCollectDt = $rslt_replaceInfo[0]['paid_collect_dt'];
				
				$sql_insertReplace = "INSERT INTO vcms_replace_truck_log(visit_id,truck_id,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,paid_amt,paid_method,paid_collect_dt,replace_time,replace_by)
				VALUES('$repVisitId','$repTruckId','$repDriverName','$repDriverGatePass','$repAssistantName','$repAssistantGatePass','$repPaidAmt','$repPaidMethod','$repPaidCollectDt',NOW(),'$login_id')";
				$this->bm->dataInsertDB1($sql_insertReplace);
				
				// update 
				$sql_updateReplace = "UPDATE do_truck_details_entry
				SET truck_id='$truckNo',driver_name='$driverName',driver_gate_pass='$driverPassNo',assistant_name='$assistantName',assistant_gate_pass='$assistantPassNo',paid_amt='',paid_method='',paid_status='0',paid_collect_dt=''
				WHERE id='$editFormId'";
				$this->bm->dataUpdateDB1($sql_updateReplace);
			}
			else
			{
				$sql_chkData = "SELECT COUNT(*) AS cnt FROM vcms_tmp_truck_dtl WHERE id='$editFormId'";
				$rslt_chkData = $this->bm->dataSelectDB1($sql_chkData);
				$cnt = $rslt_chkData[0]['cnt'];
				
				if($cnt == 0)
				{
					$sql_updateTrukTmp = "UPDATE do_truck_details_entry
					SET truck_id='$truckNo',driver_name='$driverName',driver_gate_pass='$driverPassNo',assistant_name='$assistantName',assistant_gate_pass='$assistantPassNo'
					WHERE id='$editFormId'";
				}
				else
				{
					$sql_updateTrukTmp = "UPDATE vcms_tmp_truck_dtl
					SET truck_id='$truckNo',driver_name='$driverName',driver_gate_pass='$driverPassNo',assistant_name='$assistantName',assistant_gate_pass='$assistantPassNo'
					WHERE id='$editFormId'";
				}
				$stat = $this->bm->dataUpdateDB1($sql_updateTrukTmp);
			}
		}
		else
		{
			$sql_insertTruckTmp = "INSERT INTO vcms_tmp_truck_dtl(verify_info_fcl_id,truck_id,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,emrgncy_flag,emrgncy_approve_stat)
			VALUES('$vrfyInfoFclId','$truckNo','$driverName','$driverPassNo','$assistantName','$assistantPassNo','$emrgncy_flag','$emrgncy_approve_stat')";
			$stat = $this->bm->dataInsertDB1($sql_insertTruckTmp);
		}						
		
		$sql_tmpRsltSet = "SELECT * FROM(SELECT id,verify_info_fcl_id,truck_id,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,'no' AS pay 
		FROM vcms_tmp_truck_dtl 
		WHERE verify_info_fcl_id = '$vrfyInfoFclId'
		UNION ALL
		SELECT id,verify_info_fcl_id,truck_id,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,'yes' AS pay
		FROM do_truck_details_entry
		WHERE verify_info_fcl_id = '$vrfyInfoFclId') AS tbl ORDER BY id";
		$rslt_tmpRsltSet = $this->bm->dataSelectDb1($sql_tmpRsltSet);
		
		$data['stat'] = $stat;
		$data['rslt_tmpRsltSet'] = $rslt_tmpRsltSet;
		
		$data['frmType'] = "New";
		//$data['frmType'] = $frmType;
				
		$this->bilSearchByVerifyNumber($rotNo,$contNo,$fclFlagValue,$cont_status);
		
	}
    //========================================================== deliver End
	

	
	//-----TALLY REPORT start
	function tallyReportForm()
	{
		$session_id = $this->session->userdata('value');
		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();
		}
		else
		{	
			$data['title']="TALLY REPORT FORM...";
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('tallyReportForm',$data);
			$this->load->view('jsAssets');
		}
	}
	
	function tallyReport()
	{
		if($this->input->post('rotation') && $this->input->post('container'))
				{
					$rotation=$this->input->post('rotation');
					$container=$this->input->post('container');

				}
				else{
					$rotation=str_replace("_","/",$this->uri->segment(3));
					$container=str_replace("_","/",$this->uri->segment(4));
					//$ddl_imp_rot_no=$this->uri->segment(4);
					//$rot_year=$this->uri->segment(4);
					//$rot_no=$this->uri->segment(5);
					//$ddl_imp_rot_no=$rot_year.'/'.$rot_no;
				}
		
		/*$sqltallyreport = "SELECT id,Import_Rotation_No,cont_number,Line_No,Pack_Marks_Number,Description_of_Goods,Pack_Number,loc_first,
  SUM(rcv_pack) AS rcv_pack,actual_marks,marks_state,
  (SELECT SUM(delv_pack) FROM do_information WHERE verify_no=tmp.verify_number) AS delv_pack
  FROM(SELECT igm_supplimentary_detail.id,igm_supplimentary_detail.Line_No,Import_Rotation_No,igm_sup_detail_container.cont_number,Pack_Marks_Number,igm_supplimentary_detail.Description_of_Goods,Pack_Number,rcv_pack,loc_first,actual_marks,marks_state,shed_tally_info.verify_number
  FROM igm_supplimentary_detail 
  INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
  LEFT JOIN shed_tally_info ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
  WHERE Import_Rotation_No='$rotation' AND igm_sup_detail_container.cont_number='$container' and rcv_pack>0) AS tmp GROUP BY id";*/
  
  //commented on 26/08/2017
  /* $sqltallyreport = "SELECT id,Import_Rotation_No,cont_number,Line_No,Pack_Marks_Number,Description_of_Goods,Pack_Number,
  (SUM(rcv_pack)+IFNULL(loc_first,0)) as totPkg,actual_marks,marks_state,
  (SELECT SUM(delv_pack) FROM do_information WHERE verify_no=tmp.verify_number) AS delv_pack
  FROM(SELECT igm_supplimentary_detail.id,igm_supplimentary_detail.Line_No,Import_Rotation_No,igm_sup_detail_container.cont_number,Pack_Marks_Number,igm_supplimentary_detail.Description_of_Goods,Pack_Number,rcv_pack,loc_first,actual_marks,marks_state,shed_tally_info.verify_number
  FROM igm_supplimentary_detail 
  INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
  LEFT JOIN shed_tally_info ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
  WHERE Import_Rotation_No='$rotation' AND igm_sup_detail_container.cont_number='$container') AS tmp GROUP BY id"; */
		
		$section = $this->session->userdata('section');
		/* $sqltallyreport = "SELECT id,Vessel_Name,Import_Rotation_No,cont_number,cont_size,tally_sheet_number,rcv_pack,loc_first,flt_pack,shed_loc,Line_No,Pack_Marks_Number,Description_of_Goods,Pack_Number,
		(SUM(rcv_pack)+IFNULL(loc_first,0)) AS totPkg,actual_marks,marks_state,
		(SELECT SUM(delv_pack) FROM do_information WHERE verify_no=tmp.verify_number) AS delv_pack,shift_name,Notify_name
		FROM(SELECT igm_supplimentary_detail.id,igm_supplimentary_detail.Line_No,igm_supplimentary_detail.Import_Rotation_No,igm_sup_detail_container.cont_number,Vessel_Name,cont_size,Pack_Marks_Number,igm_supplimentary_detail.Description_of_Goods,Pack_Number,rcv_pack,loc_first,actual_marks,marks_state,shed_tally_info.verify_number,shed_tally_info.flt_pack,shed_loc,tally_sheet_number,shift_name,Notify_name
		FROM igm_supplimentary_detail 
		INNER JOIN igm_masters ON igm_masters.id=igm_supplimentary_detail.igm_master_id
		INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
		LEFT JOIN shed_tally_info ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
		WHERE igm_supplimentary_detail.Import_Rotation_No='$rotation' AND igm_sup_detail_container.cont_number='$container') AS tmp GROUP BY id"; */
		
		
	/* 	$rtntallyreport = $this->bm->dataSelectDb1($sqltallyreport);
		
		$sqlBerth="SELECT IFNULL(flex_string02,flex_string03) AS rtnValue FROM sparcsn4.vsl_vessel_visit_details WHERE ib_vyg='$rotation'";
		
		$rsltBerth=$this->bm->dataReturn($sqlBerth); */
		
		$sqlinfo = "SELECT id,Vessel_Name,Import_Rotation_No,cont_number,cont_seal_number,cont_size,tally_sheet_number,rcv_pack,loc_first,flt_pack,shed_loc,Line_No,Pack_Marks_Number,Description_of_Goods,Pack_Number,
		(SUM(rcv_pack)+IFNULL(loc_first,0)) AS totPkg,actual_marks,marks_state,
		(SELECT SUM(delv_pack) FROM do_information WHERE verify_no=tmp.verify_number) AS delv_pack,shift_name,Notify_name,mlocode
		FROM(SELECT igm_supplimentary_detail.id,igm_supplimentary_detail.Line_No,igm_supplimentary_detail.Import_Rotation_No,igm_sup_detail_container.cont_number,cont_seal_number,Vessel_Name,cont_size,igm_supplimentary_detail.Pack_Marks_Number,igm_supplimentary_detail.Description_of_Goods,igm_supplimentary_detail.Pack_Number,rcv_pack,loc_first,actual_marks,marks_state,shed_tally_info.verify_number,shed_tally_info.flt_pack,shed_loc,tally_sheet_number,shift_name,igm_supplimentary_detail.Notify_name,igm_details.mlocode
		FROM igm_supplimentary_detail 
		INNER JOIN igm_details ON igm_details.id=igm_supplimentary_detail.igm_detail_id
		INNER JOIN igm_masters ON igm_masters.id=igm_supplimentary_detail.igm_master_id
		INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
		LEFT JOIN shed_tally_info ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
		WHERE igm_supplimentary_detail.Import_Rotation_No='$rotation' AND igm_sup_detail_container.cont_number='$container') AS tmp GROUP BY id";
		
		$rtninfo = $this->bm->dataSelectDb1($sqlinfo);
		
		$sqlBerth="SELECT IFNULL(flex_string02,flex_string03) AS berthOp,DATE(sparcsn4.argo_carrier_visit.ata) AS ata
		FROM sparcsn4.vsl_vessel_visit_details 
		INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.cvcvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
		WHERE ib_vyg='$rotation'";
		
		$rsltBerth=$this->bm->dataSelect($sqlBerth);
			
		$data['rotation']=$rotation;
		$data['container']=$container;
		$data['section']=$section;
		$data['rtninfo']=$rtninfo;
		$data['rsltBerth']=$rsltBerth;
		//Kawsar comment it 12-05-2020
		//$data['counter']=$counter;
		$this->load->view('tallyReport',$data);
	}
	
	//Kawsar 05/18/2020

	//function tallyReportPdf($rot,$cont)

	function tallyReportPdf()
	{ 
				
		if($this->input->post('rotation') && $this->input->post('container'))
		{
			$rotation=$this->input->post('rotation');
			$container=$this->input->post('container');
		}
		/*else if($_GET["rotation"] && $_GET["cont"])
		{
			$rotation=$_GET["rotation"];
			$container=$_GET["cont"];
		}*/
		else if($this->uri->segment(3) != null && $this->uri->segment(4) != null)
		{
			$container_size = $this->input->post('container_size');
			$seal_no = $this->input->post('seal_no');
			
			$csize = $this->input->post('csize');
			$rotation=str_replace("_","/",$this->uri->segment(3));
			$container=str_replace("_","/",$this->uri->segment(4));
		}
		else{
			$rotation=$rot;
			$container=$cont;
		}
		
		$section = $this->session->userdata('section');
		$login_id = $this->session->userdata('login_id');
		$containerSize = "";
		$contSealNumber = "";
		$sqlinfo = "SELECT  id,(SELECT Vessel_Name FROM igm_masters WHERE Import_Rotation_No= tmp.import_rotation) AS Vessel_Name,import_rotation as Import_Rotation_No,cont_number,cont_seal_number,cont_size,tally_sheet_number,rcv_pack,rcv_unit,loc_first,flt_pack,shed_loc,Line_No,Pack_Marks_Number,Description_of_Goods,Pack_Number,
		(SUM(rcv_pack)+IFNULL(loc_first,0)) AS totPkg,actual_marks,marks_state,
		(SELECT SUM(delv_pack) FROM do_information WHERE verify_no=tmp.verify_number) AS delv_pack,shift_name,wr_date,Notify_name,mlocode,shed_yard,berthOp
		FROM (SELECT shed_tally_info.igm_sup_detail_id as id,rcv_unit,igm_supplimentary_detail.Line_No,shed_tally_info.import_rotation,igm_sup_detail_container.cont_number,cont_seal_number,Vessel_Name,cont_size,igm_supplimentary_detail.Pack_Marks_Number,igm_supplimentary_detail.Description_of_Goods,igm_supplimentary_detail.Pack_Number,rcv_pack,loc_first,actual_marks,marks_state,shed_tally_info.verify_number,shed_tally_info.flt_pack,shed_loc,tally_sheet_number,shift_name,shed_tally_info.wr_date,
		(select Organization_Name from organization_profiles where id=igm_supplimentary_detail.Submitee_Org_Id) as Notify_name,igm_details.mlocode,
		shed_tally_info.shed_yard, shed_tally_info.berthOp
		FROM  shed_tally_info
		LEFT JOIN igm_supplimentary_detail ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
		LEFT JOIN igm_details ON igm_details.id=igm_supplimentary_detail.igm_detail_id
		LEFT JOIN igm_masters ON igm_masters.id=igm_supplimentary_detail.igm_master_id
		LEFT JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id		
		WHERE shed_tally_info.import_rotation='$rotation' AND shed_tally_info.cont_number='$container') AS tmp GROUP BY id";
		
		$rtninfo = $this->bm->dataSelectDb1($sqlinfo);
		for($c=1;$c<count($rtninfo);$c++)
		{
			$containerSize = $rtninfo[$c]['cont_size'];
			$contSealNumber = $rtninfo[$c]['cont_seal_number'];
		}
		$loopCounter="select * from (SELECT igm_supplimentary_detail.id,master_BL_No,Description_of_Goods,Import_Rotation_No,BL_No,cont_number,
		cont_size,Cont_gross_weight,cont_seal_number,Pack_Description,Pack_Marks_Number,Pack_Number,ConsigneeDesc,NotifyDesc 
		FROM igm_supplimentary_detail 
		LEFT JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id					
		WHERE Import_Rotation_No='$rotation' AND cont_number='$container'
		) as tbl1
		union
		select * from (SELECT shed_tally_info.igm_sup_detail_id as id,master_BL_No,Description_of_Goods,import_rotation as Import_Rotation_No,BL_No,
		shed_tally_info.cont_number, cont_size,Cont_gross_weight,cont_seal_number,Pack_Description,actual_marks,Pack_Number,ConsigneeDesc,
		NotifyDesc FROM shed_tally_info 
		LEFT JOIN igm_supplimentary_detail ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id 
		LEFT JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
		WHERE shed_tally_info.import_rotation='$rotation' and shed_tally_info.cont_number='$container' and BL_NO is null
		AND shed_tally_info.igm_sup_detail_id IS NOT NULL
		) as tbl2
		UNION
		SELECT * FROM ( SELECT shed_tally_info.igm_detail_id, BL_No AS master_BL_No, Description_of_Goods, 
		shed_tally_info.import_rotation AS Import_Rotation_No, BL_No,  shed_tally_info.cont_number, 
		cont_size, Cont_gross_weight, cont_seal_number, Pack_Description, IFNULL(actual_marks,Pack_Marks_Number) AS Pack_Marks_Number,
		IFNULL(rcv_pack, Pack_Number) AS Pack_Number, ConsigneeDesc, NotifyDesc 
		FROM shed_tally_info
		LEFT JOIN igm_details ON igm_details.id=shed_tally_info.igm_detail_id
		LEFT JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id	
		WHERE shed_tally_info.import_rotation='$rotation' AND shed_tally_info.cont_number='$container' 
		AND shed_tally_info.igm_detail_id IS NOT NULL
		) tbl3";
		
		$rtnCounter = $this->bm->dataSelectDb1($loopCounter);
		
		$sqlBerth="SELECT IFNULL(flex_string03,flex_string02) AS berthOp,DATE(sparcsn4.argo_carrier_visit.ata) AS ata
		FROM sparcsn4.vsl_vessel_visit_details 
		INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.cvcvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
		WHERE ib_vyg='$rotation'";
		//echo $sqlBerth;
		$rsltBerth=$this->bm->dataSelect($sqlBerth);
		
		$sqlSigQuery="select berth_exchange_done_status,berth_exchange_done_by,berth_exchange_done_at,
		ff_exchange_done_status,ff_exchange_done_by,ff_exchange_done_at,
		cpa_exchange_done_status,cpa_exchange_done_by,cpa_exchange_done_at 
		from shed_tally_info 
		where import_rotation='$rotation' and cont_number='$container'";
			
		$rsltSig=$this->bm->dataSelectDb1($sqlSigQuery);
		
		
		$this->data['rotation']=$rotation;
		$this->data['container']=$container;
		$this->data['section']=$section;
		$this->data['rtninfo']=$rtninfo;
		$this->data['rtnCounter']=$rtnCounter;
		$this->data['rsltBerth']=$rsltBerth;
		$this->data['rsltSig']=$rsltSig;
		
		$this->data['container_size']=$container_size;
		$this->data['seal_no']=$seal_no;
		$this->data['csize']=$csize;
		//$this->data['counter']=$counter;
		
		//block if pdf doesn't open in chrome --------starts
		ob_clean();
		header('Content-type: application/pdf');
		header('Content-Disposition: inline; filename="' . $tallyReportPdfOutput . '"');
		header('Content-Transfer-Encoding: binary');
		header('Accept-Ranges: bytes');
		//block if pdf doesn't open in chrome --------ends
		
		$this->load->library('m_pdf');
		$html=$this->load->view('tallyReportPdfOutput',$this->data, true); //load the pdf_output.php by passing our data and get all data in $html varriable.
		 
		$pdfFilePath ="tallyReport-".time()."-download.pdf";

		$pdf = $this->m_pdf->load();
		$pdf->allow_charset_conversion = true;
		$pdf->charset_in = 'iso-8859-4';
		$stylesheet = file_get_contents('assets/stylesheets/test.css'); // external css
	//	$pdf->useSubstitutions = true; // optional - just as an example
			
		//$pdf->setFooter('Prepared By : '.$login_id.'|Page {PAGENO} of {nb}|Date {DATE j-m-Y}');
			
		$pdf->WriteHTML($stylesheet,1);
		$pdf->WriteHTML($html,2);
			 
		$pdf->Output($pdfFilePath, "I"); // For Show Pdf
	}
	//-----TALLY REPORT end


// function shedBillSummaryRptForm()
// 				{
// 					$session_id = $this->session->userdata('value');
// 					if($session_id!=$this->session->userdata('session_id'))
// 					{
// 						$this->logout();
						
// 					}
// 					else
// 					{
// 						$data['title']="SUMMARY FOR SHED BILL";
// 						$this->load->view('header2');
// 						$this->load->view('shedBillSummaryRptForm',$data);   
// 						$this->load->view('footer');
// 					}
// 				}
	// function shedBillSummaryRptView()
	// 			{
	// 				$login_id = $this->session->userdata('login_id');	
	// 				$data['login_id']=$login_id;
					
	// 				$from_dt=$this->input->post('from_dt');
	// 				$to_dt=$this->input->post('to_dt');

	// 				$data['from_dt']=$from_dt;
	// 				$data['to_dt']=$to_dt;
					
	// 				$data['title']="FROM : ".$from_dt." TO : ".$to_dt;
					
	// 				$this->load->view('shedBillSummaryRptView',$data);   
	// 			}	
				
	public function getShedStockBalancePdf()
	{ 
		//load mPDF library
		$this->load->library('m_pdf');
		$mpdf->use_kwt = true;
		$login_id = $this->session->userdata('login_id');
		//load mPDF library
	   
		  $strVerifyNum=$this->input->post('vNum');
		
		if($billVerify=="")
		{
			$str="select verify_number,import_rotation,shed_tally_info.cont_number,master_BL_No,BL_No,cont_weight,cont_size,cont_height,cont_status,cont_type,
				Pack_Number,Pack_Description,Notify_name from shed_tally_info 
				inner join igm_supplimentary_detail on shed_tally_info.igm_sup_detail_id = igm_supplimentary_detail.id
				inner join igm_sup_detail_container on shed_tally_info.igm_sup_detail_id=igm_sup_detail_container.igm_sup_detail_id
				where verify_number>0 and shed_tally_info.delivery_status not in (1)";
		}
			else{
				$str="select verify_number,import_rotation,shed_tally_info.cont_number,master_BL_No,BL_No,cont_weight,cont_size,cont_height,cont_status,cont_type,
					Pack_Number,Pack_Description,Notify_name from shed_tally_info 
					inner join igm_supplimentary_detail on shed_tally_info.igm_sup_detail_id = igm_supplimentary_detail.id
					inner join igm_sup_detail_container on shed_tally_info.igm_sup_detail_id=igm_sup_detail_container.igm_sup_detail_id
					where verify_number='$strVerifyNum' and shed_tally_info.delivery_status not in (1)";
			}
				//echo $str;
				//echo $str;
		$rtnContainerList = $this->bm->dataSelectDb1($str);
		
		 $this->data['rtnContainerList']=$rtnContainerList;
		
		 $this->data['title']="Verify List";
		

		$html=$this->load->view('shedBillStockBalancePdfOutput',$this->data, true); //load the pdf_output.php by passing our data and get all data in $html varriable.
 	 
		//this the the PDF filename that user will get to download
		$pdfFilePath ="shedBill-".time()."-download.pdf";

		
		//actually, you can pass mPDF parameter on this load() function
		$pdf = $this->m_pdf->load();
		//$pdf->mirrorMargins = 1;
		//generate the PDF!
		//$stylesheet = file_get_contents('assets/css/main.css');
        //$mpdf->WriteHTML($stylesheet,1);
		//$stylesheet = file_get_contents('resources/styles/test.css'); // external css
		$pdf->useSubstitutions = true; // optional - just as an example
		//$pdf->SetHeader($url . "\n\n" . 'Date {DATE j-m-Y}');  // optional - just as an example
		//echo "SheetAdd : ".$stylesheet;
		$pdf->setFooter('Prepared By :'.$login_id.' |Page {PAGENO}|Date {DATE j-m-Y}');
		//$footerHtml='<pagefooter name="MyFooter1" content-left="{DATE j-m-Y}" content-center="{PAGENO}/{nbpg}" content-right="My document" footer-style="font-family: serif; font-size: 8pt; font-weight: bold; font-style: italic; color: #000000;" />';
		//$pdf->WriteHTML($stylesheet,1);
		$pdf->WriteHTML($html,2);
		//$pdf->WriteHTML('<pagebreak resetpagenum="1" pagenumstyle="1" suppress="off" />');
		//offer it to user via browser download! (The PDF won't be saved on your server HDD)
		//$pdf->Output($pdfFilePath, "D"); /// For Direct Download 
		$pdf->Output($pdfFilePath, "I"); // For Show Pdf
	}	
	
	
	function billGenerationForm()
	{
		$session_id = $this->session->userdata('value');
		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();
				
		}
		else
		{
			$verify_number = $this->input->post('verify_number');
			if($verify_number=="")
			{
				$data['formType']="sidebar";
			}
			else
			{
				$data['formType']="list";
				$data['verify_number']=$verify_number;
			}
			
			$getBillTarrifQuery= "select id,bil_tariffs.description,long_description,bil_tariffs.gl_code,effective_date,rate_type,amount from bil_tariffs inner join bil_tariff_rates on 
								bil_tariffs.gkey=bil_tariff_rates.tariff_gkey ORDER BY ID ASC ";
			$getBillTarrif = $this->bm->dataSelectDb1($getBillTarrifQuery);
			
			$strrcvAllQry = "select invoice_id as type_id,invoice_description as billFor from invoice_type"; 
			$getRcvAllQry = $this->bm->dataSelectDb1($strrcvAllQry);
			
			$data['getRcvAllQry']=$getRcvAllQry;			
			$data['getBillTarrif']=$getBillTarrif;			
			$data['title']="BILL GENERATION";
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('billGenerationForm',$data);
			$this->load->view('jsAssets');
		}
	}
	
	function billGenerationFormToDB()
	{
		$ipaddr = $_SERVER['REMOTE_ADDR'];
		$login_id = $this->session->userdata('login_id');
		
		$verify_number = $this->input->post('verify_num');
		$bill_no = $this->input->post('bill_no');
		$da_bill_no = $this->input->post('da_bill_no');
		$bill_date = $this->input->post('bill_date');
		$rotation_no = $this->input->post('rotation_no');
		$arr_dt = $this->input->post('arr_dt');
		$comm_dt = $this->input->post('comm_dt');
		$wr_dt = $this->input->post('wr_dt');
		$ado_no = $this->input->post('ado_no');
		$ado_dt = $this->input->post('ado_dt');
		$ado_upto = $this->input->post('ado_upto');
		$one_stop_point = $this->input->post('one_stop_point');
		$ex_rate = $this->input->post('ex_rate');
		$bill_for = $this->input->post('bill_for');
		$unstfDt = $this->input->post('unstfDt');
		$wr_upto_dt = $this->input->post('wr_upto_dt');
		$be_no = $this->input->post('be_no');
		$be_dt = $this->input->post('be_dt');
		$less = $this->input->post('less');
		$part_bl = $this->input->post('part_bl');
		
		$cont_qty = $this->input->post('cont_qty');
		$cont_wht = $this->input->post('cont_wht');
		$cnfCode = $this->input->post('cnfCode');
		$cnfName = $this->input->post('cnfName');
		$impo_reg_no = $this->input->post('impo_reg_no');
		$impo_reg_name = $this->input->post('impo_reg_name');
		
		$total_port = $this->input->post('total_port');
		$total_vat = $this->input->post('total_vat');
		$total_mlwf = $this->input->post('total_mlwf');
		$less_amt_port = $this->input->post('less_amt_port');
		$less_amt_vat = $this->input->post('less_amt_vat');
		$grand_total = $this->input->post('grand_total');
					
		$remarks = $this->input->post('remarks');
		$extra_movement=$this->input->post('ext_mov_twnty');
		if($extra_movement=="")
		{
			$extra_movement=$this->input->post('ext_mov_forty');
		}
		$vessel_name = $this->input->post('vessel_name');
		$bl_no = $this->input->post('bl_no');
		$container_size = $this->input->post('container_size');
		$container_height = $this->input->post('container_height');
		
		$chkBillExistQuery="select count(bill_no) as chkRslt from shed_bill_master where verify_no='$verify_number'";
		$getBillRslt = $this->bm->dataSelectDb1($chkBillExistQuery);
		$billExist=$getBillRslt[0]['chkRslt'];
	
		if($billExist > 0)
		{
			echo "<font color='red'><b>Bill Already Generated.</b></font>";			
		}
		else
		{
			$billGenNoQuery="select IFNULL(MAX(bill_generation_no),0)+1 as bill_generation_no 
			from shed_bill_master 
			where right(YEAR(now()),2)=".date('y');
			$billGenNo = $this->bm->dataSelectDb1($billGenNoQuery);
			$getBillGenNo = $billGenNo[0]['bill_generation_no'];
			// $shed_bill_details;
			
			$shedMasterInsertQry="insert into shed_bill_master (verify_no,unit_no,cpa_vat_reg_no,ex_rate,bill_date,
			arraival_date,import_rotation,vessel_name,
			cl_date,bl_no,wr_date,wr_upto_date,importer_vat_reg_no,importer_name,cnf_lic_no,cnf_agent,
			be_no,be_date,ado_no,ado_date,ado_valid_upto,
			manifest_qty,cont_size,cont_height,cont_weight,da_bill_no,bill_for,less,part_bl,remarks,extra_movement,total_port,
			total_vat,total_mlwf,less_amt_port,less_amt_vat,grand_total,user,ip_address,entry_dt,bill_generation_no) 
			values 
			('$verify_number','$one_stop_point','2041001546','$ex_rate','$bill_date','$arr_dt','$rotation_no',
			'$vessel_name','$comm_dt','$bl_no','$wr_dt',
			'$wr_upto_dt','$impo_reg_no','$impo_reg_name','$cnfCode','$cnfName',
			'$be_no','$be_dt','$ado_no','$ado_dt','$ado_upto','$cont_qty','$container_size','$container_height','$cont_wht','$da_bill_no',
			'$bill_for','$less','$part_bl','$remarks','$extra_movement','$total_port','$total_vat','$total_mlwf','$less_amt_port','$less_amt_vat',
			'$grand_total','$login_id','$ipaddr',now(),$getBillGenNo)";								 
									
			$shedMasterInsert=$this->bm->dataInsertDB1($shedMasterInsertQry);
			$chkBillNoQuery="select bill_no 
			from shed_bill_master 
			where verify_no='$verify_number' order by bill_no desc limit 1";
			$getBillNo = $this->bm->dataSelectDb1($chkBillNoQuery);
			$bill=$getBillNo[0]['bill_no'];
				
			if($shedMasterInsert==1)
			{
				for($i=0; $i<=$this->input->post('tbl_total_row');$i++)
				{
					if($this->input->post('tarrif_id'.$i)!="")
					{
						$tarrif_id= $this->input->post('tarrif_id'.$i);
						$billHead= $this->input->post('billHead'.$i);
						$gl_code= $this->input->post('option'.$i);
						$rate= $this->input->post('rate'.$i);
						$qty= $this->input->post('qty'.$i);
						$qday= $this->input->post('qday'.$i);
						$weight= $this->input->post('weight'.$i);
						$mlwf= $this->input->post('mlwf'.$i);
						$vatTK= $this->input->post('vatTK'.$i);
						$amt= $this->input->post('amt'.$i);
						
						$strInsertEq = "insert into shed_bill_details (verify_no,bill_no,gl_code,description,tarrif_rate,Qty,qday,amt,vatTK,mlwfTK) 
						values('$verify_number','$bill','$gl_code','$billHead','$rate','$qty','$qday','$amt','$vatTK','$mlwf')";  	  
						$stat = $this->bm->dataInsertDB1($strInsertEq);
					}
				}
				
				$tarrif_id_val= $this->input->post('tarrif_id_val');
				$billHeadVal= $this->input->post('billHeadVal');
				$glcodeVal= $this->input->post('optionVal');
				$rateVal= $this->input->post('rateVal');
				$qtyVal= $this->input->post('qtyVal');
				$qdayVal= $this->input->post('qdayVal');
				$weightVal= $this->input->post('weightVal');
				$mlwf_val= $this->input->post('mlwf_val');
				$vatTKVal= $this->input->post('vatTKVal');
				$amtVal= $this->input->post('amtVal');
				if($glcodeVal!="")
				{
					$strInsertUsedEq = "insert into shed_bill_details (verify_no,bill_no,gl_code,description,tarrif_rate,Qty,qday,amt,
											vatTK,mlwfTK) 
					values('$verify_number','$bill','$glcodeVal','$billHeadVal','$rateVal','$qtyVal','$qdayVal','$amtVal',
							'$vatTKVal','$mlwf_val')";  	  
					$resInsertUsedEq = $this->bm->dataInsertDB1($strInsertUsedEq);
				}
				
				$rtnBillQuery="select concat(right(YEAR(bill_date),2),'/',
							concat(if(length(bill_generation_no)=1,'00000',if(length(bill_generation_no)=2,'0000',if(length(bill_generation_no)=3,'000',
							if(length(bill_generation_no)=4,'00',if(length(bill_generation_no)=5,'0',''))))),bill_generation_no)) as bill_no 
							from shed_bill_master 
							where verify_no='$verify_number'";
				$rtnBillNo = $this->bm->dataSelectDb1($rtnBillQuery);
				$billNo=$rtnBillNo[0]['bill_no'];
			
				echo "<font color='green'><b>Bill Generated. Bill No: ".$billNo."</b></font> <a href='".site_url('ShedBillController/getShedBillPdf/'.$verify_number)."' target='_blank'>View Bill</a>";					 					 					 
			}
			else
			{
				echo "<font color='red'><b>Bill Not Created</b></font>";
			}								
		}
	}
	

	
	//unit set or update start
	function unitSetUpdate()
	{
		$session_id = $this->session->userdata('value');
		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();
		}
		else
		{
			$msg="";
			$value=0;
			
			$data['title']="UNIT UPDATE...";
			$data['msg']=$msg;
			$data['value']=$value;
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('unitSetUpdate',$data);
			$this->load->view('jsAssets');
		}
	}
	
	function unitSetUpdatePerform()
	{
		$session_id = $this->session->userdata('value');
		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();
		}
		else
		{
			$rotation=$this->input->post('rotation');
			$unit=$this->input->post('unit');

			$value="";
			if(is_numeric($unit))
			{
				$sql_check="select count(*) as rtnValue from assigned_unit where rotation='$rotation'";
				$rtn_check=$this->bm->dataReturnDB1($sql_check);
				
				$value=0;
				
				if($rtn_check==0)
				{
					$sql_insert="insert into assigned_unit(rotation,unit_no) values('$rotation','$unit')";
					$rslt_insert=$this->bm->dataInsertDB1($sql_insert);
					$msg="<font color='green'><b>Successfully inserted</b></font>";
				}
				else
				{
					$sql_update="update assigned_unit set unit_no='$unit' where rotation='$rotation'";
					$rslt_update=$this->bm->dataUpdateDB1($sql_update);
					$msg="<font color='green'><b>Successfully updated</b></font>";
				}
			}
			else
			{
				$msg="<font color='red'><b>Please provide digit only...</b></font>";
			}
			
			$data['title']="UNIT UPDATE...";
			$data['msg']=$msg;
			$data['value']=$value;
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('unitSetUpdate',$data);
			$this->load->view('jsAssets');
		}
	}
	//unit set or update end
	
	//unit list start
	function unitList()
	{
		$session_id = $this->session->userdata('value');
		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();
		}
		else
		{
			$sql_list="select * from assigned_unit";
			$rslt_list=$this->bm->dataSelectDB1($sql_list);
			$msg="";
			
			$data['title']="UNIT LIST...";
			$data['rslt_list']=$rslt_list;
			$data['msg']=$msg;
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('unitList',$data);
			$this->load->view('jsAssets');
		}
	}
	
	function unitListSearch()
	{
		$session_id = $this->session->userdata('value');
		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();
		}
		else
		{
			$rotation=$this->input->post('rotation');
			$msg="";
			
			$sql_list="select * from assigned_unit where rotation='$rotation'";
			$rslt_list=$this->bm->dataSelectDB1($sql_list);
			
			$data['title']="UNIT LIST...";
			$data['rslt_list']=$rslt_list;
			$data['msg']=$msg;

			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('unitList',$data);
			$this->load->view('jsAssets');
		}
	}
	//unit list end
	
	//unit list delete start
	function unitListDelete()
	{
		$session_id = $this->session->userdata('value');
		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();
		}
		else
		{
			$rotation=$this->input->post('rot');
			$unit=$this->input->post('unit');
			$msg="<font color='green'><b>Successfully Deleted</b></font>";
			
			$sql_delete="DELETE FROM assigned_unit WHERE rotation='$rotation' AND unit_no='$unit'";
			
			$rslt_delete = $this->bm->dataDeleteDB1($sql_delete);
			
			$sql_list="select * from assigned_unit";
			$rslt_list=$this->bm->dataSelectDB1($sql_list);
			
			$data['title']="UNIT LIST...";
			$data['rslt_list']=$rslt_list;
			$data['msg']=$msg;
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('unitSetUpdate',$data);
			$this->load->view('jsAssets');
			
		}
	}
	//unit list delete end
	
	//unit list edit start
	function unitListEdit()
	{
		$session_id = $this->session->userdata('value');
		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();
		}
		else
		{
			$rotation=$this->input->post('rt_no');
			$msg="";
			$value=1;
			
			$data['title']="UNIT UPDATE...";
			$data['msg']=$msg;
			$data['value']=$value;
			$data['rotation']=$rotation;
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('unitSetUpdate',$data);
			$this->load->view('jsAssets');
		}
	}
	//unit list edit end



    //=======================================================================================================================New theam work 5/4/2020...
    //-----SHED BILL LIST start
    function shedBillListForm()
    {
        $session_id = $this->session->userdata('value');

        if($session_id!=$this->session->userdata('session_id'))
        {
            $this->logout();
        }
        else
        {

            $sqlbillno="SELECT shed_bill_master.bill_no AS bn,CONCAT(RIGHT(YEAR(bill_date),2),'/',CONCAT(IF(LENGTH(bill_generation_no)=1,'00000',IF(LENGTH(bill_generation_no)=2,'0000',IF(LENGTH(bill_generation_no)=3,'000',IF(LENGTH(bill_generation_no)=4,'00',IF(LENGTH(bill_generation_no)=5,'0',''))))),bill_generation_no)) AS bill_no,shed_bill_master.verify_no,unit_no,import_rotation,cnf_agent,SUM(amt) AS total_amt,SUM(vatTk) AS total_vat,total_port,total_mlwf,rcv_delete_stat 
			FROM shed_bill_details 
			INNER JOIN shed_bill_master ON shed_bill_master.bill_no=shed_bill_details.bill_no 
			GROUP BY shed_bill_master.bill_no ORDER BY bill_no DESC";

            $rtnbillno = $this->bm->dataSelectDb1($sqlbillno);

            $data['rtnbillno']=$rtnbillno;
            $data['title']="CONSIGNEE BILL LIST FORM...";

            $this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('shedBillListForm',$data);
            $this->load->view('jsAssetsList');
        }
    }
	
	function shed_bill_cash_payment()
    {
		$session_id = $this->session->userdata('value');
		$total_amt = $this->input->post('total_amt');
		
		$sqlbillno="SELECT shed_bill_master.bill_no AS bn,CONCAT(RIGHT(YEAR(bill_date),2),'/',CONCAT(IF(LENGTH(bill_generation_no)=1,'00000',IF(LENGTH(bill_generation_no)=2,'0000',IF(LENGTH(bill_generation_no)=3,'000',IF(LENGTH(bill_generation_no)=4,'00',IF(LENGTH(bill_generation_no)=5,'0',''))))),bill_generation_no)) AS bill_no,shed_bill_master.verify_no,unit_no,import_rotation,cnf_agent,SUM(amt) AS total_amt,SUM(vatTk) AS total_vat,total_port,total_mlwf 
		FROM shed_bill_details 
		INNER JOIN shed_bill_master ON shed_bill_master.bill_no=shed_bill_details.bill_no 
		GROUP BY shed_bill_master.bill_no ORDER BY bill_no DESC";

		$rtnbillno = $this->bm->dataSelectDb1($sqlbillno);

		$data['rtnbillno']=$rtnbillno;
		$data['total_amt']=$total_amt;
		$data['title']="Payment..";

		$this->load->view('cssAssetsList');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('shed_bill_cash_view',$data);
		$this->load->view('jsAssetsList');        
    }
	
    function shedBillList()
    {
        $search_by=$this->input->post('search_by');

        if($search_by=="billNo")
        {
            $billNo=$this->input->post('search_value');
            $cond = " WHERE shed_bill_master.bill_generation_no=RIGHT('$billNo',6) GROUP BY shed_bill_master.bill_no";
        }

        else if($search_by=="verifyNo")
        {
            $verifyNo=$this->input->post('search_value');
            $cond = " WHERE shed_bill_master.verify_no='$verifyNo' GROUP BY shed_bill_master.bill_no";
        }

        else if($search_by=="Unit")
        {
            $Unit=$this->input->post('search_value');
            $cond = " WHERE shed_bill_master.unit_no='$Unit' GROUP BY shed_bill_master.bill_no";
        }

        $sqlbillno = "SELECT shed_bill_master.bill_no AS bn,CONCAT(RIGHT(YEAR(bill_date),2),'/',CONCAT(IF(LENGTH(bill_generation_no)=1,'00000',IF(LENGTH(bill_generation_no)=2,'0000',IF(LENGTH(bill_generation_no)=3,'000',
		IF(LENGTH(bill_generation_no)=4,'00',IF(LENGTH(bill_generation_no)=5,'0',''))))),bill_generation_no)) AS bill_no,shed_bill_master.verify_no,unit_no,import_rotation,cnf_agent,SUM(amt) AS total_amt,SUM(vatTk) AS total_vat,total_port,total_mlwf 
		FROM shed_bill_details INNER JOIN shed_bill_master 
		ON shed_bill_master.bill_no=shed_bill_details.bill_no".$cond;

        $rtnbillno = $this->bm->dataSelectDb1($sqlbillno);
//        echo "<pre>";
//        print_r($rtnbillno);
//        echo "</pre>";
//        exit();
        $data['title']="SHED BILL LIST FORM...";
        $data['rtnbillno']=$rtnbillno;
    
    
		$this->load->view('cssAssetsList');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('shedBillListForm',$data);
		$this->load->view('jsAssetsList');
    }

    //shed bill for FCL start
    public function getShedBillFCLPdf()
    {    		
    	if($this->uri->segment(3))
		{

    		$billVerify = $this->uri->segment(4);

			$login_id = $this->session->userdata('login_id');
		   
		   
			$strBankPaymentInfo = "select shed_bill_master.bill_no,bill_rcv_stat,cp_bank_code,user,
			concat(cp_bank_code,cp_unit,'/',right(cp_year,2),'-',concat(if(length(cp_no)=1,'000',if(length(cp_no)=2,'00',if(length(cp_no)=3,'0',''))),cp_no)) as cp_no
			from shed_bill_master 
			inner join bank_bill_recv on bank_bill_recv.bill_no=shed_bill_master.bill_no
			where verify_no='$billVerify'";
			$rtnBankPaymentInfo = $this->bm->dataSelectDb1($strBankPaymentInfo);   //ok query
			
			$rcvstat=$rtnBankPaymentInfo[0]['bill_rcv_stat'];
			$cpnoview=$rtnBankPaymentInfo[0]['cp_no'];
			$cpbankcode=$rtnBankPaymentInfo[0]['cp_bank_code'];
			$shedbill=$rtnBankPaymentInfo[0]['bill_no'];
			$bill_clerk=$rtnBankPaymentInfo[0]['user'];

			if($cpbankcode=="OB")
				$cpbankname="ONE BANK LIMITED";

			$str="select concat(right(YEAR(bill_date),2),'/',
			concat(if(length(bill_generation_no)=1,'00000',if(length(bill_generation_no)=2,'0000',if(length(bill_generation_no)=3,'000',
			if(length(bill_generation_no)=4,'00',if(length(bill_generation_no)=5,'0',''))))),bill_generation_no)) as bill_no,verify_no,unit_no,cpa_vat_reg_no,ex_rate,bill_date,arraival_date,import_rotation,vessel_name,
			cl_date,bl_no,wr_date,wr_upto_date,importer_vat_reg_no,importer_name,cnf_lic_no,cnf_agent,be_no,
			be_date,ado_no,ado_date,ado_valid_upto,manifest_qty,cont_size,cont_height from shed_bill_master 
			where verify_no='$billVerify'";
			
			$rtnContainerList = $this->bm->dataSelectDb1($str);  
			$unit_no=$rtnContainerList[0]['unit_no'];
			$cpa_vat_reg_no=$rtnContainerList[0]['cpa_vat_reg_no'];
			$ex_rate=$rtnContainerList[0]['ex_rate'];

			$qry="select verify_no,bill_no,gl_code,description,tarrif_rate,Qty,qday,amt,vatTK,mlwfTK from shed_bill_details
						where verify_no='$billVerify'";
			$chargeList = $this->bm->dataSelectDb1($qry); 

			$qry_sum="select SUM(amt) as amt from shed_bill_details
						where verify_no='$billVerify'";  
			$sumAll = $this->bm->dataSelectDb1($qry_sum);
			$tot_sum = $sumAll[0]['amt'];

			$qry_qday = "select IFNULL(SUM(qday),0) as qday from shed_bill_details
						where verify_no='$billVerify' AND gl_code not in('501005','502000N','503000N')";
			$qdayAll = $this->bm->dataSelectDb1($qry_qday);
			$tot_qday = $qdayAll[0]['qday'];

			$sqlrcvdate="SELECT recv_by,DATE(recv_time) AS recv_time FROM bank_bill_recv WHERE bill_no='$shedbill'";
			$rtnrcvdate = $this->bm->dataSelectDb1($sqlrcvdate); //ok query

			$recv_by=$rtnrcvdate[0]['recv_by'];
			$recv_time=$rtnrcvdate[0]['recv_time'];

			$this->data['rtnContainerList']=$rtnContainerList;
			$this->data['chargeList']=$chargeList;

			$this->data['title']="Shed Bill";
			$this->data['verify_number']=$billVerify;
			$this->data['rcvstat']=$rcvstat;
			$this->data['cpnoview']=$cpnoview;
			$this->data['cpbankname']=$cpbankname;
			$this->data['bill_clerk']=$bill_clerk;
			$this->data['unit_no']=$unit_no;
			$this->data['cpa_vat_reg_no']=$cpa_vat_reg_no;
			$this->data['ex_rate']=$ex_rate;
			$this->data['recv_time']=$recv_time;
			$this->data['recv_by']=$recv_by;
			$this->data['tot_sum']=$tot_sum;
			$this->data['tot_qday']=$tot_qday;
			$this->data['bill_print_times']=1;

			$this->load->view('cssAssetsList');
			$this->load->view('shedBillPdfOutputPrint',$this->data); 
			$this->load->view('jsAssetsList.php');
		}else{
			$login_id = $this->session->userdata('login_id');
			$billVerify = "";
			if($this->input->post('sendVerifyNo'))
			{
				$billVerify=$this->input->post('sendVerifyNo');
			}
			else if($this->input->post('verify_num'))
			{
				$billVerify=$this->input->post('verify_num');
			}
			else{
				$billVerify=str_replace("_","/",$this->uri->segment(3));
			}
		   
			$strBankPaymentInfo = "select shed_bill_master.bill_no,bill_rcv_stat,cp_bank_code,user,
			concat(cp_bank_code,cp_unit,'/',right(cp_year,2),'-',concat(if(length(cp_no)=1,'000',if(length(cp_no)=2,'00',if(length(cp_no)=3,'0',''))),cp_no)) as cp_no
			from shed_bill_master 
			left join bank_bill_recv on bank_bill_recv.bill_no=shed_bill_master.bill_no
			where verify_no='$billVerify'";
			$rtnBankPaymentInfo = $this->bm->dataSelectDb1($strBankPaymentInfo);   //ok query
			
			$rcvstat=$rtnBankPaymentInfo[0]['bill_rcv_stat'];
			$cpnoview=$rtnBankPaymentInfo[0]['cp_no'];
			$cpbankcode=$rtnBankPaymentInfo[0]['cp_bank_code'];
			$shedbill=$rtnBankPaymentInfo[0]['bill_no'];
			$bill_clerk=$rtnBankPaymentInfo[0]['user'];

			if($cpbankcode=="OB")
				$cpbankname="ONE BANK LIMITED";

			$str="select concat(right(YEAR(bill_date),2),'/',
									concat(if(length(bill_generation_no)=1,'00000',if(length(bill_generation_no)=2,'0000',if(length(bill_generation_no)=3,'000',
									if(length(bill_generation_no)=4,'00',if(length(bill_generation_no)=5,'0',''))))),bill_generation_no)) as bill_no,verify_no,unit_no,cpa_vat_reg_no,ex_rate,bill_date,arraival_date,import_rotation,vessel_name,
					cl_date,bl_no,wr_date,wr_upto_date,importer_vat_reg_no,importer_name,cnf_lic_no,cnf_agent,be_no,
					be_date,ado_no,ado_date,ado_valid_upto,manifest_qty,cont_size,cont_height from shed_bill_master 
					where verify_no='$billVerify'";
			$rtnContainerList = $this->bm->dataSelectDb1($str);  
			$unit_no=$rtnContainerList[0]['unit_no'];
			$cpa_vat_reg_no=$rtnContainerList[0]['cpa_vat_reg_no'];
			$ex_rate=$rtnContainerList[0]['ex_rate'];

			$qry="select verify_no,bill_no,gl_code,description,tarrif_rate,Qty,qday,amt,vatTK,mlwfTK from shed_bill_details
						where verify_no='$billVerify'";
			$chargeList = $this->bm->dataSelectDb1($qry); 

			$qry_sum="select SUM(amt) as amt from shed_bill_details
						where verify_no='$billVerify'";  
			$sumAll = $this->bm->dataSelectDb1($qry_sum);
			$tot_sum = $sumAll[0]['amt'];

			$qry_qday = "select IFNULL(SUM(qday),0) as qday from shed_bill_details
						where verify_no='$billVerify' AND gl_code not in('501005','502000N','503000N')";
			$qdayAll = $this->bm->dataSelectDb1($qry_qday);
			$tot_qday = $qdayAll[0]['qday'];

			$sqlrcvdate="SELECT recv_by,DATE(recv_time) AS recv_time FROM bank_bill_recv WHERE bill_no='$shedbill'";
			$rtnrcvdate = $this->bm->dataSelectDb1($sqlrcvdate); //ok query

			$recv_by=$rtnrcvdate[0]['recv_by'];
			$recv_time=$rtnrcvdate[0]['recv_time'];

			$this->data['rtnContainerList']=$rtnContainerList;
			$this->data['chargeList']=$chargeList;

			$this->data['title']="Shed Bill";
			$this->data['verify_number']=$billVerify;
			$this->data['rcvstat']=$rcvstat;
			$this->data['cpnoview']=$cpnoview;
			$this->data['cpbankname']=$cpbankname;
			$this->data['bill_clerk']=$bill_clerk;
			$this->data['unit_no']=$unit_no;
			$this->data['cpa_vat_reg_no']=$cpa_vat_reg_no;
			$this->data['ex_rate']=$ex_rate;
			$this->data['recv_time']=$recv_time;
			$this->data['recv_by']=$recv_by;
			$this->data['tot_sum']=$tot_sum;
			$this->data['tot_qday']=$tot_qday;
			$this->data['bill_print_times']=1;

			//$this->load->view('cssAssetsList');
			//$this->load->view('shedBillPdfOutput',$this->data); 
			//$this->load->view('jsAssetsList.php');
			
			$this->load->library('m_pdf');
			//$mpdf->use_kwt = true;
			
			$html=$this->load->view('shedBillPdfOutput',$this->data, true); 

			$pdfFilePath ="shedBillPdfOutput-".time()."-download.pdf";

			$pdf = $this->m_pdf->load();

			// $pdf->SetWatermarkText('CPA CTMS');
			// $pdf->showWatermarkText = true;

			$pdf->useSubstitutions = true; 
				
			//$pdf->setFooter('Prepared By : '.$user.'|Page {PAGENO}|Date {DATE j-m-Y}');

			//Following 1 line is used for debugging the error:- "HTML contains invalid UTF-8 character(s)"
			$html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
			
			$pdf->WriteHTML($html,2);
				
			$pdf->Output($pdfFilePath, "I");

		}

    }


	function shedreceive()
	{
		$verifyno=$this->input->post('verifyno');  
		$shedbill=$this->input->post('shedbill');
			
		$sql="UPDATE shed_bill_master
		SET bill_rcv_stat = '1'
		WHERE verify_no='$verifyno'"; 
				  
		$update = $this->bm->dataInsertDB1($sql);   //reopen
		
		//cpno start
		
		$sqlcpnoyear="SELECT IFNULL(MAX(cp_no),0) AS cp_no,YEAR(NOW()) AS year FROM bank_bill_recv WHERE cp_year=YEAR(NOW())";
		$rtncpnoyear = $this->bm->dataSelectDb1($sqlcpnoyear);
		$rtncpno=$rtncpnoyear[0]['cp_no'];
		$cpno=$rtncpno+1;
		$cpyear=$rtncpnoyear[0]['year'];
		$login_id = $this->session->userdata('login_id');
		
		$cpbankcode=$this->session->userdata('section'); //from session
		 
		$sqlunit="SELECT section AS rtnValue
		FROM users 
		INNER JOIN shed_bill_master ON shed_bill_master.user=users.login_id 
		WHERE shed_bill_master.verify_no='$verifyno'";
		$rtnunit = $this->bm->dataReturnDb1($sqlunit);
		
		$cpunit=$rtnunit;
	
		$sqlbankinsert="INSERT INTO bank_bill_recv(bill_no,cp_no,cp_year,cp_bank_code,cp_unit,recv_by,recv_time) 
		VALUES('$shedbill','$cpno','$cpyear','$cpbankcode','$cpunit','$login_id',now())"; //reopen
		
	 	$rsltbankinsert = $this->bm->dataInsertDB1($sqlbankinsert);  //reopen 
		
		//LCL Dlv Assignment Update Starts...............
		$sqlcpno="SELECT gkey,bill_no,cp_no,RIGHT(cp_year,2) AS cp_year,cp_bank_code,cp_unit FROM bank_bill_recv 
					WHERE bill_no='$shedbill'";
		$rescpno = $this->bm->dataSelectDb1($sqlcpno);
		$cntCPno = count($rescpno);
		
		$cpbankcode= "";
		$cpno= "";
		$cpyear= "";
		$cpunit= "";
		$num_length= "";
		$newcpno= "";
		$cpnoview= "";
		$cpno= "";
		for($i=0;$i<count($rescpno);$i++)
		{
			$cpbankcode=$rescpno[$i]['cp_bank_code'];
			$cpno=$rescpno[$i]['cp_no'];
			$cpyear=$rescpno[$i]['cp_year'];
			$cpunit=$rescpno[$i]['cp_unit'];
		}
		$num_length = strlen($cpno);
		if($num_length == 4)
		{
		   $newcpno=$cpno;
		}
		else if($num_length == 3)
		{
		   $newcpno="0".$cpno;
		}
		else if($num_length == 2)
		{
		   $newcpno="00".$cpno;
		}
		else if($num_length == 1)
		{
		   $newcpno="000".$cpno;
		}
		
		if($cpbankcode!="" && $cpno!="")
		{
		   $cpnoview=$cpbankcode.$cpunit."/".$cpyear."-".$newcpno;
		   $cpno=$cpnoview;
		}
		$updateLCLDlvAssignment="UPDATE lcl_dlv_assignment SET cp_no='$cpno'
							WHERE verify_num = '$verifyno'";
		$updateSt = $this->bm->dataInsertDB1($updateLCLDlvAssignment);
		//LCL Dlv Assignment Update Ends.................

		$sqlbillno = "SELECT shed_bill_master.bill_no AS bn,CONCAT(RIGHT(YEAR(bill_date),2),'/',CONCAT(IF(LENGTH(bill_generation_no)=1,'00000',IF(LENGTH(bill_generation_no)=2,'0000',IF(LENGTH(bill_generation_no)=3,'000',
		IF(LENGTH(bill_generation_no)=4,'00',IF(LENGTH(bill_generation_no)=5,'0',''))))),bill_generation_no)) AS bill_no,shed_bill_master.verify_no,unit_no,import_rotation,cnf_agent,SUM(amt) AS total_amt,SUM(vatTk) AS total_vat,total_port,total_mlwf,rcv_delete_stat 
		FROM shed_bill_details INNER JOIN shed_bill_master 
		ON shed_bill_master.bill_no=shed_bill_details.bill_no 
		GROUP BY shed_bill_master.bill_no ORDER BY bill_no DESC";
				
		$rtnbillno = $this->bm->dataSelectDb1($sqlbillno);
		$data['rtnbillno']=$rtnbillno;
	//	$data['cpnoview']=$cpnoview;
		$data['shedbill']=$shedbill;
		
		$data['title']="SHED BILL LIST FORM...";
			
		$this->load->view('cssAssetsList');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('shedBillListForm',$data);
		$this->load->view('jsAssetsList');
	}
	
	
	function dltRcvBill()
	{
		$login_id = $this->session->userdata('login_id');
		
		$verifyno = $this->input->post('verifyno');
		$shedbill = $this->input->post('shedbill');
		
		$sql_chkDltRcvStat = "SELECT rcv_delete_stat
		FROM shed_bill_master
		WHERE verify_no='$verifyno'";
		$rslt_chkDltRcvStat = $this->bm->dataSelectDB1($sql_chkDltRcvStat);
		
		if(count($rslt_chkDltRcvStat)>0)
		{
			$chkDltRcvStat=$rslt_chkDltRcvStat[0]['rcv_delete_stat'];
			
			if($chkDltRcvStat==0)
			{
				$sql_updateRcvDltStat = "UPDATE shed_bill_master
				SET rcv_delete_stat='1',rcv_delete_by='$login_id',rcv_delete_time=NOW()
				WHERE verify_no='$verifyno'";
				$this->bm->dataUpdateDB1($sql_updateRcvDltStat);
			}
		}
						
		$this->shedBillListForm();
	}
	
	function dltRcvRqstList()		// for one stop
	{
		$sql_dltRcvRqst = "SELECT shed_bill_master.bill_no AS bn,CONCAT(RIGHT(YEAR(bill_date),2),'/',CONCAT(IF(LENGTH(bill_generation_no)=1,'00000',IF(LENGTH(bill_generation_no)=2,'0000',IF(LENGTH(bill_generation_no)=3,'000',IF(LENGTH(bill_generation_no)=4,'00',IF(LENGTH(bill_generation_no)=5,'0',''))))),bill_generation_no)) AS bill_no,verify_no,cnf_lic_no,cnf_agent,rcv_delete_by,rcv_delete_time
		FROM shed_bill_master
		WHERE rcv_delete_stat='1'";
		$rslt_dltRcvRqst = $this->bm->dataSelectDb1($sql_dltRcvRqst);
		
		$data['rslt_dltRcvRqst']=$rslt_dltRcvRqst;
				
		$data['title']="Bill List to Demove Receive...";

		$this->load->view('cssAssetsList');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('deleteReceiveRequestList',$data);
		$this->load->view('jsAssetsList');
	}
	
	function acceptDltRqst()
	{
		$login_id = $this->session->userdata('login_id');
		
		$verifyno = $this->input->post('verifyno');
		$shedbill = $this->input->post('shedbill');
		
		// check validation later
		$sql_chkExist = "SELECT COUNT(*) AS rtnValue
		FROM shed_bill_master
		WHERE verify_no='$verifyno' AND bill_rcv_stat='1'";
		$chkExist = $this->bm->dataReturnDB1($sql_chkExist);	
		
		if($chkExist==1)
		{
			$sql_updateRcvStat = "UPDATE shed_bill_master
			SET bill_rcv_stat='0',rcv_delete_stat='0'
			WHERE verify_no='$verifyno'";
			$sql_updateRcvStat = $this->bm->dataUpdateDB1($sql_updateRcvStat);
			
			$sql_dltBillRcv = "DELETE FROM bank_bill_recv WHERE bill_no='$shedbill'";
			$this->bm->dataDeleteDB1($sql_dltBillRcv);
			
			$sql_chkLclDlv = "SELECT COUNT(*) AS rtnValue
			FROM lcl_dlv_assignment
			WHERE verify_num='$verifyno'";
			$chkLclDlv = $this->bm->dataReturnDB1($sql_chkLclDlv);
			
			if($chkLclDlv>0)
			{
				$sql_updateCpNo = "UPDATE lcl_dlv_assignment
				SET cp_no=NULL
				WHERE verify_num='$verifyno'";
			}
		}				
		
		$this->dltRcvRqstList();			
	}
	
	//-----SHED BILL LIST end

		//-----Bill Delete start
	function billDeletePerform()
	{
		$session_id = $this->session->userdata('value');
		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();
				
		}
		else
		{
			$verify_no=$this->input->post('vrfno');
			$bill_no=$this->input->post('sdbillno');
			
			$sql_delete_master="DELETE FROM shed_bill_master
						WHERE bill_no='$bill_no' AND verify_no='$verify_no'";
						
			$rslt_delete_master = $this->bm->dataDeleteDB1($sql_delete_master);
			
			$sql_delete_details="DELETE FROM shed_bill_details
						WHERE bill_no='$bill_no' AND verify_no='$verify_no'";
						
			$rslt_delete_details = $this->bm->dataDeleteDB1($sql_delete_details);
			
			$sqlbillno = "SELECT shed_bill_master.bill_no AS bn,CONCAT(RIGHT(YEAR(bill_date),2),'/',CONCAT(IF(LENGTH(bill_generation_no)=1,'00000',IF(LENGTH(bill_generation_no)=2,'0000',IF(LENGTH(bill_generation_no)=3,'000',IF(LENGTH(bill_generation_no)=4,'00',IF(LENGTH(bill_generation_no)=5,'0',''))))),bill_generation_no)) AS bill_no,shed_bill_master.verify_no,unit_no,import_rotation,cnf_agent,SUM(amt) AS total_amt,SUM(vatTk) AS total_vat,total_port,total_mlwf 
			FROM shed_bill_details 
			INNER JOIN shed_bill_master ON shed_bill_master.bill_no=shed_bill_details.bill_no 
			GROUP BY shed_bill_master.bill_no ORDER BY bill_no DESC";
		
			$rtnbillno = $this->bm->dataSelectDb1($sqlbillno);
			
			$data['rtnbillno']=$rtnbillno;
			$data['title']="SHED BILL LIST FORM...";
		    $this->load->view('cssAssets');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
			$this->load->view('shedBillListForm',$data);
			 $this->load->view('jsAssets');
		}
	}
	//-----Bill Delete end
	
	//faysal 5/10/2020
    function shedBillHeadWiseSummaryRptForm()
    {
        $session_id = $this->session->userdata('value');
        if($session_id!=$this->session->userdata('session_id'))
        {
            $this->logout();

        }
        else
        {
            $data['title']="HEAD WISE SUMMARY FOR SHED BILL";
            $this->load->view('cssAssets');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('shedBillHeadWiseSummaryRptForm',$data);
            $this->load->view('jsAssets');
        }
    }
	
    function shedBillHeadWiseSummaryRptView()
    {
        if($this->uri->segment(3)){
            $from_dt = $this->uri->segment(4);
            $to_dt = $this->uri->segment(5);
            $unitNo = $this->uri->segment(6);
            $data['from_dt']=$from_dt;
            $data['to_dt']=$to_dt;
            $data['unitNo']=$unitNo;
            $data['title']="UNIT NO ".$unitNo." FROM : ".$from_dt." TO : ".$to_dt;
            $this->load->view('cssAssetsList');
            $this->load->view('shedBillHeadWiseSummaryRptViewPrint',$data);
            $this->load->view('jsAssetsList');


        }else{
            $login_id = $this->session->userdata('login_id');
            $data['login_id']=$login_id;

            $from_dt=$this->input->post('fromdate');
            $to_dt=$this->input->post('todate');
            $unitNo=$this->input->post('unitNo');
            $data['from_dt']=$from_dt;
            $data['to_dt']=$to_dt;
            $data['unitNo']=$unitNo;
            $data['title']="UNIT NO ".$unitNo." FROM : ".$from_dt." TO : ".$to_dt;
            $this->load->view('cssAssetsList');
            $this->load->view('shedBillHeadWiseSummaryRptView',$data);
            $this->load->view('jsAssetsList');
        }

    }
	 //faysal 5/10/2020

    function shedBillSummaryRptForm()
    {
        $session_id = $this->session->userdata('value');
        if($session_id!=$this->session->userdata('session_id'))
        {
            $this->logout();

        }
        else
        {
            $data['title']="SUMMARY FOR SHED BILL";
            $this->load->view('cssAssets');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('shedBillSummaryRptForm',$data);
            $this->load->view('jsAssets');
        }
    }

    function shedBillSummaryRptView()
    {


        if($this->uri->segment(3)){
            $login_id = $this->session->userdata('login_id');
            $data['login_id']=$login_id;

            $from_dt = $this->uri->segment(4);
            $to_dt = $this->uri->segment(5);

            $data['from_dt']=$from_dt;
            $data['to_dt']=$to_dt;

            $data['title']="FROM : ".$from_dt." TO : ".$to_dt;
            $this->load->view('cssAssetsList');
            $this->load->view('shedBillSummaryRptViewPrint',$data);
            $this->load->view('jsAssetsList');


        }else{
            $login_id = $this->session->userdata('login_id');
            $data['login_id']=$login_id;

            $from_dt=$this->input->post('fromdate');
            $to_dt=$this->input->post('todate');

            $data['from_dt']=$from_dt;
            $data['to_dt']=$to_dt;

            $data['title']="FROM : ".$from_dt." TO : ".$to_dt;
            $this->load->view('cssAssetsList');
            $this->load->view('shedBillSummaryRptView',$data);
            $this->load->view('jsAssetsList');
        }
    }
   //P Shed Tally Report Starts------
		function pShedTallyReportPdf()
		{ 
				//load mPDF library
			$this->load->library('m_pdf');
			//$mpdf->use_kwt = true;
			
			if($this->input->post('rotation') && $this->input->post('container'))
			{
				$rotation=$this->input->post('rotation');
				$container=$this->input->post('container');
			}
			/*else if($_GET["rotation"] && $_GET["cont"])
			{
				$rotation=$_GET["rotation"];
				$container=$_GET["cont"];
			}*/
			else if($this->uri->segment(3) != null && $this->uri->segment(4) != null)
			{
				$rotation=str_replace("_","/",$this->uri->segment(3));
				$container=str_replace("_","/",$this->uri->segment(4));
			}
			else{
				$rotation=$rot;
				$container=$cont;
			}
			
			$section = $this->session->userdata('section');
			$login_id = $this->session->userdata('login_id');
				
			  $sqlinfo = "SELECT  id,(SELECT Vessel_Name FROM igm_masters WHERE Import_Rotation_No= tmp.import_rotation) AS Vessel_Name,import_rotation as Import_Rotation_No,cont_number,cont_seal_number,cont_size,tally_sheet_number,rcv_pack,rcv_unit,loc_first,flt_pack,shed_loc,Line_No,Pack_Marks_Number,Description_of_Goods,Pack_Number,
			(SUM(rcv_pack)+IFNULL(loc_first,0)) AS totPkg,actual_marks,marks_state,
			(SELECT SUM(delv_pack) FROM do_information WHERE verify_no=tmp.verify_number) AS delv_pack,shift_name,wr_date,Notify_name,mlocode, shed_yard, berthOp 
			FROM(SELECT shed_tally_info.igm_sup_detail_id as id,rcv_unit,igm_supplimentary_detail.Line_No,shed_tally_info.import_rotation,igm_sup_detail_container.cont_number,cont_seal_number,Vessel_Name,cont_size,igm_supplimentary_detail.Pack_Marks_Number,igm_supplimentary_detail.Description_of_Goods,igm_supplimentary_detail.Pack_Number,rcv_pack,loc_first,actual_marks,marks_state,shed_tally_info.verify_number,shed_tally_info.flt_pack,shed_loc,tally_sheet_number,shift_name,shed_tally_info.wr_date,shed_tally_info.shed_yard, shed_tally_info.berthOp,
			(select Organization_Name from organization_profiles where id=igm_supplimentary_detail.Submitee_Org_Id) as Notify_name,igm_details.mlocode
			FROM  shed_tally_info
			LEFT JOIN igm_supplimentary_detail ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
			LEFT JOIN igm_details ON igm_details.id=igm_supplimentary_detail.igm_detail_id
			LEFT JOIN igm_masters ON igm_masters.id=igm_supplimentary_detail.igm_master_id
			LEFT JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id		
			WHERE shed_tally_info.import_rotation='$rotation' AND shed_tally_info.cont_number='$container') AS tmp GROUP BY id";
			$rtninfo = $this->bm->dataSelectDb1($sqlinfo);
			
			$loopCounter="select * from (SELECT igm_supplimentary_detail.id,master_BL_No,Description_of_Goods,Import_Rotation_No,BL_No,cont_number,
			cont_size,Cont_gross_weight,cont_seal_number,Pack_Description,Pack_Marks_Number,Pack_Number,ConsigneeDesc,NotifyDesc 
			FROM igm_supplimentary_detail 
			LEFT JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id					
			WHERE Import_Rotation_No='$rotation' AND cont_number='$container'
			) tbl1
			union
			select * from (SELECT shed_tally_info.igm_sup_detail_id as id,master_BL_No,Description_of_Goods,import_rotation as Import_Rotation_No,BL_No,
			shed_tally_info.cont_number, cont_size,Cont_gross_weight,cont_seal_number,Pack_Description,actual_marks,Pack_Number,ConsigneeDesc,
			NotifyDesc FROM shed_tally_info 
			LEFT JOIN igm_supplimentary_detail ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id 
			LEFT JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
			WHERE shed_tally_info.import_rotation='$rotation' and shed_tally_info.cont_number='$container' and BL_NO is null
			)tbl2";
			
			$rtnCounter = $this->bm->dataSelectDb1($loopCounter);
			
			$sqlBerth="SELECT IFNULL(flex_string03,flex_string02) AS berthOp,DATE(sparcsn4.argo_carrier_visit.ata) AS ata
			FROM sparcsn4.vsl_vessel_visit_details 
			INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.cvcvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
			WHERE ib_vyg='$rotation'";
			//echo $sqlBerth;
			$rsltBerth=$this->bm->dataSelect($sqlBerth);
			
			$sqlSigQuery="select distinct signature_path_berth,signature_path_freight,signature_path_cpa from shed_tally_info 
			where import_rotation='$rotation' and cont_number='$container'";
				
			$rsltSig=$this->bm->dataSelectDb1($sqlSigQuery);
			$signature_path_berth=$rsltSig[0]['signature_path_berth'];
			$signature_path_freight=$rsltSig[0]['signature_path_freight'];
			$signature_path_cpa=$rsltSig[0]['signature_path_cpa'];
			
			$this->data['rotation']=$rotation;
			$this->data['container']=$container;
			$this->data['section']=$section;
			$this->data['rtninfo']=$rtninfo;
			$this->data['rtnCounter']=$rtnCounter;
			$this->data['rsltBerth']=$rsltBerth;
			$this->data['counter']=@$counter;
			$this->data['signature_path_berth']=$signature_path_berth;
			$this->data['signature_path_freight']=$signature_path_freight;
			$this->data['signature_path_cpa']=$signature_path_cpa;

			$html=$this->load->view('pShedTallyReportPdfOutput',$this->data, true); //load the pdf_output.php by passing our data and get all data in $html varriable.
			 
			$pdfFilePath ="tallyReport-".time()."-download.pdf";

			$pdf = $this->m_pdf->load();
			$pdf->allow_charset_conversion = true;
			$pdf->charset_in = 'iso-8859-4';
			$stylesheet = file_get_contents('assets/stylesheets/test.css'); // external css
			//	$pdf->useSubstitutions = true; // optional - just as an example
				
			//$pdf->setFooter('Prepared By : '.$login_id.'|Page {PAGENO} of {nb}|Date {DATE j-m-Y}');
				
			$pdf->WriteHTML($stylesheet,1);
			$pdf->WriteHTML($html,2);
				 
			$pdf->Output($pdfFilePath, "I"); // For Show Pdf
		}
	//P Shed Tally Report Ends--------

	function truckEntranceApplicationForm()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$data['title']="Print Gate Pass";
			$data['msg'] = "";
			
            $this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('truckEntranceApplicationForm',$data);
			$this->load->view('jsAssetsList');
		}
	}
	

	function truckEntranceApplicationPDF()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {	
			$rot_no = $this->input->post("rot_no");
			$cont_no = $this->input->post("cont_no");
			$trucVisitId = $this->input->post("trucVisitId");

			$data['title']="Truck Entrance Application";

			
			$this->data['rot_no'] = $rot_no;
			$this->data['cont_no'] = $cont_no;
			$this->data['trucVisitId'] = $trucVisitId;

			$this->load->library('m_pdf');
			$html=$this->load->view('truckEntranceApplicationPDF',$this->data, true); //load the pdf_output.php by passing our data and get all data in $html varriable.
			 
			$pdfFilePath ="truckEntranceApplicationPDF-".time()."-download.pdf";

			$pdf = $this->m_pdf->load();
			$stylesheet = file_get_contents('assets/stylesheets/test.css'); // external css

				
			$pdf->WriteHTML($stylesheet,1);
			$pdf->WriteHTML($html,2);
				 
			$pdf->Output($pdfFilePath, "I"); // For Show Pdf
			
            $this->load->view('truckEntranceApplicationPDF',$data);

		}
	}

	function vehicleGatePass()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		$login_id = $this->session->userdata('login_id');
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {	
			$rot_no = $this->input->post("rot_no");
			$cont_no = $this->input->post("cont_no");
			$trucVisitId = $this->input->post("trucVisitId");

			$data['title']="Truck Entrance Application";
			$data['rot_no'] = $rot_no;
			$data['cont_no'] = $cont_no;
			$data['trucVisitId'] = $trucVisitId;
			$data['login_id'] = $login_id;

			$this->load->view('vehiclaGatePass',$data);
			

		}
	}

	
	
	// function truckEntranceApplicationPDF()
	// {
		// $session_id = $this->session->userdata('value');
        // $LoginStat = $this->session->userdata('LoginStat');
		
		// if($LoginStat!="yes")
        // {
            // $this->logout();
        // }
        // else
        // {	
			// $rot_no = $this->input->post("rot_no");
			// $cont_no = $this->input->post("cont_no");
			// $trucVisitId = $this->input->post("trucVisitId");

			// $data['title']="Truck Entrance Application";
			
			// $this->data['rot_no'] = $rot_no;
			// $this->data['cont_no'] = $cont_no;
			// $this->data['trucVisitId'] = $trucVisitId;

			// $this->load->library('m_pdf');
			// $html=$this->load->view('truckEntranceApplicationPDF',$this->data, true); //load the pdf_output.php by passing our data and get all data in $html varriable.
			 
			// // $pdfFilePath ="truckEntranceApplicationPDF-".time()."-download.pdf";
			
			// $pdfFilePath =$_SERVER['DOCUMENT_ROOT']."/pcs/resources/vcmsEmail/".$trucVisitId."_Gate_Pass.pdf";

			// $pdf = $this->m_pdf->load();
			// $pdf->allow_charset_conversion = true;
			// $pdf->charset_in = 'iso-8859-4';
			// $stylesheet = file_get_contents('assets/stylesheets/test.css'); // external css

				
			// $pdf->WriteHTML($stylesheet,1);
			// $pdf->WriteHTML($html,2);
				 
			// // $pdf->Output($pdfFilePath, "I"); // For Show Pdf
			// $pdf->Output($pdfFilePath, "F"); // For Save Pdf
			
			// // email send - start
			// $subject="Shedbill";
			// $body="Please check the attached files.";
			// $emailClient="intakhab.chy@gmail.com";
			// // $sendEmail=$sendEmailController->sendEmail($subject,$body,$emailClient,$pdfFilePath);
			
			// $this->sendEmail($subject,$body,$emailClient,$pdfFilePath);
			// // email send - end
			
            // $this->load->view('truckEntranceApplicationPDF',$data);

		// }
	// }

	function printAllGatePass(){
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$this->data['title']="Truck Entrance Application";
			$this->load->library('m_pdf');
			$html=$this->load->view('printAllGatePass',$this->data, true); //load the pdf_output.php by passing our data and get all data in $html varriable.
			 
			$pdfFilePath ="printAllGatePass-".time()."-download.pdf";

			$pdf = $this->m_pdf->load();
			// $pdf->allow_charset_conversion = true;
			// $pdf->charset_in = 'iso-8859-4';
			$stylesheet = file_get_contents('assets/stylesheets/test.css'); // external css

				
			$pdf->WriteHTML($stylesheet,1);
			$pdf->WriteHTML($html,2);
				 
			$pdf->Output($pdfFilePath, "I"); // For Show Pdf
			
			// $this->load->view('printAllGatePass',$data);
		}
	}

	//Token --End
	
	//Assignment List View Details Starts----------------------------
	function viewDtlsByVerifyNumber()
	{
		$session_id = $this->session->userdata('value');
		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();
		}
		else
		{	
			$search = 1;
			$data['search']=$search;
			
			$data['title']="TRUCK DETAILS";
			$rotNo="";
			$contNo="";
			$verifyNo="";
		
			if($this->uri->segment(3) != null )
			{
				$verifyNo = $this->uri->segment(3);
			}
			else
			{
				$verifyNo = $this->input->post('verifyNo');
				$cont_status = $this->input->post('cont_status');
				$rotNo = $this->input->post('rotNo');
				$contNo = $this->input->post('contNo');
			}
			
			$data['cont_status']=$cont_status;
			$fclFlagValue=0;
			if($cont_status=="FCL")
			{  								//===========================================================FCL Block
                $fclFlagValue = 1;
						
				$doReportQuery ="SELECT count(verify_number) as total, sum(delv_pack) as total_do_pack, count(truck_id) as total_truck_assign 
		                from do_truck_details_entry where verify_number='$verifyNo'";
                $checkDo = $this->bm->dataSelectDb1($doReportQuery);

                $deliverd_truck = $checkDo[0]['total_truck_assign'];            

				$sql_contNumber="";
				
				if($verifyNo == "")
				{
					$sql_contNumber="SELECT DISTINCT cont_number FROM(
					SELECT igm_detail_container.cont_number 
					FROM igm_detail_container 
					INNER JOIN igm_details ON igm_details.id=igm_detail_container.igm_detail_id 
					LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_cont_id=igm_detail_container.id 
					WHERE igm_details.Import_Rotation_No='$rotNo' AND igm_detail_container.cont_number='$contNo') AS tbl";
				}
				else
				{
					$sql_contNumber="SELECT DISTINCT cont_number FROM(SELECT igm_detail_container.cont_number
					FROM igm_details
					INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
					INNER JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
					WHERE verify_info_fcl.verify_number='$verifyNo') AS tbl";
				}
				
				$rslt_contNumber=$this->bm->dataSelectDb1($sql_contNumber);
				
				for($i=0;$i<count($rslt_contNumber);$i++)
				{
					$rsltTmp[]=$rslt_contNumber[$i]['cont_number'];
				}
				
				$containerSet=join(", ",$rsltTmp);
				
				
				$data['containerSet']=$containerSet;
				
				// 2020-04-30 - intakhab - end

				// 2021-01-13 - comment - intakhab				
				
				if($verifyNo == "")
				{
					$verifyReport = "SELECT igm_details.id AS igmDetailId,igm_detail_container.id AS igm_dtl_cont_id,shed_bill_master.bill_no,verify_info_fcl.verify_number,verify_info_fcl.verify_unit AS unit_no,igm_details.Import_Rotation_No AS import_rotation, igm_masters.Vessel_Name AS vessel_name,igm_details.BL_No AS bl_no, igm_details.Description_of_Goods,Qty,bill_rcv_stat,IF(bill_rcv_stat=1,'Paid','Not Paid') AS paid_status,igm_detail_container.cont_number, igm_details.Pack_Number,igm_details.Pack_Description, verify_info_fcl.no_of_truck,verify_info_fcl.keepdown_st 
					FROM igm_detail_container 
					LEFT JOIN igm_details ON igm_details.id=igm_detail_container.igm_detail_id
					LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_cont_id=igm_detail_container.id
					LEFT JOIN shed_bill_master ON shed_bill_master.verify_no = verify_info_fcl.verify_number 
					LEFT JOIN shed_bill_details ON shed_bill_master.bill_no =shed_bill_details.bill_no
					LEFT JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
					WHERE igm_details.Import_Rotation_No='$rotNo' AND igm_detail_container.cont_number='$contNo'";
				}
				else
				{	
					$verifyReport = "SELECT igm_details.id AS igmDetailId,igm_detail_container.id AS igm_dtl_cont_id,shed_bill_master.bill_no,verify_info_fcl.verify_number,verify_info_fcl.verify_unit AS unit_no,igm_details.Import_Rotation_No AS import_rotation, igm_masters.Vessel_Name AS vessel_name,igm_details.BL_No AS bl_no, igm_details.Description_of_Goods,Qty,bill_rcv_stat,IF(bill_rcv_stat=1,'Paid','Not Paid') AS paid_status,igm_detail_container.cont_number, igm_details.Pack_Number,igm_details.Pack_Description, verify_info_fcl.no_of_truck,verify_info_fcl.keepdown_st 
					FROM igm_details 
					INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
					INNER JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id 
					LEFT JOIN shed_bill_master ON shed_bill_master.verify_no = verify_info_fcl.verify_number 
					LEFT JOIN shed_bill_details ON shed_bill_master.bill_no =shed_bill_details.bill_no 
					INNER JOIN igm_masters ON igm_masters.id=igm_details.IGM_id 
					WHERE verify_info_fcl.verify_number='$verifyNo'";
				}		
				
				
				$rtnVerifyReport = $this->bm->dataSelectDb1($verifyReport);
				$truck_num=$rtnVerifyReport[0]['no_of_truck'];
				$data['rtnVerifyReport'] = $rtnVerifyReport;

                $rem_truck=$truck_num-$deliverd_truck;
                $data['deliverd_truck']=$deliverd_truck;
                $data['rem_truck']=$rem_truck;

                if($deliverd_truck>=0)
                {
                    // $doQuery="SELECT verify_no, delv_pack, truck_id, gate_no from do_information where verify_no='$verifyNo' order by id";
					
					if($verifyNo == "")
					{
						$doQuery = "SELECT DISTINCT truck_id,delv_pack, gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass 
						FROM do_truck_details_entry
						INNER JOIN igm_details ON igm_details.Import_Rotation_No=do_truck_details_entry.import_rotation
						INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
						WHERE do_truck_details_entry.import_rotation='$rotNo' AND cont_no='$contNo'";
					}
					else
					{
						$doQuery="SELECT verify_number, delv_pack, truck_id, gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass 
						FROM do_truck_details_entry 
						WHERE verify_number='$verifyNo' 
						ORDER BY id";
					}
                   
                    $doInfo = $this->bm->dataSelectDb1($doQuery);
                    $data['doInfo']=$doInfo;
                    $doShowFlag=1;
                    $data['doShowFlag']=$doShowFlag;
                    if($truck_num>$deliverd_truck)
                    {
                        $data['dlv_btn_status']=1;
                    }
                    else
                    {
                        //$dlv_btn_status=1;
                        $data['dlv_btn_status']=0;
                    }
                    $data['tblFlag']=1;
                }
				else
                {
                    $data['dlv_btn_status']=1;
                    $data['doShowFlag']=0;
                    $data['tblFlag']=1;
                }
            }
			else
			{                              //============================================================= LCL Block
                $fclFlagValue = 0;               

                // $doReportQuery ="SELECT count(verify_no) as total, sum(delv_pack) as total_do_pack, count(truck_id) as total_truck_assign 
		                // from do_information where verify_no='$verifyNo'";

				$doReportQuery ="SELECT count(verify_number) as total, sum(delv_pack) as total_do_pack, count(truck_id) as total_truck_assign 
		                from do_truck_details_entry where verify_number='$verifyNo'";
                $checkDo = $this->bm->dataSelectDb1($doReportQuery);

                $deliverd_truck = $checkDo[0]['total_truck_assign'];               

				$verifyReport = "SELECT shed_bill_master.bill_no, shed_tally_info.verify_number, unit_no,shed_tally_info.import_rotation,
				vessel_name,shed_bill_master.bl_no,igm_supplimentary_detail.Description_of_Goods,Qty, shed_loc, shed_yard,
				if(bill_rcv_stat=1,'Paid','Not Paid') as paid_status, cont_number,
				igm_supplimentary_detail.Pack_Number, igm_supplimentary_detail.Pack_Description,
				(shed_tally_info.rcv_pack+ ifnull(shed_tally_info.loc_first,0)) as rcv_pack,shed_tally_info.rcv_unit, no_of_truck
				FROM shed_tally_info 
				left JOIN shed_bill_master ON shed_tally_info.verify_number=shed_bill_master.verify_no
				left JOIN shed_bill_details ON shed_bill_master.bill_no =shed_bill_details.bill_no
				left JOIN verify_other_data ON shed_tally_info.id=verify_other_data.shed_tally_id
				inner join igm_supplimentary_detail on igm_supplimentary_detail.id=shed_tally_info.igm_sup_detail_id
				left join igm_details on igm_details.id=igm_supplimentary_detail.igm_detail_id
				WHERE shed_tally_info.verify_number='$verifyNo'
				GROUP BY bill_no";
				//query-1: shed_bill_master.unit_no ki?
				$rtnVerifyReport = $this->bm->dataSelectDb1($verifyReport);
				$truck_num=$rtnVerifyReport[0]['no_of_truck'];
				$data['rtnVerifyReport'] = $rtnVerifyReport;

                $rem_truck=$truck_num-$deliverd_truck;
                $data['deliverd_truck'] = $deliverd_truck;
                $data['rem_truck']=$rem_truck;

                if($deliverd_truck>=0)
                {
                    // $doQuery="SELECT verify_no, delv_pack, truck_id, gate_no from do_information where verify_no='$verifyNo' order by id";
                    $doQuery="SELECT verify_number, delv_pack, truck_id, gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass from do_truck_details_entry where verify_number='$verifyNo' order by id";
                    $doInfo = $this->bm->dataSelectDb1($doQuery);
                    $data['doInfo']=$doInfo;
                    $doShowFlag=1;
                    $data['doShowFlag']=$doShowFlag;
                    if($truck_num>$deliverd_truck)
                    {
                        $data['dlv_btn_status']=1;
                    }
                    else
                    {
                        //$dlv_btn_status=1;
                        $data['dlv_btn_status']=0;
                    }
                    $data['tblFlag']=1;
                }
				else
                {
                    $data['dlv_btn_status']=1;
                    $data['doShowFlag']=0;
                    $data['tblFlag']=1;
                }
            }
            $data['fclFlagValue'] = $fclFlagValue;		

		    $data['verifyNo']=$verifyNo;
		    $data['contNo']=$contNo;
		    
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('viewDtlsByVerifyNumber',$data);
			$this->load->view('jsAssets');
		}
	}
	function updateKeepDownStatus()
	{
		$session_id = $this->session->userdata('value');
		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();
		}
		else
		{
			$rotNo = $this->input->post('rotNo');
			$contNo = $this->input->post('contNo');
			
			
			$login_id = $this->session->userdata('login_id');
			$org_license = $this->session->userdata('org_license');
			$org_Type_id = $this->session->userdata('org_Type_id');
			
			$msg = "";
			
			if($this->input->post('changeState'))
			{
				$queryDtlContId = "SELECT igm_detail_container.id AS igm_dtl_cont_id,igm_details.id AS igm_detail_id 
				FROM igm_detail_container 
				LEFT JOIN igm_details ON igm_details.id=igm_detail_container.igm_detail_id
				LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_cont_id=igm_detail_container.id
				LEFT JOIN shed_bill_master ON shed_bill_master.verify_no = verify_info_fcl.verify_number 
				LEFT JOIN shed_bill_details ON shed_bill_master.bill_no =shed_bill_details.bill_no
				LEFT JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
				WHERE igm_details.Import_Rotation_No='$rotNo' AND igm_detail_container.cont_number='$contNo'";
				
				$resDtlContId = $this->bm->dataSelectDb1($queryDtlContId);
				$igm_dtl_cont_id=$resDtlContId[0]['igm_dtl_cont_id'];
				$igm_detail_id=$resDtlContId[0]['igm_detail_id'];
			}
			else
			{
				$igm_dtl_cont_id = $this->input->post('igm_dtl_cont_id');
				$igm_detail_id = $this->input->post('igmDtlId');
			}
			
			$queryCnt = "SELECT COUNT(*) AS rtnValue FROM verify_info_fcl WHERE igm_detail_cont_id='$igm_dtl_cont_id'";
			$resCnt = $this->bm->dataReturnDb1($queryCnt);
			if($resCnt==0)
			{
				$strInsert = "INSERT INTO verify_info_fcl(igm_detail_id,igm_detail_cont_id,rotation,cont_number,keepdown_st,keepdown_at,keepdown_by)
				VALUES('$igm_detail_id','$igm_dtl_cont_id','$rotNo','$contNo','1',NOW(),'$login_id')";
				$resInsert = $this->bm->dataInsertDB1($strInsert);
				if($resInsert==1)
				{
					$msg = "<font color='blue'>Successful</font>";
				}
				else
				{
					$msg = "<font color='red'>Failed</font>";
				}
			}
			else
			{
				$queryId = "SELECT id AS rtnValue FROM verify_info_fcl WHERE igm_detail_cont_id='$igm_dtl_cont_id'";					
				$resId = $this->bm->dataReturnDb1($queryId);
				
				$sql="UPDATE verify_info_fcl SET keepdown_st='1',keepdown_at=NOW(),keepdown_by='$login_id' WHERE id = '$resId'";
				$update = $this->bm->dataInsertDB1($sql);
				if($update==1)
				{
					$msg = "<font color='blue'>Successful</font>";
				}
				else
				{
					$msg = "<font color='red'>Failed</font>";
				}
			}
			
			// Insert into "vcms_keepdown_sms" table
			$queryVerifyFclId = "SELECT id AS rtnValue FROM verify_info_fcl WHERE igm_detail_cont_id='$igm_dtl_cont_id'";
			$resVerifyFclId = $this->bm->dataReturnDb1($queryVerifyFclId);
					
			$strQuery = "SELECT DISTINCT a.gkey,a.id AS cont_no,k.name  AS cnf, k.id AS cnf_lic,
			CONCAT(k.address_line1,IFNULL(k.address_line2,'')) AS cnf_addr,
			a.gkey, a.id AS cont_no, j.bl_nbr, k.gkey AS bizu_gkey, config_metafield_lov.mfdch_value,
			mfdch_desc,k.sms_number,
			(SELECT ctmsmis.cont_yard(b.last_pos_slot)) AS Yard_No, b.flex_date01,
			(SELECT ctmsmis.cont_block(b.last_pos_slot, Yard_No)) AS Block_No,
			(SELECT inv_unit_fcy_visit.flex_string10 FROM sparcsn4.inv_unit
			INNER JOIN sparcsn4.inv_unit_fcy_visit ON inv_unit_fcy_visit.`unit_gkey`=inv_unit.`gkey`
			WHERE inv_unit.id=a.id ORDER BY inv_unit_fcy_visit.flex_date01 DESC LIMIT 1) AS rot_no,
			(SELECT RIGHT(nominal_length, 2) FROM sparcsn4.ref_equip_type
			INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.eqtyp_gkey = sparcsn4.ref_equip_type.gkey
			INNER JOIN sparcsn4.inv_unit_equip ON sparcsn4.inv_unit_equip.eq_gkey = sparcsn4.ref_equipment.gkey
			WHERE sparcsn4.inv_unit_equip.unit_gkey = a.gkey)  AS size,
			CAST((SELECT (RIGHT(sparcsn4.ref_equip_type.nominal_height,2)/10) FROM sparcsn4.ref_equip_type
			INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.eqtyp_gkey = sparcsn4.ref_equip_type.gkey
			INNER JOIN sparcsn4.inv_unit_equip ON sparcsn4.inv_unit_equip.eq_gkey = sparcsn4.ref_equipment.gkey
			WHERE sparcsn4.inv_unit_equip.unit_gkey = a.gkey) AS DECIMAL(10,1))  AS height,
			(SELECT sparcsn4.vsl_vessels.name FROM sparcsn4.vsl_vessels
			INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vessel_gkey=sparcsn4.vsl_vessels.gkey
			WHERE sparcsn4.vsl_vessel_visit_details.ib_vyg=b.flex_string10 LIMIT 1) AS v_name
			FROM sparcsn4.inv_unit a
			INNER JOIN sparcsn4.inv_unit_fcy_visit b ON b.unit_gkey = a.gkey
			INNER JOIN sparcsn4.inv_goods j ON j.gkey = a.goods
			INNER JOIN vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.ib_vyg=b.flex_string10
			LEFT JOIN sparcsn4.ref_bizunit_scoped k ON k.gkey = j.consignee_bzu
			INNER JOIN sparcsn4.config_metafield_lov ON a.flex_string01 = config_metafield_lov.mfdch_value
			WHERE a.id='$contNo' AND sparcsn4.vsl_vessel_visit_details.ib_vyg ='$rotNo'";
			
			$rslt_strQuery=$this->bm->dataSelect($strQuery);			
			$data['rslt_strQuery']=$rslt_strQuery;
			
			$sms_number = $rslt_strQuery[0]['sms_number'];
			$cont_block = $rslt_strQuery[0]['Block_No'];
			$cont_yard = $rslt_strQuery[0]['Yard_No'];
			$assignment_type = $rslt_strQuery[0]['mfdch_desc'];
			$bizu_gkey = $rslt_strQuery[0]['bizu_gkey'];
			
			$strInsert = "INSERT INTO vcms_keepdown_sms(verify_fcl_id,sms_number,cont_block,cont_yard,assignment_type,assignment_date,
														bizu_gkey,created)
			VALUES('$resVerifyFclId','$sms_number','$cont_block','$cont_yard','$assignment_type',NOW(),'$bizu_gkey',NOW())";
			$resInsert = $this->bm->dataInsertDB1($strInsert);
			
			//Redirect to assignment list starts---------------------------------
			$data['msg'] = $msg;
			$cond = "";
			if($this->input->post('search'))
			{
				$searchBy = $this->input->post('searchBy');
				$value = $this->input->post('value');
				
				if($searchBy == "bl")
				{
					$cond = "";
				}
				else if($searchBy == "cont")
				{
					$cond = " AND a.id='$value'";
				}					
			}
			
			// $sql_cnfGkey = "SELECT gkey FROM sparcsn4.ref_bizunit_scoped WHERE id='$org_license'";		
			
			// $rslt_cnfGkey = $this->bm->dataSelect($sql_cnfGkey);
			// $cnfGkey = $rslt_cnfGkey[0]['gkey'];
			
			// $sql_assignmentList = "SELECT cont_no,rot_no,
			// (SELECT id FROM sparcsn4.ref_bizunit_scoped WHERE gkey=line_op) AS mlo,
			// cf,cont_status,
			// (SELECT RIGHT(sparcsn4.ref_equip_type.nominal_length,2) FROM sparcsn4.ref_equip_type INNER JOIN sparcsn4.ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey INNER JOIN sparcsn4.inv_unit_equip ON inv_unit_equip.eq_gkey=ref_equipment.gkey WHERE sparcsn4.inv_unit_equip.unit_gkey=tbl.gkey ) AS size, 
			// (SELECT RIGHT(sparcsn4.ref_equip_type.nominal_height,2)/10 FROM sparcsn4.ref_equip_type INNER JOIN sparcsn4.ref_equipment ON ref_equipment.eqtyp_gkey=ref_equip_type.gkey INNER JOIN sparcsn4.inv_unit_equip ON inv_unit_equip.eq_gkey=ref_equipment.gkey WHERE sparcsn4.inv_unit_equip.unit_gkey=tbl.gkey ) AS height,carrentPosition,Yard_No,flex_string01 AS mfdch_value 
			// FROM 
			// ( SELECT a.gkey,a.id AS cont_no,b.flex_string10 AS rot_no,k.name AS cf,a.freight_kind AS cont_status,b.last_pos_slot AS carrentPosition, (SELECT ctmsmis.cont_yard(carrentPosition)) AS Yard_No,a.line_op,a.flex_string01 FROM sparcsn4.inv_unit a INNER JOIN sparcsn4.inv_unit_fcy_visit b ON b.unit_gkey=a.gkey INNER JOIN sparcsn4.inv_goods j ON j.gkey = a.goods LEFT JOIN sparcsn4.ref_bizunit_scoped k ON k.gkey = j.consignee_bzu WHERE k.gkey='$cnfGkey' AND DATE(b.flex_date01)>=DATE(NOW())) AS tbl";	

			$sql_assignmentList = "";
			if($org_Type_id==2)
			{
				$sql_assignmentList = "SELECT cont_no,rot_no,mlo,cf_name AS cf,cont_status,size,ROUND(height,1) AS height,Yard_no AS carrentPosition,Yard_No,mfdch_value,assignmentDate,custom_remarks
				FROM ctmsmis.tmp_vcms_assignment
				WHERE cf_lic='$org_license'";
			}
			else
			{
				$sql_assignmentList = "SELECT cont_no,rot_no,mlo,cf_name AS cf,cont_status,size,ROUND(height,1) AS height,Yard_no AS carrentPosition,Yard_No,mfdch_value,assignmentDate,custom_remarks
				FROM ctmsmis.tmp_vcms_assignment
				WHERE Block_No = 'NCY'";
			}			
			
			$rslt_assignmentList=$this->bm->dataSelect($sql_assignmentList);			
			$data['rslt_assignmentList']=$rslt_assignmentList;
			$data['org_Type_id']=$org_Type_id;
			
			$data['title']="Assignment List";
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('assignmentListForm_2',$data);
			$this->load->view('jsAssetsList');
			//Redirect to assignment list ends-----------------------------------
			
		}
	}
	//Assignment List View Details Ends------------------------------
	
	//Confirmation Process Starts---------------------------
	function confirmationProcessForm()
	{
		$session_id = $this->session->userdata('value');
		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();
		}
		else
		{
			$ah = $this->uri->segment(3);
			$msg = "";
			$title = "";
			$chk_st = "";
			$chk_by = "";
			$chk_time = "";
			if($ah=="sec")
			{
				$title = "SECURITY CONFIRMATION PROCESS";
				$chk_st = "yard_security_chk_st";
				$chk_by = "yard_security_chk_by";
				$chk_time = "yard_security_chk_time";
			}
			else if($ah=="cf")
			{
				$title = "C&F CONFIRMATION PROCESS";
				$chk_st = "cnf_chk_st";
				$chk_by = "cnf_chk_by";
				$chk_time = "cnf_chk_time";
			}
			else{
				$title = "TRAFFIC CONFIRMATION PROCESS";
				$chk_st = "traffic_chk_st";
				$chk_by = "traffic_chk_by";
				$chk_time = "traffic_chk_time";
			}
			
			$data['title']=$title;
			$data['chk_st']=$chk_st;
			$data['chk_by']=$chk_by;
			$data['chk_time']=$chk_time;
			$data['ah']=$ah;
			
			$data['msg']=$msg;
			$data['disputeMsg'] = "";
			$data['frmType']="new";
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('confirmationProcessForm',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function getVehicleData()
	{
		$session_id = $this->session->userdata('value');
		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();
		}
		else
		{
			$msg = "";
			$title = "";
			
			$visit_id = $this->input->post('visit_id');
			
			//$rotNo = $this->input->post('rotNo');
			//$contNo = $this->input->post('contNo');

			$sql_cont = "SELECT cont_no FROM do_truck_details_entry WHERE id = '$visit_id'";
			$rslt_cont = $this->bm->dataSelectDB1($sql_cont);
			$cont = "";
			for($i=0;$i<count($rslt_cont);$i++){
				$cont = $rslt_cont[$i]['cont_no'];
			}

			// echo $cont;
			// return;
			

			$rslt_status = $this->chkBlockedContainer($cont);
			//var_dump($rslt_status);
			$cont_status = "";

			for($i = 0;$i<count($rslt_status);$i++){
				$cont_status = $rslt_status[$i]['custom_block_st'];
			}

			
			//echo $cont_status;
			//return;

			$data['cont_status'] = $cont_status;
			
			$chk_st = $this->input->post('chk_st');
			$chk_by = $this->input->post('chk_by');
			$chk_time = $this->input->post('chk_time');
			$ah = $this->input->post('ah');
		
			//$data['rot_no']=$rotNo;
			//$data['cont_no']=$contNo;
			
			$data['visit_id']=$visit_id;
			
			$data['chk_st']=$chk_st;
			$data['chk_by']=$chk_by;
			$data['chk_time']=$chk_time;
			$data['ah']=$ah;
			$data['msg']=$msg;
			$data['disputeMsg'] = "";
			if($ah=="sec")
			{
				$title = "SECURITY CONFIRMATION PROCESS";
			}
			else if($ah=="cf")
			{
				$title = "C&F CONFIRMATION PROCESS";
			}
			else
			{
				$title = "TRAFFIC CONFIRMATION PROCESS";
			}
			$data['title']=$title;
			$data['frmType']="new";
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('confirmationProcess',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function updateChkStatus()
	{
		$session_id = $this->session->userdata('value');
		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();
		}
		else
		{
			$visit_id = $this->input->post('visit_id');
			
			//$rot_no = $this->input->post('rot_no');
			//$cont_no = $this->input->post('cont_no');
			
			$chk_st = $this->input->post('chk_st');
			$chk_by = $this->input->post('chk_by');
			$chk_time = $this->input->post('chk_time');
			$truckDtlId = $this->input->post('truckDtlId');
			$ah = $this->input->post('ah');
			$login_id = $this->session->userdata('login_id');
			
			$msg = "";
			$title = "";
			$queryUpdate="UPDATE do_truck_details_entry SET $chk_st=1,$chk_by='$login_id',$chk_time=NOW() WHERE id='$truckDtlId'";
			$resUpdate = $this->bm->dataInsertDB1($queryUpdate);
			if($resUpdate==1)
			{
				$msg = "<font color='blue'>Successful</font>";
			}
			else
			{
				$msg = "<font color='red'>Failed</font>";
			}
			
			if($ah=="sec")
			{
				$title = "SECURITY CONFIRMATION PROCESS";
			}
			else if($ah=="cf")
			{
				$title = "C&F CONFIRMATION PROCESS";
			}
			else
			{
				$title = "TRAFFIC CONFIRMATION PROCESS";
			}				
			
			// $data['rot_no']=$rot_no;
			// $data['cont_no']=$cont_no;
			
			$data['cont_status'] = "";
			$data['visit_id']=$visit_id;
			
			$data['chk_st']=$chk_st;
			$data['chk_by']=$chk_by;
			$data['chk_time']=$chk_time;
			
			$data['ah']=$ah;
			$data['msg']=$msg;
			$data['disputeMsg'] = "";
			$data['title']=$title;
			$data['frmType']="new";
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('confirmationProcess',$data);
			$this->load->view('jsAssetsList');				
		}
	}

	function confirmationProcessFormForCf()
	{
		$session_id = $this->session->userdata('value');
		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();
		}
		else
		{
			$msg = "";
			$title = "";
			$chk_st = "";
			$chk_by = "";
			$chk_time = "";
			
			$title = "CONFIRMATION FOR C&F";
			$chk_st = "traffic_chk_st";
			$chk_by = "traffic_chk_by";
			$chk_time = "traffic_chk_time";

			
			$data['title']=$title;
			$data['chk_st']=$chk_st;
			$data['chk_by']=$chk_by;
			$data['chk_time']=$chk_time;
			
			$data['msg']=$msg;
			$data['disputeMsg'] = "";
			$data['frmType']="new";
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('confirmationProcessFormForCf',$data);
			$this->load->view('jsAssetsList');
		}
	}

	function getVehicleDataForCf()
	{
		$session_id = $this->session->userdata('value');
		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();
		}
		else
		{
			$msg = "";
			$title = "";
			
			$visit_id = $this->input->post('visit_id');
			
			//$rotNo = $this->input->post('rotNo');
			//$contNo = $this->input->post('contNo');

			$sql_cont = "SELECT cont_no FROM do_truck_details_entry WHERE id = '$visit_id'";
			$rslt_cont = $this->bm->dataSelectDB1($sql_cont);
			$cont = "";
			for($i=0;$i<count($rslt_cont);$i++){
				$cont = $rslt_cont[$i]['cont_no'];
			}

			// echo $cont;
			// return;
			

			$rslt_status = $this->chkBlockedContainer($cont);
			//var_dump($rslt_status);
			$cont_status = "";

			for($i = 0;$i<count($rslt_status);$i++){
				$cont_status = $rslt_status[$i]['custom_block_st'];
			}

			
			//echo $cont_status;
			//return;

			$data['cont_status'] = $cont_status;
			
			$chk_st = $this->input->post('chk_st');
			$chk_by = $this->input->post('chk_by');
			$chk_time = $this->input->post('chk_time');
		
			//$data['rot_no']=$rotNo;
			//$data['cont_no']=$contNo;
			
			$data['visit_id']=$visit_id;
			
			$data['chk_st']=$chk_st;
			$data['chk_by']=$chk_by;
			$data['chk_time']=$chk_time;
			$data['msg']=$msg;
			$data['disputeMsg'] = "";

			$title = "CONFIRMATION FOR C&F";
			
			$data['title']=$title;
			$data['frmType']="new";
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('confirmationProcessForCf',$data);
			$this->load->view('jsAssetsList');
		}
	}

	function updateChkStatusForCf()
	{
		$session_id = $this->session->userdata('value');
		if($session_id!=$this->session->userdata('session_id'))
		{
			$this->logout();
		}
		else
		{
			$visit_id = $this->input->post('visit_id');
			$jettyName = $this->input->post('jetty_name');
			$ip_address = $_SERVER['REMOTE_ADDR'];

			$chk_st = "cnf_chk_st";
			$chk_by = "cnf_chk_by";
			$chk_time = "cnf_chk_time";
			$truckDtlId = $this->input->post('truckDtlId');
			$login_id = $this->session->userdata('login_id');
			
			$msg = "";
			$title = "";
			$queryUpdate="UPDATE do_truck_details_entry SET $chk_st=1 , $chk_by='$jettyName' , $chk_time=NOW() , conf_for_cf_by = '$login_id' , conf_for_cf_ip = '$ip_address' WHERE id = '$truckDtlId'";
			$resUpdate = $this->bm->dataInsertDB1($queryUpdate);
			if($resUpdate==1)
			{
				$msg = "<font color='blue'>Successful</font>";
			}
			else
			{
				$msg = "<font color='red'>Failed</font>";
			}
			
			$title = "CONFIRMATION FOR C&F";				
			
			// $data['rot_no']=$rot_no;
			// $data['cont_no']=$cont_no;
			
			$data['cont_status'] = "";
			$data['visit_id']=$visit_id;
			
			$data['chk_st']=$chk_st;
			$data['chk_by']=$chk_by;
			$data['chk_time']=$chk_time;
			
			$data['msg']=$msg;
			$data['disputeMsg'] = "";
			$data['title']=$title;
			$data['frmType']="new";
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('confirmationProcessForCf',$data);
			$this->load->view('jsAssetsList');				
		}
	}
	//Confirmation Process Ends-----------------------------

	//Loading Process -Starts

	function truckByContForm()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {

			$data['title']="Loading Process";
			$data['msg'] = "";
			$data['flag'] = 0;
			$data['disputeFlag'] = "0";
			
            $this->load->view('cssAssets');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('truckByContForm',$data);
			$this->load->view('jsAssets');
		}
	}

	function truckSearchByCont()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$visitId = $this->input->post("visitId");

			$data['visitId'] = $visitId;


			$sql_cont = "SELECT cont_no FROM do_truck_details_entry WHERE id = '$visitId'";
			$rslt_cont = $this->bm->dataSelectDB1($sql_cont);
			$cont = "";
			for($i=0;$i<count($rslt_cont);$i++){
				$cont = $rslt_cont[$i]['cont_no'];
			}

			// echo $cont;
			// return;
			

			$rslt_status = $this->chkBlockedContainer($cont);
			var_dump($rslt_status);
			$cont_status = "";

			for($i = 0;$i<count($rslt_status);$i++){
				$cont_status = $rslt_status[$i]['custom_block_st'];
			}

			//echo $cont_status;
			//return;

			$data['cont_status'] = $cont_status;


			$data['title']="Loading Process";
			$data['msg'] = "";
			$data['flag'] = 1;
			
			if($this->input->post("UpdateLoading"))
			{
				$sql="SELECT loading_dispute.*,igm_pack_unit.Pack_Unit as pckUnit
				FROM loading_dispute
				INNER JOIN igm_pack_unit ON loading_dispute.pack_unit=igm_pack_unit.id
				WHERE tr_visit_id='$visitId'";   
				$resDispute = $this->bm->dataSelectDb1($sql);
				$data['disputeFlag'] ="1";
				$data['resDispute'] =$resDispute;
			} 
			else 
			{
				$data['disputeFlag'] ="0";
			}
			
			
			
            $this->load->view('cssAssets');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('truckByContForm',$data);
			$this->load->view('jsAssets');
		}
	}

	function truckLoadStsCng()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			//$cont_id = $this->input->post("cont_no");
			$id = $this->input->post("id");
			$login_id = $this->session->userdata('login_id');

			$loadQty = $this->input->post("actual_qty");
			$packUnit = $this->input->post("pack_unit");

			$orgTypeId = $this->session->userdata('org_Type_id');
			
			$loadQuery = "";
			
			if($orgTypeId == 2)
			{
				$loadQuery = "UPDATE do_truck_details_entry SET load_st = '1' , actual_delv_pack = '$loadQty', actual_delv_unit = '$packUnit', cnf_chk_st = '1' , traffic_chk_st = '0', yard_security_chk_st = '0', cnf_chk_time = NOW() , cnf_chk_by = '$login_id' , load_by = '$login_id' , load_time = NOW() WHERE id='$id'";
			}
			else if($orgTypeId == 62)
			{
				$loadQuery = "UPDATE do_truck_details_entry SET load_st = '1' , actual_delv_pack = '$loadQty', actual_delv_unit = '$packUnit', cnf_chk_st = '0' , traffic_chk_st = '1', yard_security_chk_st = '0', traffic_chk_time = NOW() , traffic_chk_by = '$login_id' , load_by = '$login_id' , load_time = NOW() WHERE id='$id'";
			}
			else if($orgTypeId == 75)
			{
				$loadQuery = "UPDATE do_truck_details_entry SET load_st = '1' , actual_delv_pack = '$loadQty', actual_delv_unit = '$packUnit', cnf_chk_st = '0' , traffic_chk_st = '1', yard_security_chk_st = '0', traffic_chk_time = NOW() , traffic_chk_by = '$login_id' , load_by = '$login_id' , load_time = NOW() WHERE id='$id'";
			}
			else if($orgTypeId == 67)
			{
				$loadQuery = "UPDATE do_truck_details_entry SET load_st = '1' , actual_delv_pack = '$loadQty', actual_delv_unit = '$packUnit', cnf_chk_st = '0' , traffic_chk_st = '0', yard_security_chk_st = '1', yard_security_chk_time = NOW() , yard_security_chk_by = '$login_id' , load_by = '$login_id' , load_time = NOW() WHERE id='$id'";
			}

			$rslt_update=$this->bm->dataUpdateDB1($loadQuery);

			if($rslt_update>0)
			{
				$msg="<font color='green'><b>Truck Loaded Successfully!!!</b></font>";
				$logQuery = "INSERT INTO do_loading_log(tr_visit_id,pck_quantiy,pck_unit,update_time,update_by) VALUES('$id','$loadQty','$packUnit',NOW(),'$login_id')";
				$this->bm->dataInsertDB1($logQuery);
			}
			else
			{
				$msg="<font color='red'><b>Truck Can't be Loaded!!!</b></font>";
			}

			$data['cont_status'] = "";
			$data['visitId'] = $id;
			$data['title']= "Loading Process";
			$data['msg'] = $msg;
			$data['flag'] = 1;
			$data['disputeFlag'] = "0";

			$this->load->view('cssAssets');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('truckByContForm',$data);
			$this->load->view('jsAssets');

		}
	}

	function additionalTruck()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$cont = $this->input->post('cont');
			$loadQty = $this->input->post('load_qty');
			$pack_unit = $this->input->post('pack_unit');
			$visit_id = $this->input->post('visitId');
			$id = $this->input->post('id');
			$msg = "";
			
			$btn = $this->input->post("btn");

			if($btn == 'add')
			{	
				$sql_addCont = "INSERT INTO do_truck_details_additional_cont(truck_visit_id,cont_no,pack_num,pack_unit) VALUES('$visit_id','$cont','$loadQty','$pack_unit')";
				$rslt_update = $this->bm->dataInsertDB1($sql_addCont);

				$sql_updateTruck = "UPDATE do_truck_details_entry SET add_truck_st = 1 WHERE id = '$visit_id'";
				$this->bm->dataInsertDB1($sql_updateTruck);
			}
			else{
				$totalAddedCont = $this->input->post('totalAddedCont');
				
				$sql_dltCont = "DELETE FROM do_truck_details_additional_cont WHERE id='$id'";
				$rslt_delete = $this->bm->dataDeleteDB1($sql_dltCont);

				if($totalAddedCont == 1){
					$sql_reset = "UPDATE do_truck_details_entry SET add_truck_st = 0 WHERE id = '$visit_id'";
					$this->bm->dataInsertDB1($sql_reset);
				}
			}

			$data['cont_status'] = "";
			$data['visitId'] = $visit_id;
			$data['title']= "Loading Process";
			$data['msg'] = $msg;
			$data['flag'] = 1;
			$data['disputeFlag'] = "0";

			$this->load->view('cssAssets');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('truckByContForm',$data);
			$this->load->view('jsAssets');

		}
	}

	//Loading Process - Ends
	
	//Dispute Starts--------------------
	function dusputeList()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');

		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$msg="";
			$login_id = $this->session->userdata('login_id');
			$data['title']="Dispute List";
			if($this->input->post('updateDispute'))
			{
				$disputeid = $this->input->post('disputeid');
				$visitid = $this->input->post('visitid');
				$qty = $this->input->post('qty');
				$pack = $this->input->post('pack');
				$remarks = $this->input->post('remarks');
				
				$sql_update = "UPDATE loading_dispute SET qty='$qty',pack_unit='$pack',remarks='$remarks'
							WHERE loading_dispute.id = '$disputeid'";
				$this->bm->dataUpdateDB1($sql_update);
				$msg = "<font color='blue'><b>Updated Successfully!</b></font>";
			}
			//return;
			$query = "SELECT loading_dispute.*,igm_pack_unit.Pack_Unit as pckUnit
					FROM loading_dispute
					INNER JOIN igm_pack_unit ON loading_dispute.pack_unit=igm_pack_unit.id
					ORDER BY id DESC";
			$disputeList = $this->bm->dataSelectDb1($query);
			$data['disputeList'] = $disputeList;
			
			$queryPackList = "SELECT * FROM igm_pack_unit";
			$packList = $this->bm->dataSelectDb1($queryPackList);
			$data['packList'] = $packList;
			
			$data['msg'] = $msg;
			$data['login_id'] = $login_id;
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('disputeList',$data);
			$this->load->view('jsAssetsList');
		}
	}
	//Dispute Ends-----------------

	//Gate In Process - Starts

	function gateInProcessForm()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {

			$data['title']="Gate In Process Form";
			$data['msg'] = "";
			$data['flag'] = 0;
			
            $this->load->view('cssAssets');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('gateInProcessForm',$data);
			$this->load->view('jsAssets');
		}
	}

	function gateInDataSearch()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$gate_pass = $this->input->post("gate_pass");
			
			
			$sql_cont = "SELECT cont_no FROM do_truck_details_entry WHERE id = '$gate_pass'";
			$rslt_cont = $this->bm->dataSelectDB1($sql_cont);
			$cont = "";
			for($i=0;$i<count($rslt_cont);$i++){
				$cont = $rslt_cont[$i]['cont_no'];
			}
			
			$rslt_status = $this->chkBlockedContainer($cont);
			//var_dump($rslt_status);
			$cont_status = "";

			for($i = 0;$i<count($rslt_status);$i++){
				$cont_status = $rslt_status[$i]['custom_block_st'];
			}
			
			
			// $sql_agentPhoto = "SELECT driver_gate_pass,assistant_gate_pass
			// FROM do_truck_details_entry WHERE id = '$gate_pass'";
			// $rslt_agentPhoto = $this->bm->dataSelectDB1($sql_agentPhoto);
			// $driverPhoto = "";
			// $helperPhoto = "";
			
			// if(count($rslt_agentPhoto)>0){
				// $driverPass = $rslt_agentPhoto['0']['driver_gate_pass'];
				// $helperPass = $rslt_agentPhoto['0']['assistant_gate_pass'];
				// $url = "http://10.1.100.105:8095/agentdetail.php?CARDNUMBER=".$driverPass;
				// $json = file_get_contents($url);
				// $obj = json_decode($json);
				// $driverPhoto = "";
				// if(count($obj)>0)
				// {						
				// 	$driverPhoto = $obj->photobase64;
				// }
				
				// $im = $driverPhoto;
				
				// $path = $_SERVER['DOCUMENT_ROOT'].'/pcs/resources/biometricPhoto/'.$driverPass;
				
				// if(!file_exists($path)){
				// 	mkdir($path, 0777, true);
				// 	chmod($path, 0777);

				// 	$output_file=$_SERVER['DOCUMENT_ROOT'].'/pcs/resources/biometricPhoto/'.$driverPass."/".$driverPass.'.png';	
				// 	$ifp = fopen( $output_file, 'wb' ); 

				// 	$data = explode( ',', $im );

				// 	// we could add validation here with ensuring count( $data ) > 1
				// 	fwrite( $ifp, base64_decode( $data[ 1 ] ) );

				// 	// clean up the file resource
				// 	fclose( $ifp );
				// }
				 				
							
				// if($helperPass != ""){
					// $url = "http://10.1.100.105:8095/agentdetail.php?CARDNUMBER=".$helperPass;
					// $json = file_get_contents($url);
					// $obj = json_decode($json);
					// $helperPhoto = "";
					// if(count($obj)>0)
					// {						
					// 	$helperPhoto = $obj->photobase64;
					// }

					// $im = $helperPhoto;
				
					// $path = $_SERVER['DOCUMENT_ROOT'].'/pcs/resources/biometricPhoto/'.$helperPass;
					
					// if(!file_exists($path)){
					// 	mkdir($path, 0777, true);
					// 	chmod($path, 0777);

					// 	$output_file=$_SERVER['DOCUMENT_ROOT'].'/pcs/resources/biometricPhoto/'.$helperPass."/".$helperPass.'.png';	
					// 	$ifp = fopen( $output_file, 'wb' ); 

					// 	$data = explode( ',', $im );

					// 	// we could add validation here with ensuring count( $data ) > 1
					// 	fwrite( $ifp, base64_decode( $data[ 1 ] ) );

					// 	// clean up the file resource
					// 	fclose( $ifp );
					// }
				// }

				
			// }
			
			$data['cont_status'] = $cont_status;
			
			$data['gate_pass'] = $gate_pass;
			// $data['driverPhoto'] = $driverPhoto;
			// $data['helperPhoto'] = $helperPhoto;
			$data['title']="Gate In Process";
			$data['msg'] = "";
			$data['flag'] = 1;
			$data['gateFlag'] = 0;
			
            $this->load->view('cssAssets');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('gateInProcessForm',$data);
			$this->load->view('jsAssets');
		}
	}

	function gateInStsCng()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$gate_pass = $this->input->post("gate_pass");
			$id = $this->input->post("id");
			$login_id = $this->session->userdata('login_id');
			
			$sql_agentPhoto = "SELECT driver_gate_pass,assistant_gate_pass
			FROM do_truck_details_entry WHERE id = '$id'";
			$rslt_agentPhoto = $this->bm->dataSelectDB1($sql_agentPhoto);
			$driverPass = $rslt_agentPhoto['0']['driver_gate_pass'];
			$helperPass = $rslt_agentPhoto['0']['assistant_gate_pass'];
			
			
			$url = "http://10.1.100.105:8095/agentdetail.php?CARDNUMBER=".$driverPass;
			$json = file_get_contents($url);
			$obj = json_decode($json);
			$driverPhoto = "";
			if(count($obj)>0)
			{						
				$driverPhoto = $obj->photobase64;
			}
			
			$url = "http://10.1.100.105:8095/agentdetail.php?CARDNUMBER=".$helperPass;
			$json = file_get_contents($url);
			$obj = json_decode($json);
			$helperPhoto = "";
			if(count($obj)>0)
			{						
				$helperPhoto = $obj->photobase64;
			}

			$sql_cont = "SELECT cont_no FROM do_truck_details_entry WHERE id = '$gate_pass'";
			$rslt_cont = $this->bm->dataSelectDB1($sql_cont);
			$cont = "";
			for($i=0;$i<count($rslt_cont);$i++){
				$cont = $rslt_cont[$i]['cont_no'];
			}

			// echo $cont;
			// return;
			

			$rslt_status = $this->chkBlockedContainer($cont);
			//var_dump($rslt_status);
			$cont_status = "";

			for($i = 0;$i<count($rslt_status);$i++){
				$cont_status = $rslt_status[$i]['custom_block_st'];
			}

			$data['cont_status'] =  $cont_status;

			$getInQuery = "UPDATE do_truck_details_entry SET gate_in_status = '1' , gate_in_by = '$login_id' , gate_in_from = 'web' , gate_in_time = NOW() WHERE id='$id'";

			$rslt_update=$this->bm->dataUpdateDB1($getInQuery);

			if($rslt_update>0)
			{
				$msg="<font color='green'><b>Truck Gate In Process Successfully Done!!!</b></font>";
			}
			else
			{
				$msg="<font color='red'><b>Truck Can't Get In!!!</b></font>";
			}
			
			$data['gate_pass'] = $gate_pass;
			$data['driverPhoto'] = $driverPhoto;
			$data['helperPhoto'] = $helperPhoto;
			$data['title']="Gate In Process";
			$data['msg'] = $msg;
			$data['flag'] = 1;
			$data['gateFlag'] = 1;

			$this->load->view('cssAssets');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('gateInProcessForm',$data);
			$this->load->view('jsAssets');

		}
	}

	//Gate In Process - Ends

	//Payment Collection - Starts

	function paymentCollection(){
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$data['title']="Payment Collection Form";
			$data['msg'] = "";
			$data['flag'] = 0;
			
            $this->load->view('cssAssets');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('paymentCollectionForm',$data);
			$this->load->view('jsAssets');
		}
	}

	function paymentDataSearch()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$trucVisitId = $this->input->post("trucVisitId");
			
			$sql_slotFlag = "SELECT IF(currTime>visit_time_slot_end,'slotOut','slotIn') AS slotFlag FROM
			(SELECT (SELECT NOW()) AS currTime,visit_time_slot_start,visit_time_slot_end
			FROM do_truck_details_entry
			WHERE id='$trucVisitId') AS tbl";
			$rslt_slotFlag = $this->bm->dataSelectDB1($sql_slotFlag);
			$slotFlag = "";
			for($i=0;$i<count($rslt_slotFlag);$i++){
				$slotFlag = $rslt_slotFlag[$i]['slotFlag'];
			}
			
			$msg = "";
			
			if($slotFlag=="slotOut")
			{
				$msg = "<font color='red'>Truck's time slot is over.</font>";
			}
			
			$sql_cont = "SELECT cont_no FROM do_truck_details_entry WHERE id = '$trucVisitId'";
			$rslt_cont = $this->bm->dataSelectDB1($sql_cont);
			$cont = "";
			for($i=0;$i<count($rslt_cont);$i++){
				$cont = $rslt_cont[$i]['cont_no'];
			}

			// echo $cont;
			// return;
			

			$rslt_status = $this->chkBlockedContainer($cont);
			//var_dump($rslt_status);
			$cont_status = "";

			for($i = 0;$i<count($rslt_status);$i++){
				$cont_status = $rslt_status[$i]['custom_block_st'];
			}

			//echo $cont_status;
			//return;

			$data['cont_status'] = $cont_status;
			$data['trucVisitId'] = $trucVisitId;
			$data['title']="Payment Collection Form";
			$data['msg'] = $msg;
			$data['flag'] = 1;
			
            $this->load->view('cssAssets');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('paymentCollectionForm',$data);
			$this->load->view('jsAssets');
		}
	}

	function paymentStsCng()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		$ip_address = $_SERVER['REMOTE_ADDR'];
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$id = $this->input->post("id");
			$login_id = $this->session->userdata('login_id');
			$gate_no = $this->session->userdata('section');
			$driverPass = $this->input->post("driverPass");
			$helperPass = $this->input->post("helperPass");

			$stsUpdateQuery = "UPDATE do_truck_details_entry SET gate_no='$gate_no', paid_status='1' , paid_collect_dt = NOW() , paid_collect_by = '$login_id' , pay_collect_ip = '$ip_address' , collect_gate_no = '$gate_no' WHERE id='$id'";
			$rslt_update=$this->bm->dataUpdateDB1($stsUpdateQuery);

			$dataQuery = "SELECT update_by,truck_id FROM do_truck_details_entry WHERE id='$id'";
			$rslt_data=$this->bm->dataSelectDB1($dataQuery);

			$cfId = "";
			$truckId = "";
			for($i=0;$i<count($rslt_data);$i++){
				$cfId = $rslt_data[$i]['update_by'];
				$truckId = $rslt_data[$i]['truck_id'];
			}
			
			$cf = substr($cfId,0,-2);
			$trkPart = explode(" ",$truckId);
			$trck = $trkPart[0]." ".$trkPart[3]." ".$trkPart[4];
			$trck = urlencode($trck);

			if($rslt_update == 1){
				if($helperPass == "" || $helperPass == null){
					$url = "http://10.1.100.105:8095/tosevent.php?VISITNO=".$id."&EVENT=ISSUE&AIN=".$cf."&DRIVER=".$driverPass."&LP=".$trck;
					$json = file_get_contents($url);
					$obj = json_decode($json);
				}else{
					$url = "http://10.1.100.105:8095/tosevent.php?VISITNO=".$id."&EVENT=ISSUE&AIN=".$cf."&DRIVER=".$driverPass."&HELPER=".$helperPass."&LP=".$trck;
					$json = file_get_contents($url);
					$obj = json_decode($json);
				}
			}

			$data['cont_status'] = "";
			$data['trucVisitId'] = $id;
			$data['title']="Payment Collection Form";
			$data['msg'] = "";
			$data['flag'] = 1;

			$this->load->view('cssAssets');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('paymentCollectionForm',$data);
			$this->load->view('jsAssets');

		}
	}

	// Payment Collection -- Ends

	//Gate Out Process -- Starts

	function gateOutProcessForm()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {

			$data['title']="Gate Out Process";
			$data['msg'] = "";
			$data['flag'] = 0;
			
            $this->load->view('cssAssets');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('gateOutProcessForm',$data);
			$this->load->view('jsAssets');
		}
	}

	function gateOutDataSearch()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$visitId = $this->input->post("visitId");
			
			$sql_agentPhoto = "SELECT driver_gate_pass,assistant_gate_pass
			FROM do_truck_details_entry WHERE id = '$visitId'";
			$rslt_agentPhoto = $this->bm->dataSelectDB1($sql_agentPhoto);
			$driverPass = $rslt_agentPhoto['0']['driver_gate_pass'];
			$helperPass = $rslt_agentPhoto['0']['assistant_gate_pass'];
			
			$sql_cont = "SELECT cont_no FROM do_truck_details_entry WHERE id = '$visitId'";
			$rslt_cont = $this->bm->dataSelectDB1($sql_cont);
			$cont = "";
			for($i=0;$i<count($rslt_cont);$i++){
				$cont = $rslt_cont[$i]['cont_no'];
			}

			// echo $cont;
			// return;
			

			$rslt_status = $this->chkBlockedContainer($cont);
			//var_dump($rslt_status);
			$cont_status = "";

			for($i = 0;$i<count($rslt_status);$i++){
				$cont_status = $rslt_status[$i]['custom_block_st'];
			}

			// $driverPhoto = "";
			// $helperPhoto = "";
			
			// $url = "http://10.1.100.105:8095/agentdetail.php?CARDNUMBER=".$driverPass;
			// $json = file_get_contents($url);
			// $obj = json_decode($json);
			// $driverPhoto = "";
			// if(count($obj)>0)
			// {						
			// 	$driverPhoto = $obj->photobase64;
			// }

			// if($helperPass != ""){
			// 	$url = "http://10.1.100.105:8095/agentdetail.php?CARDNUMBER=".$helperPass;
			// 	$json = file_get_contents($url);
			// 	$obj = json_decode($json);
			// 	$helperPhoto = "";
			// 	if(count($obj)>0)
			// 	{						
			// 		$helperPhoto = $obj->photobase64;
			// 	}
			// }
			
			$data['cont_status'] = $cont_status;
			
			$data['visitId'] = $visitId;
			// $data['driverPhoto'] = $driverPhoto;
			// $data['helperPhoto'] = $helperPhoto;
			$data['title']="Gate Out Process";
			$data['msg'] = "";
			$data['flag'] = 1;
			$data['gateFlag'] = 0;
			
            $this->load->view('cssAssets');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('gateOutProcessForm',$data);
			$this->load->view('jsAssets');
		}
	}

	function gateOutStsCng()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$id = $this->input->post("id");
			$login_id = $this->session->userdata('login_id');
			
			$sql_agentPhoto = "SELECT driver_gate_pass,assistant_gate_pass
			FROM do_truck_details_entry WHERE id = '$id'";
			$rslt_agentPhoto = $this->bm->dataSelectDB1($sql_agentPhoto);
			$driverPass = $rslt_agentPhoto['0']['driver_gate_pass'];
			$helperPass = $rslt_agentPhoto['0']['assistant_gate_pass'];
			
			
			$url = "http://10.1.100.105:8095/agentdetail.php?CARDNUMBER=".$driverPass;
			$json = file_get_contents($url);
			$obj = json_decode($json);
			$driverPhoto = "";
			if(count($obj)>0)
			{						
				$driverPhoto = $obj->photobase64;
			}
			
			$url = "http://10.1.100.105:8095/agentdetail.php?CARDNUMBER=".$helperPass;
			$json = file_get_contents($url);
			$obj = json_decode($json);
			$helperPhoto = "";
			if(count($obj)>0)
			{						
				$helperPhoto = $obj->photobase64;
			}

			$sql_cont = "SELECT cont_no FROM do_truck_details_entry WHERE id = '$id'";
			$rslt_cont = $this->bm->dataSelectDB1($sql_cont);
			$cont = "";
			for($i=0;$i<count($rslt_cont);$i++){
				$cont = $rslt_cont[$i]['cont_no'];
			}

			// echo $cont;
			// return;
			

			$rslt_status = $this->chkBlockedContainer($cont);
			//var_dump($rslt_status);
			$cont_status = "";

			for($i = 0;$i<count($rslt_status);$i++){
				$cont_status = $rslt_status[$i]['custom_block_st'];
			}

			$data['cont_status'] =  $cont_status;

			$getInQuery = "UPDATE do_truck_details_entry SET gate_out_status = '1' , gate_out_by = '$login_id' ,gate_out_time = NOW() WHERE id='$id'";

			$rslt_update=$this->bm->dataUpdateDB1($getInQuery);

			if($rslt_update>0)
			{
				$msg="<font color='green'><b>Truck Gate Out Process Successfully Done!!!</b></font>";
			}
			else
			{
				$msg="<font color='red'><b>Truck Can't Get Out!!!</b></font>";
			}
			
			$data['visitId'] = $id;
			$data['driverPhoto'] = $driverPhoto;
			$data['helperPhoto'] = $helperPhoto;
			$data['title']="Gate Out Process";
			$data['msg'] = $msg;
			$data['flag'] = 1;
			$data['gateFlag'] = 1;

			$this->load->view('cssAssets');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('gateOutProcessForm',$data);
			$this->load->view('jsAssets');

		}
	}

	//Gate Out Process -- Ends

	//Gate In Out Report - Starts

	function gateInOutReportForm(){
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {

			$data['title']="Truck Gate In Out Report";
			$data['msg'] = "";
			$data['flag'] = 0;

			$this->load->view('cssAssets');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('gateInOutReportForm',$data);
			$this->load->view('jsAssets');
		}
	}

	function gateInOutReport(){
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$date = $this->input->post("date");
			$data['title']="Truck Gate In Out Report";
			$data['msg'] = "";
			$data['flag'] = 1;

			if($this->input->post("printFlag"))
			{
				$this->data['date'] = $date;
				$this->load->library('m_pdf');
				$html=$this->load->view('gateInOutReportFormPDF',$this->data, true); //load the pdf_output.php by passing our data and get all data in $html varriable.
				
				$pdfFilePath ="gateInOutReportFormPDF-".time()."-download.pdf";

				$pdf = $this->m_pdf->load();
				$pdf->allow_charset_conversion = true;
				$pdf->charset_in = 'iso-8859-4';
				$stylesheet = file_get_contents('assets/stylesheets/test.css'); // external css

					
				$pdf->WriteHTML($stylesheet,1);
				$pdf->WriteHTML($html,2);
					
				$pdf->Output($pdfFilePath, "I"); // For Show Pdf
			}
			else
			{
				$data['date'] = $date;
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('gateInOutReportForm',$data);
				$this->load->view('jsAssets');
			}
			
		}
	}

	//Gate In Out Report -- Ends
	
	
		
	function vcmsCartTicketList()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$data['visit_id']="";
			if($this->input->post('submit_login'))
			{
				$data['visit_id']=$this->input->post("tr_visit_id");
			}
			$data['title']="Cart Details";
			$data['msg'] = "";
			
            $this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('vcmsCartTicketList',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	
	// function vcmsCartTicketView()
	// {
	// 	$rot_no=$this->input->post('rot_no');
	// 	$cont_no=$this->input->post('cont_no');
	// 	$trucVisitId=$this->input->post('trucVisitId');
		
		
	// 	$this->load->library('m_pdf');
	// 	//$mpdf->use_kwt = true;
			
	// 	$sqlcnf="SELECT cnf_lic_no as rtnValue FROM do_truck_details_entry
	// 	INNER JOIN verify_info_fcl ON verify_info_fcl.id=do_truck_details_entry.verify_info_fcl_id
	// 	WHERE do_truck_details_entry.id='$trucVisitId'";   
	// 	$cnfLic = @$this->bm->dataReturnDB1($sqlcnf);
		
	// 	$sql_CNFName="SELECT id,name 
	// 	FROM sparcsn4.ref_bizunit_scoped 
	// 	WHERE role='SHIPPER' AND life_cycle_state='ACT' AND id='$cnfLic'";
		
	// 	//$rslt_CNFName=$this->bm->dataSelect($sql_CNFName);
		
	// 	//$this->data['rslt_CNFName']=$rslt_CNFName;
	// 	$this->data['trucVisitId']=$trucVisitId;
	
	// 	$html=$this->load->view('vcmsCartTicketView',$this->data, true); 

	// 	$pdfFilePath ="cartTicket-".time()."-download.pdf";

	// 	$pdf = $this->m_pdf->load();
	// 	$pdf->SetWatermarkText('CPA CTMS');
	// 	$pdf->showWatermarkText = false;
	// 	//	$stylesheet = file_get_contents('resources/styles/test.css'); // external css
	// 	$stylesheet = file_get_contents('resources/styles/cartticket.css'); // external css
	// 	//	$pdf->useSubstitutions = true; // optional - just as an example

	// 	//$pdf->setFooter('Developed By : '.$login_id.'|Page {PAGENO}|Date {DATE j-m-Y}');

	// 	$pdf->WriteHTML($stylesheet,1);
	// 	$pdf->WriteHTML($html,2);

	// 	$pdf->Output($pdfFilePath, "I"); // For Show Pdf
		
	// }

	function vcmsCartTicketView()
	{
		$rot_no=$this->input->post('rot_no');
		$cont_no=$this->input->post('cont_no');
		$trucVisitId=$this->input->post('trucVisitId');
		
		
		$this->load->library('m_pdf');
		//$mpdf->use_kwt = true;
		$cnfLic = "";

		$sqlcnf="SELECT cnf_lic_no FROM do_truck_details_entry
		INNER JOIN verify_info_fcl ON verify_info_fcl.id=do_truck_details_entry.verify_info_fcl_id
		WHERE do_truck_details_entry.id='$trucVisitId'";   
		$rslt_cnfLic = $this->bm->dataSelectDB1($sqlcnf);
		
		if(count($rslt_cnfLic)>0){
			$cnfLic = $rslt_cnfLic[0]['cnf_lic_no'];
		}else{
			$sqlcnf="SELECT cnf_lic_no FROM do_truck_details_entry
			INNER JOIN verify_other_data ON verify_other_data.id=do_truck_details_entry.verify_other_data_id
			WHERE do_truck_details_entry.id='$trucVisitId'";   
			$rslt_cnfLic = $this->bm->dataSelectDB1($sqlcnf);
			if(count($rslt_cnfLic)>0){
				$cnfLic = $rslt_cnfLic[0]['cnf_lic_no'];
			}
		}


		$sql_CNFName="SELECT id,name 
		FROM sparcsn4.ref_bizunit_scoped 
		WHERE role='SHIPPER' AND life_cycle_state='ACT' AND id='$cnfLic'";
		
		//$rslt_CNFName=$this->bm->dataSelect($sql_CNFName);
		
		//$this->data['rslt_CNFName']=$rslt_CNFName;
		
		//Extra Trucks----starts
		$sqlExtraTrucks="SELECT do_truck_details_additional_cont.*,igm_pack_unit.Pack_Unit AS actual_delv_unit
				FROM do_truck_details_additional_cont
				LEFT JOIN igm_pack_unit ON igm_pack_unit.id=do_truck_details_additional_cont.pack_unit 
				WHERE truck_visit_id='$trucVisitId'";   
		$rsltExtraTrucks = $this->bm->dataSelectDB1($sqlExtraTrucks);
		$this->data['rsltExtraTrucks']=$rsltExtraTrucks;
		//Extra Trucks----ends
		//---------
		$sqlHBL="SELECT igm_supplimentary_detail.Import_Rotation_No,igm_supplimentary_detail.master_BL_No,
			SUBSTR(igm_supplimentary_detail.Description_of_Goods,1,100) AS Description_of_Goods,
			igm_supplimentary_detail.BL_No,igm_sup_detail_container.cont_number
			FROM igm_sup_detail_container
			INNER JOIN igm_supplimentary_detail ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
			WHERE igm_sup_detail_container.cont_number='$cont_no' AND igm_supplimentary_detail.Import_Rotation_No='$rot_no'";   
		$resHBL = $this->bm->dataSelectDB1($sqlHBL);
		$cntHBL = count($resHBL);
		$this->data['cntHBL']=$cntHBL;
		$this->data['resHBL']=$resHBL;		
		//---------
		
		
		$this->data['trucVisitId']=$trucVisitId;
	
		$html=$this->load->view('vcmsCartTicketView',$this->data, true); 

		$pdfFilePath ="cartTicket-".time()."-download.pdf";

		$pdf = $this->m_pdf->load();
		$pdf->SetWatermarkText('CPA CTMS');
		$pdf->showWatermarkText = false;
		//	$stylesheet = file_get_contents('resources/styles/test.css'); // external css
		$stylesheet = file_get_contents('resources/styles/cartticket.css'); // external css
		//	$pdf->useSubstitutions = true; // optional - just as an example

		//$pdf->setFooter('Developed By : '.$login_id.'|Page {PAGENO}|Date {DATE j-m-Y}');

		$pdf->WriteHTML($stylesheet,1);
		$pdf->WriteHTML($html,2);

		$pdf->Output($pdfFilePath, "I"); // For Show Pdf
		
	}
	
	
	
	function vcmsCartChalanTicketView()
	{
		$rot_no=$this->input->post('rot_no');
		$cont_no=$this->input->post('cont_no');
		$trucVisitId=$this->input->post('trucVisitId');
		$CNFLicenceNo=$this->input->post('cnf_lic_no');

		
		$this->load->library('m_pdf');
		//$mpdf->use_kwt = true;
		$cnfLic = "";

		$sqlcnf="SELECT cnf_lic_no FROM do_truck_details_entry
		INNER JOIN verify_info_fcl ON verify_info_fcl.id=do_truck_details_entry.verify_info_fcl_id
		WHERE do_truck_details_entry.id='$trucVisitId'";   
		$rslt_cnfLic = $this->bm->dataSelectDB1($sqlcnf);
		
		if(count($rslt_cnfLic)>0){
			$cnfLic = $rslt_cnfLic[0]['cnf_lic_no'];
		}else{
			$sqlcnf="SELECT cnf_lic_no FROM do_truck_details_entry
			INNER JOIN verify_other_data ON verify_other_data.id=do_truck_details_entry.verify_other_data_id
			WHERE do_truck_details_entry.id='$trucVisitId'";   
			$rslt_cnfLic = $this->bm->dataSelectDB1($sqlcnf);
			if(count($rslt_cnfLic)>0){
				$cnfLic = $rslt_cnfLic[0]['cnf_lic_no'];
			}
		}


		$sql_CNFName="SELECT id,name 
		FROM sparcsn4.ref_bizunit_scoped 
		WHERE role='SHIPPER' AND life_cycle_state='ACT' AND id='$cnfLic'";
		
		//$rslt_CNFName=$this->bm->dataSelect($sql_CNFName);
		
		//$this->data['rslt_CNFName']=$rslt_CNFName;
		
		//Extra Trucks----starts
		$sqlExtraTrucks="SELECT do_truck_details_additional_cont.*,igm_pack_unit.Pack_Unit AS actual_delv_unit
				FROM do_truck_details_additional_cont
				LEFT JOIN igm_pack_unit ON igm_pack_unit.id=do_truck_details_additional_cont.pack_unit 
				WHERE truck_visit_id='$trucVisitId'";   
		$rsltExtraTrucks = $this->bm->dataSelectDB1($sqlExtraTrucks);
		$this->data['rsltExtraTrucks']=$rsltExtraTrucks;
		//Extra Trucks----ends
		//---------
		$sqlHBL="SELECT igm_supplimentary_detail.Import_Rotation_No,igm_supplimentary_detail.master_BL_No,
			SUBSTR(igm_supplimentary_detail.Description_of_Goods,1,100) AS Description_of_Goods,
			igm_supplimentary_detail.BL_No,igm_sup_detail_container.cont_number
			FROM igm_sup_detail_container
			INNER JOIN igm_supplimentary_detail ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
			WHERE igm_sup_detail_container.cont_number='$cont_no' AND igm_supplimentary_detail.Import_Rotation_No='$rot_no'";   
		$resHBL = $this->bm->dataSelectDB1($sqlHBL);
		$cntHBL = count($resHBL);
		$this->data['cntHBL']=$cntHBL;
		$this->data['resHBL']=$resHBL;		


		
		//---Chalan included--
		
			$CNFStr1="SELECT distinct(ref_bizunit_scoped.name) as name, address_line1
			FROM inv_unit 
			INNER JOIN inv_goods ON inv_goods.gkey=inv_unit.goods
			LEFT JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=inv_goods.consignee_bzu
			WHERE ref_bizunit_scoped.id = '$CNFLicenceNo'";
			$CNFresult = $this->bm->dataSelect($CNFStr1);
			
			$queryStr="SELECT do_truck_details_entry.truck_id,do_truck_details_entry.actual_delv_pack,
			do_truck_details_entry.actual_delv_unit,do_truck_details_entry.gate_no,do_truck_details_entry.cont_no,do_truck_details_entry.import_rotation,
			verify_info_fcl.igm_detail_cont_id,
			verify_info_fcl.igm_detail_id,SUBSTR(igm_details.Description_of_Goods, 1, 100) AS Description_of_Goods,
			igm_details.Notify_name,igm_details.Notify_address
			FROM do_truck_details_entry
			INNER JOIN verify_info_fcl ON do_truck_details_entry.verify_info_fcl_id=verify_info_fcl.id
			INNER JOIN igm_details ON igm_details.id=verify_info_fcl.igm_detail_id
			WHERE do_truck_details_entry.id='$trucVisitId'";

			$resQuery = $this->bm->dataSelectDb1($queryStr);

			if(count($resQuery) == 0){
				$queryStr="SELECT do_truck_details_entry.truck_id,do_truck_details_entry.actual_delv_pack,
				do_truck_details_entry.actual_delv_unit,do_truck_details_entry.gate_no,do_truck_details_entry.cont_no,do_truck_details_entry.import_rotation,
				SUBSTR(Description_of_Goods, 1, 100) AS Description_of_Goods,
				Notify_name,Notify_address
				FROM do_truck_details_entry
				INNER JOIN lcl_dlv_assignment ON do_truck_details_entry.verify_other_data_id=lcl_dlv_assignment.id
				INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=lcl_dlv_assignment.igm_sup_dtl_id
				WHERE do_truck_details_entry.id='$trucVisitId'";
				$resQuery = $this->bm->dataSelectDb1($queryStr);
			}
				
			$login_id = $this->session->userdata('login_id');
			
			$this->data['CNFresult']=$CNFresult;
			$this->data['resQuery']=$resQuery;
			$this->data['visitId']=$trucVisitId;
		
		
		//---Chalan excluded--
		
		
		$this->data['trucVisitId']=$trucVisitId;
	
		$html=$this->load->view('vcmsCartChalanTicketView',$this->data, true); 

		$pdfFilePath ="cartTicket-".time()."-download.pdf";

		$pdf = $this->m_pdf->load();
		$pdf->SetWatermarkText('CPA CTMS');
		$pdf->showWatermarkText = false;
		//	$stylesheet = file_get_contents('resources/styles/test.css'); // external css
		$stylesheet = file_get_contents('resources/styles/cartticket.css'); // external css
		//	$pdf->useSubstitutions = true; // optional - just as an example

		//$pdf->setFooter('Developed By : '.$login_id.'|Page {PAGENO}|Date {DATE j-m-Y}');

		$pdf->WriteHTML($stylesheet,1);
		$pdf->WriteHTML($html,2);

		$pdf->Output($pdfFilePath, "I"); // For Show Pdf
	}

	//Emergency Truck  -- Starts

	function emergencyTruckList()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			
			$data['title']="Emergency Truck List";

			$this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('emergencyTruckList',$data);
			$this->load->view('jsAssetsList');
		}
	}


	function emergencyTruckApprove()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$id = $this->input->post('id');

			$query = "UPDATE do_truck_details_entry SET emrgncy_approve_stat='1' WHERE id='$id'";
			$rslt_update=$this->bm->dataUpdateDB1($query);

			$data['title']="Emergency Truck List";

			$this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('emergencyTruckList',$data);
			$this->load->view('jsAssetsList');
		}
	}

	//Emergency Truck  -- Ends
	
	function chalanGenerationForm()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {

			$data['title']="Challan Confirmation Form";
			$data['msg'] = "";
			$data['flag'] = 0;
			
            $this->load->view('cssAssets');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('chalanGenerationForm',$data);
			$this->load->view('jsAssets');
		}
	}
	
	function chalanDataSearch($visitId=null)
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			if($visitId==null or $visitId=="")
				$visitId = $this->input->post("visitId");
			
			$data['visitId'] = $visitId;
			$data['title']="Chalan Confirmation Form";
			$data['msg'] = "";
			$data['flag'] = 1;
			
            $this->load->view('cssAssets');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('chalanGenerationForm',$data);
			$this->load->view('jsAssets');
		}
	}
	
	function challanConfirmation()
	{		
		$visitId=$this->input->post('visitId');
		$login_id = $this->session->userdata('login_id');
		
		$sql_challanConfirm = "UPDATE do_truck_details_entry
		SET chalan_conf_st='1',chalan_conf_by='$login_id'
		WHERE id='$visitId'";
		// echo $sql_challanConfirm;return;
		$this->bm->dataUpdateDB1($sql_challanConfirm);
		$this->chalanDataSearch($visitId);
	}
	
	// truck entry - new function - start
	
	function cnfTruckEntryForm($rotNo=null,$contNo=null,$cont_status=null,$assignmentType=null,$msg=null)
	{
		
		// on losing session give alert message.
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
		else
		{
		
			$org_license = $this->session->userdata('org_license');
			$login_id = $this->session->userdata('login_id');
			$ip_address = $_SERVER['REMOTE_ADDR'];
			
			$data['title']="TRUCK DETAIL ENTRY FORM";
			$srcFlag="";

			if($this->input->post('delBtn'))
			{
				$editVal = 0;
				
				$editId = $this->input->post('editId');
				$btnType = $this->input->post('btnType');
				$contNo = $this->input->post('contNo');
				$rotNo = $this->input->post('rotNo');
				$cont_status = $this->input->post('cont_status');
				
				$editType = $this->input->post('editBtn');
				$data['editType']=$editType;	
				
				$delId = $this->input->post('delId');	
				
				$sql_select = "select * from do_truck_details_entry WHERE id='$delId'";
				$rslt_select = $this->bm->dataSelectDB1($sql_select);

				$id = "";
				$verify_info_fcl_id = "";
				$verify_other_data_id = "";
				$verify_number = "";
				$import_rotation = "";
				$cont_no = "";
				$truck_id = "";
				$gate_no = "";
				$driver_name = "";
				$driver_gate_pass = "";
				$assistant_name = "";
				$assistant_gate_pass = "";
				$truck_agency_name = "";
				$truck_agency_phone = "";
				$last_update = "";
				$ip_addr = "";
				$update_by = "";
				$paid_amt = "";
				$paid_status = "";
				$paid_method = "";
				$visit_time_slot_start = "";
				$visit_time_slot_end = "";
				$emrgncy_flag = "";
				$emrgncy_approve_stat = "";
				$is_confirm = "";
				$driver_id = "";
				$helper_id = "";

				for($z=0;$z<count($rslt_select);$z++){
					$id = $rslt_select[$z]['id'];
					$verify_info_fcl_id = $rslt_select[$z]['verify_info_fcl_id'];
					$verify_other_data_id = $rslt_select[$z]['verify_other_data_id'];
					$verify_number = $rslt_select[$z]['verify_number'];
					$import_rotation = $rslt_select[$z]['import_rotation'];
					$cont_no = $rslt_select[$z]['cont_no'];
					$truck_id = $rslt_select[$z]['truck_id'];
					$gate_no = $rslt_select[$z]['gate_no'];
					$driver_name = $rslt_select[$z]['driver_name'];
					$driver_gate_pass = $rslt_select[$z]['driver_gate_pass'];
					$assistant_name = $rslt_select[$z]['assistant_name'];
					$assistant_gate_pass = $rslt_select[$z]['assistant_gate_pass'];
					$truck_agency_name = $rslt_select[$z]['truck_agency_name'];
					$truck_agency_phone = $rslt_select[$z]['truck_agency_phone'];
					$last_update = $rslt_select[$z]['last_update'];
					$ip_addr = $rslt_select[$z]['ip_addr'];
					$update_by = $rslt_select[$z]['update_by'];
					$paid_amt = $rslt_select[$z]['paid_amt'];
					$paid_status = $rslt_select[$z]['paid_status'];
					$paid_method = $rslt_select[$z]['paid_method'];
					$visit_time_slot_start = $rslt_select[$z]['visit_time_slot_start'];
					$visit_time_slot_end = $rslt_select[$z]['visit_time_slot_end'];
					$emrgncy_flag = $rslt_select[$z]['emrgncy_flag'];
					$emrgncy_approve_stat = $rslt_select[$z]['emrgncy_approve_stat'];
					$is_confirm = $rslt_select[$z]['is_confirm'];
					$driver_id = $rslt_select[$z]['driver_id'];
					$helper_id = $rslt_select[$z]['helper_id'];
				}

				if($id>0){
					$sql_log = "INSERT INTO delete_log_do_truck_details(visit_id,verify_info_fcl_id,verify_other_data_id,verify_number,import_rotation,cont_no,truck_id,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,truck_agency_name,truck_agency_phone,last_update,ip_addr,update_by,paid_amt,paid_status,paid_method,visit_time_slot_start,visit_time_slot_end,emrgncy_flag,emrgncy_approve_stat,is_confirm,driver_id,helper_id,deleted_by,deleted_time,delete_by_ip) VALUES('$id','$verify_info_fcl_id','$verify_other_data_id','$verify_number','$import_rotation','$cont_no','$truck_id','$gate_no','$driver_name','$driver_gate_pass','$assistant_name','$assistant_gate_pass','$truck_agency_name','$truck_agency_phone','$last_update','$ip_addr','$update_by','$paid_amt','$paid_status','$paid_method','$visit_time_slot_start','$visit_time_slot_end','$emrgncy_flag','$emrgncy_approve_stat','$is_confirm','$driver_id','$helper_id','$login_id',NOW(),'$ip_address')";
					$this->bm->dataInsertDB1($sql_log);

					$sql_delete = "DELETE  FROM do_truck_details_entry WHERE id='$delId'";
					$del_st = $this->bm->dataDeleteDB1($sql_delete);
				}
							
			}

			if($rotNo==null and $contNo==null and $cont_status==null and $assignmentType==null)		// when function is called from list
			{
				$rotNo = $this->input->post('rotNo');
				$contNo = $this->input->post('contNo');
				$unit_gkey=$this->input->post('unit_gkey');
				$cont_status = $this->input->post('cont_status');
				$assignmentType = $this->input->post('assignmentType');
				$srcFlag = "list";		

				$data['unit_gkey']=$unit_gkey;			
			}
			else
			{
			
			}

			
			$rslt_status = $this->chkBlockedContainer($contNo);
			//var_dump($rslt_status);
			$cont_blocked_status = "";

			for($i = 0;$i<count($rslt_status);$i++){
				$cont_blocked_status = $rslt_status[$i]['custom_block_st'];
			}			

			//echo $cont_status;
			//return;

			$data['cont_blocked_status'] = $cont_blocked_status;
			
			$data['rotNo']=$rotNo;
			$data['contNo']=$contNo;
			
			$data['cont_status']=$cont_status;
			$data['assignmentType']=$assignmentType;
			
			$data['msg']=$msg;

			// echo "1";
			// return;
			
			if($cont_status=="FCL")
			{									
				if($srcFlag=="list")
				{
					// $this->addVryInfoFCL($rotNo,$contNo,$unit_gkey);
					$this->addVryInfoFCL($rotNo,$contNo,$unit_gkey,$assignmentType);
				}
				
				// driver helper info
				$sql_driverInfo = "SELECT id,card_number,agent_name
				FROM vcms_vehicle_agent
				WHERE agent_type='Driver'";
				$rslt_driverInfo = $this->bm->dataSelectDB1($sql_driverInfo);
				$data['rslt_driverInfo']=$rslt_driverInfo;
				
				$sql_helperInfo = "SELECT id,card_number,agent_name
				FROM vcms_vehicle_agent
				WHERE agent_type='Helper'";
				$rslt_helperInfo = $this->bm->dataSelectDB1($sql_helperInfo);
				$data['rslt_helperInfo']=$rslt_helperInfo;
				
				// common info
				$verifyReport = "";
				$sql_posYardBlock = "";
				
				$verifyReport = "SELECT shed_bill_master.bill_no,verify_info_fcl.verify_number,verify_info_fcl.verify_unit AS unit_no,igm_details.Import_Rotation_No AS import_rotation, igm_masters.Vessel_Name AS vessel_name,igm_details.BL_No AS bl_no, igm_details.Description_of_Goods,Qty,bill_rcv_stat,IF(bill_rcv_stat=1,'Paid','Not Paid') AS paid_status,igm_detail_container.cont_number,igm_detail_container.cont_size,igm_detail_container.cont_height,igm_details.Pack_Number,igm_details.Pack_Description, verify_info_fcl.no_of_truck 
				FROM igm_detail_container 
				LEFT JOIN igm_details ON igm_details.id=igm_detail_container.igm_detail_id
				LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_cont_id=igm_detail_container.id
				LEFT JOIN shed_bill_master ON shed_bill_master.verify_no = verify_info_fcl.verify_number 
				LEFT JOIN shed_bill_details ON shed_bill_master.bill_no =shed_bill_details.bill_no
				LEFT JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
				WHERE igm_details.Import_Rotation_No='$rotNo' AND igm_detail_container.cont_number='$contNo'";

				$sql_posYardBlock = "SELECT slot AS currentPos,Yard_No,Block_No,assignmentDate
				FROM ctmsmis.tmp_vcms_assignment 
				WHERE rot_no='$rotNo' AND cont_no='$contNo'";
				
				$rtnVerifyReport = $this->bm->dataSelectDb1($verifyReport);
				$rslt_posYardBlock = $this->bm->dataSelect($sql_posYardBlock);

				$data['rtnVerifyReport'] = $rtnVerifyReport;			
				$data['rslt_posYardBlock'] = $rslt_posYardBlock;
				
				if($rtnVerifyReport[0]['cont_size']==20)
					$totTruck = 2;
				// else if($rtnVerifyReport[0]['cont_size']==40)
				else
					$totTruck = 3;
				$data['totTruck'] = $totTruck;
				
				$sql_contNumber="SELECT DISTINCT cont_number FROM(
				SELECT igm_detail_container.cont_number 
				FROM igm_detail_container 
				INNER JOIN igm_details ON igm_details.id=igm_detail_container.igm_detail_id 
				LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_cont_id=igm_detail_container.id 
				WHERE igm_details.Import_Rotation_No='$rotNo' AND igm_detail_container.cont_number='$contNo') AS tbl";
				
				$rslt_contNumber=$this->bm->dataSelectDb1($sql_contNumber);						
					
				for($i=0;$i<count($rslt_contNumber);$i++)
				{
					$rsltTmp[]=$rslt_contNumber[$i]['cont_number'];
				}
				
				$containerSet=join(", ",$rsltTmp);
				$data['containerSet']=$containerSet;
				
				// tab 1 - added truck (paid or not paid)
				
				// $sql_slotQty = "SELECT slot_1_qty,slot_2_qty,slot_3_qty
				// FROM vcms_truck_slot
				// WHERE slot_dt=DATE(NOW())";
				
				$sql_slotQty = "SELECT slot_1_qty,slot_2_qty,slot_3_qty
				FROM vcms_truck_slot";
				$rslt_slotQty = $this->bm->dataSelectDB1($sql_slotQty);
				$data['rslt_slotQty']=$rslt_slotQty;
				
				$sql_vrfyInfoFclId = "SELECT id FROM verify_info_fcl 
				WHERE rotation='$rotNo' AND cont_number='$contNo'";
				$rslt_vrfyInfoFclId = $this->bm->dataSelectDB1($sql_vrfyInfoFclId);
				$vrfyInfoFclId = $rslt_vrfyInfoFclId[0]['id'];
				$data['vrfyInfoFclId']=$vrfyInfoFclId;
				
				$sql_tmpTrkData = "SELECT id,verify_info_fcl_id,truck_id,delv_pack AS pkg_qty,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,paid_amt,paid_status,paid_method,emrgncy_flag,emrgncy_approve_stat,gate_out_status
				FROM do_truck_details_entry
				WHERE verify_info_fcl_id = '$vrfyInfoFclId'";
				// echo $sql_tmpTrkData; return;
				$rslt_tmpTrkData = $this->bm->dataSelectDB1($sql_tmpTrkData);
				$data['rslt_tmpTrkData']=$rslt_tmpTrkData;
				
				$emrgncyFlag = 0;
				
				for($i=0;$i<count($rslt_tmpTrkData);$i++)
				{
					if($rslt_tmpTrkData[$i]['emrgncy_flag']==1)
						$emrgncyFlag=1;										
				}
				
				$data['emrgncyFlag']=$emrgncyFlag;
							
				// no of truck assigned
				// $sql_noOfTruckAssign = "SELECT no_of_truck FROM verify_info_fcl WHERE id='$vrfyInfoFclId'";
				$sql_noOfTruckAssign = "SELECT no_of_truck,jetty_sirkar_id,agent_name,agent_code AS js_lic_no,mobile_number AS cell_no,card_number
				FROM verify_info_fcl
				LEFT JOIN vcms_vehicle_agent ON vcms_vehicle_agent.id=verify_info_fcl.jetty_sirkar_id
				WHERE verify_info_fcl.id='$vrfyInfoFclId'";
				$rslt_noOfTruckAssign = $this->bm->dataSelectDB1($sql_noOfTruckAssign);
				
				$noOfTruckAssign="";
				$jetty_sirkar_id="";
				$js_lic_no="";
				$cell_no="";
				
				for($jt = 0;$jt<count($rslt_noOfTruckAssign);$jt++)
				{
					$noOfTruckAssign = $rslt_noOfTruckAssign[$jt]['no_of_truck'];					
					$jetty_sirkar_id = $rslt_noOfTruckAssign[$jt]['jetty_sirkar_id'];
					$agent_name = $rslt_noOfTruckAssign[$jt]['agent_name'];
					$js_lic_no = $rslt_noOfTruckAssign[$jt]['js_lic_no'];
					$cell_no = $rslt_noOfTruckAssign[$jt]['cell_no'];
					$card_number = $rslt_noOfTruckAssign[$jt]['card_number'];
				}			
				$data['noOfTruckAssign']=$noOfTruckAssign;
				
				$data['jetty_sirkar_id']=$jetty_sirkar_id;
				$data['agent_name']=$agent_name;
				$data['js_lic_no']=$js_lic_no;
				$data['cell_no']=$cell_no;
				$data['card_number']=$card_number;
				
				// importer mobile no
				$sql_importerMobile = "SELECT importer_mobile_no FROM verify_info_fcl WHERE id='$vrfyInfoFclId'";
				$rslt_importerMobile = $this->bm->dataSelectDB1($sql_importerMobile);
				$importerMobile = $rslt_importerMobile[0]['importer_mobile_no'];
				$data['importerMobile']=$importerMobile;
				
				// truck slot
				$truckSlot = "";
				
				$sql_truckSlot = "SELECT truck_slot FROM verify_info_fcl WHERE id='$vrfyInfoFclId'";
				$rslt_truckSlot = $this->bm->dataSelectDB1($sql_truckSlot);
				if(count($rslt_truckSlot)>0)
				{
					$truckSlot = $rslt_truckSlot[0]['truck_slot'];
				}
				$data['truckSlot']=$truckSlot;
				
				$blck = "";
				$sltAssignDt = "";
				if(count($rslt_posYardBlock)>0)
				{
					$blck = $rslt_posYardBlock[0]["Block_No"];
					$sltAssignDt = $rslt_posYardBlock[0]["assignmentDate"];
				}
				$data['sltAssignDt']=$sltAssignDt;
				
				$strGetSlotCnt1 = "SELECT COUNT(*) AS rtnValue FROM ctmsmis.tmp_vcms_assignment WHERE assignmentDate='$sltAssignDt' AND Block_No='$blck' AND assignment_slot=1";
				$SlotCnt1 = $this->bm->dataReturn($strGetSlotCnt1);
				$data['SlotCnt1']=$SlotCnt1;
				
				$strGetSlotCnt2 = "SELECT COUNT(*) AS rtnValue FROM ctmsmis.tmp_vcms_assignment WHERE assignmentDate='$sltAssignDt' AND Block_No='$blck' AND assignment_slot=2";
				$SlotCnt2 = $this->bm->dataReturn($strGetSlotCnt2);
				$data['SlotCnt2']=$SlotCnt2;

				$strGetSlotCnt3 = "SELECT COUNT(*) AS rtnValue FROM ctmsmis.tmp_vcms_assignment WHERE assignmentDate='$sltAssignDt' AND Block_No='$blck' AND assignment_slot=3";
				$SlotCnt3 = $this->bm->dataReturn($strGetSlotCnt3);
				$data['SlotCnt3']=$SlotCnt3;
				// tab 2 - js info				
				
				$sql_jsInfo = "SELECT vcms_vehicle_agent.id,agent_name AS js_name,agent_type,card_number
				FROM vcms_vehicle_agent
				INNER JOIN vcms_vehicle_agency ON vcms_vehicle_agency.id=vcms_vehicle_agent.agency_id
				WHERE agency_code = '$org_license' AND agent_type = 'Jetty Sircar'";

				$rslt_jsInfo = $this->bm->dataSelectDB1($sql_jsInfo);
				$data['rslt_jsInfo']=$rslt_jsInfo;
				
				// edit - replace section
				$sql_trkEditInfo = "";
				$editVal = 0;
				$addVal = 0;
				$payVal = 0;
				$payForm = 0;
				
				if($this->input->post('addBtn') || $this->input->post('deliver') )   // Added 'deliver' to open truck add section after adding jetty sircar
				{
					$addVal = 1;
					$editVal = 0;
					$payForm = 2;
				}

				if($this->input->post('payType')=="singlePay" || $this->input->post('payType')=="allPay")
				{
					$payForm = 1;
					$editVal = 0;
					
					if($this->input->post('payType')=="singlePay")
					{						
						$truckDtlId = $this->input->post('truckDtlId');
						$payAmt = $this->input->post('payAmt');
						$payMethod = $this->input->post('payMethod');
						$payFlag = "singlePay";
						
						$data["truckDtlId"] = $truckDtlId;
											
					}
					else if($this->input->post('payType')=="allPay")
					{			
						$payAmt = $this->input->post('totalAmtToPay');
						$vrfyInfoFclId = $this->input->post('vrfyInfoFclId');
						// $payAmt = 57.5;
						$payMethod = "cash";
						$payFlag = "allPay";
						
						$data["vrfyInfoFclId"] = $vrfyInfoFclId;
					}
					$data["payAmt"] = $payAmt;
					$data["Method"] = $payMethod;
					$data["payFlag"] = $payFlag;
					
				}

				
				if($this->input->post('payment'))
				{
					$addVal = 1;
					$payForm = 2;
				}
				
				
				if($this->input->post('editId'))
				{
					$editVal = 1;
					
					$editId = $this->input->post('editId');
					$btnType = $this->input->post('btnType');
					$contNo = $this->input->post('contNo');
					$rotNo = $this->input->post('rotNo');
					$cont_status = $this->input->post('cont_status');
					
					$editType = $this->input->post('editBtn');
					$data['editType']=$editType;	
					
					$sql_trkEditInfo = "SELECT id,truck_id,driver_name,driver_gate_pass,truck_agency_name,truck_agency_phone,
					(SELECT mobile_number FROM vcms_vehicle_agent WHERE card_number=driver_gate_pass LIMIT 1) AS driver_mobile_number,
					assistant_name,assistant_gate_pass,
					(SELECT mobile_number FROM vcms_vehicle_agent WHERE card_number=assistant_gate_pass LIMIT 1) AS helper_mobile_number
					FROM do_truck_details_entry
					WHERE id='$editId'";
					$rslt_trkEditInfo = $this->bm->dataSelectDB1($sql_trkEditInfo);
					$data['rslt_trkEditInfo']=$rslt_trkEditInfo;				
								
				}
				
				$jettyEdit = 0;
				
				if($this->input->post('jettyedit')){
					$jettyEdit = 1;
				}
				
				$data['editVal']=$editVal;
				$data['addVal']=$addVal;
				$data['payVal']=$payVal;
				$data['payForm']=$payForm;
				$data['jettyEdit']=$jettyEdit;
				
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('cnfTruckEntryForm',$data);			
				$this->load->view('jsAssets');
			}
			else if($cont_status=="LCL")
			{
				
			}
		}
	}

	function checkoutbyOnline()
	{
		$visitId = $this->input->post('trucVisitId');
		$assignmentType = $this->input->post('assignmentType');
		$contact = $this->input->post('contact');
		$login_id = $this->session->userdata('login_id');
		
		//visit_id trans_id trans_time rotation payAmount tranAmount vat commision bank_tran_id cus_name cus_phone requ_id cnf_login_id PayMode statusMsg //container refTranDateTime orgiBrCode scrollNo
		
		$sql_maxRequ = "SELECT MAX(vcms_sonali_online.requ_id)+1 AS rtnValue FROM vcms_online_transaction";
		$requst_id = $this->bm->dataReturnDB1($sql_maxRequ);
		
		$query_txEntry = "INSERT INTO vcms_sonali_online (visit_id, requ_id, assign_type, cus_phone, cnf_login_id, allPay_st ) VALUES ('$visitId',
		'$requst_id', '$assignmentType', '$login_id', 0 )";
		$this->bm->dataInsertDB1($query_txEntry);
		
		$data['requst_id'] = $requst_id;
		//$data['login_id'] = $login_id;
		//$data['assignmentType'] = $assignmentType;
		$data['contact'] = $contact;
		$data['trucVisitId'] = $visitId;
		$data['flag'] = 0;  //Single Pay
		$data['name'] = $this->session->userdata('User_Name');
		$data['payAmt'] = $this->input->post('payAmt');
		$this->load->view('onlinePay', $data);
	}

	function checkoutAllbyOnline($cont,$rot,$assignmentType,$contact)
	{	

		$login_id = $this->session->userdata('login_id');

		$data['cont'] = $cont;
		$data['rot'] = $rot;
		$data['login_id'] = $login_id;
		$data['assignmentType'] = $assignmentType;
		$data['contact'] = $contact;
		$data['flag'] = 1;  //All Pay
		$data['name'] = $this->session->userdata('User_Name');
		$data['payAmt'] = $this->input->post('payAmt');
		$this->load->view('checkout', $data);
	}
	
	function onPaymentSuccess()
	{	
		$getData = file_get_contents('php://input');

		$myXMLData = urldecode ($getData);
		//header("Content-Type: application/xml");
		//echo '<br/>';
		@$myXMLData=str_replace("Request=","",$myXMLData);

		//echo @$myXMLData;

		@$xml = simplexml_load_string($myXMLData);
		if ($xml === false) {
		echo "Failed loading XML: ";
		foreach(libxml_get_errors() as $error) {
		//echo "<br>", $error->message;
		}
		} else {
		//print_r($xml);
		}

		$ApiAccessUserId=$xml->ApiAccessUserId;
		$TransactionId=$xml->TransactionId;
		$TranDateTime=$xml->TranDateTime;
		$RefTranNo=$xml->RefTranNo;
		$RefTranDateTime=$xml->RefTranDateTime;
		$TranAmount=$xml->TranAmount;
		$PayAmount=$xml->PayAmount;
		$PayMode=$xml->PayMode;
		$OrgiBrCode=$xml->OrgiBrCode;
		$StatusMsg=$xml->StatusMsg;
		$Vat=$xml->Vat;
		$Commission=$xml->Commission;
		$TransactionStatus=$xml->TransactionStatus;

		echo $ApiAccessUserId.'-'.$TransactionId.'-'.$TranAmount.'-'.$StatusMsg;
		if( $PayMode != "A01" && $TransactionStatus == "200")
		{

			# TO CONVERT AS ARRAY
			# $result = json_decode($result, true);
			# $status = $result['status'];

			# TO CONVERT AS OBJECT
			$result = json_decode($result);

			//print_r ($result);
			# TRANSACTION INFO
			$status = $result->status;
			$tran_date = $result->tran_date;
			$tran_id = $result->tran_id;
			$val_id = $result->val_id;
			$tran_amount = $result->amount;
			$store_amount = $result->store_amount;
			$currency = $result->currency;
			$bank_tran_id = $result->bank_tran_id;
			$card_type = $result->card_type;

			#CUSTOMER INFO
			$cus_name = $result->cus_name;
			$cus_phone = $result->cus_phone;

			 # EMI INFO
			$emi_instalment = $result->emi_instalment;
			$emi_amount = $result->emi_amount;
			$emi_description = $result->emi_description;
			$emi_issuer = $result->emi_issuer;

			# ISSUER INFO
			$card_no = $result->card_no;
			$card_issuer = $result->card_issuer;
			$card_brand = $result->card_brand;
			$card_issuer_country = $result->card_issuer_country;
			$card_issuer_country_code = $result->card_issuer_country_code; 

			# API AUTHENTICATION
			$APIConnect = $result->APIConnect;
			$validated_on = $result->validated_on;
			$gw_version = $result->gw_version;   

			# system Info
			$amount = $result->amount;
			$flag = $result->value_d;
			if($flag == 0)
			{
				$visitId = $result->value_a;
				$assignmentType = $result->value_b;
				$login_id = $result->value_c;

				$query_update = "UPDATE do_truck_details_entry SET paid_amt = '$amount', paid_status = 1 , paid_method = 'online' , paid_collect_dt = NOW() WHERE id = '$visitId'";
				$update_st = $this->bm->dataUpdateDB1($query_update);

				$query_Truck = "SELECT driver_gate_pass,assistant_gate_pass FROM do_truck_details_entry WHERE id='$visitId'";
				$data_truck = $this->bm->dataSelectDB1($query_Truck);

				$driver_gate_pass = "";
				$assistant_gate_pass = "";

				for($i=0;$i<count($data_truck);$i++){
					$driver_gate_pass = $data_truck[$i]['driver_gate_pass'];
					$assistant_gate_pass = $data_truck[$i]['assistant_gate_pass'];
				}

				if($update_st == 1){
					$url = "http://10.1.100.105:8095/tosevent.php?VISITNO=".$visitId."&EVENT=ISSUE&DRIVER=".$driver_gate_pass."&HELPER=".$assistant_gate_pass;
					$json = file_get_contents($url);
					$obj = json_decode($json);
				}

				$query_txEntry = "INSERT INTO vcms_online_transaction (visit_id,trans_id,trans_time,val_id,amount,store_amount,currency,bank_tran_id,card_no,card_issuer,card_brand,cus_name,cus_phone) VALUES ('$visitId','$tran_id','$tran_date','$val_id','$tran_amount','$store_amount','$currency','$bank_tran_id','$card_no','$card_issuer','$card_brand','$cus_name','$cus_phone')";
				$this->bm->dataInsertDB1($query_txEntry);
			}
			else if($flag == 1)
			{
				$rotCont = $result->value_a;
				$data = explode("_",$rotCont);
				$cont = $data[0];
				$rot = $data[1];
				$assignmentType = $result->value_b;
				$login_id = $result->value_c;

				//Insert Query Done First due to fetcing only which truck visit id is not paid
				$query_visitId = "SELECT id,driver_gate_pass,assistant_gate_pass FROM do_truck_details_entry WHERE cont_no = '$cont' AND import_rotation = '$rot' AND paid_status = 0";
				$rslt_visitId = $this->bm->dataSelectDB1($query_visitId);

				$visit_Id = "";
				$driver_gate_pass = "";
				$assistant_gate_pass = "";

				for($k = 0;count($rslt_visitId)>$k;$k++){
					$visit_Id = $rslt_visitId[$k]['id'];
					$driver_gate_pass = $rslt_visitId[$k]['driver_gate_pass'];
					$assistant_gate_pass = $rslt_visitId[$k]['assistant_gate_pass'];

					$query_txEntry = "INSERT INTO vcms_online_transaction(visit_id,trans_id,trans_time,val_id,amount,store_amount,currency,bank_tran_id,card_no,card_issuer,card_brand,cus_name,cus_phone) VALUES('$visit_Id','$tran_id','$tran_date','$val_id','$tran_amount','$store_amount','$currency','$bank_tran_id','$card_no','$card_issuer','$card_brand','$cus_name','$cus_phone')";
					$insert_st = $this->bm->dataInsertDB1($query_txEntry);

					if($insert_st == 1){
						$url = "http://10.1.100.105:8095/tosevent.php?VISITNO=".$visit_Id."&EVENT=ISSUE&DRIVER=".$driver_gate_pass."&HELPER=".$assistant_gate_pass;
						$json = file_get_contents($url);
						$obj = json_decode($json);
					}
				}

				$query_update = "UPDATE do_truck_details_entry SET paid_amt = '$amount', paid_status = 1 , paid_method = 'online' , paid_collect_dt = NOW() WHERE cont_no = '$cont' AND import_rotation = '$rot' AND paid_status = 0";
				$this->bm->dataUpdateDB1($query_update);

			}


			//echo "  STATUS: ".$status."- TRANSACTION DATE: ".$tran_date."- TRANSACTION ID: ".$tran_id."- PAYMENT TYPE: ".$card_type;

		}


		//Storing Session  - start
		$result=$this->db->query("select *,md5(new_pass) as dpass,IF(DATEDIFF(Expire_date,NOW())<=0,1,IF(Expire_date IS NULL,1,0)) AS isexpired from users where login_id='$login_id'");
		//echo "select *,md5(new_pass) as dpass from users where login_id='$username'";
		if($result->num_rows() > 0)
		{
			$row = $result->row();
			$mdata=$row->org_Type_id;
			$mdatap=$row->login_password;
			$userPass1=$row->dpass;					
		}

		$mdata_org="";
		$result_org=$this->db->query("select * from tbl_org_types where id='$mdata'");
		//echo "select * from tbl_org_types where id='$mdata'";
		if($result_org->num_rows() > 0)
		{
			$row_org =$result_org->row();
			$mdata_org=$row_org->Org_Type;
		}

		$mdata_license="";
		$mdata_Organization_Name="";
		$result_license=$this->db->query("select * from organization_profiles where id='$row->org_id'");
		//echo ("select * from organization_profiles where id='$row->org_id'");
		if($result_license->num_rows()>0)
		{
			$row_license =$result_license->row();
			$mdata_license=$row_license->License_No;
			$mdata_Organization_Name=$row_license->Organization_Name;
		}

		$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>2,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
		'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
		'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));


		//Storing Session  - End

		
		$rotNo = "";
		$contNo = "";
		$verify_info_fcl_id = "";
		$verify_other_data_id = "";
		$query_contRot = "SELECT import_rotation,cont_no,verify_info_fcl_id,verify_other_data_id FROM do_truck_details_entry WHERE id = '$visitId'";
		$rslt_contRot = $this->bm->dataSelectDB1($query_contRot);

		for($i=0;$i<count($rslt_contRot);$i++){
			$rotNo = $rslt_contRot[$i]['import_rotation'];
			$contNo = $rslt_contRot[$i]['cont_no'];
			$verify_info_fcl_id = $rslt_contRot[$i]['verify_info_fcl_id'];
			$verify_other_data_id = $rslt_contRot[$i]['verify_other_data_id'];
		}

		$cont_status = "";

		if($verify_other_data_id == ""){
			$cont_status = "FCL";
		}
		else if($verify_info_fcl_id == "")
		{
			$cont_status = "LCL";
		}

		$msg = "";

		//echo '<pre>'; print_r($this->session->all_userdata());

		$this->cnfTruckEntryForm($rotNo,$contNo,$cont_status,$assignmentType,$msg);

	}

	function paymentSuccess()
	{	
		$val_id=urlencode($_POST['val_id']);
		$store_id=urlencode("cpatosgovbdlive");
		$store_passwd=urlencode("60A35D8110A3435338");
		$requested_url = ("https://securepay.sslcommerz.com/validator/api/validationserverAPI.php?val_id=".$val_id."&store_id=".$store_id."&store_passwd=".$store_passwd."&v=1&format=json");

		$handle = curl_init();
		curl_setopt($handle, CURLOPT_URL, $requested_url);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false); # IF YOU RUN FROM LOCAL PC
		curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false); # IF YOU RUN FROM LOCAL PC

		$result = curl_exec($handle);

		$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

		if($code == 200 && !( curl_errno($handle)))
		{

			# TO CONVERT AS ARRAY
			# $result = json_decode($result, true);
			# $status = $result['status'];

			# TO CONVERT AS OBJECT
			$result = json_decode($result);

			//print_r ($result);
			# TRANSACTION INFO
			$status = $result->status;
			$tran_date = $result->tran_date;
			$tran_id = $result->tran_id;
			$val_id = $result->val_id;
			$tran_amount = $result->amount;
			$store_amount = $result->store_amount;
			$currency = $result->currency;
			$bank_tran_id = $result->bank_tran_id;
			$card_type = $result->card_type;

			#CUSTOMER INFO
			$cus_name = $result->cus_name;
			$cus_phone = $result->cus_phone;

			 # EMI INFO
			$emi_instalment = $result->emi_instalment;
			$emi_amount = $result->emi_amount;
			$emi_description = $result->emi_description;
			$emi_issuer = $result->emi_issuer;

			# ISSUER INFO
			$card_no = $result->card_no;
			$card_issuer = $result->card_issuer;
			$card_brand = $result->card_brand;
			$card_issuer_country = $result->card_issuer_country;
			$card_issuer_country_code = $result->card_issuer_country_code; 

			# API AUTHENTICATION
			$APIConnect = $result->APIConnect;
			$validated_on = $result->validated_on;
			$gw_version = $result->gw_version;   

			# system Info
			$amount = $result->amount;
			$flag = $result->value_d;
			if($flag == 0)
			{
				$visitId = $result->value_a;
				$assignmentType = $result->value_b;
				$login_id = $result->value_c;

				$query_update = "UPDATE do_truck_details_entry SET paid_amt = '$amount', paid_status = 1 , paid_method = 'online' , paid_collect_dt = NOW() WHERE id = '$visitId'";
				$update_st = $this->bm->dataUpdateDB1($query_update);

				$query_Truck = "SELECT driver_gate_pass,assistant_gate_pass FROM do_truck_details_entry WHERE id='$visitId'";
				$data_truck = $this->bm->dataSelectDB1($query_Truck);

				$driver_gate_pass = "";
				$assistant_gate_pass = "";

				for($i=0;$i<count($data_truck);$i++){
					$driver_gate_pass = $data_truck[$i]['driver_gate_pass'];
					$assistant_gate_pass = $data_truck[$i]['assistant_gate_pass'];
				}

				if($update_st == 1){
					$url = "http://10.1.100.105:8095/tosevent.php?VISITNO=".$visitId."&EVENT=ISSUE&DRIVER=".$driver_gate_pass."&HELPER=".$assistant_gate_pass;
					$json = file_get_contents($url);
					$obj = json_decode($json);
				}

				$query_txEntry = "INSERT INTO vcms_online_transaction (visit_id,trans_id,trans_time,val_id,amount,store_amount,currency,bank_tran_id,card_no,card_issuer,card_brand,cus_name,cus_phone) VALUES ('$visitId','$tran_id','$tran_date','$val_id','$tran_amount','$store_amount','$currency','$bank_tran_id','$card_no','$card_issuer','$card_brand','$cus_name','$cus_phone')";
				$this->bm->dataInsertDB1($query_txEntry);
			}
			else if($flag == 1)
			{
				$rotCont = $result->value_a;
				$data = explode("_",$rotCont);
				$cont = $data[0];
				$rot = $data[1];
				$assignmentType = $result->value_b;
				$login_id = $result->value_c;

				//Insert Query Done First due to fetcing only which truck visit id is not paid
				$query_visitId = "SELECT id,driver_gate_pass,assistant_gate_pass FROM do_truck_details_entry WHERE cont_no = '$cont' AND import_rotation = '$rot' AND paid_status = 0";
				$rslt_visitId = $this->bm->dataSelectDB1($query_visitId);

				$visit_Id = "";
				$driver_gate_pass = "";
				$assistant_gate_pass = "";

				for($k = 0;count($rslt_visitId)>$k;$k++){
					$visit_Id = $rslt_visitId[$k]['id'];
					$driver_gate_pass = $rslt_visitId[$k]['driver_gate_pass'];
					$assistant_gate_pass = $rslt_visitId[$k]['assistant_gate_pass'];

					$query_txEntry = "INSERT INTO vcms_online_transaction(visit_id,trans_id,trans_time,val_id,amount,store_amount,currency,bank_tran_id,card_no,card_issuer,card_brand,cus_name,cus_phone) VALUES('$visit_Id','$tran_id','$tran_date','$val_id','$tran_amount','$store_amount','$currency','$bank_tran_id','$card_no','$card_issuer','$card_brand','$cus_name','$cus_phone')";
					$insert_st = $this->bm->dataInsertDB1($query_txEntry);

					if($insert_st == 1){
						$url = "http://10.1.100.105:8095/tosevent.php?VISITNO=".$visit_Id."&EVENT=ISSUE&DRIVER=".$driver_gate_pass."&HELPER=".$assistant_gate_pass;
						$json = file_get_contents($url);
						$obj = json_decode($json);
					}
				}

				$query_update = "UPDATE do_truck_details_entry SET paid_amt = '$amount', paid_status = 1 , paid_method = 'online' , paid_collect_dt = NOW() WHERE cont_no = '$cont' AND import_rotation = '$rot' AND paid_status = 0";
				$this->bm->dataUpdateDB1($query_update);

			}


			//echo "  STATUS: ".$status."- TRANSACTION DATE: ".$tran_date."- TRANSACTION ID: ".$tran_id."- PAYMENT TYPE: ".$card_type;

		}


		//Storing Session  - start
		$result=$this->db->query("select *,md5(new_pass) as dpass,IF(DATEDIFF(Expire_date,NOW())<=0,1,IF(Expire_date IS NULL,1,0)) AS isexpired from users where login_id='$login_id'");
		//echo "select *,md5(new_pass) as dpass from users where login_id='$username'";
		if($result->num_rows() > 0)
		{
			$row = $result->row();
			$mdata=$row->org_Type_id;
			$mdatap=$row->login_password;
			$userPass1=$row->dpass;					
		}

		$mdata_org="";
		$result_org=$this->db->query("select * from tbl_org_types where id='$mdata'");
		//echo "select * from tbl_org_types where id='$mdata'";
		if($result_org->num_rows() > 0)
		{
			$row_org =$result_org->row();
			$mdata_org=$row_org->Org_Type;
		}

		$mdata_license="";
		$mdata_Organization_Name="";
		$result_license=$this->db->query("select * from organization_profiles where id='$row->org_id'");
		//echo ("select * from organization_profiles where id='$row->org_id'");
		if($result_license->num_rows()>0)
		{
			$row_license =$result_license->row();
			$mdata_license=$row_license->License_No;
			$mdata_Organization_Name=$row_license->Organization_Name;
		}

		$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>2,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
		'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
		'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));


		//Storing Session  - End

		
		$rotNo = "";
		$contNo = "";
		$verify_info_fcl_id = "";
		$verify_other_data_id = "";
		$query_contRot = "SELECT import_rotation,cont_no,verify_info_fcl_id,verify_other_data_id FROM do_truck_details_entry WHERE id = '$visitId'";
		$rslt_contRot = $this->bm->dataSelectDB1($query_contRot);

		for($i=0;$i<count($rslt_contRot);$i++){
			$rotNo = $rslt_contRot[$i]['import_rotation'];
			$contNo = $rslt_contRot[$i]['cont_no'];
			$verify_info_fcl_id = $rslt_contRot[$i]['verify_info_fcl_id'];
			$verify_other_data_id = $rslt_contRot[$i]['verify_other_data_id'];
		}

		$cont_status = "";

		if($verify_other_data_id == ""){
			$cont_status = "FCL";
		}
		else if($verify_info_fcl_id == "")
		{
			$cont_status = "LCL";
		}

		$msg = "";

		//echo '<pre>'; print_r($this->session->all_userdata());

		$this->cnfTruckEntryForm($rotNo,$contNo,$cont_status,$assignmentType,$msg);

	}

	function onlinePaymentSuccess()
	{	
		$val_id=urlencode($_POST['val_id']);
		$store_id=urlencode("cpatosgovbdlive");
		$store_passwd=urlencode("60A35D8110A3435338");
		$requested_url = ("https://securepay.sslcommerz.com/validator/api/validationserverAPI.php?val_id=".$val_id."&store_id=".$store_id."&store_passwd=".$store_passwd."&v=1&format=json");

		$handle = curl_init();
		curl_setopt($handle, CURLOPT_URL, $requested_url);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false); # IF YOU RUN FROM LOCAL PC
		curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false); # IF YOU RUN FROM LOCAL PC

		$result = curl_exec($handle);

		$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

		if($code == 200 && !( curl_errno($handle)))
		{

			# TO CONVERT AS ARRAY
			# $result = json_decode($result, true);
			# $status = $result['status'];

			# TO CONVERT AS OBJECT
			$result = json_decode($result);

			//print_r ($result);
			# TRANSACTION INFO
			$status = $result->status;
			$tran_date = $result->tran_date;
			$tran_id = $result->tran_id;
			$val_id = $result->val_id;
			$tran_amount = $result->amount;
			$store_amount = $result->store_amount;
			$currency = $result->currency;
			$bank_tran_id = $result->bank_tran_id;
			$card_type = $result->card_type;

			#CUSTOMER INFO
			$cus_name = $result->cus_name;
			$cus_phone = $result->cus_phone;

			 # EMI INFO
			$emi_instalment = $result->emi_instalment;
			$emi_amount = $result->emi_amount;
			$emi_description = $result->emi_description;
			$emi_issuer = $result->emi_issuer;

			# ISSUER INFO
			$card_no = $result->card_no;
			$card_issuer = $result->card_issuer;
			$card_brand = $result->card_brand;
			$card_issuer_country = $result->card_issuer_country;
			$card_issuer_country_code = $result->card_issuer_country_code; 

			# API AUTHENTICATION
			$APIConnect = $result->APIConnect;
			$validated_on = $result->validated_on;
			$gw_version = $result->gw_version;   

			# system Info
			$amount = $result->amount;
			$flag = $result->value_d;
			if($flag == 0)
			{
				$visitId = $result->value_a;
				$assignmentType = $result->value_b;
				$login_id = $result->value_c;

				$query_update = "UPDATE do_truck_details_entry SET paid_amt = '$amount', paid_status = 1 , paid_method = 'online' , paid_collect_dt = NOW() WHERE id = '$visitId'";
				$update_st = $this->bm->dataUpdateDB1($query_update);

				$query_Truck = "SELECT driver_gate_pass,assistant_gate_pass FROM do_truck_details_entry WHERE id='$visitId'";
				$data_truck = $this->bm->dataSelectDB1($query_Truck);

				$driver_gate_pass = "";
				$assistant_gate_pass = "";

				for($i=0;$i<count($data_truck);$i++){
					$driver_gate_pass = $data_truck[$i]['driver_gate_pass'];
					$assistant_gate_pass = $data_truck[$i]['assistant_gate_pass'];
				}

				if($update_st == 1){
					$url = "http://10.1.100.105:8095/tosevent.php?VISITNO=".$visitId."&EVENT=ISSUE&DRIVER=".$driver_gate_pass."&HELPER=".$assistant_gate_pass;
					$json = file_get_contents($url);
					$obj = json_decode($json);
				}

				$query_txEntry = "INSERT INTO vcms_online_transaction (visit_id,trans_id,trans_time,val_id,amount,store_amount,currency,bank_tran_id,card_no,card_issuer,card_brand,cus_name,cus_phone) VALUES ('$visitId','$tran_id','$tran_date','$val_id','$tran_amount','$store_amount','$currency','$bank_tran_id','$card_no','$card_issuer','$card_brand','$cus_name','$cus_phone')";
				$this->bm->dataInsertDB1($query_txEntry);
			}
			else if($flag == 1)
			{
				$rotCont = $result->value_a;
				$data = explode("_",$rotCont);
				$cont = $data[0];
				$rot = $data[1];
				$assignmentType = $result->value_b;
				$login_id = $result->value_c;

				//Insert Query Done First due to fetcing only which truck visit id is not paid
				$query_visitId = "SELECT id,driver_gate_pass,assistant_gate_pass FROM do_truck_details_entry WHERE cont_no = '$cont' AND import_rotation = '$rot' AND paid_status = 0";
				$rslt_visitId = $this->bm->dataSelectDB1($query_visitId);

				$visit_Id = "";
				$driver_gate_pass = "";
				$assistant_gate_pass = "";

				for($k = 0;count($rslt_visitId)>$k;$k++){
					$visit_Id = $rslt_visitId[$k]['id'];
					$driver_gate_pass = $rslt_visitId[$k]['driver_gate_pass'];
					$assistant_gate_pass = $rslt_visitId[$k]['assistant_gate_pass'];

					$query_txEntry = "INSERT INTO vcms_online_transaction(visit_id,trans_id,trans_time,val_id,amount,store_amount,currency,bank_tran_id,card_no,card_issuer,card_brand,cus_name,cus_phone) VALUES('$visit_Id','$tran_id','$tran_date','$val_id','$tran_amount','$store_amount','$currency','$bank_tran_id','$card_no','$card_issuer','$card_brand','$cus_name','$cus_phone')";
					$insert_st = $this->bm->dataInsertDB1($query_txEntry);

					if($insert_st == 1){
						$url = "http://10.1.100.105:8095/tosevent.php?VISITNO=".$visit_Id."&EVENT=ISSUE&DRIVER=".$driver_gate_pass."&HELPER=".$assistant_gate_pass;
						$json = file_get_contents($url);
						$obj = json_decode($json);
					}
				}

				$query_update = "UPDATE do_truck_details_entry SET paid_amt = '$amount', paid_status = 1 , paid_method = 'online' , paid_collect_dt = NOW() WHERE cont_no = '$cont' AND import_rotation = '$rot' AND paid_status = 0";
				$this->bm->dataUpdateDB1($query_update);

			}


			//echo "  STATUS: ".$status."- TRANSACTION DATE: ".$tran_date."- TRANSACTION ID: ".$tran_id."- PAYMENT TYPE: ".$card_type;

		}


		//Storing Session  - start
		$result=$this->db->query("select *,md5(new_pass) as dpass,IF(DATEDIFF(Expire_date,NOW())<=0,1,IF(Expire_date IS NULL,1,0)) AS isexpired from users where login_id='$login_id'");
		//echo "select *,md5(new_pass) as dpass from users where login_id='$username'";
		if($result->num_rows() > 0)
		{
			$row = $result->row();
			$mdata=$row->org_Type_id;
			$mdatap=$row->login_password;
			$userPass1=$row->dpass;					
		}

		$mdata_org="";
		$result_org=$this->db->query("select * from tbl_org_types where id='$mdata'");
		//echo "select * from tbl_org_types where id='$mdata'";
		if($result_org->num_rows() > 0)
		{
			$row_org =$result_org->row();
			$mdata_org=$row_org->Org_Type;
		}

		$mdata_license="";
		$mdata_Organization_Name="";
		$result_license=$this->db->query("select * from organization_profiles where id='$row->org_id'");
		//echo ("select * from organization_profiles where id='$row->org_id'");
		if($result_license->num_rows()>0)
		{
			$row_license =$result_license->row();
			$mdata_license=$row_license->License_No;
			$mdata_Organization_Name=$row_license->Organization_Name;
		}

		$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>2,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
		'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
		'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));


		//Storing Session  - End

		
		$rotNo = "";
		$contNo = "";
		$verify_info_fcl_id = "";
		$verify_other_data_id = "";
		$query_contRot = "SELECT import_rotation,cont_no,verify_info_fcl_id,verify_other_data_id FROM do_truck_details_entry WHERE id = '$visitId'";
		$rslt_contRot = $this->bm->dataSelectDB1($query_contRot);

		for($i=0;$i<count($rslt_contRot);$i++){
			$rotNo = $rslt_contRot[$i]['import_rotation'];
			$contNo = $rslt_contRot[$i]['cont_no'];
			$verify_info_fcl_id = $rslt_contRot[$i]['verify_info_fcl_id'];
			$verify_other_data_id = $rslt_contRot[$i]['verify_other_data_id'];
		}

		$cont_status = "";

		if($verify_other_data_id == ""){
			$cont_status = "FCL";
		}
		else if($verify_info_fcl_id == "")
		{
			$cont_status = "LCL";
		}

		$msg = "";

		//echo '<pre>'; print_r($this->session->all_userdata());

		$this->cnfTruckEntryForm($rotNo,$contNo,$cont_status,$assignmentType,$msg);

	}
	
	function addTruckToDoDtl()
	{		
		$msg = "";
		$login_id = $this->session->userdata('login_id');		
		$ipaddr = $_SERVER['REMOTE_ADDR'];
		
		$regCity = $this->input->post('regCity');
		$regClass = $this->input->post('regClass');
		$truckNo = trim($this->input->post('truckNo'));
		
		$truckId = 	$regCity." ".$regClass." ".$truckNo;
		
		$driverName = $this->input->post('driverName');
		$driverPassNo = $this->input->post('driverPassNo');								
		$assistantName = $this->input->post('assistantName');									
		$assistantPassNo = $this->input->post('assistantPassNo');
		$importerMobileNo = $this->input->post('importerMobileNo');	
		$importerMobileNo = str_replace("-","",$importerMobileNo);
		$agencyName = $this->input->post('agencyName');	
		$agencyName = str_replace("'"," ",$agencyName);	
		$agencyPhone = $this->input->post('agencyPhone');		
		// $res = str_replace( array( '\'', '"',',' , ';', '<', '>' ), ' ', $str); 
		
		$rotNo = $this->input->post('rotNo');
		$contNo = $this->input->post('contNo');
		$vrfyInfoFclId = $this->input->post('vrfyInfoFclId');
		
		$cont_status = $this->input->post('cont_status');
		$assignmentType = $this->input->post('assignmentType');
		$totTruck = $this->input->post('totTruck');
		
		$frmSlot = $this->input->post('truckSlot');			// added on 2021-03-01		
		$addBtn = $this->input->post('addBtn');
		
		$emrgncy_flag = 0;
		$emrgncy_approve_stat = 0;
		if($addBtn=="Emergency")
		{
			$emrgncy_flag = 1;	
		}
		
		$strUpdateSlot = "UPDATE ctmsmis.tmp_vcms_assignment SET assignment_slot='$frmSlot' WHERE cont_no='$contNo' AND rot_no='$rotNo'";
		$this->bm->dataUpdate($strUpdateSlot);
								
		$sql_timeSlot = "SELECT assignment_slot,assignmentDate,DATE_ADD(assignmentDate, INTERVAL 1 DAY) AS nxtDt
		FROM ctmsmis.tmp_vcms_assignment
		WHERE rot_no='$rotNo' AND cont_no='$contNo' AND assignmentDate>=DATE(NOW())";
		$rslt_timeSlot = $this->bm->dataSelect($sql_timeSlot);
		
		$asDt = "";
		$asSlot = "";	// commented on 2021-03-01
		$nxtDt = "";
		
		for($j=0;$j<count($rslt_timeSlot);$j++)
		{
			$asDt = $rslt_timeSlot[$j]['assignmentDate'];
			$asSlot = $rslt_timeSlot[$j]['assignment_slot'];
			$nxtDt = $rslt_timeSlot[$j]['nxtDt'];
		}
		$sSlot = "";
		$eSlot = "";
		if($asSlot==1)
		{
			$sSlot = $asDt." 08:00:00";
			$eSlot = $asDt." 15:59:59";
		}
		else if($asSlot==2)
		{
			$sSlot = $asDt." 16:00:00";
			$eSlot = $asDt." 23:59:59";
		}
		else
		{
			$sSlot = $nxtDt." 00:00:00";
			$eSlot = $nxtDt." 07:59:59";
		}
		$payAmt = 57.5;			
															
		if($this->input->post('editFormId'))
		{
			$editFormId = $this->input->post('editFormId');
			$editType = $this->input->post('editType');
			
			if($editType == "Replace")
			{				
				$sql_replaceInfo = "SELECT id,truck_id,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,paid_amt,paid_method,paid_collect_dt,gate_in_status,gate_in_by,gate_in_time,paid_collect_by,pay_collect_ip,import_rotation,cont_no
				FROM do_truck_details_entry
				WHERE id='$editFormId'";
				$rslt_replaceInfo = $this->bm->dataSelectDB1($sql_replaceInfo);
				
				$repVisitId = $rslt_replaceInfo[0]['id'];
				$repTruckId = $rslt_replaceInfo[0]['truck_id'];
				$repDriverName = $rslt_replaceInfo[0]['driver_name'];
				$repDriverGatePass = $rslt_replaceInfo[0]['driver_gate_pass'];
				$repAssistantName = $rslt_replaceInfo[0]['assistant_name'];
				$repAssistantGatePass = $rslt_replaceInfo[0]['assistant_gate_pass'];
				$repPaidAmt = $rslt_replaceInfo[0]['paid_amt'];
				$repPaidMethod = $rslt_replaceInfo[0]['paid_method'];
				$repPaidCollectDt = $rslt_replaceInfo[0]['paid_collect_dt'];
				$paid_collect_by = $rslt_replaceInfo[0]['paid_collect_by'];
				$pay_collect_ip = $rslt_replaceInfo[0]['pay_collect_ip'];
				$repGateInStatus = $rslt_replaceInfo[0]['gate_in_status'];
				$repGateInBy = $rslt_replaceInfo[0]['gate_in_by'];
				$repGateInTime = $rslt_replaceInfo[0]['gate_in_time'];
				$import_rotation = $rslt_replaceInfo[0]['import_rotation'];
				$cont_no = $rslt_replaceInfo[0]['cont_no'];
				
				// $sql_insertReplace = "INSERT INTO vcms_replace_truck_log(visit_id,truck_id,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,paid_amt,paid_method,paid_collect_dt,replace_time,replace_by)
				// VALUES('$repVisitId','$repTruckId','$repDriverName','$repDriverGatePass','$repAssistantName','$repAssistantGatePass','$repPaidAmt','$repPaidMethod','$repPaidCollectDt',NOW(),'$login_id')";
				
				$sql_insertReplace = "INSERT INTO vcms_replace_truck_log(visit_id,truck_id,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,paid_amt,paid_method,paid_collect_dt,replace_time,replace_by,gate_in_status,gate_in_time,gate_in_by,paid_collect_by,pay_collect_ip,import_rotation,cont_no)
				VALUES('$repVisitId','$repTruckId','$repDriverName','$repDriverGatePass','$repAssistantName','$repAssistantGatePass','$repPaidAmt','$repPaidMethod','$repPaidCollectDt',NOW(),'$login_id','$repGateInStatus','$repGateInTime','$repGateInBy','$paid_collect_by','$pay_collect_ip','$import_rotation','$cont_no')";
				$this->bm->dataInsertDB1($sql_insertReplace);
				
				// $sql_updateTruckInfo = "UPDATE do_truck_details_entry
				// SET truck_id='$truckId',driver_name='$driverName',driver_gate_pass='$driverPassNo',assistant_name='$assistantName',assistant_gate_pass='$assistantPassNo',truck_agency_name='$agencyName',truck_agency_phone='$agencyPhone',paid_amt='',paid_status=0,paid_method=''
				// WHERE id='$editFormId'";
				
				// $sql_updateTruckInfo = "UPDATE do_truck_details_entry
				// SET truck_id='$truckId',driver_name='$driverName',driver_gate_pass='$driverPassNo',assistant_name='$assistantName',assistant_gate_pass='$assistantPassNo',truck_agency_name='$agencyName',truck_agency_phone='$agencyPhone',paid_amt='',paid_status=0,paid_method='',gate_in_status='0',gate_in_by=NULL,gate_in_time=NULL
				// WHERE id='$editFormId'";
				
				$sql_updateTruckInfo = "UPDATE do_truck_details_entry
				SET truck_id='$truckId',driver_name='$driverName',driver_gate_pass='$driverPassNo',assistant_name='$assistantName',assistant_gate_pass='$assistantPassNo',truck_agency_name='$agencyName',truck_agency_phone='$agencyPhone',paid_amt='',paid_status=0,paid_method='',gate_in_status='0',gate_in_by=NULL,gate_in_time=NULL,last_update=NOW()
				WHERE id='$editFormId'";
				$this->bm->dataUpdateDB1($sql_updateTruckInfo);	
			}
			// else if($editType == "Edit")			// check with it later
			else
			{				
				// $sql_updateTruckInfo = "UPDATE do_truck_details_entry
				// SET truck_id='$truckId',driver_name='$driverName',driver_gate_pass='$driverPassNo',assistant_name='$assistantName',assistant_gate_pass='$assistantPassNo',truck_agency_name='$agencyName',truck_agency_phone='$agencyPhone'
				// WHERE id='$editFormId'";
				
				$sql_updateTruckInfo = "UPDATE do_truck_details_entry
				SET truck_id='$truckId',driver_name='$driverName',driver_gate_pass='$driverPassNo',assistant_name='$assistantName',assistant_gate_pass='$assistantPassNo',truck_agency_name='$agencyName',truck_agency_phone='$agencyPhone',edit_at=NOW(),edit_by='$login_id',edit_ip='$ipaddr'
				WHERE id='$editFormId'";
				$this->bm->dataUpdateDB1($sql_updateTruckInfo);	
			}
			
		}			
		else							
		{
			$sql_chkTruck = "SELECT COUNT(*) AS rtnValue
			FROM do_truck_details_entry 
			WHERE truck_id='$truckId' AND visit_time_slot_start='$sSlot' AND visit_time_slot_end='$eSlot'";
			$rslt_chkTruck = $this->bm->dataSelectDB1($sql_chkTruck);
			$chkTruck = $rslt_chkTruck[0]['rtnValue'];
			
			if($chkTruck==0)
			{
				$strInsertEq = "INSERT INTO do_truck_details_entry(verify_info_fcl_id,import_rotation,cont_no,truck_id,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,truck_agency_name,truck_agency_phone,update_by,ip_addr,last_update,emrgncy_flag,emrgncy_approve_stat,visit_time_slot_start,visit_time_slot_end)
				VALUES('$vrfyInfoFclId','$rotNo','$contNo','$truckId','$driverName','$driverPassNo','$assistantName','$assistantPassNo','$agencyName','$agencyPhone','$login_id','$ipaddr',NOW(),'$emrgncy_flag','$emrgncy_approve_stat','$sSlot','$eSlot')";
				// echo $strInsertEq;
				// return;
				$stat = $this->bm->dataInsertDB1($strInsertEq);
				
				if($stat == 1)
					$msg = "<font color='green'><b>Truck added successfully</b></font>";
				
			}
			else
			{
				$msg = "<font color='red'><b>This truck was assigned for this time slot previously</b></font>";
			}				
		}							
				
		$sql_updateImporterMbl = "UPDATE verify_info_fcl
		SET importer_mobile_no='$importerMobileNo'
		WHERE id='$vrfyInfoFclId'";
		$this->bm->dataUpdateDB1($sql_updateImporterMbl);

		$sql_updateSlot = "UPDATE verify_info_fcl
		SET truck_slot = '$asSlot'
		WHERE id='$vrfyInfoFclId'";
		$this->bm->dataUpdateDB1($sql_updateSlot);
		
		$this->cnfTruckEntryForm($rotNo,$contNo,$cont_status,$assignmentType,$msg);
	}
	
	// function cnfTruckPayForm()
	// {
	// 	$msg = "";
	// 	$rotNo = $this->input->post('rotNo');
	// 	$contNo = $this->input->post('contNo');
	// 	$cont_status = $this->input->post('cont_status');
	// 	$assignmentType = $this->input->post('assignmentType');
		
	// 	$this->cnfTruckEntryForm($rotNo,$contNo,$cont_status,$assignmentType,$msg);
	// }
	
	// function cnfTruckPay()
	// {
	// 	$msg = "";
	// 	$rotNo = $this->input->post('rotNo');
	// 	$contNo = $this->input->post('contNo');
	// 	$cont_status = $this->input->post('cont_status');
	// 	$assignmentType = $this->input->post('assignmentType');
			
	// 	if($this->input->post('payType')=="singlePay")
	// 	{						
	// 		$truckDtlId = $this->input->post('truckDtlId');
	// 		$payAmt = $this->input->post('payAmt');
	// 		$payMethod = $this->input->post('payMethod');
						
	// 		$sql_updatePayment = "UPDATE do_truck_details_entry
	// 		SET paid_amt='$payAmt',paid_status='2',paid_method='$payMethod'
	// 		WHERE id='$truckDtlId'";
	// 		$stat = $this->bm->dataUpdateDB1($sql_updatePayment);						
	// 	}
	// 	else if($this->input->post('payType')=="allPay")
	// 	{			
	// 		$totalAmtToPay = $this->input->post('payAmt');
	// 		$vrfyInfoFclId = $this->input->post('vrfyInfoFclId');
	// 		$payAmt = 57.5;
	// 		$payMethod = "cash";
			
	// 		// $sql_updateAllPay = "UPDATE do_truck_details_entry
	// 		// SET paid_amt='$payAmt',paid_method='$payMethod'
	// 		// WHERE verify_info_fcl_id='$vrfyInfoFclId' AND paid_amt IS NULL";

	// 		$sql_updateAllPay = "UPDATE do_truck_details_entry
	// 		SET paid_amt='$payAmt',paid_status='2',paid_method='$payMethod'
	// 		WHERE verify_info_fcl_id='$vrfyInfoFclId' AND paid_status='0' AND (emrgncy_flag='0' OR emrgncy_approve_stat='1')";
	// 		$stat = $this->bm->dataUpdateDB1($sql_updateAllPay);
	// 	}
		
	// 	$this->cnfTruckEntryForm($rotNo,$contNo,$cont_status,$assignmentType,$msg);
	// }

	function cnfTruckPayForm()
	{
		$msg = "";
		$rotNo = $this->input->post('rotNo');
		$contNo = $this->input->post('contNo');
		$cont_status = $this->input->post('cont_status');
		$assignmentType = $this->input->post('assignmentType');
		$blNo = $this->input->post('blNo');

		if($cont_status == "LCL")
		{
			$this->cnfTruckEntryLCL($rotNo,$blNo,$cont_status,"","");
		}
		else if($cont_status == "FCL")
		{
			$this->cnfTruckEntryForm($rotNo,$contNo,$cont_status,$assignmentType,$msg);
		}
		
	}

	// function cnfTruckPay()
	// {
	// 	$msg = "";
	// 	$rotNo = $this->input->post('rotNo');
	// 	$contNo = $this->input->post('contNo');
	// 	$cont_status = $this->input->post('cont_status');
	// 	$assignmentType = $this->input->post('assignmentType');
	// 	$blNo = $this->input->post('blNo');
	// 	$payment = $this->input->post('payment');

	// 	if($this->input->post('payType')=="singlePay")
	// 	{						
	// 		$truckDtlId = $this->input->post('truckDtlId');
	// 		$payAmt = $this->input->post('payAmt');
	// 		$payMethod = $this->input->post('payMethod');
						
	// 		$sql_updatePayment = "UPDATE do_truck_details_entry
	// 		SET paid_amt='$payAmt',paid_status='2',paid_method='$payMethod'
	// 		WHERE id='$truckDtlId'";
	// 		$stat = $this->bm->dataUpdateDB1($sql_updatePayment);						
	// 	}
	// 	else if($this->input->post('payType')=="allPay")
	// 	{			
	// 		$totalAmtToPay = $this->input->post('payAmt');
	// 		$payAmt = 57.5;
	// 		$payMethod = "cash";

	// 		if($cont_status == "LCL")
	// 		{
	// 			$vrfyOtherDataId = $this->input->post('vrfyOtherDataId');
	// 			$sql_updateAllPay = "UPDATE do_truck_details_entry
	// 			SET paid_amt='$payAmt',paid_status='2',paid_method='$payMethod'
	// 			WHERE verify_other_data_id='$vrfyOtherDataId' AND paid_status='0' AND (emrgncy_flag='0' OR emrgncy_approve_stat='1')";
	// 			$stat = $this->bm->dataUpdateDB1($sql_updateAllPay);
	// 		}
	// 		else if($cont_status == "FCL")
	// 		{
	// 			$vrfyInfoFclId = $this->input->post('vrfyInfoFclId'); 
	// 			$sql_updateAllPay = "UPDATE do_truck_details_entry
	// 			SET paid_amt='$payAmt',paid_status='2',paid_method='$payMethod'
	// 			WHERE verify_info_fcl_id='$vrfyInfoFclId' AND paid_status='0' AND (emrgncy_flag='0' OR emrgncy_approve_stat='1')";
	// 			$stat = $this->bm->dataUpdateDB1($sql_updateAllPay);
	// 		}
			
	// 		// $sql_updateAllPay = "UPDATE do_truck_details_entry
	// 		// SET paid_amt='$payAmt',paid_method='$payMethod'
	// 		// WHERE verify_info_fcl_id='$vrfyInfoFclId' AND paid_amt IS NULL";

			
	// 	}
		
		
	// 	if($cont_status == "LCL")
	// 	{
	// 		$this->cnfTruckEntryLCL($rotNo,$blNo,$cont_status,"","");
	// 	}
	// 	else if($cont_status == "FCL")
	// 	{
	// 		$this->cnfTruckEntryForm($rotNo,$contNo,$cont_status,$assignmentType,$msg);
	// 	}
	// }

	function cnfTruckPay()
	{
		$msg = "";
		$rotNo = $this->input->post('rotNo');
		$contNo = $this->input->post('contNo');
		$cont_status = $this->input->post('cont_status');
		$assignmentType = $this->input->post('assignmentType');
		$blNo = $this->input->post('blNo');
		$contact = $this->input->post('contact');
		$payment = $this->input->post('payment');

		if($payment == 'save')
		{
			if($this->input->post('payType')=="singlePay")
			{						
				$truckDtlId = $this->input->post('truckDtlId');
				$payAmt = $this->input->post('payAmt');
				$payMethod = $this->input->post('payMethod');
							
				$sql_updatePayment = "UPDATE do_truck_details_entry
				SET paid_amt='$payAmt',paid_status='2',paid_method='$payMethod'
				WHERE id='$truckDtlId'";
				$stat = $this->bm->dataUpdateDB1($sql_updatePayment);						
			}
			else if($this->input->post('payType')=="allPay")
			{			
				$totalAmtToPay = $this->input->post('payAmt');
				$payAmt = 57.5;
				$payMethod = "cash";

				if($cont_status == "LCL")
				{
					$vrfyOtherDataId = $this->input->post('vrfyOtherDataId');
					$sql_updateAllPay = "UPDATE do_truck_details_entry
					SET paid_amt='$payAmt',paid_status='2',paid_method='$payMethod'
					WHERE verify_other_data_id='$vrfyOtherDataId' AND paid_status='0' AND (emrgncy_flag='0' OR emrgncy_approve_stat='1')";
					$stat = $this->bm->dataUpdateDB1($sql_updateAllPay);
				}
				else if($cont_status == "FCL")
				{
					$vrfyInfoFclId = $this->input->post('vrfyInfoFclId'); 
					$sql_updateAllPay = "UPDATE do_truck_details_entry
					SET paid_amt='$payAmt',paid_status='2',paid_method='$payMethod'
					WHERE verify_info_fcl_id='$vrfyInfoFclId' AND paid_status='0' AND (emrgncy_flag='0' OR emrgncy_approve_stat='1')";
					$stat = $this->bm->dataUpdateDB1($sql_updateAllPay);
				}
				
				// $sql_updateAllPay = "UPDATE do_truck_details_entry
				// SET paid_amt='$payAmt',paid_method='$payMethod'
				// WHERE verify_info_fcl_id='$vrfyInfoFclId' AND paid_amt IS NULL";

				
			}
		}
		else if($payment == 'pay')
		{
			$this->checkoutAllbyOnline($contNo,$rotNo,$assignmentType,$contact);
			return;
		}
		
		
		if($cont_status == "LCL")
		{
			$this->cnfTruckEntryLCL($rotNo,$blNo,$cont_status,"","");
		}
		else if($cont_status == "FCL")
		{
			$this->cnfTruckEntryForm($rotNo,$contNo,$cont_status,$assignmentType,$msg);
		}
	}
	
	// function jettySarkarEntry()
	// {
	// 	$msg = "";
	// 	$login_id = $this->session->userdata('login_id');
	// 	$rotNo = $this->input->post('rotNo');
	// 	$contNo = $this->input->post('contNo');
	// 	$cont_status = $this->input->post('cont_status');
	// 	$assignmentType = $this->input->post('assignmentType');
	// 	$vrfyInfoFclId = $this->input->post('vrfyInfoFclId');
	// 	$jsName = $this->input->post('jsName');
	// 	$jsId = $this->input->post('jsId');
		
	// 	if(!$this->input->post('jettyedit'))
	// 	{
	// 		// chk jetty sarkar
	// 		// $sql_chkJS = "SELECT COUNT(*) AS rtnValue
	// 		// FROM verify_info_fcl
	// 		// WHERE jetty_sirkar_id='$jsName' AND id='$vrfyInfoFclId'";
			
	// 		$sql_chkJS = "SELECT COUNT(*) AS rtnValue
	// 		FROM verify_info_fcl
	// 		WHERE jetty_sirkar_id='$jsId' AND id='$vrfyInfoFclId'";
	// 		$rslt_chkJS = $this->bm->dataSelectDB1($sql_chkJS);
	// 		$chkJS = $rslt_chkJS[0]['rtnValue'];
			
	// 		if($chkJS == 0)
	// 		{
	// 			$prevJS = "";
	// 			// get previous JS	- check if previous exists
	// 			$sql_prevJS = "SELECT jetty_sirkar_id
	// 			FROM verify_info_fcl
	// 			WHERE id='$vrfyInfoFclId'";
	// 			$rslt_prevJS = $this->bm->dataSelectDB1($sql_prevJS);
	// 			$prevJS = $rslt_prevJS[0]['jetty_sirkar_id'];
				
	// 			// Insert into log
	// 			if($prevJS!="" or $prevJS!=null)
	// 			{
	// 				$sql_jsLog = "INSERT INTO vcms_jetty_sirkar_log(verify_info_fcl_id,prev_jetty_sirkar_id,replace_by,replace_dt)
	// 				VALUES('$vrfyInfoFclId','$prevJS','$login_id',NOW())";
	// 				$this->bm->dataInsertDB1($sql_jsLog);
	// 			}
				
	// 			// Update JS
	// 			$sql_updateJS = "UPDATE verify_info_fcl
	// 			SET jetty_sirkar_id='$jsId'
	// 			WHERE id='$vrfyInfoFclId'";
	// 			$this->bm->dataUpdateDB1($sql_updateJS);
	// 		}
	// 	}
		
	// 	$this->cnfTruckEntryForm($rotNo,$contNo,$cont_status,$assignmentType,$msg);
	// }			
	// truck entry - new function - end
	
	function dateWiseTallyListForm()
	{	
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$data['title']="DATE WISE TALLY LIST";
			$data['from_date']="";
			$data['to_date']="";
		
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('dateWisetallyListForm',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function searchDtWiseTally()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		$login_id = $this->session->userdata('login_id');
		$org_Type_id = $this->session->userdata('org_Type_id');
	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$from_date=$this->input->post('from_date');
			$to_date=$this->input->post('to_date');
			
			$section = $this->session->userdata('section');				
			$qryPrt = "";
			if($org_Type_id==59 or $org_Type_id==5)			//CPA Shed Users - cpashed (59) or cpacs1 (5)
			{
				$qryPrt = ",cpa_exchange_done_status AS exChngStatus ";
			}
			else if($org_Type_id==30)		// Berth Operator
			{
				$qryPrt = ",berth_exchange_done_status AS exChngStatus ";
			}
			else if($org_Type_id==2)		// C&F
			{
				$qryPrt = ",ff_exchange_done_status AS exChngStatus ";
			}
			
			// $str="SELECT tally_sheet_number,import_rotation,cont_number,SUM(rcv_pack) AS rcv_pack,SUM(flt_pack) AS flt_pack,SUM(shed_loc) AS shed_loc,loc_first,wr_date,shed_yard".$qryPrt." 
			// FROM shed_tally_info
			// INNER JOIN igm_supplimentary_detail ON shed_tally_info.igm_sup_detail_id = igm_supplimentary_detail.id
			// WHERE shed_yard='$section' AND (wr_date BETWEEN '$from_date' AND '$to_date')
			// GROUP BY tally_sheet_number
			// ORDER BY shed_tally_info.id DESC";
			 $str="SELECT tally_sheet_number, physical_tally_sheet_no, import_rotation, cont_number, SUM(rcv_pack) AS rcv_pack,SUM(flt_pack) AS flt_pack, SUM(flt_pack_loc) AS flt_pack_loc, 
			SUM(loc_first) AS loc_first, SUM(rcv_pack)+SUM(flt_pack)+ SUM(flt_pack_loc)+ SUM(loc_first) AS tot_pkg, wr_date,shed_yard".$qryPrt." 
			FROM shed_tally_info
			LEFT JOIN igm_supplimentary_detail ON shed_tally_info.igm_sup_detail_id = igm_supplimentary_detail.id
			LEFT JOIN igm_details ON igm_details.id=igm_supplimentary_detail.igm_detail_id 
			LEFT JOIN lcl_assignment_detail ON igm_details.id=lcl_assignment_detail.igm_detail_id 
			WHERE shed_yard='$section' AND (wr_date BETWEEN '$from_date' AND '$to_date')
			GROUP BY tally_sheet_number
			ORDER BY shed_tally_info.id DESC";
			//return;
			$rtnTallyList = $this->bm->dataSelectDb1($str);
			$this->data['rtnTallyList']=$rtnTallyList;
			
			$this->data['from_date']=$from_date;
			$this->data['to_date']=$to_date;
			
			
			$this->load->library('m_pdf');
			$html=$this->load->view('dateWiseTallyPDF',$this->data, true); //load the pdf_output.php by passing our data and get all data in $html varriable.
			 
			$pdfFilePath ="tallyReport-".time()."-download.pdf";

			$pdf = $this->m_pdf->load();
			$pdf->allow_charset_conversion = true;
			$pdf->charset_in = 'iso-8859-4';
			$stylesheet = file_get_contents('assets/stylesheets/test.css'); // external css
			//	$pdf->useSubstitutions = true; // optional - just as an example
				
			//$pdf->setFooter('Prepared By : '.$login_id.'|Page {PAGENO} of {nb}|Date {DATE j-m-Y}');
			$mpdf->shrink_tables_to_fit = 1;
			$pdf->WriteHTML($stylesheet,1);
			$pdf->WriteHTML($html,2);
				 
			$pdf->Output($pdfFilePath, "I"); // For Show Pdf
		}		
	}

	function shedWiseLyingTallyListForm()
	{	
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$data['title']="DATE WISE TALLY LIST";
			$data['from_date']="";
			$data['to_date']="";
		
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('shedWiseLyingTallyListForm',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function shedWiseLyingTallyListSearch()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		$login_id = $this->session->userdata('login_id');
		$org_Type_id = $this->session->userdata('org_Type_id');
	
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$from_date=$this->input->post('from_date');
			$to_date=$this->input->post('to_date');
			if($login_id =='admin' )
			{
				$shed_no=$this->input->post('shed_no');

			}
			
			$section = $this->session->userdata('section');				
			$qryPrt = "";
			if($org_Type_id==59 or $org_Type_id==5)			//CPA Shed Users - cpashed (59) or cpacs1 (5)
			{
				$qryPrt = ",cpa_exchange_done_status AS exChngStatus ";
			}
			else if($org_Type_id==30)		// Berth Operator
			{
				$qryPrt = ",berth_exchange_done_status AS exChngStatus ";
			}
			else if($org_Type_id==2)		// C&F
			{
				$qryPrt = ",ff_exchange_done_status AS exChngStatus ";
			}
			
			// $str="SELECT tally_sheet_number,import_rotation,cont_number,SUM(rcv_pack) AS rcv_pack,SUM(flt_pack) AS flt_pack,SUM(shed_loc) AS shed_loc,loc_first,wr_date,shed_yard".$qryPrt." 
			// FROM shed_tally_info
			// INNER JOIN igm_supplimentary_detail ON shed_tally_info.igm_sup_detail_id = igm_supplimentary_detail.id
			// WHERE shed_yard='$section' AND (wr_date BETWEEN '$from_date' AND '$to_date')
			// GROUP BY tally_sheet_number
			// ORDER BY shed_tally_info.id DESC";
			if($login_id =='admin' )
			{
				 $str="SELECT tally_sheet_number, physical_tally_sheet_no, import_rotation,  wr_date,shed_yard,
				IFNULL(igm_supplimentary_detail.BL_No, igm_supplimentary_detail.master_BL_No) AS BL_No, 
				IFNULL(shed_tally_info.actual_marks, IFNULL(igm_supplimentary_detail.Pack_Marks_Number, igm_details.Pack_Marks_Number)) AS marks,
				total_pack,rcv_unit".$qryPrt." 

				FROM shed_tally_info LEFT JOIN igm_supplimentary_detail ON shed_tally_info.igm_sup_detail_id = igm_supplimentary_detail.id LEFT JOIN igm_details ON igm_details.id=igm_supplimentary_detail.igm_detail_id LEFT JOIN lcl_assignment_detail ON igm_details.id=lcl_assignment_detail.igm_detail_id
				WHERE shed_yard='$shed_no' AND ( wr_date BETWEEN '$from_date' AND '$to_date')
				GROUP BY BL_No
				ORDER BY shed_tally_info.id DESC";
			}
			else
			{
				$str="SELECT tally_sheet_number, physical_tally_sheet_no, import_rotation,  wr_date,shed_yard,
				IFNULL(igm_supplimentary_detail.BL_No, igm_supplimentary_detail.master_BL_No) AS BL_No, 
				IFNULL(shed_tally_info.actual_marks, IFNULL(igm_supplimentary_detail.Pack_Marks_Number, igm_details.Pack_Marks_Number)) AS marks,
				total_pack,rcv_unit".$qryPrt." 

				FROM shed_tally_info LEFT JOIN igm_supplimentary_detail ON shed_tally_info.igm_sup_detail_id = igm_supplimentary_detail.id LEFT JOIN igm_details ON igm_details.id=igm_supplimentary_detail.igm_detail_id LEFT JOIN lcl_assignment_detail ON igm_details.id=lcl_assignment_detail.igm_detail_id
				WHERE shed_yard='$section' AND ( wr_date BETWEEN '$from_date' AND '$to_date')
				GROUP BY BL_No
				ORDER BY shed_tally_info.id DESC";
			}
			//echo $str ;
					//	return;
			$rtnTallyList = $this->bm->dataSelectDb1($str);
			$data['rtnTallyList']=$rtnTallyList;
			
			$data['from_date']=$from_date;
			$data['to_date']=$to_date;
			
			
		//	$this->load->library('m_pdf');
			$this->load->view('shedWiseLyingTallyList',$data); //load the pdf_output.php by passing our data and get all data in $html varriable.
		/* 	 
			$pdfFilePath ="tallyReport-".time()."-download.pdf";

			$pdf = $this->m_pdf->load();
			$pdf->allow_charset_conversion = true;
			$pdf->charset_in = 'iso-8859-4';
			$stylesheet = file_get_contents('assets/stylesheets/test.css'); 
			$mpdf->shrink_tables_to_fit = 1;
			$pdf->WriteHTML($stylesheet,1);
			$pdf->WriteHTML($html,2);
				 
			$pdf->Output($pdfFilePath, "I");  */
		}		
	}
	
	//Shed Delivery Order Starts----------------------
	function shedDeliveryOrderInfoForm()
	{	
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$data['title']="Shed Delivery Order Info Entry";
			$data['frmType']="new";
			$msg = "";
			$data['msg']=$msg;

			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('shedDOInfo',$data);
			$this->load->view('jsAssets');
		}
	}
	
	function shedDeliveryOrderInfoData()
	{	
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$rotNo = $this->input->post('rotNo');			
			$blno = $this->input->post('blno');			
			//$tallytype = $this->input->post('tallytype');
			$msgBLsearch = "";
			$msg = "";
			
			if($this->input->post('editFlag'))
			{
				// edit code block
				$id= $this->input->post('id');
				$data['editId'] = $id;
				$shedInfoByIdquery = "SELECT * FROM shed_mlo_do_info WHERE id='$id'";
				$shedInfoById = $this->bm->dataSelectDb1($shedInfoByIdquery);
				$cnf_lic_no = $shedInfoById[0]['cnf_lic_no'];
				$data['shedInfoById']=$shedInfoById;
				$data['edit'] = "edit";
				
				$sql_CNFName="SELECT id,name FROM sparcsn4.ref_bizunit_scoped WHERE role='SHIPPER' AND life_cycle_state='ACT' AND id='$cnf_lic_no'";	
				$cnf_name=$this->bm->dataSelect($sql_CNFName);
				//$cnfName = $cnf_name[0]['name'];
				$data['cnf_name']=$cnf_name;
			}else{
				$data['edit'] = "";
			}
			
			if($blno=="all")
			{
				$sqlQuery="SELECT Bill_of_Entry_No FROM igm_details WHERE Import_Rotation_No='$rotNo'";	
			}
			else
			{
				$sqlQuery="SELECT Bill_of_Entry_No FROM igm_details WHERE Import_Rotation_No='$rotNo' AND BL_No='$blno'";
			}
			$reslt = $this->bm->dataSelectDb1($sqlQuery);
			
			//---
			$resltBE = "";
			if(count($reslt)==0)
			{
				$sqlQuery="SELECT Bill_of_Entry_No FROM igm_supplimentary_detail WHERE Import_Rotation_No='$rotNo' AND BL_No='$blno'";
				$reslt = $this->bm->dataSelectDb1($sqlQuery);
			}
			//---
			
			if(count($reslt)>0){
				$resltBE = $reslt[0]['Bill_of_Entry_No'];
			}
			
			if($resltBE=="")
			{
				$msgBLsearch="<font color='red'><b>Bill of Entry Number not submitted, Please try again after submitting.</b></font>";
				
			}
			else
			{
				$msgBLsearch = "";
				
				$queryBLType="SELECT igm_details.BL_No, 'MASTER' AS bl_type FROM igm_detail_container 
							INNER JOIN igm_details ON igm_details.id=igm_detail_container.igm_detail_id 
							WHERE  igm_details.Import_Rotation_No='$rotNo' AND igm_details.BL_No='$blno'

							UNION

							SELECT igm_supplimentary_detail.BL_No AS sup_bl, 'FF' AS bl_type FROM igm_detail_container 
							INNER JOIN igm_details ON igm_details.id=igm_detail_container.igm_detail_id 
							INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.igm_detail_id=igm_details.id
							WHERE  igm_details.Import_Rotation_No='$rotNo' AND igm_supplimentary_detail.BL_No='$blno'";
				$blType=$this->bm->dataSelectDb1($queryBLType);
				$type_of_bl = $blType[0]['bl_type'];
				$data['type_of_bl']=$type_of_bl;
				
				if($type_of_bl=="MASTER")
				{
					$queryContList="SELECT cont_number,cont_seal_number,cont_size,cont_type,cont_height,Cont_gross_weight,cont_weight, Pack_Number 
									FROM igm_detail_container 
									INNER JOIN igm_details ON igm_details.id=igm_detail_container.igm_detail_id 
									WHERE  igm_details.Import_Rotation_No='$rotNo' AND igm_details.BL_No='$blno'";
				}
				else if($type_of_bl=="FF")
				{
					$queryContList="SELECT cont_number,cont_seal_number,cont_size,cont_type,cont_height,Cont_gross_weight,cont_weight,Pack_Number
									FROM igm_sup_detail_container 
									INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=igm_sup_detail_container.igm_sup_detail_id
									WHERE  igm_supplimentary_detail.Import_Rotation_No='$rotNo' AND igm_supplimentary_detail.BL_No='$blno'";
				}
				$contList=$this->bm->dataSelectDb1($queryContList);
				$data['contList']=$contList;
				
				$query="SELECT DISTINCT igm_details.id AS dtl_id,BL_No,Pack_Number,Pack_Description,Pack_Marks_Number,weight,Bill_of_Entry_No,
						No_of_Pack_Delivered,DG_status,type_of_igm,net_weight,weight_unit,net_weight_unit,igm_details.Consignee_name,Consignee_address,
						Description_of_Goods,
						igm_masters.id,igm_masters.Import_Rotation_No,vessels_berth_detail.ETA_Date,igm_masters.Vessel_Name,igm_masters.Voy_No,
						igm_masters.Net_Tonnage,Notify_name,Notify_address,port_of_origin,Port_of_Shipment,igm_details.Pack_Marks_Number,
						igm_masters.Name_of_Master,igm_masters.Port_Ship_ID Port_of_Shipment,igm_masters.Port_of_Destination,igm_masters.custom_approved,
						igm_masters.file_clearence_date,Organization_Name AS org_name,igm_masters.Submitee_Org_Type AS Submitee_Org_Type,
						igm_masters.S_Org_License_Number AS S_Org_License_Number,igm_masters.Submission_Date AS Submission_Date,igm_masters.flag AS flag,
						igm_masters.imo AS imo, reg_no,dec_code
						FROM igm_masters
						INNER JOIN igm_details ON  igm_masters.id=igm_details.IGM_id
						LEFT JOIN sad_item ON sad_item.sum_declare=igm_details.BL_No
						INNER JOIN sad_info ON sad_info.id=sad_item.sad_id
						LEFT JOIN vessels_berth_detail ON vessels_berth_detail.igm_id = igm_masters.id
						LEFT JOIN organization_profiles ON organization_profiles.id = igm_masters.Submitee_Org_Id
						WHERE igm_details.Import_Rotation_No='$rotNo' AND BL_No='$blno' ORDER BY file_clearence_date DESC";
				$doInfo=$this->bm->dataSelectDb1($query);
				
				//---
				if(count($doInfo)==0)
				{
					$query="SELECT DISTINCT igm_details.id AS dtl_id,igm_supplimentary_detail.BL_No,igm_supplimentary_detail.Pack_Number,igm_supplimentary_detail.Pack_Description,igm_supplimentary_detail.Pack_Marks_Number,igm_supplimentary_detail.weight,igm_supplimentary_detail.Bill_of_Entry_No,
					igm_supplimentary_detail.No_of_Pack_Delivered,igm_supplimentary_detail.DG_status,igm_supplimentary_detail.type_of_igm,igm_supplimentary_detail.net_weight,igm_supplimentary_detail.weight_unit,igm_supplimentary_detail.net_weight_unit,igm_supplimentary_detail.Consignee_name,igm_supplimentary_detail.Consignee_address,
					igm_supplimentary_detail.Description_of_Goods,
					igm_masters.id,igm_masters.Import_Rotation_No,vessels_berth_detail.ETA_Date,igm_masters.Vessel_Name,igm_masters.Voy_No,
					igm_masters.Net_Tonnage,igm_supplimentary_detail.Notify_name,igm_supplimentary_detail.Notify_address,igm_supplimentary_detail.port_of_origin,Port_of_Shipment,igm_details.Pack_Marks_Number,
					igm_masters.Name_of_Master,igm_masters.Port_Ship_ID Port_of_Shipment,igm_masters.Port_of_Destination,igm_masters.custom_approved,
					igm_masters.file_clearence_date,Organization_Name AS org_name,igm_masters.Submitee_Org_Type AS Submitee_Org_Type,
					igm_masters.S_Org_License_Number AS S_Org_License_Number,igm_masters.Submission_Date AS Submission_Date,igm_masters.flag AS flag,
					igm_masters.imo AS imo,reg_no,dec_code
					FROM igm_masters
					INNER JOIN igm_details ON  igm_masters.id=igm_details.IGM_id
					INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.igm_detail_id=igm_details.id
					LEFT JOIN sad_item ON sad_item.sum_declare=igm_details.BL_No
					LEFT JOIN sad_info ON sad_info.id=sad_item.sad_id
					LEFT JOIN vessels_berth_detail ON vessels_berth_detail.igm_id = igm_masters.id
					LEFT JOIN organization_profiles ON organization_profiles.id = igm_masters.Submitee_Org_Id
					WHERE igm_supplimentary_detail.Import_Rotation_No='$rotNo' AND igm_supplimentary_detail.BL_No='$blno' ORDER BY file_clearence_date DESC";
					$doInfo=$this->bm->dataSelectDb1($query);
				}
				//---
				
				$data['doInfo']=$doInfo;
				
				$dec_code = $doInfo[0]['dec_code'];
				
				$cnfCode2 = substr($dec_code, 5, 4);
				$cnfCode1 = substr($dec_code, 3, 2);
				$cnfLic = $cnfCode2."/".$cnfCode1;
				
				if($this->input->post('editFlag'))
				{
					$sql_CNFName="SELECT id,name FROM sparcsn4.ref_bizunit_scoped WHERE role='SHIPPER' AND life_cycle_state='ACT' AND id='$cnf_lic_no'";
				}
				else
				{
					$sql_CNFName="SELECT id,name FROM sparcsn4.ref_bizunit_scoped WHERE role='SHIPPER' AND life_cycle_state='ACT' AND id='$cnfLic'";
				}
				$cnf_name=$this->bm->dataSelect($sql_CNFName);
				$data['cnf_name']=$cnf_name;
				
				$queryRemainingQty = "SELECT IFNULL(gross_quantity,0),IFNULL(SUM(delv_quantity),0) AS total_delivered,
									(IFNULL(gross_quantity,0)-IFNULL(SUM(delv_quantity),0)) AS remaining
									FROM shed_mlo_do_info
									WHERE shed_mlo_do_info.imp_rot='$rotNo' AND shed_mlo_do_info.bl_no='$blno'";
				$remainingQty=$this->bm->dataSelectDb1($queryRemainingQty);
				$data['remainingQty']=$remainingQty;
				$data['cnfLic']=$cnfLic;
			}
			
			$data['reslt']=$reslt;
			$data['resltBE']=$resltBE;
			
			$data['frmType']="search";
			$data['title']="Shed Delivery Order Info Entry";
			//$data['tallytype']=$tallytype;
			$data['msg']=$msg;
			$data['msgBLsearch']=$msgBLsearch;
			$data['blno']=$blno;
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('shedDOInfo',$data);
			$this->load->view('jsAssets');
			
		}
	}
	
	function shedDeliveryOrderInfoEntry()
	{
		$user = $this->session->userdata('login_id');
		$user=str_replace(" ","",$user);
		$session_id = $this->session->userdata('value');
		
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{	
			$igm_dtl=$this->input->post('igm_dtl');
			$blno=$this->input->post('blno');
			$rotNo=$this->input->post('rotno');
			$fileRotno=str_replace('/','_',$rotNo);
			$beno=$this->input->post('beno');
			$valid_upto=$this->input->post('valid_upto');			
			$cnflic=$this->input->post('cnflic');			
			$bl_type=$this->input->post('type_of_bl');			
			$grossQty=$this->input->post('grossQty');
			$deliveredWeight=$this->input->post('deliveredWeight');
			$measurement=$this->input->post('measurement');
			$login_id = $this->session->userdata('login_id');
			$ipaddr = $_SERVER['REMOTE_ADDR'];
			
			//$type_of_Igm=$this->input->post('type_of_Igm');
			//$CODE=$this->input->post('CODE');
			
			$msg = "";
			
			$sql_do_number="SELECT MAX(do_no)+1 AS rtnValue FROM shed_mlo_do_info";
			$do_no=$this->bm->dataReturnDb1($sql_do_number);
						
			if($this->input->post('update'))
			{
				//update code
				$editId = $this->input->post('editId');
				$updateQuery = "Update shed_mlo_do_info set valid_upto_dt='$valid_upto',cnf_lic_no='$cnflic',measurement='$measurement' 
								WHERE id='$editId'";
				$res_updateShedMLO = $this->bm->dataUpdateDB1($updateQuery);
			}
			else
			{
				//insert code
				$insertQuery="INSERT INTO shed_mlo_do_info(igm_detail_id,imp_rot,bl_no,be_no,do_no,do_date,valid_upto_dt,cnf_lic_no,bl_type,gross_quantity,
							delv_quantity,measurement,user_id,upload_time,ip_addr) 
					VALUES ('$igm_dtl','$rotNo','$blno','$beno','$do_no',DATE(NOW()),'$valid_upto','$cnflic','$bl_type','$grossQty',
							'$deliveredWeight','$measurement',
							'$login_id',NOW(),'$ipaddr')";
				$res_insertShedMLO = $this->bm->dataInsertDB1($insertQuery);
			}
			
			$selectQuery="SELECT id as rtnValue FROM shed_mlo_do_info ORDER BY id DESC LIMIT 1";
			$selectID=$this->bm->dataReturnDb1($selectQuery);
			
			//image upload starts
			// Access the $_FILES global variable for this specific file being uploaded
			// and create local PHP variables from the $_FILES array of information
			$fileName = $_FILES["dofile"]["name"]; // The file name
			$fileTmpLoc = $_FILES["dofile"]["tmp_name"]; // File in the PHP tmp folder
			$fileType = $_FILES["dofile"]["type"]; // The type of file it is
			$fileSize = $_FILES["dofile"]["size"]; // File size in bytes
			$fileErrorMsg = $_FILES["dofile"]["error"]; // 0 for false... and 1 for true
			$kaboom = explode(".", $fileName); // Split file name into an array using the dot
			$fileExt = end($kaboom); // Now target the last array element to get the file extension
			// START PHP Image Upload Error Handling --------------------------------------------------
			if ($fileTmpLoc) { // if file chosen
				//echo "ERROR: Please browse for a file before clicking the upload button.";
				//exit();
				if($fileSize > 5242880) { // if file size is larger than 5 Megabytes
				echo "ERROR: Your file was larger than 5 Megabytes in size.";
				unlink($fileTmpLoc); // Remove the uploaded file from the PHP temp folder
				exit();
				}  
				else if ($fileErrorMsg == 1) { // if file upload error key is equal to 1
					echo "ERROR: An error occured while processing the file. Try again.";
					exit();
				}
			} 

			$fChosen = 0;

			if ($fileTmpLoc) {

				$fChosen = 1;
				// END PHP Image Upload Error Handling ----------------------------------------------------
				// Place it into your "uploads" folder mow using the move_uploaded_file() function
				$date = date("Ymdhis");
				//$imgName = $blno."".$fileRotno."".$selectID.".".$fileExt;

				if($this->input->post('update'))
					{
						$editId = $this->input->post('editId');
						$imgName = $blno."_".$fileRotno."_".$editId.".".$fileExt;
					}
				else 
					{
						$imgName = $blno."_".$fileRotno."_".$selectID.".".$fileExt;
					}
				
				$moveResult = move_uploaded_file($_FILES["dofile"]["tmp_name"],$_SERVER['DOCUMENT_ROOT']."/pcs/assets/do_image/".$imgName);
				// $moveResult = move_uploaded_file($_FILES["dofile"]["tmp_name"],$_SERVER['DOCUMENT_ROOT']."/myportpanel/resources/do_image/".$_FILES["dofile"]["name"]);
				// Check to make sure the move result is true before continuing
				if ($moveResult != true) {
					echo "ERROR: File not uploaded. Try again.";
					unlink($fileTmpLoc); // Remove the uploaded file from the PHP temp folder
					exit();
				}
				else{	
					if($this->input->post('update'))
					{
						$editId = $this->input->post('editId');
						$imageQuery = "SELECT do_image_loc FROM shed_mlo_do_info WHERE id='$editId'";
						$result = $this->bm->dataSelectDB1($imageQuery);
						$doImageLoc = $result[0]['do_image_loc'];
						//return;
						//unlink($_SERVER['DOCUMENT_ROOT']."/pcs/assets/do_image/".$doImageLoc);
						$updateQuery="UPDATE shed_mlo_do_info SET do_image_loc='$imgName' WHERE id='$editId'";						
					}
					else{
						$updateQuery="UPDATE shed_mlo_do_info SET do_image_loc='$imgName' WHERE id='$selectID'";
					}
					
					$resUpdateQuery = $this->bm->dataUpdateDB1($updateQuery);
					
					$msg="<font color='blue'><b>DO Entry Successfull !!</b></font>";
				}
				//@unlink($fileTmpLoc); // Remove the uploaded file from the PHP temp folder
				
				//image upload ends/
			}
			
			
			
			
			$msgBLsearch = "";
			$msg = "<font color='blue'><b>Saved Successfully!!!</b></font>";
			$data['msg']=$msg;
			
			// Going back to the form with same data-----
			if($blno=="all")
			{
				$sqlQuery="SELECT Bill_of_Entry_No FROM igm_details WHERE Import_Rotation_No='$rotNo'";	
			}
			else
			{
				$sqlQuery="SELECT Bill_of_Entry_No FROM igm_details WHERE Import_Rotation_No='$rotNo' AND BL_No='$blno'";
			}
			$reslt = $this->bm->dataSelectDb1($sqlQuery);
			
			//---
			if(count($reslt)==0)
			{
				$sqlQuery="SELECT Bill_of_Entry_No FROM igm_supplimentary_detail WHERE Import_Rotation_No='$rotNo' AND BL_No='$blno'";
				$reslt = $this->bm->dataSelectDb1($sqlQuery);
			}
			//---
			
			$resltBE = $reslt[0]['Bill_of_Entry_No'];
			if($resltBE=="")
			{
				$msgBLsearch="<font color='red'><b>Bill of Entry Number not submitted, Please try again after submitting.</b></font>";
				
			}
			else
			{
				$msgBLsearch = "";
				
				$queryBLType="SELECT igm_details.BL_No, 'MASTER' AS bl_type FROM igm_detail_container 
							INNER JOIN igm_details ON igm_details.id=igm_detail_container.igm_detail_id 
							WHERE  igm_details.Import_Rotation_No='$rotNo' AND igm_details.BL_No='$blno'

							UNION

							SELECT igm_supplimentary_detail.BL_No AS sup_bl, 'FF' AS bl_type FROM igm_detail_container 
							INNER JOIN igm_details ON igm_details.id=igm_detail_container.igm_detail_id 
							INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.igm_detail_id=igm_details.id
							WHERE  igm_details.Import_Rotation_No='$rotNo' AND igm_supplimentary_detail.BL_No='$blno'";
				$blType=$this->bm->dataSelectDb1($queryBLType);
				$type_of_bl = $blType[0]['bl_type'];
				$data['type_of_bl']=$type_of_bl;
				
				if($type_of_bl=="MASTER")
				{
					$queryContList="SELECT cont_number,cont_seal_number,cont_size,cont_type,cont_height,Cont_gross_weight,cont_weight, Pack_Number 
									FROM igm_detail_container 
									INNER JOIN igm_details ON igm_details.id=igm_detail_container.igm_detail_id 
									WHERE  igm_details.Import_Rotation_No='$rotNo' AND igm_details.BL_No='$blno'";
				}
				else if($type_of_bl=="FF")
				{
					$queryContList="SELECT cont_number,cont_seal_number,cont_size,cont_type,cont_height,Cont_gross_weight,cont_weight,Pack_Number
									FROM igm_sup_detail_container 
									INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=igm_sup_detail_container.igm_sup_detail_id
									WHERE  igm_supplimentary_detail.Import_Rotation_No='$rotNo' AND igm_supplimentary_detail.BL_No='$blno'";
				}
				$contList=$this->bm->dataSelectDb1($queryContList);
				$data['contList']=$contList;
				
				$query="SELECT DISTINCT igm_details.id AS dtl_id,BL_No,Pack_Number,Pack_Description,Pack_Marks_Number,weight,Bill_of_Entry_No,
						No_of_Pack_Delivered,DG_status,type_of_igm,net_weight,weight_unit,net_weight_unit,igm_details.Consignee_name,Consignee_address,
						Description_of_Goods,
						igm_masters.id,igm_masters.Import_Rotation_No,vessels_berth_detail.ETA_Date,igm_masters.Vessel_Name,igm_masters.Voy_No,
						igm_masters.Net_Tonnage,Notify_name,Notify_address,port_of_origin,Port_of_Shipment,igm_details.Pack_Marks_Number,
						igm_masters.Name_of_Master,igm_masters.Port_Ship_ID Port_of_Shipment,igm_masters.Port_of_Destination,igm_masters.custom_approved,
						igm_masters.file_clearence_date,Organization_Name AS org_name,igm_masters.Submitee_Org_Type AS Submitee_Org_Type,
						igm_masters.S_Org_License_Number AS S_Org_License_Number,igm_masters.Submission_Date AS Submission_Date,igm_masters.flag AS flag,
						igm_masters.imo AS imo, reg_no,dec_code
						FROM igm_masters
						INNER JOIN igm_details ON  igm_masters.id=igm_details.IGM_id
						LEFT JOIN sad_item ON sad_item.sum_declare=igm_details.BL_No
						INNER JOIN sad_info ON sad_info.id=sad_item.sad_id
						LEFT JOIN vessels_berth_detail ON vessels_berth_detail.igm_id = igm_masters.id
						LEFT JOIN organization_profiles ON organization_profiles.id = igm_masters.Submitee_Org_Id
						WHERE igm_details.Import_Rotation_No='$rotNo' AND BL_No='$blno' ORDER BY file_clearence_date DESC";
				$doInfo=$this->bm->dataSelectDb1($query);
				
				//---
				if(count($doInfo)==0)
				{
					$query="SELECT DISTINCT igm_details.id AS dtl_id,igm_supplimentary_detail.BL_No,igm_supplimentary_detail.Pack_Number,igm_supplimentary_detail.Pack_Description,igm_supplimentary_detail.Pack_Marks_Number,igm_supplimentary_detail.weight,igm_supplimentary_detail.Bill_of_Entry_No,
					igm_supplimentary_detail.No_of_Pack_Delivered,igm_supplimentary_detail.DG_status,igm_supplimentary_detail.type_of_igm,igm_supplimentary_detail.net_weight,igm_supplimentary_detail.weight_unit,igm_supplimentary_detail.net_weight_unit,igm_supplimentary_detail.Consignee_name,igm_supplimentary_detail.Consignee_address,
					igm_supplimentary_detail.Description_of_Goods,
					igm_masters.id,igm_masters.Import_Rotation_No,vessels_berth_detail.ETA_Date,igm_masters.Vessel_Name,igm_masters.Voy_No,
					igm_masters.Net_Tonnage,igm_supplimentary_detail.Notify_name,igm_supplimentary_detail.Notify_address,igm_supplimentary_detail.port_of_origin,Port_of_Shipment,igm_details.Pack_Marks_Number,
					igm_masters.Name_of_Master,igm_masters.Port_Ship_ID Port_of_Shipment,igm_masters.Port_of_Destination,igm_masters.custom_approved,
					igm_masters.file_clearence_date,Organization_Name AS org_name,igm_masters.Submitee_Org_Type AS Submitee_Org_Type,
					igm_masters.S_Org_License_Number AS S_Org_License_Number,igm_masters.Submission_Date AS Submission_Date,igm_masters.flag AS flag,
					igm_masters.imo AS imo,reg_no,dec_code
					FROM igm_masters
					INNER JOIN igm_details ON  igm_masters.id=igm_details.IGM_id
					INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.igm_detail_id=igm_details.id
					LEFT JOIN sad_item ON sad_item.sum_declare=igm_details.BL_No
					LEFT JOIN sad_info ON sad_info.id=sad_item.sad_id
					LEFT JOIN vessels_berth_detail ON vessels_berth_detail.igm_id = igm_masters.id
					LEFT JOIN organization_profiles ON organization_profiles.id = igm_masters.Submitee_Org_Id
					WHERE igm_supplimentary_detail.Import_Rotation_No='$rotNo' AND igm_supplimentary_detail.BL_No='$blno' ORDER BY file_clearence_date DESC";
					$doInfo=$this->bm->dataSelectDb1($query);
				}
				//---
				
				$data['doInfo']=$doInfo;
				
				$dec_code = $doInfo[0]['dec_code'];
				
				$cnfCode2 = substr($dec_code, 5, 4);
				$cnfCode1 = substr($dec_code, 3, 2);
				$cnfLic = $cnfCode2."/".$cnfCode1;
				
				if($this->input->post('editFlag'))
				{
					$sql_CNFName="SELECT id,name FROM sparcsn4.ref_bizunit_scoped WHERE role='SHIPPER' AND life_cycle_state='ACT' AND id='$cnf_lic_no'";
				}
				else
				{
					$sql_CNFName="SELECT id,name FROM sparcsn4.ref_bizunit_scoped WHERE role='SHIPPER' AND life_cycle_state='ACT' AND id='$cnfLic'";
				}
				$cnf_name=$this->bm->dataSelect($sql_CNFName);
				$data['cnf_name']=$cnf_name;
				
				$queryRemainingQty = "SELECT IFNULL(gross_quantity,0),IFNULL(SUM(delv_quantity),0) AS total_delivered,
									(IFNULL(gross_quantity,0)-IFNULL(SUM(delv_quantity),0)) AS remaining
									FROM shed_mlo_do_info
									WHERE shed_mlo_do_info.imp_rot='$rotNo' AND shed_mlo_do_info.bl_no='$blno'";
				$remainingQty=$this->bm->dataSelectDb1($queryRemainingQty);
				$data['remainingQty']=$remainingQty;
			}
			
			$data['reslt']=$reslt;
			$data['resltBE']=$resltBE;
			$data['cnfLic']=$cnfLic;
			
			$data['frmType']="search";
			
			$data['title']="Shed Delivery Order Info Entry";
			$data['tallytype']="";
			$data['msg']=$msg;
			$data['msgBLsearch']=$msgBLsearch;
			$data['blno']=$blno;
			$data['edit'] = "";

			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('shedDOInfo',$data);
			$this->load->view('jsAssets');
		}
	}
	
	function shedDeliveryOrderList()
	{
		$user = $this->session->userdata('login_id');
		$user=str_replace(" ","",$user);
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$queryShedDOList = "SELECT * FROM shed_mlo_do_info ORDER BY id DESC";
			$ShedDOList=$this->bm->dataSelectDb1($queryShedDOList);
			$data['ShedDOList']=$ShedDOList;
			
			$data['title']="Shed Delivery Order List...";
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('shedDeliveryOrderList',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function shedDeliveryOrderInfoPDF()
	{	
		$user = $this->session->userdata('login_id');
		$user=str_replace(" ","",$user);
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			
			echo $shedMloDo = $this->input->post('shedMloDo');		
			//return;
			$rotNo = $this->input->post('rotNo');			
			$blno = $this->input->post('blno');
			
			$msg = "";
			
			$sqlQuery="SELECT Bill_of_Entry_No FROM igm_details WHERE Import_Rotation_No='$rotNo' AND BL_No='$blno'";
			
			$reslt = $this->bm->dataSelectDb1($sqlQuery);
			$resltBE = $reslt[0]['Bill_of_Entry_No'];
								
			$queryBLType="SELECT igm_details.BL_No, 'MASTER' AS bl_type FROM igm_detail_container 
						INNER JOIN igm_details ON igm_details.id=igm_detail_container.igm_detail_id 
						WHERE  igm_details.Import_Rotation_No='$rotNo' AND igm_details.BL_No='$blno'

						UNION

						SELECT igm_supplimentary_detail.BL_No AS sup_bl, 'FF' AS bl_type FROM igm_detail_container 
						INNER JOIN igm_details ON igm_details.id=igm_detail_container.igm_detail_id 
						INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.igm_detail_id=igm_details.id
						WHERE  igm_details.Import_Rotation_No='$rotNo' AND igm_supplimentary_detail.BL_No='$blno'";
			$blType=$this->bm->dataSelectDb1($queryBLType);
			$type_of_bl = $blType[0]['bl_type'];
			$this->data['type_of_bl']=$type_of_bl;
			
			if($type_of_bl=="MASTER")
			{
				$queryContList="SELECT cont_number,cont_seal_number,cont_size,cont_type,cont_height,Cont_gross_weight,cont_weight, Pack_Number 
								FROM igm_detail_container 
								INNER JOIN igm_details ON igm_details.id=igm_detail_container.igm_detail_id 
								WHERE  igm_details.Import_Rotation_No='$rotNo' AND igm_details.BL_No='$blno'";
			}
			else if($type_of_bl=="FF")
			{
				$queryContList="SELECT cont_number,cont_seal_number,cont_size,cont_type,cont_height,Cont_gross_weight,cont_weight,Pack_Number
								FROM igm_sup_detail_container 
								INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=igm_sup_detail_container.igm_sup_detail_id
								WHERE  igm_supplimentary_detail.Import_Rotation_No='$rotNo' AND igm_supplimentary_detail.BL_No='$blno'";
			}
			$contList=$this->bm->dataSelectDb1($queryContList);
			$this->data['contList']=$contList;
			
			$query="SELECT DISTINCT igm_details.id AS dtl_id,BL_No,Pack_Number,Pack_Description,Pack_Marks_Number,weight,Bill_of_Entry_No,
						No_of_Pack_Delivered,DG_status,type_of_igm,net_weight,weight_unit,net_weight_unit,igm_details.Consignee_name,Consignee_address,
						Description_of_Goods,
						igm_masters.id,igm_masters.Import_Rotation_No,vessels_berth_detail.ETA_Date,igm_masters.Vessel_Name,igm_masters.Voy_No,
						igm_masters.Net_Tonnage,Notify_name,Notify_address,port_of_origin,Port_of_Shipment,igm_details.Pack_Marks_Number,
						igm_masters.Name_of_Master,igm_masters.Port_Ship_ID Port_of_Shipment,igm_masters.Port_of_Destination,igm_masters.custom_approved,
						igm_masters.file_clearence_date,Organization_Name AS org_name,igm_masters.Submitee_Org_Type AS Submitee_Org_Type,
						igm_masters.S_Org_License_Number AS S_Org_License_Number,igm_masters.Submission_Date AS Submission_Date,igm_masters.flag AS flag,
						igm_masters.imo AS imo, reg_no,dec_code
						FROM igm_masters
						INNER JOIN igm_details ON  igm_masters.id=igm_details.IGM_id
						LEFT JOIN sad_item ON sad_item.sum_declare=igm_details.BL_No
						INNER JOIN sad_info ON sad_info.id=sad_item.sad_id
						LEFT JOIN vessels_berth_detail ON vessels_berth_detail.igm_id = igm_masters.id
						LEFT JOIN organization_profiles ON organization_profiles.id = igm_masters.Submitee_Org_Id
					WHERE igm_details.Import_Rotation_No='$rotNo' AND BL_No='$blno' ORDER BY file_clearence_date DESC";
			$doInfo=$this->bm->dataSelectDb1($query);
			
			//---
				if(count($doInfo)==0)
				{
					$query="SELECT DISTINCT igm_details.id AS dtl_id,igm_supplimentary_detail.BL_No,igm_supplimentary_detail.Pack_Number,igm_supplimentary_detail.Pack_Description,igm_supplimentary_detail.Pack_Marks_Number,igm_supplimentary_detail.weight,igm_supplimentary_detail.Bill_of_Entry_No,
					igm_supplimentary_detail.No_of_Pack_Delivered,igm_supplimentary_detail.DG_status,igm_supplimentary_detail.type_of_igm,igm_supplimentary_detail.net_weight,igm_supplimentary_detail.weight_unit,igm_supplimentary_detail.net_weight_unit,igm_supplimentary_detail.Consignee_name,igm_supplimentary_detail.Consignee_address,
					igm_supplimentary_detail.Description_of_Goods,
					igm_masters.id,igm_masters.Import_Rotation_No,vessels_berth_detail.ETA_Date,igm_masters.Vessel_Name,igm_masters.Voy_No,
					igm_masters.Net_Tonnage,igm_supplimentary_detail.Notify_name,igm_supplimentary_detail.Notify_address,igm_supplimentary_detail.port_of_origin,Port_of_Shipment,igm_details.Pack_Marks_Number,
					igm_masters.Name_of_Master,igm_masters.Port_Ship_ID Port_of_Shipment,igm_masters.Port_of_Destination,igm_masters.custom_approved,
					igm_masters.file_clearence_date,Organization_Name AS org_name,igm_masters.Submitee_Org_Type AS Submitee_Org_Type,
					igm_masters.S_Org_License_Number AS S_Org_License_Number,igm_masters.Submission_Date AS Submission_Date,igm_masters.flag AS flag,
					igm_masters.imo AS imo,reg_no,dec_code
					FROM igm_masters
					INNER JOIN igm_details ON  igm_masters.id=igm_details.IGM_id
					INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.igm_detail_id=igm_details.id
					LEFT JOIN sad_item ON sad_item.sum_declare=igm_details.BL_No
					LEFT JOIN sad_info ON sad_info.id=sad_item.sad_id
					LEFT JOIN vessels_berth_detail ON vessels_berth_detail.igm_id = igm_masters.id
					LEFT JOIN organization_profiles ON organization_profiles.id = igm_masters.Submitee_Org_Id
					WHERE igm_supplimentary_detail.Import_Rotation_No='$rotNo' AND igm_supplimentary_detail.BL_No='$blno' ORDER BY file_clearence_date DESC";
					$doInfo=$this->bm->dataSelectDb1($query);
				}
				//---
			
			$this->data['doInfo']=$doInfo;
			
			$queryRemainingQty = "SELECT IFNULL(gross_quantity,0),IFNULL(SUM(delv_quantity),0) AS total_delivered,
								(IFNULL(gross_quantity,0)-IFNULL(SUM(delv_quantity),0)) AS remaining
								FROM shed_mlo_do_info
								WHERE shed_mlo_do_info.imp_rot='$rotNo' AND shed_mlo_do_info.bl_no='$blno'";
			$remainingQty=$this->bm->dataSelectDb1($queryRemainingQty);
			$this->data['remainingQty']=$remainingQty;
			
			
			$queryShedMloDOList = "SELECT * FROM shed_mlo_do_info WHERE id='$shedMloDo'";
			$ShedMloDOList=$this->bm->dataSelectDb1($queryShedMloDOList);
			$cnf_lic_no = $ShedMloDOList[0]['cnf_lic_no'];
			$this->data['ShedMloDOList']=$ShedMloDOList;
			
			$sql_CNFName="SELECT id,name FROM sparcsn4.ref_bizunit_scoped WHERE role='SHIPPER' AND life_cycle_state='ACT' AND id='$cnf_lic_no'";	
			$cnf_name=$this->bm->dataSelect($sql_CNFName);
			//$cnfName = $cnf_name[0]['name'];
			$this->data['cnf_name']=$cnf_name;
			
			$this->data['reslt']=$reslt;
			$this->data['resltBE']=$resltBE;
			
			$this->data['frmType']="search";
			
			$this->data['title']="Shed Delivery Order Info Entry";
			$this->data['type_of_bl']=$type_of_bl;
			$this->data['msg']=$msg;
			$this->data['blno']=$blno;
			
			$this->load->library('m_pdf');
			//$mpdf->use_kwt = true;
			
			$html=$this->load->view('shedDeliveryOrderInfoPDF',$this->data, true); 

			$pdfFilePath ="shedDeliveryOrderInfoPDF-".time()."-download.pdf";

			$pdf = $this->m_pdf->load();

			// $pdf->SetWatermarkText('CPA CTMS');
			// $pdf->showWatermarkText = true;

			$pdf->useSubstitutions = true; 
				
			//$pdf->setFooter('Prepared By : '.$user.'|Page {PAGENO}|Date {DATE j-m-Y}');

			//Following 1 line is used for debugging the error:- "HTML contains invalid UTF-8 character(s)"
			$html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
			
			$pdf->WriteHTML($html,2);
				
			$pdf->Output($pdfFilePath, "I");
		}
	}
					
	//Shed Delivery Order Ends------------------------
	
	// Email for VCMS - start
	function sendEmail($subject,$body,$emailClient,$pdfFilePath_gate_pass)
	{
		require_once 'mailer/PHPMailerAutoload.php';
		require_once 'mailer/class.phpmailer.php';
	  
		$this->CI =& get_instance();
		$email =$this->CI->config->item('email');		
		$password = $this->CI->config->item('password');
		$SMTPAuth = $this->CI->config->item('SMTPAuth');
		$SMTPSecure = $this->CI->config->item('SMTPSecure');
		$Host = $this->CI->config->item('Host');
		$Port = $this->CI->config->item('Port');
	//	$file_name="admin_20160716103021.xls";
	  
		$mail = new PHPMailer(); // create a new object
		$mail->IsSMTP(); // enable SMTP
		//$mail->SMTPDebug = 2; // debugging: 1 = errors and messages, 2 = messages only
		$mail->SMTPAuth = $SMTPAuth; // authentication enabled
		$mail->SMTPSecure = $SMTPSecure; // secure transfer enabled REQUIRED for GMail
		$mail->Host = $Host;
		$mail->Port = $Port; // or 587
		$mail->IsHTML(true);
		$mail->FromName = "Shedbill";
		//$mail->SetFrom('name@yourdomain.com', 'Rupert Bear');
		//$mail->Username = "ctms.igm@gmail.com";
		//$mail->Password = "ctmsadmin";
	  
		$mail->Username = $email;
		$mail->Password = $password;
		$mail->SetFrom($email,'Shedbill');
		$mail->Subject = $subject;
		$mail->Body = $body;
		$mail->AddAddress($emailClient);

		$mail->addAttachment($pdfFilePath_gate_pass);	//done
		
		if(!$mail->Send())
		{
			//echo "Not sent";
			//echo "<br>";
			//echo "Mailer Error: " . $mail->ErrorInfo; 
			$rtnmsg =  "There has been some error. Please try again...";
			
			date_default_timezone_set('America/New_York');

			$newYorkDate=date("Y-m-d H:i:s");

			date_default_timezone_set('Asia/Dhaka');

			$bangladeshDate= date("Y-m-d H:i:s");

			//$datetime=date("Y-m-d H:i:s")

			$ip_add=$_SERVER['REMOTE_ADDR'];;

			$fp=fopen("EmailNotSendLog.txt","a");

			$datawrite="IP: $ip_add | NewYork Time: $newYorkDate | BangladeshTime: $bangladeshDate | $mail->AddAddress($emailClient)\r\n";

			$fp=fwrite($fp,$datawrite);	

			$fp=fclose($fp);
		}
		else
		{
		//	echo "Sent";
			 $rtnmsg =  "Email has been successfully sent to $emailClient";
		}
		return $rtnmsg;		
		
	}
	// Email for VCMS - end

	//Dispute - Start
		
	function loadingDispute(){
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {	
			$login_id = $this->session->userdata('login_id');
			$loadQty = $this->input->post("loadQty");
			$packUnit = $this->input->post("pack_unit");
			$remarks = $this->input->post("remarks");
			$visit_id = $this->input->post("visit_id");
			
			$visit_id = $this->input->post('visit_id');
				
			$chk_st = $this->input->post('chk_st');
			$chk_by = $this->input->post('chk_by');
			$chk_time = $this->input->post('chk_time');
			$ah = $this->input->post('ah');
			
			$query = "INSERT INTO loading_dispute(tr_visit_id,qty,pack_unit,remarks,dispute_by,dispute_at) VALUES('$visit_id','$loadQty','$packUnit','$remarks','$login_id',NOW())";
			$this->bm->dataInsertDB1($query);
			
			$data['visit_id']=$visit_id;
				
			$data['chk_st']=$chk_st;
			$data['chk_by']=$chk_by;
			$data['chk_time']=$chk_time;
			$data['ah']=$ah;
			$data['disputeMsg']="<font color='red'>Dispute Raised</font>";
			$data['msg'] = "";
			if($ah=="sec")
			{
				$title = "SECURITY CONFIRMATION PROCESS";
			}
			else if($ah=="cf")
			{
				$title = "C&F CONFIRMATION PROCESS";
			}
			else
			{
				$title = "TRAFFIC CONFIRMATION PROCESS";
			}
			$data['title']=$title;
			$data['frmType']="new";
			
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('confirmationProcess',$data);
			$this->load->view('jsAssetsList');
		}
	}	
		
	//Dispute - End
	
	function applicationForEDObyrotationBL()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		$org_id=$this->session->userdata('org_Type_id');
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {

			$data['title']="Application For EDO.";
			$data['msg'] = "";
			$data['flag'] = "all"; //To show all do list
			
			$data['org_id'] =$this->session->userdata('org_Type_id');
			
            $this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('applicationForEDObyrotationBL',$data);
			$this->load->view('jsAssetsList');
		}
	}

	function applicationForEDObyrotationBLentry()
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
			$data['org_id'] =$this->session->userdata('org_Type_id');
			$data['title']="Application For EDO.";
			$data['msg'] = "";
			$data['flag'] = 0;
			$rot_no = trim($this->input->post("rot_no"));
			$bl_no = trim($this->input->post("bl_no"));
			$beNo = trim($this->input->post("be_no"));
			$beDate = trim($this->input->post("be_date"));
			$ofcCode = trim($this->input->post("office_code"));
			
			$ip_address = $_SERVER['REMOTE_ADDR'];
			
			$queryCntApplication="SELECT COUNT(*) AS rtnValue FROM edo_application_by_cf WHERE rotation='$rot_no' AND bl='$bl_no'";						
			$cntApplication = $this->bm->dataReturnDb1($queryCntApplication);
			if($cntApplication==0)
			{
				//If there is no application for given rotation & bl no.....
				$type_of_igm = "";
				$blType_BB = "";
				$cnt_str="SELECT COUNT(*) as rtnValue FROM igm_details 
							WHERE Import_Rotation_No='$rot_no' AND BL_No='$bl_no'";						
				$cntResult = $this->bm->dataReturnDb1($cnt_str);
				
				if($cntResult==0)
				{
					$cnt_str_sup="SELECT COUNT(*) AS rtnValue FROM igm_supplimentary_detail 
								WHERE Import_Rotation_No='$rot_no' AND BL_No='$bl_no'";
					$cntSupResult = $this->bm->dataReturnDb1($cnt_str_sup);
					if($cntSupResult==0)
					{
						$data['msg']='<font color="red"><b>Wrong Combination of Rotation and BL</b></font>';
					}
					else
					{
						$type_str_sup="SELECT igm_supplimentary_detail.type_of_igm AS rtnValue FROM igm_supplimentary_detail 
								WHERE Import_Rotation_No='$rot_no' AND BL_No='$bl_no'";
						$type_of_igm = $this->bm->dataReturnDb1($type_str_sup);
						$blType_BB = "HB";
					}
				}
				else
				{
					$type_str="SELECT igm_details.type_of_igm as rtnValue FROM igm_details 
							WHERE Import_Rotation_No='$rot_no' AND BL_No='$bl_no'";						
					$type_of_igm = $this->bm->dataReturnDb1($type_str);
					$blType_BB = "MB";
				}	
		
				
				if($type_of_igm!="")
				{
					$strInsert = "";
					if($type_of_igm=='BB')
					{	
						// "BB";
						$Submitee_Org_Id = "";
						$str="SELECT igm_details.Submitee_Org_Id
							FROM igm_details  
							WHERE igm_details.BL_No='$bl_no' AND igm_details.Import_Rotation_No='$rot_no'";
							
						$resltStr = $this->bm->dataSelectDb1($str);					
						for($i=0;$i<count($resltStr);$i++)
						{
							$Submitee_Org_Id=$resltStr[$i]['Submitee_Org_Id'];
						}					

						$strInsert = "INSERT INTO edo_application_by_cf(rotation,bl,bl_type,igm_type,sh_agent_org_id,entry_time,sumitted_by,
										ip_address) 
						VALUES('$rot_no','$bl_no','$blType_BB','$type_of_igm','$Submitee_Org_Id', NOW(),'$login_id','$ip_address')";
					}
					else
					{
						$queryStr="SELECT igm_supplimentary_detail.Submitee_Org_Id AS sup_org,
						igm_details.Submitee_Org_Id AS master_org
						FROM igm_supplimentary_detail 
						INNER JOIN igm_details ON igm_supplimentary_detail.igm_detail_id=igm_details.id
						WHERE igm_supplimentary_detail.BL_No='$bl_no' AND igm_supplimentary_detail.Import_Rotation_No='$rot_no'";
						$rsltStr = $this->bm->dataSelectDb1($queryStr);
						if(count($rsltStr)>0)
						{
							$bl_type = "HB";
							$sup_org = "";
							$master_org = "";
							$master_bl = "";
							$cont_status = "";
							$ff_stat = 0;
							$ff_clearance_time = "";
							for($i=0;$i<count($rsltStr);$i++)
							{
								$sup_org=$rsltStr[$i]['sup_org'];
								$master_org=$rsltStr[$i]['master_org'];
							}
							$strQry="select igm_supplimentary_detail.master_BL_No,igm_sup_detail_container.cont_status
							from igm_supplimentary_detail 
							INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
							INNER JOIN igm_details ON igm_supplimentary_detail.igm_detail_id=igm_details.id
							where igm_supplimentary_detail.Import_Rotation_No='$rot_no' and igm_supplimentary_detail.BL_No='$bl_no'";
							$rsltQry = $this->bm->dataSelectDb1($strQry);
							
							for($k=0;$k<count($rsltQry);$k++)
							{
								$cont_status=$rsltQry[$k]['cont_status'];
								$master_bl=$rsltQry[$k]['master_BL_No'];
							}
							if($cont_status=="LCL")
							{
								$strChkMBLClearance="select * from cleared_mbl_by_mlo where master_bl='$master_bl'";
								$mblClearance = $this->bm->dataSelectDb1($strChkMBLClearance);
								if(count($mblClearance)==0)
								{
									//$ff_stat = 0;
									$ff_stat = 1;
								}
								else
								{
									$ff_stat = 1;
									for($l=0;$l<count($mblClearance);$l++)
									{
										$ff_clearance_time=$mblClearance[$l]['entry_time'];
									}
								}
							}

							$strInsert = "INSERT INTO edo_application_by_cf( rotation, bl, bl_type, igm_type, mlo, ff_org_id,ff_stat,ff_clearance_time,cont_status,mbl_of_hbl, entry_time, sumitted_by,ip_address) VALUES('$rot_no','$bl_no','$bl_type','$type_of_igm','$master_org','$sup_org','$ff_stat','$ff_clearance_time','$cont_status','$master_bl' ,NOW(), '$login_id','$ip_address')";
						}
						else
						{
							$queryStr="SELECT igm_details.Submitee_Org_Id AS master_org	FROM igm_details 
							WHERE BL_No='$bl_no' AND Import_Rotation_No='$rot_no'";
							$rsltStr = $this->bm->dataSelectDb1($queryStr);
							$bl_type = "MB";
							$master_org = "";
							for($i=0;$i<count($rsltStr);$i++)
							{
								$master_org=$rsltStr[$i]['master_org'];
							}

							$strInsert = "INSERT INTO edo_application_by_cf( rotation, bl, bl_type, igm_type, mlo,  entry_time, sumitted_by,ip_address) VALUES('$rot_no','$bl_no','$bl_type','$type_of_igm','$master_org', NOW(), '$login_id','$ip_address')";
						}					
					}
					
					$resInsert = $this->bm->dataInsertDB1($strInsert);
					if($resInsert==1)
					{
						$data['msg']='<font color="blue"><b>Inserted Sucessfully.</b></font>';
					}
				}
			}
			else
			{
				//If there is already any application for given rotation & bl no...
				$data['msg']='<font color="red">Sorry! Already applied for Rotation- '.'<b>'.$rot_no.'</b>'.' and BL- '.'<b>'.$bl_no.'</b>'.'</font>';
			}
			
			$data['flag'] = "all"; //To show all do list
			
            $this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('applicationForEDObyrotationBL',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function applicationForEDOList()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$data['org_id'] =$this->session->userdata('org_Type_id');
			$data['title']="EDO Application List";
			$data['msg'] = "";
			$data['flag'] = "all"; //To show all do list
			
            $this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('applicationForEDOList',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function updateStatforEDO()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$data['org_id'] =$this->session->userdata('org_Type_id');
			$data['title']="EDO Application List";
			$data['flag'] = "all"; //To show all do list
			
			$edo_id = $this->input->post("clearanceEDOId");
			
			
			$ipaddr = $_SERVER['REMOTE_ADDR'];
			
			$mlo_id = "";
			$cont_st = "";
			$mbl_of_hbl = "";
			$queryEDODtls="select * from edo_application_by_cf where id='$edo_id'";
			$edoDtls = $this->bm->dataSelectDb1($queryEDODtls);
			for($i=0;$i<count($edoDtls);$i++)
				{
					$mlo_id = $edoDtls[$i]['mlo'];
					$cont_st = $edoDtls[$i]['cont_status'];
					$mbl_of_hbl = $edoDtls[$i]['mbl_of_hbl'];
				}
			if($cont_st=="LCL")
			{
				$query = "UPDATE edo_application_by_cf SET ff_stat='1',ff_clearance_time=NOW()
					WHERE bl_type='HB' AND igm_type='GM' AND mlo='$mlo_id' AND 
					cont_status='LCL' AND mbl_of_hbl='$mbl_of_hbl'";
				$update_st=$this->bm->dataUpdateDB1($query);
				if($update_st==1)
				{
					$insertQuery="INSERT INTO cleared_mbl_by_mlo(master_bl,entry_time,ip_address) 
								VALUES ('$mbl_of_hbl',NOW(),'$ipaddr')";
					$resInsert = $this->bm->dataInsertDB1($insertQuery);
					if($resInsert)
					{
						$data['msg']='<font color=green>Forwarded Successfully.</font>';
					}
					else
					{
						$data['msg']='<font color=red>Forwarding Failed.</font>';
					}
				}
				else
				{
					$data['msg']='<font color=red>Forwarding Failed.</font>';
				}
			}
			else
			{
				$valid_upto_date = $this->input->post("valid_upto_date");
				$query = "UPDATE edo_application_by_cf SET ff_stat='1',ff_clearance_time=NOW(),
					valid_upto_dt_by_mlo='$valid_upto_date'
					WHERE id='$edo_id'";
				$update_st=$this->bm->dataUpdateDB1($query);
				if($update_st==1)
					$data['msg']='<font color=green>Forwarded Successfully.</font>';
				else
					$data['msg']='<font color=red>Forwarding Failed.</font>';
			}
			
            $this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('applicationForEDOList',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function updateEDORejectStatus()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$data['org_id'] =$this->session->userdata('org_Type_id');
			$data['title']="EDO Application List";
			$data['flag'] = "all"; //To show all do list$data['flag'] = "all"; //To show all do list
			
			$eid = $this->input->post("eid");
			$rejection_remarks = $this->input->post("rejection_remarks");
			$query = "UPDATE edo_application_by_cf SET rejection_st='1',rejection_time=NOW(),rejection_remarks='$rejection_remarks'
			WHERE id='$eid'";
			$update_st=$this->bm->dataUpdateDB1($query);
			if($update_st==1)
				$data['msg']='<font color=blue><b>Rejected Successfully.</b></font>';
			else
				$data['msg']='<font color=red><b>Rejection Failed.</b></font>';
			

            $this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('applicationForEDOList',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function applyForValidityExtension()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$data['org_id'] =$this->session->userdata('org_Type_id');
			$data['title']="EDO Application List";
			$data['flag'] = "all"; //To show all do list
			
			$eid = $this->input->post("extEDOId");
			$requested_date = $this->input->post("requested_date");
			$query = "UPDATE edo_application_by_cf SET applied_valid_dt='$requested_date' WHERE id='$eid'";
			$update_st=$this->bm->dataUpdateDB1($query);
			if($update_st==1)
				$data['msg']='<font color=blue><b>Applied Successfully.</b></font>';
			else
				$data['msg']='<font color=red><b>Application Failed.</b></font>';			

            $this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('applicationForEDOList',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function ffAssocStateChange(){
		$user = $this->session->userdata('login_id');
		$user=str_replace(" ","",$user);
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{					
			$data['org_id'] =$this->session->userdata('org_Type_id');
			$data['title']="EDO Application List";
			
			$edoId = $this->input->post('edoId');
			$cnfLic = $this->input->post('cnfLic');
			$statUpdatequery = "UPDATE edo_application_by_cf SET ff_assoc_st='1' WHERE id='$edoId'";
			$update_st = $this->bm->dataUpdateDB1($statUpdatequery);			
			if($update_st==1)
			{
				$tmp = "";
				$strTokenId = "SELECT id FROM token_distribution WHERE ff_ain='$cnfLic' AND used_st=0 AND edo_id IS NULL 
								ORDER BY id ASC LIMIT 1";
				$resTokenId=$this->bm->dataSelectDb1($strTokenId);
				for($i=0;$i<count($resTokenId);$i++)
				{
					$tmp=$resTokenId[$i]['id'];
				}
				$updateTokenSt = "Update token_distribution set used_st='1',edo_id='$edoId' WHERE id='$tmp'";
				$resTokenSt = $this->bm->dataUpdateDB1($updateTokenSt);
				if($resTokenSt)
				{
					$data['msg']='<font color=blue><b>Approved!!</b></font>';
				}
				else
				{
					$data['msg']='<font color=red><b>Failed!!</b></font>';
				}				
			}				
			else
			{
				$data['msg']='<font color=red><b>Failed!!</b></font>';
			}				
			$data['flag'] = "all"; //To show all do list
            $this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('applicationForEDOList',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function deleteEDOApplication()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$data['org_id'] =$this->session->userdata('org_Type_id');
			$data['title']="EDO Application List";
			$data['flag'] = "all"; //To show all do list$data['flag'] = "all"; //To show all do list
			
			$edo_id = $this->input->post("edo_id");
			$sql_delete = "DELETE FROM edo_application_by_cf WHERE id='$edo_id'";
			$del_st = $this->bm->dataDeleteDB1($sql_delete);
			if($del_st)
				$data['msg']='<font color=blue><b>Deleted Successfully.</b></font>';
			else
				$data['msg']='<font color=red><b>Sorry! Could not delete.</b></font>';
			

            $this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('applicationForEDOList',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function tokenDistributionForm($msg)
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$data['org_id'] =$this->session->userdata('org_Type_id');
			$data['title']="Token Distribution";
			$data['msg'] = $msg;
			$data['frmType'] = "new";
						
            $this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('tokenDistribution',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function tokenDistributionEntry()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		$login_id = $this->session->userdata('login_id');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$ffLicNo = $this->input->post('ffLicNo');
			$ffName = $this->input->post('ffName');
			$tokenQty = $this->input->post('tokenQty');
			$msg = "";
			for($i=1;$i<=$tokenQty;$i++)
			{
				$insertQuery="INSERT INTO token_distribution(ff_ain,ff_name,entered_by,entry_time) 
					VALUES ('$ffLicNo','$ffName','$login_id',NOW())";
				$resInsert = $this->bm->dataInsertDB1($insertQuery);
				$token_number = "";
				if($resInsert)
				{
					$strTokenId = "SELECT id AS rtnValue FROM token_distribution ORDER BY id DESC LIMIT 1";
					$resTokenId=$this->bm->dataReturnDb1($strTokenId);
					$token_number = $ffLicNo."-".$resTokenId;
					
					$updateTokenNumber = "Update token_distribution set token_number='$token_number' WHERE id='$resTokenId'";
					$resTokenNumber = $this->bm->dataUpdateDB1($updateTokenNumber);
					if($resTokenNumber)
					{
						$msg = "<font color='blue'><b>Data Saved<b></font>";
					}
					else
					{
						$msg = "<font color='red'><b>Sorry! Something went wrong.</b></font>";
					}
				}
				else
				{
					$msg = "<font color='red'><b>Sorry! Something went wrong.</b></font>";
				}
			}
			$this->tokenDistributionForm($msg);
		}
	}
	
	function cpaApproval()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$data['org_id'] =$this->session->userdata('org_Type_id');
			$data['title']="EDO Application List";
			
			$edo_id = $this->input->post("edo_id");
			// $query = "UPDATE edo_application_by_cf SET ff_stat='1' WHERE id='$edo_id'";
			// $update_st=$this->bm->dataUpdateDB1($query);
			// if($update_st==1)
				// $data['msg']='<font color=green>Forwarded Successfully.</font>';
			// else
				// $data['msg']='<font color=red>Forwarding Failed.</font>';
			
			$data['msg']="";
			$data['flag'] = "all"; //To show all do list

            $this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('applicationForEDOList',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	////////// For Shipping Agent (from List).....without B/E restrictions-------------------------------
	function shedDeOInfoData()
	{	
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$rotNo = $this->input->post('imp_rot');			
			$blno = $this->input->post('blNo');			
			$edo_id = $this->input->post('edo_id');			
			$type_of_bl = $this->input->post('bl_type');
			$igm_type = $this->input->post('igm_type');
			$sumitted_by = $this->input->post('sumitted_by');
			//echo $type_of_bl;Bill_of_Entry_No
			//return;
			$msgBLsearch = "";
			$msg = "";
			
			if($this->input->post('editFlag'))
			{
				// "edit code block";
				$id= $this->input->post('uploadId');
				$data['editId'] = $id;
				$measurementVal = "";
				$cnf_lic_no = "";
				$validUptodtVal = "";
				$Bill_of_Entry_No_Val = "";
				$BE_Dt_Val = "";
				$office_Code_Val = "";
				$shedInfoByIdquery = "SELECT * FROM shed_mlo_do_info WHERE id='$id'";
				$shedInfoById = $this->bm->dataSelectDb1($shedInfoByIdquery);
				for($m=0;$m<count($shedInfoById);$m++)
				{
					$measurementVal = $shedInfoById[$m]['measurement'];
					$cnf_lic_no = $shedInfoById[$m]['cnf_lic_no'];
					$validUptodtVal = $shedInfoById[$m]['valid_upto_dt'];
					$Bill_of_Entry_No_Val = $shedInfoById[$m]['be_no'];
					$BE_Dt_Val = $shedInfoById[$m]['be_date'];
					$office_Code_Val = $shedInfoById[$m]['office_code'];
				}
				
				
				$data['shedInfoById']=$shedInfoById;
				$data['measurementVal']=$measurementVal;
				$data['validUptodtVal']=$validUptodtVal;
				$data['Bill_of_Entry_No_Val']=$Bill_of_Entry_No_Val;
				$data['BE_Dt_Val']=$BE_Dt_Val;
				$data['office_Code_Val']=$office_Code_Val;
				$data['cnf_lic_no']=$cnf_lic_no;
				$data['edit'] = "edit";
				
				// $sql_CNFName="SELECT id,name FROM sparcsn4.ref_bizunit_scoped 
							// WHERE role='SHIPPER' AND life_cycle_state='ACT' AND id='$cnf_lic_no'";	
				// $cnf_name=$this->bm->dataSelect($sql_CNFName);
				// $data['cnf_name']=$cnf_name;
			}else{
				$data['edit'] = "";
			}
			
			//Organization Info Starts........................
			if($type_of_bl=="HB" and $igm_type=="GM")
			{
				$orgInfo = "SELECT edo_application_by_cf.ff_org_id,organization_profiles.Organization_Name,
						organization_profiles.Address_1,organization_profiles.Address_2,
						organization_profiles.License_No,organization_profiles.AIN_No_New,organization_profiles.logo,
						organization_profiles.Cell_No_1,organization_profiles.Telephone_No_Land
						FROM edo_application_by_cf
						INNER JOIN organization_profiles ON edo_application_by_cf.ff_org_id=organization_profiles.id
						WHERE edo_application_by_cf.id='$edo_id'";
			}
			else if($type_of_bl=="MB" and $igm_type=="GM")
			{
				$orgInfo = "SELECT edo_application_by_cf.mlo,organization_profiles.Organization_Name,
						organization_profiles.Address_1,organization_profiles.Address_2,
						organization_profiles.License_No,organization_profiles.AIN_No_New,organization_profiles.logo,
						organization_profiles.Cell_No_1,organization_profiles.Telephone_No_Land
						FROM edo_application_by_cf
						INNER JOIN organization_profiles ON edo_application_by_cf.mlo=organization_profiles.id
						WHERE edo_application_by_cf.id='$edo_id'";
			}
			else if($igm_type=="BB")
			{
				$orgInfo = "SELECT edo_application_by_cf.sh_agent_org_id,organization_profiles.Organization_Name,
						organization_profiles.Address_1,organization_profiles.Address_2,
						organization_profiles.License_No,organization_profiles.AIN_No_New,organization_profiles.logo,
						organization_profiles.Cell_No_1,organization_profiles.Telephone_No_Land
						FROM edo_application_by_cf
						INNER JOIN organization_profiles ON edo_application_by_cf.sh_agent_org_id=organization_profiles.id
						WHERE edo_application_by_cf.id='$edo_id'";
			}
			$resOrgInfo = $this->bm->dataSelectDb1($orgInfo);
			for($t=0;$t<count($resOrgInfo);$t++){
				$logo_pic = $resOrgInfo[$t]['logo'];
			}
			$data['logo_pic']=$logo_pic;
			$data['logo_pic']=1;
			//Organization Info Ends..........................
			
			if($blno=="all")
			{
				$sqlQuery="SELECT Bill_of_Entry_No,Bill_of_Entry_Date FROM igm_details WHERE Import_Rotation_No='$rotNo'";	
			}
			else
			{
				$sqlQuery="SELECT Bill_of_Entry_No,Bill_of_Entry_Date FROM igm_details WHERE Import_Rotation_No='$rotNo' AND BL_No='$blno'";
			}
			$reslt = $this->bm->dataSelectDb1($sqlQuery);
			
			
			$resltBE = "";
			if(count($reslt)==0)
			{
				$sqlQuery="SELECT Bill_of_Entry_No,Bill_of_Entry_Date FROM igm_supplimentary_detail WHERE Import_Rotation_No='$rotNo' AND BL_No='$blno'";
				$reslt = $this->bm->dataSelectDb1($sqlQuery);
			}
			
			
			if(count($reslt)>0){
				$resltBE = $reslt[0]['Bill_of_Entry_No'];
			}
			// if($resltBE=="")
			// {
				// $msgBLsearch="<font color='red'><b>Bill of Entry Number not submitted, Please try again after submitting.</b></font>";
				
			// }
			// else
			// {
				$msgBLsearch = "";
				
				$queryContList = "";
				if($type_of_bl=="MB")
				{
					//echo "master ";
					$queryContList="SELECT igm_detail_container.id AS cId,cont_number,cont_seal_number,cont_size,cont_type,cont_height,Cont_gross_weight,cont_weight, Pack_Number 
									FROM igm_detail_container 
									INNER JOIN igm_details ON igm_details.id=igm_detail_container.igm_detail_id 
									WHERE  igm_details.Import_Rotation_No='$rotNo' AND igm_details.BL_No='$blno'";
				}
				else if($type_of_bl=="HB")
				{
					//echo "ff ";
					$queryContList="SELECT igm_sup_detail_container.id AS cId,cont_number,cont_seal_number,cont_size,cont_type,cont_height,Cont_gross_weight,cont_weight,Pack_Number
									FROM igm_sup_detail_container 
									INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=igm_sup_detail_container.igm_sup_detail_id
									WHERE  igm_supplimentary_detail.Import_Rotation_No='$rotNo' AND igm_supplimentary_detail.BL_No='$blno'";
				}
				$contList=$this->bm->dataSelectDb1($queryContList);
				$data['contList']=$contList;
				
				$query="SELECT DISTINCT igm_details.id AS dtl_id,BL_No,Pack_Number,Pack_Description,Pack_Marks_Number,weight,Bill_of_Entry_No,
						igm_details.Bill_of_Entry_Date,igm_details.office_code,
						No_of_Pack_Delivered,DG_status,type_of_igm,net_weight,weight_unit,net_weight_unit,igm_details.Consignee_name,Consignee_address,
						Description_of_Goods,igm_details.Volume_in_cubic_meters,
						igm_masters.id,igm_masters.Import_Rotation_No,vessels_berth_detail.ETA_Date,igm_masters.Vessel_Name,igm_masters.Voy_No,
						igm_masters.Net_Tonnage,Notify_name,Notify_address,port_of_origin,Port_of_Shipment,igm_details.Pack_Marks_Number,
						igm_masters.Name_of_Master,igm_masters.Port_Ship_ID Port_of_Shipment,igm_masters.Port_of_Destination,igm_masters.custom_approved,
						igm_masters.file_clearence_date,Organization_Name AS org_name,igm_masters.Submitee_Org_Type AS Submitee_Org_Type,
						igm_masters.S_Org_License_Number AS S_Org_License_Number,igm_masters.Submission_Date AS Submission_Date,igm_masters.flag AS flag,
						igm_masters.imo AS imo, reg_no,dec_code
						FROM igm_masters
						INNER JOIN igm_details ON  igm_masters.id=igm_details.IGM_id
						LEFT JOIN sad_item ON sad_item.sum_declare=igm_details.BL_No
						LEFT JOIN sad_info ON sad_info.id=sad_item.sad_id
						LEFT JOIN vessels_berth_detail ON vessels_berth_detail.igm_id = igm_masters.id
						LEFT JOIN organization_profiles ON organization_profiles.id = igm_masters.Submitee_Org_Id
						WHERE igm_details.Import_Rotation_No='$rotNo' AND BL_No='$blno' ORDER BY file_clearence_date DESC";
				$doInfo=$this->bm->dataSelectDb1($query);
				
				//---
				if(count($doInfo)==0)
				{
					$query="SELECT DISTINCT igm_details.id AS dtl_id,igm_supplimentary_detail.BL_No,igm_supplimentary_detail.Pack_Number,igm_supplimentary_detail.Pack_Description,igm_supplimentary_detail.Pack_Marks_Number,igm_supplimentary_detail.weight,igm_supplimentary_detail.Bill_of_Entry_No,
					igm_supplimentary_detail.Bill_of_Entry_Date,igm_supplimentary_detail.office_code,
					igm_supplimentary_detail.No_of_Pack_Delivered,igm_supplimentary_detail.DG_status,igm_supplimentary_detail.type_of_igm,igm_supplimentary_detail.net_weight,igm_supplimentary_detail.weight_unit,igm_supplimentary_detail.net_weight_unit,igm_supplimentary_detail.Consignee_name,igm_supplimentary_detail.Consignee_address,
					igm_supplimentary_detail.Description_of_Goods,igm_supplimentary_detail.Volume_in_cubic_meters,
					igm_masters.id,igm_masters.Import_Rotation_No,vessels_berth_detail.ETA_Date,igm_masters.Vessel_Name,igm_masters.Voy_No,
					igm_masters.Net_Tonnage,igm_supplimentary_detail.Notify_name,igm_supplimentary_detail.Notify_address,igm_supplimentary_detail.port_of_origin,Port_of_Shipment,igm_details.Pack_Marks_Number,
					igm_masters.Name_of_Master,igm_masters.Port_Ship_ID Port_of_Shipment,igm_masters.Port_of_Destination,igm_masters.custom_approved,
					igm_masters.file_clearence_date,Organization_Name AS org_name,igm_masters.Submitee_Org_Type AS Submitee_Org_Type,
					igm_masters.S_Org_License_Number AS S_Org_License_Number,igm_masters.Submission_Date AS Submission_Date,igm_masters.flag AS flag,
					igm_masters.imo AS imo,reg_no,dec_code
					FROM igm_masters
					INNER JOIN igm_details ON  igm_masters.id=igm_details.IGM_id
					INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.igm_detail_id=igm_details.id
					LEFT JOIN sad_item ON sad_item.sum_declare=igm_details.BL_No
					LEFT JOIN sad_info ON sad_info.id=sad_item.sad_id
					LEFT JOIN vessels_berth_detail ON vessels_berth_detail.igm_id = igm_masters.id
					LEFT JOIN organization_profiles ON organization_profiles.id = igm_masters.Submitee_Org_Id
					WHERE igm_supplimentary_detail.Import_Rotation_No='$rotNo' AND igm_supplimentary_detail.BL_No='$blno' ORDER BY file_clearence_date DESC";
					$doInfo=$this->bm->dataSelectDb1($query);
				}
				//---
				
				$dtl_id = "";
				$Notify_name = "";
				$Notify_address = "";
				$Vessel_Name = "";
				$Voy_No = "";
				$Import_Rotation_No = "";
				$Bill_of_Entry_No = "";
				$Bill_of_Entry_Date = "";
				$office_code = "";
				$Submission_Date = "";
				$port_of_origin = "";
				$Port_of_Shipment = "";
				$Port_of_Destination = "";
				$Consignee_name = "";
				$Consignee_address = "";
				$Description_of_Goods = "";
				$Pack_Description = "";
				$Pack_Marks_Number = "";
				$weight = "";
				$weight_unit = "";
				$Volume_in_cubic_meters = "";
				$igm_pack_number = "";
				for($j=0;$j<count($doInfo);$j++){
					$dtl_id = $doInfo[$j]['dtl_id'];
					$Notify_name = $doInfo[$j]['Notify_name'];
					$Notify_address = $doInfo[$j]['Notify_address'];
					$Vessel_Name = $doInfo[$j]['Vessel_Name'];
					$Voy_No = $doInfo[$j]['Voy_No'];
					$Import_Rotation_No = $doInfo[$j]['Import_Rotation_No'];
					$Bill_of_Entry_No = $doInfo[$j]['Bill_of_Entry_No'];
					$Bill_of_Entry_Date = $doInfo[$j]['Bill_of_Entry_Date'];
					$Submission_Date = $doInfo[$j]['Submission_Date'];
					$port_of_origin = $doInfo[$j]['port_of_origin'];
					$Port_of_Shipment = $doInfo[$j]['Port_of_Shipment'];
					$Port_of_Destination = $doInfo[$j]['Port_of_Destination'];
					$Consignee_name = $doInfo[$j]['Consignee_name'];
					$Consignee_address = $doInfo[$j]['Consignee_address'];
					$Description_of_Goods = $doInfo[$j]['Description_of_Goods'];
					$Pack_Description = $doInfo[$j]['Pack_Description'];
					$Pack_Marks_Number = $doInfo[$j]['Pack_Marks_Number'];
					$weight = $doInfo[$j]['weight'];
					$weight_unit = $doInfo[$j]['weight_unit'];
					$office_code = $doInfo[$j]['office_code'];
					$Volume_in_cubic_meters = $doInfo[$j]['Volume_in_cubic_meters'];
					$igm_pack_number = $doInfo[$j]['Pack_Number'];
				}
			
				$data['dtl_id']=$dtl_id;
				$data['Notify_name']=$Notify_name;
				$data['Notify_address']=$Notify_address;
				$data['Vessel_Name']=$Vessel_Name;
				$data['Voy_No']=$Voy_No;
				$data['Import_Rotation_No']=$Import_Rotation_No;
				$data['Bill_of_Entry_No']=$Bill_of_Entry_No;
				$data['Bill_of_Entry_Date']=$Bill_of_Entry_Date;
				$data['Submission_Date']=$Submission_Date;
				$data['port_of_origin']=$port_of_origin;
				$data['Port_of_Shipment']=$Port_of_Shipment;
				$data['Port_of_Destination']=$Port_of_Destination;
				$data['Consignee_name']=$Consignee_name;
				$data['Consignee_address']=$Consignee_address;
				$data['Description_of_Goods']=$Description_of_Goods;
				$data['Pack_Description']=$Pack_Description;
				$data['Pack_Marks_Number']=$Pack_Marks_Number;
				$data['weight']=$weight;
				$data['weight_unit']=$weight_unit;
				$data['office_code']=$office_code;
				$data['Volume_in_cubic_meters']=$Volume_in_cubic_meters;
				$data['igm_pack_number']=$igm_pack_number;
			
				$data['doInfo']=$doInfo;
				$dec_code = "";
				$Notify_name = "";
				$Notify_address = "";
				$Vessel_Name = "";
				$Voy_No = "";
				$Bill_of_Entry_No = "";
				$Submission_Date = "";
				$port_of_origin = "";
				for($j=0;$j<count($doInfo);$j++)
					{
						$dec_code = $doInfo[$j]['dec_code'];
						$Notify_name = $doInfo[$j]['Notify_name'];
						$Notify_address = $doInfo[$j]['Notify_address'];
						$Vessel_Name = $doInfo[$j]['Vessel_Name'];
						$Voy_No = $doInfo[$j]['Voy_No'];
						$Bill_of_Entry_No = $doInfo[$j]['Bill_of_Entry_No'];
						$Submission_Date = $doInfo[$j]['Submission_Date'];
						$port_of_origin = $doInfo[$j]['port_of_origin'];
					}
				$data['Notify_name']=$Notify_name;
				$data['Notify_address']=$Notify_address;
				$data['Vessel_Name']=$Vessel_Name;
				$data['Voy_No']=$Voy_No;
				$data['Bill_of_Entry_No']=$Bill_of_Entry_No;
				$data['Submission_Date']=$Submission_Date;
				$data['port_of_origin']=$port_of_origin;
				
				/////////////////////
				$cnfName = "";
				$cnfLicenseNo = "";
				$sql_CNFData="SELECT u_name,org_id,organization_profiles.License_No
							FROM users
							INNER JOIN organization_profiles ON users.org_id=organization_profiles.id
							WHERE users.login_id='$sumitted_by'";
				$res_CNFData=$this->bm->dataSelectDb1($sql_CNFData);
				for($k=0;$k<count($res_CNFData);$k++)
					{
						$cnfName = $res_CNFData[$k]['u_name'];
						$cnfLicenseNo = $res_CNFData[$k]['License_No'];
					}
				$data['cnfName']=$cnfName;
				$data['cnfLicenseNo']=$cnfLicenseNo;
				/////////////////
				
				//////////////////////////
				$requested_valid_dt = "";
				$valid_dt_mlo = "";
				$contSt = "";
				$beNo = "";
				$beDate = "";
				$ofcCode = "";
				$sql_edoApplyInfo="SELECT * FROM edo_application_by_cf WHERE id='$edo_id'";
				$res_EDOApplyInfo=$this->bm->dataSelectDb1($sql_edoApplyInfo);
				for($a=0;$a<count($res_EDOApplyInfo);$a++)
					{
						$requested_valid_dt = $res_EDOApplyInfo[$a]['applied_valid_dt'];
						$valid_dt_mlo = $res_EDOApplyInfo[$a]['valid_upto_dt_by_mlo'];
						$contSt = $res_EDOApplyInfo[$a]['cont_status'];
						$beNo = $res_EDOApplyInfo[$a]['be_no'];
						$beDate = $res_EDOApplyInfo[$a]['be_date'];
						$ofcCode = $res_EDOApplyInfo[$a]['ofc_code'];
					}
				/////////////////////////
				
				$cnfCode2 = substr($dec_code, 5, 4);
				$cnfCode1 = substr($dec_code, 3, 2);
				$cnfLic = $cnfCode2."/".$cnfCode1;
				
				if($this->input->post('editFlag'))
				{
					$sql_CNFName="SELECT id,name FROM sparcsn4.ref_bizunit_scoped WHERE role='SHIPPER' AND life_cycle_state='ACT' AND id='$cnf_lic_no'";
				}
				else
				{
					$sql_CNFName="SELECT id,name FROM sparcsn4.ref_bizunit_scoped WHERE role='SHIPPER' AND life_cycle_state='ACT' AND id='$cnfLic'";
				}
				$cnf_name=$this->bm->dataSelect($sql_CNFName);
				$data['cnf_name']=$cnf_name;
				
				$queryRemainingQty = "SELECT IFNULL(gross_quantity,0),IFNULL(SUM(delv_quantity),0) AS total_delivered,
									(IFNULL(gross_quantity,0)-IFNULL(SUM(delv_quantity),0)) AS remaining
									FROM shed_mlo_do_info
									WHERE shed_mlo_do_info.imp_rot='$rotNo' AND shed_mlo_do_info.bl_no='$blno'";
				$remainingQty=$this->bm->dataSelectDb1($queryRemainingQty);
				$data['remainingQty']=$remainingQty;
				$data['cnfLic']=$cnfLic;
			//}
			
			$data['reslt']=$reslt;
			$data['resltBE']=$resltBE;
			
			$data['frmType']="search";
			$data['title']="Application for EDO";
			//$data['tallytype']=$tallytype;shedInfoById
			$data['msg']=$msg;
			$data['msgBLsearch']=$msgBLsearch;
			$data['blno']=$blno;
			$data['rotNo']=$rotNo;
			$data['type_of_bl']=$type_of_bl;
			$data['igm_type']=$igm_type;
			$data['edo_id']=$edo_id;
			$data['requested_valid_dt']=$requested_valid_dt;
			$data['valid_dt_mlo']=$valid_dt_mlo;
			$data['contSt']=$contSt;
			$data['beNo']=$beNo;
			$data['beDate']=$beDate;
			$data['ofcCode']=$ofcCode;
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('ShedDOForm',$data);
			$this->load->view('jsAssets');
			
		}
	}
	
	function shedDOUpload()
	{
		$user = $this->session->userdata('login_id');
		$user=str_replace(" ","",$user);
		$session_id = $this->session->userdata('value');
		
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{	
			$igm_dtl=$this->input->post('igm_dtl');
			$blno=$this->input->post('blno');
			$blrplc=str_replace("/","_",$blno);
			$rotNo=$this->input->post('rotno');
			$fileRotno=str_replace('/','_',$rotNo);
			$beno=$this->input->post('beno');
			$billOfEntryNo=$this->input->post('billOfEntryNo');
			$billOfEntryDate=$this->input->post('billOfEntryDate');
			$officeCode=$this->input->post('officeCode');
			$valid_upto=$this->input->post('valid_upto');			
			$cnflic=$this->input->post('cnflic');			
			$bl_type=$this->input->post('type_of_bl');			
			$igm_type=$this->input->post('igm_type');			
			$grossQty=$this->input->post('grossQty');
			$deliveredWeight=$this->input->post('deliveredWeight');
			$measurement=$this->input->post('measurement');
			
			if($igm_type!="BB")
			{
				if(isset($_POST['idchk']))
				{
					$containerChk = $_POST['idchk'];
				}				
			}
			
			
			$login_id = $this->session->userdata('login_id');
			$ipaddr = $_SERVER['REMOTE_ADDR'];
			
			//$type_of_Igm=$this->input->post('type_of_Igm');
			//$CODE=$this->input->post('CODE');
			
			$msg = "";
			
			$sql_do_number="SELECT MAX(do_no)+1 AS rtnValue FROM shed_mlo_do_info";
			$do_no=$this->bm->dataReturnDb1($sql_do_number);
						
			if($this->input->post('update'))
			{
				//update code
				$editId = $this->input->post('editId');
				$updateQuery = "Update shed_mlo_do_info set be_no='$billOfEntryNo',be_date='$billOfEntryDate',office_code='$officeCode',
								valid_upto_dt='$valid_upto',cnf_lic_no='$cnflic',measurement='$measurement' 
								WHERE id='$editId'";
				$res_updateShedMLO = $this->bm->dataUpdateDB1($updateQuery);
				
				$sqlDlt = "DELETE FROM do_upload_wise_container WHERE shed_mlo_do_info_id='$editId'";
				$this->bm->dataDeleteDB1($sqlDlt);
				if($igm_type!="BB")
				{
					if(isset($containerChk))
					{
						foreach ($containerChk as $cCheck)
						{
							 // echo $cCheck;
							 // echo "<br>";
							$strInsertContInfo  = "insert into do_upload_wise_container(shed_mlo_do_info_id,cont_igm_id) 
													values('$editId','$cCheck')";
							$resInsertContInfo = $this->bm->dataInsertDB1($strInsertContInfo);
						}
					}
				}
				$msg = "<font color='blue'><b>Saved Successfully!!!</b></font>";
			}
			else
			{
				$edo_id = $this->input->post('edo_id');
				$strUploadQty="select count(*) as rtnValue from shed_mlo_do_info where edo_id='$edo_id'";
				$uploadQty = $this->bm->dataReturnDb1($strUploadQty);
				// $updateUploadSt = "Update edo_application_by_cf set do_upload_st='1' WHERE id='$edo_id'";
				// $res_updateUploadSt = $this->bm->dataUpdateDB1($updateUploadSt);
				
				// $insertQuery="INSERT INTO shed_mlo_do_info(igm_detail_id,imp_rot,bl_no,be_no,be_date,office_code,edo_id,
							// do_no,do_date,valid_upto_dt,cnf_lic_no,bl_type,gross_quantity,
							// delv_quantity,measurement,user_id,upload_time,ip_addr) 
					// VALUES ('$igm_dtl','$rotNo','$blno','$billOfEntryNo','$billOfEntryDate','$officeCode','$edo_id',
							// '$do_no',DATE(NOW()),'$valid_upto','$cnflic','$bl_type','$grossQty',
							// '$deliveredWeight','$measurement',
							// '$login_id',NOW(),'$ipaddr')";
				// $res_insertShedMLO = $this->bm->dataInsertDB1($insertQuery);				
				if($uploadQty ==0)
				{
					if($bl_type=="HB" and $igm_type="GM")
					{
						
						$ffOrgId = "";
						$ffAINno = "";
						$strFFOrgId = "SELECT * FROM edo_application_by_cf WHERE id='$edo_id'";
						$resFFOrgId=$this->bm->dataSelectDb1($strFFOrgId);
						for($x=0;$x<count($resFFOrgId);$x++)
						{
							$ffOrgId=$resFFOrgId[$x]['ff_org_id'];
						}
						$strFFAINno = "SELECT * FROM organization_profiles WHERE id='$ffOrgId' AND Org_Type_id='4'";
						$resFFAINno=$this->bm->dataSelectDb1($strFFAINno);
						for($z=0;$z<count($resFFAINno);$z++)
						{
							$ffAINno=$resFFAINno[$z]['AIN_No_New'];
						}
						
						$cntRemainingToken="SELECT COUNT(*) AS rtnValue FROM token_distribution WHERE ff_ain='$ffAINno' AND used_st='0'";
						$remainingToken = $this->bm->dataReturnDb1($cntRemainingToken);
						if($remainingToken > 0)
						{
							$updateUploadSt = "Update edo_application_by_cf set do_upload_st='1',ff_assoc_st='1' WHERE id='$edo_id'";
							$res_updateUploadSt = $this->bm->dataUpdateDB1($updateUploadSt);
							
							$insertQuery="INSERT INTO shed_mlo_do_info(igm_detail_id,imp_rot,bl_no,be_no,be_date,office_code,edo_id,
								do_no,do_date,valid_upto_dt,cnf_lic_no,bl_type,gross_quantity,
								delv_quantity,measurement,user_id,upload_time,ip_addr) 
								VALUES ('$igm_dtl','$rotNo','$blno','$billOfEntryNo','$billOfEntryDate','$officeCode','$edo_id',
										'$do_no',DATE(NOW()),'$valid_upto','$cnflic','$bl_type','$grossQty',
										'$deliveredWeight','$measurement',
										'$login_id',NOW(),'$ipaddr')";
							$res_insertShedMLO = $this->bm->dataInsertDB1($insertQuery);
							
							$sqlDoId="SELECT id AS rtnValue FROM shed_mlo_do_info WHERE imp_rot='$rotNo' AND bl_no='$blno' AND edo_id='$edo_id'
									ORDER BY id DESC LIMIT 1";
							$doId=$this->bm->dataReturnDb1($sqlDoId);
							if($igm_type!="BB")
							{
								if(isset($containerChk))
								{
									foreach ($containerChk as $cCheck)
									{
										// echo $cCheck;
										// echo "<br>";
										$strInsertContInfo  = "insert into do_upload_wise_container(shed_mlo_do_info_id,cont_igm_id) 
																values('$doId','$cCheck')";
										$resInsertContInfo = $this->bm->dataInsertDB1($strInsertContInfo);
									}
								}
							}
							
							$strTokenId = "SELECT id AS rtnValue FROM token_distribution WHERE ff_ain='$ffAINno' AND used_st=0 
											AND edo_id IS NULL ORDER BY id ASC LIMIT 1";					
							$resTokenId=$this->bm->dataReturnDb1($strTokenId);
							$updateTokenSt = "Update token_distribution set used_st='1',edo_id='$edo_id' WHERE id='$resTokenId'";
							$resTokenSt = $this->bm->dataUpdateDB1($updateTokenSt);	
							$msg = "<font color='blue'><b>Saved Successfully!!!</b></font>";
						}
						else
						{
							$msg="<font color='red'><b>Sorry! You don't have any token remaining !!</b></font>";
						}
					}
					else
					{
						$updateUploadSt = "Update edo_application_by_cf set do_upload_st='1' WHERE id='$edo_id'";
						$res_updateUploadSt = $this->bm->dataUpdateDB1($updateUploadSt);
						
						//insert code
						$insertQuery="INSERT INTO shed_mlo_do_info(igm_detail_id,imp_rot,bl_no,be_no,be_date,office_code,edo_id,
									do_no,do_date,valid_upto_dt,cnf_lic_no,bl_type,gross_quantity,
									delv_quantity,measurement,user_id,upload_time,ip_addr) 
							VALUES ('$igm_dtl','$rotNo','$blno','$billOfEntryNo','$billOfEntryDate','$officeCode','$edo_id',
									'$do_no',DATE(NOW()),'$valid_upto','$cnflic','$bl_type','$grossQty',
									'$deliveredWeight','$measurement',
									'$login_id',NOW(),'$ipaddr')";
						$res_insertShedMLO = $this->bm->dataInsertDB1($insertQuery);
						
						$sqlDoId="SELECT id AS rtnValue FROM shed_mlo_do_info WHERE imp_rot='$rotNo' AND bl_no='$blno' AND edo_id='$edo_id'
								ORDER BY id DESC LIMIT 1";
						$doId=$this->bm->dataReturnDb1($sqlDoId);
						if($igm_type!="BB")
						{
							if(isset($containerChk))
							{
								foreach ($containerChk as $cCheck)
								{
									// echo $cCheck;
									// echo "<br>";
									$strInsertContInfo  = "insert into do_upload_wise_container(shed_mlo_do_info_id,cont_igm_id) 
															values('$doId','$cCheck')";
									$resInsertContInfo = $this->bm->dataInsertDB1($strInsertContInfo);
								}
							}
						}
						$msg = "<font color='blue'><b>Saved Successfully!!!</b></font>";
					}
				}
				else
				{
					$msg = "<font color='red'><b>EDO already uploaded for this Rotation & BL!!!</b></font>";
				}
				
				
				
				// $sqlDoId="SELECT id AS rtnValue FROM shed_mlo_do_info WHERE imp_rot='$rotNo' AND bl_no='$blno' ORDER BY id DESC LIMIT 1";
				// $doId=$this->bm->dataReturnDb1($sqlDoId);
				// if($igm_type!="BB")
				// {
					// if(isset($containerChk))
					// {
						// foreach ($containerChk as $cCheck)
						// {
							// $strInsertContInfo  = "insert into do_upload_wise_container(shed_mlo_do_info_id,cont_igm_id) 
													// values('$doId','$cCheck')";
							// $resInsertContInfo = $this->bm->dataInsertDB1($strInsertContInfo);
						// }
					// }
				// }
			}
			
			$selectQuery="SELECT id as rtnValue FROM shed_mlo_do_info ORDER BY id DESC LIMIT 1";
			$selectID=$this->bm->dataReturnDb1($selectQuery);
			/*
			//image upload starts
			// Access the $_FILES global variable for this specific file being uploaded
			// and create local PHP variables from the $_FILES array of information
			$fileName = $_FILES["dofile"]["name"]; // The file name
			$fileTmpLoc = $_FILES["dofile"]["tmp_name"]; // File in the PHP tmp folder
			$fileType = $_FILES["dofile"]["type"]; // The type of file it is
			$fileSize = $_FILES["dofile"]["size"]; // File size in bytes
			$fileErrorMsg = $_FILES["dofile"]["error"]; // 0 for false... and 1 for true
			$kaboom = explode(".", $fileName); // Split file name into an array using the dot
			$fileExt = end($kaboom); // Now target the last array element to get the file extension
			// START PHP Image Upload Error Handling --------------------------------------------------
			if ($fileTmpLoc) { // if file chosen
				//echo "ERROR: Please browse for a file before clicking the upload button.";
				//exit();
				if($fileSize > 5242880) { // if file size is larger than 5 Megabytes
				echo "ERROR: Your file was larger than 5 Megabytes in size.";
				unlink($fileTmpLoc); // Remove the uploaded file from the PHP temp folder
				exit();
				}  
				else if ($fileErrorMsg == 1) { // if file upload error key is equal to 1
					echo "ERROR: An error occured while processing the file. Try again.";
					exit();
				}
			} 

			$fChosen = 0;

			if ($fileTmpLoc) {

				$fChosen = 1;
				// END PHP Image Upload Error Handling ----------------------------------------------------
				// Place it into your "uploads" folder mow using the move_uploaded_file() function
				$date = date("Ymdhis");
				//$imgName = $blrplc."".$fileRotno."".$selectID.".".$fileExt;

				if($this->input->post('update'))
					{
						$editId = $this->input->post('editId');
						$imgName = $blrplc."_".$fileRotno."_".$editId.".".$fileExt;
					}
				else 
					{
						$imgName = $blrplc."_".$fileRotno."_".$selectID.".".$fileExt;
					}
				
				$moveResult = move_uploaded_file($_FILES["dofile"]["tmp_name"],$_SERVER['DOCUMENT_ROOT']."/pcs/assets/do_image/".$imgName);
				// $moveResult = move_uploaded_file($_FILES["dofile"]["tmp_name"],$_SERVER['DOCUMENT_ROOT']."/myportpanel/resources/do_image/".$_FILES["dofile"]["name"]);
				// Check to make sure the move result is true before continuing
				if ($moveResult != true) {
					echo "ERROR: File not uploaded. Try again.";
					unlink($fileTmpLoc); // Remove the uploaded file from the PHP temp folder
					exit();
				}
				else{	
					if($this->input->post('update'))
					{
						$editId = $this->input->post('editId');
						$imageQuery = "SELECT do_image_loc FROM shed_mlo_do_info WHERE id='$editId'";
						$result = $this->bm->dataSelectDB1($imageQuery);
						$doImageLoc = $result[0]['do_image_loc'];
						//return;
						//unlink($_SERVER['DOCUMENT_ROOT']."/pcs/assets/do_image/".$doImageLoc);
						$updateQuery="UPDATE shed_mlo_do_info SET do_image_loc='$imgName' WHERE id='$editId'";						
					}
					else{
						$updateQuery="UPDATE shed_mlo_do_info SET do_image_loc='$imgName' WHERE id='$selectID'";
					}
					
					$resUpdateQuery = $this->bm->dataUpdateDB1($updateQuery);
					
					$msg="<font color='blue'><b>DO Entry Successfull !!</b></font>";
				}
				//@unlink($fileTmpLoc); // Remove the uploaded file from the PHP temp folder
				
				//image upload ends/
			}
			*/
			
			
			
			$msgBLsearch = "";
			
			$data['msg']=$msg;
			
			// Going back to the list with-----
			$data['org_id'] =$this->session->userdata('org_Type_id');
			$data['title']="EDO Application List";
			$data['flag'] = "all"; //To show all do list
			
            $this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('applicationForEDOList',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function shedDOPDF()
	{	
		$user = $this->session->userdata('login_id');
		$user=str_replace(" ","",$user);
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			
			if($this->uri->segment(3)){		
				$blno = str_replace("_","/",$this->uri->segment(3));
				$rotNo = str_replace("_","/",$this->uri->segment(4));
				$shedMloDo = $this->uri->segment(5);
				$type_of_bl = $this->uri->segment(6);
				$sumitted_by = $this->uri->segment(7);
			}
			else{
				$shedMloDo = $this->input->post('shedMloDo');		
				$rotNo = $this->input->post('rotNo');			
				$blno = $this->input->post('blno');
				$type_of_bl = $this->input->post('bl_type');
				$sumitted_by = $this->input->post('sumitted_by');
			}
			
			$msg = "";
			$resltBE = "";
			
			$edoAppliedTime = "";
			$edoForwardingTime = "";
			$edoIGMType = "";
			
			$sqlEdoApplicationInfo="SELECT * FROM edo_application_by_cf WHERE rotation='$rotNo' AND bl='$blno'";			
			$resEdoApplicationInfo = $this->bm->dataSelectDb1($sqlEdoApplicationInfo);
			for($edo=0;$edo<count($resEdoApplicationInfo);$edo++){
				$edoAppliedTime = $resEdoApplicationInfo[$edo]['entry_time'];
				$edoForwardingTime = $resEdoApplicationInfo[$edo]['ff_clearance_time'];
				$edoIGMType = $resEdoApplicationInfo[$edo]['igm_type'];
			}
			$this->data['edoAppliedTime']=$edoAppliedTime;
			$this->data['edoForwardingTime']=$edoForwardingTime;
			$this->data['edoIGMType']=$edoIGMType;
			
			
			$sqlQuery="SELECT Bill_of_Entry_No FROM igm_details WHERE Import_Rotation_No='$rotNo' AND BL_No='$blno'";			
			$reslt = $this->bm->dataSelectDb1($sqlQuery);
			for($i=0;$i<count($reslt);$i++){
				$resltBE = $reslt[$i]['Bill_of_Entry_No'];
			}
			
			
			
			if($type_of_bl=="MB")
			{
				$queryContList="SELECT cont_number,cont_seal_number,cont_size,cont_type,cont_height,
								Cont_gross_weight,cont_weight,Pack_Number 
								FROM igm_detail_container 
								INNER JOIN igm_details ON igm_details.id=igm_detail_container.igm_detail_id
								INNER JOIN do_upload_wise_container ON do_upload_wise_container.cont_igm_id=igm_detail_container.id
								WHERE igm_details.Import_Rotation_No='$rotNo' AND igm_details.BL_No='$blno'
								AND do_upload_wise_container.shed_mlo_do_info_id='$shedMloDo'";
			}
			else if($type_of_bl=="HB")
			{
				$queryContList="SELECT cont_number,cont_seal_number,cont_size,cont_type,cont_height,
								Cont_gross_weight,cont_weight,Pack_Number
								FROM igm_sup_detail_container 
								INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=igm_sup_detail_container.igm_sup_detail_id
								INNER JOIN do_upload_wise_container ON do_upload_wise_container.cont_igm_id=igm_sup_detail_container.id
								WHERE  igm_supplimentary_detail.Import_Rotation_No='$rotNo' AND igm_supplimentary_detail.BL_No='$blno'
								AND do_upload_wise_container.shed_mlo_do_info_id='$shedMloDo'";
			}
			$contList=$this->bm->dataSelectDb1($queryContList);
			$this->data['contList']=$contList;
			
			
			$query="SELECT DISTINCT igm_details.id AS dtl_id,BL_No,Pack_Number,Pack_Description,Pack_Marks_Number,weight,Bill_of_Entry_No,
						No_of_Pack_Delivered,DG_status,type_of_igm,net_weight,weight_unit,net_weight_unit,igm_details.Consignee_name,Consignee_address,
						Description_of_Goods,
						igm_masters.id,igm_masters.Import_Rotation_No,vessels_berth_detail.ETA_Date,igm_masters.Vessel_Name,igm_masters.Voy_No,
						igm_masters.Net_Tonnage,Notify_name,Notify_address,port_of_origin,Port_of_Shipment,igm_details.Pack_Marks_Number,
						igm_masters.Name_of_Master,igm_masters.Port_Ship_ID Port_of_Shipment,igm_masters.Port_of_Destination,igm_masters.custom_approved,
						igm_masters.file_clearence_date,Organization_Name AS org_name,igm_masters.Submitee_Org_Type AS Submitee_Org_Type,
						igm_masters.S_Org_License_Number AS S_Org_License_Number,igm_masters.Submission_Date AS Submission_Date,igm_masters.flag AS flag,
						igm_masters.imo AS imo, reg_no,dec_code
						FROM igm_masters
						INNER JOIN igm_details ON  igm_masters.id=igm_details.IGM_id
						LEFT JOIN sad_item ON sad_item.sum_declare=igm_details.BL_No
						LEFT JOIN sad_info ON sad_info.id=sad_item.sad_id
						LEFT JOIN vessels_berth_detail ON vessels_berth_detail.igm_id = igm_masters.id
						LEFT JOIN organization_profiles ON organization_profiles.id = igm_masters.Submitee_Org_Id
					WHERE igm_details.Import_Rotation_No='$rotNo' AND BL_No='$blno' ORDER BY file_clearence_date DESC";
			$doInfo=$this->bm->dataSelectDb1($query);
			
			//---
				if(count($doInfo)==0)
				{
					$query="SELECT DISTINCT igm_details.id AS dtl_id,igm_supplimentary_detail.BL_No,igm_supplimentary_detail.Pack_Number,igm_supplimentary_detail.Pack_Description,igm_supplimentary_detail.Pack_Marks_Number,igm_supplimentary_detail.weight,igm_supplimentary_detail.Bill_of_Entry_No,
					igm_supplimentary_detail.No_of_Pack_Delivered,igm_supplimentary_detail.DG_status,igm_supplimentary_detail.type_of_igm,igm_supplimentary_detail.net_weight,igm_supplimentary_detail.weight_unit,igm_supplimentary_detail.net_weight_unit,igm_supplimentary_detail.Consignee_name,igm_supplimentary_detail.Consignee_address,
					igm_supplimentary_detail.Description_of_Goods,
					igm_masters.id,igm_masters.Import_Rotation_No,vessels_berth_detail.ETA_Date,igm_masters.Vessel_Name,igm_masters.Voy_No,
					igm_masters.Net_Tonnage,igm_supplimentary_detail.Notify_name,igm_supplimentary_detail.Notify_address,igm_supplimentary_detail.port_of_origin,Port_of_Shipment,igm_details.Pack_Marks_Number,
					igm_masters.Name_of_Master,igm_masters.Port_Ship_ID Port_of_Shipment,igm_masters.Port_of_Destination,igm_masters.custom_approved,
					igm_masters.file_clearence_date,Organization_Name AS org_name,igm_masters.Submitee_Org_Type AS Submitee_Org_Type,
					igm_masters.S_Org_License_Number AS S_Org_License_Number,igm_masters.Submission_Date AS Submission_Date,igm_masters.flag AS flag,
					igm_masters.imo AS imo,reg_no,dec_code
					FROM igm_masters
					INNER JOIN igm_details ON  igm_masters.id=igm_details.IGM_id
					INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.igm_detail_id=igm_details.id
					LEFT JOIN sad_item ON sad_item.sum_declare=igm_details.BL_No
					LEFT JOIN sad_info ON sad_info.id=sad_item.sad_id
					LEFT JOIN vessels_berth_detail ON vessels_berth_detail.igm_id = igm_masters.id
					LEFT JOIN organization_profiles ON organization_profiles.id = igm_masters.Submitee_Org_Id
					WHERE igm_supplimentary_detail.Import_Rotation_No='$rotNo' AND igm_supplimentary_detail.BL_No='$blno' ORDER BY file_clearence_date DESC";
					$doInfo=$this->bm->dataSelectDb1($query);
				}
				//---
			
			$Notify_name = "";
			$Notify_address = "";
			$Vessel_Name = "";
			$Voy_No = "";
			$Import_Rotation_No = "";
			
			$igm_pack_number = "";
			$Submission_Date = "";
			$port_of_origin = "";
			$Port_of_Shipment = "";
			$Port_of_Destination = "";
			$Consignee_name = "";
			$Consignee_address = "";
			$Description_of_Goods = "";
			$Pack_Description = "";
			$Pack_Marks_Number = "";
			$weight = "";
			$weight_unit = "";
			for($j=0;$j<count($doInfo);$j++){
				$Notify_name = $doInfo[$j]['Notify_name'];
				$Notify_address = $doInfo[$j]['Notify_address'];
				$Vessel_Name = $doInfo[$j]['Vessel_Name'];
				$Voy_No = $doInfo[$j]['Voy_No'];
				$Import_Rotation_No = $doInfo[$j]['Import_Rotation_No'];
				$Bill_of_Entry_No = $doInfo[$j]['Bill_of_Entry_No'];
				$Submission_Date = $doInfo[$j]['Submission_Date'];
				$port_of_origin = $doInfo[$j]['port_of_origin'];
				$Port_of_Shipment = $doInfo[$j]['Port_of_Shipment'];
				$Port_of_Destination = $doInfo[$j]['Port_of_Destination'];
				$Consignee_name = $doInfo[$j]['Consignee_name'];
				$Consignee_address = $doInfo[$j]['Consignee_address'];
				$Description_of_Goods = $doInfo[$j]['Description_of_Goods'];
				$Pack_Description = $doInfo[$j]['Pack_Description'];
				$Pack_Marks_Number = $doInfo[$j]['Pack_Marks_Number'];
				$weight = $doInfo[$j]['weight'];
				$weight_unit = $doInfo[$j]['weight_unit'];
				$igm_pack_number = $doInfo[$j]['Pack_Number'];
			}
			
			$this->data['Notify_name']=$Notify_name;
			$this->data['Notify_address']=$Notify_address;
			$this->data['Vessel_Name']=$Vessel_Name;
			$this->data['Voy_No']=$Voy_No;
			$this->data['Import_Rotation_No']=$Import_Rotation_No;
			$this->data['Submission_Date']=$Submission_Date;
			$this->data['port_of_origin']=$port_of_origin;
			$this->data['Port_of_Shipment']=$Port_of_Shipment;
			$this->data['Port_of_Destination']=$Port_of_Destination;
			$this->data['Consignee_name']=$Consignee_name;
			$this->data['Consignee_address']=$Consignee_address;
			$this->data['Description_of_Goods']=$Description_of_Goods;
			$this->data['Pack_Description']=$Pack_Description;
			$this->data['Pack_Marks_Number']=$Pack_Marks_Number;
			$this->data['weight']=$weight;
			$this->data['weight_unit']=$weight_unit;
			$this->data['igm_pack_number']=$igm_pack_number;
			
			$this->data['doInfo']=$doInfo;
			
			$queryRemainingQty = "SELECT IFNULL(gross_quantity,0),IFNULL(SUM(delv_quantity),0) AS total_delivered,
								(IFNULL(gross_quantity,0)-IFNULL(SUM(delv_quantity),0)) AS remaining
								FROM shed_mlo_do_info
								WHERE shed_mlo_do_info.imp_rot='$rotNo' AND shed_mlo_do_info.bl_no='$blno'";
			$remainingQty=$this->bm->dataSelectDb1($queryRemainingQty);
			$this->data['remainingQty']=$remainingQty;
			
			$edoUploadingTime = "";
			$measurement = "";
			$valid_upto_dt = "";
			$Bill_of_Entry_No = "";
			$Bill_of_Entry_Dt = "";
			$office_code = "";
			$queryShedMloDOList = "SELECT * FROM shed_mlo_do_info WHERE id='$shedMloDo'";
			$ShedMloDOList=$this->bm->dataSelectDb1($queryShedMloDOList);
			
			for($mlo=0;$mlo<count($ShedMloDOList);$mlo++){
				$edoUploadingTime = $ShedMloDOList[$mlo]['upload_time'];
				$measurement = $ShedMloDOList[$mlo]['measurement'];
				$valid_upto_dt = $ShedMloDOList[$mlo]['valid_upto_dt'];
				$Bill_of_Entry_No = $ShedMloDOList[$mlo]['be_no'];
				$Bill_of_Entry_Dt = $ShedMloDOList[$mlo]['be_date'];
				$office_code = $ShedMloDOList[$mlo]['office_code'];
			}
			
			$cnf_lic_no = $ShedMloDOList[0]['cnf_lic_no'];
			$this->data['ShedMloDOList']=$ShedMloDOList;
			$this->data['edoUploadingTime']=$edoUploadingTime;
			$this->data['measurement']=$measurement;
			$this->data['valid_upto_dt']=$valid_upto_dt;
			$this->data['Bill_of_Entry_No']=$Bill_of_Entry_No;
			$this->data['Bill_of_Entry_Dt']=$Bill_of_Entry_Dt;
			$this->data['office_code']=$office_code;
			
			$sql_CNFName="SELECT id,name FROM sparcsn4.ref_bizunit_scoped WHERE role='SHIPPER' AND life_cycle_state='ACT' AND id='$cnf_lic_no'";	
			$cnf_name=$this->bm->dataSelect($sql_CNFName);
			//$cnfName = $cnf_name[0]['name'];
			$this->data['cnf_name']=$cnf_name;
			
			/////////////////////
				$cnfName = "";
				$cnfLicenseNo = "";
				$sql_CNFData="SELECT u_name,org_id,organization_profiles.License_No
							FROM users
							INNER JOIN organization_profiles ON users.org_id=organization_profiles.id
							WHERE users.login_id='$sumitted_by'";
				$res_CNFData=$this->bm->dataSelectDb1($sql_CNFData);
				for($k=0;$k<count($res_CNFData);$k++)
					{
						$cnfName = $res_CNFData[$k]['u_name'];
						$cnfLicenseNo = $res_CNFData[$k]['License_No'];
					}
				$this->data['cnfName']=$cnfName;
				$this->data['cnfLicenseNo']=$cnfLicenseNo;
			/////////////////
			$Organization_Name = "";
			$Address_1 = "";
			$Address_2 = "";
			$License_No = "";
			$AIN_No_New = "";
			$logo_pic = "";
			$Cell_No_1 = "";
			$Telephone_No_Land = "";
			if($type_of_bl=="HB" and $edoIGMType=="GM")
			{
				$orgLogo = "SELECT edo_application_by_cf.ff_org_id,organization_profiles.Organization_Name,
						organization_profiles.Address_1,organization_profiles.Address_2,
						organization_profiles.License_No,organization_profiles.AIN_No_New,organization_profiles.logo,
						organization_profiles.Cell_No_1,organization_profiles.Telephone_No_Land
						FROM edo_application_by_cf
						INNER JOIN organization_profiles ON edo_application_by_cf.ff_org_id=organization_profiles.id
						WHERE rotation='$rotNo' AND bl='$blno'";
			}
			else if($type_of_bl=="MB" and $edoIGMType=="GM")
			{
				$orgLogo = "SELECT edo_application_by_cf.mlo,organization_profiles.Organization_Name,
						organization_profiles.Address_1,organization_profiles.Address_2,
						organization_profiles.License_No,organization_profiles.AIN_No_New,organization_profiles.logo,
						organization_profiles.Cell_No_1,organization_profiles.Telephone_No_Land
						FROM edo_application_by_cf
						INNER JOIN organization_profiles ON edo_application_by_cf.mlo=organization_profiles.id
						WHERE rotation='$rotNo' AND bl='$blno'";
			}
			else if($edoIGMType=="BB")
			{
				$orgLogo = "SELECT edo_application_by_cf.sh_agent_org_id,organization_profiles.Organization_Name,
						organization_profiles.Address_1,organization_profiles.Address_2,
						organization_profiles.License_No,organization_profiles.AIN_No_New,organization_profiles.logo,
						organization_profiles.Cell_No_1,organization_profiles.Telephone_No_Land
						FROM edo_application_by_cf
						INNER JOIN organization_profiles ON edo_application_by_cf.sh_agent_org_id=organization_profiles.id
						WHERE rotation='$rotNo' AND bl='$blno'";
			}
			$resOrgLogo = $this->bm->dataSelectDb1($orgLogo);
			for($t=0;$t<count($resOrgLogo);$t++){
				$logo_pic = $resOrgLogo[$t]['logo'];
				$Organization_Name = $resOrgLogo[$t]['Organization_Name'];
				$Address_1 = $resOrgLogo[$t]['Address_1'];
				$Address_2 = $resOrgLogo[$t]['Address_2'];
				$License_No = $resOrgLogo[$t]['License_No'];
				$AIN_No_New = $resOrgLogo[$t]['AIN_No_New'];
				$Cell_No_1 = $resOrgLogo[$t]['Cell_No_1'];
				$Telephone_No_Land = $resOrgLogo[$t]['Telephone_No_Land'];
			}
			// echo $logo_pic;
			// return;
			
			$this->data['Organization_Name']=$Organization_Name;
			$this->data['Address_1']=$Address_1;
			$this->data['Address_2']=$Address_2;
			$this->data['License_No']=$License_No;
			$this->data['AIN_No_New']=$AIN_No_New;
			$this->data['logo_pic']=$logo_pic;
			$this->data['Cell_No_1']=$Cell_No_1;
			$this->data['Telephone_No_Land']=$Telephone_No_Land;
			
			$this->data['reslt']=$reslt;
			$this->data['resltBE']=$resltBE;
			
			$this->data['frmType']="search";
			
			$this->data['title']="Shed Delivery Order Info Entry";
			$this->data['type_of_bl']=$type_of_bl;
			$this->data['msg']=$msg;
			$this->data['blno']=$blno;
			$this->data['shedMloDo']=$shedMloDo;
			
			$this->load->library('m_pdf');
			//$mpdf->use_kwt = true;
			
			$html=$this->load->view('EDOPDF',$this->data, true); 

			$pdfFilePath ="EDOPDF-".time()."-download.pdf";

			$pdf = $this->m_pdf->load();

			// $pdf->SetWatermarkText('CPA CTMS');
			// $pdf->showWatermarkText = true;

			$pdf->useSubstitutions = true; 
				
			//$pdf->setFooter('Prepared By : '.$user.'|Page {PAGENO}|Date {DATE j-m-Y}');

			//Following 1 line is used for debugging the error:- "HTML contains invalid UTF-8 character(s)"
			$html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
			
			$pdf->WriteHTML($html,2);
				
			$pdf->Output($pdfFilePath, "I");
		}
	}
	
	function tokenDistributionList()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$org_Type_id =$this->session->userdata('org_Type_id');
			$org_id =$this->session->userdata('org_id');
			
			$queryFFList="SELECT * FROM organization_profiles WHERE Org_Type_id='4' ORDER BY id DESC";
			$ffList = $this->bm->dataSelectDb1($queryFFList);
			if($org_Type_id=="73")
			{
				$queryTokenList="SELECT * FROM token_distribution ORDER BY id DESC";
				$tokenList = $this->bm->dataSelectDb1($queryTokenList);
			}
			else
			{
				$queryFFAINno="SELECT AIN_No_New AS rtnValue FROM organization_profiles WHERE id='$org_id'";
				$resFFAINno = $this->bm->dataReturnDb1($queryFFAINno);
				
				$queryTokenList="SELECT * FROM token_distribution WHERE ff_ain='$resFFAINno'";
				$tokenList = $this->bm->dataSelectDb1($queryTokenList);
			}
			// echo $queryTokenList;return;
			
			$data['title']="Token Distribution List";
			$msg = "";
			$data['msg'] = $msg;
			$data['org_Type_id'] = $org_Type_id;
			$data['frmType'] = "new";
			$data['ffList'] = $ffList;
			$data['tokenList'] = $tokenList;
			
            $this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('tokenDistributionList',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function tokenDistributionSearch()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$org_Type_id =$this->session->userdata('org_Type_id');
			$org_id =$this->session->userdata('org_id');
			if($this->uri->segment(3))
			{
				$search_criteria = $this->uri->segment(3);
			}
			else
			{
				$search_criteria = $this->input->post('search_criteria');
			}
			$ffCondition = "";
			$searchCondition = "";
			$searchByFF = "";
			$msg = "";
			if($org_Type_id=="73")
			{
				$ff_ain = $this->input->post('ff_ain');				
				if($ff_ain=="all")
				{
					if($search_criteria=="all")
					{
						$searchCondition = " ";
					}
					else if($search_criteria=="used")
					{
						$searchCondition = "WHERE used_st=1";
					}
					else if($search_criteria=="balance")
					{
						$searchCondition = "WHERE used_st=0";
					}
					
					$ffCondition = "";
				}
				else
				{
					if($search_criteria=="all")
					{
						$searchCondition = " WHERE (used_st=1 OR used_st=0)";
					}
					else if($search_criteria=="used")
					{
						$searchCondition = "WHERE used_st=1";
					}
					else if($search_criteria=="balance")
					{
						$searchCondition = "WHERE used_st=0";
					}
					
					$ffCondition = " AND ff_ain='$ff_ain'";
					$querySearchByFF="SELECT * FROM organization_profiles WHERE AIN_No_New='$ff_ain' AND Org_Type_id='4'";
					$searchByFF = $this->bm->dataSelectDb1($querySearchByFF);
					$data['searchByFF'] = $searchByFF;
				}
				$data['ff_ain'] = $ff_ain;
				
			}
			else
			{
				if($search_criteria=="all")
				{
					$searchCondition = " WHERE (used_st=1 OR used_st=0)";
				}
				else if($search_criteria=="used")
				{
					$searchCondition = "WHERE used_st=1";
				}
				else if($search_criteria=="balance")
				{
					$searchCondition = "WHERE used_st=0";
				}
			
				$strAin="SELECT AIN_No_New AS rtnValue FROM organization_profiles WHERE id='$org_id'";
				$ff_ain = $this->bm->dataReturnDb1($strAin);
				$ffCondition = " AND ff_ain='$ff_ain'";
			}
						
			
			
			$queryTokenList="SELECT * FROM token_distribution ".$searchCondition.$ffCondition;			
			$tokenList = $this->bm->dataSelectDb1($queryTokenList);
			///Redirect
			$queryFFList="SELECT * FROM organization_profiles WHERE Org_Type_id='4' ORDER BY id DESC";
			$ffList = $this->bm->dataSelectDb1($queryFFList);
			
			
			
			$data['title']="Token Distribution List";
			$msg = "";
			$data['msg'] = $msg;
			$data['org_Type_id'] = $org_Type_id;
			$data['search_criteria'] = $search_criteria;
			$data['frmType'] = "search";
			$data['ffList'] = $ffList;
			$data['tokenList'] = $tokenList;
			
            $this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('tokenDistributionList',$data);
			$this->load->view('jsAssetsList');
			
		}
	}
	
	function pendingDOList()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$data['org_id'] =$this->session->userdata('org_Type_id');
			$data['title']="EDO Pending List";
			$data['msg'] = "";
			$data['flag'] = "pending"; //To show the pending do list 
						
            $this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('applicationForEDOList',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	function dateWiseTokenDist()
	{

		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');			
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{	
			
			$data['title']="Date Wise Token Distribution Form";
			$data['flag']="new";
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('DateWiseTokenDistributionList',$data);
			$this->load->view('jsAssetsList');

		}
	}
	function dateTokenDistributionFormAction () {
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');			
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{				
			$fromDate = $this->input->post('fromdate');
			$toDate = $this->input->post('todate');

			$qryTokenCount = "SELECT ff_ain,ff_name,COUNT(ff_ain) AS Quantity FROM token_distribution
			WHERE DATE(entry_time) BETWEEN '$fromDate' AND '$toDate'
			GROUP BY ff_ain";		
			$rsltTokenCount = $this->bm->dataselectDb1($qryTokenCount);
			if($this->input->post('pdfView'))
			{
				$this->load->library('m_pdf');
				$this->data['fromDate']=$fromDate;
				$this->data['toDate']=$toDate;
				$this->data['rsltTokenCount']=$rsltTokenCount;
				
				$html=$this->load->view('DateWiseTokenDistributionPDF',$this->data, true); //load the pdf_output.php by passing our data and get all data in $html varriable.
				$pdfFilePath ="tokenDistribution-".time()."-download.pdf";
				$pdf = $this->m_pdf->load();
				$pdf->allow_charset_conversion = true;
				$pdf->charset_in = 'iso-8859-4';
				$stylesheet = file_get_contents('assets/stylesheets/test.css'); // external css	
				$pdf->WriteHTML($stylesheet,1);
				$pdf->WriteHTML($html,2);
				$pdf->Output($pdfFilePath, "I"); // For Show Pdf
			}
			else
			{
				$data['title']="Date Wise Token Distribution Form";
				$data['rsltTokenCount'] = $rsltTokenCount;
				$data['fromDate'] = $fromDate;
				$data['toDate'] = $toDate;
				$data['flag']="search";

				$this->load->view('cssAssetsList');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('DateWiseTokenDistributionList',$data);
				$this->load->view('jsAssetsList');
			}
		}
	}

	function chkBlockedContainer($cont = null)
	{
		// $user = $this->session->userdata('login_id');
		// $user=str_replace(" ","",$user);
		// $session_id = $this->session->userdata('value');
		// $LoginStat = $this->session->userdata('LoginStat');
		
		// if($LoginStat!="yes")
		// {
		// 	$this->logout();
		// }
		// else
		// {
			$query = "SELECT custom_block_st 
			FROM ctmsmis.tmp_vcms_assignment 
			WHERE cont_no = '$cont' AND custom_block_st = 'Blocked' 
			ORDER BY block_update_dt DESC 
			LIMIT 1";
			$sts = $this->bm->dataSelect($query);
			return $sts;
		// }
	}

	//Truck Entry LCL  -- Start

	function cnfTruckEntryLCL($rotNo=null,$blNo=null,$cont_status=null,$assignmentType=null,$msg=null)
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
		else
		{
			$org_license = $this->session->userdata('org_license');
			$login_id = $this->session->userdata('login_id');
			$ip_address = $_SERVER['REMOTE_ADDR'];
			
			// echo $rotNo;
			// echo $blNo;
			
			$msg = " ";
			// $rotNo = "";
			// $blNo = "";
			$cont_status = "";
			$verifyReport = "";
			$sql_posYardBlock = "";
			$rtnVerifyReport = null;
			$cont_blocked_status = "";
			$jettyEdit = 0;

			// if($rotNo==null and $contNo==null and $cont_status==null and $assignmentType==null)		// when function is called from list
			// {
			// 	$rotNo = $this->input->post('rotNo');
			// 	$contNo = $this->input->post('contNo');
			// 	$unit_gkey=$this->input->post('unit_gkey');
			// 	$cont_status = $this->input->post('cont_status');
			// 	$assignmentType = $this->input->post('assignmentType');
			// 	//$srcFlag = "list";		

			// 	$data['unit_gkey']=$unit_gkey;			
			// }
			// else
			// {
			
			// }

			if($this->input->post('editId') || $this->input->post('delId') || $this->input->post('payAllBtn') || $this->input->post('addBtn') || $this->input->post('deliver') || $this->input->post('payBtn') || $this->input->post('payment')){  
				$assignmentType = "";
				$msg = "";
			}
			
			
			$title= "TRUCK DETAIL ENTRY FORM";
									
			if($this->input->post('search') or ($assignmentType==null and $msg==null))
			{
				$editVal = 0;
				$addVal = 0;
				$payVal = 0;
				$payForm = 0;
				
				// echo $rotNo;
				// echo $blNo;

				if($this->input->post('delBtn'))
				{
					$editVal = 0;
					
					$editId = $this->input->post('editId');
					$btnType = $this->input->post('btnType');
					$contNo = $this->input->post('contNo');
					$rotNo = $this->input->post('rotNo');
					$cont_status = $this->input->post('cont_status');
					
					$editType = $this->input->post('editBtn');
					$data['editType']=$editType;	
					
					$delId = $this->input->post('delId');	
					$sql_select = "select * from do_truck_details_entry WHERE id='$delId'";
					$rslt_select = $this->bm->dataSelectDB1($sql_select);

					$id = "";
					$verify_info_fcl_id = "";
					$verify_other_data_id = "";
					$verify_number = "";
					$import_rotation = "";
					$cont_no = "";
					$truck_id = "";
					$gate_no = "";
					$driver_name = "";
					$driver_gate_pass = "";
					$assistant_name = "";
					$assistant_gate_pass = "";
					$truck_agency_name = "";
					$truck_agency_phone = "";
					$last_update = "";
					$ip_addr = "";
					$update_by = "";
					$paid_amt = "";
					$paid_status = "";
					$paid_method = "";
					$visit_time_slot_start = "";
					$visit_time_slot_end = "";
					$emrgncy_flag = "";
					$emrgncy_approve_stat = "";
					$is_confirm = "";
					$driver_id = "";
					$helper_id = "";

					for($z=0;$z<count($rslt_select);$z++){
						$id = $rslt_select[$z]['id'];
						$verify_info_fcl_id = $rslt_select[$z]['verify_info_fcl_id'];
						$verify_other_data_id = $rslt_select[$z]['verify_other_data_id'];
						$verify_number = $rslt_select[$z]['verify_number'];
						$import_rotation = $rslt_select[$z]['import_rotation'];
						$cont_no = $rslt_select[$z]['cont_no'];
						$truck_id = $rslt_select[$z]['truck_id'];
						$gate_no = $rslt_select[$z]['gate_no'];
						$driver_name = $rslt_select[$z]['driver_name'];
						$driver_gate_pass = $rslt_select[$z]['driver_gate_pass'];
						$assistant_name = $rslt_select[$z]['assistant_name'];
						$assistant_gate_pass = $rslt_select[$z]['assistant_gate_pass'];
						$truck_agency_name = $rslt_select[$z]['truck_agency_name'];
						$truck_agency_phone = $rslt_select[$z]['truck_agency_phone'];
						$last_update = $rslt_select[$z]['last_update'];
						$ip_addr = $rslt_select[$z]['ip_addr'];
						$update_by = $rslt_select[$z]['update_by'];
						$paid_amt = $rslt_select[$z]['paid_amt'];
						$paid_status = $rslt_select[$z]['paid_status'];
						$paid_method = $rslt_select[$z]['paid_method'];
						$visit_time_slot_start = $rslt_select[$z]['visit_time_slot_start'];
						$visit_time_slot_end = $rslt_select[$z]['visit_time_slot_end'];
						$emrgncy_flag = $rslt_select[$z]['emrgncy_flag'];
						$emrgncy_approve_stat = $rslt_select[$z]['emrgncy_approve_stat'];
						$is_confirm = $rslt_select[$z]['is_confirm'];
						$driver_id = $rslt_select[$z]['driver_id'];
						$helper_id = $rslt_select[$z]['helper_id'];
					}

					$sql_log = "INSERT INTO delete_log_do_truck_details(visit_id,verify_info_fcl_id,verify_other_data_id,verify_number,import_rotation,cont_no,	truck_id,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,truck_agency_name,truck_agency_phone,last_update,ip_addr,update_by,paid_amt,paid_status,paid_method,visit_time_slot_start,visit_time_slot_end,emrgncy_flag,emrgncy_approve_stat,is_confirm,driver_id,helper_id,deleted_by,deleted_time,delete_by_ip) VALUES('$id','$verify_info_fcl_id','$verify_other_data_id','$verify_number','$import_rotation','$cont_no','$truck_id','$gate_no','$driver_name','$driver_gate_pass','$assistant_name','$assistant_gate_pass','$truck_agency_name','$truck_agency_phone','$last_update','$ip_addr','$update_by','$paid_amt','$paid_status','$paid_method','$visit_time_slot_start','$visit_time_slot_end','$emrgncy_flag','$emrgncy_approve_stat','$is_confirm','$driver_id','$helper_id','$login_id',NOW(),'$ip_address')";
					$this->bm->dataInsertDB1($sql_log);
					
					$sql_delete = "DELETE  FROM do_truck_details_entry WHERE id='$delId'";
					$del_st = $this->bm->dataDeleteDB1($sql_delete);
							
				}
			
				$rotNo = $this->input->post('rotNo');
				$blNo = $this->input->post('blNo');
				$cont_status = $this->input->post('cont_status');
				
				// echo $rotNo;
				// echo $blNo;
				
				// $verifyReport = "SELECT shed_bill_master.bill_no,shed_tally_info.verify_number,shed_tally_info.verify_unit AS unit_no,igm_supplimentary_detail.Import_Rotation_No AS import_rotation, igm_masters.Vessel_Name AS vessel_name,igm_supplimentary_detail.BL_No AS bl_no, igm_supplimentary_detail.Description_of_Goods,Qty,bill_rcv_stat,IF(bill_rcv_stat=1,'Paid','Not Paid') AS paid_status,igm_sup_detail_container.cont_number,igm_sup_detail_container.cont_size,igm_sup_detail_container.cont_height,
				// igm_supplimentary_detail.Pack_Number,igm_supplimentary_detail.Pack_Description,verify_other_data.no_of_truck 
				// FROM igm_sup_detail_container 
				// LEFT JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=igm_sup_detail_container.igm_sup_detail_id
				// LEFT JOIN shed_tally_info ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
				// LEFT JOIN verify_other_data ON verify_other_data.shed_tally_id=shed_tally_info.id
				// LEFT JOIN shed_bill_master ON shed_bill_master.verify_no = shed_tally_info.verify_number 
				// LEFT JOIN shed_bill_details ON shed_bill_master.bill_no =shed_bill_details.bill_no
				// LEFT JOIN igm_masters ON igm_masters.id=igm_supplimentary_detail.igm_master_id
				// WHERE igm_supplimentary_detail.Import_Rotation_No='$rotNo' AND igm_supplimentary_detail.BL_no='$blNo'";
				
				$verifyReport = "SELECT shed_bill_master.bill_no,shed_tally_info.verify_number,shed_tally_info.verify_unit AS unit_no,igm_supplimentary_detail.Import_Rotation_No AS import_rotation, igm_masters.Vessel_Name AS vessel_name,igm_supplimentary_detail.BL_No AS bl_no, igm_supplimentary_detail.Description_of_Goods,Qty,bill_rcv_stat,IF(bill_rcv_stat=1,'Paid','Not Paid') AS paid_status,igm_sup_detail_container.cont_number,igm_sup_detail_container.cont_size,igm_sup_detail_container.cont_height,
				igm_supplimentary_detail.Pack_Number,igm_supplimentary_detail.Pack_Description,lcl_dlv_assignment.no_of_truck 
				FROM igm_sup_detail_container 
				LEFT JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=igm_sup_detail_container.igm_sup_detail_id
				LEFT JOIN shed_tally_info ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id
				LEFT JOIN lcl_dlv_assignment ON lcl_dlv_assignment.igm_sup_dtl_id = igm_supplimentary_detail.id
				LEFT JOIN shed_bill_master ON shed_bill_master.verify_no = shed_tally_info.verify_number 
				LEFT JOIN shed_bill_details ON shed_bill_master.bill_no =shed_bill_details.bill_no
				LEFT JOIN igm_masters ON igm_masters.id=igm_supplimentary_detail.igm_master_id
				WHERE igm_supplimentary_detail.Import_Rotation_No='$rotNo' AND igm_supplimentary_detail.BL_no='$blNo'";
				$rtnVerifyReport = $this->bm->dataSelectDB1($verifyReport);	

				if(count($rtnVerifyReport)==0)		// don't proceed
				{
					$msg = "<font color='red'>Rotation and BL are not matching</font>";
				}
				else		// proceed
				{
					// work later for yard and block
					$sql_posYardBlock = "SELECT slot AS currentPos,Yard_No,Block_No,assignmentDate
					FROM ctmsmis.tmp_vcms_assignment 
					WHERE rot_no='$rotNo' AND cont_no='".$rtnVerifyReport[0]['cont_number']."'";						
					$rslt_posYardBlock = $this->bm->dataSelect($sql_posYardBlock);
					
					$data['rslt_posYardBlock'] = $rslt_posYardBlock;
					
					// if($rtnVerifyReport[0]['cont_size']==20)
						// $totTruck = 2;
					// else if($rtnVerifyReport[0]['cont_size']==40 or $rtnVerifyReport[0]['cont_size']==45)
						// $totTruck = 3;
					// $data['totTruck'] = $totTruck;
					
					// $totTruck = $rtnVerifyReport[0]['no_of_truck'];    //04-04-2021
					// $data['totTruck'] = $totTruck;
					
					// $editVal = 0;
					// $addVal = 0;
					// $payVal = 0;
					// $payForm = 0;
					
					// 
					$sql_slotQty = "SELECT slot_1_qty,slot_2_qty,slot_3_qty
					FROM vcms_truck_slot";
					$rslt_slotQty = $this->bm->dataSelectDB1($sql_slotQty);
					$data['rslt_slotQty']=$rslt_slotQty;
					
					
					// driver helper info
					$sql_driverInfo = "SELECT id,card_number,agent_name
					FROM vcms_vehicle_agent
					WHERE agent_type='Driver'";
					$rslt_driverInfo = $this->bm->dataSelectDB1($sql_driverInfo);
					$data['rslt_driverInfo']=$rslt_driverInfo;
					
					$sql_helperInfo = "SELECT id,card_number,agent_name
					FROM vcms_vehicle_agent
					WHERE agent_type='Helper'";
					$rslt_helperInfo = $this->bm->dataSelectDB1($sql_helperInfo);
					$data['rslt_helperInfo']=$rslt_helperInfo;

				
					if($this->input->post('jettyedit')){
						$jettyEdit = 1;
					}
					
					// $sql_vrfyOtherDataId = "SELECT id FROM verify_other_data 
					// WHERE rotation='$rotNo' AND cont_number='".$rtnVerifyReport[0]['cont_number']."'";
					
					// $sql_vrfyOtherDataId = "SELECT verify_other_data.id
					// FROM verify_other_data
					// INNER JOIN shed_tally_info ON shed_tally_info.id=verify_other_data.shed_tally_id
					// WHERE shed_tally_info.import_rotation = '$rotNo' AND shed_tally_info.cont_number = '".$rtnVerifyReport[0]['cont_number']."'";

					//Query changed at 4-4-2021 temporary

					// $sql_vrfyOtherDataId = "SELECT id,igm_sup_detail_id FROM verify_other_data WHERE igm_sup_detail_id = (SELECT igm_supplimentary_detail.id FROM igm_supplimentary_detail 
					// INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id = igm_supplimentary_detail.id
					// WHERE cont_number = '".$rtnVerifyReport[0]['cont_number']."' AND Import_Rotation_No='$rotNo' AND BL_No='$blNo')";
					
					// taken lcl_dlv_assignment id 
					
					$sql_vrfyOtherDataId = "SELECT id,igm_sup_dtl_id FROM lcl_dlv_assignment WHERE igm_sup_dtl_id = (SELECT igm_supplimentary_detail.id FROM igm_supplimentary_detail 
					INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id = igm_supplimentary_detail.id
					WHERE cont_number = '".$rtnVerifyReport[0]['cont_number']."' AND Import_Rotation_No='$rotNo' AND BL_No='$blNo')";

					$rslt_vrfyOtherDataId = $this->bm->dataSelectDB1($sql_vrfyOtherDataId);
					$vrfyOtherDataId = $rslt_vrfyOtherDataId[0]['id'];   // lcl_dlv_assignment id 
					// $igmSupDtlId = $rslt_vrfyOtherDataId[0]['igm_sup_detail_id'];
					$igmSupDtlId = $rslt_vrfyOtherDataId[0]['igm_sup_dtl_id'];
					$data['vrfyOtherDataId']=$vrfyOtherDataId;

					// $totTruckQuery = "SELECT no_of_truck FROM verify_other_data WHERE id='$vrfyOtherDataId'";
					// $rslt_totTruckQuery = $this->bm->dataSelectDB1($totTruckQuery);

					$totTruck = $rtnVerifyReport[0]['no_of_truck'];
					$data['totTruck'] = $totTruck;

					$sql_tmpTrkData = "SELECT id,verify_other_data_id,truck_id,delv_pack AS pkg_qty,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,paid_amt,paid_status,paid_method,emrgncy_flag,emrgncy_approve_stat,gate_out_status
					FROM do_truck_details_entry
					WHERE verify_other_data_id = '$vrfyOtherDataId'";
					// echo $sql_tmpTrkData; return;
					$rslt_tmpTrkData = $this->bm->dataSelectDB1($sql_tmpTrkData);
					$data['rslt_tmpTrkData']=$rslt_tmpTrkData;
					
					$jetty_sirkar_id = "";
					
					// $sql_jettySirkarId = "SELECT jetty_sirkar_id
					// FROM verify_other_data
					// INNER JOIN shed_tally_info ON shed_tally_info.id=verify_other_data.shed_tally_id
					// WHERE shed_tally_info.import_rotation = '$rotNo' AND shed_tally_info.cont_number = '".$rtnVerifyReport[0]['cont_number']."'";
					
					//Query changed at 4-4-2021 temporary
					// $sql_jettySirkarId = "SELECT jetty_sirkar_id FROM verify_other_data WHERE igm_sup_detail_id='$igmSupDtlId'";
					$sql_jettySirkarId = "SELECT jetty_sirkar_id FROM lcl_dlv_assignment WHERE igm_sup_dtl_id='$igmSupDtlId'";
					$rslt_jettySirkarId = $this->bm->dataSelectDB1($sql_jettySirkarId);
					
					if(count($rslt_jettySirkarId)>0)
						$jetty_sirkar_id = $rslt_jettySirkarId[0]['jetty_sirkar_id'];
					
					$data['jetty_sirkar_id']=$jetty_sirkar_id;
					
					// importer mobile no
					// $sql_importerMobile = "SELECT importer_mobile_no FROM verify_other_data WHERE id='$vrfyOtherDataId'";
					$sql_importerMobile = "SELECT importer_mobile_no FROM lcl_dlv_assignment WHERE id='$vrfyOtherDataId'";
					$rslt_importerMobile = $this->bm->dataSelectDB1($sql_importerMobile);
					$importerMobile = $rslt_importerMobile[0]['importer_mobile_no'];
					$data['importerMobile']=$importerMobile;
					
					// truck slot
					$truckSlot = "";
					
					// $sql_truckSlot = "SELECT truck_slot FROM verify_other_data WHERE id='$vrfyOtherDataId'";
					$sql_truckSlot = "SELECT truck_slot FROM lcl_dlv_assignment WHERE id='$vrfyOtherDataId'";
					$rslt_truckSlot = $this->bm->dataSelectDB1($sql_truckSlot);
					if(count($rslt_truckSlot)>0)
					{
						$truckSlot = $rslt_truckSlot[0]['truck_slot'];
					}
					$data['truckSlot']=$truckSlot;
					
					$sql_dlvDt = "SELECT deliveryDt FROM lcl_dlv_assignment WHERE id='$vrfyOtherDataId'";
					$rslt_dlvDt = $this->bm->dataSelectDB1($sql_dlvDt);
					$blck = "";
					$sltAssignDt = "";
					if(count($rslt_dlvDt)>0)
					{
						//$blck = $rslt_posYardBlock[0]["Block_No"];
						$sltAssignDt = $rslt_dlvDt[0]["deliveryDt"];
					}
					$data['sltAssignDt']=$sltAssignDt;
					
					// Will Work later on this 2021-03-23
					
					$strGetSlotCnt1 = "SELECT COUNT(*) AS rtnValue FROM ctmsmis.tmp_vcms_assignment WHERE assignmentDate='$sltAssignDt' AND Block_No='$blck' AND assignment_slot=1";
					$SlotCnt1 = $this->bm->dataReturn($strGetSlotCnt1);
					$data['SlotCnt1']=$SlotCnt1;
					
					$strGetSlotCnt2 = "SELECT COUNT(*) AS rtnValue FROM ctmsmis.tmp_vcms_assignment WHERE assignmentDate='$sltAssignDt' AND Block_No='$blck' AND assignment_slot=2";
					$SlotCnt2 = $this->bm->dataReturn($strGetSlotCnt2);
					$data['SlotCnt2']=$SlotCnt2;

					$strGetSlotCnt3 = "SELECT COUNT(*) AS rtnValue FROM ctmsmis.tmp_vcms_assignment WHERE assignmentDate='$sltAssignDt' AND Block_No='$blck' AND assignment_slot=3";
					$SlotCnt3 = $this->bm->dataReturn($strGetSlotCnt3);
					$data['SlotCnt3']=$SlotCnt3;
					
					// tab 2 - js info
					$sql_jsInfo = "SELECT vcms_vehicle_agent.id,agent_name AS js_name,agent_type,card_number
					FROM vcms_vehicle_agent
					INNER JOIN vcms_vehicle_agency ON vcms_vehicle_agency.id=vcms_vehicle_agent.agency_id
					WHERE agency_code = '$org_license' AND agent_type = 'Jetty Sircar'";

					$rslt_jsInfo = $this->bm->dataSelectDB1($sql_jsInfo);
					$data['rslt_jsInfo']=$rslt_jsInfo;

					if($this->input->post('payType')=="singlePay" || $this->input->post('payType')=="allPay")
					{
						$payForm = 1;
						$editVal = 0;
						
						if($this->input->post('payType')=="singlePay")
						{						
							$truckDtlId = $this->input->post('truckDtlId');
							$payAmt = $this->input->post('payAmt');
							$payMethod = $this->input->post('payMethod');
							$payFlag = "singlePay";
							
							$data["truckDtlId"] = $truckDtlId;
												
						}
						else if($this->input->post('payType')=="allPay")
						{			
							$payAmt = $this->input->post('totalAmtToPay');
							$vrfyInfoFclId = $this->input->post('vrfyInfoFclId');
							// $payAmt = 57.5;
							$payMethod = "cash";
							$payFlag = "allPay";
							
							$data["vrfyInfoFclId"] = $vrfyInfoFclId;
						}
						$data["payAmt"] = $payAmt;
						$data["Method"] = $payMethod;
						$data["payFlag"] = $payFlag;
						
					}

					
					if($this->input->post('payment'))
					{
						$addVal = 1;
						$payForm = 2;
					}

					if($this->input->post('editId'))
					{
						$editVal = 1;
						
						$editId = $this->input->post('editId');
						$btnType = $this->input->post('btnType');
						$contNo = $this->input->post('contNo');
						$rotNo = $this->input->post('rotNo');
						$cont_status = $this->input->post('cont_status');
						
						$editType = $this->input->post('editBtn');
						$data['editType']=$editType;	
						
						$sql_trkEditInfo = "SELECT id,truck_id,driver_name,driver_gate_pass,truck_agency_name,truck_agency_phone,
						(SELECT mobile_number FROM vcms_vehicle_agent WHERE card_number=driver_gate_pass LIMIT 1) AS driver_mobile_number,
						assistant_name,assistant_gate_pass,
						(SELECT mobile_number FROM vcms_vehicle_agent WHERE card_number=assistant_gate_pass LIMIT 1) AS helper_mobile_number
						FROM do_truck_details_entry
						WHERE id='$editId'";
						$rslt_trkEditInfo = $this->bm->dataSelectDB1($sql_trkEditInfo);
						$data['rslt_trkEditInfo']=$rslt_trkEditInfo;				
									
					}
					if($this->input->post('delId'))
					{
					$editVal = 0;
					
					$editId = $this->input->post('editId');
					$btnType = $this->input->post('btnType');
					$contNo = $this->input->post('contNo');
					$rotNo = $this->input->post('rotNo');
					$cont_status = $this->input->post('cont_status');
					
					$editType = $this->input->post('editBtn');
					$data['editType']=$editType;	
					
					$delId = $this->input->post('delId');	
				/* 	ECHO "DEL";					
					$sql_trkEditInfo = "UPDATE do_truck_details_entry  SET  paid_status=1 WHERE id='$delId'";
					$update = $this->bm->dataUpdateDB1($sql_trkEditInfo);
					echo "11111111111"; */
					$sql_delete = "DELETE  FROM do_truck_details_entry WHERE id='$delId'";
					$del_st = $this->bm->dataDeleteDB1($sql_delete);
								
					}
				}					
			}
			else
			{
				$editVal = 0;
				$addVal = 0;
				$payVal = 0;
				$payForm = 0;
			}
			
			$data['msg']=$msg;
			$data['rotNo']=$rotNo;
			// $data['contNo']=$contNo;
			$data['blNo']=$blNo;
			$data['cont_status']=$cont_status;
			$data['editVal']=$editVal;
			$data['addVal']=$addVal;
			$data['payVal']=$payVal;
			$data['payForm']=$payForm;
			$data['jettyEdit']=$jettyEdit;
			$data['rtnVerifyReport']=$rtnVerifyReport;
			$data['cont_blocked_status']=$cont_blocked_status;
			$data['title']=$title;
				
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('cnfTruckEntryFormLCL',$data);			
			$this->load->view('jsAssets');
		}
	}

	function jettySarkarEntry()
	{
		$msg = "";
		$login_id = $this->session->userdata('login_id');
		$rotNo = $this->input->post('rotNo');
		$contNo = $this->input->post('contNo');
		$blNo = $this->input->post('blNo');
		$cont_status = $this->input->post('cont_status');
		$assignmentType = $this->input->post('assignmentType');
		$vrfyInfoFclId = $this->input->post('vrfyInfoFclId');
		$vrfyOtherDataId = $this->input->post('vrfyOtherDataId');
		$jsName = $this->input->post('jsName');
		$jsId = $this->input->post('jsId');

		if(!$this->input->post('jettyedit'))
		{
			// chk jetty sarkar
			// $sql_chkJS = "SELECT COUNT(*) AS rtnValue
			// FROM verify_info_fcl
			// WHERE jetty_sirkar_id='$jsName' AND id='$vrfyInfoFclId'";
			
			if($cont_status == "LCL")
			{												
				$sql_chkJS = "SELECT COUNT(*) AS rtnValue
				FROM verify_other_data
				WHERE jetty_sirkar_id='$jsId' AND id='$vrfyOtherDataId'";

				$rslt_chkJS = $this->bm->dataSelectDB1($sql_chkJS);
				$chkJS = $rslt_chkJS[0]['rtnValue'];

				if($chkJS == 0)
				{
					$prevJS = "";
					// get previous JS	- check if previous exists
					// $sql_prevJS = "SELECT jetty_sirkar_id
					// FROM verify_other_data
					// WHERE id='$vrfyOtherDataId'";
					$sql_prevJS = "SELECT jetty_sirkar_id
					FROM lcl_dlv_assignment
					WHERE id='$vrfyOtherDataId'";
					$rslt_prevJS = $this->bm->dataSelectDB1($sql_prevJS);
					$prevJS = $rslt_prevJS[0]['jetty_sirkar_id'];
					
					// Insert into log
					// make new for lcl
					// if($prevJS!="" or $prevJS!=null)
					// {
						// $sql_jsLog = "INSERT INTO vcms_jetty_sirkar_log(verify_info_fcl_id,prev_jetty_sirkar_id,replace_by,replace_dt)
						// VALUES('$vrfyInfoFclId','$prevJS','$login_id',NOW())";
						// $this->bm->dataInsertDB1($sql_jsLog);
					// }
					
					// Update JS
					// $sql_updateJS = "UPDATE verify_other_data
					// SET jetty_sirkar_id='$jsId'
					// WHERE id='$vrfyOtherDataId'";
					$sql_updateJS = "UPDATE lcl_dlv_assignment
					SET jetty_sirkar_id='$jsId'
					WHERE id='$vrfyOtherDataId'";
					// return;
					$this->bm->dataUpdateDB1($sql_updateJS);
				}
				
				// $this->cnfTruckEntryLCL($rotNo=null,$contNo=null,$cont_status=null,"","");
				$this->cnfTruckEntryLCL($rotNo,$blNo,$cont_status,"","");
			}
			else
			{			
				$sql_chkJS = "SELECT COUNT(*) AS rtnValue
				FROM verify_info_fcl
				WHERE jetty_sirkar_id='$jsId' AND id='$vrfyInfoFclId'";
				$rslt_chkJS = $this->bm->dataSelectDB1($sql_chkJS);
				$chkJS = $rslt_chkJS[0]['rtnValue'];
				
				if($chkJS == 0)
				{
					$prevJS = "";
					// get previous JS	- check if previous exists
					$sql_prevJS = "SELECT jetty_sirkar_id
					FROM verify_info_fcl
					WHERE id='$vrfyInfoFclId'";
					$rslt_prevJS = $this->bm->dataSelectDB1($sql_prevJS);
					$prevJS = $rslt_prevJS[0]['jetty_sirkar_id'];
					
					// Insert into log
					if($prevJS!="" or $prevJS!=null)
					{
						$sql_jsLog = "INSERT INTO vcms_jetty_sirkar_log(verify_info_fcl_id,prev_jetty_sirkar_id,replace_by,replace_dt)
						VALUES('$vrfyInfoFclId','$prevJS','$login_id',NOW())";
						$this->bm->dataInsertDB1($sql_jsLog);
					}
					
					// Update JS
					$sql_updateJS = "UPDATE verify_info_fcl
					SET jetty_sirkar_id='$jsId'
					WHERE id='$vrfyInfoFclId'";
					$this->bm->dataUpdateDB1($sql_updateJS);
				}
				
				$this->cnfTruckEntryForm($rotNo,$contNo,$cont_status,$assignmentType,$msg);
			}
		}				
	}

	function addTruckToDoDtlLCL()
	{		
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
		else
		{
			$msg = "";
			$login_id = $this->session->userdata('login_id');		
			$ipaddr = $_SERVER['REMOTE_ADDR'];
			
			$regCity = $this->input->post('regCity');
			$regClass = $this->input->post('regClass');
			$truckNo = trim($this->input->post('truckNo'));
			
			$truckId = 	$regCity." ".$regClass." ".$truckNo;
			$blNo = $this->input->post('blNo');
			
			$driverName = $this->input->post('driverName');
			$driverPassNo = $this->input->post('driverPassNo');								
			$assistantName = $this->input->post('assistantName');									
			$assistantPassNo = $this->input->post('assistantPassNo');
			$importerMobileNo = $this->input->post('importerMobileNo');	
			$importerMobileNo = str_replace("-","",$importerMobileNo);	
			$agencyName = $this->input->post('agencyName');	
			$agencyName = str_replace("'"," ",$agencyName);	
			$agencyPhone = $this->input->post('agencyPhone');
			// $res = str_replace( array( '\'', '"',',' , ';', '<', '>' ), ' ', $str); 		
		
			$rotNo = $this->input->post('rotNo');
			$contNo = $this->input->post('contNo');
			$vrfyOtherDataId = $this->input->post('vrfyOtherDataId');
			
			$cont_status = $this->input->post('cont_status');
			$assignmentType = $this->input->post('assignmentType');
			$totTruck = $this->input->post('totTruck');
			$addBtn = $this->input->post('addBtn');
			$frmSlot = $this->input->post('truckSlot');
			
			$emrgncy_flag = 0;
			$emrgncy_approve_stat = 0;
			if($addBtn=="Emergency")
			{
				$emrgncy_flag = 1;	
			}
			
			//$strUpdateSlot = "UPDATE ctmsmis.tmp_vcms_assignment SET assignment_slot='$frmSlot' WHERE cont_no='$contNo' AND rot_no='$rotNo'";
			//$this->bm->dataUpdate$strUpdateSlot);
			//return;
		
			//$sql_timeSlot = "SELECT assignment_slot,assignmentDate,DATE_ADD(assignmentDate, INTERVAL 1 DAY) AS nxtDt
			//FROM ctmsmis.tmp_vcms_assignment
			//WHERE rot_no='$rotNo' AND cont_no='$contNo' AND assignmentDate>=DATE(NOW())";
			//$rslt_timeSlot = $this->bm->dataSelect($sql_timeSlot);
			
			// $sql_igmContId = "SELECT igm_detail_container.id FROM igm_detail_container
			// INNER JOIN igm_details ON igm_details.id =  igm_detail_container.igm_detail_id
			// WHERE cont_number = '$contNo' AND Import_Rotation_No='$rotNo'";
			// $rslt_igmContId = $this->bm->dataSelectDB1($sql_igmContId);
			
			// $igmId = "";
			
			// if(count($rslt_igmContId)>0){
			// 	$igmId = $rslt_igmContId[0]['id'];
			// }
			
			//echo $igmId;
			
			// $sql_timeSlot = "SELECT assignment_date,DATE_ADD(assignment_date, INTERVAL 1 DAY) AS nxtDt FROM lcl_assignment_detail 
			// WHERE igm_cont_detail_id = '$igmId' ORDER BY id DESC LIMIT 1";
			
			$sql_timeSlot = "SELECT deliveryDt,DATE_ADD(deliveryDt, INTERVAL 1 DAY) AS nxtDt FROM lcl_dlv_assignment WHERE id = '$vrfyOtherDataId' ORDER BY id DESC LIMIT 1";

			$rslt_timeSlot = $this->bm->dataSelectDB1($sql_timeSlot);
		
		
			$asDt = "";
			$asSlot = "";
			$nxtDt = "";
			
			for($j=0;$j<count($rslt_timeSlot);$j++)
			{
				$asDt = $rslt_timeSlot[$j]['deliveryDt'];
				$nxtDt = $rslt_timeSlot[$j]['nxtDt'];
			}
			
			$asSlot = $frmSlot;
			
			//$asDt = date("Y-m-d");
			//$asSlot = $frmSlot;
			//$nxtDt = date('Y-m-d', strtotime('+1 day', strtotime($asDt)));
		
			$sSlot = "";
			$eSlot = "";
			if($asSlot==1)
			{
				$sSlot = $asDt." 08:00:00";
				$eSlot = $asDt." 15:59:59";
			}
			else if($asSlot==2)
			{
				$sSlot = $asDt." 16:00:00";
				$eSlot = $asDt." 23:59:59";
			}
			else
			{
				$sSlot = $nxtDt." 00:00:00";
				$eSlot = $nxtDt." 07:59:59";
			}
			$payAmt = 57.5;			
															
			if($this->input->post('editFormId'))
			{
				$editFormId = $this->input->post('editFormId');
				$editType = $this->input->post('editType');
				
				if($editType == "Replace")
				{
					$sql_replaceInfo = "SELECT id,truck_id,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,paid_amt,paid_method,paid_collect_dt,gate_in_status,gate_in_by,gate_in_time
					FROM do_truck_details_entry
					WHERE id='$editFormId'";
					$rslt_replaceInfo = $this->bm->dataSelectDB1($sql_replaceInfo);
					
					$repVisitId = $rslt_replaceInfo[0]['id'];
					$repTruckId = $rslt_replaceInfo[0]['truck_id'];
					$repDriverName = $rslt_replaceInfo[0]['driver_name'];
					$repDriverGatePass = $rslt_replaceInfo[0]['driver_gate_pass'];
					$repAssistantName = $rslt_replaceInfo[0]['assistant_name'];
					$repAssistantGatePass = $rslt_replaceInfo[0]['assistant_gate_pass'];
					$repPaidAmt = $rslt_replaceInfo[0]['paid_amt'];
					$repPaidMethod = $rslt_replaceInfo[0]['paid_method'];
					$repPaidCollectDt = $rslt_replaceInfo[0]['paid_collect_dt'];
					
					$gate_in_status = $rslt_replaceInfo[0]['gate_in_status'];
					$gate_in_by = $rslt_replaceInfo[0]['gate_in_by'];
					$gate_in_time = $rslt_replaceInfo[0]['gate_in_time'];
					
					$sql_insertReplace = "INSERT INTO vcms_replace_truck_log(visit_id,truck_id,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,paid_amt,paid_method,paid_collect_dt,replace_time,replace_by,gate_in_status,gate_in_time,gate_in_by)
					VALUES('$repVisitId','$repTruckId','$repDriverName','$repDriverGatePass','$repAssistantName','$repAssistantGatePass','$repPaidAmt','$repPaidMethod','$repPaidCollectDt',NOW(),'$login_id','$gate_in_status','$gate_in_by','$gate_in_time')";
					$this->bm->dataInsertDB1($sql_insertReplace);
					
					$sql_updateTruckInfo = "UPDATE do_truck_details_entry
					SET truck_id='$truckId',driver_name='$driverName',driver_gate_pass='$driverPassNo',assistant_name='$assistantName',assistant_gate_pass='$assistantPassNo',truck_agency_name='$agencyName',truck_agency_phone='$agencyPhone',paid_amt='',paid_status=0,paid_method='',gate_in_status='0',gate_in_by=NULL,gate_in_time=NULL
					WHERE id='$editFormId'";
					$this->bm->dataUpdateDB1($sql_updateTruckInfo);
				}
				// else if($editType == "Edit")			// check with it later
				else
				{
					$sql_updateTruckInfo = "UPDATE do_truck_details_entry
					SET truck_id='$truckId',driver_name='$driverName',driver_gate_pass='$driverPassNo',assistant_name='$assistantName',assistant_gate_pass='$assistantPassNo',truck_agency_name='$agencyName',truck_agency_phone='$agencyPhone'
					WHERE id='$editFormId'";
					$this->bm->dataUpdateDB1($sql_updateTruckInfo);	
				}
				
			}			
			else							
			{
				$sql_chkTruck = "SELECT COUNT(*) AS rtnValue
				FROM do_truck_details_entry 
				WHERE truck_id='$truckId' AND visit_time_slot_start='$sSlot' AND visit_time_slot_end='$eSlot'";
				$rslt_chkTruck = $this->bm->dataSelectDB1($sql_chkTruck);
				$chkTruck = $rslt_chkTruck[0]['rtnValue'];
				
				if($chkTruck==0)
				{
					$strInsertEq = "INSERT INTO do_truck_details_entry(verify_other_data_id,import_rotation,cont_no,truck_id,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,truck_agency_name,truck_agency_phone,update_by,ip_addr,last_update,emrgncy_flag,emrgncy_approve_stat,visit_time_slot_start,visit_time_slot_end)
					VALUES('$vrfyOtherDataId','$rotNo','$contNo','$truckId','$driverName','$driverPassNo','$assistantName','$assistantPassNo','$agencyName','$agencyPhone','$login_id','$ipaddr',NOW(),'$emrgncy_flag','$emrgncy_approve_stat','$sSlot','$eSlot')";								
					
					$stat = $this->bm->dataInsertDB1($strInsertEq);
					
					if($stat == 1)
						$msg = "<font color='green'><b>Truck added successfully</b></font>";
					
				}
				else
				{
					$msg = "<font color='red'><b>This truck was assigned for this time slot previously</b></font>";
				}
			}	
								
			
			// $sql_updateImporterMbl = "UPDATE verify_other_data
			// SET importer_mobile_no='$importerMobileNo' , truck_slot = '$asSlot'
			// WHERE id='$vrfyOtherDataId'";
			$sql_updateImporterMbl = "UPDATE lcl_dlv_assignment
			SET importer_mobile_no='$importerMobileNo' , truck_slot = '$asSlot'
			WHERE id='$vrfyOtherDataId'";
			$this->bm->dataUpdateDB1($sql_updateImporterMbl);		
			
			$this->cnfTruckEntryLCL($rotNo,$blNo,$cont_status,"","");
		}
	}

	//Truck Entry LCL  -- End

	//Replace Truck Gate Out Process  --Start

	function replaceTruckGateOutForm()
	{
		$user = $this->session->userdata('login_id');
		$user=str_replace(" ","",$user);
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$data['msg'] = "";
			$data['title'] = "Replaced Truck Gate Out Process";
			$data['flag'] = 0;

			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('replaceTruckGateOutForm',$data);			
			$this->load->view('jsAssets');
		}
	}

	function replaceTruckGateOut()
	{
		$user = $this->session->userdata('login_id');
		$user=str_replace(" ","",$user);
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$truckVisitId = $this->input->post('truckVisitId');

			$query = "SELECT * FROM vcms_replace_truck_log WHERE visit_id='$truckVisitId'";
			$rslt_truck = $this->bm->dataSelectDB1($query);
			$data['truck'] = $rslt_truck;
			
			if(count($rslt_truck)>0){
				$query_gateIn = "SELECT gate_in_status FROM vcms_replace_truck_log WHERE visit_id='$truckVisitId'";
				$rslt_gateIn = $this->bm->dataSelectDB1($query_gateIn);
				$data['gateInSts'] = $rslt_gateIn; 
			}

			$data['truckVisitId'] = $truckVisitId;
			$data['msg'] = "";
			$data['title'] = "Replaced Truck Gate Out Process";
			$data['flag'] = 1;

			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('replaceTruckGateOutForm',$data);			
			$this->load->view('jsAssets');
		}
	}

	function replaceTruckGateOutsts()
	{
		$user = $this->session->userdata('login_id');
		$user=str_replace(" ","",$user);
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$truckVisitId = $this->input->post('truckVisitId');
			$login_id = $this->session->userdata('login_id');

			$query_gate_Out = "UPDATE vcms_replace_truck_log SET gate_out_status = 1 , gate_out_time = NOW() , gate_out_by = '$login_id'";
			$sts = $this->bm->dataUpdateDB1($query_gate_Out);

			$query = "SELECT * FROM vcms_replace_truck_log WHERE visit_id='$truckVisitId'";
			$rslt_truck = $this->bm->dataSelectDB1($query);
			$data['truck'] = $rslt_truck;
			
			if(count($rslt_truck)>0){
				$query_gateIn = "SELECT gate_in_status FROM vcms_replace_truck_log WHERE visit_id='$truckVisitId'";
				$rslt_gateIn = $this->bm->dataSelectDB1($query_gateIn);
				$data['gateInSts'] = $rslt_gateIn; 
			}

			$data['truckVisitId'] = $truckVisitId;
			$data['msg'] = "";
			$data['title'] = "Replaced Truck Gate Out Process";
			$data['flag'] = 1;

			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('replaceTruckGateOutForm',$data);			
			$this->load->view('jsAssets');
		}
	}

	//Replace Truck Gate Out Process  -End
	
	// truck entry by ds - start
	function truckEntryByDSForm()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$rslt_assignmentList=null;
			if($this->input->post('searchByCnfId'))
			{
				$cnfAinNo = $this->input->post('cnfAinNo');
				
				$sql_cnfLicNo = "SELECT * FROM organization_profiles WHERE AIN_No='$cnfAinNo'";
				$rslt_cnfLicNo = $this->bm->dataSelectDB1($sql_cnfLicNo);
				
				if(count($rslt_cnfLicNo)>0)
				{
					$cfLicNo = $rslt_cnfLicNo[0]['License_No'];
					
					$cond = " WHERE cf_lic='$cfLicNo' AND cf_lic!='' AND assignmentDate BETWEEN DATE(NOW()) AND DATE_ADD(DATE(NOW()), INTERVAL 1 DAY)";
				
					$sql_assignmentList = "SELECT DISTINCT cont_no,rot_no,mlo,cf_name AS cf,cont_status,size,ROUND(height,1) AS height,slot AS carrentPosition,Yard_No,Block_No,mfdch_value,assignmentDate,custom_remarks,unit_gkey 
					FROM ctmsmis.tmp_vcms_assignment ".$cond." ORDER BY assignmentDate DESC LIMIT 1000";
					// echo $sql_assignmentList;return;
					$rslt_assignmentList=$this->bm->dataSelect($sql_assignmentList);					
				}
				else
				{
					echo "Invalid is not found License No";
				}													
			}
			// return;
			$title = "Truck Entry By CTMS";
			
			$data['title']=$title;
			$data['rslt_assignmentList']=$rslt_assignmentList;
			
			$this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('truckEntryByDSForm',$data);
			$this->load->view('jsAssetsList');
		}
	}
	// truck entry by ds - end
	

}
?>