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
	
/* 	function logout()
	{ 
		$data['body']="<font color='blue' size=2>LogOut Successfully....</font>";
		$this->session->sess_destroy();
		$this->cache->clean();
		//redirect(base_url(),$data);
		$this->load->view('header');
		$this->load->view('welcomeview_1', $data);
		$this->load->view('footer');
		$this->db->cache_delete_all();
	} */
		function logout()
		{		
			$query="SELECT sparcsn4.vsl_vessel_visit_details.vvd_gkey,sparcsn4.vsl_vessels.name,sparcsn4.vsl_vessel_visit_details.ib_vyg,sparcsn4.vsl_vessel_visit_details.ob_vyg,
			LEFT(sparcsn4.argo_carrier_visit.phase,2) AS phase_num,SUBSTR(sparcsn4.argo_carrier_visit.phase,3) AS phase_str,sparcsn4.argo_visit_details.eta,
			sparcsn4.argo_visit_details.etd,sparcsn4.argo_carrier_visit.ata,
			sparcsn4.argo_carrier_visit.atd,sparcsn4.ref_bizunit_scoped.id AS agent,sparcsn4.argo_quay.id AS berth,
			IFNULL(sparcsn4.vsl_vessel_visit_details.flex_string02,sparcsn4.vsl_vessel_visit_details.flex_string03) AS berthop
			FROM sparcsn4.argo_carrier_visit
			INNER JOIN sparcsn4.argo_visit_details ON sparcsn4.argo_visit_details.gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
			INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_visit_details.gkey
			INNER JOIN sparcsn4.vsl_vessels ON sparcsn4.vsl_vessels.gkey=sparcsn4.vsl_vessel_visit_details.vessel_gkey
			INNER JOIN sparcsn4.ref_bizunit_scoped ON sparcsn4.ref_bizunit_scoped.gkey=sparcsn4.vsl_vessel_visit_details.bizu_gkey
			LEFT JOIN sparcsn4.vsl_vessel_berthings ON sparcsn4.vsl_vessel_berthings.vvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
			LEFT JOIN sparcsn4.argo_quay ON sparcsn4.argo_quay.gkey=sparcsn4.vsl_vessel_berthings.quay
			WHERE sparcsn4.argo_carrier_visit.phase IN ('20INBOUND','30ARRIVED','40WORKING','50COMPLETE','60DEPARTED')
			ORDER BY sparcsn4.argo_carrier_visit.phase";
			//echo $data['voysNo'];
			$rtnVesselList = $this->bm->dataSelect($query);
			$data['rtnVesselList']=$rtnVesselList;
			
			$data['body']="<font color='blue' size=2>LogOut Successfully....</font>";

			$this->session->sess_destroy();
			$this->cache->clean();
			//redirect(base_url(),$data);
			// $this->load->view('header');
			// $this->load->view('welcomeview_1', $data);
			// $this->load->view('footer');

			$this->load->view('cssVesselList');
			$this->load->view('jsVesselList');
			$this->load->view('FrontEnd/header');
			$this->load->view('FrontEnd/slider');
			$this->load->view('FrontEnd/index',$data);
			$this->load->view('FrontEnd/footer');

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
	
	/*function bilSearchByVerifyNumber_2($rotNo=null,$contNo=null,$fclFlagValue=null,$cont_status=null)
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
					FROM ctmsmis.tmp_oracle_assignment 
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
	}*/
	
	
	
	
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
					FROM ctmsmis.tmp_oracle_assignment 
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
				
				
				$rslt_posYardBlock = $this->bm->dataSelectDb2($sql_posYardBlock);
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
			FROM ctmsmis.tmp_oracle_assignment
			WHERE cf_lic='$org_license' AND cf_sms_number IS NOT NULL";
		
			$rslt_smsNo = $this->bm->dataSelectDb2($sql_smsNo);
			$smsNo = @$rslt_smsNo[0]['cf_sms_number'];
			
			//checking part BL

			$partBLQuery = "SELECT COUNT(*) AS rtnValue FROM igm_sup_detail_container 
			INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=igm_sup_detail_container.igm_sup_detail_id
			WHERE cont_number='$contNo' AND Import_Rotation_No='$rotNo' AND cont_status='FCL/PART'";
			$rslt_partBL = $this->bm->dataSelectDb1($partBLQuery);
			$partbl = 0;
			for($i=0;$i<count($rslt_partBL);$i++){
				$partbl = $rslt_partBL[$i]['rtnValue'];
			}

			if($partbl == 0){
				$partBLQuery = "SELECT COUNT(*) AS rtnValue
				FROM igm_detail_container
				INNER JOIN igm_details ON igm_detail_container.igm_detail_id=igm_details.id
				WHERE igm_details.Import_Rotation_No='$rotNo' AND igm_detail_container.cont_number='$contNo' AND cont_status='FCL/PART'";
				$rslt_partBL = $this->bm->dataSelectDb1($partBLQuery);
				$partbl = 0;
				for($i=0;$i<count($rslt_partBL);$i++){
					$partbl = $rslt_partBL[$i]['rtnValue'];
				}
			}

			$partblsts = 0;
			
			if($partbl>0){
				$partblsts = 1;
			}
			
			if($cnt==0)
			{			
				$sql_insertQtyTruck = "INSERT INTO verify_info_fcl(igm_detail_id,igm_detail_cont_id,assignment_type,cnf_lic_no,cnf_mobile_no,unit_gkey,rotation,cont_number,no_of_truck,is_part_bl,truck_no_by,truck_no_time)
				VALUES('$igmDtlId','$igmDtlContId','$assignmentType','$org_license','$smsNo','$unit_gkey','$rotNo','$contNo','$truck_qty','$partblsts','$login_id',NOW())";
				
				if($this->bm->dataInsertDB1($sql_insertQtyTruck))
					$msg = "<font color='green'><b>Truck number entered succesfully.</b></font>";
			}
			else
			{
				$sql_updateQtyTruck = "UPDATE verify_info_fcl
				SET cnf_mobile_no='$smsNo',unit_gkey='$unit_gkey',no_of_truck='$truck_qty',is_part_bl='$partblsts',truck_no_by='$login_id',truck_no_time=NOW()
				WHERE rotation='$rotNo' AND cont_number='$contNo'";
				
				if($this->bm->dataUpdateDB1($sql_updateQtyTruck))
					$msg = "<font color='green'><b>Truck number updated succesfully.</b></font>";
			}
		}
	}
	

	/*function addVryInfoFCL($rotNo,$contNo,$unit_gkey,$assignmentType)
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
			FROM ctmsmis.tmp_oracle_assignment
			WHERE cf_lic='$org_license' AND cf_sms_number IS NOT NULL";
			$rslt_smsNo = $this->bm->dataSelect($sql_smsNo);
			$smsNo = @$rslt_smsNo[0]['cf_sms_number'];
			
			//checking part BL

			$partBLQuery = "SELECT COUNT(*) AS rtnValue FROM igm_sup_detail_container 
			INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=igm_sup_detail_container.igm_sup_detail_id
			WHERE cont_number='$contNo' AND Import_Rotation_No='$rotNo' AND cont_status='FCL/PART'";
			$rslt_partBL = $this->bm->dataSelectDb1($partBLQuery);
			$partbl = 0;
			for($i=0;$i<count($rslt_partBL);$i++){
				$partbl = $rslt_partBL[$i]['rtnValue'];
			}

			if($partbl == 0){
				$partBLQuery = "SELECT COUNT(*) AS rtnValue
				FROM igm_detail_container
				INNER JOIN igm_details ON igm_detail_container.igm_detail_id=igm_details.id
				WHERE igm_details.Import_Rotation_No='$rotNo' AND igm_detail_container.cont_number='$contNo' AND cont_status='FCL/PART'";
				$rslt_partBL = $this->bm->dataSelectDb1($partBLQuery);
				$partbl = 0;
				for($i=0;$i<count($rslt_partBL);$i++){
					$partbl = $rslt_partBL[$i]['rtnValue'];
				}
			}

			$partblsts = 0;
			
			if($partbl>0){
				$partblsts = 1;
			}
			
			if($cnt==0)
			{			
				$sql_insertQtyTruck = "INSERT INTO verify_info_fcl(igm_detail_id,igm_detail_cont_id,assignment_type,cnf_lic_no,cnf_mobile_no,unit_gkey,rotation,cont_number,no_of_truck,is_part_bl,truck_no_by,truck_no_time)
				VALUES('$igmDtlId','$igmDtlContId','$assignmentType','$org_license','$smsNo','$unit_gkey','$rotNo','$contNo','$truck_qty','$partblsts','$login_id',NOW())";
				
				if($this->bm->dataInsertDB1($sql_insertQtyTruck))
					$msg = "<font color='green'><b>Truck number entered succesfully.</b></font>";
			}
			else
			{
				$sql_updateQtyTruck = "UPDATE verify_info_fcl
				SET cnf_mobile_no='$smsNo',unit_gkey='$unit_gkey',no_of_truck='$truck_qty',is_part_bl='$partblsts',truck_no_by='$login_id',truck_no_time=NOW()
				WHERE rotation='$rotNo' AND cont_number='$contNo'";
				
				if($this->bm->dataUpdateDB1($sql_updateQtyTruck))
					$msg = "<font color='green'><b>Truck number updated succesfully.</b></font>";
			}
		}
	}*/
	
	
	
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
						FROM ctmsmis.tmp_oracle_assignment
						WHERE rot_no='$rotation' AND cont_no='$cont_number' AND assignmentDate>=DATE(NOW())";
						//change 1/28/2023
						
						$rslt_timeSlot = $this->bm->dataSelectDb2($sql_timeSlot);
						
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
						
						$strInsertEq = "INSERT INTO do_truck_details_entry(verify_info_fcl_id,verify_number,import_rotation,cont_no,truck_id,delv_pack,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,update_by,ip_addr,last_update,emrgncy_flag,emrgncy_approve_stat,visit_time_slot_start,visit_time_slot_end,entry_from)
						VALUES('$vrfyInfoFclId','$verifyNo','$rotNo','$contNo','$trucId','$packQty','$gateNo','$driverName','$driverPass','$assistantName','$assistantPass','$login_id','$ipaddr',NOW(),'$emrgncy_flag','$emrgncy_approve_stat','$sSlot','$eSlot','dlv2')";								
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
	
	
	/*function deliver_2()
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
						FROM ctmsmis.tmp_oracle_assignment
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
						
						$strInsertEq = "INSERT INTO do_truck_details_entry(verify_info_fcl_id,verify_number,import_rotation,cont_no,truck_id,delv_pack,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,update_by,ip_addr,last_update,emrgncy_flag,emrgncy_approve_stat,visit_time_slot_start,visit_time_slot_end,entry_from)
						VALUES('$vrfyInfoFclId','$verifyNo','$rotNo','$contNo','$trucId','$packQty','$gateNo','$driverName','$driverPass','$assistantName','$assistantPass','$login_id','$ipaddr',NOW(),'$emrgncy_flag','$emrgncy_approve_stat','$sSlot','$eSlot','dlv2')";								
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
	}*/
	
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
		
		// $sqlBerth="SELECT NVL(flex_string02,flex_string03) AS berthOp,DATE(sparcsn4.argo_carrier_visit.ata) AS ata
		// FROM sparcsn4.vsl_vessel_visit_details 
		// INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.cvcvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
		// WHERE ib_vyg='$rotation'";
		
		$sqlBerth="       
		SELECT NVL(flex_string02,flex_string03) AS berthOp,to_char(argo_carrier_visit.ata) AS ata
		FROM vsl_vessel_visit_details 
		INNER JOIN argo_carrier_visit ON argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
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
		
		/*$sqlBerth="SELECT IFNULL(flex_string03,flex_string02) AS berthOp,DATE(sparcsn4.argo_carrier_visit.ata) AS ata
		FROM sparcsn4.vsl_vessel_visit_details 
		INNER JOIN sparcsn4.argo_carrier_visit ON sparcsn4.argo_carrier_visit.cvcvd_gkey=sparcsn4.vsl_vessel_visit_details.vvd_gkey
		WHERE ib_vyg='$rotation'";
		//echo $sqlBerth;
		$rsltBerth=$this->bm->dataSelect($sqlBerth);*/
		
		$sqlBerth="SELECT NVL(flex_string03,flex_string02) AS berthOp,to_char(argo_carrier_visit.ata,'YYYY-MM-DD')as ata
		FROM vsl_vessel_visit_details 
		INNER JOIN argo_carrier_visit ON argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey 
		WHERE ib_vyg='$rotation'
		";
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
	
	
	function billGenerationForm($msg = null)
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

			if(isset($msg))
			{
				$data['msg']=$msg;
			}
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('billGenerationForm',$data);
			$this->load->view('jsAssets');
		}
	}
	
	// function billGenerationFormToDB()
	// {
	// 	$ipaddr = $_SERVER['REMOTE_ADDR'];
	// 	$login_id = $this->session->userdata('login_id');
		
	// 	$verify_number = $this->input->post('verify_num');
	// 	$bill_no = $this->input->post('bill_no');
	// 	$da_bill_no = $this->input->post('da_bill_no');
	// 	$bill_date = $this->input->post('bill_date');
	// 	$rotation_no = $this->input->post('rotation_no');
	// 	$arr_dt = $this->input->post('arr_dt');
	// 	$comm_dt = $this->input->post('comm_dt');
	// 	$wr_dt = $this->input->post('wr_dt');
	// 	$ado_no = $this->input->post('ado_no');
	// 	$ado_dt = $this->input->post('ado_dt');
	// 	$ado_upto = $this->input->post('ado_upto');
	// 	$one_stop_point = $this->input->post('one_stop_point');
	// 	$ex_rate = $this->input->post('ex_rate');
	// 	$bill_for = $this->input->post('bill_for');
	// 	$unstfDt = $this->input->post('unstfDt');
	// 	$wr_upto_dt = $this->input->post('wr_upto_dt');
	// 	$be_no = $this->input->post('be_no');
	// 	$be_dt = $this->input->post('be_dt');
	// 	$less = $this->input->post('less');
	// 	$part_bl = $this->input->post('part_bl');
		
	// 	$cont_qty = $this->input->post('cont_qty');
	// 	$cont_wht = $this->input->post('cont_wht');
	// 	$cnfCode = $this->input->post('cnfCode');
	// 	$cnfName = $this->input->post('cnfName');
	// 	$impo_reg_no = $this->input->post('impo_reg_no');
	// 	$impo_reg_name = $this->input->post('impo_reg_name');
		
	// 	$total_port = $this->input->post('total_port');
	// 	$total_vat = $this->input->post('total_vat');
	// 	$total_mlwf = $this->input->post('total_mlwf');
	// 	$less_amt_port = $this->input->post('less_amt_port');
	// 	$less_amt_vat = $this->input->post('less_amt_vat');
	// 	$grand_total = $this->input->post('grand_total');
					
	// 	$remarks = $this->input->post('remarks');
	// 	$extra_movement=$this->input->post('ext_mov_twnty');
	// 	if($extra_movement=="")
	// 	{
	// 		$extra_movement=$this->input->post('ext_mov_forty');
	// 	}
	// 	$vessel_name = $this->input->post('vessel_name');
	// 	$bl_no = $this->input->post('bl_no');
	// 	$container_size = $this->input->post('container_size');
	// 	$container_height = $this->input->post('container_height');
		
	// 	$chkBillExistQuery="select count(bill_no) as chkRslt from shed_bill_master where verify_no='$verify_number'";
	// 	$getBillRslt = $this->bm->dataSelectDb1($chkBillExistQuery);
	// 	$billExist=$getBillRslt[0]['chkRslt'];
	
	// 	if($billExist > 0)
	// 	{
	// 		echo "<font color='red'><b>Bill Already Generated.</b></font>";			
	// 	}
	// 	else
	// 	{
	// 		$billGenNoQuery="select IFNULL(MAX(bill_generation_no),0)+1 as bill_generation_no 
	// 		from shed_bill_master 
	// 		where right(YEAR(now()),2)=".date('y');
	// 		$billGenNo = $this->bm->dataSelectDb1($billGenNoQuery);
	// 		$getBillGenNo = $billGenNo[0]['bill_generation_no'];
	// 		// $shed_bill_details;
			
	// 		$shedMasterInsertQry="insert into shed_bill_master (verify_no,unit_no,cpa_vat_reg_no,ex_rate,bill_date,
	// 		arraival_date,import_rotation,vessel_name,
	// 		cl_date,bl_no,wr_date,wr_upto_date,importer_vat_reg_no,importer_name,cnf_lic_no,cnf_agent,
	// 		be_no,be_date,ado_no,ado_date,ado_valid_upto,
	// 		manifest_qty,cont_size,cont_height,cont_weight,da_bill_no,bill_for,less,part_bl,remarks,extra_movement,total_port,
	// 		total_vat,total_mlwf,less_amt_port,less_amt_vat,grand_total,user,ip_address,entry_dt,bill_generation_no) 
	// 		values 
	// 		('$verify_number','$one_stop_point','2041001546','$ex_rate','$bill_date','$arr_dt','$rotation_no',
	// 		'$vessel_name','$comm_dt','$bl_no','$wr_dt',
	// 		'$wr_upto_dt','$impo_reg_no','$impo_reg_name','$cnfCode','$cnfName',
	// 		'$be_no','$be_dt','$ado_no','$ado_dt','$ado_upto','$cont_qty','$container_size','$container_height','$cont_wht','$da_bill_no',
	// 		'$bill_for','$less','$part_bl','$remarks','$extra_movement','$total_port','$total_vat','$total_mlwf','$less_amt_port','$less_amt_vat',
	// 		'$grand_total','$login_id','$ipaddr',now(),$getBillGenNo)";								 
									
	// 		$shedMasterInsert=$this->bm->dataInsertDB1($shedMasterInsertQry);
	// 		$chkBillNoQuery="select bill_no 
	// 		from shed_bill_master 
	// 		where verify_no='$verify_number' order by bill_no desc limit 1";
	// 		$getBillNo = $this->bm->dataSelectDb1($chkBillNoQuery);
	// 		$bill=$getBillNo[0]['bill_no'];
				
	// 		if($shedMasterInsert==1)
	// 		{
	// 			for($i=0; $i<=$this->input->post('tbl_total_row');$i++)
	// 			{
	// 				if($this->input->post('tarrif_id'.$i)!="")
	// 				{
	// 					$tarrif_id= $this->input->post('tarrif_id'.$i);
	// 					$billHead= $this->input->post('billHead'.$i);
	// 					$gl_code= $this->input->post('option'.$i);
	// 					$rate= $this->input->post('rate'.$i);
	// 					$qty= $this->input->post('qty'.$i);
	// 					$qday= $this->input->post('qday'.$i);
	// 					$weight= $this->input->post('weight'.$i);
	// 					$mlwf= $this->input->post('mlwf'.$i);
	// 					$vatTK= $this->input->post('vatTK'.$i);
	// 					$amt= $this->input->post('amt'.$i);
						
	// 					$strInsertEq = "insert into shed_bill_details (verify_no,bill_no,gl_code,description,tarrif_rate,Qty,qday,amt,vatTK,mlwfTK) 
	// 					values('$verify_number','$bill','$gl_code','$billHead','$rate','$qty','$qday','$amt','$vatTK','$mlwf')";  	  
	// 					$stat = $this->bm->dataInsertDB1($strInsertEq);
	// 				}
	// 			}
				
	// 			$tarrif_id_val= $this->input->post('tarrif_id_val');
	// 			$billHeadVal= $this->input->post('billHeadVal');
	// 			$glcodeVal= $this->input->post('optionVal');
	// 			$rateVal= $this->input->post('rateVal');
	// 			$qtyVal= $this->input->post('qtyVal');
	// 			$qdayVal= $this->input->post('qdayVal');
	// 			$weightVal= $this->input->post('weightVal');
	// 			$mlwf_val= $this->input->post('mlwf_val');
	// 			$vatTKVal= $this->input->post('vatTKVal');
	// 			$amtVal= $this->input->post('amtVal');
	// 			if($glcodeVal!="")
	// 			{
	// 				$strInsertUsedEq = "insert into shed_bill_details (verify_no,bill_no,gl_code,description,tarrif_rate,Qty,qday,amt,
	// 										vatTK,mlwfTK) 
	// 				values('$verify_number','$bill','$glcodeVal','$billHeadVal','$rateVal','$qtyVal','$qdayVal','$amtVal',
	// 						'$vatTKVal','$mlwf_val')";  	  
	// 				$resInsertUsedEq = $this->bm->dataInsertDB1($strInsertUsedEq);
	// 			}
				
	// 			$rtnBillQuery="select concat(right(YEAR(bill_date),2),'/',
	// 						concat(if(length(bill_generation_no)=1,'00000',if(length(bill_generation_no)=2,'0000',if(length(bill_generation_no)=3,'000',
	// 						if(length(bill_generation_no)=4,'00',if(length(bill_generation_no)=5,'0',''))))),bill_generation_no)) as bill_no 
	// 						from shed_bill_master 
	// 						where verify_no='$verify_number'";
	// 			$rtnBillNo = $this->bm->dataSelectDb1($rtnBillQuery);
	// 			$billNo=$rtnBillNo[0]['bill_no'];
			
	// 			echo "<font color='green'><b>Bill Generated. Bill No: ".$billNo."</b></font> <a href='".site_url('ShedBillController/getShedBillPdf/'.$verify_number)."' target='_blank'>View Bill</a>";					 					 					 
	// 		}
	// 		else
	// 		{
	// 			echo "<font color='red'><b>Bill Not Created</b></font>";
	// 		}								
	// 	}
	// }

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
		$msg = null;

		if($billExist > 0)
		{
			$msg = "<font color='red'><b>Bill Already Generated.</b></font>";			
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
			
				$msg = "<font color='green'><b>Bill Generated. Bill No: ".$billNo."</b></font> <a href='".site_url('ShedBillController/getShedBillPdf/'.$verify_number)."' target='_blank'>View Bill</a>";
			}
			else
			{
				$msg = "<font color='red'><b>Bill Not Created</b></font>";
			}								
		}

		$this->billGenerationForm($msg);
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
			$rot = null;

			if($this->input->post('ddl_imp_rot_no')){
				$rot = $this->input->post('ddl_imp_rot_no');
			}
			
			$data['rot']=$rot;
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
					$sql_insert="insert into assigned_unit(rotation,unit_no,created_at) values('$rotation','$unit',NOW())";
					$rslt_insert=$this->bm->dataInsertDB1($sql_insert);
					$msg="<font color='green'><b>Successfully inserted</b></font>";
				}
				else
				{
					$sql_update="update assigned_unit set unit_no='$unit',updated_at = NOW() where rotation='$rotation'";
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
	
	function printGatePassForMobileApp(){
		
		//echo $this->uri->segment(4);
		 $trucVisitId=$this->uri->segment(4);
		
	//	$this->data['title']="Truck Entrance Application";
		$data['trucVisitId'] = $trucVisitId;
		
		$this->load->library('m_pdf');
		
		
		$html=$this->load->view('vehiclaGatePassForApp',$data, true); //load the pdf_output.php by passing our data and get all data in $html varriable.
		 
		$pdfFilePath ="GatePass_For_".$trucVisitId.".pdf";

		$pdf = $this->m_pdf->load();
		// $pdf->allow_charset_conversion = true;
		// $pdf->charset_in = 'iso-8859-4';
		$stylesheet = file_get_contents('assets/stylesheets/test.css'); // external css

			
		$pdf->WriteHTML($stylesheet,1);
		$pdf->WriteHTML($html,2);
			 
		$pdf->Output($pdfFilePath, "I"); // For Show Pdf
			
		
	}

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
				FROM ctmsmis.tmp_oracle_assignment
				WHERE cf_lic='$org_license'";
			}
			else
			{
				$sql_assignmentList = "SELECT cont_no,rot_no,mlo,cf_name AS cf,cont_status,size,ROUND(height,1) AS height,Yard_no AS carrentPosition,Yard_No,mfdch_value,assignmentDate,custom_remarks
				FROM ctmsmis.tmp_oracle_assignment
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
			
			$cont_status = "";

			// $rslt_status = $this->chkBlockedContainer($cont);
			$rslt_status = $this->bm->chkBlockedContainer($cont,$visit_id);
			//var_dump($rslt_status);

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

			$sql_cont = "SELECT cont_no FROM do_truck_details_entry WHERE id = '$visit_id'";
			$rslt_cont = $this->bm->dataSelectDB1($sql_cont);
			$cont = "";
			for($i=0;$i<count($rslt_cont);$i++){
				$cont = $rslt_cont[$i]['cont_no'];
			}

			$cont_status = "";

			// $rslt_status = $this->chkBlockedContainer($cont);
			$rslt_status = $this->bm->chkBlockedContainer($cont,$visit_id);
			//var_dump($rslt_status);

			for($i = 0;$i<count($rslt_status);$i++){
				$cont_status = $rslt_status[$i]['custom_block_st'];
			}

			$data['cont_status'] = $cont_status;
			
			$chk_st = $this->input->post('chk_st');
			$chk_by = $this->input->post('chk_by');
			$chk_time = $this->input->post('chk_time');
			
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
			
			$jsGatePass = $this->input->post("jsPass");

			if($jsGatePass != "")
			{
				$vrfyInfoFclId = $this->input->post("fcl_id");
				$vrfyOtherDataId = $this->input->post("lcl_id");

				$jsId = "";
				$jsName = "";
				$sql_jsid = "SELECT id,agent_name FROM vcms_vehicle_agent WHERE card_number = '$jsGatePass'";
				$data_jsid = $this->bm->dataSelectDB1($sql_jsid);
				for($i=0;$i<count($data_jsid);$i++)
				{
					$jsId = $data_jsid[$i]['id'];
					$jsName =$data_jsid[$i]['agent_name'];
				}
				$jettyName = $jsName;

				if($vrfyInfoFclId != "")
				{
					$sql_chkJS = "SELECT COUNT(*) AS rtnValue
					FROM verify_info_fcl
					WHERE jetty_sirkar_id='$jsId' AND id='$vrfyInfoFclId'";
					$rslt_chkJS = $this->bm->dataSelectDB1($sql_chkJS);
					$chkJS = "";
					for($i=0;count($rslt_chkJS)>$i;$i++)
					{
						$chkJS = $rslt_chkJS[$i]['rtnValue'];
					}
					
					if($chkJS == 0)
					{
						$prevJS = "";
						// get previous JS	- check if previous exists
						$sql_prevJS = "SELECT jetty_sirkar_id
						FROM verify_info_fcl
						WHERE id='$vrfyInfoFclId'";
						$rslt_prevJS = $this->bm->dataSelectDB1($sql_prevJS);
						
						for($i=0;$i<count($rslt_prevJS);$i++){
							$prevJS = $rslt_prevJS[$i]['jetty_sirkar_id'];
						}
						
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
				}
				else if($vrfyOtherDataId != "")
				{
					$sql_chkJS = "SELECT COUNT(*) AS rtnValue
					FROM lcl_dlv_assignment
					WHERE jetty_sirkar_id='$jsId' AND id='$vrfyOtherDataId'";

					$rslt_chkJS = $this->bm->dataSelectDB1($sql_chkJS);
					$chkJS = $rslt_chkJS[0]['rtnValue'];

					for($i=0;count($rslt_chkJS)>$i;$i++)
					{
						$chkJS = $rslt_chkJS[$i]['rtnValue'];
					}

					if($chkJS == 0)
					{
						$prevJS = "";

						$sql_prevJS = "SELECT jetty_sirkar_id
						FROM lcl_dlv_assignment
						WHERE id='$vrfyOtherDataId'";
						$rslt_prevJS = $this->bm->dataSelectDB1($sql_prevJS);
						$prevJS = $rslt_prevJS[0]['jetty_sirkar_id'];
						
						$sql_updateJS = "UPDATE lcl_dlv_assignment
						SET jetty_sirkar_id='$jsId'
						WHERE id='$vrfyOtherDataId'";
						// return;
						$this->bm->dataUpdateDB1($sql_updateJS);
					}
				}
			}

			// Jetty Sircar add

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
	
			$assignment_type = "";

			$sql_chk_assignment_type = "SELECT do_truck_details_entry.*,verify_info_fcl.assignment_type,
			(SELECT License_No FROM organization_profiles WHERE AIN_No_New=REPLACE(do_truck_details_entry.update_by,'CF','') OR AIN_No = REPLACE(do_truck_details_entry.update_by,'CF','') AND organization_profiles.Org_Type_id='2' ORDER BY id LIMIT 1) AS cflic
			FROM do_truck_details_entry 
			LEFT JOIN verify_info_fcl ON do_truck_details_entry.verify_info_fcl_id=verify_info_fcl.id
			WHERE do_truck_details_entry.id = '$visitId'";

			$rslt_chk_assignment_type = $this->bm->dataSelectDB1($sql_chk_assignment_type);
			$assignment_type = "";
			$cflic = "";
			$verify_info_fcl_id = null;
			for($a=0;$a<count($rslt_chk_assignment_type);$a++){
				$assignment_type = $rslt_chk_assignment_type[$a]['assignment_type'];
				$cflic = $rslt_chk_assignment_type[$a]['cflic'];
				$verify_info_fcl_id = $rslt_chk_assignment_type[$a]['verify_info_fcl_id'];
			}

			if(!is_null($verify_info_fcl_id))
			{
				$strGetContByCF ="SELECT cont_no,mfdch_value FROM ctmsmis.tmp_oracle_assignment WHERE cf_lic = '$cflic' AND assignmentDate BETWEEN CONCAT(DATE(NOW()),' 00:00:00') AND CONCAT(DATE(NOW()),' 23:59:59') AND mfdch_value NOT IN('CANCEL','APPREF','APPCUS','APPOTH','--') ORDER BY cont_no ASC";
				$resGetContByCF = $this->bm->dataSelectDb2($strGetContByCF);
				
				$data['resGetContByCF'] = $resGetContByCF;
				$data['assignment_type'] = $assignment_type;

				$sql_cont = "SELECT cont_no,import_rotation FROM do_truck_details_entry WHERE id = '$visitId'";
				$rslt_cont = $this->bm->dataSelectDB1($sql_cont);
				$cont = "";
				$import_rotation = "";
				for($i=0;$i<count($rslt_cont);$i++){
					$cont = $rslt_cont[$i]['cont_no'];
					$import_rotation = $rslt_cont[$i]['import_rotation'];
				}
				
				// $cont_status = "";

				// $rslt_status = $this->bm->chkBlockedContainer($cont,$visitId);

				// for($i = 0;$i<count($rslt_status);$i++){
				// 	$cont_status = $rslt_status[$i]['custom_block_st'];
				// }

				// $data['cont_status'] = $cont_status;


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
			else
			{
				$data['title']="Loading Process";
				$data['msg3'] = "<font color='red' size='4'>This is not FCL Container!</font>";
				$data['flag'] = 0;
				$data['disputeFlag'] = "0";
				
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('truckByContForm',$data);
				$this->load->view('jsAssets');
			}
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
			$jsPass = $this->input->post("jsPass");
			$contNo = $this->input->post("contLoad");
			$orgTypeId = $this->session->userdata('org_Type_id');

			// entry for container -- start

			$assignmentQuery = "SELECT mfdch_value,rot_no,cont_status,unit_gkey,cf_lic FROM ctmsmis.tmp_oracle_assignment WHERE assignmentDate = DATE(NOW()) AND cont_no = '$contNo'";
			$assignmentRslt = $this->bm->dataSelectDb2($assignmentQuery);

			$rotNo = "";
			$cont_status = "";
			$unit_gkey = "";
			$assignmentType = "";
			$org_license = "";
			
			for($ar = 0; $ar<count($assignmentRslt);$ar++)
			{
				$rotNo = $assignmentRslt[$ar]['rot_no'];
				$cont_status = $assignmentRslt[$ar]['cont_status'];
				$unit_gkey = $assignmentRslt[$ar]['unit_gkey'];
				$assignmentType = $assignmentRslt[$ar]['mfdch_value'];
				$org_license = $assignmentRslt[$ar]['cf_lic'];
			}

			// custom block check -- start

			$cont_stat = "";

			$rslt_status = $this->bm->chkBlockedContainer($contNo,$id);

			for($i = 0;$i<count($rslt_status);$i++){
				$cont_stat = $rslt_status[$i]['custom_block_st'];
			}
			
			$data['cont_stat'] = $cont_stat;

			if($cont_stat == "DO_NOT_RELEASE")
			{
				$assignment_type = "";
				$sql_chk_assignment_type = "SELECT do_truck_details_entry.*,verify_info_fcl.assignment_type
									FROM do_truck_details_entry 
									INNER JOIN verify_info_fcl on do_truck_details_entry.verify_info_fcl_id=verify_info_fcl.id
									WHERE do_truck_details_entry.id = '$id'";
				$rslt_chk_assignment_type = $this->bm->dataSelectDB1($sql_chk_assignment_type);
				for($a=0;$a<count($rslt_chk_assignment_type);$a++){
					$assignment_type = $rslt_chk_assignment_type[$a]['assignment_type'];
				}
				$data['assignment_type'] = $assignment_type;

				$strGetContByCF ="SELECT cont_no,mfdch_value FROM ctmsmis.tmp_oracle_assignment WHERE cf_lic = '$org_license' AND assignmentDate BETWEEN CONCAT(DATE(NOW()),' 00:00:00') AND CONCAT(DATE(NOW()),' 23:59:59')";
				$resGetContByCF = $this->bm->dataSelectDb2($strGetContByCF);
				
				$data['resGetContByCF'] = $resGetContByCF;
				
				$data['cont_status'] = "";
				$data['visitId'] = $id;
				$data['title']= "Loading Process";
				$data['msg'] = "<font color='red' size='4'><b>This container is blocked by custom.</b></font>";;
				$data['flag'] = 1;
				$data['disputeFlag'] = "0";

				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('truckByContForm',$data);
				$this->load->view('jsAssets');
				return;
			}

			// custom block check -- end

			$sql_chkExist = "SELECT COUNT(*) AS rtnValue 
			FROM verify_info_fcl 
			WHERE rotation='$rotNo' AND cont_number='$contNo'";
			$rslt_chkExist = $this->bm->dataSelectDb1($sql_chkExist);
			$cnt = "";
			for($i=0;count($rslt_chkExist)>$i;$i++)
			{
				$cnt = $rslt_chkExist[$i]['rtnValue'];
			}
			
			$sql_igmDtlContId = "SELECT igm_details.id AS igm_dtl_id,igm_detail_container.id AS igm_dtl_cont_id,cont_size
			FROM igm_details
			INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
			WHERE Import_Rotation_No='$rotNo' AND cont_number='$contNo'";
			$rslt_igmDtlContId = $this->bm->dataSelectDb1($sql_igmDtlContId);
			$igmDtlId = "";
			$igmDtlContId = "";
			$cont_size = "";
			for($i=0;count($rslt_igmDtlContId)>$i;$i++)
			{
				$igmDtlId = $rslt_igmDtlContId[$i]['igm_dtl_id'];				
				$igmDtlContId = $rslt_igmDtlContId[$i]['igm_dtl_cont_id'];
				$cont_size = $rslt_igmDtlContId[$i]['cont_size'];
			}
		
			if($cont_size == 20)
				$truck_qty = 2;
			// else if($cont_size == 40)
			else
				$truck_qty = 3;
			
			$sql_smsNo = "SELECT cf_sms_number 
			FROM ctmsmis.tmp_oracle_assignment
			WHERE cf_lic='$org_license' AND cf_sms_number IS NOT NULL";
			$rslt_smsNo = $this->bm->dataSelectDb2($sql_smsNo);
			$smsNo = "";
			for($i=0;count($rslt_smsNo)>$i;$i++)
			{
				$smsNo = $rslt_smsNo[$i]['cf_sms_number'];
			}

			//checking part BL

			$partBLQuery = "SELECT COUNT(*) AS rtnValue FROM igm_sup_detail_container 
			INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=igm_sup_detail_container.igm_sup_detail_id
			WHERE cont_number='$contNo' AND Import_Rotation_No='$rotNo' AND cont_status='FCL/PART'";
			$rslt_partBL = $this->bm->dataSelectDb1($partBLQuery);
			$partbl = 0;
			for($i=0;$i<count($rslt_partBL);$i++){
				$partbl = $rslt_partBL[$i]['rtnValue'];
			}

			if($partbl == 0){
				$partBLQuery = "SELECT COUNT(*) AS rtnValue
				FROM igm_detail_container
				INNER JOIN igm_details ON igm_detail_container.igm_detail_id=igm_details.id
				WHERE igm_details.Import_Rotation_No='$rotNo' AND igm_detail_container.cont_number='$contNo' AND cont_status='FCL/PART'";
				$rslt_partBL = $this->bm->dataSelectDb1($partBLQuery);
				$partbl = 0;
				for($i=0;$i<count($rslt_partBL);$i++){
					$partbl = $rslt_partBL[$i]['rtnValue'];
				}
			}

			$partblsts = 0;
			
			if($partbl>0){
				$partblsts = 1;
			}

			$cfLogin_query = "SELECT login_id FROM organization_profiles 
			INNER JOIN users ON users.org_id = organization_profiles.id
			WHERE organization_profiles.License_No = '$org_license'";

			$cfLogin_rslt = $this->bm->dataSelectDB1($cfLogin_query);
			$cfLogin_id = "";
			if(count($cfLogin_rslt)>0)
			{
				$cfLogin_id = $cfLogin_rslt[0]['login_id'];
			}

			if($cnt==0)
			{			
				$sql_insertQtyTruck = "INSERT INTO verify_info_fcl(igm_detail_id,igm_detail_cont_id,assignment_type,cnf_lic_no,cnf_mobile_no,unit_gkey,rotation,cont_number,no_of_truck,is_part_bl,truck_no_by,truck_no_time)
				VALUES('$igmDtlId','$igmDtlContId','$assignmentType','$org_license','$smsNo','$unit_gkey','$rotNo','$contNo','$truck_qty','$partblsts','$cfLogin_id',NOW())";
				
				if($this->bm->dataInsertDB1($sql_insertQtyTruck))
					$msg = "<font color='green'><b>Truck number entered succesfully.</b></font>";
			}
			else
			{
				$sql_updateQtyTruck = "UPDATE verify_info_fcl
				SET cnf_mobile_no='$smsNo',unit_gkey='$unit_gkey',no_of_truck='$truck_qty',is_part_bl='$partblsts',truck_no_by='$cfLogin_id',truck_no_time=NOW()
				WHERE rotation='$rotNo' AND cont_number='$contNo'";
				
				if($this->bm->dataUpdateDB1($sql_updateQtyTruck))
					$msg = "<font color='green'><b>Truck number updated succesfully.</b></font>";
			}

			$sql_verifyInfoFclid = "SELECT id FROM verify_info_fcl WHERE rotation='$rotNo' AND cont_number='$contNo'";
			$data_vrfyinfofclId = $this->bm->dataSelectDB1($sql_verifyInfoFclid);
			$vrfyInfoFclId = "";
			for($i=0;count($data_vrfyinfofclId)>$i;$i++)
			{
				$vrfyInfoFclId = $data_vrfyinfofclId[$i]['id'];
			}

			$truckUpdateQuery = "UPDATE do_truck_details_entry SET cont_no = '$contNo', import_rotation = '$rotNo', verify_info_fcl_id = '$vrfyInfoFclId' WHERE id = '$id'";
			$this->bm->dataUpdateDB1($truckUpdateQuery);

			$strGetContByCF ="SELECT cont_no FROM ctmsmis.tmp_oracle_assignment WHERE cf_lic = '$org_license' AND assignmentDate BETWEEN CONCAT(DATE(NOW()),' 00:00:00') AND CONCAT(DATE(NOW()),' 23:59:59')";
			$resGetContByCF = $this->bm->dataSelectDb2($strGetContByCF);
			
			$data['resGetContByCF'] = $resGetContByCF;


			// entry for container -- end

			// Jetty sircar entry -- start
			
			$jsId = "";
			$jsName = "";
			$sql_jsid = "SELECT id,agent_name FROM vcms_vehicle_agent WHERE card_number = '$jsPass'";
			$data_jsid = $this->bm->dataSelectDB1($sql_jsid);

			for($i=0;$i<count($data_jsid);$i++)
			{
				$jsId = $data_jsid[$i]['id'];
				$jsName =$data_jsid[$i]['agent_name'];
			}

			$truckQuery = "SELECT verify_info_fcl_id, verify_other_data_id FROM do_truck_details_entry WHERE id = '$id'"; 
			$truckData = $this->bm->dataSelectDb1($truckQuery);

			$vrfyInfoFclId = "";
			$vrfyOtherDataId = "";
			
			if(count($truckData)>0){
				$vrfyInfoFclId = $truckData[0]['verify_info_fcl_id'];
				$vrfyOtherDataId = $truckData[0]['verify_other_data_id'];
			}

			if($vrfyInfoFclId == "")
			{
				// echo "LCL";
				// return;
				$sql_chkJS = "SELECT COUNT(*) AS rtnValue
				FROM lcl_dlv_assignment
				WHERE jetty_sirkar_id='$jsId' AND id='$vrfyOtherDataId'";

				$rslt_chkJS = $this->bm->dataSelectDB1($sql_chkJS);
				$chkJS = $rslt_chkJS[0]['rtnValue'];

				for($i=0;count($rslt_chkJS)>$i;$i++)
				{
					$chkJS = $rslt_chkJS[$i]['rtnValue'];
				}

				if($chkJS == 0)
				{
					$prevJS = "";
					$sql_prevJS = "SELECT jetty_sirkar_id
					FROM lcl_dlv_assignment
					WHERE id='$vrfyOtherDataId'";
					$rslt_prevJS = $this->bm->dataSelectDB1($sql_prevJS);
					$prevJS = $rslt_prevJS[0]['jetty_sirkar_id'];
					
					$sql_updateJS = "UPDATE lcl_dlv_assignment
					SET jetty_sirkar_id='$jsId'
					WHERE id='$vrfyOtherDataId'";
					// return;
					$this->bm->dataUpdateDB1($sql_updateJS);
				}
			}
			else
			{
				// echo "FCL";
				// return;
				$sql_chkJS = "SELECT COUNT(*) AS rtnValue
				FROM verify_info_fcl
				WHERE jetty_sirkar_id='$jsId' AND id='$vrfyInfoFclId'";
				$rslt_chkJS = $this->bm->dataSelectDB1($sql_chkJS);
				$chkJS = "";
				for($i=0;count($rslt_chkJS)>$i;$i++)
				{
					$chkJS = $rslt_chkJS[$i]['rtnValue'];
				}
				
				if($chkJS == 0)
				{
					$prevJS = "";
					// get previous JS	- check if previous exists
					$sql_prevJS = "SELECT jetty_sirkar_id
					FROM verify_info_fcl
					WHERE id='$vrfyInfoFclId'";
					$rslt_prevJS = $this->bm->dataSelectDB1($sql_prevJS);
					
					for($i=0;$i<count($rslt_prevJS);$i++){
						$prevJS = $rslt_prevJS[$i]['jetty_sirkar_id'];
					}
					
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
			}

			// Jetty sircar entry -- end

			$assignment_type = "";
			$sql_chk_assignment_type = "SELECT do_truck_details_entry.*,verify_info_fcl.assignment_type
								FROM do_truck_details_entry 
								INNER JOIN verify_info_fcl on do_truck_details_entry.verify_info_fcl_id=verify_info_fcl.id
								WHERE do_truck_details_entry.id = '$id'";
			$rslt_chk_assignment_type = $this->bm->dataSelectDB1($sql_chk_assignment_type);
			for($a=0;$a<count($rslt_chk_assignment_type);$a++){
				$assignment_type = $rslt_chk_assignment_type[$a]['assignment_type'];
			}
			$data['assignment_type'] = $assignment_type;
			
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
			else if($orgTypeId == 59)
			{
				$loadQuery = "UPDATE do_truck_details_entry SET load_st = '1' , actual_delv_pack = '$loadQty', actual_delv_unit = '$packUnit', cnf_chk_st = '0' , traffic_chk_st = '1', yard_security_chk_st = '0', traffic_chk_time = NOW() , traffic_chk_by = '$login_id' , load_by = '$login_id' , load_time = NOW() WHERE id='$id'";
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
			$msg1 = "";

			$assignmentQuery = "SELECT mfdch_value,rot_no,cont_status,unit_gkey,cf_lic FROM ctmsmis.tmp_oracle_assignment WHERE assignmentDate = DATE(NOW()) AND cont_no = '$cont'";
			$assignmentRslt = $this->bm->dataSelect($assignmentQuery);

			$rotNo = "";
			$cont_status = "";
			$unit_gkey = "";
			$assignmentType = "";
			$org_license = "";
			for($ar = 0; $ar<count($assignmentRslt);$ar++)
			{
				$rotNo = $assignmentRslt[$ar]['rot_no'];
				$cont_status = $assignmentRslt[$ar]['cont_status'];
				$unit_gkey = $assignmentRslt[$ar]['unit_gkey'];
				$assignmentType = $assignmentRslt[$ar]['mfdch_value'];
				$org_license = $assignmentRslt[$ar]['cf_lic'];
			}


			$cont_stat = "";

			$rslt_status = $this->bm->chkBlockedContainer($cont,$visit_id);

			for($i = 0;$i<count($rslt_status);$i++){
				$cont_stat = $rslt_status[$i]['custom_block_st'];
			}
			
			$data['cont_stat'] = $cont_stat;

			if($cont_stat == "DO_NOT_RELEASE")
			{

				$strGetContByCF ="SELECT cont_no,mfdch_value FROM ctmsmis.tmp_oracle_assignment WHERE cf_lic = '$org_license' AND assignmentDate BETWEEN CONCAT(DATE(NOW()),' 00:00:00') AND CONCAT(DATE(NOW()),' 23:59:59')";
				$resGetContByCF = $this->bm->dataSelect($strGetContByCF);
				
				$data['resGetContByCF'] = $resGetContByCF;

				$assignment_type = "";
				$sql_chk_assignment_type = "SELECT do_truck_details_entry.*,verify_info_fcl.assignment_type
									FROM do_truck_details_entry 
									INNER JOIN verify_info_fcl on do_truck_details_entry.verify_info_fcl_id=verify_info_fcl.id
									WHERE do_truck_details_entry.id = '$visit_id'";
				$rslt_chk_assignment_type = $this->bm->dataSelectDB1($sql_chk_assignment_type);
				for($a=0;$a<count($rslt_chk_assignment_type);$a++){
					$assignment_type = $rslt_chk_assignment_type[$a]['assignment_type'];
				}

				$data['assignment_type'] = $assignment_type;
				$data['cont_status'] = "";
				$data['visitId'] = $visit_id;
				$data['title']= "Loading Process";
				$data['msg'] = $msg;
				$data['msg1'] = "<font color='red' size='4'><b>This container is blocked by custom.</b></font>";
				$data['flag'] = 1;
				$data['disputeFlag'] = "0";

				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('truckByContForm',$data);
				$this->load->view('jsAssets');
				return;
			}

			$btn = $this->input->post("btn");

			if($btn == 'add')
			{	
				$sql_addCont = "INSERT INTO do_truck_details_additional_cont(truck_visit_id,cont_no,pack_num,pack_unit) VALUES('$visit_id','$cont','$loadQty','$pack_unit')";
				$rslt_update = $this->bm->dataInsertDB1($sql_addCont);

				$sql_updateTruck = "UPDATE do_truck_details_entry SET add_truck_st = 1 WHERE id = '$visit_id'";
				$this->bm->dataInsertDB1($sql_updateTruck);
			}
			else
			{
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
			$data['msg1'] = $msg1;
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
			$gate_pass = $this->input->post("gate_pass");		// gate_pass is visit id
			
			
			$sql_cont = "SELECT cont_no FROM do_truck_details_entry WHERE id = '$gate_pass'";
			$rslt_cont = $this->bm->dataSelectDB1($sql_cont);
			$cont = "";
			for($i=0;$i<count($rslt_cont);$i++){
				$cont = $rslt_cont[$i]['cont_no'];
			}
			
			$cont_status = "";

			// $rslt_status = $this->chkBlockedContainer($cont);
			$rslt_status = $this->bm->chkBlockedContainer($cont,$gate_pass);
			//var_dump($rslt_status);

			for($i = 0;$i<count($rslt_status);$i++){
				$cont_status = $rslt_status[$i]['custom_block_st'];
			}
			
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
			$gate_pass = $this->input->post("gate_pass");		// gate pass is visit id
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
			
			$cont_status = "";

			$rslt_status = $this->bm->chkBlockedContainer($cont,$gate_pass);
			//var_dump($rslt_status);

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

	function paymentCollection()
	{
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
			
			$cont_status = "";

			// $rslt_status = $this->chkBlockedContainer($cont);
			$rslt_status = $this->bm->chkBlockedContainer($cont,$trucVisitId);

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
			$gate = $this->input->post("gate_no");
			$data['msg'] = "";

			if($login_id == "pass"){
				$data['msg'] = "<font color='red'>You are not authorized to collect payment!</font>";
			}
			else
			{
				if($gate == "")
				{
					$stsUpdateQuery = "UPDATE do_truck_details_entry SET gate_no='$gate_no', paid_status='1' , paid_collect_dt = NOW() , paid_collect_by = '$login_id' , pay_collect_ip = '$ip_address' , collect_gate_no = '$gate_no' WHERE id='$id'";
				}
				else
				{
					$stsUpdateQuery = "UPDATE do_truck_details_entry SET paid_status='1' , paid_collect_dt = NOW() , paid_collect_by = '$login_id' , pay_collect_ip = '$ip_address' , collect_gate_no = '$gate_no' WHERE id='$id'";
				}
				
				$rslt_update=$this->bm->dataUpdateDB1($stsUpdateQuery);

				$dataQuery = "SELECT update_by,truck_id FROM do_truck_details_entry WHERE id='$id'";
				$rslt_data=$this->bm->dataSelectDB1($dataQuery);

				$cfId = "";
				$truckId = "";
				for($i=0;$i<count($rslt_data);$i++){
					$cfId = $rslt_data[$i]['update_by'];
					$truckId = $rslt_data[$i]['truck_id'];
				}
				
				$ain = substr($cfId,0,-2);
				$trkPart = explode(" ",$truckId);
				$trck = $trkPart[0]." ".$trkPart[3]." ".$trkPart[4];   
				//$trck = urlencode($trck);

				if($rslt_update == 1)
				{
					if($id != 0)
					{
						$eventType = "ISSUE";
						$biometricInsertQuery = "INSERT INTO biometricEventLog(visit_id,event_type,ain_no,driver_pass,helper_pass,truck_id,entry_at,entry_by,entry_ip) VALUES('$id','$eventType','$ain','$driverPass','$helperPass','$trck',NOW(),'$login_id','$ip_address')";
						$this->bm->dataInsertDB1($biometricInsertQuery);
					}

					// if($helperPass == "" || $helperPass == null){
					// 	$url = "http://10.1.100.105:8095/tosevent.php?VISITNO=".$id."&EVENT=ISSUE&AIN=".$ain."&DRIVER=".$driverPass."&LP=".$trck;
					// 	$json = file_get_contents($url);
					// 	$obj = json_decode($json);
					// }else{
					// 	$url = "http://10.1.100.105:8095/tosevent.php?VISITNO=".$id."&EVENT=ISSUE&AIN=".$ain."&DRIVER=".$driverPass."&HELPER=".$helperPass."&LP=".$trck;
					// 	$json = file_get_contents($url);
					// 	$obj = json_decode($json);
					// }
				}
				$data['msg'] = "";
			}

			$data['cont_status'] = "";
			$data['trucVisitId'] = $id;
			$data['title']="Payment Collection Form";
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
			
			$cont_status = "";

			// $rslt_status = $this->chkBlockedContainer($cont);
			$rslt_status = $this->bm->chkBlockedContainer($cont,$visitId);
			//var_dump($rslt_status);

			for($i = 0;$i<count($rslt_status);$i++){
				$cont_status = $rslt_status[$i]['custom_block_st'];
			}
			
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
			
			$cont_status = "";
			
			$rslt_status = $this->bm->chkBlockedContainer($cont,$id);
			//var_dump($rslt_status);

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
		//$pdf->SetWatermarkText('CPA CTMS');
		$pdf->showWatermarkText = false;
		//	$stylesheet = file_get_contents('resources/styles/test.css'); // external css
		$stylesheet = file_get_contents('resources/styles/cartticket.css'); // external css
		//	$pdf->useSubstitutions = true; // optional - just as an example

		//$pdf->setFooter('Developed By : '.$login_id.'|Page {PAGENO}|Date {DATE j-m-Y}');

		$pdf->WriteHTML($stylesheet,1);
		$pdf->WriteHTML($html,2);

		$pdf->Output($pdfFilePath, "I"); // For Show Pdf
		
	}
	
	
	
	


//vcms cart challan ticket view strat
// oracle 
	function vcmsCartChalanTicketView()
	{
		$rot_no=$this->input->post('rot_no');
		$cont_no=$this->input->post('cont_no');
	    $trucVisitId=$this->input->post('trucVisitId');
		$CNFLicenceNo=$this->input->post('cnf_lic_no');
		
		//Added by kawsar - 2022-07-21
		$cont_stat_query = "SELECT verify_info_fcl_id,verify_other_data_id FROM do_truck_details_entry WHERE id = '$trucVisitId'";
		$cont_stat_rslt = $this->bm->dataSelectDB1($cont_stat_query);

		if(count($cont_stat_rslt)>0){
			$verify_info_fcl_id = $cont_stat_rslt[0]['verify_info_fcl_id'];
			$verify_other_data_id = $cont_stat_rslt[0]['verify_other_data_id'];
		}

		$cont_stat = null;
		if($verify_info_fcl_id == null || $verify_info_fcl_id == "")
		{
			$cont_stat = "LCL";
		}else{
			$cont_stat = "FCL";
		}
		
		

		 $data['cont_stat'] = $cont_stat;
		
	
		
		if($cont_stat == 'LCL')
		{
			// New code starts here with part bl

			$this->load->library('m_pdf');
			//$mpdf->use_kwt = true;
			$cnfLic = "";
			$assignment_type = "";

			$sqlcnf="SELECT cnf_lic_no, lcl_dlv_assignment.bl_no
			FROM do_truck_details_entry
			INNER JOIN lcl_dlv_assignment ON lcl_dlv_assignment.id = do_truck_details_entry.verify_other_data_id
			WHERE do_truck_details_entry.id='$trucVisitId'";   
			 $rslt_cnfLic = $this->bm->dataSelectDB1($sqlcnf);
			$cnfLic = "";
			$bl_no = "";
			if(count($rslt_cnfLic)>0)
			{
			    $cnfLic = $rslt_cnfLic[0]['cnf_lic_no'];
				$bl_no = $rslt_cnfLic[0]['bl_no'];
			}
			
			$this->data['bl_no']=$bl_no;

			$additionalBlQuery = "SELECT bl_no FROM do_truck_details_additional_bl_lcl WHERE truck_visit_id = '$trucVisitId'";
			$additionalBlData = $this->bm->dataSelectDB1($additionalBlQuery);
			$this->data['additionalBlData']=$additionalBlData;		


			
		    $sql_CNFName="SELECT id,name 
			FROM ref_bizunit_scoped 
			WHERE role='SHIPPER' AND life_cycle_state='ACT' AND id='$cnfLic'";
			
		
			

			$sqlExtraTrucks="SELECT do_truck_details_additional_cont_lcl.*,igm_pack_unit.Pack_Unit AS actual_delv_unit
			FROM do_truck_details_additional_cont_lcl
			LEFT JOIN igm_pack_unit ON igm_pack_unit.id=do_truck_details_additional_cont_lcl.pack_unit 
		    WHERE truck_visit_id='$trucVisitId'";   
			$rsltExtraTrucks = $this->bm->dataSelectDB1($sqlExtraTrucks);
			$this->data['rsltExtraTrucks']=$rsltExtraTrucks;

			//Extra Trucks----ends
			$sqlHBL="SELECT igm_supplimentary_detail.Import_Rotation_No,igm_supplimentary_detail.master_BL_No,
			SUBSTR(igm_supplimentary_detail.Description_of_Goods,1,100) AS Description_of_Goods,
			igm_supplimentary_detail.BL_No,igm_sup_detail_container.cont_number
			FROM igm_sup_detail_container
			INNER JOIN igm_supplimentary_detail ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
			WHERE igm_supplimentary_detail.BL_No='$bl_no' AND igm_supplimentary_detail.Import_Rotation_No='$rot_no'
			
			UNION
			
			SELECT igm_details.Import_Rotation_No,igm_details.BL_No,
			SUBSTR(igm_details.Description_of_Goods,1,100) AS Description_of_Goods,
			igm_details.BL_No,igm_detail_container.cont_number
			FROM igm_detail_container
			INNER JOIN igm_details ON igm_detail_container.igm_detail_id=igm_details.id
			WHERE igm_details.BL_No='$bl_no' AND igm_details.Import_Rotation_No='$rot_no'";
			// echo $sqlHBL;return;
			$resHBL = $this->bm->dataSelectDB1($sqlHBL);
			$cntHBL = count($resHBL);
			$this->data['cntHBL']=$cntHBL;
			$this->data['resHBL']=$resHBL;		
			
			//print_r($resHBL);
			//return;
			
			$house_bl=array();
			$allHBL="";
			for($j=0;$j<count($resHBL);$j++)
			{
				$bls = "'".$resHBL[$j]['BL_No']."'";
				array_push($house_bl,$bls);
			}
			//print_r($house_bl);
			//return;
			$allHBL = implode(",",$house_bl);	
			
			$this->data['allHBL']=$allHBL;	


			
			//---Chalan included--
			
		    $CNFStr1="SELECT distinct(ref_bizunit_scoped.name) as name, address_line1,
			ref_bizunit_scoped.sms_number
			FROM inv_unit 
			INNER JOIN inv_goods ON inv_goods.gkey=inv_unit.goods
			LEFT JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=inv_goods.consignee_bzu
			WHERE ref_bizunit_scoped.id = '$CNFLicenceNo'";
			$CNFresult = $this->bm->dataSelect($CNFStr1);
		
			
			// added on 2022-03-06 - intakhab
			if(count($CNFresult)==0)
			{
				$CNFLicenceNo="0".$CNFLicenceNo;
				
				$CNFStr1="SELECT distinct(ref_bizunit_scoped.name) as name, address_line1,
				ref_bizunit_scoped.sms_number
				FROM inv_unit 
				INNER JOIN inv_goods ON inv_goods.gkey=inv_unit.goods
				LEFT JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=inv_goods.consignee_bzu
				WHERE ref_bizunit_scoped.id = '$CNFLicenceNo'";
				// echo $CNFStr1;return;
			 	$CNFresult = $this->bm->dataSelect($CNFStr1);
			
			}

			if(count($CNFresult)==0)
			{
			

			    $CNFStr1="SELECT id,name 
				FROM ref_bizunit_scoped 
				WHERE role='SHIPPER' AND life_cycle_state='ACT' AND id='$cnfLic'";
				$CNFresult = $this->bm->dataSelect($CNFStr1);

			}
			
	
				
			$queryStr="SELECT do_truck_details_entry.truck_id,do_truck_details_entry.actual_delv_pack, igm_pack_unit.Pack_Unit AS actual_delv_unit,do_truck_details_entry.gate_no,do_truck_details_entry.cont_no,do_truck_details_entry.import_rotation, SUBSTR(igm_supplimentary_detail.Description_of_Goods, 1, 100) AS Description_of_Goods, igm_supplimentary_detail.Notify_name,igm_supplimentary_detail.Notify_address
			FROM do_truck_details_entry
			INNER JOIN lcl_dlv_assignment ON lcl_dlv_assignment.id = do_truck_details_entry.verify_other_data_id
			INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=lcl_dlv_assignment.igm_sup_dtl_id
			LEFT JOIN igm_pack_unit ON igm_pack_unit.id=do_truck_details_entry.actual_delv_unit
			WHERE do_truck_details_entry.id='$trucVisitId'";
			// echo $queryStr;return;
			$resQuery = $this->bm->dataSelectDb1($queryStr);
			
			if(count($resQuery)==0)
			{
				$queryStr="SELECT do_truck_details_entry.truck_id,do_truck_details_entry.actual_delv_pack, igm_pack_unit.Pack_Unit AS actual_delv_unit,do_truck_details_entry.gate_no,do_truck_details_entry.cont_no,do_truck_details_entry.import_rotation, SUBSTR(igm_details.Description_of_Goods, 1, 100) AS Description_of_Goods, igm_details.Notify_name,igm_details.Notify_address
				FROM do_truck_details_entry
				INNER JOIN lcl_dlv_assignment ON lcl_dlv_assignment.id = do_truck_details_entry.verify_other_data_id
				INNER JOIN igm_details ON igm_details.id=lcl_dlv_assignment.igm_sup_dtl_id
				LEFT JOIN igm_pack_unit ON igm_pack_unit.id=do_truck_details_entry.actual_delv_unit
				WHERE do_truck_details_entry.id='$trucVisitId'";
				$resQuery = $this->bm->dataSelectDb1($queryStr);
			}

		
				
			$login_id = $this->session->userdata('login_id');
			// echo$queryStr;return;	-- checked
			$this->data['CNFresult']=$CNFresult;
			$this->data['resQuery']=$resQuery;
			$this->data['visitId']=$trucVisitId;

		
			//  ---  Chalan excluded  --
			
			
			$this->data['trucVisitId']=$trucVisitId;
		
			$html=$this->load->view('vcmsCartChalanTicketViewLCL',$this->data, true); 

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

			$pdf->Output($pdfFilePath, "I"); // For Show Pdf CNFresult

		}
		else if($cont_stat == 'FCL' || $cont_stat == 'FCL/PART')
		{
			$this->load->library('m_pdf');
			//$mpdf->use_kwt = true;
			$cnfLic = "";
			$assignment_type = "";

			$sqlcnf="SELECT cnf_lic_no,verify_info_fcl.assignment_type 
			FROM do_truck_details_entry
			INNER JOIN verify_info_fcl ON verify_info_fcl.id=do_truck_details_entry.verify_info_fcl_id
			WHERE do_truck_details_entry.id='$trucVisitId'";   
			$rslt_cnfLic = $this->bm->dataSelectDB1($sqlcnf);
			
			if(count($rslt_cnfLic)>0){
				$cnfLic = $rslt_cnfLic[0]['cnf_lic_no'];
				for($a=0;$a<count($rslt_cnfLic);$a++){
					$assignment_type = $rslt_cnfLic[$a]['assignment_type'];
				}
			}else{
				$sqlcnf="SELECT cnf_lic_no FROM do_truck_details_entry
				INNER JOIN verify_other_data ON verify_other_data.id=do_truck_details_entry.verify_other_data_id
				WHERE do_truck_details_entry.id='$trucVisitId'";   
				$rslt_cnfLic = $this->bm->dataSelectDB1($sqlcnf);
				if(count($rslt_cnfLic)>0){
					$cnfLic = $rslt_cnfLic[0]['cnf_lic_no'];
				}
			}

			
			$this->data['assignment_type'] = $assignment_type;

			
			 $sql_CNFName="SELECT id,name 
			FROM ref_bizunit_scoped 
			WHERE role='SHIPPER' AND life_cycle_state='ACT' AND id='$cnfLic'";
		
			
		
			
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
				igm_supplimentary_detail.BL_No,igm_sup_detail_container.cont_number,igm_supplimentary_detail.Notify_name
				FROM igm_sup_detail_container
				INNER JOIN igm_supplimentary_detail ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
				WHERE igm_sup_detail_container.cont_number='$cont_no' AND igm_supplimentary_detail.Import_Rotation_No='$rot_no'";   
			$resHBL = $this->bm->dataSelectDB1($sqlHBL);
			$cntHBL = count($resHBL);
			$this->data['cntHBL']=$cntHBL;
			$this->data['resHBL']=$resHBL;		
			
			//print_r($resHBL);
			//return;
			
			$house_bl=array();
			$allHBL="";
			for($j=0;$j<count($resHBL);$j++)
			{
				$bls = "'".$resHBL[$j]['BL_No']."'";
				array_push($house_bl,$bls);
			}
			
			$allHBL = implode(",",$house_bl);	
			
			$this->data['allHBL']=$allHBL;	


			
			//---Chalan included--
			
				$CNFStr1="SELECT distinct(ref_bizunit_scoped.name) as name, address_line1, ref_bizunit_scoped.sms_number
				FROM inv_unit 
				INNER JOIN inv_goods ON inv_goods.gkey=inv_unit.goods
				LEFT JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=inv_goods.consignee_bzu
				WHERE ref_bizunit_scoped.id = '$CNFLicenceNo'";
				$CNFresult = $this->bm->dataSelect($CNFStr1);
				
				// added on 2022-03-06 - intakhab
				if(count($CNFresult)==0)
				{
					$CNFLicenceNo="0".$CNFLicenceNo;
					
					$CNFStr1="SELECT distinct(ref_bizunit_scoped.name) as name, address_line1, ref_bizunit_scoped.sms_number
					FROM inv_unit 
					INNER JOIN inv_goods ON inv_goods.gkey=inv_unit.goods
					LEFT JOIN ref_bizunit_scoped ON ref_bizunit_scoped.gkey=inv_goods.consignee_bzu
					WHERE ref_bizunit_scoped.id = '$CNFLicenceNo'";
					 $CNFresult = $this->bm->dataSelect($CNFStr1);
				}
				
				$queryStr="SELECT do_truck_details_entry.truck_id,do_truck_details_entry.actual_delv_pack,
				igm_pack_unit.Pack_Unit AS actual_delv_unit,do_truck_details_entry.gate_no,do_truck_details_entry.cont_no,do_truck_details_entry.import_rotation,
				verify_info_fcl.igm_detail_cont_id,
				verify_info_fcl.igm_detail_id,SUBSTR(igm_details.Description_of_Goods, 1, 100) AS Description_of_Goods,
				igm_details.Notify_name,igm_details.Notify_address
				FROM do_truck_details_entry
				INNER JOIN verify_info_fcl ON do_truck_details_entry.verify_info_fcl_id=verify_info_fcl.id
				INNER JOIN igm_details ON igm_details.id=verify_info_fcl.igm_detail_id
				LEFT JOIN igm_pack_unit ON igm_pack_unit.id=do_truck_details_entry.actual_delv_unit
				WHERE do_truck_details_entry.id='$trucVisitId'";

				$resQuery = $this->bm->dataSelectDb1($queryStr);

				if(count($resQuery) == 0){
					$queryStr="SELECT do_truck_details_entry.truck_id,do_truck_details_entry.actual_delv_pack,
					igm_pack_unit.Pack_Unit AS actual_delv_unit,do_truck_details_entry.gate_no,do_truck_details_entry.cont_no,do_truck_details_entry.import_rotation,
					SUBSTR(Description_of_Goods, 1, 100) AS Description_of_Goods,
					Notify_name,Notify_address
					FROM do_truck_details_entry
					INNER JOIN lcl_dlv_assignment ON do_truck_details_entry.verify_other_data_id=lcl_dlv_assignment.id
					INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=lcl_dlv_assignment.igm_sup_dtl_id
					LEFT JOIN igm_pack_unit ON igm_pack_unit.id=do_truck_details_entry.actual_delv_unit
					WHERE do_truck_details_entry.id='$trucVisitId'";
					$resQuery = $this->bm->dataSelectDb1($queryStr);
				}
					// echo$queryStr;return;
				$login_id = $this->session->userdata('login_id');
				
				$this->data['CNFresult']=$CNFresult;
				$this->data['resQuery']=$resQuery;
				$this->data['visitId']=$trucVisitId;

			
			//---Chalan excluded--
			
			
			$this->data['trucVisitId']=$trucVisitId;
		
			$html=$this->load->view('vcmsCartChalanTicketView',$this->data, true); 

			$pdfFilePath ="cartTicket-".time()."-download.pdf";

			$pdf = $this->m_pdf->load();
			//$pdf->SetWatermarkText('CPA CTMS');
			$pdf->showWatermarkText = false;
			//	$stylesheet = file_get_contents('resources/styles/test.css'); // external css
			$stylesheet = file_get_contents('resources/styles/cartticket.css'); // external css
			//	$pdf->useSubstitutions = true; // optional - just as an example

			//$pdf->setFooter('Developed By : '.$login_id.'|Page {PAGENO}|Date {DATE j-m-Y}');

			$pdf->WriteHTML($stylesheet,1);
			$pdf->WriteHTML($html,2);

			$pdf->Output($pdfFilePath, "I"); // For Show Pdf
		}	
	}

    //vcms cart challan ticket view end

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

			$sql_cont = "SELECT cont_no FROM do_truck_details_entry WHERE id = '$visitId'";
			$rslt_cont = $this->bm->dataSelectDB1($sql_cont);
			$cont = "";
			for($i=0;$i<count($rslt_cont);$i++){
				$cont = $rslt_cont[$i]['cont_no'];
			}
			
			$cont_status = "";

			$rslt_status = $this->bm->chkBlockedContainer($cont,$visitId);

			for($i = 0;$i<count($rslt_status);$i++){
				$cont_status = $rslt_status[$i]['custom_block_st'];
			}

			$data['cont_status'] = $cont_status;
			
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
	
	
	function onlinePaymentDataUpdation()
	{
		$find_visitStr = "SELECT id, visit_id, DATE(entry_time) AS ref_date, requ_id FROM vcms_online_pay
							WHERE vcms_online_pay.entry_time BETWEEN NOW()-INTERVAL 1 DAY AND NOW()  AND TransactionStatus IS NULL";
		$chkVisit= $this->bm->dataSelectDB1($find_visitStr);
		
		if(count($chkVisit)>0)
		{
			$visit_id="";
			$ref_date="";
			$requ_id="";
			for($v=0; $v<count($chkVisit); $v++)
			{
				$requ_id=$chkVisit[$v]['requ_id'];
				$ref_date=$chkVisit[$v]['ref_date'];
			
				$verify_post=$this->bm->sonaliPayVerifyJsonbody($ref_date, $requ_id);
			
					
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, "https://spg.com.bd:6314/api/SpgService/TransactionVerification");
				//added
				curl_setopt($ch, CURLOPT_TIMEOUT, 100);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 100);
				curl_setopt($ch, CURLOPT_POST, 1 );

				//added

				curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json","Authorization: OAuth 2.0 token here"));
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $verify_post);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); # KEEP IT FALSE IF YOU RUN FROM LOCAL PC

				curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
				$content = curl_exec($ch);

				$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				//print_r( $content); 
				//return; 
				$getData="";

				if($code == 200 || $code== 0)
				{
					curl_close( $ch);
					// $getData = $content;
					
					 $verifyData = json_decode($content, true );
					//echo $verifyData ='{"TransactionId":"2112122147484169","TransactionDate":"2021-12-12 14:42:51","ReferenceNo":"2147484169_0","ReferenceDate":"2021-12-12","ServiceName":"Test Portal","BrCode":"00026","ApplicantName":"HABIGANJ AGRO LIMITED","MobileNo":"01755128784","TranAmount":"57.50","StatusCode":"200","StCode":"bdtax","SpCode":"sbl_account","PayMode":"A02","PayAmount":"69","Vat":"1.55","Commission":"9.95","ScrollNo":"-P000900002"}';
					
					
					$verifyDa = explode('"',$verifyData);
					
					@$TransactionId=$verifyDa[3];
					@$TransactionDate=$verifyDa[7];
					@$ReferenceNo=$verifyDa[11];
					@$ReferenceDate=$verifyDa[15] ;
					@$ServiceName=$verifyDa[19];
					@$BrCode=$verifyDa[23];
					@$ApplicantName=$verifyDa[27];
					@$MobileNo=$verifyDa[31];
					@$TranAmount=$verifyDa[35];
					@$StatusCode=$verifyDa[39];
					@$StCode=$verifyDa[43];
					@$SpCode=$verifyDa[47];
					@$PayMode=$verifyDa[51];
					@$PayAmount=$verifyDa[55];
					@$Vat=$verifyDa[59];
					@$Commission=$verifyDa[63];
					@$ScrollNo=$verifyDa[67]; 
					
					//return;
					if($StatusCode=='200')
					{
						//echo "ok";
						//return;
						$str_online_dt = "SELECT visit_id, cnf_login_id, assign_type, payer_st  FROM vcms_online_pay WHERE requ_id='$requ_id'";
						$data_pay = $this->bm->dataSelectDB1($str_online_dt);
						
						for($k = 0; $k < count($data_pay); $k++)
						{
							$visitId = $data_pay[$k]['visit_id'];
							$login_id = $data_pay[$k]['cnf_login_id'];
							$assignmentType = $data_pay[$k]['assign_type'];
							$payer_st = $data_pay[$k]['payer_st'];
						}

						$query_update = "UPDATE do_truck_details_entry SET paid_amt = '$PayAmount', paid_status = 1 , paid_method = 'online' , paid_collect_dt = '$TransactionDate' WHERE id = '$visitId'";
						$update_st = $this->bm->dataUpdateDB1($query_update); 

						$update_st="";
						$pay_update_str = "UPDATE vcms_online_pay 
						SET  trans_id = '$TransactionId', trans_time='$TransactionDate', payAmount = '$PayAmount', tranAmount='$TranAmount', vat='$Vat', 
						commision='$Commission', PayMode='$PayMode', 
						refTranDateTime='$ReferenceDate', orgiBrCode = '$BrCode', scrollNo='$ScrollNo', TransactionStatus='$StatusCode',
						updated_by='C&F', update_time=NOW() WHERE requ_id = '$requ_id' AND visit_id = '$visitId'";
						$update_st = $this->bm->dataUpdateDB1($pay_update_str);
						}
					}
				}
		
			}
			else
			{
				return;
			}
		}
		
	// truck entry - new function - start
	//in oracle allready
	
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
			$data['login_id']=$login_id;
			$srcFlag="";
			
			$this->onlinePaymentDataUpdation();  // this function can online payment update if previously not updated. ---- Sumon ----- 11/04/2022

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
				FROM ctmsmis.tmp_oracle_assignment 
				WHERE rot_no='$rotNo' AND cont_no='$contNo'";
				
				$rtnVerifyReport = $this->bm->dataSelectDb1($verifyReport);
				
				$rslt_posYardBlock = $this->bm->dataSelectDb2($sql_posYardBlock);

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
				
				$strGetSlotCnt1 = "SELECT COUNT(*) AS rtnValue FROM ctmsmis.tmp_oracle_assignment WHERE assignmentDate='$sltAssignDt' AND Block_No='$blck' AND assignment_slot=1";
				// $SlotCnt1 = $this->bm->dataReturn($strGetSlotCnt1);
				$SlotCnt1 = $this->bm->dataReturnDb2($strGetSlotCnt1);
				$data['SlotCnt1']=$SlotCnt1;
				
				$strGetSlotCnt2 = "SELECT COUNT(*) AS rtnValue FROM ctmsmis.tmp_oracle_assignment WHERE assignmentDate='$sltAssignDt' AND Block_No='$blck' AND assignment_slot=2";
				// $SlotCnt2 = $this->bm->dataReturn($strGetSlotCnt2);
				$SlotCnt2 = $this->bm->dataReturnDb2($strGetSlotCnt2);
				$data['SlotCnt2']=$SlotCnt2;

				$strGetSlotCnt3 = "SELECT COUNT(*) AS rtnValue FROM ctmsmis.tmp_oracle_assignment WHERE assignmentDate='$sltAssignDt' AND Block_No='$blck' AND assignment_slot=3";
				// $SlotCnt3 = $this->bm->dataReturn($strGetSlotCnt3);
				$SlotCnt3 = $this->bm->dataReturnDb2($strGetSlotCnt3);
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
				
				$ipaddr = $_SERVER['REMOTE_ADDR'];
				
				$data['editVal']=$editVal;
				$data['addVal']=$addVal;
				$data['payVal']=$payVal;
				$data['payForm']=$payForm;
				$data['jettyEdit']=$jettyEdit;
				$data['ipaddr']=$ipaddr;
				
				
				
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
	
	/*function cnfTruckEntryForm($rotNo=null,$contNo=null,$cont_status=null,$assignmentType=null,$msg=null)
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
			$data['login_id']=$login_id;
			$srcFlag="";
			
			$this->onlinePaymentDataUpdation();  // this function can online payment update if previously not updated. ---- Sumon ----- 11/04/2022

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
				FROM ctmsmis.tmp_oracle_assignment 
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
				
				$strGetSlotCnt1 = "SELECT COUNT(*) AS rtnValue FROM ctmsmis.tmp_oracle_assignment WHERE assignmentDate='$sltAssignDt' AND Block_No='$blck' AND assignment_slot=1";
				$SlotCnt1 = $this->bm->dataReturn($strGetSlotCnt1);
				$data['SlotCnt1']=$SlotCnt1;
				
				$strGetSlotCnt2 = "SELECT COUNT(*) AS rtnValue FROM ctmsmis.tmp_oracle_assignment WHERE assignmentDate='$sltAssignDt' AND Block_No='$blck' AND assignment_slot=2";
				$SlotCnt2 = $this->bm->dataReturn($strGetSlotCnt2);
				$data['SlotCnt2']=$SlotCnt2;

				$strGetSlotCnt3 = "SELECT COUNT(*) AS rtnValue FROM ctmsmis.tmp_oracle_assignment WHERE assignmentDate='$sltAssignDt' AND Block_No='$blck' AND assignment_slot=3";
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
				
				$ipaddr = $_SERVER['REMOTE_ADDR'];
				
				$data['editVal']=$editVal;
				$data['addVal']=$addVal;
				$data['payVal']=$payVal;
				$data['payForm']=$payForm;
				$data['jettyEdit']=$jettyEdit;
				$data['ipaddr']=$ipaddr;
				
				
				
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
	}*/
 
/* 	function checkoutbyOnline()
	{
		$payAmt = $this->input->post('payAmt');
		$visitId = $this->input->post('trucVisitId');
		$assignmentType = $this->input->post('assignmentType');
		$cont_status = $this->input->post('cont_status');
		$contNo = $this->input->post('contNo');
		$rotNo = $this->input->post('rotNo');
		$contact = $this->input->post('contact');
		$login_id = $this->session->userdata('login_id');
		$flag='0';
		
		$find_visitStr = "SELECT count(*) as rtnValue FROM vcms_online_pay where visit_id='$visitId'";
		$checkVisit= $this->bm->dataReturnDB1($find_visitStr);
		
		if($checkVisit>0)
		{
			$sql_Requ = "SELECT MAX(vcms_online_ReqID.max_reqID) AS rtnValue FROM vcms_online_ReqID";
			$requst_id = $this->bm->dataReturnDB1($sql_Requ);
			
			$ref=$requst_id."_".$flag;
			
			$query_update = "UPDATE vcms_online_pay SET RefTranNo='$ref', requ_id='$requst_id' WHERE visit_id = '$visitId'";
			$up_st = $this->bm->dataUpdateDB1($query_update); 
			if($up_st>0)
			{
				$newReq_id=$requst_id+1;
				$newReq_id="0".$newReq_id;		// added now
				$query_update = "UPDATE vcms_online_ReqID SET max_reqID='$newReq_id'";
				$update_st = $this->bm->dataUpdateDB1($query_update); 
			}
		}
		else
		{

			$sql_Requ = "SELECT MAX(vcms_online_ReqID.max_reqID) AS rtnValue FROM vcms_online_ReqID";
			$requst_id = $this->bm->dataReturnDB1($sql_Requ);
			$ref=$requst_id."_".$flag;
			$query_txEntry = "INSERT INTO vcms_online_pay (visit_id, RefTranNo, requ_id, tr_amt, challan_amt, assign_type, cnf_login_id, allPay_st, chk_st ) VALUES ('$visitId', '$ref', '$requst_id', '50', '7.5', '$assignmentType', '$login_id', 0 , 1)";
			$st=$this->bm->dataInsertDB1($query_txEntry);
			if($st>0)
			{
				$newReq_id=$requst_id+1;
				$newReq_id="0".$newReq_id;		// added now
				$query_update = "UPDATE vcms_online_ReqID SET max_reqID='$newReq_id'";
				$update_st = $this->bm->dataUpdateDB1($query_update); 
			}
		}
		
		//return;
		$data['requst_id'] = $requst_id;
		$data['ref'] = $ref;
		$data['login_id'] = $login_id;
		$data['contact'] = $contact;
		$data['trucVisitId'] = $visitId;
		$data['flag'] = $flag;  //Single Pay
		$data['name'] = $this->session->userdata('User_Name');
		$cus_name= $this->session->userdata('User_Name');
		$data['payAmt'] = $payAmt;
		$this->onlinePay($contNo, $rotNo, $login_id, $contact, $ref, $requst_id, $payAmt, $cus_name, $assignmentType, $cont_status);
	} */	  
	
 /*	function checkoutbyOnline()
	{
		$payAmt = $this->input->post('payAmt');
		// $payAmt = 1;
		$visitId = $this->input->post('trucVisitId');
		$assignmentType = $this->input->post('assignmentType');
		$cont_status = $this->input->post('cont_status');
		$contNo = $this->input->post('contNo');
		$rotNo = $this->input->post('rotNo');
		$contact = $this->input->post('contact');
		$login_id = $this->session->userdata('login_id');
		$flag='0';
		
		$find_visitStr = "SELECT id, visit_id, date(entry_time) as ref_date, requ_id FROM vcms_online_pay WHERE visit_id='$visitId'";
		$chkVisit= $this->bm->dataSelectDB1($find_visitStr);
		
		if(count($chkVisit)>0)
		{
			$visit_id="";
			$ref_date="";
			$requ_id="";
			for($v=0; $v<count($chkVisit); $v++)
			{
				$requ_id=$chkVisit[$v]['requ_id'];
				$ref_date=$chkVisit[$v]['ref_date'];
			}
			$verify_post=$this->bm->sonaliPayVerifyJsonbody($ref_date, $requ_id);
		
				
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://spg.com.bd:6314/api/SpgService/TransactionVerification");
			//added
			curl_setopt($ch, CURLOPT_TIMEOUT, 100);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 100);
			curl_setopt($ch, CURLOPT_POST, 1 );

			//added

			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json","Authorization: OAuth 2.0 token here"));
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $verify_post);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); # KEEP IT FALSE IF YOU RUN FROM LOCAL PC

			curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
			$content = curl_exec($ch);

			$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			//print_r( $content); 
			//return; 
			$getData="";

			if($code == 200 || $code== 0)
			{
				curl_close( $ch);
				// $getData = $content;
				
				 $verifyData = json_decode($content, true );
				//echo $verifyData ='{"TransactionId":"2112122147484169","TransactionDate":"2021-12-12 14:42:51","ReferenceNo":"2147484169_0","ReferenceDate":"2021-12-12","ServiceName":"Test Portal","BrCode":"00026","ApplicantName":"HABIGANJ AGRO LIMITED","MobileNo":"01755128784","TranAmount":"57.50","StatusCode":"200","StCode":"bdtax","SpCode":"sbl_account","PayMode":"A02","PayAmount":"69","Vat":"1.55","Commission":"9.95","ScrollNo":"-P000900002"}';
				
				
				$verifyDa = explode('"',$verifyData);
				
				$TransactionId=$verifyDa[3];
				$TransactionDate=$verifyDa[7];
				$ReferenceNo=$verifyDa[11];
				$ReferenceDate=$verifyDa[15] ;
				$ServiceName=$verifyDa[19];
				$BrCode=$verifyDa[23];
				$ApplicantName=$verifyDa[27];
				$MobileNo=$verifyDa[31];
				$TranAmount=$verifyDa[35];
				$StatusCode=$verifyDa[39];
				$StCode=$verifyDa[43];
				$SpCode=$verifyDa[47];
				$PayMode=$verifyDa[51];
				$PayAmount=$verifyDa[55];
				$Vat=$verifyDa[59];
				$Commission=$verifyDa[63];
				$ScrollNo=$verifyDa[67]; 
				
				//return;
				if($StatusCode=='200')
				{
					//echo "ok";
					//return;
					$str_online_dt = "SELECT visit_id, cnf_login_id, assign_type, payer_st  FROM vcms_online_pay WHERE requ_id='$requ_id'";
					$data_pay = $this->bm->dataSelectDB1($str_online_dt);
					
					for($k = 0; $k < count($data_pay); $k++)
					{
						$visitId = $data_pay[$k]['visit_id'];
						$login_id = $data_pay[$k]['cnf_login_id'];
						$assignmentType = $data_pay[$k]['assign_type'];
						$payer_st = $data_pay[$k]['payer_st'];
					}

					$query_update = "UPDATE do_truck_details_entry SET paid_amt = '$PayAmount', paid_status = 1 , paid_method = 'online' , paid_collect_dt = NOW() WHERE id = '$visitId'";
					$update_st = $this->bm->dataUpdateDB1($query_update); 

					$query_Truck = "SELECT driver_gate_pass,assistant_gate_pass FROM do_truck_details_entry WHERE id='$visitId'";
					$data_truck = $this->bm->dataSelectDB1($query_Truck);

					$driver_gate_pass = "";
					$assistant_gate_pass = "";

					for($i=0;$i<count($data_truck);$i++){
						$driver_gate_pass = $data_truck[$i]['driver_gate_pass'];
						$assistant_gate_pass = $data_truck[$i]['assistant_gate_pass'];
					}
					$update_st=0;
					$pay_update_str = "UPDATE vcms_online_pay 
					SET  trans_id = '$TransactionId', trans_time='$TransactionDate', payAmount = '$PayAmount', tranAmount='$TranAmount', vat='$Vat', 
					commision='$Commission', PayMode='$PayMode', 
					refTranDateTime='$ReferenceDate', orgiBrCode = '$BrCode', scrollNo='$ScrollNo', TransactionStatus='$StatusCode',
					updated_by='C&F', update_time=NOW() WHERE requ_id = '$requ_id' AND visit_id = '$visitId'";
					$update_st = $this->bm->dataUpdateDB1($pay_update_str);
					if($update_st>0)
					{
						//curl_close( $ch);
						$msg = "<font size=4 color=green> Payment completed successfully.</font>";
						$this->cnfTruckEntryForm($rotNo,$contNo,$cont_status,$assignmentType,$msg);
						return;
					}
					
				}
				else
				{
					//echo "ok2";
					//return;
					$sql_Requ = "SELECT MAX(vcms_online_ReqID.max_reqID) AS rtnValue FROM vcms_online_ReqID";
					$requst_id = $this->bm->dataReturnDB1($sql_Requ);
					
					$ref=$requst_id."_".$flag;
					
					$query_update = "UPDATE vcms_online_pay SET RefTranNo='$ref', entry_time=NOW(), cnf_login_id='$login_id', requ_id='$requst_id' WHERE visit_id = '$visitId'";
					$up_st = $this->bm->dataUpdateDB1($query_update); 
					if($up_st>0)
					{
						$newReq_id=$requst_id+1;
						$newReq_id="0".$newReq_id;		// added now
						$query_update = "UPDATE vcms_online_ReqID SET max_reqID='$newReq_id'";
						$update_st = $this->bm->dataUpdateDB1($query_update); 
					}
				}
				//return;
				
			} 
			else 
			{
				curl_close( $ch);
				$msg="<font size=4 color=red><b>Network Problem. Please Try again later.</b></font>";

				$this->cnfTruckEntryForm($rotNo,$contNo,$cont_status,$assignmentType,$msg);
				return;
			}
		
		}
					
		else
		{
			
			$sql_Requ = "SELECT MAX(vcms_online_ReqID.max_reqID) AS rtnValue FROM vcms_online_ReqID";
			$requst_id = $this->bm->dataReturnDB1($sql_Requ);
			$ref=$requst_id."_".$flag;
			$query_txEntry = "INSERT INTO vcms_online_pay (visit_id, RefTranNo, entry_time, requ_id, tr_amt, challan_amt, assign_type, cnf_login_id, allPay_st, chk_st, gateway ) VALUES ('$visitId', '$ref', NOW(), '$requst_id', '50', '7.5', '$assignmentType', '$login_id', 0 , 1, 'sonali')";
			$st=$this->bm->dataInsertDB1($query_txEntry);
			if($st>0)
			{
				$newReq_id=$requst_id+1;
				$newReq_id="0".$newReq_id;		// added now
				$query_update = "UPDATE vcms_online_ReqID SET max_reqID='$newReq_id'";
				$update_st = $this->bm->dataUpdateDB1($query_update); 
			}
		}
		
		//return;
		$data['requst_id'] = $requst_id;
		$data['ref'] = $ref;
		$data['login_id'] = $login_id;
		$data['contact'] = $contact;
		$data['trucVisitId'] = $visitId;
		$data['flag'] = $flag;  //Single Pay
		$data['name'] = $this->session->userdata('User_Name');
		$cus_name= $this->session->userdata('User_Name');
		$data['payAmt'] = $payAmt;
		//$payAmt='1';
		$this->onlinePay($contNo, $rotNo, $login_id, $contact, $ref, $requst_id, $payAmt, $cus_name, $assignmentType, $cont_status);			
	} */
	
	
		function checkoutbyOnline()
	{
		$payAmt = $this->input->post('payAmt');
		// $payAmt = 1;
		$visitId = $this->input->post('trucVisitId');
		$assignmentType = $this->input->post('assignmentType');
		$cont_status = $this->input->post('cont_status');
		$contNo = $this->input->post('contNo');
		$rotNo = $this->input->post('rotNo');
		$contact = $this->input->post('contact');
		$login_id = $this->session->userdata('login_id');
		$flag='0';
		
		$find_visitStr = "SELECT id, visit_id, date(entry_time) as ref_date, requ_id FROM vcms_online_pay WHERE visit_id='$visitId'";
		$chkVisit= $this->bm->dataSelectDB1($find_visitStr);
		
		if(count($chkVisit)>0)
		{
			$visit_id="";
			$ref_date="";
			$requ_id="";
			for($v=0; $v<count($chkVisit); $v++)
			{
				$requ_id=$chkVisit[$v]['requ_id'];
				$ref_date=$chkVisit[$v]['ref_date'];
			}
			$verify_post=$this->bm->sonaliPayVerifyJsonbody($ref_date, $requ_id);
		
				
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://spg.com.bd:6314/api/SpgService/TransactionVerification");
			//added
			curl_setopt($ch, CURLOPT_TIMEOUT, 100);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 100);
			curl_setopt($ch, CURLOPT_POST, 1 );

			//added

			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json","Authorization: OAuth 2.0 token here"));
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $verify_post);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); # KEEP IT FALSE IF YOU RUN FROM LOCAL PC

			curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
			$content = curl_exec($ch);

			$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			//print_r( $content); 
			//return; 
			$getData="";

			if($code == 200 || $code== 0)
			{
				curl_close( $ch);
				// $getData = $content;
				
				 $verifyData = json_decode($content, true );
				//echo $verifyData ='{"TransactionId":"2112122147484169","TransactionDate":"2021-12-12 14:42:51","ReferenceNo":"2147484169_0","ReferenceDate":"2021-12-12","ServiceName":"Test Portal","BrCode":"00026","ApplicantName":"HABIGANJ AGRO LIMITED","MobileNo":"01755128784","TranAmount":"57.50","StatusCode":"200","StCode":"bdtax","SpCode":"sbl_account","PayMode":"A02","PayAmount":"69","Vat":"1.55","Commission":"9.95","ScrollNo":"-P000900002"}';
				
				
				$verifyDa = explode('"',$verifyData);
				
				$TransactionId=$verifyDa[3];
				$TransactionDate=$verifyDa[7];
				$ReferenceNo=$verifyDa[11];
				$ReferenceDate=$verifyDa[15] ;
				$ServiceName=$verifyDa[19];
				$BrCode=$verifyDa[23];
				$ApplicantName=$verifyDa[27];
				$MobileNo=$verifyDa[31];
				$TranAmount=$verifyDa[35];
				$StatusCode=$verifyDa[39];
				$StCode=$verifyDa[43];
				$SpCode=$verifyDa[47];
				$PayMode=$verifyDa[51];
				$PayAmount=$verifyDa[55];
				$Vat=$verifyDa[59];
				$Commission=$verifyDa[63];
				$ScrollNo=$verifyDa[67]; 
				
				//return;
				if($StatusCode=='200')
				{
					//echo "ok";
					//return;
					$str_online_dt = "SELECT visit_id, cnf_login_id, assign_type, payer_st  FROM vcms_online_pay WHERE requ_id='$requ_id'";
					$data_pay = $this->bm->dataSelectDB1($str_online_dt);
					
					for($k = 0; $k < count($data_pay); $k++)
					{
						$visitId = $data_pay[$k]['visit_id'];
						$login_id = $data_pay[$k]['cnf_login_id'];
						$assignmentType = $data_pay[$k]['assign_type'];
						$payer_st = $data_pay[$k]['payer_st'];
					}

					$query_update = "UPDATE do_truck_details_entry SET paid_amt = '$PayAmount', paid_status = 1 , paid_method = 'online' , paid_collect_dt = NOW() WHERE id = '$visitId'";
					$update_st = $this->bm->dataUpdateDB1($query_update); 

					$query_Truck = "SELECT driver_gate_pass,assistant_gate_pass FROM do_truck_details_entry WHERE id='$visitId'";
					$data_truck = $this->bm->dataSelectDB1($query_Truck);

					$driver_gate_pass = "";
					$assistant_gate_pass = "";

					for($i=0;$i<count($data_truck);$i++){
						$driver_gate_pass = $data_truck[$i]['driver_gate_pass'];
						$assistant_gate_pass = $data_truck[$i]['assistant_gate_pass'];
					}
					$update_st=0;
					$pay_update_str = "UPDATE vcms_online_pay 
					SET  trans_id = '$TransactionId', trans_time='$TransactionDate', payAmount = '$PayAmount', tranAmount='$TranAmount', vat='$Vat', 
					commision='$Commission', PayMode='$PayMode', 
					refTranDateTime='$ReferenceDate', orgiBrCode = '$BrCode', scrollNo='$ScrollNo', TransactionStatus='$StatusCode',
					updated_by='C&F', update_time=NOW() WHERE requ_id = '$requ_id' AND visit_id = '$visitId'";
					$update_st = $this->bm->dataUpdateDB1($pay_update_str);
					if($update_st>0)
					{
						//curl_close( $ch);
						$msg = "<font size=4 color=green> Payment completed successfully.</font>";
						$this->cnfTruckEntryForm($rotNo,$contNo,$cont_status,$assignmentType,$msg);
						return;
					}
					
				}
				else
				{
					//echo "ok2";
					//return;
					$sql_Requ = "SELECT MAX(vcms_online_ReqID.max_reqID) AS rtnValue FROM vcms_online_ReqID";
					$requst_id = $this->bm->dataReturnDB1($sql_Requ);
					
					$ref=$requst_id."_".$flag;
					
					$query_update = "UPDATE vcms_online_pay SET RefTranNo='$ref', entry_time=NOW(), cnf_login_id='$login_id', requ_id='$requst_id' WHERE visit_id = '$visitId'";
					$up_st = $this->bm->dataUpdateDB1($query_update); 
					if($up_st>0)
					{
						$newReq_id=$requst_id+1;
						$newReq_id="0".$newReq_id;		// added now
						$query_update = "UPDATE vcms_online_ReqID SET max_reqID='$newReq_id'";
						$update_st = $this->bm->dataUpdateDB1($query_update); 
					}
				}
				//return;
				
			} 
			else 
			{
				curl_close( $ch);
				$msg="<font size=4 color=red><b>Network Problem. Please Try again later.</b></font>";

				$this->cnfTruckEntryForm($rotNo,$contNo,$cont_status,$assignmentType,$msg);
				return;
			}
		
		}
					
		else
		{
			
			$sql_Requ = "SELECT MAX(vcms_online_ReqID.max_reqID) AS rtnValue FROM vcms_online_ReqID";
			$requst_id = $this->bm->dataReturnDB1($sql_Requ);
			$ref=$requst_id."_".$flag;
			$query_txEntry = "INSERT INTO vcms_online_pay (visit_id, RefTranNo, entry_time, requ_id, tr_amt, challan_amt, assign_type, cnf_login_id, allPay_st, chk_st, gateway ) VALUES ('$visitId', '$ref', NOW(), '$requst_id', '50', '7.5', '$assignmentType', '$login_id', 0 , 1, 'sonali')";
			$st=$this->bm->dataInsertDB1($query_txEntry);
			if($st>0)
			{
				$newReq_id=$requst_id+1;
				$newReq_id="0".$newReq_id;		// added now
				$query_update = "UPDATE vcms_online_ReqID SET max_reqID='$newReq_id'";
				$update_st = $this->bm->dataUpdateDB1($query_update); 
			}
		}
		
		//return;
		$data['requst_id'] = $requst_id;
		$data['ref'] = $ref;
		$data['login_id'] = $login_id;
		$data['contact'] = $contact;
		$data['trucVisitId'] = $visitId;
		$data['flag'] = $flag;  //Single Pay
		$data['name'] = $this->session->userdata('User_Name');
		$cus_name= $this->session->userdata('User_Name');
		$data['payAmt'] = $payAmt;
		//$payAmt='1';
		$this->onlinePay($contNo, $rotNo, $login_id, $contact, $ref, $requst_id, $payAmt, $cus_name, $assignmentType, $cont_status);			
	} 
 
	function checkoutAllbyOnline($cont,$rot,$assignmentType,$payAmt,$contact)
	{	
		$login_id = $this->session->userdata('login_id');
		$data['cont'] = $cont;
		$data['rot'] = $rot;
		$data['login_id'] = $login_id;
		$data['assignmentType'] = $assignmentType;
		//echo $assignmentType;
		//return;
		$cont_status = $this->input->post('cont_status');
		//$data['contact'] = $contact;
		$flag='1';
		
		$tot_visit= $payAmt/57.5;
		
		//echo "11111";
		
		$find_visitStr = "SELECT COUNT(*) AS rtnValue FROM vcms_online_pay WHERE vcms_online_pay.container='$cont' AND vcms_online_pay.rotation='$rot'";
		$checkVisit= $this->bm->dataReturnDB1($find_visitStr);
		//return;
		if($checkVisit>0)
		{		
			
			$sql_Requ = "SELECT MAX(vcms_online_ReqID.max_reqID) AS rtnValue FROM vcms_online_ReqID";
			$requst_id = $this->bm->dataReturnDB1($sql_Requ);
			
			$ref=$requst_id."_".$flag;
			
			$query_update = "UPDATE vcms_online_pay SET RefTranNo='$ref', requ_id='$requst_id' WHERE container='$cont' AND rotation='$rot' AND trans_id IS NULL";
			$up_st = $this->bm->dataUpdateDB1($query_update); 
			if($up_st>0)
			{
				$newReq_id=$requst_id+1;
				$newReq_id="0".$newReq_id;		// added now
				$query_update = "UPDATE vcms_online_ReqID SET max_reqID='$newReq_id'";
				$update_st = $this->bm->dataUpdateDB1($query_update); 
			}
			
			// If later Visit Id created and used allpay btn for same rotaion and container, whose previous visit ID exist.
			
					//$sql_dtl_info = "SELECT * FROM do_truck_details_entry  WHERE cont_no='$cont' AND import_rotation='$rot' AND paid_status='0'";
					$sql_dtl_info = "SELECT * FROM do_truck_details_entry WHERE cont_no='$cont' AND import_rotation='$rot' AND paid_status='0' 
								AND id NOT IN ( SELECT vcms_online_pay.visit_id FROM vcms_online_pay WHERE vcms_online_pay.container='$cont' AND vcms_online_pay.rotation='$rot')";
					$dtl_result = $this->bm->dataSelectDB1($sql_dtl_info); 
					/* echo $tot_visit.'-'.count($dtl_result);
					return; */
					if($tot_visit==count($dtl_result))
					{	
					
						for($i=0; $i<count($dtl_result); $i++)
						{
							$visitId = $dtl_result[$i]['id'];	
							
							$query_txEntry = "INSERT INTO vcms_online_pay (visit_id, RefTranNo, rotation, container, requ_id, tr_amt, challan_amt, assign_type, cnf_login_id, allPay_st, chk_st ) VALUES ('$visitId', '$ref', '$rot','$cont','$requst_id', '50', '7.5', '$assignmentType', '$login_id', 1, 1 )";
							$st=$this->bm->dataInsertDB1($query_txEntry);
						}
						
						
						if($st>0)
						{
							$newReq_id=$requst_id+1;
							$newReq_id="0".$newReq_id;		// added now
							$query_update = "UPDATE vcms_online_ReqID SET max_reqID='$newReq_id'";
							$update_st = $this->bm->dataUpdateDB1($query_update); 
						}
					}
					else
					{
						
						/* $data['requst_id'] = $requst_id;
						$data['login_id'] = $login_id;
						$data['contact'] = $contact;
						//$data['trucVisitId'] = $visitId;
						//$data['name'] = $this->session->userdata('User_Name');
						$data['payAmt'] = $payAmt;
						$data['ref'] = $ref;
						//$data['payAmt'] = $this->input->post('payAmt');

						$data['flag'] = 1;  //All Pay
						$data['name'] = $this->session->userdata('User_Name');
						$this->load->view('onlinePay', $data);
						$msg="Something Wrong. Please, Pay Seperately."; */

					} 

		}
		else
		{
			$sql_Requ = "SELECT MAX(vcms_online_ReqID.max_reqID) AS rtnValue FROM vcms_online_ReqID";
			 $requst_id = $this->bm->dataReturnDB1($sql_Requ);
			
			$ref=$requst_id."_".$flag;
			
			$sql_dtl_info = "SELECT * FROM do_truck_details_entry  WHERE cont_no='$cont' AND import_rotation='$rot' AND paid_status='0'";
			$dtl_result = $this->bm->dataSelectDB1($sql_dtl_info); 
			
			if($tot_visit==count($dtl_result))
			{	
				for($i=0; $i<count($dtl_result); $i++)
				{
					$visitId = $dtl_result[$i]['id'];
						
					$find_visitStr = "SELECT COUNT(*) AS rtnValue FROM vcms_online_pay WHERE vcms_online_pay.visit_id='$visitId'";
					$checkVisit= $this->bm->dataReturnDB1($find_visitStr);
					//return;
					if($checkVisit>0)
					{
						$query_update = "UPDATE vcms_online_pay SET  rotation='$rot', container='$cont', RefTranNo='$ref', requ_id='$requst_id', tr_amt='50', challan_amt='7.5', assign_type='$assignmentType', cnf_login_id='$login_id', allPay_st='1', chk_st='1'
						WHERE visit_id='$visitId'";
						$up_st = $this->bm->dataUpdateDB1($query_update); 						
					}
					else
					{
						$query_txEntry = "INSERT INTO vcms_online_pay (visit_id, rotation, container, RefTranNo, requ_id, tr_amt, challan_amt, assign_type, cnf_login_id, allPay_st, chk_st ) VALUES ('$visitId', '$rot','$cont', '$ref', '$requst_id', '50', '7.5', '$assignmentType', '$login_id', 1, 1 )";
						$st=$this->bm->dataInsertDB1($query_txEntry);
					}
				}
				
				
				if($st>0)
				{
					$newReq_id=$requst_id+1;
					$newReq_id="0".$newReq_id;		// added now
					$query_update = "UPDATE vcms_online_ReqID SET max_reqID='$newReq_id'";
					$update_st = $this->bm->dataUpdateDB1($query_update); 
				}
			}
			else
			{
				$msg="Something Wrong. Please, Pay Seperately.";
				echo "<font color=red size=4><b>Something Wrong. Please, Pay Seperately or approve your emergency truck. Then, try again.</b></font>";
				return;
			}
		}
		$data['requst_id'] = $requst_id;
		$data['login_id'] = $login_id;
		$data['contact'] = $contact;
		//$data['trucVisitId'] = $visitId;
		//$data['name'] = $this->session->userdata('User_Name');
		$data['payAmt'] = $payAmt;
		$data['ref'] = $ref;
		//$data['payAmt'] = $this->input->post('payAmt');
		
		$data['flag'] = 1;  //All Pay
		$cus_name= $this->session->userdata('User_Name');
		$data['name'] = $this->session->userdata('User_Name');
		//$this->load->view('onlinePay', $data);
		//$this->onlinePay($cont, $rot, $login_id, $contact, $ref, $requst_id, $payAmt, $cus_name, $assignmentType, $cont_status);
		$this->onlinePay($cont, $rot, $login_id, $contact, $ref, $requst_id, $payAmt, $cus_name, $assignmentType, $cont_status);
		
	}
	//Single Payment from TOS APP 11/17/21 By ASIF
	function checkoutbyMobileOnline()
	{
		$payAmt = "57.5";
		$visitId = "38929";
		$assignmentType = "FCL";
		$cont_status =  "FCL";
		$contNo = "XINU1354870";
		$rotNo = "2021/5100";
		$contact = "01674077457";
		$login_id = 'dsasif';
		$flag='0';
		
		$find_visitStr = "SELECT count(*) as rtnValue FROM vcms_online_pay where visit_id='$visitId'";
		$checkVisit= $this->bm->dataReturnDB1($find_visitStr);
		
		if($checkVisit>0)
		{
			$sql_Requ = "SELECT MAX(vcms_online_ReqID.max_reqID) AS rtnValue FROM vcms_online_ReqID";
			$requst_id = $this->bm->dataReturnDB1($sql_Requ);
			
			$ref=$requst_id."_".$flag;
			
			$query_update = "UPDATE vcms_online_pay SET RefTranNo='$ref', requ_id='$requst_id' WHERE visit_id = '$visitId'";
			$up_st = $this->bm->dataUpdateDB1($query_update); 
			if($up_st>0)
			{
				$newReq_id=$requst_id+1;
				$newReq_id="0".$newReq_id;		// added now
				$query_update = "UPDATE vcms_online_ReqID SET max_reqID='$newReq_id'";
				$update_st = $this->bm->dataUpdateDB1($query_update); 
			}
		}
		else
		{
			/* $sql_maxRequ = "SELECT MAX(vcms_online_pay.requ_id)+1 AS rtnValue FROM vcms_online_pay";
			$requst_id = $this->bm->dataReturnDB1($sql_maxRequ); */
			$sql_Requ = "SELECT MAX(vcms_online_ReqID.max_reqID) AS rtnValue FROM vcms_online_ReqID";
			$requst_id = $this->bm->dataReturnDB1($sql_Requ);
			$ref=$requst_id."_".$flag;
			$query_txEntry = "INSERT INTO vcms_online_pay (visit_id, RefTranNo, requ_id, tr_amt, challan_amt, assign_type, cnf_login_id, allPay_st, chk_st ) VALUES ('$visitId', '$ref', '$requst_id', '50', '7.5', '$assignmentType', '$login_id', 0 , 1)";
			$st=$this->bm->dataInsertDB1($query_txEntry);
			if($st>0)
			{
				$newReq_id=$requst_id+1;
				$newReq_id="0".$newReq_id;		// added now
				$query_update = "UPDATE vcms_online_ReqID SET max_reqID='$newReq_id'";
				$update_st = $this->bm->dataUpdateDB1($query_update); 
			}
		}
		
		//return;
		$data['requst_id'] = $requst_id;
		$data['ref'] = $ref;
		$data['login_id'] = $login_id;
		$data['contact'] = $contact;
		$data['trucVisitId'] = $visitId;
		$data['flag'] = $flag;  //Single Pay
		$data['name'] = $this->session->userdata('User_Name');
		$cus_name= $this->session->userdata('User_Name');
		$data['payAmt'] = $payAmt;
	//	$this->load->view('onlinePay', $data);
		$this->onlinePay($contNo, $rotNo, $login_id, $contact, $ref, $requst_id, $payAmt, $cus_name, $assignmentType, $cont_status);
	}
	
	
	function onlinePay($cont=null, $rot=null, $login_id=null, $contact=null, $ref=null, $requst_id=null, $payAmt=null, $name=null, $assignmentType=null, $cont_status=null)
	{
		// "strAmount": "'.$payAmt.'",
		//$payAmt = 1;
		$tranTime=$payAmt/57.5;
		//$tranTime=1;
		$dt=date('Y-m-d');
		//$ref=$requst_id."_".$flag;
		$post_data = '{
				"AccessUser": {
				"userName" : "CtGPoRt2015",
				"password" : "XporLocDbs$TghDl23@34t97"
				},
				"strUserId" : "CtGPoRt2015",
				"strPassKey": "XporLocDbs$TghDl23@34t97",
				"strRequestId": "'.$requst_id.'",
				"strAmount": "'.$payAmt.'",
				"strTranDate": "'.$dt.'",
				"strAccounts": "1113300250311-0820102000468"
				}';
		//  echo $post_data;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://spg.com.bd:6314/api/SpgService/GetSessionKey");
		//added
		curl_setopt($ch, CURLOPT_TIMEOUT, 100);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 100);
		curl_setopt($ch, CURLOPT_POST, 1 );

		//added

		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json","Authorization: OAuth 2.0 token here"));
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); # KEEP IT FALSE IF YOU RUN FROM LOCAL PC

		//$result = curl_exec($ch);
		//curl_close($ch);
		/*  echo $result; 
		var_dump($result);
		return;  */


		curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
		$content = curl_exec($ch);

		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		// echo $code;
		//echo $content;
		//$code=0;;

		//echo "<br>";
		// print_r( $content); 
		//return; 
		$sessionData="";

		if($code == 200)
		{
			curl_close( $ch);
			$sessionData = $content;
		} 
		else 
		{
			curl_close( $ch);
			$msg="<font size=4 color=red><b>Network Problem. Please Try again later.</b></font>";
			//echo $rot.'-'.$cont.'-'.$cont_status.'-'.$assignmentType.'-'.$msg;
		//	return;
			$orgTypeId = $this->session->userdata('org_Type_id');

			if(is_null($orgTypeId))
			{
				$data['title']="Truck Entry for Users";
				$data['msg']="<font size='4' color='red'>Network Problem. Please Try again later.</font>";

				$this->load->view('cssAssets');
				// $this->load->view('headerTop');
				// $this->load->view('sidebar');
				$this->load->view('truckEntryForUsers',$data);
				$this->load->view('jsAssets');
				return;
			}
			else if($orgTypeId == 79)
			{
				$data['title']= "Yard Delivery";
				$data['msg'] = $msg;
				$this->load->view('cssAssetsList');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('truckEntryByDriver_Yard',$data);
				$this->load->view('jsAssetsList');
			}else
			{
				$this->cnfTruckEntryForm($rot,$cont,$cont_status,$assignmentType,$msg);
			}
			return;
		/* 	echo "FAILED TO CONNECT  API";
			exit; */ 
		}
		/* echo $sessionData ;
		return; */
		$data = json_decode($sessionData, true );
		//echo $data;
		//echo "<br>";
		$dataPart = explode('"',$data);
		//print_r( $dataPart) ;

		$skey=$dataPart[3];
		//echo $skey;
		//return;	
		$cur_time=date('Y-m-d H:i:s', time());

		$tranAmt=$tranTime*50;
		$tranChallan=$tranTime*7.5;

	// "TranAmount": "'.$payAmt.'",
	// "CrAmount": "'.$tranChallan.'",
	// "CrAmount": "'.$tranAmt.'",
		$post_data2='{
				"Authentication": {
				"ApiAccessUserId": "CtGPoRt2015",
				"ApiAccessPassKey":"'.$skey.'"
				},
				"ReferenceInfo": {
				"RequestId": "'.$requst_id.'",
				"RefTranNo": "'.$ref.'",
				"RefTranDateTime": "'.$cur_time.'",
				"ReturnUrl": "http://cpatos.gov.bd/pcs/index.php/ShedBillController/onlinePaymentSuccess",
				"ReturnMethod": "POST",
				"TranAmount": "'.$payAmt.'",
				"ContactName": "'.$name.'",
				"ContactNo": "'.$contact.'",
				"PayerId": "'.$login_id.'",
				"Address": "applicentAddress"
				},
				"CreditInformations": [
				{
				"SLNO": "1",
				"CreditAccount": "1113300250311",
				"CrAmount": "'.$tranChallan.'",
				"Purpose": "CHL",
				"Onbehalf": "CTGPORTCHL"
				},
				{
				"SLNO": "2",
				"CreditAccount": "0820102000468",                                                                                                                                                                                          
				"CrAmount": "'.$tranAmt.'",
				"Purpose": "TRN",
				"Onbehalf": "CTGPORT"
				}
				]
				}';

		//echo $post_data2;
		//echo '\r\n';
		//return;
		$handle = curl_init();
		curl_setopt($handle, CURLOPT_URL, "https://spg.com.bd:6314/api/SpgService/PaymentByPortal");
		//added
		curl_setopt($handle, CURLOPT_TIMEOUT, 30);
		curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($handle, CURLOPT_POST, 1 );


		//added

		curl_setopt($handle, CURLOPT_HTTPHEADER, array("Content-Type: application/json","Authorization: OAuth 2.0 token here"));
		curl_setopt($handle, CURLOPT_POST, 1);
		curl_setopt($handle, CURLOPT_POSTFIELDS, $post_data2);

		curl_setopt($handle, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE); # KEEP IT FALSE IF YOU RUN FROM LOCAL PC


		$content2 = curl_exec($handle);

		$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

		//if($code == 200 && !( curl_errno($handle))) {
		if($code == 200)
			{
			curl_close( $handle);
			$getData = $content2;
			} 
		else 
			{
			curl_close( $handle);
			$msg="<font size=4 color=red><b>Network Problem. Please Try again later.</b></font>";
			//echo $rot.'-'.$cont.'-'.$cont_status.'-'.$assignmentType.'-'.$msg;
		//	return;
			$orgTypeId = $this->session->userdata('org_Type_id');

			if(is_null($orgTypeId))
			{
				$data['title']="Truck Entry for Users";
				$data['msg']="<font size='4' color='red'>Network Problem. Please Try again later.</font>";

				$this->load->view('cssAssets');
				// $this->load->view('headerTop');
				// $this->load->view('sidebar');
				$this->load->view('truckEntryForUsers',$data);
				$this->load->view('jsAssets');
				return;
			}
			else if($orgTypeId == 79)
			{
				$data['title']= "Yard Delivery";
				$data['msg'] = $msg;
				$this->load->view('cssAssetsList');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('truckEntryByDriver_Yard',$data);
				$this->load->view('jsAssetsList');
			}else
			{
				$this->cnfTruckEntryForm($rot,$cont,$cont_status,$assignmentType,$msg);
			}
			return;
			}
		$session_token = json_decode($getData, true );
		//echo $session_token ;
		//echo "<br/>";
		$token_str = explode('"',$session_token);
		//echo $token_str;
		//echo "<br/>";
		$token=$token_str[7];
		//echo $token;
		//return;

		$direct_api_url= "https://spg.com.bd:6313/SpgLanding/SpgLanding/".$token;
		header('Location:'.$direct_api_url);  
		exit;

	}
	
	// for backup
	function onlinePay_2($cont=null, $rot=null, $login_id=null, $contact=null, $ref=null, $requst_id=null, $payAmt=null, $name=null, $assignmentType=null, $cont_status=null)
	{
		$tranTime=$payAmt/57.5;
		$dt=date('Y-m-d');
		//$ref=$requst_id."_".$flag;
		$post_data = '{
				"AccessUser": {
				"userName" : "bdtaxUser2014",
				"password" : "duUserPayment2014"
				},
				"strUserId" : "bdtaxUser2014",
				"strPassKey": "duUserPayment2014",
				"strRequestId": "'.$requst_id.'",
				"strAmount": "'.$payAmt.'",
				"strTranDate": "'.$dt.'",
				"strAccounts": "1110000018754-0002634271324"
				}';
		//echo $post_data;
		//return; 
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://spg.sblesheba.com:6314/api/SpgService/GetSessionKey");
		//added
		curl_setopt($ch, CURLOPT_TIMEOUT, 100);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 100);
		curl_setopt($ch, CURLOPT_POST, 1 );

		//added

		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json","Authorization: OAuth 2.0 token here"));
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); # KEEP IT FALSE IF YOU RUN FROM LOCAL PC

		//$result = curl_exec($ch);
		//curl_close($ch);
		/*  echo $result; 
		var_dump($result);
		return;  */


		curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
		$content = curl_exec($ch);

		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		//echo $code;
		//echo $content;
		//$code=0;;

		//echo "<br>";
		//print_r( $content); 
		//return; 
		$sessionData="";

		if($code == 200)
		{
			curl_close( $ch);
			$sessionData = $content;
		} 
		else 
		{
			curl_close( $ch);
			$msg="<font size=4 color=red><b>Network Problem. Please Try again later.</b></font>";
			//echo $rot.'-'.$cont.'-'.$cont_status.'-'.$assignmentType.'-'.$msg;
		//	return;
			$this->cnfTruckEntryForm($rot,$cont,$cont_status,$assignmentType,$msg);
			return;
		/* 	echo "FAILED TO CONNECT  API";
			exit; */ 
		}
		/* echo $sessionData ;
		return; */
		$data = json_decode($sessionData, true );
		//echo $data;
		//echo "<br>";
		$dataPart = explode('"',$data);
		//print_r( $dataPart) ;

		$skey=$dataPart[3];
		//echo $skey;
		//return;	
		$cur_time=date('Y-m-d H:i:s', time());

		$tranAmt=$tranTime*50;
		$tranChallan=$tranTime*7.5;

		$post_data2='{
				"Authentication": {
				"ApiAccessUserId": "bdtaxUser2014",
				"ApiAccessPassKey":"'.$skey.'"
				},
				"ReferenceInfo": {
				"RequestId": "'.$requst_id.'",
				"RefTranNo": "'.$ref.'",
				"RefTranDateTime": "'.$cur_time.'",
				"ReturnUrl": "http://cpatos.gov.bd/pcs/index.php/ShedBillController/onlinePaymentSuccess",
				"ReturnMethod": "POST",
				"TranAmount": "'.$payAmt.'",
				"ContactName": "'.$name.'",
				"ContactNo": "'.$contact.'",
				"PayerId": "'.$login_id.'",
				"Address": "applicentAddress"
				},
				"CreditInformations": [
				{
				"SLNO": "1",
				"CreditAccount": "1110000018754",
				"CrAmount": "'.$tranChallan.'",
				"Purpose": "CHL",
				"Onbehalf": "Test"
				},
				{
				"SLNO": "2",
				"CreditAccount": "0002634271324",
				"CrAmount": "'.$tranAmt.'",
				"Purpose": "TRN",
				"Onbehalf": "Test"
				}
				]
				}';

		//echo $post_data2;
		//echo '\r\n';
		//return;
		$handle = curl_init();
		curl_setopt($handle, CURLOPT_URL, "https://spg.sblesheba.com:6314/api/SpgService/PaymentByPortal");
		//added
		curl_setopt($handle, CURLOPT_TIMEOUT, 30);
		curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($handle, CURLOPT_POST, 1 );


		//added

		curl_setopt($handle, CURLOPT_HTTPHEADER, array("Content-Type: application/json","Authorization: OAuth 2.0 token here"));
		curl_setopt($handle, CURLOPT_POST, 1);
		curl_setopt($handle, CURLOPT_POSTFIELDS, $post_data2);

		curl_setopt($handle, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE); # KEEP IT FALSE IF YOU RUN FROM LOCAL PC


		$content2 = curl_exec($handle);

		$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

		//if($code == 200 && !( curl_errno($handle))) {
		if($code == 200)
			{
			curl_close( $handle);
			$getData = $content2;
			} 
		else 
			{
			curl_close( $handle);
			$msg="<font size=4 color=red><b>Network Problem. Please Try again later.</b></font>";
			//echo $rot.'-'.$cont.'-'.$cont_status.'-'.$assignmentType.'-'.$msg;
		//	return;
			$this->cnfTruckEntryForm($rot,$cont,$cont_status,$assignmentType,$msg);
			return;
			}
		$session_token = json_decode($getData, true );
		//echo $session_token ;
		//echo "<br/>";
		$token_str = explode('"',$session_token);
		//echo $token_str;
		//echo "<br/>";
		$token=$token_str[7];
		//echo $token;
		//return;

		$direct_api_url= "https://spg.sblesheba.com:6313/SpgLanding/SpgLanding/".$token;
		header('Location:'.$direct_api_url);  
		exit;

	}
	
	// function onlinePaymentSuccess()
	// {	
	// 	$getData = file_get_contents('php://input');

	// 	$myXMLData = urldecode ($getData);
	// 	//header("Content-Type: application/xml");
	// 	//echo '<br/>';
	// 	@$myXMLData=str_replace("Request=","",$myXMLData);

	// 	//echo @$myXMLData;

	// 	@$xml = simplexml_load_string($myXMLData);
	// 	if ($xml === false) {
	// 	echo "Failed loading XML: ";
	// 	foreach(libxml_get_errors() as $error) {
	// 	//echo "<br>", $error->message;
	// 	}
	// 	} else {
	// 	//print_r($xml);
	// 	}
	// 	$ApiAccessUserId=$xml->ApiAccessUserId;
	// 	$TransactionId=$xml->TransactionId;
	// 	$TranDateTime=$xml->TranDateTime;
	// 	$RefTranNo=$xml->RefTranNo;
	// 	$RefTranDateTime=$xml->RefTranDateTime;
	// 	$TranAmount=$xml->TranAmount;
	// 	$PayAmount=$xml->PayAmount;
	// 	$PayMode=$xml->PayMode;
	// 	$OrgiBrCode=$xml->OrgiBrCode;
	// 	$StatusMsg=$xml->StatusMsg;
	// 	$Vat=$xml->Vat;
	// 	$Commission=$xml->Commission;
	// 	$TransactionStatus=$xml->TransactionStatus;
	// 	$ScrollNo=$xml->ScrollNo;
		
	// 	$data = explode("_",$RefTranNo);
	// 	$requst_id = $data[0];
	// 	$flag = $data[1];
	// 	$payFlag = "";

		
	// 	//echo $ApiAccessUserId.'-'.$TransactionId.'-'.$TranAmount.'-'.$StatusMsg.'-'.$flag.'-'.$requst_id;
		
	// 	if( $PayMode != "A01" && $TransactionStatus == "200")
	// 	{
	// 		$payFlag = 1; // payment success
			
	// 		if($flag == 0)		// single pay ???
	// 		{
				
	// 			$str_online_dt = "SELECT visit_id, cnf_login_id, assign_type, payer_st  FROM vcms_online_pay WHERE requ_id='$requst_id'";
	// 			$data_pay = $this->bm->dataSelectDB1($str_online_dt);
				
	// 			for($k = 0; $k < count($data_pay); $k++)
	// 			{
	// 				$visitId = $data_pay[$k]['visit_id'];
	// 				$login_id = $data_pay[$k]['cnf_login_id'];
	// 				$assignmentType = $data_pay[$k]['assign_type'];
	// 				$payer_st = $data_pay[$k]['payer_st'];
	// 			}

	// 		 	$query_update = "UPDATE do_truck_details_entry SET paid_amt = '$PayAmount', paid_status = 1 , paid_method = 'online' , paid_collect_dt = NOW() WHERE id = '$visitId'";
	// 			$update_st = $this->bm->dataUpdateDB1($query_update); 

	// 			$query_Truck = "SELECT driver_gate_pass,assistant_gate_pass FROM do_truck_details_entry WHERE id='$visitId'";
	// 			$data_truck = $this->bm->dataSelectDB1($query_Truck);

	// 			$driver_gate_pass = "";
	// 			$assistant_gate_pass = "";

	// 			for($i=0;$i<count($data_truck);$i++){
	// 				$driver_gate_pass = $data_truck[$i]['driver_gate_pass'];
	// 				$assistant_gate_pass = $data_truck[$i]['assistant_gate_pass'];
	// 			}
    //       	//$update_st=1;
	// 			if($update_st == 1){
	// 				$url = "http://10.1.100.105:8095/tosevent.php?VISITNO=".$visitId."&EVENT=ISSUE&DRIVER=".$driver_gate_pass."&HELPER=".$assistant_gate_pass;
	// 				$json = file_get_contents($url);
	// 				$obj = json_decode($json);
	// 			}
	// 			$pay_update_str = "UPDATE vcms_online_pay 
	// 			SET  trans_id = '$TransactionId', trans_time='$TranDateTime', payAmount = '$PayAmount', tranAmount='$TranAmount', vat='$Vat', 
	// 			commision='$Commission', PayMode='$PayMode', statusMsg='$StatusMsg',
	// 			refTranDateTime='$RefTranDateTime', orgiBrCode = '$OrgiBrCode', scrollNo='$ScrollNo', TransactionStatus='$TransactionStatus',
	// 			updated_by='C&F', update_time=NOW() WHERE requ_id = '$requst_id' AND visit_id = '$visitId'";
	// 			$update_st = $this->bm->dataUpdateDB1($pay_update_str);
				
	// 		}
	// 		else if($flag == 1)
	// 		{
				
	// 			 $str_online_dt = "SELECT visit_id, cnf_login_id, assign_type  FROM vcms_online_pay WHERE requ_id='$requst_id'";
	// 			$data_pay = $this->bm->dataSelectDB1($str_online_dt);
				
	// 			for($k = 0; $k < count($data_pay); $k++)
	// 			{
	// 				$visitId = $data_pay[$k]['visit_id'];
	// 				$login_id = $data_pay[$k]['cnf_login_id'];
	// 				$assignmentType = $data_pay[$k]['assign_type'];
					
	// 				//Equally all amount divided
	// 				//$PayAmount_ind=$PayAmount/count($data_pay)
	// 				//$TranAmount_ind=$TranAmount/count($data_pay)

	// 			 	$query_update = "UPDATE do_truck_details_entry SET paid_amt = '57.50', paid_status = 1 , paid_method = 'online' , paid_collect_dt = NOW() WHERE id = '$visitId'";
	// 				$update_st = $this->bm->dataUpdateDB1($query_update); 

	// 				$query_Truck = "SELECT driver_gate_pass,assistant_gate_pass FROM do_truck_details_entry WHERE id='$visitId'";
	// 				$data_truck = $this->bm->dataSelectDB1($query_Truck);

	// 				$driver_gate_pass = "";
	// 				$assistant_gate_pass = "";
					
					
	// 			 	$pay_update_str = "UPDATE vcms_online_pay
	// 				SET  trans_id = '$TransactionId', trans_time='$TranDateTime', payAmount = '$PayAmount', tranAmount='$TranAmount', vat='$Vat', commision='$Commission', PayMode='$PayMode', statusMsg='$StatusMsg',
	// 				refTranDateTime='$RefTranDateTime', orgiBrCode = '$OrgiBrCode', scrollNo='$ScrollNo', TransactionStatus='$TransactionStatus', updated_by='C&F', update_time=NOW()
	// 				WHERE requ_id = '$requst_id' AND visit_id = '$visitId'";
	// 				$update_st = $this->bm->dataUpdateDB1($pay_update_str);

	// 				for($i=0;$i<count($data_truck);$i++){
	// 					$driver_gate_pass = $data_truck[$i]['driver_gate_pass'];
	// 					$assistant_gate_pass = $data_truck[$i]['assistant_gate_pass'];
	// 				}
	// 				//$update_st=1;
	// 				 if($update_st == 1){
	// 					$url = "http://10.1.100.105:8095/tosevent.php?VISITNO=".$visitId."&EVENT=ISSUE&DRIVER=".$driver_gate_pass."&HELPER=".$assistant_gate_pass;
	// 					$json = file_get_contents($url);
	// 					$obj = json_decode($json);
	// 				} 
					
					
				
	// 			}
				


	// 			/* $query_update = "UPDATE do_truck_details_entry SET paid_amt = '$amount', paid_status = 1 , paid_method = 'online' , paid_collect_dt = NOW() WHERE cont_no = '$cont' AND import_rotation = '$rot' AND paid_status = 0";
	// 			$this->bm->dataUpdateDB1($query_update); */

	// 		}
	// 		//echo "  STATUS: ".$status."- TRANSACTION DATE: ".$tran_date."- TRANSACTION ID: ".$tran_id."- PAYMENT TYPE: ".$card_type;

	// 	}
	// 	else
	// 	{
	// 		$payFlag = 0; // payment declined
	// 		$str_online_dt = "SELECT visit_id, cnf_login_id, assign_type, payer_st  FROM vcms_online_pay WHERE requ_id='$requst_id'";
	// 		$data_pay = $this->bm->dataSelectDB1($str_online_dt);
			
	// 		for($k = 0; $k < count($data_pay); $k++)
	// 		{
	// 			$visitId = $data_pay[$k]['visit_id'];
	// 			$login_id = $data_pay[$k]['cnf_login_id'];
	// 			$assignmentType = $data_pay[$k]['assign_type'];
	// 			$payer_st = $data_pay[$k]['payer_st'];
	// 		}
	// 	}

	// 	if($payer_st == 1){
	// 		$driverUserQuery = "SELECT drv_login_id FROM do_truck_details_entry WHERE id = '$visitId'";
	// 		$driverUserRslt = $this->bm->dataSelectDB1($driverUserQuery);
	// 		for($l = 0; $l<count($driverUserRslt); $l++){
	// 			$login_id = $driverUserRslt[$l]['drv_login_id'];
	// 		}
	// 	}

	// 	//Storing Session  - start
	// 	$result=$this->db->query("select *,md5(new_pass) as dpass,IF(DATEDIFF(Expire_date,NOW())<=0,1,IF(Expire_date IS NULL,1,0)) AS isexpired from users where login_id='$login_id'");
	// 	//echo "select *,md5(new_pass) as dpass from users where login_id='$username'";
	// 	if($result->num_rows() > 0)
	// 	{
	// 		$row = $result->row();
	// 		$mdata=$row->org_Type_id;
	// 		$mdatap=$row->login_password;
	// 		$userPass1=$row->dpass;					
	// 	}

	// 	$mdata_org="";
	// 	$result_org=$this->db->query("select * from tbl_org_types where id='$mdata'");
	// 	//echo "select * from tbl_org_types where id='$mdata'";
	// 	if($result_org->num_rows() > 0)
	// 	{
	// 		$row_org =$result_org->row();
	// 		$mdata_org=$row_org->Org_Type;
	// 	}

	// 	$mdata_license="";
	// 	$mdata_Organization_Name="";
	// 	$result_license=$this->db->query("select * from organization_profiles where id='$row->org_id'");
	// 	//echo ("select * from organization_profiles where id='$row->org_id'");
	// 	if($result_license->num_rows()>0)
	// 	{
	// 		$row_license =$result_license->row();
	// 		$mdata_license=$row_license->License_No;
	// 		$mdata_Organization_Name=$row_license->Organization_Name;
	// 	}

	// 	if($payer_st == 1)
	// 	{
	// 		$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>79,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
	// 		'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
	// 		'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));
	// 	}
	// 	else
	// 	{
	// 		$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>2,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
	// 		'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
	// 		'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));
	// 	}


	// 	//Storing Session  - End

		
	// 	$rotNo = "";
	// 	$contNo = "";
	// 	$verify_info_fcl_id = "";
	// 	$verify_other_data_id = "";
	// 	 $query_contRot = "SELECT import_rotation,cont_no,verify_info_fcl_id,verify_other_data_id FROM do_truck_details_entry WHERE id = '$visitId'";
	// 	$rslt_contRot = $this->bm->dataSelectDB1($query_contRot);
		
	// 	for($i=0;$i<count($rslt_contRot);$i++){
	// 		$rotNo = $rslt_contRot[$i]['import_rotation'];
	// 		$contNo = $rslt_contRot[$i]['cont_no'];
	// 		$verify_info_fcl_id = $rslt_contRot[$i]['verify_info_fcl_id'];
	// 		$verify_other_data_id = $rslt_contRot[$i]['verify_other_data_id'];
	// 	}
	// 	// $rotNo.'--'.$contNo.'--'.$verify_info_fcl_id.'--'.$verify_other_data_id;
	// 	$cont_status = "";

	// 	if($verify_other_data_id == ""){
	// 		$cont_status = "FCL";
	// 	}
	// 	else if($verify_info_fcl_id == "")
	// 	{
	// 		$cont_status = "LCL";
	// 	}

	// 	$msg = "<font size=4 color=green> Payment completed successfully.</font>";
	 
	// 	//echo '<pre>'; print_r($this->session->all_userdata());
	// 	if($payer_st == 1)
	// 	{
	// 		if($payFlag == 1)
	// 		{
	// 			$msg = "<font size='4' color='green'> Payment completed successfully for the visit Id: {$visitId}</font>";

	// 			$smstext = urlencode("Payment successfully completed for the visit Id: {$visitId}");
	// 			$mobile = $this->session->userdata('login_id');

	// 			$this->bm->sendSMS($mobile, $smstext);
	// 		}
	// 		else
	// 		{
	// 			$msg = "<font size='4' color='red'> Payment declined for the visit Id: {$visitId}</font>";
	// 		}
			
	// 		$data['msg'] = $msg;
	// 		$data['title']= "Yard Delivery";

	// 		$this->load->view('cssAssetsList');
	// 		$this->load->view('headerTop');
	// 		$this->load->view('sidebar');
	// 		$this->load->view('truckEntryByDriver_Yard',$data);
	// 		$this->load->view('jsAssetsList');
	// 	}
	// 	else
	// 	{
	// 		$this->cnfTruckEntryForm($rotNo,$contNo,$cont_status,$assignmentType,$msg);
	// 	}

	// }

	function onlinePaymentSuccess()
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
		$ScrollNo=$xml->ScrollNo;
		
		$data = explode("_",$RefTranNo);
		$requst_id = $data[0];
		$flag = $data[1];
		$payFlag = "";

		
		//echo $ApiAccessUserId.'-'.$TransactionId.'-'.$TranAmount.'-'.$StatusMsg.'-'.$flag.'-'.$requst_id;
		
		if( $PayMode != "A01" && $TransactionStatus == "200")
		{
			$payFlag = 1; // payment success
			
			if($flag == 0)		// single pay ???
			{
				
				$str_online_dt = "SELECT visit_id, cnf_login_id, assign_type, payer_st  FROM vcms_online_pay WHERE requ_id='$requst_id'";
				$data_pay = $this->bm->dataSelectDB1($str_online_dt);
				
				for($k = 0; $k < count($data_pay); $k++)
				{
					$visitId = $data_pay[$k]['visit_id'];
					$login_id = $data_pay[$k]['cnf_login_id'];
					$assignmentType = $data_pay[$k]['assign_type'];
					$payer_st = $data_pay[$k]['payer_st'];
				}

			 	$query_update = "UPDATE do_truck_details_entry SET paid_amt = '$PayAmount', paid_status = 1 , paid_method = 'online' , paid_collect_dt = NOW() WHERE id = '$visitId'";
				$update_st = $this->bm->dataUpdateDB1($query_update); 

				$query_Truck = "SELECT driver_gate_pass,assistant_gate_pass FROM do_truck_details_entry WHERE id='$visitId'";
				$data_truck = $this->bm->dataSelectDB1($query_Truck);

				$driver_gate_pass = "";
				$assistant_gate_pass = "";

				for($i=0;$i<count($data_truck);$i++){
					$driver_gate_pass = $data_truck[$i]['driver_gate_pass'];
					$assistant_gate_pass = $data_truck[$i]['assistant_gate_pass'];
				}
          	//$update_st=1;
				if($update_st == 1){
					$url = "http://10.1.100.105:8095/tosevent.php?VISITNO=".$visitId."&EVENT=ISSUE&DRIVER=".$driver_gate_pass."&HELPER=".$assistant_gate_pass;
					$json = file_get_contents($url);
					$obj = json_decode($json);
				}
				$pay_update_str = "UPDATE vcms_online_pay 
				SET  trans_id = '$TransactionId', trans_time='$TranDateTime', payAmount = '$PayAmount', tranAmount='$TranAmount', vat='$Vat', 
				commision='$Commission', PayMode='$PayMode', statusMsg='$StatusMsg',
				refTranDateTime='$RefTranDateTime', orgiBrCode = '$OrgiBrCode', scrollNo='$ScrollNo', TransactionStatus='$TransactionStatus',
				updated_by='C&F', update_time=NOW() WHERE requ_id = '$requst_id' AND visit_id = '$visitId'";
				$update_st = $this->bm->dataUpdateDB1($pay_update_str);
				
			}
			else if($flag == 1)
			{
				
				$str_online_dt = "SELECT visit_id, cnf_login_id, assign_type  FROM vcms_online_pay WHERE requ_id='$requst_id'";
				$data_pay = $this->bm->dataSelectDB1($str_online_dt);
				
				for($k = 0; $k < count($data_pay); $k++)
				{
					$visitId = $data_pay[$k]['visit_id'];
					$login_id = $data_pay[$k]['cnf_login_id'];
					$assignmentType = $data_pay[$k]['assign_type'];
					
					//Equally all amount divided
					//$PayAmount_ind=$PayAmount/count($data_pay)
					//$TranAmount_ind=$TranAmount/count($data_pay)

				 	$query_update = "UPDATE do_truck_details_entry SET paid_amt = '57.50', paid_status = 1 , paid_method = 'online' , paid_collect_dt = NOW() WHERE id = '$visitId'";
					$update_st = $this->bm->dataUpdateDB1($query_update); 

					$query_Truck = "SELECT driver_gate_pass,assistant_gate_pass FROM do_truck_details_entry WHERE id='$visitId'";
					$data_truck = $this->bm->dataSelectDB1($query_Truck);

					$driver_gate_pass = "";
					$assistant_gate_pass = "";
					
					
				 	$pay_update_str = "UPDATE vcms_online_pay
					SET  trans_id = '$TransactionId', trans_time='$TranDateTime', payAmount = '$PayAmount', tranAmount='$TranAmount', vat='$Vat', commision='$Commission', PayMode='$PayMode', statusMsg='$StatusMsg',
					refTranDateTime='$RefTranDateTime', orgiBrCode = '$OrgiBrCode', scrollNo='$ScrollNo', TransactionStatus='$TransactionStatus', updated_by='C&F', update_time=NOW()
					WHERE requ_id = '$requst_id' AND visit_id = '$visitId'";
					$update_st = $this->bm->dataUpdateDB1($pay_update_str);

					for($i=0;$i<count($data_truck);$i++){
						$driver_gate_pass = $data_truck[$i]['driver_gate_pass'];
						$assistant_gate_pass = $data_truck[$i]['assistant_gate_pass'];
					}
					//$update_st=1;
					 if($update_st == 1){
						$url = "http://10.1.100.105:8095/tosevent.php?VISITNO=".$visitId."&EVENT=ISSUE&DRIVER=".$driver_gate_pass."&HELPER=".$assistant_gate_pass;
						$json = file_get_contents($url);
						$obj = json_decode($json);
					}	
				
				}
				/* $query_update = "UPDATE do_truck_details_entry SET paid_amt = '$amount', paid_status = 1 , paid_method = 'online' , paid_collect_dt = NOW() WHERE cont_no = '$cont' AND import_rotation = '$rot' AND paid_status = 0";
				$this->bm->dataUpdateDB1($query_update); */

			}
			else if($flag == 3)
			{
				$str_online_dt = "SELECT visit_id  FROM vcms_online_pay WHERE requ_id='$requst_id'";
				$data_pay = $this->bm->dataSelectDB1($str_online_dt);
				
				for($k = 0; $k < count($data_pay); $k++)
				{
					$visitId = $data_pay[$k]['visit_id'];

					$query_update = "UPDATE do_truck_details_entry SET paid_amt = '$PayAmount', paid_status = 1 , paid_method = 'online' , paid_collect_dt = NOW() WHERE id = '$visitId'";
					$update_st = $this->bm->dataUpdateDB1($query_update);

					$pay_update_str = "UPDATE vcms_online_pay
					SET  trans_id = '$TransactionId', trans_time='$TranDateTime', payAmount = '$PayAmount', tranAmount='$TranAmount', vat='$Vat', commision='$Commission', PayMode='$PayMode', statusMsg='$StatusMsg',
					refTranDateTime='$RefTranDateTime', orgiBrCode = '$OrgiBrCode', scrollNo='$ScrollNo', TransactionStatus='$TransactionStatus', updated_by='C&F', update_time=NOW()
					WHERE requ_id = '$requst_id' AND visit_id = '$visitId'";
					$update_st = $this->bm->dataUpdateDB1($pay_update_str);

					$mobile = null;
					$mbl_query = "SELECT paid_collect_by FROM do_truck_details_entry WHERE id='$visitId'";
					$data_mbl = $this->bm->dataSelectDB1($mbl_query);
					
					for($l = 0; $l < count($data_mbl); $l++)
					{
						$mobile = $data_mbl[$l]['paid_collect_by'];
					}

					$smstext = urlencode("Payment successfully completed for the visit Id: {$visitId}");

					$this->bm->sendSMS($mobile, $smstext);

				}
			}
			//echo "  STATUS: ".$status."- TRANSACTION DATE: ".$tran_date."- TRANSACTION ID: ".$tran_id."- PAYMENT TYPE: ".$card_type;

		}
		else
		{
			$payFlag = 0; // payment declined
			$visitId = null;
			$login_id = null;
			$assignmentType = null;
			$payer_st = null;

			if($flag == 0|| $flag == 1)
			{
				$str_online_dt = "SELECT visit_id, cnf_login_id, assign_type, payer_st  FROM vcms_online_pay WHERE requ_id='$requst_id'";
				$data_pay = $this->bm->dataSelectDB1($str_online_dt);
				
				for($k = 0; $k < count($data_pay); $k++)
				{
					$visitId = $data_pay[$k]['visit_id'];
					$login_id = $data_pay[$k]['cnf_login_id'];
					$assignmentType = $data_pay[$k]['assign_type'];
					$payer_st = $data_pay[$k]['payer_st'];
				}
			}
			else if($flag == 3)
			{
				$str_online_dt = "SELECT visit_id  FROM vcms_online_pay WHERE requ_id='$requst_id'";
				$data_pay = $this->bm->dataSelectDB1($str_online_dt);
				
				for($k = 0; $k < count($data_pay); $k++)
				{
					$visitId = $data_pay[$k]['visit_id'];
				}
			}
		}

		if($payer_st == 1){
			$driverUserQuery = "SELECT drv_login_id FROM do_truck_details_entry WHERE id = '$visitId'";
			$driverUserRslt = $this->bm->dataSelectDB1($driverUserQuery);
			for($l = 0; $l<count($driverUserRslt); $l++){
				$login_id = $driverUserRslt[$l]['drv_login_id'];
			}
		}

		if($flag == 0 || $flag == 1)
		{
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

			if($payer_st == 1)
			{
				$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>79,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
				'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
				'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));
			}
			else
			{
				$this->session->set_userdata(array('login_index_id' => $row->id,'login_id'=>$row->login_id,'User_Name'=> $row->u_name,'Control_Panel'=>2,'section'=>$row->section,'n4_bizu_gkey'=>$row->n4_bizu_gkey,'LoginStat'=>"yes",'user_role_id'=> $row->user_role_id,'is_admin_user'=>$row->is_admin_user,'org_Type_id'=>$mdata,'org_id'=> $row->org_id,'org_type'=> $mdata_org,
				'first_login_track'=>$row->first_login_track,'isexpired'=>$row->isexpired,
				'org_license'=>$mdata_license,'org_name'=> $mdata_Organization_Name,'value'=> $this->session->userdata('session_id')));
			}


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
			// $rotNo.'--'.$contNo.'--'.$verify_info_fcl_id.'--'.$verify_other_data_id;
			$cont_status = "";

			if($verify_other_data_id == ""){
				$cont_status = "FCL";
			}
			else if($verify_info_fcl_id == "")
			{
				$cont_status = "LCL";
			}

			$msg = "<font size=4 color=green> Payment completed successfully.</font>";
		
			//echo '<pre>'; print_r($this->session->all_userdata());
			if($payer_st == 1)
			{
				if($payFlag == 1)
				{
					$msg = "<font size='4' color='green'> Payment completed successfully for the visit Id: {$visitId}</font>";

					$smstext = urlencode("Payment successfully completed for the visit Id: {$visitId}");
					$mobile = $this->session->userdata('login_id');

					$this->bm->sendSMS($mobile, $smstext);
				}
				else
				{
					$msg = "<font size='4' color='red'> Payment declined for the visit Id: {$visitId}</font>";
				}
				
				$data['msg'] = $msg;
				$data['title']= "Yard Delivery";

				$this->load->view('cssAssetsList');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('truckEntryByDriver_Yard',$data);
				$this->load->view('jsAssetsList');
			}
			else
			{
				$this->cnfTruckEntryForm($rotNo,$contNo,$cont_status,$assignmentType,$msg);
			}
		}
		else if($flag == 3)
		{
			$data['title']="Truck Entry for Users";
			$data['msg']="<font color='green'>Payment Successfull for the visit id: ".$visitId."</font>";

			$this->load->view('cssAssets');
			// $this->load->view('headerTop');
			// $this->load->view('sidebar');
			$this->load->view('truckEntryForUsers',$data);
			$this->load->view('jsAssets');
		}

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
		
		$strUpdateSlot = "UPDATE ctmsmis.tmp_oracle_assignment SET assignment_slot='$frmSlot' WHERE cont_no='$contNo' AND rot_no='$rotNo'";
		// $this->bm->dataUpdate($strUpdateSlot);
		$this->bm->dataUpdatedb2($strUpdateSlot);
								
		$sql_timeSlot = "SELECT assignment_slot,assignmentDate,DATE_ADD(assignmentDate, INTERVAL 1 DAY) AS nxtDt
		FROM ctmsmis.tmp_oracle_assignment
		WHERE rot_no='$rotNo' AND cont_no='$contNo' AND assignmentDate>=DATE(NOW())";
		
		$rslt_timeSlot = $this->bm->dataSelectDb2($sql_timeSlot);
		
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
				
				
				
				$sql_insertReplace = "INSERT INTO vcms_replace_truck_log(visit_id,truck_id,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,paid_amt,paid_method,paid_collect_dt,replace_time,replace_by,gate_in_status,gate_in_time,gate_in_by,paid_collect_by,pay_collect_ip,import_rotation,cont_no)
				VALUES('$repVisitId','$repTruckId','$repDriverName','$repDriverGatePass','$repAssistantName','$repAssistantGatePass','$repPaidAmt','$repPaidMethod','$repPaidCollectDt',NOW(),'$login_id','$repGateInStatus','$repGateInTime','$repGateInBy','$paid_collect_by','$pay_collect_ip','$import_rotation','$cont_no')";
				$this->bm->dataInsertDB1($sql_insertReplace);
				
				
				
				$sql_updateTruckInfo = "UPDATE do_truck_details_entry
				SET truck_id='$truckId',driver_name='$driverName',driver_gate_pass='$driverPassNo',assistant_name='$assistantName',assistant_gate_pass='$assistantPassNo',truck_agency_name='$agencyName',truck_agency_phone='$agencyPhone',paid_amt='',paid_status=0,paid_method='',gate_in_status='0',gate_in_by=NULL,gate_in_time=NULL,last_update=NOW()
				WHERE id='$editFormId'";
				$this->bm->dataUpdateDB1($sql_updateTruckInfo);	
			}
			// else if($editType == "Edit")			// check with it later
			else
			{				
			
				
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
				$strInsertEq = "INSERT INTO do_truck_details_entry(verify_info_fcl_id,import_rotation,cont_no,truck_id,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,truck_agency_name,truck_agency_phone,update_by,ip_addr,last_update,emrgncy_flag,emrgncy_approve_stat,visit_time_slot_start,visit_time_slot_end,entry_from)
				VALUES('$vrfyInfoFclId','$rotNo','$contNo','$truckId','$driverName','$driverPassNo','$assistantName','$assistantPassNo','$agencyName','$agencyPhone','$login_id','$ipaddr',NOW(),'$emrgncy_flag','$emrgncy_approve_stat','$sSlot','$eSlot','cnf')";
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
			//echo "1";
			$payAmt = $this->input->post('payAmt');
			//echo $contNo.'-'.$rotNo.'-'.$assignmentType.'-'.$contact.'-'.$payAmt;
			//return;
			$this->checkoutAllbyOnline($contNo,$rotNo,$assignmentType,$payAmt,$contact);
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
			if($section==null){
			 $str="SELECT tally_sheet_number, physical_tally_sheet_no, import_rotation, cont_number, SUM(rcv_pack) AS rcv_pack,SUM(flt_pack) AS flt_pack, SUM(flt_pack_loc) AS flt_pack_loc, 
			SUM(loc_first) AS loc_first, SUM(rcv_pack)+SUM(flt_pack)+ SUM(flt_pack_loc)+ SUM(loc_first) AS tot_pkg, wr_date,shed_yard".$qryPrt." 
			FROM shed_tally_info
			LEFT JOIN igm_supplimentary_detail ON shed_tally_info.igm_sup_detail_id = igm_supplimentary_detail.id
			LEFT JOIN igm_details ON igm_details.id=igm_supplimentary_detail.igm_detail_id 
			LEFT JOIN lcl_assignment_detail ON igm_details.id=lcl_assignment_detail.igm_detail_id 
			WHERE (wr_date BETWEEN '$from_date' AND '$to_date')
			GROUP BY tally_sheet_number
			ORDER BY shed_tally_info.id DESC";
			}
			else{
				$str="SELECT tally_sheet_number, physical_tally_sheet_no, import_rotation, cont_number, SUM(rcv_pack) AS rcv_pack,SUM(flt_pack) AS flt_pack, SUM(flt_pack_loc) AS flt_pack_loc, 
			SUM(loc_first) AS loc_first, SUM(rcv_pack)+SUM(flt_pack)+ SUM(flt_pack_loc)+ SUM(loc_first) AS tot_pkg, wr_date,shed_yard".$qryPrt." 
			FROM shed_tally_info
			LEFT JOIN igm_supplimentary_detail ON shed_tally_info.igm_sup_detail_id = igm_supplimentary_detail.id
			LEFT JOIN igm_details ON igm_details.id=igm_supplimentary_detail.igm_detail_id 
			LEFT JOIN lcl_assignment_detail ON igm_details.id=lcl_assignment_detail.igm_detail_id 
			WHERE shed_yard='$section' AND (wr_date BETWEEN '$from_date' AND '$to_date')
			GROUP BY tally_sheet_number
			ORDER BY shed_tally_info.id DESC";
				
			}
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
				
				$cnf_name = "";
				$sql_CNFName="SELECT id,name FROM ref_bizunit_scoped 
							WHERE role='SHIPPER' AND life_cycle_state='ACT' AND id='$cnf_lic_no'";	
				$res_cnf_name=$this->bm->dataSelect($sql_CNFName);
				
				for($c=0;$c<count($res_cnf_name);$c++){
					$cnf_name = $res_cnf_name[$c]['NAME'];
				}
				$this->data['cnf_name']=$cnf_name;
				
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
				
				$cnf_name = "";
				if($this->input->post('editFlag'))
				{
					$sql_CNFName="SELECT id,name FROM ref_bizunit_scoped 
								WHERE role='SHIPPER' AND life_cycle_state='ACT' AND id='$cnf_lic_no'";
				}
				else
				{
					$sql_CNFName="SELECT id,name FROM ref_bizunit_scoped 
								WHERE role='SHIPPER' AND life_cycle_state='ACT' AND id='$cnfLic'";
				}
				$res_cnf_name=$this->bm->dataSelect($sql_CNFName);
				
				for($c=0;$c<count($res_cnf_name);$c++){
					$cnf_name = $res_cnf_name[$c]['NAME'];
				}
				$this->data['cnf_name']=$cnf_name;
				
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
				
				$moveResult = move_uploaded_file($_FILES["dofile"]["tmp_name"],$_SERVER['DOCUMENT_ROOT']."/assets/do_image/".$imgName);
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
					$sql_CNFName="SELECT id,name FROM ref_bizunit_scoped 
							WHERE role='SHIPPER' AND life_cycle_state='ACT' AND id='$cnf_lic_no'";
				}
				else
				{
					$sql_CNFName="SELECT id,name FROM ref_bizunit_scoped 
							WHERE role='SHIPPER' AND life_cycle_state='ACT' AND id='$cnfLic'";
				}
				$res_cnf_name=$this->bm->dataSelect($sql_CNFName);
				
				$cnf_name = "";
				for($c=0;$c<count($res_cnf_name);$c++){
					$cnf_name = $res_cnf_name[$c]['NAME'];
				}
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
			$queryShedDOList = "SELECT * FROM shed_mlo_do_info ORDER BY id DESC limit 1000";			
			$ShedDOList=$this->bm->dataSelectDB1($queryShedDOList);
			
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
			
			$shedMloDo = $this->input->post('shedMloDo');		
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
			
			$sql_CNFName="SELECT id,name FROM ref_bizunit_scoped 
						WHERE role='SHIPPER' AND life_cycle_state='ACT' AND id='$cnf_lic_no'";	
			$res_cnf_name=$this->bm->dataSelect($sql_CNFName);
			//$cnfName = $cnf_name[0]['name'];
			
			$cnf_name = "";
			for($c=0;$c<count($res_cnf_name);$c++){
				$cnf_name = $res_cnf_name[$c]['NAME'];
			}
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
		
	
	function approveEDOapplication()
	{
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$searchBy = $this->input->post('search_by');
			$searchInput = $this->input->post('searchInput');
			$searched_be_dt = $this->input->post('searched_be_dt');			
					
			$data['searchBy']=$searchBy;
			$data['searchInput']=$searchInput;			 
			$data['searched_be_dt']=$searched_be_dt;
			
	        // $data['searchBy']=$this->input->post('search_by');
		    // $data['searchInput']=$this->input->post('searchInput');
			
		    $data['org_id'] =$this->session->userdata('org_Type_id');
			$data['title']="EDO Approved Application List";
			$data['msg'] = "";
			$data['flag'] = "all"; //To show all do list
			$data['cpa_search'] = 1;
			
            $this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('approveForEDOList',$data);
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
			
			$data['flag'] = "pending"; //To show all do list$data['flag'] = "all"; //To show all do list
			$data['searchBy'] = "";
			$data['searchInput'] = "";
			$data['cpa_search'] = 0;
						
            $this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('applicationForEDOList',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	
	function chkBlockedContainer($cont = null)
	{
		$query = "SELECT custom_block_st 
		FROM ctmsmis.tmp_oracle_assignment 
		WHERE cont_no = '$cont' AND custom_block_st = 'Blocked' 
		ORDER BY block_update_dt DESC 
		LIMIT 1";
		
		$sts = $this->bm->dataSelectDb2($query);
		
		return $sts;	
	}
	
	function chkBlockedContainer_temp($cont = null,$visitId = null)
	{				
		$sql_rotBl="SELECT igm_details.Import_Rotation_No AS rot_no,igm_details.BL_No AS bl_no
		FROM igm_details
		INNER JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
		INNER JOIN do_truck_details_entry ON do_truck_details_entry.verify_info_fcl_id=verify_info_fcl.id
		WHERE do_truck_details_entry.id='$visitId'";
		$rslt_rotBl = $this->bm->dataSelectDB1($sql_rotBl);
		
		$rotNo = $rslt_rotBl[0]['rot_no'];
		$blNo = $rslt_rotBl[0]['bl_no'];
		
		$sql_releaseChk = "SELECT release_flag AS custom_block_st
		FROM nbr_block_unblock_data
		INNER JOIN nbr_block_unblock_cont_no ON nbr_block_unblock_cont_no.block_unblock_id=nbr_block_unblock_data.id
		WHERE nbr_block_unblock_cont_no.cont_no='$cont' AND rotation_no='$rotNo' AND bl_ref='$blNo'";
		$sts = $this->bm->dataSelectDB1($sql_releaseChk);
		return $sts;
	}

	//Truck Entry LCL  -- Start
	//orcle completed cnfTruckEntryLCL
	

	
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
			
			
			$this->onlinePaymentDataUpdation();  // this function can online payment update if previously not updated. ---- Sumon ----- 11/04/2022

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
					FROM ctmsmis.tmp_oracle_assignment 
					WHERE rot_no='$rotNo' AND cont_no='".$rtnVerifyReport[0]['cont_number']."'";						
					// $rslt_posYardBlock = $this->bm->dataSelect($sql_posYardBlock);
					$rslt_posYardBlock = $this->bm->dataSelectDb2($sql_posYardBlock);
					
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
					
					$strGetSlotCnt1 = "SELECT COUNT(*) AS rtnValue FROM ctmsmis.tmp_oracle_assignment WHERE assignmentDate='$sltAssignDt' AND Block_No='$blck' AND assignment_slot=1";
					// $SlotCnt1 = $this->bm->dataReturn($strGetSlotCnt1);
					$SlotCnt1 = $this->bm->dataReturnDb2($strGetSlotCnt1);
					$data['SlotCnt1']=$SlotCnt1;
					
					$strGetSlotCnt2 = "SELECT COUNT(*) AS rtnValue FROM ctmsmis.tmp_oracle_assignment WHERE assignmentDate='$sltAssignDt' AND Block_No='$blck' AND assignment_slot=2";
					$SlotCnt2 = $this->bm->dataReturnDb2($strGetSlotCnt2);
					$data['SlotCnt2']=$SlotCnt2;

					$strGetSlotCnt3 = "SELECT COUNT(*) AS rtnValue FROM ctmsmis.tmp_oracle_assignment WHERE assignmentDate='$sltAssignDt' AND Block_No='$blck' AND assignment_slot=3";
					$SlotCnt3 = $this->bm->dataReturnDb2($strGetSlotCnt3);
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
				
					$sql_prevJS = "SELECT jetty_sirkar_id
					FROM lcl_dlv_assignment
					WHERE id='$vrfyOtherDataId'";
					$rslt_prevJS = $this->bm->dataSelectDB1($sql_prevJS);
					$prevJS = $rslt_prevJS[0]['jetty_sirkar_id'];
					
				
					$sql_updateJS = "UPDATE lcl_dlv_assignment
					SET jetty_sirkar_id='$jsId'
					WHERE id='$vrfyOtherDataId'";
					// return;
					$this->bm->dataUpdateDB1($sql_updateJS);
				}
				
				
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
			
			//$strUpdateSlot = "UPDATE ctmsmis.tmp_oracle_assignment SET assignment_slot='$frmSlot' WHERE cont_no='$contNo' AND rot_no='$rotNo'";
			//$this->bm->dataUpdate$strUpdateSlot);
			//return;
		
			//$sql_timeSlot = "SELECT assignment_slot,assignmentDate,DATE_ADD(assignmentDate, INTERVAL 1 DAY) AS nxtDt
			//FROM ctmsmis.tmp_oracle_assignment
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
					$sql_replaceInfo = "SELECT id,truck_id,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,paid_amt,paid_method,paid_collect_dt,paid_collect_by,gate_in_status,gate_in_by,gate_in_time
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
					$repPaidCollectBy = $rslt_replaceInfo[0]['paid_collect_by'];
					
					$gate_in_status = $rslt_replaceInfo[0]['gate_in_status'];
					$gate_in_by = $rslt_replaceInfo[0]['gate_in_by'];
					$gate_in_time = $rslt_replaceInfo[0]['gate_in_time'];
					
					$sql_insertReplace = "INSERT INTO vcms_replace_truck_log(visit_id,truck_id,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,paid_amt,paid_method,paid_collect_dt,paid_collect_by,replace_time,replace_by,gate_in_status,gate_in_time,gate_in_by)
					VALUES('$repVisitId','$repTruckId','$repDriverName','$repDriverGatePass','$repAssistantName','$repAssistantGatePass','$repPaidAmt','$repPaidMethod','$repPaidCollectDt','$repPaidCollectBy',NOW(),'$login_id','$gate_in_status','$gate_in_by','$gate_in_time')";
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
					$strInsertEq = "INSERT INTO do_truck_details_entry(verify_other_data_id,import_rotation,cont_no,truck_id,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,truck_agency_name,truck_agency_phone,update_by,ip_addr,last_update,emrgncy_flag,emrgncy_approve_stat,visit_time_slot_start,visit_time_slot_end,entry_from)
					VALUES('$vrfyOtherDataId','$rotNo','$contNo','$truckId','$driverName','$driverPassNo','$assistantName','$assistantPassNo','$agencyName','$agencyPhone','$login_id','$ipaddr',NOW(),'$emrgncy_flag','$emrgncy_approve_stat','$sSlot','$eSlot','cnflcl')";								
					
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
	
	
	
	// truck entry by ds - end


	// Truck Entry By Admin

	function truckEntryByAdmin()
	{
		/*echo 'truckEntryByAdmin';
		return;*/
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
			$data['title']="Truck Entry By Admin";
			$data['flag'] = 0;
			$data['msg'] = "";

			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('truckEntryByAdmin',$data);
			$this->load->view('jsAssets');
		}
	}

	
	
	function truckEntryByDSForm($action=null,$ain=null)
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
			$ipaddr = $_SERVER['REMOTE_ADDR'];
			$msg = "";

			if(is_null($action) || is_null($ain))
			{
				$action = $this->input->post('searchByCnfId');
				$ain = trim($this->input->post('cnfAinNo'));
			}
			else
			{
				$msg = "<font color='red' size='4'><b>This container is blocked by custom.</b></font>";
			}

			$data['ain'] = $ain;
			$cfLoginId = $ain."CF";

			$sql_lic = "SELECT License_No FROM organization_profiles 
			INNER JOIN users ON users.org_id = organization_profiles.id
			WHERE login_id = '$cfLoginId'";

			$data_lic = $this->bm->dataSelectDB1($sql_lic);
			$org_license = "";
			for($i=0;$i<count($data_lic);$i++){
				$org_license = $data_lic[$i]['License_No'];
			
			}
			//data_assignment
			if($this->input->post('delBtn'))
			{
				$login_id = $cfLoginId;	
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

				for($z=0;$z<count($rslt_select);$z++)
				{
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

				$sql_log = "INSERT INTO delete_log_do_truck_details(visit_id,verify_info_fcl_id,verify_other_data_id,verify_number,import_rotation,cont_no,truck_id,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,truck_agency_name,truck_agency_phone,last_update,ip_addr,update_by,paid_amt,paid_status,paid_method,visit_time_slot_start,visit_time_slot_end,emrgncy_flag,emrgncy_approve_stat,is_confirm,driver_id,helper_id,deleted_by,deleted_time,delete_by_ip) VALUES('$id','$verify_info_fcl_id','$verify_other_data_id','$verify_number','$import_rotation','$cont_no','$truck_id','$gate_no','$driver_name','$driver_gate_pass','$assistant_name','$assistant_gate_pass','$truck_agency_name','$truck_agency_phone','$last_update','$ip_addr','$update_by','$paid_amt','$paid_status','$paid_method','$visit_time_slot_start','$visit_time_slot_end','$emrgncy_flag','$emrgncy_approve_stat','$is_confirm','$driver_id','$helper_id','$login_id',NOW(),'$ipaddr')";
				$this->bm->dataInsertDB1($sql_log);

				$sql_delete = "DELETE  FROM do_truck_details_entry WHERE id='$delId'";
				$del_st = $this->bm->dataDeleteDB1($sql_delete);
				$del_st = 1;
				if($del_st == 1)
				{
					$msg = "<font color='green'>Truck deleted successfully</font>";
				}


				// Search
				$sql_cf = "SELECT u_name FROM users WHERE login_id = '$cfLoginId'";
				$data_cf = $this->bm->dataSelectDB1($sql_cf);
				$cf = "";
				for($i=0;count($data_cf)>$i;$i++){
					$cf = $data_cf[$i]['u_name'];
				}
				$data['cf_name'] = $cf;

				$data_assignment = null;
				
				
				if($org_license != "")
				{
				
					$sql_assignment = "SELECT cont_no,rot_no,bl_no,mlo,cf_name AS cf,cont_status,size,ROUND(height,1) AS height,slot AS carrentPosition,Yard_No,Block_No,mfdch_value,assignmentDate,custom_remarks,unit_gkey 
					FROM ctmsmis.tmp_oracle_assignment 
					 WHERE cf_lic='$org_license' AND cf_lic!='' AND assignmentDate>=DATE(NOW()) AND mfdch_value NOT IN('CANCEL','APPREF','APPCUS','APPOTH','--') ORDER BY cont_no ASC
					";
                     //change Nadim start
					// $data_assignment = $this->bm->dataSelect($sql_assignment);
				    $data_assignment = $this->bm->dataSelectDb2($sql_assignment);
					 
					
                
					//change Nadim End
				}

				$data_jsInfo = null;
				if($org_license != "")
				{
					$sql_jsInfo = "SELECT vcms_vehicle_agent.id,agent_name AS js_name,agent_type,card_number
					FROM vcms_vehicle_agent
					INNER JOIN vcms_vehicle_agency ON vcms_vehicle_agency.id=vcms_vehicle_agent.agency_id
					WHERE agent_type = 'Jetty Sircar'"; // agency_code = '$org_license' AND

					$data_jsInfo = $this->bm->dataSelectDB1($sql_jsInfo);
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

				// Truck Info
				$data_truck = null;
				if($org_license != "")
				{
					$sql_truck = "SELECT * FROM do_truck_details_entry WHERE update_by = '$cfLoginId' AND DATE(last_update) = DATE(NOW())";
					$data_truck = $this->bm->dataSelectDB1($sql_truck);
				}
				
				$data['rslt_tmpTrkData'] = $data_truck;
				$data['data_jsInfo'] = $data_jsInfo;
				$data['data_assignment'] = $data_assignment;
				$data['flag'] = 1;
				$data['msg'] = $msg;
			}

			if($action == "Search")
			{
				$sql_cf = "SELECT u_name FROM users WHERE login_id = '$cfLoginId'";
				$data_cf = $this->bm->dataSelectDB1($sql_cf);
				$cf = "";
				for($i=0;count($data_cf)>$i;$i++){
					$cf = $data_cf[$i]['u_name'];
				}
				$data['cf_name'] = $cf;


				$data_assignment = null;
				if($org_license != "")
				{
					$sql_assignment = "SELECT cont_no,rot_no,bl_no,mlo,cf_name AS cf,cont_status,size,ROUND(height,1) AS height,slot AS carrentPosition,Yard_No,Block_No,mfdch_value,assignmentDate,custom_remarks,unit_gkey 
					FROM ctmsmis.tmp_oracle_assignment 
					WHERE cf_lic='$org_license' AND cf_lic!='' AND assignmentDate>=DATE(NOW()) AND mfdch_value NOT IN('CANCEL','APPREF','APPCUS','APPOTH','--') ORDER BY cont_no ASC";

					$data_assignment = $this->bm->dataSelectDb2($sql_assignment);
					
					
				}
			
				$data_jsInfo = null;
				if($org_license != "")
				{
					$sql_jsInfo = "SELECT vcms_vehicle_agent.id,agent_name AS js_name,agent_type,card_number
					FROM vcms_vehicle_agent
					INNER JOIN vcms_vehicle_agency ON vcms_vehicle_agency.id=vcms_vehicle_agent.agency_id
					WHERE agent_type = 'Jetty Sircar'"; // agency_code = '$org_license' AND

					$data_jsInfo = $this->bm->dataSelectDB1($sql_jsInfo);
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

				// Truck Info
				$data_truck = null;
				if($org_license != ""){
					$sql_truck = "SELECT * FROM do_truck_details_entry WHERE update_by = '$cfLoginId' AND DATE(last_update) = DATE(NOW())";
					$data_truck = $this->bm->dataSelectDB1($sql_truck);
				}
				
				$data['rslt_tmpTrkData'] = $data_truck;
				$data['data_jsInfo'] = $data_jsInfo;
				$data['data_assignment'] = $data_assignment;
				$data['flag'] = 1;
			}
			else if($action == "Add" || $action == "Emergency")
			{
				$assignment = $this->input->post('assignment');
				$data = explode("|",$assignment);
				$contNo = $data[0];
				$rotNo = $data[1];
				$cont_status = $data[2];
				$unit_gkey = $data[3];
				$assignmentType = $data[5];

				// Custom Block Check -- starts

				$blNo = $this->bm->getBlByRotCont($rotNo,$contNo);
				$result = $this->bm->chkBlockedContainerforTruckEntry($contNo,$rotNo,$blNo);
				$custom_block_status = "";
				for($ij = 0; $ij<count($result);$ij++){
					$custom_block_status = $result[$ij]['custom_block_st'];
				}

				if($custom_block_status == "DO_NOT_RELEASE"){
					$this->truckEntryByDSForm("Search",$ain);
					return;
				}
				
				// Custom Block Check -- ends

				$jsGatePass = $this->input->post('jsGatePass');
				$jsId = "";
				$jsName = "";
				$sql_jsid = "SELECT id,agent_name FROM vcms_vehicle_agent WHERE card_number = '$jsGatePass'";
				$data_jsid = $this->bm->dataSelectDB1($sql_jsid);
				for($i=0;$i<count($data_jsid);$i++)
				{
					$jsId = $data_jsid[$i]['id'];
					$jsName =$data_jsid[$i]['agent_name'];
				}

				if($cont_status == "FCL")
				{
					$login_id = $cfLoginId;
					$sql_chkExist = "SELECT COUNT(*) AS rtnValue 
					FROM verify_info_fcl 
					WHERE rotation='$rotNo' AND cont_number='$contNo'";
					$rslt_chkExist = $this->bm->dataSelectDb1($sql_chkExist);
					$cnt = "";
					for($i=0;count($rslt_chkExist)>$i;$i++)
					{
						$cnt = $rslt_chkExist[$i]['rtnValue'];
					}
					
					$sql_igmDtlContId = "SELECT igm_details.id AS igm_dtl_id,igm_detail_container.id AS igm_dtl_cont_id,cont_size
					FROM igm_details
					INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
					WHERE Import_Rotation_No='$rotNo' AND cont_number='$contNo'";
					$rslt_igmDtlContId = $this->bm->dataSelectDb1($sql_igmDtlContId);
					$igmDtlId = "";
					$igmDtlContId = "";
					$cont_size = "";
					for($i=0;count($rslt_igmDtlContId)>$i;$i++)
					{
						$igmDtlId = $rslt_igmDtlContId[$i]['igm_dtl_id'];				
						$igmDtlContId = $rslt_igmDtlContId[$i]['igm_dtl_cont_id'];
						$cont_size = $rslt_igmDtlContId[$i]['cont_size'];
					}
				
					if($cont_size == 20)
						$truck_qty = 2;
					// else if($cont_size == 40)
					else
						$truck_qty = 3;
					
					$sql_smsNo = "SELECT cf_sms_number 
					FROM ctmsmis.tmp_oracle_assignment
					WHERE cf_lic='$org_license' AND cf_sms_number IS NOT NULL";
					$rslt_smsNo = $this->bm->dataSelectDb2($sql_smsNo);
					
					$smsNo = "";
					for($i=0;count($rslt_smsNo)>$i;$i++)
					{
						$smsNo = $rslt_smsNo[$i]['cf_sms_number'];
					}

					//checking part BL

					$partBLQuery = "SELECT COUNT(*) AS rtnValue FROM igm_sup_detail_container 
					INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=igm_sup_detail_container.igm_sup_detail_id
					WHERE cont_number='$contNo' AND Import_Rotation_No='$rotNo' AND cont_status='FCL/PART'";
					$rslt_partBL = $this->bm->dataSelectDb1($partBLQuery);
					$partbl = 0;
					for($i=0;$i<count($rslt_partBL);$i++){
						$partbl = $rslt_partBL[$i]['rtnValue'];
					}

					if($partbl == 0){
						$partBLQuery = "SELECT COUNT(*) AS rtnValue
						FROM igm_detail_container
						INNER JOIN igm_details ON igm_detail_container.igm_detail_id=igm_details.id
						WHERE igm_details.Import_Rotation_No='$rotNo' AND igm_detail_container.cont_number='$contNo' AND cont_status='FCL/PART'";
						$rslt_partBL = $this->bm->dataSelectDb1($partBLQuery);
						$partbl = 0;
						for($i=0;$i<count($rslt_partBL);$i++){
							$partbl = $rslt_partBL[$i]['rtnValue'];
						}
					}

					$partblsts = 0;
					
					if($partbl>0){
						$partblsts = 1;
					}

					if($cnt==0)
					{			
						$sql_insertQtyTruck = "INSERT INTO verify_info_fcl(igm_detail_id,igm_detail_cont_id,assignment_type,cnf_lic_no,cnf_mobile_no,unit_gkey,rotation,cont_number,no_of_truck,is_part_bl,truck_no_by,truck_no_time)
						VALUES('$igmDtlId','$igmDtlContId','$assignmentType','$org_license','$smsNo','$unit_gkey','$rotNo','$contNo','$truck_qty','$partblsts','$login_id',NOW())";
						
						if($this->bm->dataInsertDB1($sql_insertQtyTruck))
							$msg = "<font color='green'><b>Truck number entered succesfully.</b></font>";
					}
					else
					{
						$sql_updateQtyTruck = "UPDATE verify_info_fcl
						SET cnf_mobile_no='$smsNo',unit_gkey='$unit_gkey',no_of_truck='$truck_qty',is_part_bl='$partblsts',truck_no_by='$login_id',truck_no_time=NOW()
						WHERE rotation='$rotNo' AND cont_number='$contNo'";
						
						if($this->bm->dataUpdateDB1($sql_updateQtyTruck))
							$msg = "<font color='green'><b>Truck number updated succesfully.</b></font>";
					}

					//Verify Info FCL ID
					$sql_verifyInfoFclid = "SELECT id FROM verify_info_fcl WHERE rotation='$rotNo' AND cont_number='$contNo'";
					$data_vrfyinfofclId = $this->bm->dataSelectDB1($sql_verifyInfoFclid);
					$vrfyInfoFclId = "";
					for($i=0;count($data_vrfyinfofclId)>$i;$i++)
					{
						$vrfyInfoFclId = $data_vrfyinfofclId[$i]['id'];
					}

					// Jetty Sircar Entry -- Starts 

					$sql_chkJS = "SELECT COUNT(*) AS rtnValue
					FROM verify_info_fcl
					WHERE jetty_sirkar_id='$jsId' AND id='$vrfyInfoFclId'";
					$rslt_chkJS = $this->bm->dataSelectDB1($sql_chkJS);
					$chkJS = "";
					for($i=0;count($rslt_chkJS)>$i;$i++)
					{
						$chkJS = $rslt_chkJS[$i]['rtnValue'];
					}
					
					if($chkJS == 0)
					{
						$prevJS = "";
						// get previous JS	- check if previous exists
						$sql_prevJS = "SELECT jetty_sirkar_id
						FROM verify_info_fcl
						WHERE id='$vrfyInfoFclId'";
						$rslt_prevJS = $this->bm->dataSelectDB1($sql_prevJS);
						
						for($i=0;$i<count($rslt_prevJS);$i++){
							$prevJS = $rslt_prevJS[$i]['jetty_sirkar_id'];
						}
						
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

					// Jetty Sircar Entry -- Ends

					// Add Truck - starts
		
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

					$totTruck = $truck_qty;
					
					$frmSlot = $this->input->post('truckSlot');			// added on 2021-03-01		
					
					$emrgncy_flag = 0;
					$emrgncy_approve_stat = 0;

					if($action == "Emergency")
					{
						$emrgncy_flag = 1;	
					}

					$strUpdateSlot = "UPDATE ctmsmis.tmp_oracle_assignment SET assignment_slot='$frmSlot' WHERE cont_no='$contNo' AND rot_no='$rotNo'";
					// $this->bm->dataUpdate($strUpdateSlot);
					$this->bm->dataUpdatedb2($strUpdateSlot);
											
					$sql_timeSlot = "SELECT assignment_slot,assignmentDate,DATE_ADD(assignmentDate, INTERVAL 1 DAY) AS nxtDt
					FROM ctmsmis.tmp_oracle_assignment
					WHERE rot_no='$rotNo' AND cont_no='$contNo' AND assignmentDate>=DATE(NOW())";
					$rslt_timeSlot = $this->bm->dataSelectDb2($sql_timeSlot);
					

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

					$sql_chkTruck = "SELECT COUNT(*) AS rtnValue
					FROM do_truck_details_entry 
					WHERE truck_id='$truckId' AND visit_time_slot_start='$sSlot' AND visit_time_slot_end='$eSlot'";
					$rslt_chkTruck = $this->bm->dataSelectDB1($sql_chkTruck);
					$chkTruck = "";
					for($i=0;count($rslt_chkTruck)>$i;$i++){
						$chkTruck = $rslt_chkTruck[$i]['rtnValue'];
					}
					
					if($chkTruck==0)
					{
						$strInsertEq = "INSERT INTO do_truck_details_entry(verify_info_fcl_id,import_rotation,cont_no,truck_id,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,truck_agency_name,truck_agency_phone,update_by,ip_addr,last_update,emrgncy_flag,emrgncy_approve_stat,visit_time_slot_start,visit_time_slot_end,entry_from)
						VALUES('$vrfyInfoFclId','$rotNo','$contNo','$truckId','$driverName','$driverPassNo','$assistantName','$assistantPassNo','$agencyName','$agencyPhone','$login_id','$ipaddr',NOW(),'$emrgncy_flag','$emrgncy_approve_stat','$sSlot','$eSlot','admin')";
						$stat = $this->bm->dataInsertDB1($strInsertEq);
						// $stat = 1;
						if($stat == 1)
							$msg = "<font color='green'><b>Truck added successfully</b></font>";
						
					}
					else
					{
						$msg = "<font color='red'><b>This truck was assigned for this time slot previously</b></font>";
					}

					$sql_updateImporterMbl = "UPDATE verify_info_fcl
					SET importer_mobile_no='$importerMobileNo'
					WHERE id='$vrfyInfoFclId'";
					$this->bm->dataUpdateDB1($sql_updateImporterMbl);

					$sql_updateSlot = "UPDATE verify_info_fcl
					SET truck_slot = '$asSlot'
					WHERE id='$vrfyInfoFclId'";
					$this->bm->dataUpdateDB1($sql_updateSlot);

					// Add Truck - Ends

					// search Data  - Starts

					$sql_cf = "SELECT u_name FROM users WHERE login_id = '$cfLoginId'";
					$data_cf = $this->bm->dataSelectDB1($sql_cf);
					$cf = "";
					for($i=0;count($data_cf)>$i;$i++){
						$cf = $data_cf[$i]['u_name'];
					}
					$data['cf_name'] = $cf;

					$data_assignment = null;
					if($org_license != "")
					{
						$sql_assignment = "SELECT cont_no,rot_no,bl_no,mlo,cf_name AS cf,cont_status,size,ROUND(height,1) AS height,slot AS carrentPosition,Yard_No,Block_No,mfdch_value,assignmentDate,custom_remarks,unit_gkey 
						FROM ctmsmis.tmp_oracle_assignment 
						WHERE cf_lic='$org_license' AND cf_lic!='' AND assignmentDate>=DATE(NOW()) AND mfdch_value NOT IN('CANCEL','APPREF','APPCUS','APPOTH','--') ORDER BY cont_no ASC";

						$data_assignment = $this->bm->dataSelectDb2($sql_assignment);
						
					}

					$data_jsInfo = null;
					if($org_license != "")
					{
						$sql_jsInfo = "SELECT vcms_vehicle_agent.id,agent_name AS js_name,agent_type,card_number
						FROM vcms_vehicle_agent
						INNER JOIN vcms_vehicle_agency ON vcms_vehicle_agency.id=vcms_vehicle_agent.agency_id
						WHERE agent_type = 'Jetty Sircar'"; // agency_code = '$org_license' AND

						$data_jsInfo = $this->bm->dataSelectDB1($sql_jsInfo);
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

					// Truck Info
					$data_truck = null;
					if($org_license != ""){
						$sql_truck = "SELECT * FROM do_truck_details_entry WHERE update_by = '$cfLoginId' AND DATE(last_update) = DATE(NOW())";
						$data_truck = $this->bm->dataSelectDB1($sql_truck);
					}
					
					$data['rslt_tmpTrkData'] = $data_truck;
					$data['data_jsInfo'] = $data_jsInfo;
					$data['data_assignment'] = $data_assignment;
					$data['flag'] = 1;
					$data['ain'] = $ain;
					$data['jsGatePass'] = $jsGatePass;
					$data['contNo'] = $contNo;

					// Load Data  -- Ends 

				}
				else if($cont_status == "LCL")
				{
					
				}
			}
			else if($action == "Update")
			{
				$editType = $this->input->post('editType');
				$assignment = $this->input->post('assignmentData');
				$data = explode("|",$assignment);
				$contNo = $data[0];
				$rotNo = $data[1];
				$cont_status = $data[2];
				$unit_gkey = $data[3];
				$assignmentType = $data[4];

				// Custom Block Check -- starts

				$blNo = $this->bm->getBlByRotCont($rotNo,$contNo);
				$result = $this->bm->chkBlockedContainerforTruckEntry($contNo,$rotNo,$blNo);
				$custom_block_status = "";
				for($ij = 0; $ij<count($result);$ij++){
					$custom_block_status = $result[$ij]['custom_block_st'];
				}

				if($custom_block_status == "DO_NOT_RELEASE"){
					$this->truckEntryByDSForm("Search",$ain);
					return;
				}
				
				// Custom Block Check -- ends

				$jsGatePass = $this->input->post('jsGatePass');
				$jsId = "";
				$jsName = "";
				$sql_jsid = "SELECT id,agent_name FROM vcms_vehicle_agent WHERE card_number = '$jsGatePass'";
				$data_jsid = $this->bm->dataSelectDB1($sql_jsid);
				for($i=0;$i<count($data_jsid);$i++)
				{
					$jsId = $data_jsid[$i]['id'];
					$jsName =$data_jsid[$i]['agent_name'];
				}

				if($cont_status == "FCL")
				{
					$login_id = $cfLoginId;
					
					$sql_igmDtlContId = "SELECT igm_details.id AS igm_dtl_id,igm_detail_container.id AS igm_dtl_cont_id,cont_size
					FROM igm_details
					INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
					WHERE Import_Rotation_No='$rotNo' AND cont_number='$contNo'";
					$rslt_igmDtlContId = $this->bm->dataSelectDb1($sql_igmDtlContId);
					$igmDtlId = "";
					$igmDtlContId = "";
					$cont_size = "";
					for($i=0;count($rslt_igmDtlContId)>$i;$i++)
					{
						$igmDtlId = $rslt_igmDtlContId[$i]['igm_dtl_id'];				
						$igmDtlContId = $rslt_igmDtlContId[$i]['igm_dtl_cont_id'];
						$cont_size = $rslt_igmDtlContId[$i]['cont_size'];
					}
				
					if($cont_size == 20)
						$truck_qty = 2;
					// else if($cont_size == 40)
					else
						$truck_qty = 3;
					
					$sql_smsNo = "SELECT cf_sms_number 
					FROM ctmsmis.tmp_oracle_assignment
					WHERE cf_lic='$org_license' AND cf_sms_number IS NOT NULL";
					//change 1/26/2023
					// $rslt_smsNo = $this->bm->dataSelect($sql_smsNo);
					$rslt_smsNo = $this->bm->dataSelectDb2($sql_smsNo);
					 
					$smsNo = "";
					for($i=0;count($rslt_smsNo)>$i;$i++)
					{
						$smsNo = $rslt_smsNo[$i]['cf_sms_number'];
					}

					
					$sql_updateQtyTruck = "UPDATE verify_info_fcl
					SET cnf_mobile_no='$smsNo',unit_gkey='$unit_gkey',no_of_truck='$truck_qty',truck_no_by='$login_id',truck_no_time=NOW()
					WHERE rotation='$rotNo' AND cont_number='$contNo'";
					
					if($this->bm->dataUpdateDB1($sql_updateQtyTruck))
						$msg = "<font color='green'><b>Truck number updated succesfully.</b></font>";

					//Verify Info FCL ID
					$sql_verifyInfoFclid = "SELECT id FROM verify_info_fcl WHERE rotation='$rotNo' AND cont_number='$contNo'";
					$data_vrfyinfofclId = $this->bm->dataSelectDB1($sql_verifyInfoFclid);
					$vrfyInfoFclId = "";
					for($i=0;count($data_vrfyinfofclId)>$i;$i++)
					{
						$vrfyInfoFclId = $data_vrfyinfofclId[$i]['id'];
					}

					// Jetty Sircar Entry -- Starts 

					$sql_chkJS = "SELECT COUNT(*) AS rtnValue
					FROM verify_info_fcl
					WHERE jetty_sirkar_id='$jsId' AND id='$vrfyInfoFclId'";
					$rslt_chkJS = $this->bm->dataSelectDB1($sql_chkJS);
					$chkJS = "";
					for($i=0;count($rslt_chkJS)>$i;$i++)
					{
						$chkJS = $rslt_chkJS[$i]['rtnValue'];
					}
					
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

					// Jetty Sircar Entry -- Ends

					// Edit Truck - starts 
					
					$truckVisitId = $this->input->post('truckVisitId');

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

					$totTruck = $truck_qty;

					// Added on 02 Aug 2021

					$frmSlot = $this->input->post('truckSlot');		
					
					$emrgncy_flag = 0;
					$emrgncy_approve_stat = 0;

					if($action == "Emergency")
					{
						$emrgncy_flag = 1;	
					}

					$strUpdateSlot = "UPDATE ctmsmis.tmp_oracle_assignment SET assignment_slot='$frmSlot' WHERE cont_no='$contNo' AND rot_no='$rotNo'";
					//change 1/26/2023

					// $this->bm->dataUpdate($strUpdateSlot);
					$this->bm->dataUpdatedb2($strUpdateSlot);
											
					$sql_timeSlot = "SELECT assignment_slot,assignmentDate,DATE_ADD(assignmentDate, INTERVAL 1 DAY) AS nxtDt
					FROM ctmsmis.tmp_oracle_assignment
					WHERE rot_no='$rotNo' AND cont_no='$contNo' AND assignmentDate>=DATE(NOW())";
					// $rslt_timeSlot = $this->bm->dataSelect($sql_timeSlot);
					$rslt_timeSlot = $this->bm->dataSelectDb2($sql_timeSlot);
					

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

					// Added on 02 Aug 2021

					$payAmt = 57.5;

					if($editType == "Replace")
					{				
						$sql_replaceInfo = "SELECT id,import_rotation,cont_no,truck_id,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,paid_amt,paid_method,paid_collect_dt,paid_collect_by
						FROM do_truck_details_entry
						WHERE id='$truckVisitId'";
						$rslt_replaceInfo = $this->bm->dataSelectDB1($sql_replaceInfo);
						
						$repVisitId = $rslt_replaceInfo[0]['id'];
						$repRot = $rslt_replaceInfo[0]['import_rotation'];
						$repCont = $rslt_replaceInfo[0]['cont_no'];
						$repTruckId = $rslt_replaceInfo[0]['truck_id'];
						$repDriverName = $rslt_replaceInfo[0]['driver_name'];
						$repDriverGatePass = $rslt_replaceInfo[0]['driver_gate_pass'];
						$repAssistantName = $rslt_replaceInfo[0]['assistant_name'];
						$repAssistantGatePass = $rslt_replaceInfo[0]['assistant_gate_pass'];
						$repPaidAmt = $rslt_replaceInfo[0]['paid_amt'];
						$repPaidMethod = $rslt_replaceInfo[0]['paid_method'];
						$repPaidCollectDt = $rslt_replaceInfo[0]['paid_collect_dt'];
						$repPaidCollectBy = $rslt_replaceInfo[0]['paid_collect_by'];
						
						$sql_insertReplace = "INSERT INTO vcms_replace_truck_log(visit_id,truck_id,import_rotation,cont_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,paid_amt,paid_method,paid_collect_dt,paid_collect_by,replace_time,replace_by)
						VALUES('$repVisitId','$repTruckId','$repRot','$repCont','$repDriverName','$repDriverGatePass','$repAssistantName','$repAssistantGatePass','$repPaidAmt','$repPaidMethod','$repPaidCollectDt','$repPaidCollectBy',NOW(),'$login_id')";
						$this->bm->dataInsertDB1($sql_insertReplace);
						
						$sql_updateTruckInfo = "UPDATE do_truck_details_entry
						SET truck_id='$truckId',driver_name='$driverName',driver_gate_pass='$driverPassNo',assistant_name='$assistantName',assistant_gate_pass='$assistantPassNo',truck_agency_name='$agencyName',truck_agency_phone='$agencyPhone',paid_amt='',paid_status=0,paid_method='',gate_in_status='0',gate_in_by=NULL,gate_in_time=NULL,last_update=NOW()
						WHERE id='$truckVisitId'";
						$this->bm->dataUpdateDB1($sql_updateTruckInfo);	
					}
					else if($editType == "Edit")			// check with it later
					{
						$sql_updateTruckInfo = "UPDATE do_truck_details_entry
						SET truck_id='$truckId',driver_name='$driverName',driver_gate_pass='$driverPassNo',assistant_name='$assistantName',assistant_gate_pass='$assistantPassNo',truck_agency_name='$agencyName',truck_agency_phone='$agencyPhone',edit_at=NOW(),edit_by='$login_id',edit_ip='$ipaddr'
						WHERE id='$truckVisitId'";
						$this->bm->dataUpdateDB1($sql_updateTruckInfo);	
					}

					$sql_updateImporterMbl = "UPDATE verify_info_fcl
					SET importer_mobile_no='$importerMobileNo'
					WHERE id='$vrfyInfoFclId'";
					$this->bm->dataUpdateDB1($sql_updateImporterMbl);

					$sql_updateSlot = "UPDATE verify_info_fcl
					SET truck_slot = '$asSlot'
					WHERE id='$vrfyInfoFclId'";
					$this->bm->dataUpdateDB1($sql_updateSlot);
						

					// Edit Truck - Ends

					// Search Data  - Starts

					$sql_cf = "SELECT u_name FROM users WHERE login_id = '$cfLoginId'";
					$data_cf = $this->bm->dataSelectDB1($sql_cf);
					$cf = "";
					for($i=0;count($data_cf)>$i;$i++){
						$cf = $data_cf[$i]['u_name'];
					}
					$data['cf_name'] = $cf;

					$data_assignment = null;
					if($org_license != "")
					{
						$sql_assignment = "SELECT cont_no,rot_no,bl_no,mlo,cf_name AS cf,cont_status,size,ROUND(height,1) AS height,slot AS carrentPosition,Yard_No,Block_No,mfdch_value,assignmentDate,custom_remarks,unit_gkey 
						FROM ctmsmis.tmp_oracle_assignment 
						WHERE cf_lic='$org_license' AND cf_lic!='' AND assignmentDate>=DATE(NOW()) AND mfdch_value NOT IN('CANCEL','APPREF','APPCUS','APPOTH','--') ORDER BY cont_no ASC";
                         //change 1/26/2023
						// $data_assignment = $this->bm->dataSelect($sql_assignment);
						$data_assignment = $this->bm->dataSelectDb2($sql_assignment);
					
					}

					$data_jsInfo = null;
					if($org_license != "")
					{
						$sql_jsInfo = "SELECT vcms_vehicle_agent.id,agent_name AS js_name,agent_type,card_number
						FROM vcms_vehicle_agent
						INNER JOIN vcms_vehicle_agency ON vcms_vehicle_agency.id=vcms_vehicle_agent.agency_id
						WHERE agent_type = 'Jetty Sircar'"; // agency_code = '$org_license' AND

						$data_jsInfo = $this->bm->dataSelectDB1($sql_jsInfo);
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

					// Truck Info
					$data_truck = null;
					if($org_license != "")
					{
						$sql_truck = "SELECT * FROM do_truck_details_entry WHERE update_by = '$cfLoginId' AND DATE(last_update) = DATE(NOW())";
						$data_truck = $this->bm->dataSelectDB1($sql_truck);
					}
					
					$data['rslt_tmpTrkData'] = $data_truck;
					$data['data_jsInfo'] = $data_jsInfo;
					$data['data_assignment'] = $data_assignment;
					$data['flag'] = 1;
					$data['ain'] = $ain;
					$data['jsGatePass'] = $jsGatePass;

					// Load Data  -- Ends 

				}
				else if($cont_status == "LCL")
				{

				}
			}

			// Edit Truck 
				
			if($this->input->post('editBtn'))
			{
				$editId = $this->input->post('editId');
				$btnType = $this->input->post('btnType');
				$data['editType'] = $btnType;
				
				$editType = $this->input->post('editBtn');
				$data['editType']=$editType;	
				
				$sql_trkEditInfo = "SELECT id,verify_info_fcl_id,truck_id,driver_name,driver_gate_pass,truck_agency_name,cont_no,truck_agency_phone,
				(SELECT DISTINCT mobile_number FROM vcms_vehicle_agent WHERE card_number=driver_gate_pass) AS driver_mobile_number,
				assistant_name,assistant_gate_pass,
				(SELECT DISTINCT mobile_number FROM vcms_vehicle_agent WHERE card_number=assistant_gate_pass) AS helper_mobile_number
				FROM do_truck_details_entry
				WHERE id='$editId'";
				$rslt_trkEditInfo = $this->bm->dataSelectDB1($sql_trkEditInfo);
				$data['rslt_trkEditInfo']=$rslt_trkEditInfo;

				$cont = "";
				$vrfyInfoFclId = "";
				for($i=0;$i<count($rslt_trkEditInfo);$i++)
				{
					$vrfyInfoFclId = $rslt_trkEditInfo[$i]['verify_info_fcl_id'];
					$cont = $rslt_trkEditInfo[$i]['cont_no'];
				}
				$data['cont'] = $cont;

				// Assignment Data

				$sql_assignmentData = "SELECT * FROM ctmsmis.tmp_oracle_assignment WHERE assignmentDate = DATE(NOW()) AND cont_no = '$cont'";
				//change mysql to oracle 1/26/2023
				// $rslt_assignmentData = $this->bm->dataSelect($sql_assignmentData);
				$rslt_assignmentData = $this->bm->dataSelectDb2($sql_assignmentData);
				
				$assignmentData = "";
				for($i=0;$i<count($rslt_assignmentData);$i++)
				{
					$assignmentData = $rslt_assignmentData[$i]['cont_no']."|".$rslt_assignmentData[$i]['rot_no']."|".$rslt_assignmentData[$i]['cont_status']."|".$rslt_assignmentData[$i]['unit_gkey']."|".$rslt_assignmentData[$i]['mfdch_value'];
				}
				$data['assignmentData'] = $assignmentData;

				// Importer's Mobile

				$sql_importerMobile = "SELECT importer_mobile_no,jetty_sirkar_id,truck_slot FROM verify_info_fcl WHERE id='$vrfyInfoFclId'";
				$rslt_importerMobile = $this->bm->dataSelectDB1($sql_importerMobile);

				$jetty_sirkar_id = "";
				$importerMobile = "";
				$truckSlot = "";
				for($i=0;$i<count($rslt_importerMobile);$i++)
				{
					$importerMobile = $rslt_importerMobile[$i]['importer_mobile_no'];
					$jetty_sirkar_id = $rslt_importerMobile[$i]['jetty_sirkar_id'];
					$truckSlot = $rslt_importerMobile[0]['truck_slot'];
				}

				$data['truckSlot']=$truckSlot;
				$data['importerMobile']=$importerMobile;
				
				// Jetty Sirkar Card Number

				$sql_jetty_Sirkar = "SELECT card_number FROM vcms_vehicle_agent WHERE id = '$jetty_sirkar_id'";
				$rslt_jetty_Sirkar = $this->bm->dataSelectDB1($sql_jetty_Sirkar);

				$cardNumber = "";
				for($i=0;$i<count($rslt_jetty_Sirkar);$i++)
				{
					$cardNumber = $rslt_jetty_Sirkar[$i]['card_number'];
				}

				$data['cardNumber']=$cardNumber;

				$sql_jsInfo = "SELECT agent_name, agent_code FROM vcms_vehicle_agent WHERE card_number = '$cardNumber'";
				$rslt_jsInfo = $this->bm->dataSelectDB1($sql_jsInfo);

				$agent_name = "";
				$agent_code = "";
				for($i=0;$i<count($rslt_jsInfo);$i++)
				{
					$agent_name = $rslt_jsInfo[$i]['agent_name'];
					$agent_code = $rslt_jsInfo[$i]['agent_code'];
				}

				$data['agent_name'] = $agent_name;
				$data['agent_code'] = $agent_code;
				
				// Search Data

				$sql_cf = "SELECT u_name FROM users WHERE login_id = '$cfLoginId'";
				$data_cf = $this->bm->dataSelectDB1($sql_cf);
				$cf = "";
				for($i=0;count($data_cf)>$i;$i++){
					$cf = $data_cf[$i]['u_name'];
				}
				$data['cf_name'] = $cf;

				$data_assignment = null;
				if($org_license != "")
				{
					$sql_assignment = "SELECT cont_no,rot_no,bl_no,mlo,cf_name AS cf,cont_status,size,ROUND(height,1) AS height,slot AS carrentPosition,Yard_No,Block_No,mfdch_value,assignmentDate,custom_remarks,unit_gkey 
					FROM ctmsmis.tmp_oracle_assignment 
					WHERE cf_lic='$org_license' AND cf_lic!='' AND assignmentDate>=DATE(NOW()) AND mfdch_value NOT IN('CANCEL','APPREF','APPCUS','APPOTH','--') ORDER BY cont_no ASC";
                     //change mysql to oracle 1/26/2023
					// $data_assignment = $this->bm->dataSelect($sql_assignment);
					$data_assignment = $this->bm->dataSelectDb2($sql_assignment);
				
				}

				$data_jsInfo = null;
				if($org_license != "")
				{
					$sql_jsInfo = "SELECT vcms_vehicle_agent.id,agent_name AS js_name,agent_type,card_number
					FROM vcms_vehicle_agent
					INNER JOIN vcms_vehicle_agency ON vcms_vehicle_agency.id=vcms_vehicle_agent.agency_id
					WHERE agent_type = 'Jetty Sircar'"; // agency_code = '$org_license' AND

					$data_jsInfo = $this->bm->dataSelectDB1($sql_jsInfo);
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

				// Truck Info
				$data_truck = null;
				if($org_license != "")
				{
					$sql_truck = "SELECT * FROM do_truck_details_entry WHERE update_by = '$cfLoginId' AND DATE(last_update) = DATE(NOW())";
					$data_truck = $this->bm->dataSelectDB1($sql_truck);
				}

				$data['rslt_tmpTrkData'] = $data_truck;
				$data['data_jsInfo'] = $data_jsInfo;
				$data['data_assignment'] = $data_assignment;
				$data['flag'] = 1;
							
			}

			$data['msg'] = $msg;
			$data['title']="Truck Entry By Admin";
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('truckEntryByAdmin',$data);
			$this->load->view('jsAssetsList');
		}
	}

	// Truck Entry By Admin


	// LCL Truck Entry By Admin  -- starts

	function lcltruckEntryByAdmin()
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
			$data['title']="LCL Truck Entry By Admin";
			$data['flag'] = 0;
			$data['msg'] = "";

			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('lcltruckEntryByAdmin',$data);
			$this->load->view('jsAssets');
		}
	}

	function lcltruckEntryByDSForm($action=null,$ain=null)
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
			$ipaddr = $_SERVER['REMOTE_ADDR'];
			$msg = "";

			if(is_null($action) || is_null($ain)){
				$action = $this->input->post('searchByCnfId');
				$ain = trim($this->input->post('cnfAinNo'));
			}
			else
			{
				$msg = "<font color='red' size='4'><b>This container is blocked by custom.</b></font>";
			}

			$data['ain'] = $ain;
			$cfLoginId = $ain."CF";

			$sql_lic = "SELECT License_No FROM organization_profiles 
			INNER JOIN users ON users.org_id = organization_profiles.id
			WHERE login_id = '$cfLoginId'";

			$data_lic = $this->bm->dataSelectDB1($sql_lic);
			$org_license = "";
			for($i=0;$i<count($data_lic);$i++){
				$org_license = $data_lic[$i]['License_No'];
			}

			$cnfLic = explode("/", $org_license);
			$cnfLic_firstpart = $cnfLic[0];
			$cnfLic_firstpart = ltrim($cnfLic_firstpart, '0');

			if($this->input->post('delBtn'))
			{
				$login_id = $cfLoginId;	
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

				for($z=0;$z<count($rslt_select);$z++)
				{
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

				$sql_log = "INSERT INTO delete_log_do_truck_details(visit_id,verify_info_fcl_id,verify_other_data_id,verify_number,import_rotation,cont_no,truck_id,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,truck_agency_name,truck_agency_phone,last_update,ip_addr,update_by,paid_amt,paid_status,paid_method,visit_time_slot_start,visit_time_slot_end,emrgncy_flag,emrgncy_approve_stat,is_confirm,driver_id,helper_id,deleted_by,deleted_time,delete_by_ip) VALUES('$id','$verify_info_fcl_id','$verify_other_data_id','$verify_number','$import_rotation','$cont_no','$truck_id','$gate_no','$driver_name','$driver_gate_pass','$assistant_name','$assistant_gate_pass','$truck_agency_name','$truck_agency_phone','$last_update','$ip_addr','$update_by','$paid_amt','$paid_status','$paid_method','$visit_time_slot_start','$visit_time_slot_end','$emrgncy_flag','$emrgncy_approve_stat','$is_confirm','$driver_id','$helper_id','$login_id',NOW(),'$ipaddr')";
				$this->bm->dataInsertDB1($sql_log);

				$sql_delete = "DELETE  FROM do_truck_details_entry WHERE id='$delId'";
				$del_st = $this->bm->dataDeleteDB1($sql_delete);
				$del_st = 1;
				if($del_st == 1)
				{
					$msg = "<font color='green'>Truck deleted successfully</font>";
				}


				// Search
				$sql_cf = "SELECT u_name FROM users WHERE login_id = '$cfLoginId'";
				$data_cf = $this->bm->dataSelectDB1($sql_cf);
				$cf = "";
				for($i=0;count($data_cf)>$i;$i++){
					$cf = $data_cf[$i]['u_name'];
				}
				$data['cf_name'] = $cf;

				$data_assignment = null;
				if($org_license != "")
				{
					$lclAssignmentQuery = "SELECT igm_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no,
					igm_details.Pack_Description, igm_details.Pack_Number, igm_detail_container.cont_status,igm_detail_container.cont_size,
					oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'dtl' AS igm_type,
					IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT CONCAT(shed_yard,' - ',shed_loc) FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
					FROM oracle_nts_data
					INNER JOIN igm_details ON igm_details.id=oracle_nts_data.igm_detail_id
					INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
					WHERE igm_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
					AND REPLACE(LTRIM(REPLACE(oracle_nts_data.cnf_lno,'0',' ')),' ','0') LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id) > 0	
					
					UNION 
					
					SELECT igm_sup_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no, igm_supplimentary_detail.Pack_Description, igm_supplimentary_detail.Pack_Number,igm_sup_detail_container.cont_status,igm_sup_detail_container.cont_size,
					oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'sup_dtl' AS igm_type,
					IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT CONCAT(shed_yard,' - ',shed_loc) FROM shed_tally_info WHERE cont_number=igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
					FROM oracle_nts_data
					INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=oracle_nts_data.igm_sup_detail_id
					INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
					WHERE igm_sup_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
					AND REPLACE(LTRIM(REPLACE(oracle_nts_data.cnf_lno,'0',' ')),' ','0') LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number= igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id) > 0";

					$data_assignment = $this->bm->dataSelectDB1($lclAssignmentQuery);
					
					// if(empty($data_assignment))
					// {
					// 	if(substr($cnfLic[0], 0, 1)=='0' )
					// 	{
					// 		$cnfLic_firstpart = substr($cnfLic[0], 1);
					// 	}
					// 	else if (substr($cnfLic[0], 0, 2)=='00' )
					// 	{
					// 		$cnfLic_firstpart = substr($cnfLic[0], 2);
					// 	}
														
							
					// 	$lclAssignmentQuery = "SELECT igm_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no,
					// 	igm_details.Pack_Description, igm_details.Pack_Number, igm_detail_container.cont_status,igm_detail_container.cont_size,
					// 	oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'dtl' AS igm_type,
					// 	IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT shed_loc FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
					// 	FROM oracle_nts_data
					// 	INNER JOIN igm_details ON igm_details.id=oracle_nts_data.igm_detail_id
					// 	INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
					// 	WHERE igm_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
					// 	AND oracle_nts_data.cnf_lno LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id) > 0	
						
					// 	UNION 
						
					// 	SELECT igm_sup_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no, igm_supplimentary_detail.Pack_Description, igm_supplimentary_detail.Pack_Number,igm_sup_detail_container.cont_status,igm_sup_detail_container.cont_size,
					// 	oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'sup_dtl' AS igm_type,
					// 	IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT shed_loc FROM shed_tally_info WHERE cont_number=igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
					// 	FROM oracle_nts_data
					// 	INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=oracle_nts_data.igm_sup_detail_id
					// 	INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
					// 	WHERE igm_sup_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
					// 	AND oracle_nts_data.cnf_lno LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number= igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id) > 0";

					// 	$data_assignment = $this->bm->dataSelectDB1($lclAssignmentQuery);
					// }					
				}

				$data_jsInfo = null;
				if($org_license != "")
				{
					$sql_jsInfo = "SELECT vcms_vehicle_agent.id,agent_name AS js_name,agent_type,card_number
					FROM vcms_vehicle_agent
					INNER JOIN vcms_vehicle_agency ON vcms_vehicle_agency.id=vcms_vehicle_agent.agency_id
					WHERE agent_type = 'Jetty Sircar'"; // agency_code = '$org_license' AND

					$data_jsInfo = $this->bm->dataSelectDB1($sql_jsInfo);
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

				// Truck Info
				$data_truck = null;
				if($org_license != "")
				{
					$sql_truck = "SELECT * FROM do_truck_details_entry WHERE update_by = '$cfLoginId' AND DATE(last_update) = DATE(NOW())";
					$data_truck = $this->bm->dataSelectDB1($sql_truck);
				}
				
				$data['rslt_tmpTrkData'] = $data_truck;
				$data['data_jsInfo'] = $data_jsInfo;
				$data['data_assignment'] = $data_assignment;
				$data['flag'] = 1;
				$data['msg'] = $msg;
			}

			if($action == "Search")
			{
				$sql_cf = "SELECT u_name FROM users WHERE login_id = '$cfLoginId'";
				$data_cf = $this->bm->dataSelectDB1($sql_cf);
				$cf = "";
				for($i=0;count($data_cf)>$i;$i++){
					$cf = $data_cf[$i]['u_name'];
				}

				$data['cf_name'] = $cf;


				$data_assignment = null;
				if($org_license != "")
				{
					$lclAssignmentQuery = "SELECT igm_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no,
					igm_details.Pack_Description, igm_details.Pack_Number, igm_detail_container.cont_status,igm_detail_container.cont_size,
					oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'dtl' AS igm_type,
					IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT CONCAT(shed_yard,' - ',shed_loc) FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
					FROM oracle_nts_data
					INNER JOIN igm_details ON igm_details.id=oracle_nts_data.igm_detail_id
					INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
					WHERE igm_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
					AND REPLACE(LTRIM(REPLACE(oracle_nts_data.cnf_lno,'0',' ')),' ','0') LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id) > 0	
					
					UNION 
					
					SELECT igm_sup_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no, igm_supplimentary_detail.Pack_Description, igm_supplimentary_detail.Pack_Number,igm_sup_detail_container.cont_status,igm_sup_detail_container.cont_size,
					oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'sup_dtl' AS igm_type,
					IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT CONCAT(shed_yard,' - ',shed_loc) FROM shed_tally_info WHERE cont_number=igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
					FROM oracle_nts_data
					INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=oracle_nts_data.igm_sup_detail_id
					INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
					WHERE igm_sup_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
					AND REPLACE(LTRIM(REPLACE(oracle_nts_data.cnf_lno,'0',' ')),' ','0') LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number= igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id) > 0";

					$data_assignment = $this->bm->dataSelectDB1($lclAssignmentQuery);
					
					// if(empty($data_assignment))
					// {
					// 	if(substr($cnfLic[0], 0, 1)=='0' )
					// 	{
					// 		$cnfLic_firstpart = substr($cnfLic[0], 1);
					// 	}
					// 	else if (substr($cnfLic[0], 0, 2)=='00' )
					// 	{
					// 		$cnfLic_firstpart = substr($cnfLic[0], 2);
					// 	}
														
							
					// 	$lclAssignmentQuery = "SELECT igm_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no,
					// 	igm_details.Pack_Description, igm_details.Pack_Number, igm_detail_container.cont_status,igm_detail_container.cont_size,
					// 	oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'dtl' AS igm_type,
					// 	IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT shed_loc FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
					// 	FROM oracle_nts_data
					// 	INNER JOIN igm_details ON igm_details.id=oracle_nts_data.igm_detail_id
					// 	INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
					// 	WHERE igm_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
					// 	AND oracle_nts_data.cnf_lno LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id) > 0	
						
					// 	UNION 
						
					// 	SELECT igm_sup_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no, igm_supplimentary_detail.Pack_Description, igm_supplimentary_detail.Pack_Number,igm_sup_detail_container.cont_status,igm_sup_detail_container.cont_size,
					// 	oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'sup_dtl' AS igm_type,
					// 	IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT shed_loc FROM shed_tally_info WHERE cont_number=igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
					// 	FROM oracle_nts_data
					// 	INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=oracle_nts_data.igm_sup_detail_id
					// 	INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
					// 	WHERE igm_sup_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
					// 	AND oracle_nts_data.cnf_lno LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number= igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id) > 0";

					// 	$data_assignment = $this->bm->dataSelectDB1($lclAssignmentQuery);
					// }				
				}


				$data_jsInfo = null;
				if($org_license != "")
				{
					$sql_jsInfo = "SELECT vcms_vehicle_agent.id,agent_name AS js_name,agent_type,card_number
					FROM vcms_vehicle_agent
					INNER JOIN vcms_vehicle_agency ON vcms_vehicle_agency.id=vcms_vehicle_agent.agency_id
					WHERE agent_type = 'Jetty Sircar'"; // agency_code = '$org_license' AND

					$data_jsInfo = $this->bm->dataSelectDB1($sql_jsInfo);
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

				// Truck Info
				$data_truck = null;
				if($org_license != ""){
					$sql_truck = "SELECT * FROM do_truck_details_entry WHERE update_by = '$cfLoginId' AND DATE(last_update) = DATE(NOW())";
					$data_truck = $this->bm->dataSelectDB1($sql_truck);
				}
				
				$data['rslt_tmpTrkData'] = $data_truck;
				$data['data_jsInfo'] = $data_jsInfo;
				$data['data_assignment'] = $data_assignment;
				$data['flag'] = 1;
			}
			else if($action == "Add" || $action == "Emergency")
			{
				$assignment = $this->input->post('assignment');
				$data = explode("|",$assignment);
				$contNo = $data[0];
				$rotNo = $data[1];
				$cont_status = $data[2];
				$blNo = $data[3];
				$igm_type = $data[4]; // sup = sup_dtl  & dtl = dtl
				$igm_id = $data[5];
				$verify_no = $data[6];
				$cp_no = $data[7];
				$cont_size = $data[8];
				$gate = $this->input->post('gate');
				$this->session->set_userdata('gateData',$gate);


				// Custom Block Check -- starts

				$blNo = $this->bm->getBlByRotCont($rotNo,$contNo);
				$result = $this->bm->chkBlockedContainerforTruckEntry($contNo,$rotNo,$blNo);
				$custom_block_status = "";
				for($ij = 0; $ij<count($result);$ij++){
					$custom_block_status = $result[$ij]['custom_block_st'];
				}

				if($custom_block_status == "DO_NOT_RELEASE"){
					$this->lcltruckEntryByDSForm("Search",$ain);
					return;
				}
				
				// Custom Block Check -- ends

				// return;
				//$unit_gkey = $data[3];
				//$assignmentType = $data[5];

				$jsGatePass = $this->input->post('jsGatePass');
				$jsId = "";
				$jsName = "";
				$sql_jsid = "SELECT id,agent_name FROM vcms_vehicle_agent WHERE card_number = '$jsGatePass'";
				$data_jsid = $this->bm->dataSelectDB1($sql_jsid);

				for($i=0;$i<count($data_jsid);$i++)
				{
					$jsId = $data_jsid[$i]['id'];
					$jsName =$data_jsid[$i]['agent_name'];
				}

				if($cont_status == "LCL")
				{
					$login_id = $cfLoginId;
					$sql_chkExist = "SELECT COUNT(*) AS rtnValue 
					FROM lcl_dlv_assignment 
					WHERE rot_no='$rotNo' AND bl_no='$blNo'";
					$rslt_chkExist = $this->bm->dataSelectDb1($sql_chkExist);
					$cnt = "";
					for($i=0;count($rslt_chkExist)>$i;$i++)
					{
						$cnt = $rslt_chkExist[$i]['rtnValue'];
					}
					
					// $sql_igmDtlContId = "SELECT igm_details.id AS igm_dtl_id,igm_detail_container.id AS igm_dtl_cont_id,cont_size
					// FROM igm_details
					// INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
					// WHERE Import_Rotation_No='$rotNo' AND cont_number='$contNo'";

					// $rslt_igmDtlContId = $this->bm->dataSelectDb1($sql_igmDtlContId);
					// $igmDtlId = "";
					// $igmDtlContId = "";
					// $cont_size = "";
					// for($i=0;count($rslt_igmDtlContId)>$i;$i++)
					// {
					// 	$igmDtlId = $rslt_igmDtlContId[$i]['igm_dtl_id'];				
					// 	$igmDtlContId = $rslt_igmDtlContId[$i]['igm_dtl_cont_id'];
					// 	$cont_size = $rslt_igmDtlContId[$i]['cont_size'];
					// }
				
					if($cont_size == 20)
						$truck_qty = 2;
					// else if($cont_size == 40)
					else
						$truck_qty = 3;
					
					// $sql_smsNo = "SELECT cf_sms_number 
					// FROM ctmsmis.tmp_oracle_assignment
					// WHERE cf_lic='$org_license' AND cf_sms_number IS NOT NULL";
					// $rslt_smsNo = $this->bm->dataSelect($sql_smsNo);
					// $smsNo = "";
					// for($i=0;count($rslt_smsNo)>$i;$i++)
					// {
					// 	$smsNo = $rslt_smsNo[$i]['cf_sms_number'];
					// }

					//checking part BL

					$partBLQuery = "SELECT COUNT(*) AS rtnValue FROM igm_sup_detail_container 
					INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=igm_sup_detail_container.igm_sup_detail_id
					WHERE cont_number='$contNo' AND Import_Rotation_No='$rotNo' AND cont_status='FCL/PART'";
					$rslt_partBL = $this->bm->dataSelectDb1($partBLQuery);
					$partbl = 0;
					for($i=0;$i<count($rslt_partBL);$i++){
						$partbl = $rslt_partBL[$i]['rtnValue'];
					}

					if($partbl == 0){
						$partBLQuery = "SELECT COUNT(*) AS rtnValue
						FROM igm_detail_container
						INNER JOIN igm_details ON igm_detail_container.igm_detail_id=igm_details.id
						WHERE igm_details.Import_Rotation_No='$rotNo' AND igm_detail_container.cont_number='$contNo' AND cont_status='FCL/PART'";
						$rslt_partBL = $this->bm->dataSelectDb1($partBLQuery);
						$partbl = 0;
						for($i=0;$i<count($rslt_partBL);$i++){
							$partbl = $rslt_partBL[$i]['rtnValue'];
						}
					}

					$partblsts = 0;
					
					if($partbl>0){
						$partblsts = 1;
					}

					if($cnt==0)
					{			
						$lclDlv_query = "INSERT INTO lcl_dlv_assignment (igm_sup_dtl_id,rot_no,bl_no,cp_no,cnf_lic_no,no_of_truck,deliveryDt,igm_type,verify_num,entry_by,entry_at,entry_ip,is_part_bl,jetty_sirkar_id) VALUES('$igm_id','$rotNo','$blNo','$cp_no','$org_license','$truck_qty',date(NOW()),'$igm_type','$verify_no','$login_id',NOW(),'$ipaddr','$partblsts','$jsId')";
						
						if($this->bm->dataInsertDB1($lclDlv_query))
							$msg = "<font color='green'><b>Truck number entered succesfully.</b></font>";
					}
					else
					{
						$sql_updateQtyTruck = "UPDATE lcl_dlv_assignment
						SET no_of_truck='$truck_qty',is_part_bl='$partblsts',entry_by='$login_id'
						WHERE rot_no='$rotNo' AND bl_no='$blNo'";
						
						if($this->bm->dataUpdateDB1($sql_updateQtyTruck))
							$msg = "<font color='green'><b>Truck number updated succesfully.</b></font>";
					}

					//Verify Other Data ID

					$sql_vrfyOtherDataId = "SELECT id FROM lcl_dlv_assignment WHERE rot_no='$rotNo' AND bl_no='$blNo'";
					$data_vrfyOtherDataId = $this->bm->dataSelectDB1($sql_vrfyOtherDataId);
					$vrfyOtherDataId = "";
					for($i=0;count($data_vrfyOtherDataId)>$i;$i++)
					{
						$vrfyOtherDataId = $data_vrfyOtherDataId[$i]['id'];
					}


					$sql_chkJS = "SELECT COUNT(*) AS rtnValue
					FROM lcl_dlv_assignment
					WHERE jetty_sirkar_id='$jsId' AND id='$vrfyOtherDataId'";

					$rslt_chkJS = $this->bm->dataSelectDB1($sql_chkJS);
					$chkJS = $rslt_chkJS[0]['rtnValue'];

					for($i=0;count($rslt_chkJS)>$i;$i++)
					{
						$chkJS = $rslt_chkJS[$i]['rtnValue'];
					}

					if($chkJS == 0)
					{
						$prevJS = "";

						$sql_prevJS = "SELECT jetty_sirkar_id
						FROM lcl_dlv_assignment
						WHERE id='$vrfyOtherDataId'";
						$rslt_prevJS = $this->bm->dataSelectDB1($sql_prevJS);
						$prevJS = $rslt_prevJS[0]['jetty_sirkar_id'];
						
						$sql_updateJS = "UPDATE lcl_dlv_assignment
						SET jetty_sirkar_id='$jsId'
						WHERE id='$vrfyOtherDataId'";
						// return;
						$this->bm->dataUpdateDB1($sql_updateJS);
					}

					// Jetty Sircar Entry -- Ends

					// Add Truck - starts
		
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

					$totTruck = $truck_qty;
					
					$frmSlot = $this->input->post('truckSlot');			// added on 2021-03-01		
					
					$emrgncy_flag = 0;
					$emrgncy_approve_stat = 0;

					if($action == "Emergency")
					{
						$emrgncy_flag = 1;	
					}

					// $strUpdateSlot = "UPDATE ctmsmis.tmp_oracle_assignment SET assignment_slot='$frmSlot' WHERE cont_no='$contNo' AND rot_no='$rotNo'";
					// $this->bm->dataUpdate($strUpdateSlot);
											
					// $sql_timeSlot = "SELECT assignment_slot,assignmentDate,DATE_ADD(assignmentDate, INTERVAL 1 DAY) AS nxtDt
					// FROM ctmsmis.tmp_oracle_assignment
					// WHERE rot_no='$rotNo' AND cont_no='$contNo' AND assignmentDate>=DATE(NOW())";
					// $rslt_timeSlot = $this->bm->dataSelect($sql_timeSlot);

					$sql_timeSlot = "SELECT deliveryDt,DATE_ADD(deliveryDt, INTERVAL 1 DAY) AS nxtDt FROM lcl_dlv_assignment WHERE id = '$vrfyOtherDataId' ORDER BY id DESC LIMIT 1";

					$rslt_timeSlot = $this->bm->dataSelectDB1($sql_timeSlot);

					$asDt = "";
					$asSlot = "";	// commented on 2021-03-01
					$nxtDt = "";
					
					for($j=0;$j<count($rslt_timeSlot);$j++)
					{
						$asDt = $rslt_timeSlot[$j]['deliveryDt'];
						$nxtDt = $rslt_timeSlot[$j]['nxtDt'];
					}

					$asSlot = $frmSlot;

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

					$sql_chkTruck = "SELECT COUNT(*) AS rtnValue
					FROM do_truck_details_entry 
					WHERE truck_id='$truckId' AND visit_time_slot_start='$sSlot' AND visit_time_slot_end='$eSlot'";
					$rslt_chkTruck = $this->bm->dataSelectDB1($sql_chkTruck);
					$chkTruck = "";
					for($i=0;count($rslt_chkTruck)>$i;$i++){
						$chkTruck = $rslt_chkTruck[$i]['rtnValue'];
					}
					
					if($chkTruck==0)
					{
						$strInsertEq = "INSERT INTO do_truck_details_entry(verify_other_data_id,import_rotation,cont_no,truck_id,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,truck_agency_name,truck_agency_phone,gate_no,update_by,ip_addr,last_update,emrgncy_flag,emrgncy_approve_stat,visit_time_slot_start,visit_time_slot_end,entry_from)
						VALUES('$vrfyOtherDataId','$rotNo','$contNo','$truckId','$driverName','$driverPassNo','$assistantName','$assistantPassNo','$agencyName','$agencyPhone','$gate','$login_id','$ipaddr',NOW(),'$emrgncy_flag','$emrgncy_approve_stat','$sSlot','$eSlot','admin')";
						$stat = $this->bm->dataInsertDB1($strInsertEq);
						// $stat = 1;
						if($stat == 1)
							$msg = "<font color='green'><b>Truck added successfully</b></font>";
						
					}
					else
					{
						$msg = "<font color='red'><b>This truck was assigned for this time slot previously</b></font>";
					}

					$sql_updateImporterMbl = "UPDATE lcl_dlv_assignment
					SET importer_mobile_no='$importerMobileNo', truck_slot = '$asSlot'
					WHERE id='$vrfyOtherDataId'";
					$this->bm->dataUpdateDB1($sql_updateImporterMbl);

					// Add Truck - Ends

					// search Data  - Starts

					$sql_cf = "SELECT u_name FROM users WHERE login_id = '$cfLoginId'";
					$data_cf = $this->bm->dataSelectDB1($sql_cf);
					$cf = "";
					for($i=0;count($data_cf)>$i;$i++){
						$cf = $data_cf[$i]['u_name'];
					}
					$data['cf_name'] = $cf;

					$data_assignment = null;
					if($org_license != "")
					{
						$lclAssignmentQuery = "SELECT igm_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no,
						igm_details.Pack_Description, igm_details.Pack_Number, igm_detail_container.cont_status,igm_detail_container.cont_size,
						oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'dtl' AS igm_type,
						IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT CONCAT(shed_yard,' - ',shed_loc) FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
						FROM oracle_nts_data
						INNER JOIN igm_details ON igm_details.id=oracle_nts_data.igm_detail_id
						INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
						WHERE igm_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
						AND REPLACE(LTRIM(REPLACE(oracle_nts_data.cnf_lno,'0',' ')),' ','0') LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id) > 0	
						
						UNION 
						
						SELECT igm_sup_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no, igm_supplimentary_detail.Pack_Description, igm_supplimentary_detail.Pack_Number,igm_sup_detail_container.cont_status,igm_sup_detail_container.cont_size,
						oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'sup_dtl' AS igm_type,
						IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT CONCAT(shed_yard,' - ',shed_loc) FROM shed_tally_info WHERE cont_number=igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
						FROM oracle_nts_data
						INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=oracle_nts_data.igm_sup_detail_id
						INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
						WHERE igm_sup_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
						AND REPLACE(LTRIM(REPLACE(oracle_nts_data.cnf_lno,'0',' ')),' ','0') LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number= igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id) > 0";

						$data_assignment = $this->bm->dataSelectDB1($lclAssignmentQuery);
						
						// if(empty($data_assignment))
						// {
						// if(substr($cnfLic[0], 0, 1)=='0' )
						// {
						// 	$cnfLic_firstpart = substr($cnfLic[0], 1);
						// }
						// else if (substr($cnfLic[0], 0, 2)=='00' )
						// {
						// 	$cnfLic_firstpart = substr($cnfLic[0], 2);
						// }
														
							
						// $lclAssignmentQuery = "SELECT igm_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no,
						// igm_details.Pack_Description, igm_details.Pack_Number, igm_detail_container.cont_status,igm_detail_container.cont_size,
						// oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'dtl' AS igm_type,
						// IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT shed_loc FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
						// FROM oracle_nts_data
						// INNER JOIN igm_details ON igm_details.id=oracle_nts_data.igm_detail_id
						// INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
						// WHERE igm_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
						// AND oracle_nts_data.cnf_lno LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id) > 0	
						
						// UNION 
						
						// SELECT igm_sup_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no, igm_supplimentary_detail.Pack_Description, igm_supplimentary_detail.Pack_Number,igm_sup_detail_container.cont_status,igm_sup_detail_container.cont_size,
						// oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'sup_dtl' AS igm_type,
						// IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT shed_loc FROM shed_tally_info WHERE cont_number=igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
						// FROM oracle_nts_data
						// INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=oracle_nts_data.igm_sup_detail_id
						// INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
						// WHERE igm_sup_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
						// AND oracle_nts_data.cnf_lno LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number= igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id) > 0";

						// $data_assignment = $this->bm->dataSelectDB1($lclAssignmentQuery);
						// }				
					}

					$data_jsInfo = null;
					if($org_license != "")
					{
						$sql_jsInfo = "SELECT vcms_vehicle_agent.id,agent_name AS js_name,agent_type,card_number
						FROM vcms_vehicle_agent
						INNER JOIN vcms_vehicle_agency ON vcms_vehicle_agency.id=vcms_vehicle_agent.agency_id
						WHERE agent_type = 'Jetty Sircar'"; // agency_code = '$org_license' AND

						$data_jsInfo = $this->bm->dataSelectDB1($sql_jsInfo);
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

					// Truck Info
					$data_truck = null;
					if($org_license != ""){
						$sql_truck = "SELECT * FROM do_truck_details_entry WHERE update_by = '$cfLoginId' AND DATE(last_update) = DATE(NOW())";
						$data_truck = $this->bm->dataSelectDB1($sql_truck);
					}
					
					$data['rslt_tmpTrkData'] = $data_truck;
					$data['data_jsInfo'] = $data_jsInfo;
					$data['data_assignment'] = $data_assignment;
					$data['flag'] = 1;
					$data['ain'] = $ain;
					$data['jsGatePass'] = $jsGatePass;
					$data['contNo'] = $contNo;

					// Load Data  -- Ends 

				}
			}
			else if($action == "Update")
			{
				$editType = $this->input->post('editType');
				$assignment = $this->input->post('assignmentData');
				$data = explode("|",$assignment);
				$contNo = $data[0];
				$rotNo = $data[1];
				$cont_status = $data[2];
				$blNo = $data[3];
				$igm_type = $data[4]; // sup = sup_dtl  & dtl = dtl
				$igm_id = $data[5];
				$verify_no = $data[6];
				$cp_no = $data[7];
				$cont_size = $data[8];
				$gate = $this->input->post('gate');

				// Custom Block Check -- starts

				$blNo = $this->bm->getBlByRotCont($rotNo,$contNo);
				$result = $this->bm->chkBlockedContainerforTruckEntry($contNo,$rotNo,$blNo);
				$custom_block_status = "";
				for($ij = 0; $ij<count($result);$ij++){
					$custom_block_status = $result[$ij]['custom_block_st'];
				}
				
				if($custom_block_status == "DO_NOT_RELEASE"){
					$this->lcltruckEntryByDSForm("Search",$ain);
					return;
				}
				
				// Custom Block Check -- ends

				$jsGatePass = $this->input->post('jsGatePass');
				$jsId = "";
				$jsName = "";
				$sql_jsid = "SELECT id,agent_name FROM vcms_vehicle_agent WHERE card_number = '$jsGatePass'";
				$data_jsid = $this->bm->dataSelectDB1($sql_jsid);
				for($i=0;$i<count($data_jsid);$i++)
				{
					$jsId = $data_jsid[$i]['id'];
					$jsName =$data_jsid[$i]['agent_name'];
				}

				if($cont_status == "LCL")
				{
					$login_id = $cfLoginId;
				
					if($cont_size == 20)
						$truck_qty = 2;
					// else if($cont_size == 40)
					else
						$truck_qty = 3;
					
					// $sql_smsNo = "SELECT cf_sms_number 
					// FROM ctmsmis.tmp_oracle_assignment
					// WHERE cf_lic='$org_license' AND cf_sms_number IS NOT NULL";
					// $rslt_smsNo = $this->bm->dataSelect($sql_smsNo);
					// $smsNo = "";
					// for($i=0;count($rslt_smsNo)>$i;$i++)
					// {
					// 	$smsNo = $rslt_smsNo[$i]['cf_sms_number'];
					// }

					$sql_updateQtyTruck = "UPDATE lcl_dlv_assignment
					SET no_of_truck='$truck_qty',entry_by='$login_id'
					WHERE rot_no='$rotNo' AND bl_no='$blNo'";
					
					if($this->bm->dataUpdateDB1($sql_updateQtyTruck))
						$msg = "<font color='green'><b>Truck number updated succesfully.</b></font>";

					//Verify Info FCL ID
					$sql_vrfyOtherDataId = "SELECT id FROM lcl_dlv_assignment WHERE rot_no='$rotNo' AND bl_no='$blNo'";
					$data_vrfyOtherDataId = $this->bm->dataSelectDB1($sql_vrfyOtherDataId);
					$vrfyOtherDataId = "";
					for($i=0;count($data_vrfyOtherDataId)>$i;$i++)
					{
						$vrfyOtherDataId = $data_vrfyOtherDataId[$i]['id'];
					}

					// Jetty Sircar Entry -- Starts 

					$sql_chkJS = "SELECT COUNT(*) AS rtnValue
					FROM lcl_dlv_assignment
					WHERE jetty_sirkar_id='$jsId' AND id='$vrfyOtherDataId'";

					$rslt_chkJS = $this->bm->dataSelectDB1($sql_chkJS);
					$chkJS = $rslt_chkJS[0]['rtnValue'];

					for($i=0;count($rslt_chkJS)>$i;$i++)
					{
						$chkJS = $rslt_chkJS[$i]['rtnValue'];
					}

					if($chkJS == 0)
					{
						$prevJS = "";

						$sql_prevJS = "SELECT jetty_sirkar_id
						FROM lcl_dlv_assignment
						WHERE id='$vrfyOtherDataId'";
						$rslt_prevJS = $this->bm->dataSelectDB1($sql_prevJS);
						$prevJS = $rslt_prevJS[0]['jetty_sirkar_id'];
						
						$sql_updateJS = "UPDATE lcl_dlv_assignment
						SET jetty_sirkar_id='$jsId'
						WHERE id='$vrfyOtherDataId'";
						// return;
						$this->bm->dataUpdateDB1($sql_updateJS);
					}

					// Jetty Sircar Entry -- Ends

					// Edit Truck - starts 
					
					$truckVisitId = $this->input->post('truckVisitId');

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

					$totTruck = $truck_qty;

					// Added on 02 Aug 2021

					$frmSlot = $this->input->post('truckSlot');		
					
					$emrgncy_flag = 0;
					$emrgncy_approve_stat = 0;

					if($action == "Emergency")
					{
						$emrgncy_flag = 1;	
					}

					// $strUpdateSlot = "UPDATE ctmsmis.tmp_oracle_assignment SET assignment_slot='$frmSlot' WHERE cont_no='$contNo' AND rot_no='$rotNo'";
					// $this->bm->dataUpdate($strUpdateSlot);
											
					// $sql_timeSlot = "SELECT assignment_slot,assignmentDate,DATE_ADD(assignmentDate, INTERVAL 1 DAY) AS nxtDt
					// FROM ctmsmis.tmp_oracle_assignment
					// WHERE rot_no='$rotNo' AND cont_no='$contNo' AND assignmentDate>=DATE(NOW())";
					// $rslt_timeSlot = $this->bm->dataSelect($sql_timeSlot);

					$sql_timeSlot = "SELECT deliveryDt,DATE_ADD(deliveryDt, INTERVAL 1 DAY) AS nxtDt FROM lcl_dlv_assignment WHERE id = '$vrfyOtherDataId' ORDER BY id DESC LIMIT 1";

					$rslt_timeSlot = $this->bm->dataSelectDB1($sql_timeSlot);

					$asDt = "";
					$asSlot = "";	// commented on 2021-03-01
					$nxtDt = "";
					
					for($j=0;$j<count($rslt_timeSlot);$j++)
					{
						$asDt = $rslt_timeSlot[$j]['deliveryDt'];
						$nxtDt = $rslt_timeSlot[$j]['nxtDt'];
					}

					$asSlot = $frmSlot;

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

					// Added on 02 Aug 2021

					$payAmt = 57.5;

					if($editType == "Replace")
					{
						// echo "in the function!";
						// return;				
						$sql_replaceInfo = "SELECT id,truck_id,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,paid_amt,paid_method,paid_collect_dt,paid_collect_by,pay_collect_ip
						FROM do_truck_details_entry
						WHERE id='$truckVisitId'";
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
						$repPaidCollectBy = $rslt_replaceInfo[0]['paid_collect_by'];
						$repPaidCollectIp = $rslt_replaceInfo[0]['pay_collect_ip'];
						
						$sql_insertReplace = "INSERT INTO vcms_replace_truck_log(visit_id,truck_id,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,paid_amt,paid_method,paid_collect_dt,replace_time,replace_by,import_rotation,cont_no,paid_collect_by,pay_collect_ip)
						VALUES('$repVisitId','$repTruckId','$repDriverName','$repDriverGatePass','$repAssistantName','$repAssistantGatePass','$repPaidAmt','$repPaidMethod','$repPaidCollectDt',NOW(),'$login_id','$rotNo','$contNo','$repPaidCollectBy','$repPaidCollectIp')";
						$this->bm->dataInsertDB1($sql_insertReplace);
						
						$sql_updateTruckInfo = "UPDATE do_truck_details_entry
						SET truck_id='$truckId',driver_name='$driverName',driver_gate_pass='$driverPassNo',assistant_name='$assistantName',assistant_gate_pass='$assistantPassNo',truck_agency_name='$agencyName',truck_agency_phone='$agencyPhone',paid_amt='',paid_status=0,paid_method='',gate_no = '$gate',paid_collect_dt='',paid_collect_by='',pay_collect_ip='',gate_in_status='0',gate_in_by=NULL,gate_in_time=NULL,last_update=NOW()
						WHERE id='$truckVisitId'";
						$this->bm->dataUpdateDB1($sql_updateTruckInfo);	
					}
					else if($editType == "Edit")			// check with it later
					{
						$sql_updateTruckInfo = "UPDATE do_truck_details_entry
						SET truck_id='$truckId',driver_name='$driverName',driver_gate_pass='$driverPassNo',assistant_name='$assistantName',assistant_gate_pass='$assistantPassNo',truck_agency_name='$agencyName',truck_agency_phone='$agencyPhone',gate_no = '$gate',edit_at=NOW(),edit_by='$login_id',edit_ip='$ipaddr'
						WHERE id='$truckVisitId'";
						$this->bm->dataUpdateDB1($sql_updateTruckInfo);	
					}
					// echo "out of function";
					// return;

					$sql_updateImporterMbl = "UPDATE lcl_dlv_assignment
					SET importer_mobile_no='$importerMobileNo', truck_slot = '$asSlot'
					WHERE id='$vrfyOtherDataId'";
					$this->bm->dataUpdateDB1($sql_updateImporterMbl);
						

					// Edit Truck - Ends

					// Search Data  - Starts

					$sql_cf = "SELECT u_name FROM users WHERE login_id = '$cfLoginId'";
					$data_cf = $this->bm->dataSelectDB1($sql_cf);
					$cf = "";
					for($i=0;count($data_cf)>$i;$i++){
						$cf = $data_cf[$i]['u_name'];
					}
					
					$data['cf_name'] = $cf;

					$data_assignment = null;
					if($org_license != "")
					{
						$lclAssignmentQuery = "SELECT igm_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no,
						igm_details.Pack_Description, igm_details.Pack_Number, igm_detail_container.cont_status,igm_detail_container.cont_size,
						oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'dtl' AS igm_type,
						IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT CONCAT(shed_yard,' - ',shed_loc) FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
						FROM oracle_nts_data
						INNER JOIN igm_details ON igm_details.id=oracle_nts_data.igm_detail_id
						INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
						WHERE igm_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
						AND REPLACE(LTRIM(REPLACE(oracle_nts_data.cnf_lno,'0',' ')),' ','0') LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id) > 0	
						
						UNION 
						
						SELECT igm_sup_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no, igm_supplimentary_detail.Pack_Description, igm_supplimentary_detail.Pack_Number,igm_sup_detail_container.cont_status,igm_sup_detail_container.cont_size,
						oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'sup_dtl' AS igm_type,
						IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT CONCAT(shed_yard,' - ',shed_loc) FROM shed_tally_info WHERE cont_number=igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
						FROM oracle_nts_data
						INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=oracle_nts_data.igm_sup_detail_id
						INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
						WHERE igm_sup_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
						AND REPLACE(LTRIM(REPLACE(oracle_nts_data.cnf_lno,'0',' ')),' ','0') LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number= igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id) > 0";

						$data_assignment = $this->bm->dataSelectDB1($lclAssignmentQuery);
						
						// if(empty($data_assignment))
						// {
						// 	if(substr($cnfLic[0], 0, 1)=='0' )
						// 	{
						// 		$cnfLic_firstpart = substr($cnfLic[0], 1);
						// 	}
						// 	else if (substr($cnfLic[0], 0, 2)=='00' )
						// 	{
						// 		$cnfLic_firstpart = substr($cnfLic[0], 2);
						// 	}
														
							
						// $lclAssignmentQuery = "SELECT igm_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no,
						// igm_details.Pack_Description, igm_details.Pack_Number, igm_detail_container.cont_status,igm_detail_container.cont_size,
						// oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'dtl' AS igm_type,
						// IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT shed_loc FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
						// FROM oracle_nts_data
						// INNER JOIN igm_details ON igm_details.id=oracle_nts_data.igm_detail_id
						// INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
						// WHERE igm_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
						// AND oracle_nts_data.cnf_lno LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id) > 0	
						
						// UNION 
						
						// SELECT igm_sup_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no, igm_supplimentary_detail.Pack_Description, igm_supplimentary_detail.Pack_Number,igm_sup_detail_container.cont_status,igm_sup_detail_container.cont_size,
						// oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'sup_dtl' AS igm_type,
						// IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT shed_loc FROM shed_tally_info WHERE cont_number=igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
						// FROM oracle_nts_data
						// INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=oracle_nts_data.igm_sup_detail_id
						// INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
						// WHERE igm_sup_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
						// AND oracle_nts_data.cnf_lno LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number= igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id) > 0";

						// $data_assignment = $this->bm->dataSelectDB1($lclAssignmentQuery);
						// }				
					}

					$data_jsInfo = null;
					if($org_license != "")
					{
						$sql_jsInfo = "SELECT vcms_vehicle_agent.id,agent_name AS js_name,agent_type,card_number
						FROM vcms_vehicle_agent
						INNER JOIN vcms_vehicle_agency ON vcms_vehicle_agency.id=vcms_vehicle_agent.agency_id
						WHERE agent_type = 'Jetty Sircar'"; // agency_code = '$org_license' AND

						$data_jsInfo = $this->bm->dataSelectDB1($sql_jsInfo);
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

					// Truck Info
					$data_truck = null;
					if($org_license != "")
					{
						$sql_truck = "SELECT * FROM do_truck_details_entry WHERE update_by = '$cfLoginId' AND DATE(last_update) = DATE(NOW())";
						$data_truck = $this->bm->dataSelectDB1($sql_truck);
					}
					
					$data['rslt_tmpTrkData'] = $data_truck;
					$data['data_jsInfo'] = $data_jsInfo;
					$data['data_assignment'] = $data_assignment;
					$data['flag'] = 1;
					$data['ain'] = $ain;
					$data['jsGatePass'] = $jsGatePass;

					// Load Data  -- Ends 

				}
			}

			// Edit Truck 
				
			if($this->input->post('editBtn'))
			{
				$editId = $this->input->post('editId');
				$btnType = $this->input->post('btnType');
				$data['editType'] = $btnType;
				
				$editType = $this->input->post('editBtn');
				$data['editType']=$editType;	

				$sql_trkEditInfo = "SELECT id,verify_other_data_id,truck_id,driver_name,driver_gate_pass,truck_agency_name,cont_no,truck_agency_phone,gate_no,
				(SELECT DISTINCT mobile_number FROM vcms_vehicle_agent WHERE card_number=driver_gate_pass) AS driver_mobile_number,
				assistant_name,assistant_gate_pass,
				(SELECT DISTINCT mobile_number FROM vcms_vehicle_agent WHERE card_number=assistant_gate_pass) AS helper_mobile_number
				FROM do_truck_details_entry
				WHERE id='$editId'";
				$rslt_trkEditInfo = $this->bm->dataSelectDB1($sql_trkEditInfo);
				$data['rslt_trkEditInfo']=$rslt_trkEditInfo;

				$cont = "";
				$vrfyOtherDataId = "";
				for($i=0;$i<count($rslt_trkEditInfo);$i++)
				{
					$vrfyOtherDataId = $rslt_trkEditInfo[$i]['verify_other_data_id'];
					$cont = $rslt_trkEditInfo[$i]['cont_no'];
				}
				$data['cont'] = $cont;

				// Assignment Data

				$sql_assignmentData = "SELECT igm_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no,
				igm_details.Pack_Description, igm_details.Pack_Number, igm_detail_container.cont_status,igm_detail_container.cont_size,
				oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'dtl' AS igm_type,
				IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT CONCAT(shed_yard,' - ',shed_loc) FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
				FROM oracle_nts_data
				INNER JOIN igm_details ON igm_details.id=oracle_nts_data.igm_detail_id
				INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
				WHERE igm_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
				AND REPLACE(LTRIM(REPLACE(oracle_nts_data.cnf_lno,'0',' ')),' ','0') LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number='$cont' AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id) > 0	
				
				UNION 
				
				SELECT igm_sup_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no, igm_supplimentary_detail.Pack_Description, igm_supplimentary_detail.Pack_Number,igm_sup_detail_container.cont_status,igm_sup_detail_container.cont_size,
				oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'sup_dtl' AS igm_type,
				IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT CONCAT(shed_yard,' - ',shed_loc) FROM shed_tally_info WHERE cont_number=igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
				FROM oracle_nts_data
				INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=oracle_nts_data.igm_sup_detail_id
				INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
				WHERE igm_sup_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
				AND REPLACE(LTRIM(REPLACE(oracle_nts_data.cnf_lno,'0',' ')),' ','0') LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number= '$cont' AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id) > 0";
				$rslt_assignmentData = $this->bm->dataSelectDB1($sql_assignmentData);
				// if(empty($rslt_assignmentData))
				// {
				// 	if(substr($cnfLic[0], 0, 1)=='0' )
				// 	{
				// 		$cnfLic_firstpart = substr($cnfLic[0], 1);
				// 	}
				// 	else if (substr($cnfLic[0], 0, 2)=='00' )
				// 	{
				// 		$cnfLic_firstpart = substr($cnfLic[0], 2);
				// 	}
												
					
				// 	$sql_assignmentData = "SELECT igm_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no,
				// 	igm_details.Pack_Description, igm_details.Pack_Number, igm_detail_container.cont_status,igm_detail_container.cont_size,
				// 	oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'dtl' AS igm_type,
				// 	IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT shed_loc FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
				// 	FROM oracle_nts_data
				// 	INNER JOIN igm_details ON igm_details.id=oracle_nts_data.igm_detail_id
				// 	INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
				// 	WHERE igm_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
				// 	AND oracle_nts_data.cnf_lno LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id) > 0	
					
				// 	UNION 
					
				// 	SELECT igm_sup_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no, igm_supplimentary_detail.Pack_Description, igm_supplimentary_detail.Pack_Number,igm_sup_detail_container.cont_status,igm_sup_detail_container.cont_size,
				// 	oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'sup_dtl' AS igm_type,
				// 	IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT shed_loc FROM shed_tally_info WHERE cont_number=igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
				// 	FROM oracle_nts_data
				// 	INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=oracle_nts_data.igm_sup_detail_id
				// 	INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
				// 	WHERE igm_sup_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
				// 	AND oracle_nts_data.cnf_lno LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number= igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id) > 0";

				// 	$rslt_assignmentData = $this->bm->dataSelectDB1($sql_assignmentData);
				// }

				$assignmentData = "";
				for($i=0;$i<count($rslt_assignmentData);$i++)
				{
					$igm_type = $rslt_assignmentData[$i]['igm_type'];

					$igm_id = "";
					if($igm_type == "sup_dtl"){
						$igm_id = $rslt_assignmentData[$i]['igm_sup_detail_id'];
					}else if($igm_type == "dtl"){
						$igm_id = $rslt_assignmentData[$i]['igm_detail_id'];
					}

					if($igm_type == "sup_dtl"){
						$igm_type = "sup";
					}

					$assignmentData = $rslt_assignmentData[$i]['cont_number']."|".$rslt_assignmentData[$i]['imp_rot_no']."|".$rslt_assignmentData[$i]['cont_status']."|".$rslt_assignmentData[$i]['bl_no']."|".$igm_type."|".$igm_id."|".$rslt_assignmentData[$i]['verify_no']."|".$rslt_assignmentData[$i]['cp_no']."|".$rslt_assignmentData[$i]['cont_size'];
				}
				$data['assignmentData'] = $assignmentData;

				// Importer's Mobile

				$sql_importerMobile = "SELECT importer_mobile_no,jetty_sirkar_id,truck_slot FROM lcl_dlv_assignment WHERE id='$vrfyOtherDataId'";
				$rslt_importerMobile = $this->bm->dataSelectDB1($sql_importerMobile);

				$jetty_sirkar_id = "";
				$importerMobile = "";
				$truckSlot = "";
				for($i=0;$i<count($rslt_importerMobile);$i++)
				{
					$importerMobile = $rslt_importerMobile[$i]['importer_mobile_no'];
					$jetty_sirkar_id = $rslt_importerMobile[$i]['jetty_sirkar_id'];
					$truckSlot = $rslt_importerMobile[$i]['truck_slot'];
				}

				$data['truckSlot']=$truckSlot;
				$data['importerMobile']=$importerMobile;
				
				// Jetty Sirkar Card Number

				$sql_jetty_Sirkar = "SELECT card_number FROM vcms_vehicle_agent WHERE id = '$jetty_sirkar_id'";
				$rslt_jetty_Sirkar = $this->bm->dataSelectDB1($sql_jetty_Sirkar);

				$cardNumber = "";
				for($i=0;$i<count($rslt_jetty_Sirkar);$i++)
				{
					$cardNumber = $rslt_jetty_Sirkar[$i]['card_number'];
				}

				$data['cardNumber']=$cardNumber;

				$sql_jsInfo = "SELECT agent_name, agent_code FROM vcms_vehicle_agent WHERE card_number = '$cardNumber'";
				$rslt_jsInfo = $this->bm->dataSelectDB1($sql_jsInfo);

				$agent_name = "";
				$agent_code = "";
				for($i=0;$i<count($rslt_jsInfo);$i++)
				{
					$agent_name = $rslt_jsInfo[$i]['agent_name'];
					$agent_code = $rslt_jsInfo[$i]['agent_code'];
				}

				$data['agent_name'] = $agent_name;
				$data['agent_code'] = $agent_code;
				
				// Search Data

				$sql_cf = "SELECT u_name FROM users WHERE login_id = '$cfLoginId'";
				$data_cf = $this->bm->dataSelectDB1($sql_cf);
				$cf = "";
				for($i=0;count($data_cf)>$i;$i++){
					$cf = $data_cf[$i]['u_name'];
				}
				$data['cf_name'] = $cf;

				$data_assignment = null;
				if($org_license != "")
				{
					$sql_assignment = "SELECT igm_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no,
					igm_details.Pack_Description, igm_details.Pack_Number, igm_detail_container.cont_status,igm_detail_container.cont_size,
					oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'dtl' AS igm_type,
					IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT CONCAT(shed_yard,' - ',shed_loc) FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
					FROM oracle_nts_data
					INNER JOIN igm_details ON igm_details.id=oracle_nts_data.igm_detail_id
					INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
					WHERE igm_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
					AND REPLACE(LTRIM(REPLACE(oracle_nts_data.cnf_lno,'0',' ')),' ','0') LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id) > 0	
					
					UNION 
					
					SELECT igm_sup_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no, igm_supplimentary_detail.Pack_Description, igm_supplimentary_detail.Pack_Number,igm_sup_detail_container.cont_status,igm_sup_detail_container.cont_size,
					oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'sup_dtl' AS igm_type,
					IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT CONCAT(shed_yard,' - ',shed_loc) FROM shed_tally_info WHERE cont_number=igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
					FROM oracle_nts_data
					INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=oracle_nts_data.igm_sup_detail_id
					INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
					WHERE igm_sup_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
					AND REPLACE(LTRIM(REPLACE(oracle_nts_data.cnf_lno,'0',' ')),' ','0') LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number= igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id) > 0";

					$data_assignment = $this->bm->dataSelectDB1($sql_assignment);
					
					// if(empty($data_assignment))
					// {
					// 	if(substr($cnfLic[0], 0, 1)=='0' )
					// 	{
					// 		$cnfLic_firstpart = substr($cnfLic[0], 1);
					// 	}
					// 	else if (substr($cnfLic[0], 0, 2)=='00' )
					// 	{
					// 		$cnfLic_firstpart = substr($cnfLic[0], 2);
					// 	}
													
						
					// $lclAssignmentQuery = "SELECT igm_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no,
					// igm_details.Pack_Description, igm_details.Pack_Number, igm_detail_container.cont_status,igm_detail_container.cont_size,
					// oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'dtl' AS igm_type,
					// IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT shed_loc FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
					// FROM oracle_nts_data
					// INNER JOIN igm_details ON igm_details.id=oracle_nts_data.igm_detail_id
					// INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
					// WHERE igm_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
					// AND oracle_nts_data.cnf_lno LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id) > 0	
					
					// UNION 
					
					// SELECT igm_sup_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no, igm_supplimentary_detail.Pack_Description, igm_supplimentary_detail.Pack_Number,igm_sup_detail_container.cont_status,igm_sup_detail_container.cont_size,
					// oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'sup_dtl' AS igm_type,
					// IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT shed_loc FROM shed_tally_info WHERE cont_number=igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
					// FROM oracle_nts_data
					// INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=oracle_nts_data.igm_sup_detail_id
					// INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
					// WHERE igm_sup_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
					// AND oracle_nts_data.cnf_lno LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number= igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id) > 0";

					// $data_assignment = $this->bm->dataSelectDB1($lclAssignmentQuery);
					// }		
				}

				$data_jsInfo = null;
				if($org_license != "")
				{
					$sql_jsInfo = "SELECT vcms_vehicle_agent.id,agent_name AS js_name,agent_type,card_number
					FROM vcms_vehicle_agent
					INNER JOIN vcms_vehicle_agency ON vcms_vehicle_agency.id=vcms_vehicle_agent.agency_id
					WHERE agent_type = 'Jetty Sircar'"; // agency_code = '$org_license' AND

					$data_jsInfo = $this->bm->dataSelectDB1($sql_jsInfo);
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

				// Truck Info
				$data_truck = null;
				if($org_license != "")
				{
					$sql_truck = "SELECT * FROM do_truck_details_entry WHERE update_by = '$cfLoginId' AND DATE(last_update) = DATE(NOW())";
					$data_truck = $this->bm->dataSelectDB1($sql_truck);
				}

				$data['rslt_tmpTrkData'] = $data_truck;
				$data['data_jsInfo'] = $data_jsInfo;
				$data['data_assignment'] = $data_assignment;
				$data['flag'] = 1;
							
			}

			$data['msg'] = $msg;
			$data['title']="LCL Truck Entry By Admin";
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('lcltruckEntryByAdmin',$data);
			$this->load->view('jsAssetsList');
		}
	}

	// LCL Truck Entry By Admin  -- Ends


	// Truck Pay By Admin

	/*function truckPayByAdmin()
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
			$msg = "";
			$action = $this->input->post('searchByCnfId');
			$ain = $this->input->post('cnfAinNo');
			$cfLoginId = $ain."CF";
			$payAmt = $this->input->post('payAmt');
			$payMethod = $this->input->post('payMethod');
			$truckDtlId = $this->input->post('truckDtlId');
			$payment = $this->input->post('payment');

			if($payment == 'payment')
			{
				$sql_updatePayment = "UPDATE do_truck_details_entry
				SET paid_amt='$payAmt',paid_status='2',paid_method='$payMethod'
				WHERE id='$truckDtlId'";
				$stat = $this->bm->dataUpdateDB1($sql_updatePayment);

			}
			else if($payment == 'showData')
			{
				$data['payFlag'] = 1;
			}

			$sql_lic = "SELECT License_No FROM organization_profiles 
			INNER JOIN users ON users.org_id = organization_profiles.id
			WHERE login_id = '$cfLoginId'";

			$data_lic = $this->bm->dataSelectDB1($sql_lic);
			$org_license = "";
			for($i=0;$i<count($data_lic);$i++)
			{
				$org_license = $data_lic[$i]['License_No'];
			}

			$sql_cf = "SELECT u_name FROM users WHERE login_id = '$cfLoginId'";
			$data_cf = $this->bm->dataSelectDB1($sql_cf);
			$cf = "";
			for($i=0;count($data_cf)>$i;$i++){
				$cf = $data_cf[$i]['u_name'];
			}
			$data['cf_name'] = $cf;

			$data_assignment = null;
			if($org_license != "")
			{
				$sql_assignment = "SELECT cont_no,rot_no,mlo,cf_name AS cf,cont_status,size,ROUND(height,1) AS height,slot AS carrentPosition,Yard_No,Block_No,mfdch_value,assignmentDate,custom_remarks,unit_gkey 
				FROM ctmsmis.tmp_oracle_assignment 
				WHERE cf_lic='$org_license' AND cf_lic!='' AND assignmentDate>=DATE(NOW()) AND mfdch_value NOT IN('CANCEL','APPREF','APPCUS','APPOTH','--') ORDER BY cont_no ASC";

				$data_assignment = $this->bm->dataSelect($sql_assignment);
			}

			$data_jsInfo = null;
			if($org_license != "")
			{
				$sql_jsInfo = "SELECT vcms_vehicle_agent.id,agent_name AS js_name,agent_type,card_number
				FROM vcms_vehicle_agent
				INNER JOIN vcms_vehicle_agency ON vcms_vehicle_agency.id=vcms_vehicle_agent.agency_id
				WHERE agent_type = 'Jetty Sircar'"; // agency_code = '$org_license' AND

				$data_jsInfo = $this->bm->dataSelectDB1($sql_jsInfo);
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

			// Truck Info
			$data_truck = null;
			if($org_license != "")
			{
				$sql_truck = "SELECT * FROM do_truck_details_entry WHERE update_by = '$cfLoginId' AND DATE(last_update) = DATE(NOW())";
				$data_truck = $this->bm->dataSelectDB1($sql_truck);
			}
			
			$data['rslt_tmpTrkData'] = $data_truck;
			$data['data_jsInfo'] = $data_jsInfo;
			$data['data_assignment'] = $data_assignment;
			$data['ain'] = $ain;
			$data['flag'] = 1;
			$data['payAmt'] = $payAmt;
			$data['payMethod'] = $payMethod; 
			$data['truckDtlId'] = $truckDtlId;
			
			$data['msg'] = $msg;
			$data['title']="Truck Entry By Admin";
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('truckEntryByAdmin',$data);
			$this->load->view('jsAssetsList');

		}
	}*/
	
	function truckPayByAdmin()
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
			$msg = "";
			$action = $this->input->post('searchByCnfId');
			$ain = $this->input->post('cnfAinNo');
			$cfLoginId = $ain."CF";
			$payAmt = $this->input->post('payAmt');
			$payMethod = $this->input->post('payMethod');
			$truckDtlId = $this->input->post('truckDtlId');
			$payment = $this->input->post('payment');

			if($payment == 'payment')
			{
				$sql_updatePayment = "UPDATE do_truck_details_entry
				SET paid_amt='$payAmt',paid_status='2',paid_method='$payMethod'
				WHERE id='$truckDtlId'";
				$stat = $this->bm->dataUpdateDB1($sql_updatePayment);

			}
			else if($payment == 'showData')
			{
				$data['payFlag'] = 1;
			}

			$sql_lic = "SELECT License_No FROM organization_profiles 
			INNER JOIN users ON users.org_id = organization_profiles.id
			WHERE login_id = '$cfLoginId'";

			$data_lic = $this->bm->dataSelectDB1($sql_lic);
			$org_license = "";
			for($i=0;$i<count($data_lic);$i++)
			{
				$org_license = $data_lic[$i]['License_No'];
			}

			$sql_cf = "SELECT u_name FROM users WHERE login_id = '$cfLoginId'";
			$data_cf = $this->bm->dataSelectDB1($sql_cf);
			$cf = "";
			for($i=0;count($data_cf)>$i;$i++){
				$cf = $data_cf[$i]['u_name'];
			}
			$data['cf_name'] = $cf;

			$data_assignment = null;
			if($org_license != "")
			{
				$sql_assignment = "SELECT cont_no,rot_no,mlo,cf_name AS cf,cont_status,size,ROUND(height,1) AS height,slot AS carrentPosition,Yard_No,Block_No,mfdch_value,assignmentDate,custom_remarks,unit_gkey 
				FROM ctmsmis.tmp_oracle_assignment 
				WHERE cf_lic='$org_license' AND cf_lic!='' AND assignmentDate>=DATE(NOW()) AND mfdch_value NOT IN('CANCEL','APPREF','APPCUS','APPOTH','--') ORDER BY cont_no ASC";
                //change 1/26/2023
				// $data_assignment = $this->bm->dataSelect($sql_assignment);
				$data_assignment = $this->bm->dataSelectDb2($sql_assignment);
			}

			$data_jsInfo = null;
			if($org_license != "")
			{
				$sql_jsInfo = "SELECT vcms_vehicle_agent.id,agent_name AS js_name,agent_type,card_number
				FROM vcms_vehicle_agent
				INNER JOIN vcms_vehicle_agency ON vcms_vehicle_agency.id=vcms_vehicle_agent.agency_id
				WHERE agent_type = 'Jetty Sircar'"; // agency_code = '$org_license' AND

				$data_jsInfo = $this->bm->dataSelectDB1($sql_jsInfo);
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

			// Truck Info
			$data_truck = null;
			if($org_license != "")
			{
				$sql_truck = "SELECT * FROM do_truck_details_entry WHERE update_by = '$cfLoginId' AND DATE(last_update) = DATE(NOW())";
				$data_truck = $this->bm->dataSelectDB1($sql_truck);
			}
			
			$data['rslt_tmpTrkData'] = $data_truck;
			$data['data_jsInfo'] = $data_jsInfo;
			$data['data_assignment'] = $data_assignment;
			$data['ain'] = $ain;
			$data['flag'] = 1;
			$data['payAmt'] = $payAmt;
			$data['payMethod'] = $payMethod; 
			$data['truckDtlId'] = $truckDtlId;
			
			$data['msg'] = $msg;
			$data['title']="Truck Entry By Admin";
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('truckEntryByAdmin',$data);
			$this->load->view('jsAssetsList');

		}
	}

	// Truck Pay By Admin

	// LCL Truck Pay By Admin - start

	function lcltruckPayByAdmin()
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
			$msg = "";
			$action = $this->input->post('searchByCnfId');
			$ain = $this->input->post('cnfAinNo');
			$cfLoginId = $ain."CF";
			$payAmt = $this->input->post('payAmt');
			$payMethod = $this->input->post('payMethod');
			$truckDtlId = $this->input->post('truckDtlId');
			$payment = $this->input->post('payment');

			if($payment == 'payment')
			{
				$sql_updatePayment = "UPDATE do_truck_details_entry
				SET paid_amt='$payAmt',paid_status='2',paid_method='$payMethod'
				WHERE id='$truckDtlId'";
				$stat = $this->bm->dataUpdateDB1($sql_updatePayment);

			}
			else if($payment == 'showData')
			{
				$data['payFlag'] = 1;
			}

			$sql_lic = "SELECT License_No FROM organization_profiles 
			INNER JOIN users ON users.org_id = organization_profiles.id
			WHERE login_id = '$cfLoginId'";

			$data_lic = $this->bm->dataSelectDB1($sql_lic);
			$org_license = "";
			for($i=0;$i<count($data_lic);$i++)
			{
				$org_license = $data_lic[$i]['License_No'];
			}

			$cnfLic = explode("/", $org_license);
			$cnfLic_firstpart = $cnfLic[0];

			$sql_cf = "SELECT u_name FROM users WHERE login_id = '$cfLoginId'";
			$data_cf = $this->bm->dataSelectDB1($sql_cf);
			$cf = "";
			for($i=0;count($data_cf)>$i;$i++){
				$cf = $data_cf[$i]['u_name'];
			}
			$data['cf_name'] = $cf;

			$data_assignment = null;
			if($org_license != "")
			{
				$lclAssignmentQuery = "SELECT igm_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no,
				igm_details.Pack_Description, igm_details.Pack_Number, igm_detail_container.cont_status,igm_detail_container.cont_size,
				oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'dtl' AS igm_type,
				IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT shed_loc FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
				FROM oracle_nts_data
				INNER JOIN igm_details ON igm_details.id=oracle_nts_data.igm_detail_id
				INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
				WHERE igm_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
				AND oracle_nts_data.cnf_lno LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id) > 0	
				
				UNION 
				
				SELECT igm_sup_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no, igm_supplimentary_detail.Pack_Description, igm_supplimentary_detail.Pack_Number,igm_sup_detail_container.cont_status,igm_sup_detail_container.cont_size,
				oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'sup_dtl' AS igm_type,
				IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT shed_loc FROM shed_tally_info WHERE cont_number=igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
				FROM oracle_nts_data
				INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=oracle_nts_data.igm_sup_detail_id
				INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
				WHERE igm_sup_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
				AND oracle_nts_data.cnf_lno LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number= igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id) > 0";

				$data_assignment = $this->bm->dataSelectDB1($lclAssignmentQuery);
			}

			$data_jsInfo = null;
			if($org_license != "")
			{
				$sql_jsInfo = "SELECT vcms_vehicle_agent.id,agent_name AS js_name,agent_type,card_number
				FROM vcms_vehicle_agent
				INNER JOIN vcms_vehicle_agency ON vcms_vehicle_agency.id=vcms_vehicle_agent.agency_id
				WHERE agent_type = 'Jetty Sircar'"; // agency_code = '$org_license' AND

				$data_jsInfo = $this->bm->dataSelectDB1($sql_jsInfo);
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

			// Truck Info
			$data_truck = null;
			if($org_license != "")
			{
				$sql_truck = "SELECT * FROM do_truck_details_entry WHERE update_by = '$cfLoginId' AND DATE(last_update) = DATE(NOW())";
				$data_truck = $this->bm->dataSelectDB1($sql_truck);
			}
			
			$data['rslt_tmpTrkData'] = $data_truck;
			$data['data_jsInfo'] = $data_jsInfo;
			$data['data_assignment'] = $data_assignment;
			$data['ain'] = $ain;
			$data['flag'] = 1;
			$data['payAmt'] = $payAmt;
			$data['payMethod'] = $payMethod; 
			$data['truckDtlId'] = $truckDtlId;
			
			$data['msg'] = $msg;
			$data['title']="LCL Truck Entry By Admin";
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('lcltruckEntryByAdmin',$data);
			$this->load->view('jsAssetsList');

		}
	}

	// LCL Truck Pay By Admin  -- end
	
	/////////////// Change Truck Info --start

	function changeTruckInfoForm()
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		
		$section = $this->session->userdata('section');
		$login_id = $this->session->userdata('login_id');	
		$data['login_id']=$login_id;
		
		$data['title']="Change Truck Entry Info..";
		$data['msg']="";
		
		$this->load->view('cssAssets');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('changeTruckInfoForm',$data);
		$this->load->view('jsAssets');
	}

	function changeTruckInfo()
	{ 
		// echo "working";return;
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$login_id = $this->session->userdata('login_id');
			$ip_addr = $_SERVER['REMOTE_ADDR'];
			$msg="";
						
			$visit_id=trim($this->input->post('visit_id'));

			$prev_paid_collected_by=$this->input->post('prev_paid_collected_by');
			$current_paid_collected_by=$this->input->post('current_paid_collected_by');
			
			
			$query="INSERT INTO do_truck_info_change_log(visit_id,prev_paid_collected_by,current_paid_collected_by,changed_at,changed_by,ip_addr)
			VALUES('$visit_id','$prev_paid_collected_by','$current_paid_collected_by',NOW(),'$login_id','$ip_addr')";			
			$this->bm->dataInsertDB1($query);
			
			$update_query="UPDATE do_truck_details_entry SET paid_collect_by='$current_paid_collected_by' WHERE id='$visit_id'";		
			$sts=$this->bm->dataUpdateDB1($update_query);						
             			
			if($sts==1){
				$msg="<font color='green'><b>Data Updated SuccesFully</b></font>";
			}
			else{
				$msg="<font color='red'><b>Update failed</b></font>";
			}
			
			$data['title']="Change Truck Entry Info..";
			$data['msg']=$msg;
		
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('changeTruckInfoForm',$data);
			$this->load->view('jsAssets');
		}


	}
	
	////////////////// Change Truck Info --end

	// Driver Dashboard  - start
	function driverDashboard()
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
			$data['title']="Driver Dashboard";

			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('driverDashboard',$data);
			$this->load->view('jsAssetsList');
		}
	}

	function driverYardDelivery()
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
			$data['title']="Yard Delivery";

			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('truckEntryByDriver_Yard',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	
	
	//oracle 
	function driverYardTruckVerify()
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
			$msg = "";
			$cont = $this->input->post('contNo');
			$driverPass = $this->input->post('driverPassNo');
			$driverName = $this->input->post('driverName');
			$assistantPassNo = $this->input->post('assistantPassNo');
			$assistantName = $this->input->post('assistantName');
			$phoneNo = $this->input->post('phoneNo');
			$regCity = $this->input->post('regCity');
			$regClass = $this->input->post('regClass');
			$regTruck = $this->input->post('truckNo');
			$truckNo =  $regCity." ".$regClass." ".$regTruck;
			$slot = $this->input->post('truckSlot');
			
			$chkContQuery = "SELECT rot_no,mlo,cf_name AS cf,cont_status,size,ROUND(height,1) AS height,slot AS carrentPosition,Yard_No,Block_No,mfdch_value,assignmentDate,custom_remarks,unit_gkey 
			FROM ctmsmis.tmp_oracle_assignment 
			WHERE cf_lic!='' AND cont_no='$cont' AND assignmentDate>=DATE(NOW()) AND mfdch_value NOT IN('CANCEL','APPREF','APPCUS','APPOTH','--')";

			
			$contCount = $this->bm->dataSelectDb2($chkContQuery);

			if(count($contCount)>0)
			{	
				$rotNo = "";
				$cont_status = "";
				$unit_gkey = "";
				$assignmentType = "";

				for($i=0;$i<count($contCount);$i++){
					$rotNo = $contCount[$i]['rot_no'];
					$cont_status = $contCount[$i]['cont_status'];
					$unit_gkey = $contCount[$i]['unit_gkey'];
					$assignmentType = $contCount[$i]['mfdch_value'];
				}

				$bl = $this->bm->getBlByRotCont($rotNo,$cont);

				$result = $this->bm->chkBlockedContainerforTruckEntry($cont,$rotNo,$bl);

				$custom_block_status = "";
				for($ij = 0; $ij<count($result);$ij++){
					$custom_block_status = $result[$ij]['custom_block_st'];
				}
				
				if($custom_block_status == "DO_NOT_RELEASE"){
					$msg = "<font color='red' size='4'><b>Custom blocked this container.</b></font>";
				}

				$sql_igmDtlContId = "SELECT cont_size
				FROM igm_details
				INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
				WHERE Import_Rotation_No='$rotNo' AND cont_number='$cont'";
				$rslt_igmDtlContId = $this->bm->dataSelectDb1($sql_igmDtlContId);

				$cont_size = "";
				
				for($i=0;count($rslt_igmDtlContId)>$i;$i++)
				{

					$cont_size = $rslt_igmDtlContId[$i]['cont_size'];
				}

				if($cont_size == 20)
					$truck_qty = 2;
				else
					$truck_qty = 3;

				// check additional truck approved or not
				$additionalTruckQuery = "SELECT truckQty FROM addional_Truck_Permission WHERE cont_no = '$cont'";
				$additionalTruckRslt = $this->bm->dataSelectDB1($additionalTruckQuery);
				
				$additionalTruck = 0; // if no row found at addional_Truck_Permission table then additionalTruck = 0
				$hasApprovement = 0; // assume this cont does't take any additional truck approvement from security

				if(count($additionalTruckRslt)>0){  
					$additionalTruck = $additionalTruckRslt[0]['truckQty']; // assign additional_truck value if row found at addional_Truck_Permission
					$hasApprovement = 1; // will Update if this cont has any additional truck approvement
				}
				
				$truck_qty+=$additionalTruck; // truck_qty = truck_qty + additionalTruck
				
				// Truck Added at do_truck_details_entry table
				$emergencyTruckQuery = "SELECT COUNT(*) AS rtnValue FROM do_truck_details_entry WHERE cont_no = '$cont' AND DATE(last_update) = DATE(NOW())";
				$emergencyTruckRslt = $this->bm->dataReturnDB1($emergencyTruckQuery);

				$emrgncy_flag = 0;

				if($truck_qty <= $emergencyTruckRslt){
					$emrgncy_flag = 1;
				}

				$data['emrgncy_flag'] = $emrgncy_flag;
				$data['hasApprovement'] = $hasApprovement;

				$chkContQuery = "SELECT cf_lic,cf_name FROM ctmsmis.tmp_oracle_assignment WHERE assignmentDate = DATE(NOW()) AND cont_no = '$cont'";
				
				$contCount = $this->bm->dataSelectDb2($chkContQuery);

				$cf_lic = "";
				$cf_name = "";

				for($i=0;$i<count($contCount);$i++){
					$cf_lic = $contCount[$i]['cf_lic'];
					$cf_name = $contCount[$i]['cf_name'];
				}

				$cfAinQuery = "SELECT IFNULL(AIN_No_New,AIN_No) AS AIN_No FROM organization_profiles WHERE License_No = '$cf_lic' LIMIT 1";
				$cfAinRslt = $this->bm->dataSelectDB1($cfAinQuery);
				$ain = "";
				for($i=0;$i<count($cfAinRslt);$i++){
					$ain = $cfAinRslt[$i]['AIN_No'];
				}

				// Truck Info
				$truckQuery = "SELECT * FROM do_truck_details_entry WHERE cont_no = '$cont'";
				$truckRslt = $this->bm->dataSelectDB1($truckQuery);

				// assignment Type for payment
				$assignmentTypeQuery = "SELECT mfdch_value FROM ctmsmis.tmp_oracle_assignment WHERE cont_no = '$cont'";
				
				$assignmentTypeRslt = $this->bm->dataSelectDb2($assignmentTypeQuery);

				$assignmentType = "";

				for($i=0;$i<count($assignmentTypeRslt);$i++){
					$assignmentType = $assignmentTypeRslt[$i]['mfdch_value'];
				}

				// $custom_block_status = "DO_NOT_RELEASE";
				$data['custom_block_status'] = $custom_block_status;
				$data['cont'] = $cont;
				$data['driverPass'] = $driverPass;
				$data['driverName'] = $driverName;
				$data['assistantPassNo'] = $assistantPassNo;
				$data['assistantName'] = $assistantName;
				$data['phoneNo'] = $phoneNo;
				$data['truckNo'] = $truckNo;
				$data['slot'] = $slot;

				$data['assignmentType'] = $assignmentType;
				$data['truckRslt'] = $truckRslt;
				$data['cf_name'] = $cf_name;
				$data['ain'] = $ain;
				$data['cf_lic'] = $cf_lic;

				$data['title']="Yard Delivery Verify";
				$data['msg'] = $msg;

				$this->load->view('cssAssetsList');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('truckEntryByDriver_Verify',$data);
				$this->load->view('jsAssetsList');
			}
			else
			{
				$msg = "<font color='red' size='4'>No Assignment found for this Container Today!</font>";
				$data['msg'] = $msg;
				$data['title']="Yard Delivery";

				$this->load->view('cssAssetsList');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('truckEntryByDriver_Yard',$data);
				$this->load->view('jsAssetsList');
			}

			return;
		}
	}

	function driverYardTruckPay()
	{
		$user = $this->session->userdata('login_id');
		$user=str_replace(" ","",$user);
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$ipaddr = $_SERVER['REMOTE_ADDR'];
		$driver_login_id = $this->session->userdata('login_id');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$msg = "";
			$method = $this->input->post('btnVerify');
			$contNo = $this->input->post('contNo');
			$ain = $this->input->post('ain');
			$org_license = $this->input->post('cf_lic');
			$cfLoginId = $ain."CF";

			$assignmentQuery = "SELECT rot_no,mlo,cf_name AS cf,cont_status,size,ROUND(height,1) AS height,slot AS carrentPosition,Yard_No,Block_No,mfdch_value,assignmentDate,custom_remarks,unit_gkey 
			FROM ctmsmis.tmp_oracle_assignment 
			WHERE cf_lic!='' AND cont_no='$contNo' AND assignmentDate>=DATE(NOW()) AND mfdch_value NOT IN('CANCEL','APPREF','APPCUS','APPOTH','--')";
		
			$assignmentRslt = $this->bm->dataSelectDb2($assignmentQuery);
			
			if(count($assignmentRslt)>0)
			{
				$rotNo = "";
				$cont_status = "";
				$unit_gkey = "";
				$assignmentType = "";

				for($i=0;$i<count($assignmentRslt);$i++){
					$rotNo = $assignmentRslt[$i]['rot_no'];
					$cont_status = $assignmentRslt[$i]['cont_status'];
					$unit_gkey = $assignmentRslt[$i]['unit_gkey'];
					$assignmentType = $assignmentRslt[$i]['mfdch_value'];
				}

				//checking emergency truck -- start 

				$emrgncy_flag = 0;
				$emrgncy_approve_stat = 0;

				$emergencyTruckQuery = "SELECT COUNT(*) AS rtnValue FROM do_truck_details_entry WHERE cont_no = '$contNo' AND DATE(last_update) = DATE(NOW())";
				$emergencyTruckRslt = $this->bm->dataReturnDB1($emergencyTruckQuery);

				//-------------------

				$sql_igmDtlContId = "SELECT igm_details.id AS igm_dtl_id,igm_detail_container.id AS igm_dtl_cont_id,cont_size
				FROM igm_details
				INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
				WHERE Import_Rotation_No='$rotNo' AND cont_number='$contNo'";
				$rslt_igmDtlContId = $this->bm->dataSelectDb1($sql_igmDtlContId);
				$igmDtlId = "";
				$igmDtlContId = "";
				$cont_size = "";
				
				for($i=0;count($rslt_igmDtlContId)>$i;$i++)
				{
					$igmDtlId = $rslt_igmDtlContId[$i]['igm_dtl_id'];				
					$igmDtlContId = $rslt_igmDtlContId[$i]['igm_dtl_cont_id'];
					$cont_size = $rslt_igmDtlContId[$i]['cont_size'];
				}

			
				if($cont_size == 20)
					$truck_qty = 2;
				// else if($cont_size == 40)
				else
					$truck_qty = 3;

				// check additional truck approved or not
				$additionalTruckQuery = "SELECT truckQty FROM addional_Truck_Permission WHERE cont_no = '$contNo'";
				$additionalTruckRslt = $this->bm->dataSelectDB1($additionalTruckQuery);
				
				$additionalTruck = 0; // if no row found at addional_Truck_Permission table then additionalTruck = 0

				if(count($additionalTruckRslt)>0){  
					$additionalTruck = $additionalTruckRslt[0]['truckQty']; // assign additional_truck value if row found at addional_Truck_Permission
				}
				
				$truck_qty+=$additionalTruck; // truck_qty = truck_qty + additionalTruck

				$truckId = $this->input->post('truckNo');

				$sameTruckChkQuery = "SELECT count(*) AS rtnValue FROM do_truck_details_entry WHERE truck_id = '$truckId' AND cont_no = '$contNo' AND paid_status = 0";
				$sameTruckChk = $this->bm->dataReturnDB1($sameTruckChkQuery);

				if($sameTruckChk>0){
					$emergencyTruckRslt--;
				}

				if($truck_qty <= $emergencyTruckRslt){
					$emrgncy_flag = 1;
				}

				if($emrgncy_flag == 0)
				{
					$login_id = $cfLoginId;
				
					$sql_chkExist = "SELECT COUNT(*) AS rtnValue 
					FROM verify_info_fcl 
					WHERE rotation='$rotNo' AND cont_number='$contNo'";
					$rslt_chkExist = $this->bm->dataSelectDb1($sql_chkExist);
					$cnt = "";
					for($i=0;count($rslt_chkExist)>$i;$i++)
					{
						$cnt = $rslt_chkExist[$i]['rtnValue'];
					}

					$sql_smsNo = "SELECT cf_sms_number 
					FROM ctmsmis.tmp_oracle_assignment
					WHERE cf_lic='$org_license' AND cf_sms_number IS NOT NULL";
					$rslt_smsNo = $this->bm->dataSelectDb2($sql_smsNo);
					$smsNo = "";
					for($i=0;count($rslt_smsNo)>$i;$i++)
					{
						$smsNo = $rslt_smsNo[$i]['cf_sms_number'];
					}

					//checking part BL

					$partBLQuery = "SELECT COUNT(*) AS rtnValue FROM igm_sup_detail_container 
					INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=igm_sup_detail_container.igm_sup_detail_id
					WHERE cont_number='$contNo' AND Import_Rotation_No='$rotNo' AND cont_status='FCL/PART'";
					$rslt_partBL = $this->bm->dataSelectDb1($partBLQuery);
					$partbl = 0;
					for($i=0;$i<count($rslt_partBL);$i++){
						$partbl = $rslt_partBL[$i]['rtnValue'];
					}

					if($partbl == 0){
						$partBLQuery = "SELECT COUNT(*) AS rtnValue
						FROM igm_detail_container
						INNER JOIN igm_details ON igm_detail_container.igm_detail_id=igm_details.id
						WHERE igm_details.Import_Rotation_No='$rotNo' AND igm_detail_container.cont_number='$contNo' AND cont_status='FCL/PART'";
						$rslt_partBL = $this->bm->dataSelectDb1($partBLQuery);
						$partbl = 0;
						for($i=0;$i<count($rslt_partBL);$i++){
							$partbl = $rslt_partBL[$i]['rtnValue'];
						}
					}

					$partblsts = 0;
						
					if($partbl>0){
						$partblsts = 1;
					}

					if($cnt==0)
					{			
						$sql_insertQtyTruck = "INSERT INTO verify_info_fcl(igm_detail_id,igm_detail_cont_id,assignment_type,cnf_lic_no,cnf_mobile_no,unit_gkey,rotation,cont_number,no_of_truck,is_part_bl,truck_no_by,truck_no_time)
						VALUES('$igmDtlId','$igmDtlContId','$assignmentType','$org_license','$smsNo','$unit_gkey','$rotNo','$contNo','$truck_qty','$partblsts','$login_id',NOW())";
						
						if($this->bm->dataInsertDB1($sql_insertQtyTruck))
							$msg = "<font color='green'><b>Truck number entered succesfully.</b></font>";
					}
					else
					{
						$sql_updateQtyTruck = "UPDATE verify_info_fcl
						SET cnf_mobile_no='$smsNo',unit_gkey='$unit_gkey',no_of_truck='$truck_qty',is_part_bl='$partblsts',truck_no_by='$login_id',truck_no_time=NOW()
						WHERE rotation='$rotNo' AND cont_number='$contNo'";
						
						if($this->bm->dataUpdateDB1($sql_updateQtyTruck))
							$msg = "<font color='green'><b>Truck number updated succesfully.</b></font>";
					}

					//Verify Info FCL ID
					$sql_verifyInfoFclid = "SELECT id FROM verify_info_fcl WHERE rotation='$rotNo' AND cont_number='$contNo'";
					$data_vrfyinfofclId = $this->bm->dataSelectDB1($sql_verifyInfoFclid);
					$vrfyInfoFclId = "";
					for($i=0;count($data_vrfyinfofclId)>$i;$i++)
					{
						$vrfyInfoFclId = $data_vrfyinfofclId[$i]['id'];
					}


					//Driver Mobile No. Update -- start
					$phoneNo = $this->input->post('phoneNo');
					$driverPassNo = $this->input->post('driverPassNo');
					$driverName = $this->input->post('driverName');
					$assistantPassNo = $this->input->post('assistantPassNo');
					$assistantName = $this->input->post('assistantName');
					$truckId = $this->input->post('truckNo');
					$frmSlot = $this->input->post('truckSlot');	

					$driverNoQuery = "SELECT id,card_number,mobile_number FROM vcms_vehicle_agent WHERE card_number = '$driverPassNo' AND agent_type='Driver' ORDER BY last_update DESC";
					$driverNoRslt = $this->bm->dataSelectDB1($driverNoQuery);

					$DriverNo = "";
					for($i=0;$i<count($driverNoRslt);$i++){
						$DriverNo = $driverNoRslt[$i]['mobile_number'];
					}

					if($DriverNo != $phoneNo){
						echo $driverNoUpdtQuery = "UPDATE vcms_vehicle_agent set mobile_number='$phoneNo' WHERE card_number = '$driverPassNo' AND agent_type='Driver'";
						$driverNoRslt = $this->bm->dataSelectDB1($driverNoUpdtQuery);
					}

					//Driver Mobile No. Update -- end

					// Add Truck - starts		

					$totTruck = $truck_qty;

					$strUpdateSlot = "UPDATE ctmsmis.tmp_oracle_assignment SET assignment_slot='$frmSlot' WHERE cont_no='$contNo' AND rot_no='$rotNo'";
					// $this->bm->dataUpdate($strUpdateSlot);
					$this->bm->dataUpdatedb2($strUpdateSlot);
											
					$sql_timeSlot = "SELECT assignment_slot,assignmentDate,DATE_ADD(assignmentDate, INTERVAL 1 DAY) AS nxtDt
					FROM ctmsmis.tmp_oracle_assignment
					WHERE rot_no='$rotNo' AND cont_no='$contNo' AND assignmentDate>=DATE(NOW())";
					$rslt_timeSlot = $this->bm->dataSelectDb2($sql_timeSlot);

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

					$sql_chkTruck = "SELECT COUNT(*) AS rtnValue
					FROM do_truck_details_entry 
					WHERE truck_id='$truckId' AND visit_time_slot_start='$sSlot' AND visit_time_slot_end='$eSlot'"; //sSlot and $eSlot not found without slot
					
					

					$rslt_chkTruck = $this->bm->dataSelectDB1($sql_chkTruck);
					$chkTruck = "";
					for($i=0;count($rslt_chkTruck)>$i;$i++){
						$chkTruck = $rslt_chkTruck[$i]['rtnValue'];
					}
					
					if($chkTruck==0)
					{
						$strInsertEq = "INSERT INTO do_truck_details_entry(verify_info_fcl_id,import_rotation,cont_no,truck_id,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,update_by,ip_addr,last_update,visit_time_slot_start,visit_time_slot_end,emrgncy_flag,emrgncy_approve_stat,drv_login_id,entry_from)
						VALUES('$vrfyInfoFclId','$rotNo','$contNo','$truckId','$driverName','$driverPassNo','$assistantName','$assistantPassNo','$login_id','$ipaddr',NOW(),'$sSlot','$eSlot','$emrgncy_flag','$emrgncy_approve_stat','$driver_login_id','drive')";
						$stat = $this->bm->dataInsertDB1($strInsertEq);
						$stat = 1;
						if($stat == 1)
							$msg = "<font color='green'><b>Truck added successfully</b></font>";
						
					}
					else
					{
						$msg = "<font size='4' color='red'><b>This truck was assigned for this time slot previously</b></font>";
						$data['title']= "Driver Dashboard";
						$data['msg'] = $msg;
						$this->load->view('cssAssetsList');
						$this->load->view('headerTop');
						$this->load->view('sidebar');
						$this->load->view('driverDashboard',$data);
						$this->load->view('jsAssetsList');
					}

					

					// Online Pay Starts Here 

					$visitIdQuery = "SELECT id FROM do_truck_details_entry WHERE cont_no = '$contNo' AND import_rotation = '$rotNo' AND truck_id = '$truckId' AND DATE(last_update) = DATE(NOW())";
					
					$visitRslt = $this->bm->dataSelectDB1($visitIdQuery);
					$visitId = "";

					for($x=0;$x<count($visitRslt);$x++){
						$visitId = $visitRslt[$x]['id'];
					}
					
					$contact = $smsNo;

					if($method=='sonalypay')
					{		
						
						$flag='0';
						
						$find_visitStr = "SELECT count(*) as rtnValue FROM vcms_online_pay where visit_id='$visitId'";
						$checkVisit= $this->bm->dataReturnDB1($find_visitStr);
						
						if($checkVisit>0)
						{
							$sql_Requ = "SELECT MAX(vcms_online_ReqID.max_reqID) AS rtnValue FROM vcms_online_ReqID";
							$requst_id = $this->bm->dataReturnDB1($sql_Requ);
							
							$ref=$requst_id."_".$flag;
							
							$query_update = "UPDATE vcms_online_pay SET RefTranNo='$ref', cnf_login_id='$login_id', requ_id='$requst_id', payer_st = 1 WHERE visit_id = '$visitId'";
							$up_st = $this->bm->dataUpdateDB1($query_update); 
							if($up_st>0)
							{
								$newReq_id=$requst_id+1;
								$newReq_id="0".$newReq_id;		// added now
								$query_update = "UPDATE vcms_online_ReqID SET max_reqID='$newReq_id'";
								$update_st = $this->bm->dataUpdateDB1($query_update); 
							}
						}
						else
						{
							/* $sql_maxRequ = "SELECT MAX(vcms_online_pay.requ_id)+1 AS rtnValue FROM vcms_online_pay";
							$requst_id = $this->bm->dataReturnDB1($sql_maxRequ); */
							$sql_Requ = "SELECT MAX(vcms_online_ReqID.max_reqID) AS rtnValue FROM vcms_online_ReqID";
							$requst_id = $this->bm->dataReturnDB1($sql_Requ);
							$ref=$requst_id."_".$flag;
							$query_txEntry = "INSERT INTO vcms_online_pay (visit_id, RefTranNo, requ_id, tr_amt, challan_amt, assign_type, cnf_login_id, allPay_st, chk_st, gateway, payer_st) VALUES ('$visitId', '$ref', '$requst_id', '50', '7.5', '$assignmentType', '$login_id', 0 , 1, 'sonali',1)";
							$st=$this->bm->dataInsertDB1($query_txEntry);
							if($st>0)
							{
								$newReq_id=$requst_id+1;
								$newReq_id="0".$newReq_id;		// added now
								$query_update = "UPDATE vcms_online_ReqID SET max_reqID='$newReq_id'";
								$update_st = $this->bm->dataUpdateDB1($query_update); 
							}
						}
						
						//return;
						$data['requst_id'] = $requst_id;
						$data['ref'] = $ref;
						$data['login_id'] = $login_id;
						$data['contact'] = $contact;
						$data['trucVisitId'] = $visitId;
						$data['flag'] = $flag;  //Single Pay
						$data['name'] = $this->session->userdata('User_Name');
						$cus_name= $this->session->userdata('User_Name');
						$data['payAmt'] = $payAmt;
						//$payAmt = 10;
						$this->onlinePay($contNo, $rotNo, $login_id, $contact, $ref, $requst_id, $payAmt, $cus_name, $assignmentType, $cont_status);
					
					}
					else
					{
						// echo "Ekpay";
						// return;
						$flag='0';			
						$find_visitStr = "SELECT count(*) as rtnValue FROM vcms_online_pay where visit_id='$visitId'";
						$checkVisit= $this->bm->dataReturnDB1($find_visitStr);
						//echo $checkVisit;
						if($checkVisit>0)
						{
							$sql_Requ = "SELECT MAX(vcms_online_ekpay_transID.max_transId) AS rtnValue FROM vcms_online_ekpay_transID";
							$requst_id = $this->bm->dataReturnDB1($sql_Requ);
							
							$ref=$requst_id."_".$flag;
							
							$query_update = "UPDATE vcms_online_pay SET RefTranNo='$ref', cnf_login_id='$login_id', requ_id='$requst_id', payer_st = 1 WHERE visit_id = '$visitId'";
							$up_st = $this->bm->dataUpdateDB1($query_update); 
							if($up_st>0)
							{
								$newReq_id=$requst_id+1;
								//$newReq_id="0".$newReq_id;		// added now
								$query_update = "UPDATE vcms_online_ekpay_transID SET max_transId='$newReq_id'";
								$update_st = $this->bm->dataUpdateDB1($query_update); 
							}
						}
						else
						{
							/* $sql_maxRequ = "SELECT MAX(vcms_online_pay.requ_id)+1 AS rtnValue FROM vcms_online_pay";
							$requst_id = $this->bm->dataReturnDB1($sql_maxRequ); */
							$sql_Requ = "SELECT MAX(vcms_online_ekpay_transID.max_transId) AS rtnValue FROM vcms_online_ekpay_transID";
							$requst_id = $this->bm->dataReturnDB1($sql_Requ);
							$ref=$requst_id."_".$flag;
							$query_txEntry = "INSERT INTO vcms_online_pay (visit_id, RefTranNo, rotation, container, requ_id, tr_amt, challan_amt, assign_type, cnf_login_id, allPay_st, chk_st, gateway, payer_st) VALUES ('$visitId', '$ref', '$rotNo', '$contNo', '$requst_id', '50', '7.5', '$assignmentType', '$login_id', 0 , 1, 'ekpay',1)";
							//return;
							$st=$this->bm->dataInsertDB1($query_txEntry);
							if($st>0)
							{
								$newReq_id=$requst_id+1;
								//$newReq_id="0".$newReq_id;		// added now
								$query_update = "UPDATE vcms_online_ekpay_transID SET max_transId='$newReq_id'";
								$update_st = $this->bm->dataUpdateDB1($query_update); 
							}
						}
						
						//return;
						$data['requst_id'] = $requst_id;
						$data['ref'] = $ref;
						$data['login_id'] = $login_id;
						$data['contact'] = $contact;
						$data['trucVisitId'] = $visitId;
						$data['flag'] = $flag;  //Single Pay
						$data['name'] = $this->session->userdata('User_Name');
						$cus_name= $this->session->userdata('User_Name');
						$data['payAmt'] = $payAmt;
						$this->onlineEkPay($contNo, $rotNo, $login_id, $contact, $ref, $requst_id, $payAmt, $cus_name, $assignmentType, $cont_status);

					}
				}
				else
				{
					$msg = "<font color='red'>You can't add emergency truck without port security approvement!</font>";
					$data['msg'] = $msg;
					$data['title']="Yard Delivery";

					$this->load->view('cssAssetsList');
					$this->load->view('headerTop');
					$this->load->view('sidebar');
					$this->load->view('truckEntryByDriver_Yard',$data);
					$this->load->view('jsAssetsList');
				}
			}
			else
			{
				$msg = "<font color='red'>No Assignment found for this Container Today!</font>";
				$data['msg'] = $msg;
				$data['title']="Yard Delivery";

				$this->load->view('cssAssetsList');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('truckEntryByDriver_Yard',$data);
				$this->load->view('jsAssetsList');
			}
		}
	}


	function onlineEkPay($cont=null, $rot=null, $login_id=null, $contact=null, $ref=null, $requst_id=null, $payAmt=null, $name=null, $assignmentType=null, $cont_status=null)
	{
		//echo "1";
		//return;
		$ip_server = $_SERVER['SERVER_ADDR'];
		$host= gethostname();
		$iph = gethostbyname($host);
		//$dt=date("Y-m-d H:i:s", strtotime('+5 hours'));
		$req_timestamp=date("Y-m-d H:i:s", strtotime('+0 hours')).' '."GMT+6";
		//echo $req_timestamp;
		//return;
		$trnx_id=$requst_id;
		//$ref=$requst_id."_".$flag;
		$post_data = '{
		"mer_info":{
		"mer_reg_id":"cpa_test",
		"mer_pas_key":"CtgPa@Ts17"
		},
		"req_timestamp":"'.$req_timestamp.'",
		"feed_uri":{
		"c_uri":"http://cpatos.gov.bd/PcsOracle/index.php/ShedBillController/ekpayPaymentCancel",
		"f_uri":"http://cpatos.gov.bd/PcsOracle/index.php/ShedBillController/ekpayPaymentDecline",
		"s_uri":"http://cpatos.gov.bd/PcsOracle/index.php/ShedBillController/ekpayPaymentSuccess"
		},
		"cust_info":{
		"cust_email":"",
		"cust_id":"'.$ref.'",
		"cust_mail_addr":"dhaka",
		"cust_mobo_no":"'.$contact.'",
		"cust_name":"'.$name.'"
		},
		"trns_info":{
		"ord_det":"'.$cont.'",
		"ord_id":"'.$rot.'",
		"trnx_amt":"'.$payAmt.'",
		"trnx_currency":"BDT",
		"trnx_id":"'.$trnx_id.'"
		},
		"ipn_info":{
		"ipn_channel":"1",
		"ipn_email":"",
		"ipn_uri":"http://cpatos.gov.bd/ekpayRcvInfo/getInfo.php"
		},
		"mac_addr":"1.1.1.1"
		}';
		//echo $post_data;
		//return; 
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://sandbox.ekpay.gov.bd/ekpaypg/v1/merchant-api");


		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json","Authorization: OAuth 2.0 token here"));
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); # KEEP IT FALSE IF YOU RUN FROM LOCAL PC



		curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
		$content = curl_exec($ch);

		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		echo $code;
		//echo $content;
		//$code=0;;
		//return;
		echo "<br>";
		//print_r( $content); 
		//return; 
		$sessionData="";

		if($code == 200) {
		curl_close( $ch);
		$sessionData = $content;
		} else {
		$msg="<font size=4 color=red><b>Network Problem. Please Try again later.</b></font>";
			//echo $rot.'-'.$cont.'-'.$cont_status.'-'.$assignmentType.'-'.$msg;
		//	return;
			$this->cnfTruckEntryForm($rot,$cont,$cont_status,$assignmentType,$msg);
			return;
		}

		$data = json_decode($sessionData, true );
		$token=$data['secure_token'];

		$direct_api_url= "https://sandbox.ekpay.gov.bd/ekpaypg/v1?sToken=".$token."&trnsID=".$trnx_id;

		header('Location:'.$direct_api_url);  
	}
	
	
	function ekpayPaymentSuccess()
	{		
		$transId=trim($_GET['transId']);
		/* echo $transId;
		return; */

		$str_online_dt = "SELECT visit_id, cnf_login_id, assign_type,payer_st  FROM vcms_online_pay_copy WHERE requ_id='$transId'";
		$data_pay = $this->bm->dataSelectDB1($str_online_dt);
		
		for($k = 0; $k < count($data_pay); $k++)
		{
			$visitId = $data_pay[$k]['visit_id'];
			$login_id = $data_pay[$k]['cnf_login_id'];
			$assignmentType = $data_pay[$k]['assign_type'];
			$payer_st = $data_pay[$k]['payer_st'];
			
			$query_update_DO = "UPDATE do_truck_details_entry SET paid_amt = '57.50', paid_status = 1 , paid_method = 'online' , paid_collect_dt = NOW() WHERE id = '$visitId'";
			$updt_st = $this->bm->dataUpdateDB1($query_update_DO);
			
		}

		if($payer_st == 1){
			$driverUserQuery = "SELECT drv_login_id FROM do_truck_details_entry WHERE id = '$visitId'";
			$driverUserRslt = $this->bm->dataSelectDB1($driverUserQuery);
			for($l = 0; $l<count($driverUserRslt); $l++){
				$login_id = $driverUserRslt[$k]['drv_login_id'];
			}
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
		// $rotNo.'--'.$contNo.'--'.$verify_info_fcl_id.'--'.$verify_other_data_id;
		$cont_status = "";

		if($verify_other_data_id == ""){
			$cont_status = "FCL";
		}
		else if($verify_info_fcl_id == "")
		{
			$cont_status = "LCL";
		}
		
		$msg = "<font size=4 color=green> Payment completed successfully.</font>";
		usleep( 250000 );
		//echo '<pre>'; print_r($this->session->all_userdata());

		if($payer_st == 1)
		{
			$data['msg'] = $msg;
			$data['title']="Yard Delivery";

			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('truckEntryByDriver_Yard',$data);
			$this->load->view('jsAssetsList');
		}
		else
		{
			$this->cnfTruckEntryForm($rotNo,$contNo,$cont_status,$assignmentType,$msg);
		}
	}
	
	function ekpayPaymentDecline()
	{		
		$transId=trim($_GET['transId']);
		/* echo $transId;
		return; */

		$str_online_dt = "SELECT visit_id, cnf_login_id, assign_type , payer_st  FROM vcms_online_pay_copy WHERE requ_id='$transId'";
		$data_pay = $this->bm->dataSelectDB1($str_online_dt);
		
		for($k = 0; $k < count($data_pay); $k++)
		{
			$visitId = $data_pay[$k]['visit_id'];
			$login_id = $data_pay[$k]['cnf_login_id'];
			$assignmentType = $data_pay[$k]['assign_type'];
			$payer_st = $data_pay[$k]['payer_st'];
		}

		if($payer_st == 1){
			$driverUserQuery = "SELECT drv_login_id FROM do_truck_details_entry WHERE id = '$visitId'";
			$driverUserRslt = $this->bm->dataSelectDB1($driverUserQuery);
			for($l = 0; $l<count($driverUserRslt); $l++){
				$login_id = $driverUserRslt[$k]['drv_login_id'];
			}
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
		// $rotNo.'--'.$contNo.'--'.$verify_info_fcl_id.'--'.$verify_other_data_id;
		$cont_status = "";

		if($verify_other_data_id == ""){
			$cont_status = "FCL";
		}
		else if($verify_info_fcl_id == "")
		{
			$cont_status = "LCL";
		}
		
		$msg = "<font size=4 color=red> Payment Declined.</font>";
		usleep( 250000 );
		//echo '<pre>'; print_r($this->session->all_userdata());

				
		if($payer_st == 1)
		{
			$data['msg'] = $msg;
			$data['title']="Yard Delivery";

			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('truckEntryByDriver_Yard',$data);
			$this->load->view('jsAssetsList');
		}
		else
		{
			$this->cnfTruckEntryForm($rotNo,$contNo,$cont_status,$assignmentType,$msg);
		}
	}

	function ekpayPaymentCancel()
	{		
		$transId=trim($_GET['transId']);
		/* echo $transId;
		return; */

		$str_online_dt = "SELECT visit_id, cnf_login_id, assign_type, payer_st  FROM vcms_online_pay_copy WHERE requ_id='$transId'";
		$data_pay = $this->bm->dataSelectDB1($str_online_dt);
		
		for($k = 0; $k < count($data_pay); $k++)
		{
			$visitId = $data_pay[$k]['visit_id'];
			$login_id = $data_pay[$k]['cnf_login_id'];
			$assignmentType = $data_pay[$k]['assign_type'];
			$payer_st = $data_pay[$k]['payer_st'];
		}

		if($payer_st == 1){
			$driverUserQuery = "SELECT drv_login_id FROM do_truck_details_entry WHERE id = '$visitId'";
			$driverUserRslt = $this->bm->dataSelectDB1($driverUserQuery);
			for($l = 0; $l<count($driverUserRslt); $l++){
				$login_id = $driverUserRslt[$k]['drv_login_id'];
			}
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
		// $rotNo.'--'.$contNo.'--'.$verify_info_fcl_id.'--'.$verify_other_data_id;
		$cont_status = "";

		if($verify_other_data_id == ""){
			$cont_status = "FCL";
		}
		else if($verify_info_fcl_id == "")
		{
			$cont_status = "LCL";
		}
		
		$msg = "<font size=4 color=red> Payment Cancelled.</font>";
		usleep( 250000 );
		//echo '<pre>'; print_r($this->session->all_userdata());

		if($payer_st == 1)
		{
			$data['msg'] = $msg;
			$data['title']="Yard Delivery";

			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('truckEntryByDriver_Yard',$data);
			$this->load->view('jsAssetsList');
		}
		else
		{
			$this->cnfTruckEntryForm($rotNo,$contNo,$cont_status,$assignmentType,$msg);
		}
	}

	// Driver Shed Delivery Starts Here

	function driverShedDelivery()
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
			$data['title']="Shed Delivery";

			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('truckEntryByDriver_Shed',$data);
			$this->load->view('jsAssetsList');
		}
	}

	function driverShedTruckVerify()
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
			$msg = "";
			$rot = $this->input->post('rot');
			$blNo = $this->input->post('blNo');
			$driverPass = $this->input->post('driverPassNo');
			$driverName = $this->input->post('driverName');
			$assistantPassNo = $this->input->post('assistantPassNo');
			$assistantName = $this->input->post('assistantName');
			$phoneNo = $this->input->post('phoneNo');
			$regCity = $this->input->post('regCity');
			$regClass = $this->input->post('regClass');
			$regTruck = $this->input->post('truckNo');
			$truckNo =  $regCity." ".$regClass." ".$regTruck;

			$slot = $this->input->post('truckSlot');
			
			$chkAssQuery = "SELECT igm_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no,
			igm_details.Pack_Description, igm_details.Pack_Number, igm_detail_container.cont_status,igm_detail_container.cont_size,
			oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'dtl' AS igm_type,
			IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT CONCAT(shed_yard,' - ',shed_loc) FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc,cnf_name,cnf_lno
			FROM oracle_nts_data
			INNER JOIN igm_details ON igm_details.id=oracle_nts_data.igm_detail_id
			INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
			WHERE igm_detail_container.cont_status='LCL' AND imp_rot_no = '$rot' AND oracle_nts_data.bl_no = '$blNo' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
			AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND imp_rot_no=imp_rot_no AND igm_detail_id=igm_details.id) > 0	
			
			UNION 
			
			SELECT igm_sup_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no, igm_supplimentary_detail.Pack_Description, igm_supplimentary_detail.Pack_Number,igm_sup_detail_container.cont_status,igm_sup_detail_container.cont_size,
			oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'sup_dtl' AS igm_type,
			IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT CONCAT(shed_yard,' - ',shed_loc) FROM shed_tally_info WHERE cont_number=igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc,cnf_name,cnf_lno
			FROM oracle_nts_data
			INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=oracle_nts_data.igm_sup_detail_id
			INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
			WHERE igm_sup_detail_container.cont_status='LCL' AND imp_rot_no = '$rot' AND oracle_nts_data.bl_no = '$blNo' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
			AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number= igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id) > 0";

			$assCount = $this->bm->dataSelectDB1($chkAssQuery);

			if(count($assCount)>0)
			{	
				
				$cont_status = "LCL";
				$cont = "";
				$cf_name = "";
				$cf_lno = "";

				for($i=0;$i<count($assCount);$i++){
					$cont = $assCount[$i]['cont_number'];
					$cf_name = $assCount[$i]['cnf_name'];
					$cf_lno = $assCount[$i]['cnf_lno'];
				}

				$result = $this->bm->chkBlockedContainerforTruckEntry($cont,$rot,$blNo);

				$custom_block_status = "";
				for($ij = 0; $ij<count($result);$ij++){
					$custom_block_status = $result[$ij]['custom_block_st'];
				}

				if($custom_block_status == "DO_NOT_RELEASE"){
					$msg = "<font color='red' size='4'><b>Custom blocked this container.</b></font>";
				}

				// $sql_igmDtlContId = "SELECT cont_size
				// FROM igm_details
				// INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
				// WHERE Import_Rotation_No='$rot' AND cont_number='$cont'";
				// $rslt_igmDtlContId = $this->bm->dataSelectDb1($sql_igmDtlContId);

				// $cont_size = "";
				
				// for($i=0;count($rslt_igmDtlContId)>$i;$i++)
				// {

				// 	$cont_size = $rslt_igmDtlContId[$i]['cont_size'];
				// }

				// if($cont_size == 20)
				// 	$truck_qty = 2;
				// else
				// 	$truck_qty = 3;

				// // check additional truck approved or not
				// $additionalTruckQuery = "SELECT truckQty FROM addional_Truck_Permission WHERE cont_no = '$cont'";
				// $additionalTruckRslt = $this->bm->dataSelectDB1($additionalTruckQuery);
				
				// $additionalTruck = 0; // if no row found at addional_Truck_Permission table then additionalTruck = 0
				// $hasApprovement = 0; // assume this cont does't take any additional truck approvement from security

				// if(count($additionalTruckRslt)>0){  
				// 	$additionalTruck = $additionalTruckRslt[0]['truckQty']; // assign additional_truck value if row found at addional_Truck_Permission
				// 	$hasApprovement = 1; // will Update if this cont has any additional truck approvement
				// }
				
				// $truck_qty+=$additionalTruck; // truck_qty = truck_qty + additionalTruck
				
				// // Truck Added at do_truck_details_entry table
				// $emergencyTruckQuery = "SELECT COUNT(*) AS rtnValue FROM do_truck_details_entry WHERE cont_no = '$cont' AND DATE(last_update) = DATE(NOW())";
				// $emergencyTruckRslt = $this->bm->dataReturnDB1($emergencyTruckQuery);


				// $emrgncy_flag = 0;

				// if($truck_qty <= $emergencyTruckRslt){
				// 	$emrgncy_flag = 1;
				// }

				// $data['emrgncy_flag'] = $emrgncy_flag;
				// $data['hasApprovement'] = $hasApprovement;

				$emrgncy_flag = 0;
				$hasApprovement = 0;

				$data['emrgncy_flag'] = $emrgncy_flag;
				$data['hasApprovement'] = $hasApprovement;


				$cfAinQuery = "SELECT login_id,IFNULL(AIN_No_New,AIN_No) AS AIN_No,License_No
				FROM organization_profiles 
				INNER JOIN users ON users.org_id = organization_profiles.id
				WHERE organization_profiles.License_No LIKE '%$cf_lno%'";
				$cfAinRslt = $this->bm->dataSelectDB1($cfAinQuery);

				$cf_lic = "";
				$ain = "";
				for($i=0;$i<count($cfAinRslt);$i++){
					$ain = $cfAinRslt[$i]['AIN_No'];
					$cf_lic = $cfAinRslt[$i]['License_No'];
				}

				// Truck Info
				$truckQuery = "SELECT * FROM do_truck_details_entry WHERE cont_no = '$cont'";
				$truckRslt = $this->bm->dataSelectDB1($truckQuery);

				$data['custom_block_status'] = $custom_block_status;
				$data['rot'] = $rot;
				$data['blNo'] = $blNo;
				$data['driverPass'] = $driverPass;
				$data['driverName'] = $driverName;
				$data['assistantPassNo'] = $assistantPassNo;
				$data['assistantName'] = $assistantName;
				$data['phoneNo'] = $phoneNo;
				$data['truckNo'] = $truckNo;
				$data['slot'] = $slot;
				$data['truckRslt'] = $truckRslt;
				$data['cf_name'] = $cf_name;
				$data['ain'] = $ain;
				$data['cf_lic'] = $cf_lic;

				$data['title']="Shed Delivery Verify";
				$data['msg'] = $msg;

				$this->load->view('cssAssetsList');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('truckEntryByDriver_shedVerify',$data);
				$this->load->view('jsAssetsList');
			}
			else
			{
				$msg = "<font color='red' size='4'>No Assignment found for the Rotation: ".$rot." & BL ".$blNo." Today!</font>";
				$data['msg'] = $msg;
				$data['title']="Shed Delivery";

				$this->load->view('cssAssetsList');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('truckEntryByDriver_Shed',$data);
				$this->load->view('jsAssetsList');
			}
		}
	}

	function driverShedTruckPay()
	{

		$user = $this->session->userdata('login_id');
		$user=str_replace(" ","",$user);
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$ipaddr = $_SERVER['REMOTE_ADDR'];
		$driver_login_id = $this->session->userdata('login_id');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$msg = "";
			$method = $this->input->post('btnVerify');
			$rotNo = $this->input->post('rot');
			$blNo = $this->input->post('blNo');
			$ain = $this->input->post('ain');
			$org_license = $this->input->post('cf_lic');
			$cfLoginId = $ain."CF";

			$assignmentQuery = "SELECT igm_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no,
			igm_details.Pack_Description, igm_details.Pack_Number, igm_detail_container.cont_status,igm_detail_container.cont_size,
			oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'dtl' AS igm_type,
			IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT CONCAT(shed_yard,' - ',shed_loc) FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc,cnf_name,cnf_lno
			FROM oracle_nts_data
			INNER JOIN igm_details ON igm_details.id=oracle_nts_data.igm_detail_id
			INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
			WHERE igm_detail_container.cont_status='LCL' AND imp_rot_no = '$rotNo' AND oracle_nts_data.bl_no = '$blNo' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
			AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND imp_rot_no=imp_rot_no AND igm_detail_id=igm_details.id) > 0	
			
			UNION 
			
			SELECT igm_sup_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no, igm_supplimentary_detail.Pack_Description, igm_supplimentary_detail.Pack_Number,igm_sup_detail_container.cont_status,igm_sup_detail_container.cont_size,
			oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'sup_dtl' AS igm_type,
			IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT CONCAT(shed_yard,' - ',shed_loc) FROM shed_tally_info WHERE cont_number=igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc,cnf_name,cnf_lno
			FROM oracle_nts_data
			INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=oracle_nts_data.igm_sup_detail_id
			INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
			WHERE igm_sup_detail_container.cont_status='LCL' AND imp_rot_no = '$rotNo' AND oracle_nts_data.bl_no = '$blNo' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
			AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number= igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id) > 0";

			$assignmentRslt = $this->bm->dataSelectDB1($assignmentQuery);
			
			if(count($assignmentRslt)>0)
			{
				$cont_status = "LCL";
				$contNo = "";
				$cf_name = "";
				$cf_lno = "";
				$igm_id = "";
				$igm_type = "";
				$cp_no = "";
				$verify_no = "";

				for($i=0;$i<count($assignmentRslt);$i++){
					$contNo = $assignmentRslt[$i]['cont_number'];
					$cf_name = $assignmentRslt[$i]['cnf_name'];
					$cf_lno = $assignmentRslt[$i]['cnf_lno'];
					$igm_id = $assignmentRslt[$i]['igm_id'];
					$igm_type = $assignmentRslt[$i]['igm_type'];
					$cp_no = $assignmentRslt[$i]['cp_no'];
					$verify_no = $assignmentRslt[$i]['verify_no'];
				}

				$sql_igmDtlContId = "SELECT cont_size
				FROM igm_details
				INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
				WHERE Import_Rotation_No='$rotNo' AND cont_number='$contNo'";
				$rslt_igmDtlContId = $this->bm->dataSelectDb1($sql_igmDtlContId);

				$cont_size = "";
				
				for($i=0;count($rslt_igmDtlContId)>$i;$i++)
				{

					$cont_size = $rslt_igmDtlContId[$i]['cont_size'];
				}

				if($cont_size == 20)
					$truck_qty = 2;
				else
					$truck_qty = 3;

				// // check additional truck approved or not
				// $additionalTruckQuery = "SELECT truckQty FROM addional_Truck_Permission WHERE cont_no = '$cont'";
				// $additionalTruckRslt = $this->bm->dataSelectDB1($additionalTruckQuery);
				
				// $additionalTruck = 0; // if no row found at addional_Truck_Permission table then additionalTruck = 0
				// $hasApprovement = 0; // assume this cont does't take any additional truck approvement from security

				// if(count($additionalTruckRslt)>0){  
				// 	$additionalTruck = $additionalTruckRslt[0]['truckQty']; // assign additional_truck value if row found at addional_Truck_Permission
				// 	$hasApprovement = 1; // will Update if this cont has any additional truck approvement
				// }
				
				// $truck_qty+=$additionalTruck; // truck_qty = truck_qty + additionalTruck
				
				// // Truck Added at do_truck_details_entry table
				// $emergencyTruckQuery = "SELECT COUNT(*) AS rtnValue FROM do_truck_details_entry WHERE cont_no = '$contNo' AND DATE(last_update) = DATE(NOW())";
				// $emergencyTruckRslt = $this->bm->dataReturnDB1($emergencyTruckQuery);

				// $emrgncy_flag = 0;

				// if($truck_qty <= $emergencyTruckRslt){
				// 	$emrgncy_flag = 1;
				// }

				// $data['emrgncy_flag'] = $emrgncy_flag;
				// $data['hasApprovement'] = $hasApprovement;
				
				$emrgncy_approve_stat = 0;

				$emrgncy_flag = 0;
				$hasApprovement = 0;

				$data['emrgncy_flag'] = $emrgncy_flag;
				$data['hasApprovement'] = $hasApprovement;

				$login_id = $cfLoginId;
				$sql_chkExist = "SELECT COUNT(*) AS rtnValue 
				FROM lcl_dlv_assignment 
				WHERE rot_no='$rotNo' AND bl_no='$blNo'";
				$rslt_chkExist = $this->bm->dataSelectDb1($sql_chkExist);
				$cnt = "";
				for($i=0;count($rslt_chkExist)>$i;$i++)
				{
					$cnt = $rslt_chkExist[$i]['rtnValue'];
				}

				//checking part BL

				$partBLQuery = "SELECT COUNT(*) AS rtnValue FROM igm_sup_detail_container 
				INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=igm_sup_detail_container.igm_sup_detail_id
				WHERE cont_number='$contNo' AND Import_Rotation_No='$rotNo' AND cont_status='FCL/PART'";
				$rslt_partBL = $this->bm->dataSelectDb1($partBLQuery);
				$partbl = 0;
				for($i=0;$i<count($rslt_partBL);$i++){
					$partbl = $rslt_partBL[$i]['rtnValue'];
				}

				if($partbl == 0){
					$partBLQuery = "SELECT COUNT(*) AS rtnValue
					FROM igm_detail_container
					INNER JOIN igm_details ON igm_detail_container.igm_detail_id=igm_details.id
					WHERE igm_details.Import_Rotation_No='$rotNo' AND igm_detail_container.cont_number='$contNo' AND cont_status='FCL/PART'";
					$rslt_partBL = $this->bm->dataSelectDb1($partBLQuery);
					$partbl = 0;
					for($i=0;$i<count($rslt_partBL);$i++){
						$partbl = $rslt_partBL[$i]['rtnValue'];
					}
				}

				$partblsts = 0;
				
				if($partbl>0){
					$partblsts = 1;
				}

				$frmSlot = $this->input->post('truckSlot');	

				if($cnt==0)
				{			
					$lclDlv_query = "INSERT INTO lcl_dlv_assignment (igm_sup_dtl_id,rot_no,bl_no,cp_no,cnf_lic_no,no_of_truck,deliveryDt,igm_type,verify_num,entry_by,entry_at,entry_ip,is_part_bl,truck_slot) VALUES('$igm_id','$rotNo','$blNo','$cp_no','$org_license','$truck_qty',date(NOW()),'$igm_type','$verify_no','$login_id',NOW(),'$ipaddr','$partblsts','$frmSlot')";
					
					$this->bm->dataInsertDB1($lclDlv_query);
				}
				else
				{
					$sql_updateQtyTruck = "UPDATE lcl_dlv_assignment
					SET no_of_truck='$truck_qty',is_part_bl='$partblsts',entry_by='$login_id'
					WHERE rot_no='$rotNo' AND bl_no='$blNo'";
					
					$this->bm->dataUpdateDB1($sql_updateQtyTruck);
				}

				//Verify Other Data ID

				$sql_vrfyOtherDataId = "SELECT id FROM lcl_dlv_assignment WHERE rot_no='$rotNo' AND bl_no='$blNo'";
				$data_vrfyOtherDataId = $this->bm->dataSelectDB1($sql_vrfyOtherDataId);
				$vrfyOtherDataId = "";
				for($i=0;count($data_vrfyOtherDataId)>$i;$i++)
				{
					$vrfyOtherDataId = $data_vrfyOtherDataId[$i]['id'];
				}

				//Driver Mobile No. Update -- start
				$phoneNo = $this->input->post('phoneNo');
				$driverPassNo = $this->input->post('driverPassNo');
				$driverName = $this->input->post('driverName');
				$assistantPassNo = $this->input->post('assistantPassNo');
				$assistantName = $this->input->post('assistantName');
				$truckId = $this->input->post('truckNo');

				$driverNoQuery = "SELECT id,card_number,mobile_number FROM vcms_vehicle_agent WHERE card_number = '$driverPassNo' AND agent_type='Driver' ORDER BY last_update DESC";
				$driverNoRslt = $this->bm->dataSelectDB1($driverNoQuery);

				$DriverNo = "";
				for($i=0;$i<count($driverNoRslt);$i++){
					$DriverNo = $driverNoRslt[$i]['mobile_number'];
				}

				if($DriverNo != $phoneNo){
					$driverNoUpdtQuery = "UPDATE vcms_vehicle_agent set mobile_number='$phoneNo' WHERE card_number = '$driverPassNo' AND agent_type='Driver'";
					$driverNoRslt = $this->bm->dataSelectDB1($driverNoUpdtQuery);
				}

				//Driver Mobile No. Update -- end

				// Add Truck - starts		

				$totTruck = $truck_qty;

				$sql_timeSlot = "SELECT deliveryDt,DATE_ADD(deliveryDt, INTERVAL 1 DAY) AS nxtDt FROM lcl_dlv_assignment WHERE id = '$vrfyOtherDataId' ORDER BY id DESC LIMIT 1";

				$rslt_timeSlot = $this->bm->dataSelectDB1($sql_timeSlot);

				$asDt = "";
				$asSlot = "";	// commented on 2021-03-01
				$nxtDt = "";
				
				for($j=0;$j<count($rslt_timeSlot);$j++)
				{
					$asDt = $rslt_timeSlot[$j]['deliveryDt'];
					$nxtDt = $rslt_timeSlot[$j]['nxtDt'];
				}

				$asSlot = $frmSlot;

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

				$sql_chkTruck = "SELECT COUNT(*) AS rtnValue
				FROM do_truck_details_entry 
				WHERE truck_id='$truckId' AND visit_time_slot_start='$sSlot' AND visit_time_slot_end='$eSlot'";
				$rslt_chkTruck = $this->bm->dataSelectDB1($sql_chkTruck);
				$chkTruck = "";
				for($i=0;count($rslt_chkTruck)>$i;$i++){
					$chkTruck = $rslt_chkTruck[$i]['rtnValue'];
				}
				
				if($chkTruck==0)
				{
					$strInsertEq = "INSERT INTO do_truck_details_entry(verify_other_data_id,import_rotation,cont_no,truck_id,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,update_by,ip_addr,last_update,emrgncy_flag,emrgncy_approve_stat,visit_time_slot_start,visit_time_slot_end,drv_login_id,entry_from)
					VALUES('$vrfyOtherDataId','$rotNo','$contNo','$truckId','$driverName','$driverPassNo','$assistantName','$assistantPassNo','$login_id','$ipaddr',NOW(),'$emrgncy_flag','$emrgncy_approve_stat','$sSlot','$eSlot','$driver_login_id','driv')";
					$stat = $this->bm->dataInsertDB1($strInsertEq);
					//$stat = 1;
					if($stat == 1)
						$msg = "<font color='green'><b>Truck added successfully</b></font>";
					
				}
				else
				{
					$msg = "<font size='4' color='red'><b>This truck was assigned for this time slot previously</b></font>";
					$data['title']= "Driver Dashboard";
					$data['msg'] = $msg;
					$this->load->view('cssAssetsList');
					$this->load->view('headerTop');
					$this->load->view('sidebar');
					$this->load->view('driverDashboard',$data);
					$this->load->view('jsAssetsList');
					return;
				}

				// Online Pay Starts Here 

				$visitIdQuery = "SELECT id FROM do_truck_details_entry WHERE cont_no = '$contNo' AND import_rotation = '$rotNo' AND truck_id = '$truckId' AND DATE(last_update) = DATE(NOW())";
				
				$visitRslt = $this->bm->dataSelectDB1($visitIdQuery);
				$visitId = "";

				for($x=0;$x<count($visitRslt);$x++){
					$visitId = $visitRslt[$x]['id'];
				}
				
				$assignmentType = "";
				$contact = $phoneNo;

				if($method=='sonalypay')
				{		
					
					$flag='0';
					
					$find_visitStr = "SELECT count(*) as rtnValue FROM vcms_online_pay where visit_id='$visitId'";
					$checkVisit= $this->bm->dataReturnDB1($find_visitStr);
					
					if($checkVisit>0)
					{
						$sql_Requ = "SELECT MAX(vcms_online_ReqID.max_reqID) AS rtnValue FROM vcms_online_ReqID";
						$requst_id = $this->bm->dataReturnDB1($sql_Requ);
						
						$ref=$requst_id."_".$flag;
						
						$query_update = "UPDATE vcms_online_pay SET RefTranNo='$ref', cnf_login_id='$login_id', requ_id='$requst_id', payer_st = 1 WHERE visit_id = '$visitId'";
						$up_st = $this->bm->dataUpdateDB1($query_update); 
						if($up_st>0)
						{
							$newReq_id=$requst_id+1;
							$newReq_id="0".$newReq_id;		// added now
							$query_update = "UPDATE vcms_online_ReqID SET max_reqID='$newReq_id'";
							$update_st = $this->bm->dataUpdateDB1($query_update); 
						}
					}
					else
					{
						/* $sql_maxRequ = "SELECT MAX(vcms_online_pay.requ_id)+1 AS rtnValue FROM vcms_online_pay";
						$requst_id = $this->bm->dataReturnDB1($sql_maxRequ); */
						$sql_Requ = "SELECT MAX(vcms_online_ReqID.max_reqID) AS rtnValue FROM vcms_online_ReqID";
						$requst_id = $this->bm->dataReturnDB1($sql_Requ);
						$ref=$requst_id."_".$flag;
						$query_txEntry = "INSERT INTO vcms_online_pay (visit_id, RefTranNo, requ_id, tr_amt, challan_amt, assign_type, cnf_login_id, allPay_st, chk_st, gateway, payer_st) VALUES ('$visitId', '$ref', '$requst_id', '50', '7.5', '$assignmentType', '$login_id', 0 , 1, 'sonali',1)";
						$st=$this->bm->dataInsertDB1($query_txEntry);
						if($st>0)
						{
							$newReq_id=$requst_id+1;
							$newReq_id="0".$newReq_id;		// added now
							$query_update = "UPDATE vcms_online_ReqID SET max_reqID='$newReq_id'";
							$update_st = $this->bm->dataUpdateDB1($query_update); 
						}
					}
					
					//return;
					$data['requst_id'] = $requst_id;
					$data['ref'] = $ref;
					$data['login_id'] = $login_id;
					$data['contact'] = $contact;
					$data['trucVisitId'] = $visitId;
					$data['flag'] = $flag;  //Single Pay
					$data['name'] = $this->session->userdata('User_Name');
					$cus_name= $this->session->userdata('User_Name');
					$data['payAmt'] = $payAmt;
					//$payAmt = 10;
					$this->onlinePay($contNo, $rotNo, $login_id, $contact, $ref, $requst_id, $payAmt, $cus_name, $assignmentType, $cont_status);
				
				}
				else
				{
					// echo "Ekpay";
					// return;
					$flag='0';			
					$find_visitStr = "SELECT count(*) as rtnValue FROM vcms_online_pay where visit_id='$visitId'";
					$checkVisit= $this->bm->dataReturnDB1($find_visitStr);
					//echo $checkVisit;
					if($checkVisit>0)
					{
						$sql_Requ = "SELECT MAX(vcms_online_ekpay_transID.max_transId) AS rtnValue FROM vcms_online_ekpay_transID";
						$requst_id = $this->bm->dataReturnDB1($sql_Requ);
						
						$ref=$requst_id."_".$flag;
						
						$query_update = "UPDATE vcms_online_pay SET RefTranNo='$ref', cnf_login_id='$login_id', requ_id='$requst_id', payer_st = 1 WHERE visit_id = '$visitId'";
						$up_st = $this->bm->dataUpdateDB1($query_update); 
						if($up_st>0)
						{
							$newReq_id=$requst_id+1;
							//$newReq_id="0".$newReq_id;		// added now
							$query_update = "UPDATE vcms_online_ekpay_transID SET max_transId='$newReq_id'";
							$update_st = $this->bm->dataUpdateDB1($query_update); 
						}
					}
					else
					{
						/* $sql_maxRequ = "SELECT MAX(vcms_online_pay.requ_id)+1 AS rtnValue FROM vcms_online_pay";
						$requst_id = $this->bm->dataReturnDB1($sql_maxRequ); */
						$sql_Requ = "SELECT MAX(vcms_online_ekpay_transID.max_transId) AS rtnValue FROM vcms_online_ekpay_transID";
						$requst_id = $this->bm->dataReturnDB1($sql_Requ);
						$ref=$requst_id."_".$flag;
						$query_txEntry = "INSERT INTO vcms_online_pay (visit_id, RefTranNo, rotation, container, requ_id, tr_amt, challan_amt, assign_type, cnf_login_id, allPay_st, chk_st, gateway, payer_st) VALUES ('$visitId', '$ref', '$rotNo', '$contNo', '$requst_id', '50', '7.5', '$assignmentType', '$login_id', 0 , 1, 'ekpay',1)";
						//return;
						$st=$this->bm->dataInsertDB1($query_txEntry);
						if($st>0)
						{
							$newReq_id=$requst_id+1;
							//$newReq_id="0".$newReq_id;		// added now
							$query_update = "UPDATE vcms_online_ekpay_transID SET max_transId='$newReq_id'";
							$update_st = $this->bm->dataUpdateDB1($query_update); 
						}
					}
					
					//return;
					$data['requst_id'] = $requst_id;
					$data['ref'] = $ref;
					$data['login_id'] = $login_id;
					$data['contact'] = $contact;
					$data['trucVisitId'] = $visitId;
					$data['flag'] = $flag;  //Single Pay
					$data['name'] = $this->session->userdata('User_Name');
					$cus_name= $this->session->userdata('User_Name');
					$data['payAmt'] = $payAmt;
					$this->onlineEkPay($contNo, $rotNo, $login_id, $contact, $ref, $requst_id, $payAmt, $cus_name, $assignmentType, $cont_status);

				}
				
			}
			else
			{
				$msg = "<font color='red'>No Assignment found for the Rotation: ".$rot." & BL ".$blNo." Today!</font>";
				$data['msg'] = $msg;
				$data['title']="Shed Delivery";

				$this->load->view('cssAssetsList');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('truckEntryByDriver_Shed',$data);
				$this->load->view('jsAssetsList');
			}

		}
	}

	// Driver Shed Delivery Ends Here

	// Driver OCD Delivery Starts Here

	function driverOcdDelivery()
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
			$data['title']="OCD Delivery";

			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('truckEntryByDriver_Ocd',$data);
			$this->load->view('jsAssetsList');
		}
	}
	
	
	function driverOcdTruckVerify()
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
			$msg = "";
			$cont = $this->input->post('contNo');
			$driverPass = $this->input->post('driverPassNo');
			$driverName = $this->input->post('driverName');
			$assistantPassNo = $this->input->post('assistantPassNo');
			$assistantName = $this->input->post('assistantName');
			$phoneNo = $this->input->post('phoneNo');
			$regCity = $this->input->post('regCity');
			$regClass = $this->input->post('regClass');
			$regTruck = $this->input->post('truckNo');
			$truckNo =  $regCity." ".$regClass." ".$regTruck;

			$slot = $this->input->post('truckSlot');
			
			$chkContQuery = "SELECT rot_no,mlo,cf_name AS cf,cont_status,size,ROUND(height,1) AS height,slot AS carrentPosition,Yard_No,Block_No,mfdch_value,assignmentDate,custom_remarks,unit_gkey 
			FROM ctmsmis.tmp_oracle_assignment 
			WHERE cf_lic!='' AND cont_no='$cont' AND assignmentDate>=DATE(NOW()) AND mfdch_value IN('OCD')";

			
			$contCount = $this->bm->dataSelectDb2($chkContQuery);

			if(count($contCount)>0)
			{	
				$rotNo = "";
				$cont_status = "";
				$unit_gkey = "";
				$assignmentType = "";

				for($i=0;$i<count($contCount);$i++){
					$rotNo = $contCount[$i]['rot_no'];
					$cont_status = $contCount[$i]['cont_status'];
					$unit_gkey = $contCount[$i]['unit_gkey'];
					$assignmentType = $contCount[$i]['mfdch_value'];
				}

				$bl = $this->bm->getBlByRotCont($rotNo,$cont);

				$result = $this->bm->chkBlockedContainerforTruckEntry($cont,$rotNo,$bl);

				$custom_block_status = "";
				for($ij = 0; $ij<count($result);$ij++){
					$custom_block_status = $result[$ij]['custom_block_st'];
				}

				if($custom_block_status == "DO_NOT_RELEASE"){
					$msg = "<font color='red' size='4'><b>Custom blocked this container.</b></font>";
				}



				$chkContQuery = "SELECT cf_lic,cf_name FROM ctmsmis.tmp_oracle_assignment WHERE assignmentDate = DATE(NOW()) AND cont_no = '$cont'";
				
				$contCount = $this->bm->dataSelectDb2($chkContQuery);

				$cf_lic = "";
				$cf_name = "";

				for($i=0;$i<count($contCount);$i++){
					$cf_lic = $contCount[$i]['cf_lic'];
					$cf_name = $contCount[$i]['cf_name'];
				}

				$cfAinQuery = "SELECT IFNULL(AIN_No_New,AIN_No) AS AIN_No FROM organization_profiles WHERE License_No = '$cf_lic' LIMIT 1";
				$cfAinRslt = $this->bm->dataSelectDB1($cfAinQuery);
				$ain = "";
				for($i=0;$i<count($cfAinRslt);$i++){
					$ain = $cfAinRslt[$i]['AIN_No'];
				}

				// Truck Info
				$truckQuery = "SELECT * FROM do_truck_details_entry WHERE cont_no = '$cont'";
				$truckRslt = $this->bm->dataSelectDB1($truckQuery);

				

				$assignmentType = "OCD";

				
				$data['custom_block_status'] = $custom_block_status;
				$data['cont'] = $cont;
				$data['driverPass'] = $driverPass;
				$data['driverName'] = $driverName;
				$data['assistantPassNo'] = $assistantPassNo;
				$data['assistantName'] = $assistantName;
				$data['phoneNo'] = $phoneNo;
				$data['truckNo'] = $truckNo;
				$data['slot'] = $slot;

				$data['assignmentType'] = $assignmentType;
				$data['truckRslt'] = $truckRslt;
				$data['cf_name'] = $cf_name;
				$data['ain'] = $ain;
				$data['cf_lic'] = $cf_lic;

				$data['title']="OCD Delivery Verify";
				$data['msg'] = $msg;

				$this->load->view('cssAssetsList');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('truckEntryByDriver_OcdVerify',$data);
				$this->load->view('jsAssetsList');
			}
			else
			{
				$msg = "<font color='red'>No OCD Assignment found for this Container Today!</font>";
				$data['msg'] = $msg;
				$data['title']="OCD Delivery";

				$this->load->view('cssAssetsList');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('truckEntryByDriver_Ocd',$data);
				$this->load->view('jsAssetsList');
			}

			return;
		}
	}


	


	
	
	
	function driverOcdTruckPay()
	{
		$user = $this->session->userdata('login_id');
		$user=str_replace(" ","",$user);
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$ipaddr = $_SERVER['REMOTE_ADDR'];
		$driver_login_id = $this->session->userdata('login_id');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$msg = "";
			$method = $this->input->post('btnVerify');
			$contNo = $this->input->post('contNo');
			$ain = $this->input->post('ain');
			$org_license = $this->input->post('cf_lic');
			$cfLoginId = $ain."CF";

			$assignmentQuery = "SELECT rot_no,mlo,cf_name AS cf,cont_status,size,ROUND(height,1) AS height,slot AS carrentPosition,Yard_No,Block_No,mfdch_value,assignmentDate,custom_remarks,unit_gkey 
			FROM ctmsmis.tmp_oracle_assignment 
			WHERE cf_lic!='' AND cont_no='$contNo' AND assignmentDate>=DATE(NOW()) AND mfdch_value IN('OCD')";
			// $assignmentRslt = $this->bm->dataSelect($assignmentQuery);
			$assignmentRslt = $this->bm->dataSelectDb2($assignmentQuery);
			
			if(count($assignmentRslt)>0)
			{
				$rotNo = "";
				$cont_status = "";
				$unit_gkey = "";
				$assignmentType = "";

				for($i=0;$i<count($assignmentRslt);$i++){
					$rotNo = $assignmentRslt[$i]['rot_no'];
					$cont_status = $assignmentRslt[$i]['cont_status'];
					$unit_gkey = $assignmentRslt[$i]['unit_gkey'];
					$assignmentType = $assignmentRslt[$i]['mfdch_value'];
				}

				//checking emergency truck -- start 

				$emrgncy_flag = 0;
				$emrgncy_approve_stat = 0;

				$emergencyTruckQuery = "SELECT COUNT(*) AS rtnValue FROM do_truck_details_entry WHERE cont_no = '$contNo' AND DATE(last_update) = DATE(NOW())";
				$emergencyTruckRslt = $this->bm->dataReturnDB1($emergencyTruckQuery);

				//-------------------

				$sql_igmDtlContId = "SELECT igm_details.id AS igm_dtl_id,igm_detail_container.id AS igm_dtl_cont_id,cont_size
				FROM igm_details
				INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
				WHERE Import_Rotation_No='$rotNo' AND cont_number='$contNo'";
				$rslt_igmDtlContId = $this->bm->dataSelectDb1($sql_igmDtlContId);
				$igmDtlId = "";
				$igmDtlContId = "";
				$cont_size = "";
				
				for($i=0;count($rslt_igmDtlContId)>$i;$i++)
				{
					$igmDtlId = $rslt_igmDtlContId[$i]['igm_dtl_id'];				
					$igmDtlContId = $rslt_igmDtlContId[$i]['igm_dtl_cont_id'];
					$cont_size = $rslt_igmDtlContId[$i]['cont_size'];
				}

				if($cont_size == 20)
					$truck_qty = 2;
				else
					$truck_qty = 3;

				
				
				$additionalTruck = 0; // if no row found at addional_Truck_Permission table then additionalTruck = 0

		

				$truckId = $this->input->post('truckNo');

			

				if($emrgncy_flag == 0)
				{
					$login_id = $cfLoginId;
				
					$sql_chkExist = "SELECT COUNT(*) AS rtnValue 
					FROM verify_info_fcl 
					WHERE rotation='$rotNo' AND cont_number='$contNo'";
					$rslt_chkExist = $this->bm->dataSelectDb1($sql_chkExist);
					$cnt = "";
					for($i=0;count($rslt_chkExist)>$i;$i++)
					{
						$cnt = $rslt_chkExist[$i]['rtnValue'];
					}


					$sql_smsNo = "SELECT cf_sms_number 
					FROM ctmsmis.tmp_oracle_assignment
					WHERE cf_lic='$org_license' AND cf_sms_number IS NOT NULL";
					// $rslt_smsNo = $this->bm->dataSelect($sql_smsNo);
					$rslt_smsNo = $this->bm->dataSelectDb2($sql_smsNo);
					$smsNo = "";
					for($i=0;count($rslt_smsNo)>$i;$i++)
					{
						$smsNo = $rslt_smsNo[$i]['cf_sms_number'];
					}

					//checking part BL

					$partBLQuery = "SELECT COUNT(*) AS rtnValue FROM igm_sup_detail_container 
					INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=igm_sup_detail_container.igm_sup_detail_id
					WHERE cont_number='$contNo' AND Import_Rotation_No='$rotNo' AND cont_status='FCL/PART'";
					$rslt_partBL = $this->bm->dataSelectDb1($partBLQuery);
					$partbl = 0;
					for($i=0;$i<count($rslt_partBL);$i++){
						$partbl = $rslt_partBL[$i]['rtnValue'];
					}

					if($partbl == 0){
						$partBLQuery = "SELECT COUNT(*) AS rtnValue
						FROM igm_detail_container
						INNER JOIN igm_details ON igm_detail_container.igm_detail_id=igm_details.id
						WHERE igm_details.Import_Rotation_No='$rotNo' AND igm_detail_container.cont_number='$contNo' AND cont_status='FCL/PART'";
						$rslt_partBL = $this->bm->dataSelectDb1($partBLQuery);
						$partbl = 0;
						for($i=0;$i<count($rslt_partBL);$i++){
							$partbl = $rslt_partBL[$i]['rtnValue'];
						}
					}

					$partblsts = 0;
						
					if($partbl>0){
						$partblsts = 1;
					}

					if($cnt==0)
					{			
						$sql_insertQtyTruck = "INSERT INTO verify_info_fcl(igm_detail_id,igm_detail_cont_id,assignment_type,cnf_lic_no,cnf_mobile_no,unit_gkey,rotation,cont_number,no_of_truck,is_part_bl,truck_no_by,truck_no_time)
						VALUES('$igmDtlId','$igmDtlContId','$assignmentType','$org_license','$smsNo','$unit_gkey','$rotNo','$contNo','$truck_qty','$partblsts','$login_id',NOW())";
						
						if($this->bm->dataInsertDB1($sql_insertQtyTruck))
							$msg = "<font color='green'><b>Truck number entered succesfully.</b></font>";
					}
					else
					{
						$sql_updateQtyTruck = "UPDATE verify_info_fcl
						SET cnf_mobile_no='$smsNo',unit_gkey='$unit_gkey',no_of_truck='$truck_qty',is_part_bl='$partblsts',truck_no_by='$login_id',truck_no_time=NOW()
						WHERE rotation='$rotNo' AND cont_number='$contNo'";
						
						if($this->bm->dataUpdateDB1($sql_updateQtyTruck))
							$msg = "<font color='green'><b>Truck number updated succesfully.</b></font>";
					}

					//Verify Info FCL ID
					$sql_verifyInfoFclid = "SELECT id FROM verify_info_fcl WHERE rotation='$rotNo' AND cont_number='$contNo'";
					$data_vrfyinfofclId = $this->bm->dataSelectDB1($sql_verifyInfoFclid);
					$vrfyInfoFclId = "";
					for($i=0;count($data_vrfyinfofclId)>$i;$i++)
					{
						$vrfyInfoFclId = $data_vrfyinfofclId[$i]['id'];
					}


					//Driver Mobile No. Update -- start
					$phoneNo = $this->input->post('phoneNo');
					$driverPassNo = $this->input->post('driverPassNo');
					$driverName = $this->input->post('driverName');
					$assistantPassNo = $this->input->post('assistantPassNo');
					$assistantName = $this->input->post('assistantName');
					$truckId = $this->input->post('truckNo');
					$frmSlot = $this->input->post('truckSlot');	

					$driverNoQuery = "SELECT id,card_number,mobile_number FROM vcms_vehicle_agent WHERE card_number = '$driverPassNo' AND agent_type='Driver' ORDER BY last_update DESC";
					$driverNoRslt = $this->bm->dataSelectDB1($driverNoQuery);

					$DriverNo = "";
					for($i=0;$i<count($driverNoRslt);$i++){
						$DriverNo = $driverNoRslt[$i]['mobile_number'];
					}

					if($DriverNo != $phoneNo){
						echo $driverNoUpdtQuery = "UPDATE vcms_vehicle_agent set mobile_number='$phoneNo' WHERE card_number = '$driverPassNo' AND agent_type='Driver'";
						$driverNoRslt = $this->bm->dataSelectDB1($driverNoUpdtQuery);
					}

					//Driver Mobile No. Update -- end

					// Add Truck - starts		

					$totTruck = $truck_qty;

					$strUpdateSlot = "UPDATE ctmsmis.tmp_oracle_assignment SET assignment_slot='$frmSlot' WHERE cont_no='$contNo' AND rot_no='$rotNo'";
					// $this->bm->dataUpdate($strUpdateSlot);
					$this->bm->dataUpdatedb2($strUpdateSlot);
											
					$sql_timeSlot = "SELECT assignment_slot,assignmentDate,DATE_ADD(assignmentDate, INTERVAL 1 DAY) AS nxtDt
					FROM ctmsmis.tmp_oracle_assignment
					WHERE rot_no='$rotNo' AND cont_no='$contNo' AND assignmentDate>=DATE(NOW())";
					// $rslt_timeSlot = $this->bm->dataSelect($sql_timeSlot);
					$rslt_timeSlot = $this->bm->dataSelectDb2($sql_timeSlot);

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

					$sql_chkTruck = "SELECT COUNT(*) AS rtnValue
					FROM do_truck_details_entry 
					WHERE truck_id='$truckId' AND visit_time_slot_start='$sSlot' AND visit_time_slot_end='$eSlot'"; //sSlot and $eSlot not found without slot
					
					// $sql_chkTruck = "SELECT COUNT(*) AS rtnValue
					// FROM do_truck_details_entry
					// WHERE truck_id='$truckId' AND date(last_update) = date(NOW())";

					$rslt_chkTruck = $this->bm->dataSelectDB1($sql_chkTruck);
					$chkTruck = "";
					for($i=0;count($rslt_chkTruck)>$i;$i++){
						$chkTruck = $rslt_chkTruck[$i]['rtnValue'];
					}
					
					if($chkTruck==0)
					{
						$strInsertEq = "INSERT INTO do_truck_details_entry(verify_info_fcl_id,import_rotation,cont_no,truck_id,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,update_by,ip_addr,last_update,visit_time_slot_start,visit_time_slot_end,emrgncy_flag,emrgncy_approve_stat,drv_login_id,entry_from)
						VALUES('$vrfyInfoFclId','$rotNo','$contNo','$truckId','$driverName','$driverPassNo','$assistantName','$assistantPassNo','$login_id','$ipaddr',NOW(),'$sSlot','$eSlot','$emrgncy_flag','$emrgncy_approve_stat','$driver_login_id','driv')";
						$stat = $this->bm->dataInsertDB1($strInsertEq);
						$stat = 1;
						if($stat == 1)
							$msg = "<font color='green'><b>Truck added successfully</b></font>";
						
					}
					else
					{
						$msg = "<font size='4' color='red'><b>This truck was assigned for this time slot previously</b></font>";
						$data['title']= "Driver Dashboard";
						$data['msg'] = $msg;
						$this->load->view('cssAssetsList');
						$this->load->view('headerTop');
						$this->load->view('sidebar');
						$this->load->view('driverDashboard',$data);
						$this->load->view('jsAssetsList');
					}

					

					// Online Pay Starts Here 

					$visitIdQuery = "SELECT id FROM do_truck_details_entry WHERE cont_no = '$contNo' AND import_rotation = '$rotNo' AND truck_id = '$truckId' AND DATE(last_update) = DATE(NOW())";
					
					$visitRslt = $this->bm->dataSelectDB1($visitIdQuery);
					$visitId = "";

					for($x=0;$x<count($visitRslt);$x++){
						$visitId = $visitRslt[$x]['id'];
					}
					
					$contact = $smsNo;

					if($method=='sonalypay')
					{		
						
						$flag='0';
						
						$find_visitStr = "SELECT count(*) as rtnValue FROM vcms_online_pay where visit_id='$visitId'";
						$checkVisit= $this->bm->dataReturnDB1($find_visitStr);
						
						if($checkVisit>0)
						{
							$sql_Requ = "SELECT MAX(vcms_online_ReqID.max_reqID) AS rtnValue FROM vcms_online_ReqID";
							$requst_id = $this->bm->dataReturnDB1($sql_Requ);
							
							$ref=$requst_id."_".$flag;
							
							$query_update = "UPDATE vcms_online_pay SET RefTranNo='$ref', cnf_login_id='$login_id', requ_id='$requst_id', payer_st = 1 WHERE visit_id = '$visitId'";
							$up_st = $this->bm->dataUpdateDB1($query_update); 
							if($up_st>0)
							{
								$newReq_id=$requst_id+1;
								$newReq_id="0".$newReq_id;		// added now
								$query_update = "UPDATE vcms_online_ReqID SET max_reqID='$newReq_id'";
								$update_st = $this->bm->dataUpdateDB1($query_update); 
							}
						}
						else
						{
							/* $sql_maxRequ = "SELECT MAX(vcms_online_pay.requ_id)+1 AS rtnValue FROM vcms_online_pay";
							$requst_id = $this->bm->dataReturnDB1($sql_maxRequ); */
							$sql_Requ = "SELECT MAX(vcms_online_ReqID.max_reqID) AS rtnValue FROM vcms_online_ReqID";
							$requst_id = $this->bm->dataReturnDB1($sql_Requ);
							$ref=$requst_id."_".$flag;
							$query_txEntry = "INSERT INTO vcms_online_pay (visit_id, RefTranNo, requ_id, tr_amt, challan_amt, assign_type, cnf_login_id, allPay_st, chk_st, gateway, payer_st) VALUES ('$visitId', '$ref', '$requst_id', '50', '7.5', '$assignmentType', '$login_id', 0 , 1, 'sonali',1)";
							$st=$this->bm->dataInsertDB1($query_txEntry);
							if($st>0)
							{
								$newReq_id=$requst_id+1;
								$newReq_id="0".$newReq_id;		// added now
								$query_update = "UPDATE vcms_online_ReqID SET max_reqID='$newReq_id'";
								$update_st = $this->bm->dataUpdateDB1($query_update); 
							}
						}
						
						//return;
						$data['requst_id'] = $requst_id;
						$data['ref'] = $ref;
						$data['login_id'] = $login_id;
						$data['contact'] = $contact;
						$data['trucVisitId'] = $visitId;
						$data['flag'] = $flag;  //Single Pay
						$data['name'] = $this->session->userdata('User_Name');
						$cus_name= $this->session->userdata('User_Name');
						$data['payAmt'] = $payAmt;
						//$payAmt = 10;
						$this->onlinePay($contNo, $rotNo, $login_id, $contact, $ref, $requst_id, $payAmt, $cus_name, $assignmentType, $cont_status);
					
					}
					else
					{
						// echo "Ekpay";
						// return;
						$flag='0';			
						$find_visitStr = "SELECT count(*) as rtnValue FROM vcms_online_pay where visit_id='$visitId'";
						$checkVisit= $this->bm->dataReturnDB1($find_visitStr);
						//echo $checkVisit;
						if($checkVisit>0)
						{
							$sql_Requ = "SELECT MAX(vcms_online_ekpay_transID.max_transId) AS rtnValue FROM vcms_online_ekpay_transID";
							$requst_id = $this->bm->dataReturnDB1($sql_Requ);
							
							$ref=$requst_id."_".$flag;
							
							$query_update = "UPDATE vcms_online_pay SET RefTranNo='$ref', cnf_login_id='$login_id', requ_id='$requst_id', payer_st = 1 WHERE visit_id = '$visitId'";
							$up_st = $this->bm->dataUpdateDB1($query_update); 
							if($up_st>0)
							{
								$newReq_id=$requst_id+1;
								//$newReq_id="0".$newReq_id;		// added now
								$query_update = "UPDATE vcms_online_ekpay_transID SET max_transId='$newReq_id'";
								$update_st = $this->bm->dataUpdateDB1($query_update); 
							}
						}
						else
						{
							/* $sql_maxRequ = "SELECT MAX(vcms_online_pay.requ_id)+1 AS rtnValue FROM vcms_online_pay";
							$requst_id = $this->bm->dataReturnDB1($sql_maxRequ); */
							$sql_Requ = "SELECT MAX(vcms_online_ekpay_transID.max_transId) AS rtnValue FROM vcms_online_ekpay_transID";
							$requst_id = $this->bm->dataReturnDB1($sql_Requ);
							$ref=$requst_id."_".$flag;
							$query_txEntry = "INSERT INTO vcms_online_pay (visit_id, RefTranNo, rotation, container, requ_id, tr_amt, challan_amt, assign_type, cnf_login_id, allPay_st, chk_st, gateway, payer_st) VALUES ('$visitId', '$ref', '$rotNo', '$contNo', '$requst_id', '50', '7.5', '$assignmentType', '$login_id', 0 , 1, 'ekpay',1)";
							//return;
							$st=$this->bm->dataInsertDB1($query_txEntry);
							if($st>0)
							{
								$newReq_id=$requst_id+1;
								//$newReq_id="0".$newReq_id;		// added now
								$query_update = "UPDATE vcms_online_ekpay_transID SET max_transId='$newReq_id'";
								$update_st = $this->bm->dataUpdateDB1($query_update); 
							}
						}
						
						//return;
						$data['requst_id'] = $requst_id;
						$data['ref'] = $ref;
						$data['login_id'] = $login_id;
						$data['contact'] = $contact;
						$data['trucVisitId'] = $visitId;
						$data['flag'] = $flag;  //Single Pay
						$data['name'] = $this->session->userdata('User_Name');
						$cus_name= $this->session->userdata('User_Name');
						$data['payAmt'] = $payAmt;
						$this->onlineEkPay($contNo, $rotNo, $login_id, $contact, $ref, $requst_id, $payAmt, $cus_name, $assignmentType, $cont_status);

					}
				}
				else
				{
					$msg = "<font color='red'>You can't add emergency truck without port security approvement!</font>";
					$data['msg'] = $msg;
					$data['title']="Yard Delivery";

					$this->load->view('cssAssetsList');
					$this->load->view('headerTop');
					$this->load->view('sidebar');
					$this->load->view('truckEntryByDriver_Ocd',$data);
					$this->load->view('jsAssetsList');
				}
			}
			else
			{
				$msg = "<font color='red'>No Assignment found for this Container Today!</font>";
				$data['msg'] = $msg;
				$data['title']="Yard Delivery";

				$this->load->view('cssAssetsList');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('truckEntryByDriver_Ocd',$data);
				$this->load->view('jsAssetsList');
			}
		}
	}


	// Driver OCD Delivery Ends Here

	// Driver Dashboard  - End

	// Gate Pass for Driver  -- start

	function gatePassForDriverForm()
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
			$data['title']="Print Gate Pass Form";

			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('gatePassForDriverForm',$data);
			$this->load->view('jsAssets');
		}
	}

	function gatePassforDriver()
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
			$trucVisitId = "";

			if($this->input->post("trucVisitId"))
			{			
				$trucVisitId = trim($this->input->post("trucVisitId"));
			}
			else
			{
				$trucVisitId = trim($_GET["visitId"]);
			}

			$msg = "";

			$checkvisitIdQuery = "SELECT COUNT(*) as rtnValue FROM do_truck_details_entry WHERE id = '$trucVisitId'";
			$chkSts = $this->bm->dataReturnDB1($checkvisitIdQuery);

			if($chkSts > 0)
			{
				$dataQuery = "SELECT import_rotation,cont_no FROM do_truck_details_entry WHERE id = '$trucVisitId'";
				$dataRslt = $this->bm->dataSelectDB1($dataQuery);

				$rot_no = "";
				$cont_no = "";
				for($i = 0; $i<count($dataRslt);$i++){
					$rot_no = $dataRslt[$i]['import_rotation'];
					$cont_no = $dataRslt[$i]['cont_no'];
				}

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
			else
			{
				$msg = "<font color='red' size='4'>No Data found for visit id: {$trucVisitId}</font>";
				$data['msg'] = $msg;
				$data['title']="Print Gate Pass Form";

				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('gatePassForDriverForm',$data);
				$this->load->view('jsAssets');
			}

			return;
		}
	}

	// Gate Pass for Driver  -- end

	// Truck Wise gate pass print form - start

	function contWiseGatePassPrintForm()
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
			$data['title'] = "Container Wise Gate Pass Print Form";
			$data['flag'] = 0;

			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('contWiseGatePassPrintForm',$data);
			$this->load->view('jsAssets');
		}
	}

	function contWiseGatePassPrint()
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
			$contNo = trim($this->input->post("cont"));
			$msg = "";

			$truckQuery = "SELECT * FROM do_truck_details_entry WHERE cont_no = '$contNo'";
			$truckResult = $this->bm->dataSelectDB1($truckQuery);

			// assignment Type for payment
			$assignmentTypeQuery = "SELECT mfdch_value FROM ctmsmis.tmp_oracle_assignment WHERE cont_no = '$contNo'";
			$assignmentTypeRslt = $this->bm->dataSelect($assignmentTypeQuery);

			$assignmentType = "";

			for($i=0;$i<count($assignmentTypeRslt);$i++){
				$assignmentType = $assignmentTypeRslt[$i]['mfdch_value'];
			}

			$data['msg'] = $msg;
			$data['title'] = "Container Wise Gate Pass Print";
			$data['truckResult'] = $truckResult;
			$data['assignmentType'] = $assignmentType;
			$data['flag'] = 1;

			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('contWiseGatePassPrintForm',$data);
			$this->load->view('jsAssets');
		}
	}

	// Truck Wise gate pass print form - end

	// Container Wise Unpaid Truck Form  -- start

	function contWiseUnpaidTruckForm()
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
			$data['title'] = "Container Wise Unpaid Truck Form";
			$data['flag'] = 0;

			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('contWiseUnpaidTruckForm',$data);
			$this->load->view('jsAssets');
		}
	}

	function contWiseUnpaidTruck()
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
			$contNo = trim($this->input->post("cont"));
			$msg = "";

			$truckQuery = "SELECT * FROM do_truck_details_entry WHERE cont_no = '$contNo' AND paid_status = 0 AND drv_login_id = '$user'";
			$truckResult = $this->bm->dataSelectDB1($truckQuery);

			// assignment Type for payment
			$assignmentTypeQuery = "SELECT mfdch_value FROM ctmsmis.tmp_oracle_assignment WHERE cont_no = '$contNo'";
			$assignmentTypeRslt = $this->bm->dataSelect($assignmentTypeQuery);

			$assignmentType = "";

			for($i=0;$i<count($assignmentTypeRslt);$i++){
				$assignmentType = $assignmentTypeRslt[$i]['mfdch_value'];
			}

			$data['msg'] = $msg;
			$data['title'] = "Container Wise Unpaid Truck";
			$data['truckResult'] = $truckResult;
			$data['assignmentType'] = $assignmentType;
			$data['flag'] = 1;

			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('contWiseUnpaidTruckForm',$data);
			$this->load->view('jsAssets');
		}
	}

	// Container Wise Unpaid Truck Form  - end

	// Additional Truck Permission -- start

	function additionalTruckPermissionForm()
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
			$data['title'] = "Additional Truck Permission Form";

			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('additionalTruckPermissionForm',$data);
			$this->load->view('jsAssets');
		}
	}

	function additionalTruckPermission()
	{
		$user = $this->session->userdata('login_id');
		$user=str_replace(" ","",$user);
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$login_id = $this->session->userdata('login_id');
		$ip_addr = $_SERVER['REMOTE_ADDR'];
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$msg = "";

			$cont = $this->input->post('cont');
			$assignDt = $this->input->post('assignDt');
			$truckQty = $this->input->post('truck_qty');

			$checkAssignmentQuery = "SELECT count(*) as rtnValue FROM ctmsmis.tmp_oracle_assignment WHERE assignmentDate = '$assignDt' AND cont_no = '$cont'";
			$checkAssignmentRslt = $this->bm->dataSelect($checkAssignmentQuery);

			$hasAssignment = 0;

			for($i=0;$i<count($checkAssignmentRslt);$i++){
				$hasAssignment = $checkAssignmentRslt[$i]['rtnValue'];
			}

			if($hasAssignment>0)
			{
				$unit_gkeyQuery = "SELECT unit_gkey,rot_no FROM ctmsmis.tmp_oracle_assignment WHERE assignmentDate = '$assignDt' AND cont_no = '$cont'";
				$unit_gkeyRslt = $this->bm->dataSelect($unit_gkeyQuery);

				$unit_gkey = "";
				$rot_no = "";

				for($i=0;$i<count($unit_gkeyRslt);$i++)
				{
					$unit_gkey = $unit_gkeyRslt[$i]['unit_gkey'];
					$rot_no = $unit_gkeyRslt[$i]['rot_no'];
				}

				$chkCountQuery = "SELECT COUNT(*) AS rtnValue FROM addional_Truck_Permission WHERE cont_no = '$cont' AND assignDt = '$assignDt'";
				$chkCount = $this->bm->dataReturnDB1($chkCountQuery);

				if($chkCount == 0){
					$insertQuery = "INSERT INTO addional_Truck_Permission(unit_gkey,cont_no,rot_no,assignDt,truckQty,created_at,created_by,ip_addr) VALUES('$unit_gkey','$cont','$rot_no','$assignDt','$truckQty',NOW(),'$login_id','$ip_addr')";
				
					if($this->bm->dataInsertDB1($insertQuery))
					{
						$msg = "<font color='green' size='4'>Data successfully inserted!</font>";
					}
					else
					{
						$msg = "<font color='red' size='4'>Data can not be inserted! Please try again.</font>";
					}
				}
				else
				{
					$getIdQuery = "SELECT id AS rtnValue FROM addional_Truck_Permission WHERE cont_no = '$cont' AND assignDt = '$assignDt'";
					$id = $this->bm->dataReturnDB1($getIdQuery);

					$updateQuery = "UPDATE addional_Truck_Permission SET truckQty = '$truckQty', updated_at = NOW(), updated_by = '$login_id', updated_ip = '$ip_addr' WHERE id = '$id'";
				
					if($this->bm->dataUpdateDB1($updateQuery))
					{
						$msg = "<font color='green' size='4'>Data successfully updated!</font>";
					}
					else
					{
						$msg = "<font color='red' size='4'>Data can not be updated! Please try again.</font>";
					}
				}

			}
			else
			{
				$msg = "<font color='red' size='4'>No assignment found for container <b>".$cont."</b> at <b>".$assignDt."</b></font>";
			}


			$data['msg'] = $msg;
			$data['title'] = "Additional Truck Permission Form";

			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('additionalTruckPermissionForm',$data);
			$this->load->view('jsAssets');
		}
	}

	// Additional Truck Permission -- end

	// View Notification Start

	function viewNotification()
	{
		$notification_id=$_GET["flag"];
		//$flag=$_GET["flag1"];
		$login_id = $this->session->userdata('login_id');
		$org_Type_id = $this->session->userdata('org_Type_id');
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		 $org_id = $this->session->userdata("org_id");
		
		$fla="";
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$Submitee_Org_Id = "";
			$query = "SELECT users.org_id  FROM users WHERE users.login_id='$login_id'";
			$resultQuery =$this->bm->dataSelectDb1($query);
			
					$Submitee_Org_Id = $resultQuery[0]["org_id"];
				
			
			
			if($org_Type_id==73) //freight forwarder association
			{
				$edoQuery = "SELECT * FROM edo_notification
				INNER JOIN edo_application_by_cf
				ON edo_notification.application_id =edo_application_by_cf.id
				WHERE bl_type='HB' AND igm_type='GM' AND ff_stat='1' AND edo_notification.id='$notification_id'
				ORDER BY edo_application_by_cf.id DESC";
	
							
						
			}						
			else if($org_Type_id==57 or $org_Type_id==10) //shipping_agent
			{
				$edoQuery = "SELECT * FROM edo_notification
				INNER JOIN edo_application_by_cf
				ON edo_notification.application_id =edo_application_by_cf.id 
				where igm_type='BB' and sh_agent_org_id='$Submitee_Org_Id' AND edo_notification.id='$notification_id'
				ORDER BY edo_application_by_cf.id DESC";
					
							
			}
			else if($org_Type_id==4) //freight forwarder
			{
				if($fla=="pending")
				{
					$edoQuery = "SELECT * FROM edo_notification
					INNER JOIN edo_application_by_cf
					ON edo_notification.application_id =edo_application_by_cf.id  
					WHERE igm_type='GM' AND bl_type='HB' AND ff_org_id='$org_id' AND edo_notification.id='$notification_id'
					AND do_upload_st='0' AND rejection_st='0'
					ORDER BY  edo_application_by_cf.id DESC";
						
						
				}
				else
				{
					$edoQuery = "SELECT * FROM edo_notification
					INNER JOIN edo_application_by_cf
					ON edo_notification.application_id =edo_application_by_cf.id  
					where igm_type='GM' AND ff_org_id='$org_id' AND edo_notification.id='$notification_id'
					ORDER BY edo_application_by_cf.id DESC";	
				}
			
			}
			else if($org_Type_id==1) //MLO
			{
				$edoQuery = "SELECT * FROM edo_notification
				INNER JOIN edo_application_by_cf
				ON edo_notification.application_id =edo_application_by_cf.id 
				WHERE igm_type='GM' AND mlo='$Submitee_Org_Id' AND edo_notification.id='$notification_id'
				AND (cont_status = 'FCL' OR bl_type = 'MB')
				ORDER BY edo_application_by_cf.id DESC";
									
			}
			else if($org_Type_id==5 or $org_Type_id==62) //CPA or ONESTOP
			{
				$edoQuery = "SELECT * FROM edo_notification
				INNER JOIN edo_application_by_cf
				ON edo_notification.application_id =edo_application_by_cf.id
				WHERE IF(bl_type='HB' AND igm_type='GM', ff_assoc_st, do_upload_st) ='1' AND edo_notification.id='$notification_id' 
				ORDER BY edo_application_by_cf.id  DESC";
							
			}
			else  
			{
					
				$edoQuery = "SELECT * FROM edo_notification 
				INNER JOIN edo_application_by_cf
				ON edo_notification.application_id =edo_application_by_cf.id 
				WHERE edo_application_by_cf.sumitted_by='$login_id' And edo_notification.id='$notification_id'  
				ORDER BY  edo_application_by_cf.id DESC";
					
			}
	
			$resultNotification =$this->bm->dataSelectDb1($edoQuery);
			$data['org_id'] =$this->session->userdata('org_Type_id');
			$data['title']="EDO Application List";
			$data['msg'] = "";
			$data['flag'] = "all"; //To show all do list
			$data['resultNotification'] =$resultNotification;
            $this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('viewNotification',$data);
			$this->load->view('jsAssetsList');
			$updateQuery="UPDATE edo_notification SET seen_st='1' WHERE id='$notification_id'";
			$this->bm->dataUpdateDB1($updateQuery);

		}
		
	}
	
	// View Notification End
	
	//View All The Notification Start
	
	function  ViewAllNotification()
	{

		$login_id = $this->session->userdata('login_id');
		$org_Type_id = $this->session->userdata('org_Type_id');
		$session_id = $this->session->userdata('value');
        $LoginStat = $this->session->userdata('LoginStat');
		$org_id = $this->session->userdata("org_id");
		$fla="";
		
		if($LoginStat!="yes")
        {
            $this->logout();
        }
        else
        {
			$Submitee_Org_Id = "";
			$query = "SELECT users.org_id  FROM users WHERE users.login_id='$login_id'";
			$resultQuery =$this->bm->dataSelectDb1($query);
			
			$Submitee_Org_Id = $resultQuery[0]["org_id"];

			if($org_Type_id==73) //freight forwarder association
			{
				$edoQuery = "SELECT  DISTINCT application_id,edo_application_by_cf.* FROM ( 
				SELECT * FROM edo_notification WHERE org_notified='$org_id' AND seen_st='0'
				UNION
				SELECT * FROM edo_notification 
														
				WHERE org_notified='$org_id' AND life_st='0')AS edo_notify 
				INNER JOIN edo_application_by_cf
				ON edo_notify.application_id =edo_application_by_cf.id
				WHERE bl_type='HB' AND igm_type='GM' AND ff_stat='1'
				ORDER BY edo_application_by_cf.id DESC";
				
							
						
			}						
			else if($org_Type_id==57 or $org_Type_id==10) //shipping_agent
			{
				$edoQuery = "SELECT  DISTINCT application_id,edo_application_by_cf.* FROM ( 
				SELECT * FROM edo_notification WHERE org_notified='$org_id' AND seen_st='0'
				UNION
				SELECT * FROM edo_notification 
				WHERE org_notified='$org_id' AND life_st='0')AS edo_notify 
				INNER JOIN edo_application_by_cf
				ON edo_notify.application_id =edo_application_by_cf.id 
				where igm_type='BB' and sh_agent_org_id='$Submitee_Org_Id'
				ORDER BY edo_application_by_cf.id DESC";
					
							
			}
			else if($org_Type_id==4) //freight forwarder
			{
				if($fla=="pending")
				{
					$edoQuery = "SELECT  DISTINCT application_id,edo_application_by_cf.* FROM ( 
					SELECT * FROM edo_notification WHERE org_notified='$org_id' AND seen_st='0' 
					UNION
					SELECT * FROM edo_notification 
					WHERE org_notified='$org_id' AND life_st='0')AS edo_notify 
					INNER JOIN edo_application_by_cf
					ON edo_notify.application_id =edo_application_by_cf.id 
					WHERE igm_type='GM' AND bl_type='HB' AND ff_org_id='$org_id'
					AND do_upload_st='0' AND rejection_st='0'
					ORDER BY  edo_application_by_cf.id DESC";
						
						
				}
				else
				{
					$edoQuery = "SELECT  DISTINCT application_id,edo_application_by_cf.* FROM ( 
					SELECT * FROM edo_notification WHERE org_notified='$org_id' AND seen_st='0'
					UNION
					SELECT * FROM edo_notification 
					WHERE org_notified='$org_id' AND life_st='0')AS edo_notify 
					INNER JOIN edo_application_by_cf
					ON edo_notify.application_id =edo_application_by_cf.id 
					where igm_type='GM' AND ff_org_id='$org_id'
					ORDER BY edo_application_by_cf.id DESC";
						

						
				}
			
			}
			else if($org_Type_id==1) //MLO
			{
				$edoQuery = "SELECT DISTINCT application_id,edo_application_by_cf.* FROM ( 
				SELECT * FROM edo_notification WHERE org_notified='$org_id' AND seen_st='0'
				UNION
				SELECT * FROM edo_notification 
				WHERE org_notified='$org_id' AND life_st='0')AS edo_notify 
				INNER JOIN edo_application_by_cf
				ON edo_notify.application_id =edo_application_by_cf.id
				WHERE igm_type='GM' AND mlo='$Submitee_Org_Id' 
				AND (cont_status = 'FCL' OR bl_type = 'MB')
				ORDER BY edo_application_by_cf.id DESC";
				$resultNotification =$this->bm->dataSelectDb1($edoQuery);
				echo $a=count( $resultNotification);
							
			}
			else if($org_Type_id==5 or $org_Type_id==62) //CPA or ONESTOP
			{
				$edoQuery = "SELECT DISTINCT application_id,edo_application_by_cf.* FROM ( 
				SELECT * FROM edo_notification WHERE org_notified='$org_id' AND seen_st='0'
				UNION
				SELECT * FROM edo_notification 
				WHERE org_notified='$org_id' AND life_st='0')AS edo_notify 
				INNER JOIN edo_application_by_cf
				ON edo_notify.application_id =edo_application_by_cf.id
				WHERE IF(bl_type='HB' AND igm_type='GM', ff_assoc_st, do_upload_st) ='1'
				ORDER BY edo_application_by_cf.id  DESC";
							
			}
			else  
			{
				$edoQuery = "SELECT  DISTINCT application_id,edo_application_by_cf.* FROM ( 
				SELECT * FROM edo_notification WHERE org_notified='$org_id' AND seen_st='0' 
				UNION
				SELECT * FROM edo_notification 
														
				WHERE org_notified='$org_id' AND life_st='0')AS edo_notify 
				INNER JOIN edo_application_by_cf
				ON edo_notify.application_id =edo_application_by_cf.id 
				WHERE sumitted_by='$login_id' 
				ORDER BY  edo_application_by_cf.id DESC";

			}
								
			$resultNotification =$this->bm->dataSelectDb1($edoQuery);
			$data['org_id'] =$this->session->userdata('org_Type_id');
			$data['title']="EDO Application List";
			$data['msg'] = "";
			$data['flag'] = "all"; //To show all do list
			$data['resultNotification'] =$resultNotification;
            $this->load->view('cssAssetsList');
            $this->load->view('headerTop');
            $this->load->view('sidebar');
            $this->load->view('viewNotification',$data);
			$this->load->view('jsAssetsList');

			for($notification=0;$notification<count($resultNotification);$notification++)
			{
			    $application_id=$resultNotification[$notification]['application_id'];

				$updateQuery="UPDATE edo_notification SET seen_st='1' WHERE application_id='$application_id' AND org_notified='$org_id' AND seen_st='0'";
			
			    $this->bm->dataUpdateDB1($updateQuery);

			}
			
		}
		
	}
	
	// View All The Notification End 
	
	function dateWiseEDOReportBackupPreviousWay()
	{	
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{						
			$this->load->library('m_pdf');

			$do_date=$this->input->post('do_date');			
			$str="SELECT do_date,org_name,SUM(tot) AS tot,SUM(approve) AS approve,SUM(notApprove) AS notApprove
			FROM (
			SELECT shed_mlo_do_info.do_date,IFNULL(ff_org_id,mlo) AS org, 
			(SELECT Organization_Name FROM organization_profiles WHERE id=org) AS org_name,
			1 AS tot,IF(check_st=1,1,0) AS approve,IF(check_st=0,1,0) AS notApprove
			FROM shed_mlo_do_info
			INNER JOIN edo_application_by_cf ON edo_application_by_cf.id=shed_mlo_do_info.edo_id
			WHERE shed_mlo_do_info.do_date='$do_date'
			) AS tbl GROUP BY do_date,org_name ORDER BY do_date,org_name";
			$edoReport = $this->bm->dataSelectDb1($str);
			$this->data['edoReport']=$edoReport;			
			$this->data['do_date']=$do_date;
			$this->data['title']="Date Wise EDO Report";
			
			$html=$this->load->view('dateWiseEDOReport',$this->data, true); //load the pdf_output.php by passing our data and get all data in $html varriable.
				
			$pdfFilePath ="dateWiseEDOReport-".time()."-download.pdf";

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
	}
	
	// Bill of Entry Upload Status Report -- start

	function beUploadStsReportForm()
	{
		$user = $this->session->userdata('login_id');
		$user=str_replace(" ","",$user);
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{

			$data['title']="Bill of Entry Upload Status Report Form";
			$data['msg'] = "";

			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('beUploadStsReportForm',$data);
			$this->load->view('jsAssetsList');
		}
	}

	function beUploadStsReport()
	{
		$user = $this->session->userdata('login_id');
		$user=str_replace(" ","",$user);
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$date = $this->input->post('date');
			$dateData = strtoupper(date("d-M-y",strtotime($date)));
			
			//return;

			$url = "http://192.168.16.243:8082/edoInfo/list/".$dateData;
			$json = file_get_contents($url);

			if($json === FALSE)
			{
				$obj = "";
			}
			else
			{
				$obj = json_decode($json);
			}
			
			$data['title']="Bill of Entry Upload Status Report";
			$data['data'] = $obj;

			$this->load->view('beUploadStsReport',$data);
		}
	}

	// Bill of Entry Upload Status Report -- end
	
	

	// Laden Export Container -- starts

	function ladenExportContainerForm()
	{
		$user = $this->session->userdata('login_id');
		$user=str_replace(" ","",$user);
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$query = "SELECT DISTINCT gkey, id FROM road_gates WHERE life_cycle_state='ACT'";
			$gateList = $this->bm->dataSelect($query);
				
			$data['gateList']=$gateList;
			$data['title']="Laden Export Container Form";
			$data['msg'] = "";

			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('ladenExportContainerForm',$data);
			$this->load->view('jsAssetsList');
		}
	}

    function ladenExportContainer()
	{
		$user = $this->session->userdata('login_id');
		$user=str_replace(" ","",$user);
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$searchBy = $this->input->post('searchBy');
			$st = "";

			if($searchBy == 'rotation')
			{
				$rotation = $this->input->post('rotation');
				$data['rotation'] = $rotation;

				$this->data['rotation'] = $rotation;

				$st = "WHERE vsl_vessel_visit_details.ib_vyg='$rotation'";
			}
			else if($searchBy == 'date')
			{
				$fromDate = $this->input->post('fromDate');
				$toDate = $this->input->post('toDate');
				$data['fromDate'] = $fromDate;
				$data['toDate'] = $toDate;

				$this->data['fromDate'] = $fromDate;
				$this->data['toDate'] = $toDate;

				//$st = "WHERE DATE(sparcsn4.road_truck_visit_details.created) BETWEEN '$fromDate' AND '$toDate'";
				$st = " WHERE cast(road_truck_visit_details.created as date) BETWEEN to_date('$fromDate','yyyy-mm-dd') AND to_date('$toDate','yyyy-mm-dd')";
			}
			else if($searchBy == 'gate')
			{
				$fromDate = $this->input->post('fromDate');
				$toDate = $this->input->post('toDate');
				$gate = $this->input->post('gate');
				$data['fromDate'] = $fromDate;
				$data['toDate'] = $toDate;
				

				$query = "SELECT id as rtnValue FROM road_gates WHERE life_cycle_state='ACT' AND gkey='$gate'";
				$gateId = $this->bm->dataReturn($query);

				$data['gate'] = $gateId;

				$this->data['fromDate'] = $fromDate;
				$this->data['toDate'] = $toDate;
				$this->data['gate'] = $gateId;

				//$st = "WHERE DATE(sparcsn4.road_truck_visit_details.created) BETWEEN '$fromDate' AND '$toDate' AND sparcsn4.road_gates.gkey=$gate";
				$st = "WHERE  cast(road_truck_visit_details.created as date) BETWEEN to_date('$fromDate','yyyy-mm-dd') AND to_date('$toDate','yyyy-mm-dd')  AND road_gates.gkey=$gate";
			}
			
			$options = $this->input->post('fileOptions');

			$query = "SELECT inv_unit.line_op, road_truck_visit_details.bat_nbr,
			inv_unit.category, road_truck_transactions.ctr_id,road_truck_transactions.stage_id,
			road_truck_transactions.ctr_freight_kind, SUBSTR(road_truck_transactions.eqo_eq_length,-2) AS siz,
			road_truck_visit_details.created AS gate_in,
			inv_unit_fcy_visit.time_load,road_truck_visit_details.created,
			concat(concat(concat(EXTRACT(DAY FROM inv_unit_fcy_visit.time_load - road_truck_visit_details.created),' days '),
			concat(EXTRACT(HOUR FROM inv_unit_fcy_visit.time_load - road_truck_visit_details.created),' hours ')), 
			concat(EXTRACT(MINUTE FROM inv_unit_fcy_visit.time_load - road_truck_visit_details.created),' minutes')) as duration,
			
			inv_unit.id,  ib_vyg, inv_unit.category,vsl_vessels.name AS vsl_name,
			(SELECT ref_bizunit_scoped.id  FROM ref_bizunit_scoped
			WHERE ref_bizunit_scoped.gkey=inv_unit.line_op
			) AS MLO,road_gates.id AS gate
			FROM inv_unit 
			INNER JOIN inv_unit_fcy_visit  ON inv_unit_fcy_visit.unit_gkey=inv_unit.gkey
			INNER JOIN argo_carrier_visit ON  inv_unit_fcy_visit.actual_ob_cv=argo_carrier_visit.gkey
			
			INNER JOIN vsl_vessel_visit_details ON argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
			INNER JOIN vsl_vessel_berthings ON vsl_vessel_berthings.vvd_gkey=vsl_vessel_visit_details.vvd_gkey
			INNER JOIN vsl_vessels ON vsl_vessels.gkey=vsl_vessel_visit_details.vessel_gkey
			INNER JOIN road_truck_transactions ON road_truck_transactions.unit_gkey=inv_unit.gkey
			INNER JOIN road_truck_visit_details ON road_truck_transactions.truck_visit_gkey=road_truck_visit_details.tvdtls_gkey
			INNER JOIN road_gates ON road_gates.gkey=road_truck_visit_details.gate_gkey $st";

			$rslt = $this->bm->dataSelect($query);

			$data['title']="Laden Export Container";
			$data['msg'] = "";

			if($options == 'html' || $options == 'xl')
			{
				$data['searchBy'] = $searchBy;
				$data['options'] = $options;
				$data['rslt'] = $rslt;
				$this->load->view('ladenExportContainerReport',$data);
			}
			else if($options == 'pdf')
			{
				$this->data['searchBy'] = $searchBy;
				$this->data['rslt'] = $rslt;
				$this->load->library('m_pdf');
				$html=$this->load->view('ladenExportContainerpdf',$this->data, true); 
				$pdfFilePath ="Laden Export Container-".time()."-download.pdf";
				$pdf = $this->m_pdf->load();
				$pdf->useSubstitutions = true; 
				$html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
				$stylesheet = file_get_contents('assets/stylesheets/test.css');
				$pdf->WriteHTML($stylesheet,1);
				$pdf->WriteHTML($html,2);
				$pdf->Output($pdfFilePath, "I");
			}
		}
	}
	// Laden Export Container -- ends
	
	// called from Pcs 192.168.16.42 -- start

	function pullfile()
	{
		$cardNum = $_GET['cardNum'];
		$image = $cardNum.'.png';
		$path = $_SERVER['DOCUMENT_ROOT'].'/biometricPhoto/'.$cardNum;
				
		if($cardNum!="")
		{
			if(!file_exists($path)){
				mkdir($path, 0777, true);
				chmod($path, 0777);
				
				$pulledFile = file_get_contents('http://192.168.16.42/biometricPhoto/'.$cardNum.'/'.$image);
				$result = file_put_contents($_SERVER['DOCUMENT_ROOT'].'/biometricPhoto/'.$cardNum.'/'.$image,$pulledFile);
			}
		}
	}

	// called from Pcs 192.168.16.42 -- end

	// FCL Truck Entry for Security Panel - start

	function fclTruckEntryformforSecurity()
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
			$data['title']="Truck Entry for FCL";
			$data['flag'] = 0;
			$data['msg'] = "";

			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('fclTruckEntryforSecurity',$data);
			$this->load->view('jsAssets');
		}
	}

	function fclTruckSearchforSecurity($cnfAinNo=null)
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
			$ipaddr = $_SERVER['REMOTE_ADDR'];
			$msg = "";

			if(is_null($cnfAinNo))
			{
				$cnfAinNo = trim($this->input->post('cnfAin'));
			}
			else
			{
				$msg = "<font color='red' size='4'><b>This container is blocked by custom.</b></font>";
			}
			
			$cnfAinNorslt = explode(" - ",$cnfAinNo);
			$ain = trim($cnfAinNorslt[0]);
			$data['ain'] = $ain;
			$cfLoginId = $ain."CF";

			$sql_lic = "SELECT License_No FROM organization_profiles 
			INNER JOIN users ON users.org_id = organization_profiles.id
			WHERE login_id = '$cfLoginId'";

			$data_lic = $this->bm->dataSelectDB1($sql_lic);
			$org_license = "";
			for($i=0;$i<count($data_lic);$i++){
				$org_license = $data_lic[$i]['License_No'];
			}

			$sql_cf = "SELECT u_name FROM users WHERE login_id = '$cfLoginId'";
			$data_cf = $this->bm->dataSelectDB1($sql_cf);
			$cf = "";
			for($i=0;count($data_cf)>$i;$i++){
				$cf = $data_cf[$i]['u_name'];
			}
			$data['cf_name'] = $cf;


			$data_assignment = null;
			if($org_license != "")
			{
				$sql_assignment = "SELECT cont_no,rot_no,bl_no,mlo,cf_name AS cf,cont_status,size,ROUND(height,1) AS height,slot AS carrentPosition,Yard_No,Block_No,mfdch_value,assignmentDate,custom_remarks,unit_gkey 
				FROM ctmsmis.tmp_oracle_assignment 
				WHERE cf_lic='$org_license' AND cf_lic!='' AND assignmentDate>=DATE(NOW()) AND mfdch_value NOT IN('CANCEL','APPREF','APPCUS','APPOTH','--') ORDER BY cont_no ASC";

				$data_assignment = $this->bm->dataSelect($sql_assignment);
			}

			$data_jsInfo = null;
			if($org_license != "")
			{
				$sql_jsInfo = "SELECT vcms_vehicle_agent.id,agent_name AS js_name,agent_type,card_number
				FROM vcms_vehicle_agent
				INNER JOIN vcms_vehicle_agency ON vcms_vehicle_agency.id=vcms_vehicle_agent.agency_id
				WHERE agent_type = 'Jetty Sircar'"; // agency_code = '$org_license' AND

				$data_jsInfo = $this->bm->dataSelectDB1($sql_jsInfo);
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

			// Truck Info
			$data_truck = null;
			if($org_license != ""){
				$sql_truck = "SELECT * FROM do_truck_details_entry WHERE update_by = '$cfLoginId' AND DATE(last_update) = DATE(NOW())";
				$data_truck = $this->bm->dataSelectDB1($sql_truck);
			}
			
			$data['rslt_tmpTrkData'] = $data_truck;
			$data['data_jsInfo'] = $data_jsInfo;
			$data['data_assignment'] = $data_assignment;
			$data['flag'] = 1;

			$data['msg'] = $msg;
			$data['title']="Truck Entry for FCL";
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('fclTruckEntryforSecurity',$data);
			$this->load->view('jsAssetsList');
		}
	}

	function fclTruckEntryforSecurity()
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
			$ipaddr = $_SERVER['REMOTE_ADDR'];
			$msg = "";
			$action = $this->input->post('searchByCnfId');
			$cnfAinNo = trim($this->input->post('cnfAinNo'));
			$cnfAinNorslt = explode(" - ",$cnfAinNo);
			$ain = trim($cnfAinNorslt[0]);
			$data['ain'] = $ain;
			$cfLoginId = $ain."CF";

			if(!$this->input->post('editBtn'))
			{
				if(is_null($action))
				{
					$this->fclTruckEntryformforSecurity();
				}
			}

			$sql_lic = "SELECT License_No FROM organization_profiles 
			INNER JOIN users ON users.org_id = organization_profiles.id
			WHERE login_id = '$cfLoginId'";

			$data_lic = $this->bm->dataSelectDB1($sql_lic);
			$org_license = "";
			for($i=0;$i<count($data_lic);$i++){
				$org_license = $data_lic[$i]['License_No'];
			}

			if($this->input->post('delBtn'))
			{
				$login_id = $cfLoginId;	
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

				for($z=0;$z<count($rslt_select);$z++)
				{
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

				$sql_log = "INSERT INTO delete_log_do_truck_details(visit_id,verify_info_fcl_id,verify_other_data_id,verify_number,import_rotation,cont_no,truck_id,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,truck_agency_name,truck_agency_phone,last_update,ip_addr,update_by,paid_amt,paid_status,paid_method,visit_time_slot_start,visit_time_slot_end,emrgncy_flag,emrgncy_approve_stat,is_confirm,driver_id,helper_id,deleted_by,deleted_time,delete_by_ip) VALUES('$id','$verify_info_fcl_id','$verify_other_data_id','$verify_number','$import_rotation','$cont_no','$truck_id','$gate_no','$driver_name','$driver_gate_pass','$assistant_name','$assistant_gate_pass','$truck_agency_name','$truck_agency_phone','$last_update','$ip_addr','$update_by','$paid_amt','$paid_status','$paid_method','$visit_time_slot_start','$visit_time_slot_end','$emrgncy_flag','$emrgncy_approve_stat','$is_confirm','$driver_id','$helper_id','$login_id',NOW(),'$ipaddr')";
				$this->bm->dataInsertDB1($sql_log);

				$sql_delete = "DELETE  FROM do_truck_details_entry WHERE id='$delId'";
				$del_st = $this->bm->dataDeleteDB1($sql_delete);
				$del_st = 1;
				if($del_st == 1)
				{
					$msg = "<font color='green'>Truck deleted successfully</font>";
				}


				// Search
				$sql_cf = "SELECT u_name FROM users WHERE login_id = '$cfLoginId'";
				$data_cf = $this->bm->dataSelectDB1($sql_cf);
				$cf = "";
				for($i=0;count($data_cf)>$i;$i++){
					$cf = $data_cf[$i]['u_name'];
				}
				$data['cf_name'] = $cf;

				$data_assignment = null;
				if($org_license != "")
				{
					$sql_assignment = "SELECT cont_no,rot_no,bl_no,mlo,cf_name AS cf,cont_status,size,ROUND(height,1) AS height,slot AS carrentPosition,Yard_No,Block_No,mfdch_value,assignmentDate,custom_remarks,unit_gkey 
					FROM ctmsmis.tmp_oracle_assignment 
					WHERE cf_lic='$org_license' AND cf_lic!='' AND assignmentDate>=DATE(NOW()) AND mfdch_value NOT IN('CANCEL','APPREF','APPCUS','APPOTH','--') ORDER BY cont_no ASC";

					$data_assignment = $this->bm->dataSelect($sql_assignment);
				}

				$data_jsInfo = null;
				if($org_license != "")
				{
					$sql_jsInfo = "SELECT vcms_vehicle_agent.id,agent_name AS js_name,agent_type,card_number
					FROM vcms_vehicle_agent
					INNER JOIN vcms_vehicle_agency ON vcms_vehicle_agency.id=vcms_vehicle_agent.agency_id
					WHERE agent_type = 'Jetty Sircar'"; // agency_code = '$org_license' AND

					$data_jsInfo = $this->bm->dataSelectDB1($sql_jsInfo);
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

				// Truck Info
				$data_truck = null;
				if($org_license != "")
				{
					$sql_truck = "SELECT * FROM do_truck_details_entry WHERE update_by = '$cfLoginId' AND DATE(last_update) = DATE(NOW())";
					$data_truck = $this->bm->dataSelectDB1($sql_truck);
				}
				
				$data['rslt_tmpTrkData'] = $data_truck;
				$data['data_jsInfo'] = $data_jsInfo;
				$data['data_assignment'] = $data_assignment;
				$data['flag'] = 1;
				$data['msg'] = $msg;
			}

			if($action == "Search")
			{
				$sql_cf = "SELECT u_name FROM users WHERE login_id = '$cfLoginId'";
				$data_cf = $this->bm->dataSelectDB1($sql_cf);
				$cf = "";
				for($i=0;count($data_cf)>$i;$i++){
					$cf = $data_cf[$i]['u_name'];
				}
				$data['cf_name'] = $cf;


				$data_assignment = null;
				if($org_license != "")
				{
					$sql_assignment = "SELECT cont_no,rot_no,bl_no,mlo,cf_name AS cf,cont_status,size,ROUND(height,1) AS height,slot AS carrentPosition,Yard_No,Block_No,mfdch_value,assignmentDate,custom_remarks,unit_gkey 
					FROM ctmsmis.tmp_oracle_assignment 
					WHERE cf_lic='$org_license' AND cf_lic!='' AND assignmentDate>=DATE(NOW()) AND mfdch_value NOT IN('CANCEL','APPREF','APPCUS','APPOTH','--') ORDER BY cont_no ASC";

					$data_assignment = $this->bm->dataSelect($sql_assignment);
				}

				$data_jsInfo = null;
				if($org_license != "")
				{
					$sql_jsInfo = "SELECT vcms_vehicle_agent.id,agent_name AS js_name,agent_type,card_number
					FROM vcms_vehicle_agent
					INNER JOIN vcms_vehicle_agency ON vcms_vehicle_agency.id=vcms_vehicle_agent.agency_id
					WHERE agent_type = 'Jetty Sircar'"; // agency_code = '$org_license' AND

					$data_jsInfo = $this->bm->dataSelectDB1($sql_jsInfo);
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

				// Truck Info
				$data_truck = null;
				if($org_license != ""){
					$sql_truck = "SELECT * FROM do_truck_details_entry WHERE update_by = '$cfLoginId' AND DATE(last_update) = DATE(NOW())";
					$data_truck = $this->bm->dataSelectDB1($sql_truck);
				}
				
				$data['rslt_tmpTrkData'] = $data_truck;
				$data['data_jsInfo'] = $data_jsInfo;
				$data['data_assignment'] = $data_assignment;
				$data['flag'] = 1;
			}
			else if($action == "Add" || $action == "Emergency")
			{
				$assignment = $this->input->post('assignment');
				$data = explode("|",$assignment);
				$contNo = "";
				$rotNo = "";
				$cont_status = "";
				$unit_gkey = "";
				$assignmentType = "";
				$cntArray = count($data);

				if($cntArray>1)
				{
					$contNo = $data[0];
					$rotNo = $data[1];
					$cont_status = $data[2];
					$unit_gkey = $data[3];
					$assignmentType = $data[5];

					$blNo = $this->bm->getBlByRotCont($rotNo,$contNo);
					$result = $this->bm->chkBlockedContainerforTruckEntry($contNo,$rotNo,$blNo);
					$custom_block_status = "";
					for($ij = 0; $ij<count($result);$ij++){
						$custom_block_status = $result[$ij]['custom_block_st'];
					}
					
					if($custom_block_status == "DO_NOT_RELEASE"){
						$this->fclTruckSearchforSecurity($cnfAinNo);
						return;
					}
				}
				// $jsGatePass = $this->input->post('jsGatePass');
				// $jsId = "";
				// $jsName = "";
				// $sql_jsid = "SELECT id,agent_name FROM vcms_vehicle_agent WHERE card_number = '$jsGatePass'";
				// $data_jsid = $this->bm->dataSelectDB1($sql_jsid);
				// for($i=0;$i<count($data_jsid);$i++)
				// {
				// 	$jsId = $data_jsid[$i]['id'];
				// 	$jsName =$data_jsid[$i]['agent_name'];
				// }

				// if($cont_status == "FCL")
				// {
					$login_id = $cfLoginId;
					$sql_chkExist = "SELECT COUNT(*) AS rtnValue 
					FROM verify_info_fcl 
					WHERE rotation='$rotNo' AND cont_number='$contNo'";
					$rslt_chkExist = $this->bm->dataSelectDb1($sql_chkExist);
					$cnt = "";
					for($i=0;count($rslt_chkExist)>$i;$i++)
					{
						$cnt = $rslt_chkExist[$i]['rtnValue'];
					}
					
					$sql_igmDtlContId = "SELECT igm_details.id AS igm_dtl_id,igm_detail_container.id AS igm_dtl_cont_id,cont_size
					FROM igm_details
					INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
					WHERE Import_Rotation_No='$rotNo' AND cont_number='$contNo'";
					$rslt_igmDtlContId = $this->bm->dataSelectDb1($sql_igmDtlContId);
					$igmDtlId = "";
					$igmDtlContId = "";
					$cont_size = "";
					for($i=0;count($rslt_igmDtlContId)>$i;$i++)
					{
						$igmDtlId = $rslt_igmDtlContId[$i]['igm_dtl_id'];				
						$igmDtlContId = $rslt_igmDtlContId[$i]['igm_dtl_cont_id'];
						$cont_size = $rslt_igmDtlContId[$i]['cont_size'];
					}
				
					if($cont_size == 20)
						$truck_qty = 2;
					// else if($cont_size == 40)
					else
						$truck_qty = 3;
					
					$sql_smsNo = "SELECT cf_sms_number 
					FROM ctmsmis.tmp_oracle_assignment
					WHERE cf_lic='$org_license' AND cf_sms_number IS NOT NULL";
					$rslt_smsNo = $this->bm->dataSelect($sql_smsNo);
					$smsNo = "";
					for($i=0;count($rslt_smsNo)>$i;$i++)
					{
						$smsNo = $rslt_smsNo[$i]['cf_sms_number'];
					}

					//checking part BL

					$partBLQuery = "SELECT COUNT(*) AS rtnValue FROM igm_sup_detail_container 
					INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=igm_sup_detail_container.igm_sup_detail_id
					WHERE cont_number='$contNo' AND Import_Rotation_No='$rotNo' AND cont_status='FCL/PART'";
					$rslt_partBL = $this->bm->dataSelectDb1($partBLQuery);
					$partbl = 0;
					for($i=0;$i<count($rslt_partBL);$i++){
						$partbl = $rslt_partBL[$i]['rtnValue'];
					}

					if($partbl == 0){
						$partBLQuery = "SELECT COUNT(*) AS rtnValue
						FROM igm_detail_container
						INNER JOIN igm_details ON igm_detail_container.igm_detail_id=igm_details.id
						WHERE igm_details.Import_Rotation_No='$rotNo' AND igm_detail_container.cont_number='$contNo' AND cont_status='FCL/PART'";
						$rslt_partBL = $this->bm->dataSelectDb1($partBLQuery);
						$partbl = 0;
						for($i=0;$i<count($rslt_partBL);$i++){
							$partbl = $rslt_partBL[$i]['rtnValue'];
						}
					}

					$partblsts = 0;
					
					if($partbl>0){
						$partblsts = 1;
					}

					if($cntArray>1)
					{
						if($cnt==0)
						{			
							$sql_insertQtyTruck = "INSERT INTO verify_info_fcl(igm_detail_id,igm_detail_cont_id,assignment_type,cnf_lic_no,cnf_mobile_no,unit_gkey,rotation,cont_number,no_of_truck,is_part_bl,truck_no_by,truck_no_time)
							VALUES('$igmDtlId','$igmDtlContId','$assignmentType','$org_license','$smsNo','$unit_gkey','$rotNo','$contNo','$truck_qty','$partblsts','$login_id',NOW())";
							
							if($this->bm->dataInsertDB1($sql_insertQtyTruck))
								$msg = "<font color='green'><b>Truck number entered succesfully.</b></font>";
						}
						else
						{
							$sql_updateQtyTruck = "UPDATE verify_info_fcl
							SET cnf_mobile_no='$smsNo',unit_gkey='$unit_gkey',no_of_truck='$truck_qty',is_part_bl='$partblsts',truck_no_by='$login_id',truck_no_time=NOW()
							WHERE rotation='$rotNo' AND cont_number='$contNo'";
							
							if($this->bm->dataUpdateDB1($sql_updateQtyTruck))
								$msg = "<font color='green'><b>Truck number updated succesfully.</b></font>";
						}
					}
					
					//Verify Info FCL ID
					$sql_verifyInfoFclid = "SELECT id FROM verify_info_fcl WHERE rotation='$rotNo' AND cont_number='$contNo'";
					$data_vrfyinfofclId = $this->bm->dataSelectDB1($sql_verifyInfoFclid);
					$vrfyInfoFclId = "";
					for($i=0;count($data_vrfyinfofclId)>$i;$i++)
					{
						$vrfyInfoFclId = $data_vrfyinfofclId[$i]['id'];
					}

					// Jetty Sircar Entry -- Starts 

					// $sql_chkJS = "SELECT COUNT(*) AS rtnValue
					// FROM verify_info_fcl
					// WHERE jetty_sirkar_id='$jsId' AND id='$vrfyInfoFclId'";
					// $rslt_chkJS = $this->bm->dataSelectDB1($sql_chkJS);
					// $chkJS = "";
					// for($i=0;count($rslt_chkJS)>$i;$i++)
					// {
					// 	$chkJS = $rslt_chkJS[$i]['rtnValue'];
					// }
					
					// if($chkJS == 0)
					// {
					// 	$prevJS = "";
					// 	// get previous JS	- check if previous exists
					// 	$sql_prevJS = "SELECT jetty_sirkar_id
					// 	FROM verify_info_fcl
					// 	WHERE id='$vrfyInfoFclId'";
					// 	$rslt_prevJS = $this->bm->dataSelectDB1($sql_prevJS);
						
					// 	for($i=0;$i<count($rslt_prevJS);$i++){
					// 		$prevJS = $rslt_prevJS[$i]['jetty_sirkar_id'];
					// 	}
						
					// 	// Insert into log
					// 	if($prevJS!="" or $prevJS!=null)
					// 	{
					// 		$sql_jsLog = "INSERT INTO vcms_jetty_sirkar_log(verify_info_fcl_id,prev_jetty_sirkar_id,replace_by,replace_dt)
					// 		VALUES('$vrfyInfoFclId','$prevJS','$login_id',NOW())";
					// 		$this->bm->dataInsertDB1($sql_jsLog);
					// 	}
						
					// 	// Update JS
					// 	$sql_updateJS = "UPDATE verify_info_fcl
					// 	SET jetty_sirkar_id='$jsId'
					// 	WHERE id='$vrfyInfoFclId'";
					// 	$this->bm->dataUpdateDB1($sql_updateJS);
					// }

					// Jetty Sircar Entry -- Ends

					// Add Truck - starts
		
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

					$totTruck = $truck_qty;
					
					$frmSlot = $this->input->post('truckSlot');			// added on 2021-03-01		
					
					$emrgncy_flag = 0;
					$emrgncy_approve_stat = 0;

					if($action == "Emergency")
					{
						$emrgncy_flag = 1;	
					}

					$strUpdateSlot = "UPDATE ctmsmis.tmp_oracle_assignment SET assignment_slot='$frmSlot' WHERE cont_no='$contNo' AND rot_no='$rotNo'";
					$this->bm->dataUpdate($strUpdateSlot);
											
					$sql_timeSlot = "SELECT assignment_slot,assignmentDate,DATE_ADD(assignmentDate, INTERVAL 1 DAY) AS nxtDt
					FROM ctmsmis.tmp_oracle_assignment
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

					$sql_chkTruck = "SELECT COUNT(*) AS rtnValue
					FROM do_truck_details_entry 
					WHERE truck_id='$truckId' AND visit_time_slot_start='$sSlot' AND visit_time_slot_end='$eSlot'";
					$rslt_chkTruck = $this->bm->dataSelectDB1($sql_chkTruck);
					$chkTruck = "";
					for($i=0;count($rslt_chkTruck)>$i;$i++){
						$chkTruck = $rslt_chkTruck[$i]['rtnValue'];
					}

					$gate_no = $this->session->userdata('section');
					
					if($chkTruck==0)
					{
						$strInsertEq = "INSERT INTO do_truck_details_entry(verify_info_fcl_id,import_rotation,cont_no,truck_id,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,truck_agency_name,truck_agency_phone,update_by,ip_addr,last_update,emrgncy_flag,emrgncy_approve_stat,visit_time_slot_start,visit_time_slot_end,entry_from,paid_amt,paid_status,paid_method,paid_collect_dt,paid_collect_by,pay_collect_ip,gate_no)
						VALUES('$vrfyInfoFclId','$rotNo','$contNo','$truckId','$driverName','$driverPassNo','$assistantName','$assistantPassNo','$agencyName','$agencyPhone','$login_id','$ipaddr',NOW(),'$emrgncy_flag','$emrgncy_approve_stat','$sSlot','$eSlot','security','$payAmt',1,'cash',NOW(),'$user','$ipaddr','$gate_no')";
						$stat = $this->bm->dataInsertDB1($strInsertEq);
						// $stat = 1;
						if($stat == 1)
							$msg = "<font color='green'><b>Truck added successfully</b></font>";

						$visitId_query = "SELECT id FROM do_truck_details_entry WHERE truck_id = '$truckId' AND driver_gate_pass='$driverPassNo' AND update_by = '$login_id' AND paid_collect_by = '$user'";

						$visitId_rslt = $this->bm->dataSelectDB1($visitId_query);
						$visit_id = "";

						for($vi=0;$vi<count($visitId_rslt);$vi++)
						{
							$visit_id = $visitId_rslt[$vi]['id'];
						}

						$trkPart = explode(" ",$truckId);
						$trck = $trkPart[0]." ".$trkPart[3]." ".$trkPart[4];   
						//$trck = urlencode($trck);
		
						if($stat == 1)
						{
							if($visit_id != 0)
							{
								$eventType = "ISSUE";
								$biometricInsertQuery = "INSERT INTO biometricEventLog(visit_id,event_type,ain_no,driver_pass,helper_pass,truck_id,entry_at,entry_by,entry_ip) VALUES('$visit_id','$eventType','$ain','$driverPassNo','$assistantPassNo','$trck',NOW(),'$user','$ipaddr')";
								$this->bm->dataInsertDB1($biometricInsertQuery);
							}
						}
						
					}
					else
					{
						$msg = "<font color='red'><b>This truck was assigned for this time slot previously</b></font>";
					}
					
					if($cntArray>1)
					{
						$sql_updateImporterMbl = "UPDATE verify_info_fcl
						SET importer_mobile_no='$importerMobileNo'
						WHERE id='$vrfyInfoFclId'";
						$this->bm->dataUpdateDB1($sql_updateImporterMbl);

						$sql_updateSlot = "UPDATE verify_info_fcl
						SET truck_slot = '$asSlot'
						WHERE id='$vrfyInfoFclId'";
						$this->bm->dataUpdateDB1($sql_updateSlot);
					}

					// Add Truck - Ends

					// search Data  - Starts

					$sql_cf = "SELECT u_name FROM users WHERE login_id = '$cfLoginId'";
					$data_cf = $this->bm->dataSelectDB1($sql_cf);
					$cf = "";
					for($i=0;count($data_cf)>$i;$i++){
						$cf = $data_cf[$i]['u_name'];
					}
					$data['cf_name'] = $cf;

					$data_assignment = null;
					if($org_license != "")
					{
						$sql_assignment = "SELECT cont_no,rot_no,bl_no,mlo,cf_name AS cf,cont_status,size,ROUND(height,1) AS height,slot AS carrentPosition,Yard_No,Block_No,mfdch_value,assignmentDate,custom_remarks,unit_gkey 
						FROM ctmsmis.tmp_oracle_assignment 
						WHERE cf_lic='$org_license' AND cf_lic!='' AND assignmentDate>=DATE(NOW()) AND mfdch_value NOT IN('CANCEL','APPREF','APPCUS','APPOTH','--') ORDER BY cont_no ASC";

						$data_assignment = $this->bm->dataSelect($sql_assignment);
					}

					$data_jsInfo = null;
					if($org_license != "")
					{
						$sql_jsInfo = "SELECT vcms_vehicle_agent.id,agent_name AS js_name,agent_type,card_number
						FROM vcms_vehicle_agent
						INNER JOIN vcms_vehicle_agency ON vcms_vehicle_agency.id=vcms_vehicle_agent.agency_id
						WHERE agent_type = 'Jetty Sircar'"; // agency_code = '$org_license' AND

						$data_jsInfo = $this->bm->dataSelectDB1($sql_jsInfo);
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

					// Truck Info
					$data_truck = null;
					if($org_license != ""){
						$sql_truck = "SELECT * FROM do_truck_details_entry WHERE update_by = '$cfLoginId' AND DATE(last_update) = DATE(NOW())";
						$data_truck = $this->bm->dataSelectDB1($sql_truck);
					}
					
					$data['rslt_tmpTrkData'] = $data_truck;
					$data['data_jsInfo'] = $data_jsInfo;
					$data['data_assignment'] = $data_assignment;
					$data['flag'] = 1;
					$data['ain'] = $ain;
					//$data['jsGatePass'] = $jsGatePass;
					$data['contNo'] = $contNo;

					// Load Data  -- Ends 

				// }
				// else if($cont_status == "LCL")
				// {
					
				// }

				$visitId_Query = "SELECT id FROM do_truck_details_entry WHERE update_by = '$cfLoginId' AND truck_id = '$truckId' AND paid_collect_by = '$user'";
				$visitId_Rslt = $this->bm->dataSelectDb1($visitId_Query);
				$trucVisitId = "";
				for($vi=0;count($visitId_Rslt)>$vi;$vi++){
					$trucVisitId = $visitId_Rslt[$vi]['id'];
				}

				$data['msg'] = $msg;
				$data['title']="Vehicle Gate Pass";
				$data['rot_no'] = $rotNo;
				$data['cont_no'] = $contNo;
				$data['trucVisitId'] = $trucVisitId;
				$data['login_id'] = $user;
				$data['flag'] = 'FCL';

				$this->load->view('cssAssetsList');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('vehiclegatePassforSecurity',$data);
				$this->load->view('jsAssetsList');
				return;
			}
			else if($action == "Update")
			{
				$editType = $this->input->post('editType');
				$assignment = $this->input->post('assignmentData');
				$data = explode("|",$assignment);
				$contNo = $data[0];
				$rotNo = $data[1];
				$cont_status = $data[2];
				$unit_gkey = $data[3];
				$assignmentType = $data[4];

				if(!is_null($rotNo) || !is_null($contNo)){
					$blNo = $this->bm->getBlByRotCont($rotNo,$contNo);
					$result = $this->bm->chkBlockedContainerforTruckEntry($contNo,$rotNo,$blNo);
					$custom_block_status = "";
					for($ij = 0; $ij<count($result);$ij++){
						$custom_block_status = $result[$ij]['custom_block_st'];
					}
					
					if($custom_block_status == "DO_NOT_RELEASE"){
						$this->fclTruckSearchforSecurity($cnfAinNo);
						return;
					}
				}

				// $jsGatePass = $this->input->post('jsGatePass');
				// $jsId = "";
				// $jsName = "";
				// $sql_jsid = "SELECT id,agent_name FROM vcms_vehicle_agent WHERE card_number = '$jsGatePass'";
				// $data_jsid = $this->bm->dataSelectDB1($sql_jsid);
				// for($i=0;$i<count($data_jsid);$i++)
				// {
				// 	$jsId = $data_jsid[$i]['id'];
				// 	$jsName =$data_jsid[$i]['agent_name'];
				// }

				if($cont_status == "FCL")
				{
					$login_id = $cfLoginId;
					
					$sql_igmDtlContId = "SELECT igm_details.id AS igm_dtl_id,igm_detail_container.id AS igm_dtl_cont_id,cont_size
					FROM igm_details
					INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
					WHERE Import_Rotation_No='$rotNo' AND cont_number='$contNo'";
					$rslt_igmDtlContId = $this->bm->dataSelectDb1($sql_igmDtlContId);
					$igmDtlId = "";
					$igmDtlContId = "";
					$cont_size = "";
					for($i=0;count($rslt_igmDtlContId)>$i;$i++)
					{
						$igmDtlId = $rslt_igmDtlContId[$i]['igm_dtl_id'];				
						$igmDtlContId = $rslt_igmDtlContId[$i]['igm_dtl_cont_id'];
						$cont_size = $rslt_igmDtlContId[$i]['cont_size'];
					}
				
					if($cont_size == 20)
						$truck_qty = 2;
					// else if($cont_size == 40)
					else
						$truck_qty = 3;
					
					$sql_smsNo = "SELECT cf_sms_number 
					FROM ctmsmis.tmp_oracle_assignment
					WHERE cf_lic='$org_license' AND cf_sms_number IS NOT NULL";
					$rslt_smsNo = $this->bm->dataSelect($sql_smsNo);
					$smsNo = "";
					for($i=0;count($rslt_smsNo)>$i;$i++)
					{
						$smsNo = $rslt_smsNo[$i]['cf_sms_number'];
					}

					
					$sql_updateQtyTruck = "UPDATE verify_info_fcl
					SET cnf_mobile_no='$smsNo',unit_gkey='$unit_gkey',no_of_truck='$truck_qty',truck_no_by='$login_id',truck_no_time=NOW()
					WHERE rotation='$rotNo' AND cont_number='$contNo'";
					
					if($this->bm->dataUpdateDB1($sql_updateQtyTruck))
						$msg = "<font color='green'><b>Truck number updated succesfully.</b></font>";

					//Verify Info FCL ID
					$sql_verifyInfoFclid = "SELECT id FROM verify_info_fcl WHERE rotation='$rotNo' AND cont_number='$contNo'";
					$data_vrfyinfofclId = $this->bm->dataSelectDB1($sql_verifyInfoFclid);
					$vrfyInfoFclId = "";
					for($i=0;count($data_vrfyinfofclId)>$i;$i++)
					{
						$vrfyInfoFclId = $data_vrfyinfofclId[$i]['id'];
					}

					// Jetty Sircar Entry -- Starts 

					// $sql_chkJS = "SELECT COUNT(*) AS rtnValue
					// FROM verify_info_fcl
					// WHERE jetty_sirkar_id='$jsId' AND id='$vrfyInfoFclId'";
					// $rslt_chkJS = $this->bm->dataSelectDB1($sql_chkJS);
					// $chkJS = "";
					// for($i=0;count($rslt_chkJS)>$i;$i++)
					// {
					// 	$chkJS = $rslt_chkJS[$i]['rtnValue'];
					// }
					
					// if($chkJS == 0)
					// {
					// 	$prevJS = "";
					// 	// get previous JS	- check if previous exists
					// 	$sql_prevJS = "SELECT jetty_sirkar_id
					// 	FROM verify_info_fcl
					// 	WHERE id='$vrfyInfoFclId'";
					// 	$rslt_prevJS = $this->bm->dataSelectDB1($sql_prevJS);
					// 	$prevJS = $rslt_prevJS[0]['jetty_sirkar_id'];
						
					// 	// Insert into log
					// 	if($prevJS!="" or $prevJS!=null)
					// 	{
					// 		$sql_jsLog = "INSERT INTO vcms_jetty_sirkar_log(verify_info_fcl_id,prev_jetty_sirkar_id,replace_by,replace_dt)
					// 		VALUES('$vrfyInfoFclId','$prevJS','$login_id',NOW())";
					// 		$this->bm->dataInsertDB1($sql_jsLog);
					// 	}
						
					// 	// Update JS
					// 	$sql_updateJS = "UPDATE verify_info_fcl
					// 	SET jetty_sirkar_id='$jsId'
					// 	WHERE id='$vrfyInfoFclId'";
					// 	$this->bm->dataUpdateDB1($sql_updateJS);
					// }

					// Jetty Sircar Entry -- Ends

					// Edit Truck - starts 
					
					$truckVisitId = $this->input->post('truckVisitId');

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

					$totTruck = $truck_qty;

					// Added on 02 Aug 2021

					$frmSlot = $this->input->post('truckSlot');		
					
					$emrgncy_flag = 0;
					$emrgncy_approve_stat = 0;

					if($action == "Emergency")
					{
						$emrgncy_flag = 1;	
					}

					$strUpdateSlot = "UPDATE ctmsmis.tmp_oracle_assignment SET assignment_slot='$frmSlot' WHERE cont_no='$contNo' AND rot_no='$rotNo'";
					$this->bm->dataUpdate($strUpdateSlot);
											
					$sql_timeSlot = "SELECT assignment_slot,assignmentDate,DATE_ADD(assignmentDate, INTERVAL 1 DAY) AS nxtDt
					FROM ctmsmis.tmp_oracle_assignment
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

					// Added on 02 Aug 2021

					$payAmt = 57.5;

					if($editType == "Replace")
					{				
						$sql_replaceInfo = "SELECT id,truck_id,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,paid_amt,paid_method,paid_collect_dt,paid_collect_by,pay_collect_ip
						FROM do_truck_details_entry
						WHERE id='$truckVisitId'";
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
						$repPaidCollectBy = $rslt_replaceInfo[0]['paid_collect_by'];
						$repPaidCollectIp = $rslt_replaceInfo[0]['pay_collect_ip'];
						
						$sql_insertReplace = "INSERT INTO vcms_replace_truck_log(visit_id,truck_id,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,paid_amt,paid_method,paid_collect_dt,replace_time,replace_by,import_rotation,cont_no,paid_collect_by,pay_collect_ip)
						VALUES('$repVisitId','$repTruckId','$repDriverName','$repDriverGatePass','$repAssistantName','$repAssistantGatePass','$repPaidAmt','$repPaidMethod','$repPaidCollectDt',NOW(),'$login_id','$rotNo','$contNo','$repPaidCollectBy','$repPaidCollectIp')";

						$this->bm->dataInsertDB1($sql_insertReplace);
						
						$sql_updateTruckInfo = "UPDATE do_truck_details_entry
						SET truck_id='$truckId',driver_name='$driverName',driver_gate_pass='$driverPassNo',assistant_name='$assistantName',assistant_gate_pass='$assistantPassNo',truck_agency_name='$agencyName',truck_agency_phone='$agencyPhone',paid_amt='57.5',paid_status=1,paid_method='cash',gate_no = '',paid_collect_dt=NOW(),paid_collect_by='$user',pay_collect_ip='$ipaddr',gate_in_status='0',gate_in_by=NULL,gate_in_time=NULL,last_update=NOW()
						WHERE id='$truckVisitId'";
						$this->bm->dataUpdateDB1($sql_updateTruckInfo);	
					}
					else if($editType == "Edit")			// check with it later
					{
						$sql_updateTruckInfo = "UPDATE do_truck_details_entry
						SET truck_id='$truckId',driver_name='$driverName',driver_gate_pass='$driverPassNo',assistant_name='$assistantName',assistant_gate_pass='$assistantPassNo',truck_agency_name='$agencyName',truck_agency_phone='$agencyPhone',edit_at=NOW(),edit_by='$login_id',edit_ip='$ipaddr'
						WHERE id='$truckVisitId'";
						$this->bm->dataUpdateDB1($sql_updateTruckInfo);	
					}

					$sql_updateImporterMbl = "UPDATE verify_info_fcl
					SET importer_mobile_no='$importerMobileNo'
					WHERE id='$vrfyInfoFclId'";
					$this->bm->dataUpdateDB1($sql_updateImporterMbl);

					$sql_updateSlot = "UPDATE verify_info_fcl
					SET truck_slot = '$asSlot'
					WHERE id='$vrfyInfoFclId'";
					$this->bm->dataUpdateDB1($sql_updateSlot);
						

					// Edit Truck - Ends

					// Search Data  - Starts

					$sql_cf = "SELECT u_name FROM users WHERE login_id = '$cfLoginId'";
					$data_cf = $this->bm->dataSelectDB1($sql_cf);
					$cf = "";
					for($i=0;count($data_cf)>$i;$i++){
						$cf = $data_cf[$i]['u_name'];
					}
					$data['cf_name'] = $cf;

					$data_assignment = null;
					if($org_license != "")
					{
						$sql_assignment = "SELECT cont_no,rot_no,bl_no,mlo,cf_name AS cf,cont_status,size,ROUND(height,1) AS height,slot AS carrentPosition,Yard_No,Block_No,mfdch_value,assignmentDate,custom_remarks,unit_gkey 
						FROM ctmsmis.tmp_oracle_assignment 
						WHERE cf_lic='$org_license' AND cf_lic!='' AND assignmentDate>=DATE(NOW()) AND mfdch_value NOT IN('CANCEL','APPREF','APPCUS','APPOTH','--') ORDER BY cont_no ASC";

						$data_assignment = $this->bm->dataSelect($sql_assignment);
					}

					$data_jsInfo = null;
					if($org_license != "")
					{
						$sql_jsInfo = "SELECT vcms_vehicle_agent.id,agent_name AS js_name,agent_type,card_number
						FROM vcms_vehicle_agent
						INNER JOIN vcms_vehicle_agency ON vcms_vehicle_agency.id=vcms_vehicle_agent.agency_id
						WHERE agent_type = 'Jetty Sircar'"; // agency_code = '$org_license' AND

						$data_jsInfo = $this->bm->dataSelectDB1($sql_jsInfo);
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

					// Truck Info
					$data_truck = null;
					if($org_license != "")
					{
						$sql_truck = "SELECT * FROM do_truck_details_entry WHERE update_by = '$cfLoginId' AND DATE(last_update) = DATE(NOW())";
						$data_truck = $this->bm->dataSelectDB1($sql_truck);
					}
					
					$data['rslt_tmpTrkData'] = $data_truck;
					$data['data_jsInfo'] = $data_jsInfo;
					$data['data_assignment'] = $data_assignment;
					$data['flag'] = 1;
					$data['ain'] = $ain;
					// $data['jsGatePass'] = $jsGatePass;

					// Load Data  -- Ends 

				}
				else if($cont_status == "LCL")
				{

				}
			}

			// Edit Truck 
				
			if($this->input->post('editBtn'))
			{
				$editId = $this->input->post('editId');
				$btnType = $this->input->post('btnType');
				$data['editType'] = $btnType;
				
				$editType = $this->input->post('editBtn');
				$data['editType']=$editType;	
				
				$sql_trkEditInfo = "SELECT id,verify_info_fcl_id,truck_id,driver_name,driver_gate_pass,truck_agency_name,cont_no,truck_agency_phone,
				(SELECT DISTINCT mobile_number FROM vcms_vehicle_agent WHERE card_number=driver_gate_pass) AS driver_mobile_number,
				assistant_name,assistant_gate_pass,
				(SELECT DISTINCT mobile_number FROM vcms_vehicle_agent WHERE card_number=assistant_gate_pass) AS helper_mobile_number
				FROM do_truck_details_entry
				WHERE id='$editId'";
				$rslt_trkEditInfo = $this->bm->dataSelectDB1($sql_trkEditInfo);
				$data['rslt_trkEditInfo']=$rslt_trkEditInfo;

				$cont = "";
				$vrfyInfoFclId = "";
				for($i=0;$i<count($rslt_trkEditInfo);$i++)
				{
					$vrfyInfoFclId = $rslt_trkEditInfo[$i]['verify_info_fcl_id'];
					$cont = $rslt_trkEditInfo[$i]['cont_no'];
				}
				$data['cont'] = $cont;

				// Assignment Data

				$sql_assignmentData = "SELECT * FROM ctmsmis.tmp_oracle_assignment WHERE assignmentDate = DATE(NOW()) AND cont_no = '$cont'";
				$rslt_assignmentData = $this->bm->dataSelect($sql_assignmentData);
				$assignmentData = "";
				for($i=0;$i<count($rslt_assignmentData);$i++)
				{
					$assignmentData = $rslt_assignmentData[$i]['cont_no']."|".$rslt_assignmentData[$i]['rot_no']."|".$rslt_assignmentData[$i]['cont_status']."|".$rslt_assignmentData[$i]['unit_gkey']."|".$rslt_assignmentData[$i]['mfdch_value'];
				}
				$data['assignmentData'] = $assignmentData;

				// Importer's Mobile

				$sql_importerMobile = "SELECT importer_mobile_no,jetty_sirkar_id,truck_slot FROM verify_info_fcl WHERE id='$vrfyInfoFclId'";
				$rslt_importerMobile = $this->bm->dataSelectDB1($sql_importerMobile);

				$jetty_sirkar_id = "";
				$importerMobile = "";
				$truckSlot = "";
				for($i=0;$i<count($rslt_importerMobile);$i++)
				{
					$importerMobile = $rslt_importerMobile[$i]['importer_mobile_no'];
					$jetty_sirkar_id = $rslt_importerMobile[$i]['jetty_sirkar_id'];
					$truckSlot = $rslt_importerMobile[0]['truck_slot'];
				}

				$data['truckSlot']=$truckSlot;
				$data['importerMobile']=$importerMobile;
				
				// Jetty Sirkar Card Number

				$sql_jetty_Sirkar = "SELECT card_number FROM vcms_vehicle_agent WHERE id = '$jetty_sirkar_id'";
				$rslt_jetty_Sirkar = $this->bm->dataSelectDB1($sql_jetty_Sirkar);

				$cardNumber = "";
				for($i=0;$i<count($rslt_jetty_Sirkar);$i++)
				{
					$cardNumber = $rslt_jetty_Sirkar[$i]['card_number'];
				}

				$data['cardNumber']=$cardNumber;

				$sql_jsInfo = "SELECT agent_name, agent_code FROM vcms_vehicle_agent WHERE card_number = '$cardNumber'";
				$rslt_jsInfo = $this->bm->dataSelectDB1($sql_jsInfo);

				$agent_name = "";
				$agent_code = "";
				for($i=0;$i<count($rslt_jsInfo);$i++)
				{
					$agent_name = $rslt_jsInfo[$i]['agent_name'];
					$agent_code = $rslt_jsInfo[$i]['agent_code'];
				}

				$data['agent_name'] = $agent_name;
				$data['agent_code'] = $agent_code;
				
				// Search Data

				$sql_cf = "SELECT u_name FROM users WHERE login_id = '$cfLoginId'";
				$data_cf = $this->bm->dataSelectDB1($sql_cf);
				$cf = "";
				for($i=0;count($data_cf)>$i;$i++){
					$cf = $data_cf[$i]['u_name'];
				}
				$data['cf_name'] = $cf;

				$data_assignment = null;
				if($org_license != "")
				{
					$sql_assignment = "SELECT cont_no,rot_no,bl_no,mlo,cf_name AS cf,cont_status,size,ROUND(height,1) AS height,slot AS carrentPosition,Yard_No,Block_No,mfdch_value,assignmentDate,custom_remarks,unit_gkey 
					FROM ctmsmis.tmp_oracle_assignment 
					WHERE cf_lic='$org_license' AND cf_lic!='' AND assignmentDate>=DATE(NOW()) AND mfdch_value NOT IN('CANCEL','APPREF','APPCUS','APPOTH','--') ORDER BY cont_no ASC";

					$data_assignment = $this->bm->dataSelect($sql_assignment);
				}

				$data_jsInfo = null;
				if($org_license != "")
				{
					$sql_jsInfo = "SELECT vcms_vehicle_agent.id,agent_name AS js_name,agent_type,card_number
					FROM vcms_vehicle_agent
					INNER JOIN vcms_vehicle_agency ON vcms_vehicle_agency.id=vcms_vehicle_agent.agency_id
					WHERE agent_type = 'Jetty Sircar'"; // agency_code = '$org_license' AND

					$data_jsInfo = $this->bm->dataSelectDB1($sql_jsInfo);
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

				// Truck Info
				$data_truck = null;
				if($org_license != "")
				{
					$sql_truck = "SELECT * FROM do_truck_details_entry WHERE update_by = '$cfLoginId' AND DATE(last_update) = DATE(NOW())";
					$data_truck = $this->bm->dataSelectDB1($sql_truck);
				}

				$data['rslt_tmpTrkData'] = $data_truck;
				$data['data_jsInfo'] = $data_jsInfo;
				$data['data_assignment'] = $data_assignment;
				$data['flag'] = 1;
							
			}

			$data['msg'] = $msg;
			$data['title']="Truck Entry for FCL";
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('fclTruckEntryforSecurity',$data);
			$this->load->view('jsAssetsList');
		}
	}

	// FCL Truck Entry for Security Panel - end

	// FCL Truck Pay By Security - start

	function truckPayBySecurity()
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
			$msg = "";
			$action = $this->input->post('searchByCnfId');
			$ain = $this->input->post('cnfAinNo');
			$cfLoginId = $ain."CF";
			$payAmt = $this->input->post('payAmt');
			$payMethod = $this->input->post('payMethod');
			$truckDtlId = $this->input->post('truckDtlId');
			$payment = $this->input->post('payment');

			if($payment == 'payment')
			{
				$sql_updatePayment = "UPDATE do_truck_details_entry
				SET paid_amt='$payAmt',paid_status='2',paid_method='$payMethod'
				WHERE id='$truckDtlId'";
				$stat = $this->bm->dataUpdateDB1($sql_updatePayment);

			}
			else if($payment == 'showData')
			{
				$data['payFlag'] = 1;
			}

			$sql_lic = "SELECT License_No FROM organization_profiles 
			INNER JOIN users ON users.org_id = organization_profiles.id
			WHERE login_id = '$cfLoginId'";

			$data_lic = $this->bm->dataSelectDB1($sql_lic);
			$org_license = "";
			for($i=0;$i<count($data_lic);$i++)
			{
				$org_license = $data_lic[$i]['License_No'];
			}

			$sql_cf = "SELECT u_name FROM users WHERE login_id = '$cfLoginId'";
			$data_cf = $this->bm->dataSelectDB1($sql_cf);
			$cf = "";
			for($i=0;count($data_cf)>$i;$i++){
				$cf = $data_cf[$i]['u_name'];
			}
			$data['cf_name'] = $cf;

			$data_assignment = null;
			if($org_license != "")
			{
				$sql_assignment = "SELECT cont_no,rot_no,mlo,cf_name AS cf,cont_status,size,ROUND(height,1) AS height,slot AS carrentPosition,Yard_No,Block_No,mfdch_value,assignmentDate,custom_remarks,unit_gkey 
				FROM ctmsmis.tmp_oracle_assignment 
				WHERE cf_lic='$org_license' AND cf_lic!='' AND assignmentDate>=DATE(NOW()) AND mfdch_value NOT IN('CANCEL','APPREF','APPCUS','APPOTH','--') ORDER BY cont_no ASC";

				$data_assignment = $this->bm->dataSelect($sql_assignment);
			}

			$data_jsInfo = null;
			if($org_license != "")
			{
				$sql_jsInfo = "SELECT vcms_vehicle_agent.id,agent_name AS js_name,agent_type,card_number
				FROM vcms_vehicle_agent
				INNER JOIN vcms_vehicle_agency ON vcms_vehicle_agency.id=vcms_vehicle_agent.agency_id
				WHERE agent_type = 'Jetty Sircar'"; // agency_code = '$org_license' AND

				$data_jsInfo = $this->bm->dataSelectDB1($sql_jsInfo);
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

			// Truck Info
			$data_truck = null;
			if($org_license != "")
			{
				$sql_truck = "SELECT * FROM do_truck_details_entry WHERE update_by = '$cfLoginId' AND DATE(last_update) = DATE(NOW())";
				$data_truck = $this->bm->dataSelectDB1($sql_truck);
			}
			
			$data['rslt_tmpTrkData'] = $data_truck;
			$data['data_jsInfo'] = $data_jsInfo;
			$data['data_assignment'] = $data_assignment;
			$data['ain'] = $ain;
			$data['flag'] = 1;
			$data['payAmt'] = $payAmt;
			$data['payMethod'] = $payMethod; 
			$data['truckDtlId'] = $truckDtlId;
			
			$data['msg'] = $msg;
			$data['title']="Truck Entry for FCL";
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('fclTruckEntryforSecurity',$data);
			$this->load->view('jsAssetsList');

		}
	}

	// FCL Truck Pay By Security  -- end

	// LCL Truck Entry By Security  -- starts

	function lclTruckEntryformforSecurity()
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
			$data['title']="Truck Entry for LCL";
			$data['flag'] = 0;
			$data['msg'] = "";

			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('lclTruckEntryforSecurity',$data);
			$this->load->view('jsAssets');
		}
	}

	function lclTruckSearchforSecurity($cnfAinNo=null)
	{
		$user = $this->session->userdata('login_id');
		$user = str_replace(" ","",$user);
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$ipaddr = $_SERVER['REMOTE_ADDR'];
			$msg = "";
			
			if(is_null($cnfAinNo))
			{
				$cnfAinNo = trim($this->input->post('cnfAin'));
			}
			else
			{
				$msg = "<font color='red' size='4'><b>This container is blocked by custom.</b></font>";
			}

			$cnfAinNorslt = explode(" - ",$cnfAinNo);
			$ain = trim($cnfAinNorslt[0]);
			$data['ain'] = $ain;
			$cfLoginId = $ain."CF";

			$sql_lic = "SELECT License_No FROM organization_profiles 
			INNER JOIN users ON users.org_id = organization_profiles.id
			WHERE login_id = '$cfLoginId'";

			$data_lic = $this->bm->dataSelectDB1($sql_lic);
			$org_license = "";
			for($i=0;$i<count($data_lic);$i++){
				$org_license = $data_lic[$i]['License_No'];
			}

			$cnfLic = explode("/", $org_license);
			$cnfLic_firstpart = $cnfLic[0];
			$cnfLic_firstpart = ltrim($cnfLic_firstpart, '0');

			$sql_cf = "SELECT u_name FROM users WHERE login_id = '$cfLoginId'";
				$data_cf = $this->bm->dataSelectDB1($sql_cf);
				$cf = "";
				for($i=0;count($data_cf)>$i;$i++){
					$cf = $data_cf[$i]['u_name'];
				}
				$data['cf_name'] = $cf;


				$data_assignment = null;
				if($org_license != "")
				{
					$lclAssignmentQuery = "SELECT igm_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no,
					igm_details.Pack_Description, igm_details.Pack_Number, igm_detail_container.cont_status,igm_detail_container.cont_size,
					oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'dtl' AS igm_type,
					IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT CONCAT(shed_yard,' - ',shed_loc) FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
					FROM oracle_nts_data
					INNER JOIN igm_details ON igm_details.id=oracle_nts_data.igm_detail_id
					INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
					WHERE igm_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
					AND REPLACE(LTRIM(REPLACE(oracle_nts_data.cnf_lno,'0',' ')),' ','0') LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id) > 0	
					
					UNION 
					
					SELECT igm_sup_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no, igm_supplimentary_detail.Pack_Description, igm_supplimentary_detail.Pack_Number,igm_sup_detail_container.cont_status,igm_sup_detail_container.cont_size,
					oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'sup_dtl' AS igm_type,
					IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT CONCAT(shed_yard,' - ',shed_loc) FROM shed_tally_info WHERE cont_number=igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
					FROM oracle_nts_data
					INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=oracle_nts_data.igm_sup_detail_id
					INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
					WHERE igm_sup_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
					AND REPLACE(LTRIM(REPLACE(oracle_nts_data.cnf_lno,'0',' ')),' ','0') LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number= igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id) > 0";

					$data_assignment = $this->bm->dataSelectDB1($lclAssignmentQuery);
					// if(empty($data_assignment))
					// {
					// 	if(substr($cnfLic[0], 0, 1)=='0' )
					// 	{
					// 		$cnfLic_firstpart = substr($cnfLic[0], 1);
					// 	}
					// 	else if (substr($cnfLic[0], 0, 2)=='00' )
					// 	{
					// 		$cnfLic_firstpart = substr($cnfLic[0], 2);
					// 	}
							
					// 		/* 
					// 		if(strlen($cnfLic[0])==1)
					// 			$cnfLic_firstpart = "000".$cnfLic[0];
					// 		else if(strlen($cnfLic[0])==2)
					// 			$cnfLic_firstpart = "00".$cnfLic[0];
					// 		else if(strlen($cnfLic[0])==3)
					// 			$cnfLic_firstpart = "0".$cnfLic[0];
					// 		else if(strlen($cnfLic[0])==4)
					// 			$cnfLic_firstpart = "".$cnfLic[0]; */
							
							
					// 	$lclAssignmentQuery = "SELECT igm_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no,
					// 	igm_details.Pack_Description, igm_details.Pack_Number, igm_detail_container.cont_status,igm_detail_container.cont_size,
					// 	oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'dtl' AS igm_type,
					// 	IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT shed_loc FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
					// 	FROM oracle_nts_data
					// 	INNER JOIN igm_details ON igm_details.id=oracle_nts_data.igm_detail_id
					// 	INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
					// 	WHERE igm_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
					// 	AND oracle_nts_data.cnf_lno LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id) > 0	
						
					// 	UNION 
						
					// 	SELECT igm_sup_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no, igm_supplimentary_detail.Pack_Description, igm_supplimentary_detail.Pack_Number,igm_sup_detail_container.cont_status,igm_sup_detail_container.cont_size,
					// 	oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'sup_dtl' AS igm_type,
					// 	IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT shed_loc FROM shed_tally_info WHERE cont_number=igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
					// 	FROM oracle_nts_data
					// 	INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=oracle_nts_data.igm_sup_detail_id
					// 	INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
					// 	WHERE igm_sup_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
					// 	AND oracle_nts_data.cnf_lno LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number= igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id) > 0";

					// 	$data_assignment = $this->bm->dataSelectDB1($lclAssignmentQuery);
					// }					
				}


				$data_jsInfo = null;
				if($org_license != "")
				{
					$sql_jsInfo = "SELECT vcms_vehicle_agent.id,agent_name AS js_name,agent_type,card_number
					FROM vcms_vehicle_agent
					INNER JOIN vcms_vehicle_agency ON vcms_vehicle_agency.id=vcms_vehicle_agent.agency_id
					WHERE agent_type = 'Jetty Sircar'"; // agency_code = '$org_license' AND

					$data_jsInfo = $this->bm->dataSelectDB1($sql_jsInfo);
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

				// Truck Info
				$data_truck = null;
				if($org_license != ""){
					$sql_truck = "SELECT * FROM do_truck_details_entry WHERE update_by = '$cfLoginId' AND DATE(last_update) = DATE(NOW())";
					$data_truck = $this->bm->dataSelectDB1($sql_truck);
				}
				
				$data['rslt_tmpTrkData'] = $data_truck;
				$data['data_jsInfo'] = $data_jsInfo;
				$data['data_assignment'] = $data_assignment;
				$data['flag'] = 1;

				$data['msg'] = $msg;
				$data['title']="Truck Entry for LCL";
				$this->load->view('cssAssetsList');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('lclTruckEntryforSecurity',$data);
				$this->load->view('jsAssetsList');


		}
	}

	function lclTruckEntryforSecurity()
	{
		$user = $this->session->userdata('login_id');
		$user = str_replace(" ","",$user);
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		
		if($LoginStat!="yes")
		{
			$this->logout();
		}
		else
		{
			$ipaddr = $_SERVER['REMOTE_ADDR'];
			$msg = "";
			$action = $this->input->post('searchByCnfId');
			$cnfAinNo = trim($this->input->post('cnfAinNo'));
			$cnfAinNorslt = explode(" - ",$cnfAinNo);
			$ain = trim($cnfAinNorslt[0]);
			$data['ain'] = $ain;
			$cfLoginId = $ain."CF";
			
			if(!$this->input->post('editBtn'))
			{
				if(is_null($action))
				{
					$this->lclTruckEntryformforSecurity();
				}
			}

			$sql_lic = "SELECT License_No FROM organization_profiles 
			INNER JOIN users ON users.org_id = organization_profiles.id
			WHERE login_id = '$cfLoginId'";

			$data_lic = $this->bm->dataSelectDB1($sql_lic);
			$org_license = "";
			for($i=0;$i<count($data_lic);$i++){
				$org_license = $data_lic[$i]['License_No'];
			}

			$cnfLic = explode("/", $org_license);
			$cnfLic_firstpart = $cnfLic[0];
			$cnfLic_firstpart = ltrim($cnfLic_firstpart, '0');

			if($this->input->post('delBtn'))
			{
				$login_id = $cfLoginId;	
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

				for($z=0;$z<count($rslt_select);$z++)
				{
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

				$sql_log = "INSERT INTO delete_log_do_truck_details(visit_id,verify_info_fcl_id,verify_other_data_id,verify_number,import_rotation,cont_no,truck_id,gate_no,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,truck_agency_name,truck_agency_phone,last_update,ip_addr,update_by,paid_amt,paid_status,paid_method,visit_time_slot_start,visit_time_slot_end,emrgncy_flag,emrgncy_approve_stat,is_confirm,driver_id,helper_id,deleted_by,deleted_time,delete_by_ip) VALUES('$id','$verify_info_fcl_id','$verify_other_data_id','$verify_number','$import_rotation','$cont_no','$truck_id','$gate_no','$driver_name','$driver_gate_pass','$assistant_name','$assistant_gate_pass','$truck_agency_name','$truck_agency_phone','$last_update','$ip_addr','$update_by','$paid_amt','$paid_status','$paid_method','$visit_time_slot_start','$visit_time_slot_end','$emrgncy_flag','$emrgncy_approve_stat','$is_confirm','$driver_id','$helper_id','$login_id',NOW(),'$ipaddr')";
				$this->bm->dataInsertDB1($sql_log);

				$sql_delete = "DELETE  FROM do_truck_details_entry WHERE id='$delId'";
				$del_st = $this->bm->dataDeleteDB1($sql_delete);
				$del_st = 1;
				if($del_st == 1)
				{
					$msg = "<font color='green'>Truck deleted successfully</font>";
				}


				// Search
				$sql_cf = "SELECT u_name FROM users WHERE login_id = '$cfLoginId'";
				$data_cf = $this->bm->dataSelectDB1($sql_cf);
				$cf = "";
				for($i=0;count($data_cf)>$i;$i++){
					$cf = $data_cf[$i]['u_name'];
				}
				$data['cf_name'] = $cf;

				$data_assignment = null;
				if($org_license != "")
				{
					$lclAssignmentQuery = "SELECT igm_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no,
					igm_details.Pack_Description, igm_details.Pack_Number, igm_detail_container.cont_status,igm_detail_container.cont_size,
					oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'dtl' AS igm_type,
					IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT CONCAT(shed_yard,' - ',shed_loc) FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
					FROM oracle_nts_data
					INNER JOIN igm_details ON igm_details.id=oracle_nts_data.igm_detail_id
					INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
					WHERE igm_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
					AND REPLACE(LTRIM(REPLACE(oracle_nts_data.cnf_lno,'0',' ')),' ','0') LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id) > 0	
					
					UNION 
					
					SELECT igm_sup_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no, igm_supplimentary_detail.Pack_Description, igm_supplimentary_detail.Pack_Number,igm_sup_detail_container.cont_status,igm_sup_detail_container.cont_size,
					oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'sup_dtl' AS igm_type,
					IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT CONCAT(shed_yard,' - ',shed_loc) FROM shed_tally_info WHERE cont_number=igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
					FROM oracle_nts_data
					INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=oracle_nts_data.igm_sup_detail_id
					INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
					WHERE igm_sup_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
					AND REPLACE(LTRIM(REPLACE(oracle_nts_data.cnf_lno,'0',' ')),' ','0') LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number= igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id) > 0";

					$data_assignment = $this->bm->dataSelectDB1($lclAssignmentQuery);
					
					// if(empty($data_assignment))
					// 	{
					// 		if(substr($cnfLic[0], 0, 1)=='0' )
					// 		{
					// 			$cnfLic_firstpart = substr($cnfLic[0], 1);
					// 		}
					// 		else if (substr($cnfLic[0], 0, 2)=='00' )
					// 		{
					// 			$cnfLic_firstpart = substr($cnfLic[0], 2);
					// 		}
					// 		/* if(strlen($cnfLic[0])==1)
					// 			$cnfLic_firstpart = "000".$cnfLic[0];
					// 		else if(strlen($cnfLic[0])==2)
					// 			$cnfLic_firstpart = "00".$cnfLic[0];
					// 		else if(strlen($cnfLic[0])==3)
					// 			$cnfLic_firstpart = "0".$cnfLic[0];
					// 		else if(strlen($cnfLic[0])==4)
					// 			$cnfLic_firstpart = "".$cnfLic[0]; */
							
							
					// 	$lclAssignmentQuery = "SELECT igm_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no,
					// 	igm_details.Pack_Description, igm_details.Pack_Number, igm_detail_container.cont_status,igm_detail_container.cont_size,
					// 	oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'dtl' AS igm_type,
					// 	IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT shed_loc FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
					// 	FROM oracle_nts_data
					// 	INNER JOIN igm_details ON igm_details.id=oracle_nts_data.igm_detail_id
					// 	INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
					// 	WHERE igm_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
					// 	AND oracle_nts_data.cnf_lno LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id) > 0	
						
					// 	UNION 
						
					// 	SELECT igm_sup_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no, igm_supplimentary_detail.Pack_Description, igm_supplimentary_detail.Pack_Number,igm_sup_detail_container.cont_status,igm_sup_detail_container.cont_size,
					// 	oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'sup_dtl' AS igm_type,
					// 	IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT shed_loc FROM shed_tally_info WHERE cont_number=igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
					// 	FROM oracle_nts_data
					// 	INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=oracle_nts_data.igm_sup_detail_id
					// 	INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
					// 	WHERE igm_sup_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
					// 	AND oracle_nts_data.cnf_lno LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number= igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id) > 0";
					// 	return;
					// 	$data_assignment = $this->bm->dataSelectDB1($lclAssignmentQuery);
					// }
				}

				$data_jsInfo = null;
				if($org_license != "")
				{
					$sql_jsInfo = "SELECT vcms_vehicle_agent.id,agent_name AS js_name,agent_type,card_number
					FROM vcms_vehicle_agent
					INNER JOIN vcms_vehicle_agency ON vcms_vehicle_agency.id=vcms_vehicle_agent.agency_id
					WHERE agent_type = 'Jetty Sircar'"; // agency_code = '$org_license' AND

					$data_jsInfo = $this->bm->dataSelectDB1($sql_jsInfo);
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

				// Truck Info
				$data_truck = null;
				if($org_license != "")
				{
					$sql_truck = "SELECT * FROM do_truck_details_entry WHERE update_by = '$cfLoginId' AND DATE(last_update) = DATE(NOW())";
					$data_truck = $this->bm->dataSelectDB1($sql_truck);
				}
				
				$data['rslt_tmpTrkData'] = $data_truck;
				$data['data_jsInfo'] = $data_jsInfo;
				$data['data_assignment'] = $data_assignment;
				$data['flag'] = 1;
				$data['msg'] = $msg;
			}

			if($action == "Search")
			{
				$sql_cf = "SELECT u_name FROM users WHERE login_id = '$cfLoginId'";
				$data_cf = $this->bm->dataSelectDB1($sql_cf);
				$cf = "";
				for($i=0;count($data_cf)>$i;$i++){
					$cf = $data_cf[$i]['u_name'];
				}
				$data['cf_name'] = $cf;


				$data_assignment = null;
				if($org_license != "")
				{
					$lclAssignmentQuery = "SELECT igm_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no,
					igm_details.Pack_Description, igm_details.Pack_Number, igm_detail_container.cont_status,igm_detail_container.cont_size,
					oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'dtl' AS igm_type,
					IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT CONCAT(shed_yard,' - ',shed_loc) FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
					FROM oracle_nts_data
					INNER JOIN igm_details ON igm_details.id=oracle_nts_data.igm_detail_id
					INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
					WHERE igm_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
					AND REPLACE(LTRIM(REPLACE(oracle_nts_data.cnf_lno,'0',' ')),' ','0') LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id) > 0	
					
					UNION 
					
					SELECT igm_sup_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no, igm_supplimentary_detail.Pack_Description, igm_supplimentary_detail.Pack_Number,igm_sup_detail_container.cont_status,igm_sup_detail_container.cont_size,
					oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'sup_dtl' AS igm_type,
					IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT CONCAT(shed_yard,' - ',shed_loc) FROM shed_tally_info WHERE cont_number=igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
					FROM oracle_nts_data
					INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=oracle_nts_data.igm_sup_detail_id
					INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
					WHERE igm_sup_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
					AND REPLACE(LTRIM(REPLACE(oracle_nts_data.cnf_lno,'0',' ')),' ','0') LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number= igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id) > 0";

					$data_assignment = $this->bm->dataSelectDB1($lclAssignmentQuery);
					// if(empty($data_assignment))
					// 	{
					// 		if(substr($cnfLic[0], 0, 1)=='0' )
					// 		{
					// 			$cnfLic_firstpart = substr($cnfLic[0], 1);
					// 		}
					// 		else if (substr($cnfLic[0], 0, 2)=='00' )
					// 		{
					// 			$cnfLic_firstpart = substr($cnfLic[0], 2);
					// 		}
							
					// 		/* 
					// 		if(strlen($cnfLic[0])==1)
					// 			$cnfLic_firstpart = "000".$cnfLic[0];
					// 		else if(strlen($cnfLic[0])==2)
					// 			$cnfLic_firstpart = "00".$cnfLic[0];
					// 		else if(strlen($cnfLic[0])==3)
					// 			$cnfLic_firstpart = "0".$cnfLic[0];
					// 		else if(strlen($cnfLic[0])==4)
					// 			$cnfLic_firstpart = "".$cnfLic[0]; */
							
							
					// 	$lclAssignmentQuery = "SELECT igm_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no,
					// 	igm_details.Pack_Description, igm_details.Pack_Number, igm_detail_container.cont_status,igm_detail_container.cont_size,
					// 	oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'dtl' AS igm_type,
					// 	IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT shed_loc FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
					// 	FROM oracle_nts_data
					// 	INNER JOIN igm_details ON igm_details.id=oracle_nts_data.igm_detail_id
					// 	INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
					// 	WHERE igm_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
					// 	AND oracle_nts_data.cnf_lno LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id) > 0	
						
					// 	UNION 
						
					// 	SELECT igm_sup_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no, igm_supplimentary_detail.Pack_Description, igm_supplimentary_detail.Pack_Number,igm_sup_detail_container.cont_status,igm_sup_detail_container.cont_size,
					// 	oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'sup_dtl' AS igm_type,
					// 	IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT shed_loc FROM shed_tally_info WHERE cont_number=igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
					// 	FROM oracle_nts_data
					// 	INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=oracle_nts_data.igm_sup_detail_id
					// 	INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
					// 	WHERE igm_sup_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
					// 	AND oracle_nts_data.cnf_lno LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number= igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id) > 0";

					// 	$data_assignment = $this->bm->dataSelectDB1($lclAssignmentQuery);
					// }					
				}


				$data_jsInfo = null;
				if($org_license != "")
				{
					$sql_jsInfo = "SELECT vcms_vehicle_agent.id,agent_name AS js_name,agent_type,card_number
					FROM vcms_vehicle_agent
					INNER JOIN vcms_vehicle_agency ON vcms_vehicle_agency.id=vcms_vehicle_agent.agency_id
					WHERE agent_type = 'Jetty Sircar'"; // agency_code = '$org_license' AND

					$data_jsInfo = $this->bm->dataSelectDB1($sql_jsInfo);
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

				// Truck Info
				$data_truck = null;
				if($org_license != ""){
					$sql_truck = "SELECT * FROM do_truck_details_entry WHERE update_by = '$cfLoginId' AND DATE(last_update) = DATE(NOW())";
					$data_truck = $this->bm->dataSelectDB1($sql_truck);
				}
				
				$data['rslt_tmpTrkData'] = $data_truck;
				$data['data_jsInfo'] = $data_jsInfo;
				$data['data_assignment'] = $data_assignment;
				$data['flag'] = 1;
			}
			else if($action == "Add" || $action == "Emergency")
			{
				$assignment = $this->input->post('assignment');
				$data = explode("|",$assignment);
				$contNo = "";
				$rotNo = "";
				$cont_status = "";
				$blNo = "";
				$igm_type = ""; // sup = sup_dtl  & dtl = dtl
				$igm_id = "";
				$verify_no = "";
				$cp_no = "";
				$cont_size = "";

				$cntArray = count($data);

				if($cntArray>1)
				{
					$contNo = $data[0];
					$rotNo = $data[1];
					$cont_status = $data[2];
					$blNo = $data[3];
					$igm_type = $data[4]; // sup = sup_dtl  & dtl = dtl
					$igm_id = $data[5];
					$verify_no = $data[6];
					$cp_no = $data[7];
					$cont_size = $data[8];

					$blNo = $this->bm->getBlByRotCont($rotNo,$contNo);
					$result = $this->bm->chkBlockedContainerforTruckEntry($contNo,$rotNo,$blNo);
					$custom_block_status = "";
					for($ij = 0; $ij<count($result);$ij++){
						$custom_block_status = $result[$ij]['custom_block_st'];
					}
					
					if($custom_block_status == "DO_NOT_RELEASE"){
						$this->lclTruckSearchforSecurity($cnfAinNo);
						return;
					}
				}

				//$gate = $this->input->post('gate');
				$gate = $this->session->userdata('section');
				// $data['gateData'] = $gate;
				$this->session->set_userdata('gateData',$gate);
				
				// return;
				//$unit_gkey = $data[3];
				//$assignmentType = $data[5];

				// $jsGatePass = $this->input->post('jsGatePass');
				// $jsId = "";
				// $jsName = "";
				// $sql_jsid = "SELECT id,agent_name FROM vcms_vehicle_agent WHERE card_number = '$jsGatePass'";
				// $data_jsid = $this->bm->dataSelectDB1($sql_jsid);

				// for($i=0;$i<count($data_jsid);$i++)
				// {
				// 	$jsId = $data_jsid[$i]['id'];
				// 	$jsName =$data_jsid[$i]['agent_name'];
				// }

				// if($cont_status == "LCL")
				// {
					$login_id = $cfLoginId;
					$sql_chkExist = "SELECT COUNT(*) AS rtnValue 
					FROM lcl_dlv_assignment 
					WHERE rot_no='$rotNo' AND bl_no='$blNo'";
					$rslt_chkExist = $this->bm->dataSelectDb1($sql_chkExist);
					$cnt = "";
					for($i=0;count($rslt_chkExist)>$i;$i++)
					{
						$cnt = $rslt_chkExist[$i]['rtnValue'];
					}
					
					// $sql_igmDtlContId = "SELECT igm_details.id AS igm_dtl_id,igm_detail_container.id AS igm_dtl_cont_id,cont_size
					// FROM igm_details
					// INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
					// WHERE Import_Rotation_No='$rotNo' AND cont_number='$contNo'";

					// $rslt_igmDtlContId = $this->bm->dataSelectDb1($sql_igmDtlContId);
					// $igmDtlId = "";
					// $igmDtlContId = "";
					// $cont_size = "";
					// for($i=0;count($rslt_igmDtlContId)>$i;$i++)
					// {
					// 	$igmDtlId = $rslt_igmDtlContId[$i]['igm_dtl_id'];				
					// 	$igmDtlContId = $rslt_igmDtlContId[$i]['igm_dtl_cont_id'];
					// 	$cont_size = $rslt_igmDtlContId[$i]['cont_size'];
					// }
				
					if($cont_size == 20)
						$truck_qty = 2;
					// else if($cont_size == 40)
					else
						$truck_qty = 3;
					
					// $sql_smsNo = "SELECT cf_sms_number 
					// FROM ctmsmis.tmp_oracle_assignment
					// WHERE cf_lic='$org_license' AND cf_sms_number IS NOT NULL";
					// $rslt_smsNo = $this->bm->dataSelect($sql_smsNo);
					// $smsNo = "";
					// for($i=0;count($rslt_smsNo)>$i;$i++)
					// {
					// 	$smsNo = $rslt_smsNo[$i]['cf_sms_number'];
					// }

					//checking part BL

					$partBLQuery = "SELECT COUNT(*) AS rtnValue FROM igm_sup_detail_container 
					INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=igm_sup_detail_container.igm_sup_detail_id
					WHERE cont_number='$contNo' AND Import_Rotation_No='$rotNo' AND cont_status='FCL/PART'";
					$rslt_partBL = $this->bm->dataSelectDb1($partBLQuery);
					$partbl = 0;
					for($i=0;$i<count($rslt_partBL);$i++){
						$partbl = $rslt_partBL[$i]['rtnValue'];
					}

					if($partbl == 0){
						$partBLQuery = "SELECT COUNT(*) AS rtnValue
						FROM igm_detail_container
						INNER JOIN igm_details ON igm_detail_container.igm_detail_id=igm_details.id
						WHERE igm_details.Import_Rotation_No='$rotNo' AND igm_detail_container.cont_number='$contNo' AND cont_status='FCL/PART'";
						$rslt_partBL = $this->bm->dataSelectDb1($partBLQuery);
						$partbl = 0;
						for($i=0;$i<count($rslt_partBL);$i++){
							$partbl = $rslt_partBL[$i]['rtnValue'];
						}
					}

					$partblsts = 0;
					
					if($partbl>0){
						$partblsts = 1;
					}

					if($cntArray>1)
					{
						if($cnt==0)
						{			
							$lclDlv_query = "INSERT INTO lcl_dlv_assignment (igm_sup_dtl_id,rot_no,bl_no,cp_no,cnf_lic_no,no_of_truck,deliveryDt,igm_type,verify_num,entry_by,entry_at,entry_ip,is_part_bl) VALUES('$igm_id','$rotNo','$blNo','$cp_no','$org_license','$truck_qty',date(NOW()),'$igm_type','$verify_no','$login_id',NOW(),'$ipaddr','$partblsts')";
							
							if($this->bm->dataInsertDB1($lclDlv_query))
								$msg = "<font color='green'><b>Truck number entered succesfully.</b></font>";
						}
						else
						{
							$sql_updateQtyTruck = "UPDATE lcl_dlv_assignment
							SET no_of_truck='$truck_qty',is_part_bl='$partblsts',entry_by='$login_id'
							WHERE rot_no='$rotNo' AND bl_no='$blNo'";
							
							if($this->bm->dataUpdateDB1($sql_updateQtyTruck))
								$msg = "<font color='green'><b>Truck number updated succesfully.</b></font>";
						}
					}

					//Verify Other Data ID

					$sql_vrfyOtherDataId = "SELECT id FROM lcl_dlv_assignment WHERE rot_no='$rotNo' AND bl_no='$blNo'";
					$data_vrfyOtherDataId = $this->bm->dataSelectDB1($sql_vrfyOtherDataId);
					$vrfyOtherDataId = "";
					for($i=0;count($data_vrfyOtherDataId)>$i;$i++)
					{
						$vrfyOtherDataId = $data_vrfyOtherDataId[$i]['id'];
					}

					// Jetty Sircar Entry -- Starts

					// $sql_chkJS = "SELECT COUNT(*) AS rtnValue
					// FROM lcl_dlv_assignment
					// WHERE jetty_sirkar_id='$jsId' AND id='$vrfyOtherDataId'";

					// $rslt_chkJS = $this->bm->dataSelectDB1($sql_chkJS);
					// $chkJS = $rslt_chkJS[0]['rtnValue'];

					// for($i=0;count($rslt_chkJS)>$i;$i++)
					// {
					// 	$chkJS = $rslt_chkJS[$i]['rtnValue'];
					// }

					// if($chkJS == 0)
					// {
					// 	$prevJS = "";
					// 	$sql_prevJS = "SELECT jetty_sirkar_id
					// 	FROM lcl_dlv_assignment
					// 	WHERE id='$vrfyOtherDataId'";
					// 	$rslt_prevJS = $this->bm->dataSelectDB1($sql_prevJS);
					// 	$prevJS = $rslt_prevJS[0]['jetty_sirkar_id'];
						
					// 	$sql_updateJS = "UPDATE lcl_dlv_assignment
					// 	SET jetty_sirkar_id='$jsId'
					// 	WHERE id='$vrfyOtherDataId'";
					// 	// return;
					// 	$this->bm->dataUpdateDB1($sql_updateJS);
					// }

					// Jetty Sircar Entry -- Ends

					// Add Truck - starts
		
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

					$totTruck = $truck_qty;
					
					$frmSlot = $this->input->post('truckSlot');			// added on 2021-03-01		
					
					$emrgncy_flag = 0;
					$emrgncy_approve_stat = 0;

					if($action == "Emergency")
					{
						$emrgncy_flag = 1;	
					}

					// $strUpdateSlot = "UPDATE ctmsmis.tmp_oracle_assignment SET assignment_slot='$frmSlot' WHERE cont_no='$contNo' AND rot_no='$rotNo'";
					// $this->bm->dataUpdate($strUpdateSlot);
											
					// $sql_timeSlot = "SELECT assignment_slot,assignmentDate,DATE_ADD(assignmentDate, INTERVAL 1 DAY) AS nxtDt
					// FROM ctmsmis.tmp_oracle_assignment
					// WHERE rot_no='$rotNo' AND cont_no='$contNo' AND assignmentDate>=DATE(NOW())";
					// $rslt_timeSlot = $this->bm->dataSelect($sql_timeSlot);

					$sql_timeSlot = "SELECT deliveryDt,DATE_ADD(deliveryDt, INTERVAL 1 DAY) AS nxtDt FROM lcl_dlv_assignment WHERE id = '$vrfyOtherDataId' ORDER BY id DESC LIMIT 1";

					$rslt_timeSlot = $this->bm->dataSelectDB1($sql_timeSlot);

					$asDt = "";
					$asSlot = "";	// commented on 2021-03-01
					$nxtDt = "";
					
					for($j=0;$j<count($rslt_timeSlot);$j++)
					{
						$asDt = $rslt_timeSlot[$j]['deliveryDt'];
						$nxtDt = $rslt_timeSlot[$j]['nxtDt'];
					}

					$asSlot = $frmSlot;

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

					$sql_chkTruck = "SELECT COUNT(*) AS rtnValue
					FROM do_truck_details_entry 
					WHERE truck_id='$truckId' AND visit_time_slot_start='$sSlot' AND visit_time_slot_end='$eSlot'";
					$rslt_chkTruck = $this->bm->dataSelectDB1($sql_chkTruck);
					$chkTruck = "";
					for($i=0;count($rslt_chkTruck)>$i;$i++){
						$chkTruck = $rslt_chkTruck[$i]['rtnValue'];
					}
					
					if($chkTruck==0)
					{
						$strInsertEq = "INSERT INTO do_truck_details_entry(verify_other_data_id,import_rotation,cont_no,truck_id,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,truck_agency_name,truck_agency_phone,gate_no,update_by,ip_addr,last_update,emrgncy_flag,emrgncy_approve_stat,visit_time_slot_start,visit_time_slot_end,entry_from,paid_amt,paid_status,paid_method,paid_collect_dt,paid_collect_by,pay_collect_ip)
						VALUES('$vrfyOtherDataId','$rotNo','$contNo','$truckId','$driverName','$driverPassNo','$assistantName','$assistantPassNo','$agencyName','$agencyPhone','$gate','$login_id','$ipaddr',NOW(),'$emrgncy_flag','$emrgncy_approve_stat','$sSlot','$eSlot','security','$payAmt',1,'cash',NOW(),'$user','$ipaddr')";
						$stat = $this->bm->dataInsertDB1($strInsertEq);
						// $stat = 1;
						if($stat == 1)
							$msg = "<font color='green'><b>Truck added successfully</b></font>";

						$visitId_query = "SELECT id FROM do_truck_details_entry WHERE truck_id = '$truckId' AND driver_gate_pass='$driverPassNo' AND update_by = '$login_id' AND paid_collect_by = '$user'";

						$visitId_rslt = $this->bm->dataSelectDB1($visitId_query);
						$visit_id = "";

						for($vi=0;$vi<count($visitId_rslt);$vi++)
						{
							$visit_id = $visitId_rslt[$vi]['id'];
						}

						$trkPart = explode(" ",$truckId);
						$trck = $trkPart[0]." ".$trkPart[3]." ".$trkPart[4];   
						//$trck = urlencode($trck);
		
						if($stat == 1)
						{
							if($visit_id != 0)
							{
								$eventType = "ISSUE";
								$biometricInsertQuery = "INSERT INTO biometricEventLog(visit_id,event_type,ain_no,driver_pass,helper_pass,truck_id,entry_at,entry_by,entry_ip) VALUES('$visit_id','$eventType','$ain','$driverPassNo','$assistantPassNo','$trck',NOW(),'$user','$ipaddr')";
								$this->bm->dataInsertDB1($biometricInsertQuery);
							}
						}
						
					}
					else
					{
						$msg = "<font color='red'><b>This truck was assigned for this time slot previously</b></font>";
					}

					if($cntArray>1)
					{
						$sql_updateImporterMbl = "UPDATE lcl_dlv_assignment
						SET importer_mobile_no='$importerMobileNo', truck_slot = '$asSlot'
						WHERE id='$vrfyOtherDataId'";
						$this->bm->dataUpdateDB1($sql_updateImporterMbl);
					}

					// Add Truck - Ends

					// search Data  - Starts

					$sql_cf = "SELECT u_name FROM users WHERE login_id = '$cfLoginId'";
					$data_cf = $this->bm->dataSelectDB1($sql_cf);
					$cf = "";
					for($i=0;count($data_cf)>$i;$i++){
						$cf = $data_cf[$i]['u_name'];
					}
					$data['cf_name'] = $cf;

					$data_assignment = null;
					if($org_license != "")
					{
						$lclAssignmentQuery = "SELECT igm_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no,
						igm_details.Pack_Description, igm_details.Pack_Number, igm_detail_container.cont_status,igm_detail_container.cont_size,
						oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'dtl' AS igm_type,
						IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT CONCAT(shed_yard,' - ',shed_loc) FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
						FROM oracle_nts_data
						INNER JOIN igm_details ON igm_details.id=oracle_nts_data.igm_detail_id
						INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
						WHERE igm_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
						AND REPLACE(LTRIM(REPLACE(oracle_nts_data.cnf_lno,'0',' ')),' ','0') LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id) > 0	
						
						UNION 
						
						SELECT igm_sup_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no, igm_supplimentary_detail.Pack_Description, igm_supplimentary_detail.Pack_Number,igm_sup_detail_container.cont_status,igm_sup_detail_container.cont_size,
						oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'sup_dtl' AS igm_type,
						IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT CONCAT(shed_yard,' - ',shed_loc) FROM shed_tally_info WHERE cont_number=igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
						FROM oracle_nts_data
						INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=oracle_nts_data.igm_sup_detail_id
						INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
						WHERE igm_sup_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
						AND REPLACE(LTRIM(REPLACE(oracle_nts_data.cnf_lno,'0',' ')),' ','0') LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number= igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id) > 0";

						$data_assignment = $this->bm->dataSelectDB1($lclAssignmentQuery);
						// if(empty($data_assignment))
						// {
						// 	if(substr($cnfLic[0], 0, 1)=='0' )
						// 	{
						// 		$cnfLic_firstpart = substr($cnfLic[0], 1);
						// 	}
						// 	else if (substr($cnfLic[0], 0, 2)=='00' )
						// 	{
						// 		$cnfLic_firstpart = substr($cnfLic[0], 2);
						// 	}
							
						// 	/* if(strlen($cnfLic[0])==1)
						// 		$cnfLic_firstpart = "000".$cnfLic[0];
						// 	else if(strlen($cnfLic[0])==2)
						// 		$cnfLic_firstpart = "00".$cnfLic[0];
						// 	else if(strlen($cnfLic[0])==3)
						// 		$cnfLic_firstpart = "0".$cnfLic[0];
						// 	else if(strlen($cnfLic[0])==4)
						// 		$cnfLic_firstpart = "".$cnfLic[0]; */
								
								
						// 	$lclAssignmentQuery = "SELECT igm_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no,
						// 	igm_details.Pack_Description, igm_details.Pack_Number, igm_detail_container.cont_status,igm_detail_container.cont_size,
						// 	oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'dtl' AS igm_type,
						// 	IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT shed_loc FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
						// 	FROM oracle_nts_data
						// 	INNER JOIN igm_details ON igm_details.id=oracle_nts_data.igm_detail_id
						// 	INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
						// 	WHERE igm_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
						// 	AND oracle_nts_data.cnf_lno LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id) > 0	
							
						// 	UNION 
							
						// 	SELECT igm_sup_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no, igm_supplimentary_detail.Pack_Description, igm_supplimentary_detail.Pack_Number,igm_sup_detail_container.cont_status,igm_sup_detail_container.cont_size,
						// 	oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'sup_dtl' AS igm_type,
						// 	IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT shed_loc FROM shed_tally_info WHERE cont_number=igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
						// 	FROM oracle_nts_data
						// 	INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=oracle_nts_data.igm_sup_detail_id
						// 	INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
						// 	WHERE igm_sup_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
						// 	AND oracle_nts_data.cnf_lno LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number= igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id) > 0";

						// 	$data_assignment = $this->bm->dataSelectDB1($lclAssignmentQuery);
						// }						
					}

					$data_jsInfo = null;
					if($org_license != "")
					{
						$sql_jsInfo = "SELECT vcms_vehicle_agent.id,agent_name AS js_name,agent_type,card_number
						FROM vcms_vehicle_agent
						INNER JOIN vcms_vehicle_agency ON vcms_vehicle_agency.id=vcms_vehicle_agent.agency_id
						WHERE agent_type = 'Jetty Sircar'"; // agency_code = '$org_license' AND

						$data_jsInfo = $this->bm->dataSelectDB1($sql_jsInfo);
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

					// Truck Info
					$data_truck = null;
					if($org_license != ""){
						$sql_truck = "SELECT * FROM do_truck_details_entry WHERE update_by = '$cfLoginId' AND DATE(last_update) = DATE(NOW())";
						$data_truck = $this->bm->dataSelectDB1($sql_truck);
					}
					
					$data['rslt_tmpTrkData'] = $data_truck;
					$data['data_jsInfo'] = $data_jsInfo;
					$data['data_assignment'] = $data_assignment;
					$data['flag'] = 1;
					$data['ain'] = $ain;
					// $data['jsGatePass'] = $jsGatePass;
					$data['contNo'] = $contNo;

					// Load Data  -- Ends 

				// }

				$visitId_Query = "SELECT id FROM do_truck_details_entry WHERE update_by = '$cfLoginId' AND truck_id = '$truckId' AND paid_collect_by = '$user'";
				$visitId_Rslt = $this->bm->dataSelectDb1($visitId_Query);
				$trucVisitId = "";
				for($vi=0;count($visitId_Rslt)>$vi;$vi++){
					$trucVisitId = $visitId_Rslt[$vi]['id'];
				}

				$data['msg'] = $msg;
				$data['title']="Vehicle Gate Pass";
				$data['rot_no'] = $rotNo;
				$data['cont_no'] = $contNo;
				$data['trucVisitId'] = $trucVisitId;
				$data['login_id'] = $user;
				$data['flag'] = 'LCL';

				$this->load->view('cssAssetsList');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('vehiclegatePassforSecurity',$data);
				$this->load->view('jsAssetsList');
				return;

			}
			else if($action == "Update")
			{
				$editType = $this->input->post('editType');
				$assignment = $this->input->post('assignmentData');
				$data = explode("|",$assignment);
				$contNo = $data[0];
				$rotNo = $data[1];
				$cont_status = $data[2];
				$blNo = $data[3];
				$igm_type = $data[4]; // sup = sup_dtl  & dtl = dtl
				$igm_id = $data[5];
				$verify_no = $data[6];
				$cp_no = $data[7];
				$cont_size = $data[8];
				$gate = $this->input->post('gate');

				if(!is_null($rotNo) || !is_null($contNo))
				{
					$blNo = $this->bm->getBlByRotCont($rotNo,$contNo);
					$result = $this->bm->chkBlockedContainerforTruckEntry($contNo,$rotNo,$blNo);
					$custom_block_status = "";
					for($ij = 0; $ij<count($result);$ij++){
						$custom_block_status = $result[$ij]['custom_block_st'];
					}
					
					if($custom_block_status == "DO_NOT_RELEASE"){
						$this->lclTruckSearchforSecurity($cnfAinNo);
						return;
					}
				}

				// $jsGatePass = $this->input->post('jsGatePass');
				// $jsId = "";
				// $jsName = "";
				// $sql_jsid = "SELECT id,agent_name FROM vcms_vehicle_agent WHERE card_number = '$jsGatePass'";
				// $data_jsid = $this->bm->dataSelectDB1($sql_jsid);
				// for($i=0;$i<count($data_jsid);$i++)
				// {
				// 	$jsId = $data_jsid[$i]['id'];
				// 	$jsName =$data_jsid[$i]['agent_name'];
				// }

				if($cont_status == "LCL")
				{
					$login_id = $cfLoginId;
				
					if($cont_size == 20)
						$truck_qty = 2;
					// else if($cont_size == 40)
					else
						$truck_qty = 3;
					
					// $sql_smsNo = "SELECT cf_sms_number 
					// FROM ctmsmis.tmp_oracle_assignment
					// WHERE cf_lic='$org_license' AND cf_sms_number IS NOT NULL";
					// $rslt_smsNo = $this->bm->dataSelect($sql_smsNo);
					// $smsNo = "";
					// for($i=0;count($rslt_smsNo)>$i;$i++)
					// {
					// 	$smsNo = $rslt_smsNo[$i]['cf_sms_number'];
					// }

					$sql_updateQtyTruck = "UPDATE lcl_dlv_assignment
					SET no_of_truck='$truck_qty',entry_by='$login_id'
					WHERE rot_no='$rotNo' AND bl_no='$blNo'";
					
					if($this->bm->dataUpdateDB1($sql_updateQtyTruck))
						$msg = "<font color='green'><b>Truck number updated succesfully.</b></font>";

					//Verify Info FCL ID
					$sql_vrfyOtherDataId = "SELECT id FROM lcl_dlv_assignment WHERE rot_no='$rotNo' AND bl_no='$blNo'";
					$data_vrfyOtherDataId = $this->bm->dataSelectDB1($sql_vrfyOtherDataId);
					$vrfyOtherDataId = "";
					for($i=0;count($data_vrfyOtherDataId)>$i;$i++)
					{
						$vrfyOtherDataId = $data_vrfyOtherDataId[$i]['id'];
					}

					// Jetty Sircar Entry -- Starts 

					// $sql_chkJS = "SELECT COUNT(*) AS rtnValue
					// FROM lcl_dlv_assignment
					// WHERE jetty_sirkar_id='$jsId' AND id='$vrfyOtherDataId'";

					// $rslt_chkJS = $this->bm->dataSelectDB1($sql_chkJS);
					// $chkJS = $rslt_chkJS[0]['rtnValue'];

					// for($i=0;count($rslt_chkJS)>$i;$i++)
					// {
					// 	$chkJS = $rslt_chkJS[$i]['rtnValue'];
					// }

					// if($chkJS == 0)
					// {
					// 	$prevJS = "";

					// 	$sql_prevJS = "SELECT jetty_sirkar_id
					// 	FROM lcl_dlv_assignment
					// 	WHERE id='$vrfyOtherDataId'";
					// 	$rslt_prevJS = $this->bm->dataSelectDB1($sql_prevJS);
					// 	$prevJS = $rslt_prevJS[0]['jetty_sirkar_id'];
						
					// 	$sql_updateJS = "UPDATE lcl_dlv_assignment
					// 	SET jetty_sirkar_id='$jsId'
					// 	WHERE id='$vrfyOtherDataId'";
					// 	// return;
					// 	$this->bm->dataUpdateDB1($sql_updateJS);
					// }

					// Jetty Sircar Entry -- Ends

					// Edit Truck - starts 
					
					$truckVisitId = $this->input->post('truckVisitId');

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

					$totTruck = $truck_qty;

					// Added on 02 Aug 2021

					$frmSlot = $this->input->post('truckSlot');		
					
					$emrgncy_flag = 0;
					$emrgncy_approve_stat = 0;

					if($action == "Emergency")
					{
						$emrgncy_flag = 1;	
					}

					// $strUpdateSlot = "UPDATE ctmsmis.tmp_oracle_assignment SET assignment_slot='$frmSlot' WHERE cont_no='$contNo' AND rot_no='$rotNo'";
					// $this->bm->dataUpdate($strUpdateSlot);
											
					// $sql_timeSlot = "SELECT assignment_slot,assignmentDate,DATE_ADD(assignmentDate, INTERVAL 1 DAY) AS nxtDt
					// FROM ctmsmis.tmp_oracle_assignment
					// WHERE rot_no='$rotNo' AND cont_no='$contNo' AND assignmentDate>=DATE(NOW())";
					// $rslt_timeSlot = $this->bm->dataSelect($sql_timeSlot);

					$sql_timeSlot = "SELECT deliveryDt,DATE_ADD(deliveryDt, INTERVAL 1 DAY) AS nxtDt FROM lcl_dlv_assignment WHERE id = '$vrfyOtherDataId' ORDER BY id DESC LIMIT 1";

					$rslt_timeSlot = $this->bm->dataSelectDB1($sql_timeSlot);

					$asDt = "";
					$asSlot = "";	// commented on 2021-03-01
					$nxtDt = "";
					
					for($j=0;$j<count($rslt_timeSlot);$j++)
					{
						$asDt = $rslt_timeSlot[$j]['deliveryDt'];
						$nxtDt = $rslt_timeSlot[$j]['nxtDt'];
					}

					$asSlot = $frmSlot;

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

					// Added on 02 Aug 2021

					$payAmt = 57.5;

					if($editType == "Replace")
					{				
						$sql_replaceInfo = "SELECT id,truck_id,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,paid_amt,paid_method,paid_collect_dt,paid_collect_by,pay_collect_ip
						FROM do_truck_details_entry
						WHERE id='$truckVisitId'";
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
						$repPaidCollectBy = $rslt_replaceInfo[0]['paid_collect_by'];
						$repPaidCollectIp = $rslt_replaceInfo[0]['pay_collect_ip'];
						
						$sql_insertReplace = "INSERT INTO vcms_replace_truck_log(visit_id,truck_id,driver_name,driver_gate_pass,assistant_name,assistant_gate_pass,paid_amt,paid_method,paid_collect_dt,replace_time,replace_by,import_rotation,cont_no,paid_collect_by,pay_collect_ip)
						VALUES('$repVisitId','$repTruckId','$repDriverName','$repDriverGatePass','$repAssistantName','$repAssistantGatePass','$repPaidAmt','$repPaidMethod','$repPaidCollectDt',NOW(),'$login_id','$rotNo','$contNo','$repPaidCollectBy','$repPaidCollectIp')";
						$this->bm->dataInsertDB1($sql_insertReplace);
						
						$sql_updateTruckInfo = "UPDATE do_truck_details_entry
						SET truck_id='$truckId',driver_name='$driverName',driver_gate_pass='$driverPassNo',assistant_name='$assistantName',assistant_gate_pass='$assistantPassNo',truck_agency_name='$agencyName',truck_agency_phone='$agencyPhone',paid_amt='57.5',paid_status=1,paid_method='cash',gate_no = '$gate',paid_collect_dt=NOW(),paid_collect_by='$user',pay_collect_ip='$ipaddr',gate_in_status='0',gate_in_by=NULL,gate_in_time=NULL,last_update=NOW()
						WHERE id='$truckVisitId'";
						$this->bm->dataUpdateDB1($sql_updateTruckInfo);		
					}
					else if($editType == "Edit")			// check with it later
					{
						$sql_updateTruckInfo = "UPDATE do_truck_details_entry
						SET truck_id='$truckId',driver_name='$driverName',driver_gate_pass='$driverPassNo',assistant_name='$assistantName',assistant_gate_pass='$assistantPassNo',truck_agency_name='$agencyName',truck_agency_phone='$agencyPhone',gate_no = '$gate',edit_at=NOW(),edit_by='$login_id',edit_ip='$ipaddr'
						WHERE id='$truckVisitId'";
						$this->bm->dataUpdateDB1($sql_updateTruckInfo);	
					}

					$sql_updateImporterMbl = "UPDATE lcl_dlv_assignment
					SET importer_mobile_no='$importerMobileNo', truck_slot = '$asSlot'
					WHERE id='$vrfyOtherDataId'";
					$this->bm->dataUpdateDB1($sql_updateImporterMbl);
						

					// Edit Truck - Ends

					// Search Data  - Starts

					$sql_cf = "SELECT u_name FROM users WHERE login_id = '$cfLoginId'";
					$data_cf = $this->bm->dataSelectDB1($sql_cf);
					$cf = "";
					for($i=0;count($data_cf)>$i;$i++){
						$cf = $data_cf[$i]['u_name'];
					}
					
					$data['cf_name'] = $cf;

					$data_assignment = null;
					if($org_license != "")
					{
						$lclAssignmentQuery = "SELECT igm_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no,
						igm_details.Pack_Description, igm_details.Pack_Number, igm_detail_container.cont_status,igm_detail_container.cont_size,
						oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'dtl' AS igm_type,
						IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT CONCAT(shed_yard,' - ',shed_loc) FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
						FROM oracle_nts_data
						INNER JOIN igm_details ON igm_details.id=oracle_nts_data.igm_detail_id
						INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
						WHERE igm_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
						AND REPLACE(LTRIM(REPLACE(oracle_nts_data.cnf_lno,'0',' ')),' ','0') LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id) > 0	
						
						UNION 
						
						SELECT igm_sup_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no, igm_supplimentary_detail.Pack_Description, igm_supplimentary_detail.Pack_Number,igm_sup_detail_container.cont_status,igm_sup_detail_container.cont_size,
						oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'sup_dtl' AS igm_type,
						IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT CONCAT(shed_yard,' - ',shed_loc) FROM shed_tally_info WHERE cont_number=igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
						FROM oracle_nts_data
						INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=oracle_nts_data.igm_sup_detail_id
						INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
						WHERE igm_sup_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
						AND REPLACE(LTRIM(REPLACE(oracle_nts_data.cnf_lno,'0',' ')),' ','0') LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number= igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id) > 0";

						$data_assignment = $this->bm->dataSelectDB1($lclAssignmentQuery);
						// if(empty($data_assignment))
						// 	{
						// 		if(substr($cnfLic[0], 0, 1)=='0' )
						// 		{
						// 			$cnfLic_firstpart = substr($cnfLic[0], 1);
						// 		}
						// 		else if (substr($cnfLic[0], 0, 2)=='00' )
						// 		{
						// 			$cnfLic_firstpart = substr($cnfLic[0], 2);
						// 		}
						// 		/* if(strlen($cnfLic[0])==1)
						// 			$cnfLic_firstpart = "000".$cnfLic[0];
						// 		else if(strlen($cnfLic[0])==2)
						// 			$cnfLic_firstpart = "00".$cnfLic[0];
						// 		else if(strlen($cnfLic[0])==3)
						// 			$cnfLic_firstpart = "0".$cnfLic[0];
						// 		else if(strlen($cnfLic[0])==4)
						// 			$cnfLic_firstpart = "".$cnfLic[0]; */
								
								
						// 	$lclAssignmentQuery = "SELECT igm_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no,
						// 	igm_details.Pack_Description, igm_details.Pack_Number, igm_detail_container.cont_status,igm_detail_container.cont_size,
						// 	oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'dtl' AS igm_type,
						// 	IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT shed_loc FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
						// 	FROM oracle_nts_data
						// 	INNER JOIN igm_details ON igm_details.id=oracle_nts_data.igm_detail_id
						// 	INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
						// 	WHERE igm_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
						// 	AND oracle_nts_data.cnf_lno LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id) > 0	
							
						// 	UNION 
							
						// 	SELECT igm_sup_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no, igm_supplimentary_detail.Pack_Description, igm_supplimentary_detail.Pack_Number,igm_sup_detail_container.cont_status,igm_sup_detail_container.cont_size,
						// 	oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'sup_dtl' AS igm_type,
						// 	IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT shed_loc FROM shed_tally_info WHERE cont_number=igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
						// 	FROM oracle_nts_data
						// 	INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=oracle_nts_data.igm_sup_detail_id
						// 	INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
						// 	WHERE igm_sup_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
						// 	AND oracle_nts_data.cnf_lno LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number= igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id) > 0";

						// 	$data_assignment = $this->bm->dataSelectDB1($lclAssignmentQuery);
						// }						
					}

					$data_jsInfo = null;
					if($org_license != "")
					{
						$sql_jsInfo = "SELECT vcms_vehicle_agent.id,agent_name AS js_name,agent_type,card_number
						FROM vcms_vehicle_agent
						INNER JOIN vcms_vehicle_agency ON vcms_vehicle_agency.id=vcms_vehicle_agent.agency_id
						WHERE agent_type = 'Jetty Sircar'"; // agency_code = '$org_license' AND

						$data_jsInfo = $this->bm->dataSelectDB1($sql_jsInfo);
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

					// Truck Info
					$data_truck = null;
					if($org_license != "")
					{
						$sql_truck = "SELECT * FROM do_truck_details_entry WHERE update_by = '$cfLoginId' AND DATE(last_update) = DATE(NOW())";
						$data_truck = $this->bm->dataSelectDB1($sql_truck);
					}
					
					$data['rslt_tmpTrkData'] = $data_truck;
					$data['data_jsInfo'] = $data_jsInfo;
					$data['data_assignment'] = $data_assignment;
					$data['flag'] = 1;
					$data['ain'] = $ain;
					// $data['jsGatePass'] = $jsGatePass;

					// Load Data  -- Ends 

				}
			}

			// Edit Truck 
				
			if($this->input->post('editBtn'))
			{
				$editId = $this->input->post('editId');
				$btnType = $this->input->post('btnType');
				$data['editType'] = $btnType;
				
				$editType = $this->input->post('editBtn');
				$data['editType']=$editType;	

				$sql_trkEditInfo = "SELECT id,verify_other_data_id,truck_id,driver_name,driver_gate_pass,truck_agency_name,cont_no,truck_agency_phone,gate_no,
				(SELECT DISTINCT mobile_number FROM vcms_vehicle_agent WHERE card_number=driver_gate_pass) AS driver_mobile_number,
				assistant_name,assistant_gate_pass,
				(SELECT DISTINCT mobile_number FROM vcms_vehicle_agent WHERE card_number=assistant_gate_pass) AS helper_mobile_number
				FROM do_truck_details_entry
				WHERE id='$editId'";

				$rslt_trkEditInfo = $this->bm->dataSelectDB1($sql_trkEditInfo);
				$data['rslt_trkEditInfo']=$rslt_trkEditInfo;

				$cont = "";
				$vrfyOtherDataId = "";
				for($i=0;$i<count($rslt_trkEditInfo);$i++)
				{
					$vrfyOtherDataId = $rslt_trkEditInfo[$i]['verify_other_data_id'];
					$cont = $rslt_trkEditInfo[$i]['cont_no'];
				}
				$data['cont'] = $cont;

				// Assignment Data

				$sql_assignmentData = "SELECT igm_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no,
				igm_details.Pack_Description, igm_details.Pack_Number, igm_detail_container.cont_status,igm_detail_container.cont_size,
				oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'dtl' AS igm_type,
				IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT CONCAT(shed_yard,' - ',shed_loc) FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
				FROM oracle_nts_data
				INNER JOIN igm_details ON igm_details.id=oracle_nts_data.igm_detail_id
				INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
				WHERE igm_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
				AND oracle_nts_data.cnf_lno LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number='$cont' AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id) > 0	
				
				UNION 
				
				SELECT igm_sup_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no, igm_supplimentary_detail.Pack_Description, igm_supplimentary_detail.Pack_Number,igm_sup_detail_container.cont_status,igm_sup_detail_container.cont_size,
				oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'sup_dtl' AS igm_type,
				IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT CONCAT(shed_yard,' - ',shed_loc) FROM shed_tally_info WHERE cont_number=igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
				FROM oracle_nts_data
				INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=oracle_nts_data.igm_sup_detail_id
				INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
				WHERE igm_sup_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
				AND oracle_nts_data.cnf_lno LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number= '$cont' AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id) > 0";
				$rslt_assignmentData = $this->bm->dataSelectDB1($sql_assignmentData);
				$assignmentData = "";
				for($i=0;$i<count($rslt_assignmentData);$i++)
				{
					$igm_type = $rslt_assignmentData[$i]['igm_type'];

					$igm_id = "";
					if($igm_type == "sup_dtl"){
						$igm_id = $rslt_assignmentData[$i]['igm_sup_detail_id'];
					}else if($igm_type == "dtl"){
						$igm_id = $rslt_assignmentData[$i]['igm_detail_id'];
					}

					if($igm_type == "sup_dtl"){
						$igm_type = "sup";
					}

					$assignmentData = $rslt_assignmentData[$i]['cont_number']."|".$rslt_assignmentData[$i]['imp_rot_no']."|".$rslt_assignmentData[$i]['cont_status']."|".$rslt_assignmentData[$i]['bl_no']."|".$igm_type."|".$igm_id."|".$rslt_assignmentData[$i]['verify_no']."|".$rslt_assignmentData[$i]['cp_no']."|".$rslt_assignmentData[$i]['cont_size'];
				}
				$data['assignmentData'] = $assignmentData;

				// Importer's Mobile

				$sql_importerMobile = "SELECT importer_mobile_no,jetty_sirkar_id,truck_slot FROM lcl_dlv_assignment WHERE id='$vrfyOtherDataId'";
				$rslt_importerMobile = $this->bm->dataSelectDB1($sql_importerMobile);

				$jetty_sirkar_id = "";
				$importerMobile = "";
				$truckSlot = "";
				for($i=0;$i<count($rslt_importerMobile);$i++)
				{
					$importerMobile = $rslt_importerMobile[$i]['importer_mobile_no'];
					$jetty_sirkar_id = $rslt_importerMobile[$i]['jetty_sirkar_id'];
					$truckSlot = $rslt_importerMobile[$i]['truck_slot'];
				}

				$data['truckSlot']=$truckSlot;
				$data['importerMobile']=$importerMobile;
				
				// Jetty Sirkar Card Number

				$sql_jetty_Sirkar = "SELECT card_number FROM vcms_vehicle_agent WHERE id = '$jetty_sirkar_id'";
				$rslt_jetty_Sirkar = $this->bm->dataSelectDB1($sql_jetty_Sirkar);

				$cardNumber = "";
				for($i=0;$i<count($rslt_jetty_Sirkar);$i++)
				{
					$cardNumber = $rslt_jetty_Sirkar[$i]['card_number'];
				}

				$data['cardNumber']=$cardNumber;

				$sql_jsInfo = "SELECT agent_name, agent_code FROM vcms_vehicle_agent WHERE card_number = '$cardNumber'";
				$rslt_jsInfo = $this->bm->dataSelectDB1($sql_jsInfo);

				$agent_name = "";
				$agent_code = "";
				for($i=0;$i<count($rslt_jsInfo);$i++)
				{
					$agent_name = $rslt_jsInfo[$i]['agent_name'];
					$agent_code = $rslt_jsInfo[$i]['agent_code'];
				}

				$data['agent_name'] = $agent_name;
				$data['agent_code'] = $agent_code;
				
				// Search Data

				$sql_cf = "SELECT u_name FROM users WHERE login_id = '$cfLoginId'";
				$data_cf = $this->bm->dataSelectDB1($sql_cf);
				$cf = "";
				for($i=0;count($data_cf)>$i;$i++){
					$cf = $data_cf[$i]['u_name'];
				}
				$data['cf_name'] = $cf;

				$data_assignment = null;
				if($org_license != "")
				{
					$sql_assignment = "SELECT igm_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no,
					igm_details.Pack_Description, igm_details.Pack_Number, igm_detail_container.cont_status,igm_detail_container.cont_size,
					oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'dtl' AS igm_type,
					IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT CONCAT(shed_yard,' - ',shed_loc) FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
					FROM oracle_nts_data
					INNER JOIN igm_details ON igm_details.id=oracle_nts_data.igm_detail_id
					INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
					WHERE igm_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
					AND REPLACE(LTRIM(REPLACE(oracle_nts_data.cnf_lno,'0',' ')),' ','0') LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id) > 0	
					
					UNION 
					
					SELECT igm_sup_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no, igm_supplimentary_detail.Pack_Description, igm_supplimentary_detail.Pack_Number,igm_sup_detail_container.cont_status,igm_sup_detail_container.cont_size,
					oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'sup_dtl' AS igm_type,
					IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT CONCAT(shed_yard,' - ',shed_loc) FROM shed_tally_info WHERE cont_number=igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
					FROM oracle_nts_data
					INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=oracle_nts_data.igm_sup_detail_id
					INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
					WHERE igm_sup_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
					AND REPLACE(LTRIM(REPLACE(oracle_nts_data.cnf_lno,'0',' ')),' ','0') LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number= igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id) > 0";

					$data_assignment = $this->bm->dataSelectDB1($sql_assignment);
				}

				$data_jsInfo = null;
				if($org_license != "")
				{
					$sql_jsInfo = "SELECT vcms_vehicle_agent.id,agent_name AS js_name,agent_type,card_number
					FROM vcms_vehicle_agent
					INNER JOIN vcms_vehicle_agency ON vcms_vehicle_agency.id=vcms_vehicle_agent.agency_id
					WHERE agent_type = 'Jetty Sircar'"; // agency_code = '$org_license' AND

					$data_jsInfo = $this->bm->dataSelectDB1($sql_jsInfo);
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

				// Truck Info
				$data_truck = null;
				if($org_license != "")
				{
					$sql_truck = "SELECT * FROM do_truck_details_entry WHERE update_by = '$cfLoginId' AND DATE(last_update) = DATE(NOW())";
					$data_truck = $this->bm->dataSelectDB1($sql_truck);
				}

				$data['rslt_tmpTrkData'] = $data_truck;
				$data['data_jsInfo'] = $data_jsInfo;
				$data['data_assignment'] = $data_assignment;
				$data['flag'] = 1;
							
			}

			$data['msg'] = $msg;
			$data['title']="Truck Entry for LCL";
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('lclTruckEntryforSecurity',$data);
			$this->load->view('jsAssetsList');
		}
	}

	// LCL Truck Entry By Security  -- ends

	// LCL Truck Pay By Security - start

	function lcltruckPayBySecurity()
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
			$msg = "";
			$action = $this->input->post('searchByCnfId');
			$ain = $this->input->post('cnfAinNo');
			$cfLoginId = $ain."CF";
			$payAmt = $this->input->post('payAmt');
			$payMethod = $this->input->post('payMethod');
			$truckDtlId = $this->input->post('truckDtlId');
			$payment = $this->input->post('payment');

			if($payment == 'payment')
			{
				$sql_updatePayment = "UPDATE do_truck_details_entry
				SET paid_amt='$payAmt',paid_status='2',paid_method='$payMethod'
				WHERE id='$truckDtlId'";
				$stat = $this->bm->dataUpdateDB1($sql_updatePayment);

			}
			else if($payment == 'showData')
			{
				$data['payFlag'] = 1;
			}

			$sql_lic = "SELECT License_No FROM organization_profiles 
			INNER JOIN users ON users.org_id = organization_profiles.id
			WHERE login_id = '$cfLoginId'";

			$data_lic = $this->bm->dataSelectDB1($sql_lic);
			$org_license = "";
			for($i=0;$i<count($data_lic);$i++)
			{
				$org_license = $data_lic[$i]['License_No'];
			}

			$cnfLic = explode("/", $org_license);
			$cnfLic_firstpart = $cnfLic[0];

			$sql_cf = "SELECT u_name FROM users WHERE login_id = '$cfLoginId'";
			$data_cf = $this->bm->dataSelectDB1($sql_cf);
			$cf = "";
			for($i=0;count($data_cf)>$i;$i++){
				$cf = $data_cf[$i]['u_name'];
			}
			$data['cf_name'] = $cf;

			$data_assignment = null;
			if($org_license != "")
			{
				$lclAssignmentQuery = "SELECT igm_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no,
				igm_details.Pack_Description, igm_details.Pack_Number, igm_detail_container.cont_status,igm_detail_container.cont_size,
				oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'dtl' AS igm_type,
				IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT shed_loc FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
				FROM oracle_nts_data
				INNER JOIN igm_details ON igm_details.id=oracle_nts_data.igm_detail_id
				INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
				WHERE igm_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
				AND oracle_nts_data.cnf_lno LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number=igm_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_detail_id=igm_details.id) > 0	
				
				UNION 
				
				SELECT igm_sup_detail_container.cont_number, oracle_nts_data.igm_detail_id, oracle_nts_data.igm_sup_detail_id,verify_no,verify_date,imp_rot_no, igm_supplimentary_detail.Pack_Description, igm_supplimentary_detail.Pack_Number,igm_sup_detail_container.cont_status,igm_sup_detail_container.cont_size,
				oracle_nts_data.office_code,unit_number,be_no,be_dt,oracle_nts_data.bl_no,cp_no,DATE(cp_date) AS cp_date,'sup_dtl' AS igm_type,
				IFNULL(NULLIF(oracle_nts_data.igm_detail_id, 0), oracle_nts_data.igm_sup_detail_id) AS igm_id,(SELECT shed_loc FROM shed_tally_info WHERE cont_number=igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id ORDER BY shed_tally_info.id DESC LIMIT 1) AS shed_loc
				FROM oracle_nts_data
				INNER JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id=oracle_nts_data.igm_sup_detail_id
				INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id 
				WHERE igm_sup_detail_container.cont_status='LCL' AND DATE(cp_date) BETWEEN DATE_ADD(DATE(NOW()) , INTERVAL -15 DAY) AND  DATE(NOW()) 
				AND oracle_nts_data.cnf_lno LIKE '$cnfLic_firstpart%' AND (SELECT COUNT(*) FROM shed_tally_info WHERE cont_number= igm_sup_detail_container.cont_number AND import_rotation=imp_rot_no AND igm_sup_detail_id=igm_supplimentary_detail.id) > 0";

				$data_assignment = $this->bm->dataSelectDB1($lclAssignmentQuery);
			}

			$data_jsInfo = null;
			if($org_license != "")
			{
				$sql_jsInfo = "SELECT vcms_vehicle_agent.id,agent_name AS js_name,agent_type,card_number
				FROM vcms_vehicle_agent
				INNER JOIN vcms_vehicle_agency ON vcms_vehicle_agency.id=vcms_vehicle_agent.agency_id
				WHERE agent_type = 'Jetty Sircar'"; // agency_code = '$org_license' AND

				$data_jsInfo = $this->bm->dataSelectDB1($sql_jsInfo);
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

			// Truck Info
			$data_truck = null;
			if($org_license != "")
			{
				$sql_truck = "SELECT * FROM do_truck_details_entry WHERE update_by = '$cfLoginId' AND DATE(last_update) = DATE(NOW())";
				$data_truck = $this->bm->dataSelectDB1($sql_truck);
			}
			
			$data['rslt_tmpTrkData'] = $data_truck;
			$data['data_jsInfo'] = $data_jsInfo;
			$data['data_assignment'] = $data_assignment;
			$data['ain'] = $ain;
			$data['flag'] = 1;
			$data['payAmt'] = $payAmt;
			$data['payMethod'] = $payMethod; 
			$data['truckDtlId'] = $truckDtlId;
			
			$data['msg'] = $msg;
			$data['title']="Truck Entry for LCL";
			$this->load->view('cssAssetsList');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('lclTruckEntryforSecurity',$data);
			$this->load->view('jsAssetsList');

		}
	}

	// LCL Truck Pay By Security  -- end

	// Truck Entry for Users -- start

	function truckEntryForUsers()
	{

		$data['title']="Truck Entry for Users";
		$data['msg']="";

		$this->load->view('cssAssets');
		// $this->load->view('headerTop');
		// $this->load->view('sidebar');
		$this->load->view('truckEntryForUsers',$data);
		$this->load->view('jsAssets');

	}

	function truckPayForUsers()
	{
		// $this->onlinePaymentDataUpdation();
		// return;
		$user = $this->session->userdata('login_id');
		$user=str_replace(" ","",$user);
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
		$ipaddr = $_SERVER['REMOTE_ADDR'];
		
		$mobile = $this->input->post('phone_number');
		$cnf = $this->input->post('cnfAin');
		$cnfAinNorslt = explode(" - ",$cnf);
		$ain = trim($cnfAinNorslt[0]);
		$cfLoginId = $ain."CF";

		$totalAmount = 57.5;
		// $totalAmount = 1;

		$truckEntryQuery = "INSERT INTO do_truck_details_entry(paid_collect_by,update_by,last_update,ip_addr,entry_from) VALUES('$mobile','$cfLoginId',NOW(),'$ipaddr','user')";
		$this->bm->dataInsertDB1($truckEntryQuery);

		$visitIdQuery = "SELECT id FROM do_truck_details_entry WHERE paid_collect_by='$mobile' AND update_by='$cfLoginId' ORDER BY id DESC LIMIT 1";
		$visitIdResult = $this->bm->dataSelectDB1($visitIdQuery);
		$visitId = null;
		if(count($visitIdResult)>0){
			$visitId = $visitIdResult[0]['id'];
		}

		$sql_Requ = "SELECT MAX(vcms_online_ReqID.max_reqID) AS rtnValue FROM vcms_online_ReqID";
		$requst_id = $this->bm->dataReturnDB1($sql_Requ);
		
		$flag = '3';
		$ref = $requst_id."_".$flag;

		$query_txEntry = "INSERT INTO vcms_online_pay (visit_id, RefTranNo, requ_id, tr_amt, challan_amt, allPay_st, chk_st ) VALUES ('$visitId', '$ref', '$requst_id', '50', '7.5', 0 , 1)";
		$up_st = $this->bm->dataInsertDB1($query_txEntry);

		if($up_st>0)
			{
				$newReq_id=$requst_id+1;
				$newReq_id="0".$newReq_id;		// added now
				$query_update = "UPDATE vcms_online_ReqID SET max_reqID='$newReq_id'";
				$update_st = $this->bm->dataUpdateDB1($query_update); 
			}

		$login_id = null;

		$contact = $mobile;
		$data['requst_id'] = $requst_id;
		$data['ref'] = $ref;
		$data['login_id'] = $login_id;
		$data['contact'] = $contact;
		$data['flag'] = $flag;
		$cus_name = "Customer";
		$data['payAmt'] = $totalAmount;
		$contNo = null;
		$rotNo = null;
		$assignmentType = null;
		$cont_status = null;

		// echo $totalAmount;
		// echo $contact;
		// echo $requst_id;
		// echo $flag;
		// echo $login_id." : ".$contact." : ".$ref." : ".$requst_id." : ".$totalAmount." : ".$cus_name;
		// return;

		// $this->onlinePayPrepaid($login_id, $contact, $ref, $requst_id, $totalAmount, $cus_name);
		$this->onlinePay($contNo, $rotNo, $login_id, $contact, $ref, $requst_id, $totalAmount, $cus_name, $assignmentType, $cont_status);

		// return;

		// $data['title']="Termianl wise special assignment";
		// $data['msg']="";
	}

	// Truck Entry for Users -- end

	// Gate Pass for users -- start

	function gatePassForUsersForm()
	{
		$data['title']="Gate Pass for Users";
		$data['msg']="";

		$this->load->view('cssAssets');
		$this->load->view('gatePassForUsersForm',$data);
		$this->load->view('jsAssets');
	}

	function gatePassForUsers()
	{
		$trucVisitId = $this->input->post("visit_id");
		$rot = null;
		$cont = null;
		$u_name = null;

		$truckQuery = "SELECT import_rotation,cont_no,u_name
		FROM do_truck_details_entry 
		INNER JOIN users ON users.login_id = do_truck_details_entry.update_by 
		WHERE do_truck_details_entry.id = '$trucVisitId'";
		$truckRslt = $this->bm->dataSelectDB1($truckQuery);

		for($i=0;$i<count($truckRslt);$i++)
		{
			$rot = $truckRslt[$i]['import_rotation'];
			$cont = $truckRslt[$i]['cont_no'];
			$u_name = $truckRslt[$i]['u_name'];
		}

		// Barcode Library  -- start

		//load library
		$this->load->library('zend');
		//load in folder Zend

		$this->zend->load('Zend/Barcode');
		//generate barcode

		// $imageResource = Zend_Barcode::factory('code128', 'image', array('text'=>$trucVisitId), array())->draw();
		// imagepng($imageResource, $_SERVER['DOCUMENT_ROOT']."/pcsTest/assets/images/barcode/".$trucVisitId.'.png');

		// $data['barcode'] = IMG_PATH."barcode/".$trucVisitId.'.png';

		// Barcode Library  -- end

		$data['title']="Gate Pass for Users";
		$data['rot_no'] = $rot;
		$data['cont_no'] = $cont;
		$data['trucVisitId'] = $trucVisitId;
		$data['u_name'] = $u_name;
		$data['login_id'] = "";

		$this->load->view('vehiclaGatePass',$data);
	}

	// Gate Pass for users -- end
	
	//to show draft bill
	function shedBillDraftDetail()
	{
		$imp_rot = $this->input->post("imp_rot");
		$bl_no = $this->input->post("bl_no");
		//$strVerifyNum= $_GET["verify_num"];
		$unstfDt=date("Y-m-d");;
		
	 	$uptoDt= date("Y-m-d");
		$rpc= 0;
		$hcCharge= 0;
		$scCharge= 0;
		$vatInfo=1; 
		$mlwf= 1;
		
		$section = $this->session->userdata('section');
		
		$cont_status="";

		$this->tariffGenerateDraftBill($imp_rot,$bl_no,$unstfDt,$uptoDt,$rpc,$hcCharge,$scCharge); 

		$str="SELECT  import_rotation,shed_tally_info.cont_number,verify_number,Vessel_Name,
			IFNULL(igm_supplimentary_detail.Line_No, igm_details.Line_No) AS  Line_No,
			IFNULL(igm_supplimentary_detail.BL_No, igm_details.BL_No) AS BL_No,  
			IFNULL(igm_detail_container.cont_gross_weight, igm_sup_detail_container.Cont_gross_weight) AS cont_weight,
			IFNULL(igm_detail_container.cont_size, igm_sup_detail_container.cont_size) AS cont_size,
			IFNULL(igm_detail_container.cont_height, igm_sup_detail_container.cont_height) AS cont_height,
			IFNULL(igm_detail_container.cont_type, igm_sup_detail_container.cont_type) AS cont_type,
			IFNULL(igm_supplimentary_detail.Consignee_code, igm_details.Consignee_code) AS Consignee_code,  
			IFNULL(igm_supplimentary_detail.Consignee_name, igm_details.Consignee_name) AS Consignee_name,  
			IFNULL(igm_supplimentary_detail.Pack_Number, igm_details.Pack_Number) AS Pack_Number,  
			IFNULL(igm_supplimentary_detail.notify_name, igm_details.notify_name) AS notify_name, 
			wr_date,wr_upto_date,cnf_lic_no,be_no, be_date, cnf_name,rcv_pack,loc_first,total_pack,
			igm_supplimentary_detail.Pack_Number,
			verify_other_data.valid_up_to_date,verify_other_data.do_no,verify_other_data.do_date,
			verify_other_data.comm_landing_date,rcv_unit,equipment,
			used_equipment.equipment_id,used_equipment.equipment_charge,used_equipment.equipment_name,used_equipment.remarks,
			bil_tariffs.id AS tariffid,bil_tariffs.gl_code AS glcode,bil_tariff_rates.amount AS tamt
			FROM shed_tally_info
			LEFT JOIN igm_supplimentary_detail ON igm_supplimentary_detail.id = shed_tally_info.igm_sup_detail_id
			LEFT JOIN igm_sup_detail_container ON shed_tally_info.igm_sup_detail_id=igm_sup_detail_container.igm_sup_detail_id
			LEFT JOIN  igm_details ON igm_details.id = shed_tally_info.igm_detail_id
			LEFT JOIN igm_detail_container ON shed_tally_info.igm_detail_id=igm_detail_container.igm_detail_id
			INNER JOIN igm_masters ON igm_supplimentary_detail.igm_master_id=igm_masters.id
			LEFT JOIN verify_other_data ON shed_tally_info.id=verify_other_data.shed_tally_id
			LEFT JOIN appraisement_info ON (appraisement_info.rotation=igm_supplimentary_detail.Import_Rotation_No
			AND appraisement_info.BL_NO=igm_supplimentary_detail.BL_No)
			OR (appraisement_info.rotation=igm_details.Import_Rotation_No AND appraisement_info.BL_NO=igm_details.BL_No)
			LEFT JOIN used_equipment ON appraisement_info.equipment_id=used_equipment.equipment_id
			LEFT JOIN bil_tariffs ON used_equipment.equipment_name=bil_tariffs.description
			LEFT JOIN bil_tariff_rates ON bil_tariffs.gkey=bil_tariff_rates.tariff_gkey
			WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_supplimentary_detail.BL_No='$bl_no'";
		$rtnBillList = $this->bm->dataSelectDb1($str);
		
		if(count($rtnBillList)==0)
		{			
			$cont_status="FCL";
			
			$str="SELECT  DISTINCT igm_detail_container.cont_number, igm_details.Import_Rotation_No AS import_rotation, 
			verify_info_fcl.verify_number,igm_masters.Vessel_Name,igm_details.Line_No,igm_details.BL_No,
			igm_detail_container.Cont_gross_weight AS cont_weight,igm_detail_container.cont_size,
			igm_detail_container.cont_height,igm_detail_container.cont_status,igm_detail_container.cont_type,
			certify_info_fcl.wr_upto_date,certify_info_fcl.cnf_lic_no,certify_info_fcl.be_no,certify_info_fcl.be_date,
			igm_details.notify_name,certify_info_fcl.cnf_name,igm_details.Pack_Number,igm_details.Consignee_code,
			igm_details.Consignee_name,verify_info_fcl.valid_up_to_date,verify_info_fcl.do_no,certify_info_fcl.do_date,
			appraisement_info_fcl.equipment,common_land_date AS comm_landing_date,warfrent_start_date AS wr_date
			FROM igm_details
			INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
			LEFT JOIN certify_info_fcl ON certify_info_fcl.igm_detail_id=igm_details.id
			INNER JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
			LEFT JOIN appraisement_info_fcl ON appraisement_info_fcl.rotation=igm_details.Import_Rotation_No AND appraisement_info_fcl.BL_NO=igm_details.BL_No
			INNER JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
			INNER JOIN assigned_unit ON assigned_unit.rotation=igm_details.Import_Rotation_No
			WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$bl_no'";					
			$rtnBillList = $this->bm->dataSelectDb1($str);
			
			
			$strTotContainer="select count(*)
			as totCont from (
			SELECT DISTINCT igm_detail_container.cont_number,igm_details.Import_Rotation_No AS import_rotation,
			verify_info_fcl.verify_number,igm_masters.Vessel_Name,igm_details.Line_No,igm_details.BL_No,
			igm_detail_container.Cont_gross_weight AS cont_weight,igm_detail_container.cont_size,
			igm_detail_container.cont_height,igm_detail_container.cont_status,igm_detail_container.cont_type,
			certify_info_fcl.wr_upto_date,certify_info_fcl.cnf_lic_no,certify_info_fcl.be_no,certify_info_fcl.be_date,
			igm_details.notify_name,certify_info_fcl.cnf_name,igm_details.Pack_Number,igm_details.Consignee_code,
			igm_details.Consignee_name,verify_info_fcl.valid_up_to_date,verify_info_fcl.do_no,certify_info_fcl.do_date,
			appraisement_info_fcl.equipment,'' as comm_landing_date,'' as wr_date
			FROM igm_details
			INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
			LEFT JOIN certify_info_fcl ON certify_info_fcl.igm_detail_id=igm_details.id
			INNER JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
			LEFT JOIN appraisement_info_fcl ON appraisement_info_fcl.rotation=igm_details.Import_Rotation_No AND appraisement_info_fcl.BL_NO=igm_details.BL_No
			INNER JOIN igm_masters ON igm_masters.id=igm_details.IGM_id
			WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$bl_no') as tble";
			$rtnTotContainer = $this->bm->dataSelectDb1($strTotContainer);
			$data['rtnTotContainer']=$rtnTotContainer;
			for($k=0;$k<count($rtnTotContainer); $k++)
			{
				$total_container=$rtnTotContainer[$k]['totCont'];
			}		
			//$total_container = $rtnTotContainer[0]['totCont'];
			$data['total_container']=$total_container;
		/* 	echo json_encode($data);
			return; */

		}
		else
		{
			$cont_status="LCL";
		}
		
		$import_rotation = @$rtnBillList[0]['import_rotation'];
		$container = @$rtnBillList[0]['cont_number'];
		$blNo= @$rtnBillList[0]['BL_No'];
				
	
		$arraivalDateQry="select to_char(argo_carrier_visit.ata,'YYYY-MM-DD') as ata from vsl_vessel_visit_details
		inner join argo_carrier_visit 
		on argo_carrier_visit.cvcvd_gkey=vsl_vessel_visit_details.vvd_gkey
		where vsl_vessel_visit_details.ib_vyg='$imp_rot'";
		$arraivalDate = $this->bm->dataSelect($arraivalDateQry);
		for($k=0;$k<count($arraivalDate); $k++)
		{
			$arraivalDateValue=$arraivalDate[$k]['ATA'];
		}	
		//$arraivalDateValue=$arraivalDate[0]['ATA'];
			
		if($cont_status=="LCL")
		{
			$getDataAppraisalQry="SELECT equipment,appraise_date,carpainter_use,hosting_charge,extra_movement,scale_for 
			FROM appraisement_info WHERE rotation='$imp_rot' AND BL_NO='$bl_no'";
		}
		else if($cont_status=="FCL")
		{
			$getDataAppraisalQry="SELECT equipment,appraise_date,carpainter_use,hosting_charge,extra_movement,scale_for
			FROM appraisement_info_fcl WHERE rotation='$imp_rot' AND BL_NO='$bl_no'";
		}
	
		
		$appraisalData = $this->bm->dataSelectDb1($getDataAppraisalQry);
		$appraisalDataCount=count($appraisalData);

		//	$getExRateQuery= "select rate from bil_currency_exchange_rates where DATE(effective_date)= '$arraivalDateValue'";
		
		$getExRateQuery= "SELECT IFNULL((SELECT rate FROM bil_currency_exchange_rates WHERE DATE(effective_date)= '$arraivalDateValue'),(SELECT rate FROM bil_currency_exchange_rates ORDER BY gkey DESC LIMIT 1)) AS rate";
		$getExRate = $this->bm->dataSelectDb1($getExRateQuery);
		//$getExRateValue=$getExRate[0]['rate'];
		for($k=0;$k<count($getExRate); $k++)
		{
			$getExRateValue=$getExRate[$k]['rate'];
		}
		

		
		$getDateDiffQuery= "SELECT IFNULL(DATEDIFF('$uptoDt',DATE_ADD('$unstfDt',INTERVAL 4 DAY)),0) AS dif";		
		// 2020-04-06 - start
		$getDateDiff = $this->bm->dataSelectDb1($getDateDiffQuery);
		for($k=0;$k<count($getDateDiff); $k++)
		{
			$dateDiffValue=$getDateDiff[$k]['dif'];
		}
		
		
		// 2020-04-06 - end
		
		//$dateDiffValue=15;					
					
		// 2020-04-06 - start	
		if($cont_status=="LCL")
		{
			$qry= "select verify_no,tarrif_id,bil_tariffs.description,bil_tariffs.gl_code,IFNULL(bil_tariff_rates.amount,0) as tarrif_rate,
			ifnull(verify_other_data.update_ton,CEIL(igm_sup_detail_container.Cont_gross_weight /1000)) as Qty,
			igm_sup_detail_container.Cont_gross_weight as cont_weight,
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
			(select if($vatInfo='0',0,(select amt*15/100))) as vatTK 
			FROM shed_bill_tarrif_draft
			INNER JOIN bil_tariffs ON  shed_bill_tarrif_draft.tarrif_id= bil_tariffs.id
			INNER JOIN bil_tariff_rates ON bil_tariffs.gkey=bil_tariff_rates.tariff_gkey
			INNER JOIN igm_supplimentary_detail ON shed_bill_tarrif_draft.rotation= igm_supplimentary_detail.Import_Rotation_No AND shed_bill_tarrif_draft.bl_no= igm_supplimentary_detail.BL_No
			INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id = igm_supplimentary_detail.id

			LEFT JOIN shed_tally_info ON shed_tally_info.igm_sup_detail_id=igm_sup_detail_container.igm_sup_detail_id
			INNER JOIN verify_other_data ON verify_other_data.shed_tally_id=shed_tally_info.id
			where shed_bill_tarrif_draft.rotation='$imp_rot' AND shed_bill_tarrif_draft.bl_no='$bl_no'";
		}
		else if($cont_status=="FCL")
		{						//amt
			$qry="SELECT DISTINCT bil_tariffs.gl_code AS gl_code,bil_tariff_rates.currency_gkey,verify_number,tarrif_id,
			IF(currency_gkey='2',CONCAT('$',bil_tariffs.description),bil_tariffs.description) AS description,
			IFNULL(bil_tariff_rates.amount,0) AS tarrif_rate,
			certify_info_fcl.update_ton,
			(CASE
				WHEN tarrif_id='HOSTING_CHARGES'
				THEN hosting_charge
				ELSE
					CASE
					WHEN tarrif_id='WEIGHMENT_CHARGE'
					THEN scale_for
					ELSE
						CASE
							WHEN tarrif_id='REPAIRING_CHARGE'
							THEN carpainter_use
							ELSE
								CASE
									WHEN tarrif_id='FLT_1_5_TON'
									THEN 
							(SELECT COUNT(*) FROM igm_details 
							INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id 
							WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$bl_no')
									ELSE 
										CASE
											WHEN tarrif_id='FLT_6_20_TON'
											THEN 
								(SELECT COUNT(*) FROM igm_details 
								INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id 
								WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$bl_no')
											ELSE
												CASE
													WHEN tarrif_id='FLT_21_50_TON'
													THEN 
									(SELECT COUNT(*) FROM igm_details 
									INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id 
									WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$bl_no')
												   -- ELSE add remaining charges
												END
										END
								END
						END
				END		
			END) AS Qty,
			'1' AS qday,
			IF(currency_gkey='2',(SELECT tarrif_rate*Qty*qday*84.96),(SELECT tarrif_rate*Qty*qday)) AS amt,
			(SELECT IF(1='0',0,(SELECT amt*15/100))) AS vatTK
			FROM igm_details 
			INNER JOIN shed_bill_tarrif_draft ON shed_bill_tarrif_draft.rotation=igm_details.Import_Rotation_No AND shed_bill_tarrif_draft.bl_no=igm_details.BL_No			
			INNER JOIN bil_tariffs ON bil_tariffs.id=shed_bill_tarrif_draft.tarrif_id
			INNER JOIN bil_tariff_rates ON bil_tariff_rates.tariff_gkey=bil_tariffs.gkey
			LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
			LEFT JOIN certify_info_fcl ON certify_info_fcl.igm_detail_id=igm_details.id
			LEFT JOIN appraisement_info_fcl ON appraisement_info_fcl.rotation=igm_details.Import_Rotation_No AND appraisement_info_fcl.BL_NO=igm_details.BL_No			
			WHERE  igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$bl_no' AND (bil_tariffs.gl_code IN('503000N','204002N','309000') OR (tarrif_id LIKE 'FLT_%' OR tarrif_id LIKE 'CRANE_%'))

			UNION ALL

			SELECT DISTINCT bil_tariffs.gl_code,currency_gkey,verify_number,shed_bill_tarrif_draft.tarrif_id,bil_tariffs.description,
			IFNULL(bil_tariff_rates.amount,0) AS tarrif_rate,
			-- igm_details.id AS igm_dtls_id,
			certify_info_fcl.update_ton,
			-- appraisement_info_fcl.gkey AS appInfoFCL_gkey,
			-- cont_size,
			(CASE
				WHEN (bil_tariffs.gl_code='501001' OR bil_tariffs.gl_code='505001')		-- 20
				THEN (SELECT COUNT(*) AS cnt FROM (SELECT igm_detail_container.cont_status,igm_detail_container.cont_size,
				igm_detail_container.cont_height	 
				FROM igm_details
				INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
				WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$bl_no') AS tbl WHERE cont_size='20')
				ELSE
				CASE
					WHEN (bil_tariffs.gl_code='501002' OR bil_tariffs.gl_code='505002')		-- 40 8.6
					THEN (SELECT COUNT(*) AS cnt FROM (SELECT igm_detail_container.cont_status,igm_detail_container.cont_size,
						igm_detail_container.cont_height	 
						FROM igm_details
						INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
						WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$bl_no') AS tbl WHERE cont_size='40' AND cont_height='8.6')
					ELSE
						CASE
							WHEN (bil_tariffs.gl_code='501003' OR bil_tariffs.gl_code='505006')		-- 40 9.6 45
							THEN (SELECT COUNT(*) AS cnt FROM (SELECT igm_detail_container.cont_status,igm_detail_container.cont_size,
								igm_detail_container.cont_height	 
								FROM igm_details
								INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
								WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$bl_no') AS tbl 
								WHERE cont_size='45' OR (cont_size='40' AND cont_height='9.6'))
								ELSE -- slab
						CASE	-- slab 20 
							WHEN (bil_tariffs.gl_code='403017' OR bil_tariffs.gl_code='403019' OR bil_tariffs.gl_code='403021')
							THEN (SELECT COUNT(*) AS cnt FROM (SELECT igm_detail_container.cont_status,
								igm_detail_container.cont_size,
								igm_detail_container.cont_height	 
								FROM igm_details
								INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
								WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$bl_no') AS tbl 
								WHERE cont_size='20')
							ELSE
								CASE 	-- slab 40
									WHEN (bil_tariffs.gl_code='403023' OR bil_tariffs.gl_code='403025' OR bil_tariffs.gl_code='403027')
									THEN (SELECT COUNT(*) AS cnt FROM (SELECT igm_detail_container.cont_status,
										igm_detail_container.cont_size,
										igm_detail_container.cont_height	 
										FROM igm_details
										INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
										WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$bl_no') AS tbl 
										WHERE cont_size='40')
									ELSE
										CASE	-- slab 45
											WHEN (bil_tariffs.gl_code='403029' OR bil_tariffs.gl_code='403031' OR bil_tariffs.gl_code='403033')
											THEN (SELECT COUNT(*) AS cnt FROM (SELECT igm_detail_container.cont_status,
												igm_detail_container.cont_size,
												igm_detail_container.cont_height	 
												FROM igm_details
												INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
												WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$bl_no') AS tbl 
												WHERE cont_size='45')
										END
								END			                
						END
					END   
					END	       
			END) AS Qty,

			(CASE 
				WHEN 
					tarrif_id LIKE '%1ST%'
				THEN 
					IF($dateDiffValue<=7,$dateDiffValue,7)
				ELSE 
					CASE 
						WHEN 
							tarrif_id LIKE '%2ND%'
						THEN 
							IF($dateDiffValue<=20,$dateDiffValue-7,7)
						ELSE  
							IF(tarrif_id LIKE '%3RD%',$dateDiffValue-20,1)							
					END
			END) AS qday,		
			
			IF(currency_gkey='2',(SELECT tarrif_rate*Qty*qday*$getExRateValue),(SELECT tarrif_rate*Qty*qday)) AS amt,
			(SELECT IF($vatInfo='0',0,(SELECT amt*15/100))) AS vatTK
			FROM igm_details 
			INNER JOIN shed_bill_tarrif_draft ON shed_bill_tarrif_draft.rotation=igm_details.Import_Rotation_No AND shed_bill_tarrif_draft.bl_no=igm_details.BL_No
			INNER JOIN bil_tariffs ON bil_tariffs.id=shed_bill_tarrif_draft.tarrif_id
			INNER JOIN bil_tariff_rates ON bil_tariff_rates.tariff_gkey=bil_tariffs.gkey
			LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
			LEFT JOIN certify_info_fcl ON certify_info_fcl.igm_detail_id=igm_details.id
			LEFT JOIN appraisement_info_fcl ON appraisement_info_fcl.rotation=igm_details.Import_Rotation_No AND appraisement_info_fcl.BL_NO=igm_details.BL_No
			WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$bl_no' AND 
			bil_tariffs.gl_code IN('501001','501002','501003','505001','505002','505006','403017','403019','403021','403023','403025','403027','403029','403031','403033')";
		}
		
		// //echo $qry; vatTK
		 $chargeList = $this->bm->dataSelectDb1($qry);
		
		$oneStopPoint="select distinct unit_no from assigned_unit where rotation='$imp_rot'";
		$oneStopList = $this->bm->dataSelectDb1($oneStopPoint);
		$oneStop=@$oneStopList[0]['unit_no'];
				
		//	$data['totalBillList']=$totalBillList;	// 2020-04-06
		if($appraisalDataCount==0)
		{
			$data['appraisalData']=null; 
		}
		else{
			$data['appraisalData']=$appraisalData; 
		}
		
		$data['rtnBillList']=$rtnBillList;
		$data['chargeList']=$chargeList;		// 2020-04-06
		$data['arraivalDateValue']=$arraivalDateValue;
		$data['getExRateValue']=$getExRateValue;
		//$data['sectionValue']=$this->session->userdata('section');
		$data['sectionValue']=$oneStop;
		$data['unstfDt']=$unstfDt;
		
		$data['cont_status']=$cont_status;
		
		$data['uptoDt']=$uptoDt;
		$data['rpc']=$rpc;
		$data['hcCharge']=$hcCharge;
		$data['scCharge']=$scCharge;
		$data['imp_rot']=$imp_rot;
		$data['bl_no']=$bl_no;
		
		$data['dateDiffValue']=$dateDiffValue;
		//print_r($data);
		$this->load->view('shedBillDraftView',$data);
	//	echo json_encode($data);
		//$terminal = $_POST["terminalName"];	
	}
	
	
	
	function tariffGenerateDraftBill($imp_rot,$bl_no,$unstfDt,$uptoDt,$rpc,$hcCharge,$scCharge)
	{
		$qry="SELECT igm_sup_detail_container.cont_number,igm_sup_detail_container.cont_status,cont_size,cont_height,rcv_pack,loc_first,shed_tally_info.cont_number,equipment,appraisement_info.equipment_id,used_equipment.equipment_name	 
		FROM  igm_supplimentary_detail
		INNER JOIN igm_sup_detail_container ON igm_sup_detail_container.igm_sup_detail_id=igm_supplimentary_detail.id
		LEFT JOIN  shed_tally_info ON shed_tally_info.igm_sup_detail_id=igm_supplimentary_detail.id 
		LEFT JOIN appraisement_info ON igm_supplimentary_detail.Import_Rotation_No=appraisement_info.rotation AND igm_supplimentary_detail.BL_No=appraisement_info.BL_NO
		LEFT JOIN used_equipment ON used_equipment.equipment_id=appraisement_info.equipment_id
		WHERE igm_supplimentary_detail.Import_Rotation_No='$imp_rot' AND igm_supplimentary_detail.BL_No='$bl_no'
		GROUP BY igm_sup_detail_container.id";
		
		$conStatus = $this->bm->dataSelectDb1($qry); 
		//echo $conStatus[0]['cont_status']; 
		
		if(count($conStatus)==0)
		{
			$qry="SELECT igm_detail_container.cont_status,cont_size,cont_height,igm_detail_container.cont_number,equipment,appraisement_info_fcl.equipment_id,used_equipment.equipment_name	 
			FROM  igm_details
			INNER JOIN igm_detail_container ON igm_detail_container.igm_detail_id=igm_details.id
			LEFT JOIN appraisement_info_fcl ON igm_details.Import_Rotation_No=appraisement_info_fcl.rotation AND igm_details.BL_No=appraisement_info_fcl.BL_NO
			LEFT JOIN used_equipment ON used_equipment.equipment_id=appraisement_info_fcl.equipment_id
			LEFT JOIN verify_info_fcl ON verify_info_fcl.igm_detail_id=igm_details.id
			WHERE igm_details.Import_Rotation_No='$imp_rot' AND igm_details.BL_No='$bl_no'
			GROUP BY igm_detail_container.id";
			$conStatus = $this->bm->dataSelectDb1($qry);
		}
		
		$cont_status = $conStatus[0]['cont_status'];
		if($cont_status=='LCL')
		{
			$loc_first = $conStatus[0]['loc_first'];
			$rcv_pack = $conStatus[0]['rcv_pack'];
		}
		$cont_number = $conStatus[0]['cont_number'];
		//echo "Starus==".$loc_first;
		$equip_charge = $conStatus[0]['equipment'];
		$equip_id = $conStatus[0]['equipment_id'];
		$equip_name = $conStatus[0]['equipment_name'];
		if($cont_status=="LCL")
		{
			$strRiverDues="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
				values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_LCL('$imp_rot','$bl_no', 1)),1,1)";
			$statRiverDues=$this->bm->dataInsertDB1($strRiverDues);
				
			$strStuffUnStuff="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
				values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_LCL('$imp_rot','$bl_no',2)),1,2)";
			$statStuffUnStuff=$this->bm->dataInsertDB1($strStuffUnStuff);
			
			if($hcCharge!=0)
			{
				$strHostingCharge="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
				values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_LCL('$imp_rot','$bl_no',3)),1,3)";
				$statHostingCharge=$this->bm->dataInsertDB1($strHostingCharge);
			}
			else
			{
				$strDelHostingCharge="delete from shed_bill_tarrif_draft where rotation='$imp_rot' AND bl_no='$bl_no' AND event_type=3";
				$statDelHostingCharge=$this->bm->dataInsertDB1($strDelHostingCharge);
			}
			
			if($rpc!=0)
			{
				$strScaleCharge="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
					values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_LCL('$imp_rot','$bl_no',12)),1,12)";
				$statScaleCharge=$this->bm->dataInsertDB1($strScaleCharge);
			}
			else
			{
				$strDelScaleCharge="delete from shed_bill_tarrif_draft where rotation='$imp_rot' AND bl_no='$bl_no' AND event_type=12";
				$statDelScaleCharge=$this->bm->dataInsertDB1($strDelScaleCharge);
			}
			
			if($scCharge!=0)
			{				
				$strWeightmentCharge="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
					values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_LCL('$imp_rot','$bl_no',10)),1,10)";
				$statWeightmentCharge=$this->bm->dataInsertDB1($strWeightmentCharge);
			}
			else
			{				
				$strDelWeightmentCharge="delete from shed_bill_tarrif_draft where rotation='$imp_rot' AND bl_no='$bl_no' AND event_type=10";
				$statDelWeightmentCharge=$this->bm->dataInsertDB1($strDelWeightmentCharge);
			}
			if($loc_first>0)
			{			
				$getDateDiffQuery= "SELECT IFNULL(DATEDIFF('$uptoDt',DATE_ADD('$unstfDt',INTERVAL 4 day)),0) as dif";
				
				$getDateDiff = $this->bm->dataSelectDb1($getDateDiffQuery);
				
				$dateDiffValue=$getDateDiff[0]['dif'];
				if($dateDiffValue>14)
				{
					//9
					$strStuffUnStuff="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_LCL('$imp_rot','$bl_no',9)),1,9)";
					$statStuffUnStuff=$this->bm->dataInsertDB1($strStuffUnStuff);
					//8
					$strStuffUnStuff1="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_LCL('$imp_rot','$bl_no',8)),1,8)";
					$statStuffUnStuff1=$this->bm->dataInsertDB1($strStuffUnStuff1);
					//7
					$strStuffUnStuff2="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_LCL('$imp_rot','$bl_no',7)),1,7)";
					$statStuffUnStuff2=$this->bm->dataInsertDB1($strStuffUnStuff2);
				}
				else if($dateDiffValue>7 and $dateDiffValue<=14)
				{
					//7
					$strStuffUnStuff="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_LCL('$imp_rot','$bl_no',7)),1,7)";
					$statStuffUnStuff=$this->bm->dataInsertDB1($strStuffUnStuff);
					//8
					$strStuffUnStuff1="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_bill_tarrif_draft('$imp_rot','$bl_no',8)),1,8)";
					$statStuffUnStuff1=$this->bm->dataInsertDB1($strStuffUnStuff1);
				}
				else if($dateDiffValue>0 and $dateDiffValue<=7)
				{
					//7
					$strStuffUnStuff="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_LCL('$imp_rot','$bl_no',7)),1,7)";
					$statStuffUnStuff=$this->bm->dataInsertDB1($strStuffUnStuff);
				}
			}
			//else if($loc_first<=0)
			//{
				/********************Add 4 Days*************************/
			if($rcv_pack>0)
			{				
				$dateDiffValue=$getDateDiff[0]['dif'];
				//$dateDiffValue = 18;
				if($dateDiffValue>14)
				{
					//4
					$strStuffUnStuff="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_LCL('$imp_rot','$bl_no',4)),1,4)";
					$statStuffUnStuff=$this->bm->dataInsertDB1($strStuffUnStuff);
					//5
					$strStuffUnStuff="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_LCL('$imp_rot','$bl_no',5)),1,5)";
					$statStuffUnStuff=$this->bm->dataInsertDB1($strStuffUnStuff);
					//6
					$strStuffUnStuff="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_LCL('$imp_rot','$bl_no',6)),1,6)";
					$statStuffUnStuff=$this->bm->dataInsertDB1($strStuffUnStuff);
				}
				else if($dateDiffValue>7 and $dateDiffValue<=14)
				{
					//4
					$strStuffUnStuff="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_LCL('$imp_rot','$bl_no',4)),1,4)";
					$statStuffUnStuff=$this->bm->dataInsertDB1($strStuffUnStuff);
					//5
					$strStuffUnStuff="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_LCL('$imp_rot','$bl_no',5)),1,5)";
					$statStuffUnStuff=$this->bm->dataInsertDB1($strStuffUnStuff);
				}
				else if($dateDiffValue>0 and $dateDiffValue<=7)
				{
					//4
					$strStuffUnStuff="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_LCL('$imp_rot','$bl_no',4)),1,4)";
					$statStuffUnStuff=$this->bm->dataInsertDB1($strStuffUnStuff);
				}
			}
		/* 
			$eqipmentStr="SELECT equip_for_assignment.equip_id 
						FROM shed_tally_info 
						INNER JOIN assignment_request_data ON assignment_request_data.igm_detail_id=shed_tally_info.igm_detail_id 
														OR assignment_request_data.igm_sup_dtl_id=shed_tally_info.igm_sup_detail_id
						INNER JOIN equip_for_assignment ON equip_for_assignment.assign_id=assignment_request_data.id
						WHERE shed_tally_info.verify_number='$billVerify'"; */
			$eqipmentStr="SELECT equip_for_assignment.equip_id 
						FROM shed_tally_info 
						INNER JOIN assignment_request_data ON assignment_request_data.igm_detail_id=shed_tally_info.igm_detail_id 
											OR assignment_request_data.igm_sup_dtl_id=shed_tally_info.igm_sup_detail_id
						INNER JOIN equip_for_assignment ON equip_for_assignment.assign_id=assignment_request_data.id
						LEFT JOIN igm_sup_detail_container ON shed_tally_info.igm_sup_detail_id=igm_sup_detail_container.igm_sup_detail_id
						INNER JOIN igm_supplimentary_detail ON igm_sup_detail_container.igm_sup_detail_id = igm_supplimentary_detail.id
						WHERE igm_supplimentary_detail.Import_Rotation_No='$imp_rot' AND igm_supplimentary_detail.BL_No='$bl_no'";

			$eqipmentRslt = $this->bm->dataSelectDb1($eqipmentStr);
			for($i=0; $i<count($eqipmentRslt); $i++)
			{
				$equip_id=$eqipmentRslt[$i]['equip_id'];
				if($equip_id==1)  //USED EQUIPMENT
				{
					$strUsedEquipmentCharge="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
					values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_LCL('$imp_rot','$bl_no',13)),1,13)";
					$statUsedEquipmentCharge=$this->bm->dataInsertDB1($strUsedEquipmentCharge);
				}
				else if($equip_id==2)  //USED EQUIPMENT
				{
					$strUsedEquipmentCharge="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
					values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_LCL('$imp_rot','$bl_no',14)),1,14)";
					$statUsedEquipmentCharge=$this->bm->dataInsertDB1($strUsedEquipmentCharge);
				}
				else if($equip_id==3)  //USED EQUIPMENT
				{
					$strUsedEquipmentCharge="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
					values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_LCL('$imp_rot','$bl_no',15)),1,15)";
					$statUsedEquipmentCharge=$this->bm->dataInsertDB1($strUsedEquipmentCharge);
				}
				else if($equip_id==4)  //USED EQUIPMENT
				{
					$strUsedEquipmentCharge="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
					values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_LCL('$imp_rot','$bl_no',16)),1,16)";
					$statUsedEquipmentCharge=$this->bm->dataInsertDB1($strUsedEquipmentCharge);
				}
				else if($equip_id==5)  //USED EQUIPMENT
				{
					$strUsedEquipmentCharge="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
					values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_LCL('$imp_rot','$bl_no',17)),1,17)";
					$statUsedEquipmentCharge=$this->bm->dataInsertDB1($strUsedEquipmentCharge);
				}
				else
				{
					$strDelUsedEquipmentCharge="delete from shed_bill_tarrif_draft where rotation='$imp_rot' AND bl_no='$bl_no' AND event_type in (13,14,15,16,17)";
					$statDelUsedEquipmentCharge=$this->bm->dataInsertDB1($strDelUsedEquipmentCharge);
				}
			}
		}
		else if($cont_status=="FCL")			// --------------------- FCL ---------------------
		{
			//echo '11111111111';
			// $cont_size = $conStatus[0]['cont_size'];
			// $cont_height = $conStatus[0]['cont_height'];
			
			// use loop to check size and height. then check the current chargeList query.
			
			$riverDues20_cnt=0;
			$riverDues40_cnt=0;
			$riverDues40HQ_cnt=0;
			$riverDues45_cnt=0;
			
			$liftOn20_cnt=0;
			$liftOn40_cnt=0;
			$liftOn40HQ_cnt=0;
			$liftOn45_cnt=0;
			
			for($i=0;$i<count($conStatus);$i++)
			{
				$cont_size = $conStatus[$i]['cont_size'];
				$cont_height = $conStatus[$i]['cont_height'];
				
				if($cont_size==20)			// container wise separate
				{
					if($riverDues20_cnt==0)
					{
						// RIVER_DUES_FCL_20
						$strRiverDues20="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',18)),3,18)";
						$statRiverDues20=$this->bm->dataInsertDB1($strRiverDues20);
						$riverDues20_cnt++;
					}
					
					if($liftOn20_cnt==0)
					{
						// LIFT_ON_FCL_20
						$strLiftOn20="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',21)),3,21)";
						$statLiftOn20=$this->bm->dataInsertDB1($strLiftOn20);
						$liftOn20_cnt++;					
					}
				}
				else if($cont_size==40 and $cont_height=="8.6")		// container wise separate
				{
					if($riverDues40_cnt==0)
					{
						// RIVER_DUES_FCL_40
						$strRiverDues40="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',19)),3,19)";
						$statRiverDues40=$this->bm->dataInsertDB1($strRiverDues40);
						$riverDues40_cnt++;
					}
					
					if($liftOn40_cnt==0)
					{
						// LIFT_ON_FCL_40
						$strLiftOn40="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',22)),3,22)";
						$strLiftOn40=$this->bm->dataInsertDB1($strLiftOn40);
						$liftOn40_cnt++;
					}
				}
				else if($cont_size==40 and $cont_height=="9.6")		// container wise separate
				{
					if($riverDues40HQ_cnt==0)
					{
						// RIVER_DUES_FCL_45
						$strRiverDues45="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',20)),3,20)";
						$statRiverDues45=$this->bm->dataInsertDB1($strRiverDues45);
						$riverDues40HQ_cnt++;
					}
					
					if($liftOn40HQ_cnt==0)
					{
						// LIFT_ON_FCL_45
						$strLiftOn45="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',23)),3,23)";
						$strLiftOn45=$this->bm->dataInsertDB1($strLiftOn45);
						$liftOn40HQ_cnt++;
					}
				}
				else if($cont_size==45)		// container wise separate
				{
					if($riverDues45_cnt==0)
					{
						// RIVER_DUES_FCL_45
						$strRiverDues45="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',20)),3,20)";
						$statRiverDues45=$this->bm->dataInsertDB1($strRiverDues45);
						$riverDues45_cnt++;
					}
					
					if($liftOn45_cnt==0)
					{
						// LIFT_ON_FCL_45
						$strLiftOn45="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',23)),3,23)";
						$strLiftOn45=$this->bm->dataInsertDB1($strLiftOn45);
						$liftOn45_cnt++;
					}
				}
			}
												
			// hc, rpc, sc start
			if($hcCharge!=0)		// one tarrif for total bill
			{
				// HOSTING_CHARGES
				$strHostingCharge="replace into shed_bill_tarrif_draft(rotation, bl_no, tarrif_id,billType,event_type) 
				values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',3)),3,3)";
				$statHostingCharge=$this->bm->dataInsertDB1($strHostingCharge);
			}
			else
			{
				$strDelHostingCharge="delete from shed_bill_tarrif_draft where rotation='$imp_rot' AND bl_no='$bl_no' AND event_type=3";
				$statDelHostingCharge=$this->bm->dataInsertDB1($strDelHostingCharge);
			}
			
			if($rpc!=0)				// one tarrif for total bill
			{
				// REPAIRING_CHARGE
				$strScaleCharge="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
					values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',12)),3,12)";
				$statScaleCharge=$this->bm->dataInsertDB1($strScaleCharge);
			}
			else
			{
				$strDelScaleCharge="delete from shed_bill_tarrif_draft where rotation='$imp_rot' AND bl_no='$bl_no' AND event_type=12";
				$statDelScaleCharge=$this->bm->dataInsertDB1($strDelScaleCharge);
			}
			
			if($scCharge!=0)		// one tarrif for total bill	- WEIGHMENT CHARGE
			{				
				// WEIGHMENT_CHARGE
				$strWeightmentCharge="replace into shed_bill_tarrif_draft(rotation, bl_no, tarrif_id,billType,event_type) 
					values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',10)),3,10)";
				$statWeightmentCharge=$this->bm->dataInsertDB1($strWeightmentCharge);
			}
			else
			{				
				$strDelWeightmentCharge="delete from shed_bill_tarrif_draft where rotation='$imp_rot' AND bl_no='$bl_no' and event_type=10";
				$statDelWeightmentCharge=$this->bm->dataInsertDB1($strDelWeightmentCharge);
			}
			// hc, rpc, sc end
			
			// equipment tarrif start	
			if($equip_id==1)  //USED EQUIPMENT				// container wise separate
			{
				// FLT_1_5_TON
				$strUsedEquipmentCharge="replace into shed_bill_tarrif_draft(rotation, bl_no, tarrif_id,billType,event_type) 
				values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',13)),3,13)";
				$statUsedEquipmentCharge=$this->bm->dataInsertDB1($strUsedEquipmentCharge);
			}
			else if($equip_id==2)  //USED EQUIPMENT			// container wise separate
			{
				// FLT_6_20_TON
				$strUsedEquipmentCharge="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
				values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',14)),3,14)";
				$statUsedEquipmentCharge=$this->bm->dataInsertDB1($strUsedEquipmentCharge);
			}
			else if($equip_id==3)  //USED EQUIPMENT			// container wise separate
			{
				// FLT_21_50_TON
				$strUsedEquipmentCharge="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
				values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',15)),3,15)";
				$statUsedEquipmentCharge=$this->bm->dataInsertDB1($strUsedEquipmentCharge);
			}
			else if($equip_id==4)  //USED EQUIPMENT			// container wise separate
			{
				// CRANE_1_10_TON
				$strUsedEquipmentCharge="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
				values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',16)),3,16)";
				$statUsedEquipmentCharge=$this->bm->dataInsertDB1($strUsedEquipmentCharge);
			}
			else if($equip_id==5)  //USED EQUIPMENT			// container wise separate
			{
				// CRANE_ABOVE_10_TON
				$strUsedEquipmentCharge="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
				values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',17)),3,17)";
				$statUsedEquipmentCharge=$this->bm->dataInsertDB1($strUsedEquipmentCharge);
			}
			else
			{
				$strDelUsedEquipmentCharge="delete from shed_bill_tarrif_draft where rotation='$imp_rot' AND bl_no='$bl_no' AND event_type in (13,14,15,16,17)";
				$statDelUsedEquipmentCharge=$this->bm->dataInsertDB1($strDelUsedEquipmentCharge);
			}
			
			// slab - start
			$getDateDiffQuery= "SELECT IFNULL(DATEDIFF('$uptoDt',DATE_ADD('$unstfDt',INTERVAL 4 day)),0) as dif";
			
			$getDateDiff = $this->bm->dataSelectDb1($getDateDiffQuery);
			$dateDiffValue=$getDateDiff[0]['dif'];
			
			for($i=0;$i<count($conStatus);$i++)
			{
				$cont_size = $conStatus[$i]['cont_size'];
				$cont_height = $conStatus[$i]['cont_height'];
				
				if($dateDiffValue>20)		// over 20 days				// container wise separate
				{
					if($cont_size==20)
					{
						// 24 - 1-7
						$strStorage1stSlab20="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',24)),3,24)";
						$statStorage1stSlab20=$this->bm->dataInsertDB1($strStorage1stSlab20);
						
						// 25 - 8-20
						$strStorage2ndSlab20="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',25)),3,25)";
						$statStorage2ndSlab20=$this->bm->dataInsertDB1($strStorage2ndSlab20);
						
						// 26 - over 20
						$strStorage3rdSlab20="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',26)),3,26)";
						$statStorage3rdSlab20=$this->bm->dataInsertDB1($strStorage3rdSlab20);
					}
				//	else if($cont_size==40 and $cont_height==8.6)
					else if($cont_size==40)
					{
						//  27 - 1-7
						$strStorage1stSlab40="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',27)),3,27)";
						$statStorage1stSlab40=$this->bm->dataInsertDB1($strStorage1stSlab40);
						
						// 28 - 8-20
						$strStorage2ndSlab40="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',28)),3,28)";
						$statStorage2ndSlab40=$this->bm->dataInsertDB1($strStorage2ndSlab40);
						
						// 29 - over 20
						$strStorage3rdSlab40="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',29)),3,29)";
						$statStorage3rdSlab40=$this->bm->dataInsertDB1($strStorage3rdSlab40);
					}
					else if($cont_size==45)
					{
						// 30 - 1-7
						$strStorage1stSlab45="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',30)),3,30)";
						$statStorage1stSlab45=$this->bm->dataInsertDB1($strStorage1stSlab45);
						
						// 31 - 8-20
						$strStorage2ndSlab45="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',31)),3,31)";
						$statStorage2ndSlab45=$this->bm->dataInsertDB1($strStorage2ndSlab45);
						
						// 32 - over 20
						$strStorage3rdSlab45="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',32)),3,32)";
						$statStorage3rdSlab45=$this->bm->dataInsertDB1($strStorage3rdSlab45);
					}

				}
				else if($dateDiffValue>7 and $dateDiffValue<=20)		// 8 to 20 days
				{
					if($cont_size==20)
					{
						// 24 - 1-7
						$strStorage1stSlab20="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',24)),3,24)";
						$statStorage1stSlab20=$this->bm->dataInsertDB1($strStorage1stSlab20);
						
						// 25 - 8-20
						$strStorage2ndSlab20="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',25)),3,25)";
						$statStorage2ndSlab20=$this->bm->dataInsertDB1($strStorage2ndSlab20);
					}
				//	else if($cont_size==40 and $cont_height==8.6)
					else if($cont_size==40)
					{
						//  27 - 1-7
						$strStorage1stSlab40="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',27)),3,27)";
						$statStorage1stSlab40=$this->bm->dataInsertDB1($strStorage1stSlab40);
						
						// 28 - 8-20
						$strStorage2ndSlab40="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',28)),3,28)";
						$statStorage2ndSlab40=$this->bm->dataInsertDB1($strStorage2ndSlab40);
					}
					else if($cont_size==45)
					{
						// 30 - 1-7
						$strStorage1stSlab45="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',30)),3,30)";
						$statStorage1stSlab45=$this->bm->dataInsertDB1($strStorage1stSlab45);
						
						// 31 - 8-20
						$strStorage2ndSlab45="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',31)),3,31)";
						$statStorage2ndSlab45=$this->bm->dataInsertDB1($strStorage2ndSlab45);
					}

				}
				else if($dateDiffValue>0 and $dateDiffValue<=7)			// 1 to 7 days
				{	
					if($cont_size==20)
					{
						// 24 - 1-7
						$strStorage1stSlab20="replace into shed_bill_tarrif_draft(rotation, bl_no,tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',24)),3,24)";
						$statStorage1stSlab20=$this->bm->dataInsertDB1($strStorage1stSlab20);
					}
				//	else if($cont_size==40 and $cont_height==8.6)
					else if($cont_size==40)
					{
						//  27 - 1-7
						$strStorage1stSlab40="replace into shed_bill_tarrif_draft(rotation, bl_no, tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',27)),3,27)";
						$statStorage1stSlab40=$this->bm->dataInsertDB1($strStorage1stSlab40);
					}
					else if($cont_size==45)
					{
						// 30 - 1-7
						$strStorage1stSlab45="replace into shed_bill_tarrif_draft(rotation, bl_no, tarrif_id,billType,event_type) 
						values('$imp_rot','$bl_no',(select get_shed_draft_bill_tarrif_FCL('$imp_rot','$bl_no',30)),3,30)";
						$statStorage1stSlab45=$this->bm->dataInsertDB1($strStorage1stSlab45);
					}
					// else if($cont_size==40 and $cont_height==9.6)
					
				}
			}		// for($i=0;$i<count($conStatus);$i++) - loop ends
			
			
			// slab - end
		}
	}
	

//Bill Tariff - start

function billTariff()
{
	$session_id = $this->session->userdata('value');
	$LoginStat = $this->session->userdata('LoginStat');
	
	if($LoginStat!="yes") 
	{
		$this->logout();
	}
	else
	{
		$data['title'] = "Bill Tariff...";
		$data['msg'] = "";					
		
		$this->load->view('cssAssets');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('billTariff',$data);
		$this->load->view('jsAssets');
	}
}

function getBillTariff()
{		// kawsar - to be merged
	if(isset($_POST['save']))
	{	
		// $session_id = $this->session->userdata('value');
		// if ($session_id != $this->session->userdata('session_id')) 
		// {
			// $this->logout();
		// }
		// else
		// {
		
			$string = $this->input->post('id');
		
			$id = $string;

			$desc = $this->input->post('desc');
	
	
			$ldesc = $this->input->post('ldesc');
			$glcode = $this->input->post('glcode');
			$bill_type = $this->input->post('bill_type');

			$login_id = $this->session->userdata('login_id');

			$check_query = "SELECT COUNT(*) AS rtnValue FROM bil_tariffs WHERE id = '$id'";
			$check_rslt = $this->bm->dataReturnDB1($check_query);
			
			if($check_rslt == 0)
			{
			
			$sql = "INSERT INTO bil_tariffs(id,description,long_description,gl_code,bill_type,created,creator)
			VALUES('$id','$desc','$ldesc','$glcode','$bill_type',NOW(),'$login_id')";

			$result = $this->bm->dataInsertDB1($sql);
			if($result)
			{
				$msg="<font color='green'>Tarrif successfully inserted</font>";
			}
			else
			{
				$msg="<font color='red'>Tarrif failed to insert</font>";
			}

			}
			else{
				$msg="<font color='red'>Duplicate entry</font>";
			}
			$data['title'] = "Bill Tariff...";
			$data['msg'] = $msg;				
			$data['flag'] = 0;	
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('billTariff',$data);
			$this->load->view('jsAssets');
	}

	// }
	else if($_POST['update'])
	{


		 $string = $this->input->post('id');
		
		 $id = $string;
		$desc = $this->input->post('desc');
		 $ldesc = $this->input->post('ldesc');
		 $glcode = $this->input->post('glcode');
		 $bill_type = $this->input->post('bill_type');
	
		 $login_id = $this->session->userdata('login_id');
		 $gkey = $this->input->post('gkey');
		
		 $sql = "UPDATE bil_tariffs SET id='$id',description = '$desc',long_description = '$ldesc', gl_code = '$glcode',bill_type='$bill_type',created='NOW()',creator='$login_id' WHERE gkey = '$gkey'";
		
		 $result = $this->bm->dataUpdateDB1($sql);
		if($result)
		{
			$msg="<font color='green'>Tarrif successfully updated</font>";
		}
		else
		{
			$msg="<font color='red'>Tarrif failed to update</font>";
		}
		$data['title'] = "Bill Tariff...";
		$data['msg'] = $msg;
		
		$this->load->view('cssAssets');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('billTariff',$data);
		$this->load->view('jsAssets');
	}
	
}

function billTariffRate()
{
	$session_id = $this->session->userdata('value');
	$LoginStat = $this->session->userdata('LoginStat');
	
	if($LoginStat!="yes") 
	{
		$this->logout();
	}
	else
	{
		$data['title'] = "Bill Tariff Rate...";
		$data['msg'] = "";
		
		$this->load->view('cssAssets');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('billTariffRate',$data);
		$this->load->view('jsAssets');
	}        
}

function billTarriffRateAction()		// kawsar - to be merged
{
	if(isset($_POST['save']))
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
	
		if($LoginStat!="yes") 
		{
			$this->logout();
		}
		else
		{
			$bill_type=$this->input->post('bill_type');
			$id=$this->input->post('id');
			$rate_type=$this->input->post('rate_type');
			$currency=$this->input->post('currency');
			$amount=$this->input->post('amount');
			$effective_date = $this->input->post('effective_date');

			$login_id = $this->session->userdata('login_id');
			
			$sql_insertBillTarrifRate="INSERT INTO bil_tariff_rates(effective_date,rate_type,amount,currency_gkey,tariff_gkey,created,creator)
			VALUES('$effective_date','$rate_type','$amount','$currency','$id',NOW(),'$login_id')";
			$result = $this->bm->dataInsertDB1($sql_insertBillTarrifRate);
			if($result)
			{
				$msg="<font color='green'>Tarrif rate successfully inserted</font>";
			}
			else
			{
				$msg="<font color='red'>Tarrif rate failed to insert</font>";
			}
			
			$data['title'] = "Bill Tariff Rate...";
			$data['msg'] = $msg;
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('billTariffRate',$data);
			$this->load->view('jsAssets');
		}		
	}
	else if($_POST['update'])
	{
		//$bill_type=$this->input->post('bill_type');
		//$id=$this->input->post('id');
		$rate_type=$this->input->post('rate_type');
		$currency=$this->input->post('currency');
		$amount=$this->input->post('amount');
		$effective_date = $this->input->post('effective_date');
		$login_id = $this->session->userdata('login_id');
		$tariff_gkey = $this->input->post('gkey');
		
		$sql_insertBillTarrifRate="UPDATE bil_tariff_rates SET rate_type = '$rate_type',currency = '$currency', effective_date = '$effective_date', amount = '$amount', changed = 'NOW()', changer = '$login_id' WHERE tariff_gkey = '$tariff_gkey'";
		$result = $this->bm->dataUpdateDB1($sql_insertBillTarrifRate);
		if($result)
		{
			$msg="<font color='green'>Tarrif rate successfully updated</font>";
		}
		else
		{
			$msg="<font color='red'>Tarrif rate failed to update</font>";
		}
		
		$data['title'] = "Bill Tariff Rate...";
		$data['msg'] = $msg;
		
		$this->load->view('cssAssets');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('billTariffRate',$data);
		$this->load->view('jsAssets');
	}

}

function billTariffList()
{		
	// kawsar - to be merged
	$sql = "SELECT bil_tariffs.gkey,bil_tariffs.id,bil_tariffs.description,bil_tariffs.long_description,bil_tariffs.gl_code,bil_tariffs.bill_type
	FROM bil_tariffs Left JOIN bil_tariff_rates ON bil_tariffs.gkey = bil_tariff_rates.tariff_gkey";
	$result = $this->bm->dataSelectDb1($sql);
	
	$data['result'] = $result;
	$data['title'] = "Bill Tarrif List...";


	$this->load->view('cssAssetsList');
	$this->load->view('headerTop');
	$this->load->view('sidebar');
	$this->load->view('billTariffList',$data);
	$this->load->view('jsAssetsList');
	
}

function billTariffAction()
{		// kawsar - to be merged
	$data['msg']="";
		
	if(isset($_POST['delete']))
	{
		$key = $_POST['gkey'];		
		$deleteSql="DELETE FROM bil_tariffs WHERE bil_tariffs.gkey='$key'";
		$deleteStat=$this->bm->dataDeleteDb1($deleteSql);
		$data['msg']="<font color='red'><b>Tariff Data Deleted Successful.</b></font>";
		
		$sql = "SELECT bil_tariffs.gkey,bil_tariffs.id,bil_tariffs.description,bil_tariffs.long_description,bil_tariffs.gl_code,bil_tariffs.bill_type
		FROM bil_tariffs Left JOIN bil_tariff_rates ON bil_tariffs.gkey = bil_tariff_rates.tariff_gkey";
		$result = $this->bm->dataSelectDb1($sql);
		
		$data['result'] = $result;

		$this->load->view('cssAssetsList');
		$this->load->view('headerTop');
		$this->load->view('sidebar');
		$this->load->view('billTariffList',$data);
		$this->load->view('jsAssetsList');
		//redirect('ShedBillController/billTarifList/', 'refresh',$data);

	}
	else if(isset($_POST['editTarrif']))
	{
		$session_id = $this->session->userdata('value');
		$id=$this->input->post('id');
		$desc=$this->input->post('desc');
		$ldesc=$this->input->post('ldesc');
		$glcode=$this->input->post('glcode');
		$bill_type=$this->input->post('bill_type');
		
		
		
		

		if ($session_id != $this->session->userdata('session_id')) 
		{
			$this->logout();
		}
		else
		{	
			$key = $_POST['gkey'];
			$sql = "SELECT gkey,id,description,long_description,gl_code,bill_type FROM bil_tariffs WHERE gkey = '$key'";
			$result = $this->bm->dataSelectDb1($sql);


			// $sql_insertBillTarrifRate="UPDATE bil_tariffs SET id = '$id',description = '$desc', long_description = '$ldesc', gl_code = '$glcode', bill_type = '$bill_type', changed = 'NOW()' WHERE gkey = '$key'";
			// $result1 = $this->bm->dataUpdateDB1($sql_insertBillTarrifRate);
			// if($result1)
			// {
			// 	$msg="<font color='green'>Bill Tarrif successfully updated</font>";
			// }
			// else
			// {
			// 	$msg="<font color='red'>Bill Tarrif failed to update</font>";
			// }
			
		
			// $data['msg'] = $msg;

			$data['title'] = "Edit Bill Tariff...";

			$data['result'] = $result;
			// $data['result1'] = $result1;
							
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('billTariff',$data);
			$this->load->view('jsAssets');		
		}
	}
}

function rateList()
{		
	// kawsar - to be merged
	$sql = "SELECT bil_tariffs.gkey,bil_tariffs.id,bil_tariff_rates.amount
	FROM bil_tariffs INNER JOIN bil_tariff_rates ON bil_tariffs.gkey = bil_tariff_rates.tariff_gkey";
	$result = $this->bm->dataSelectDb1($sql);
	// echo "<pre>";
	// var_dump($result);
	// echo "</pre>";
	$data['result'] = $result;
	$data['title'] = "Rate List...";
	
	$this->load->view('cssAssetsList');
	$this->load->view('headerTop');
	$this->load->view('sidebar');
	$this->load->view('rateList',$data);
	$this->load->view('jsAssetsList');

}

function rateAction()
{		// kawsar - to be merged
	if(isset($_POST['editRate']))
	{
		$session_id = $this->session->userdata('value');
		$LoginStat = $this->session->userdata('LoginStat');
	
		if($LoginStat!="yes") 
		{
			$this->logout();
		}
		else
		{
			$key = $_POST['gkey'];
			$sql = "SELECT bil_tariff_rates.effective_date,bil_tariff_rates.rate_type,bil_tariff_rates.amount,bil_tariff_rates.currency_gkey,bil_tariff_rates.tariff_gkey,bil_tariffs.id,bil_tariffs.bill_type,bil_tariffs.gkey FROM bil_tariff_rates INNER JOIN bil_tariffs WHERE bil_tariff_rates.tariff_gkey = bil_tariffs.gkey AND tariff_gkey = '$key'";
			$result = $this->bm->dataSelectDb1($sql);
			$data['result'] = $result;
			$data['title'] = "Edit Bill Tariff Rate...";
			$data['msg'] = "";				
			
			$this->load->view('cssAssets');
			$this->load->view('headerTop');
			$this->load->view('sidebar');
			$this->load->view('billTariffRate',$data);
			$this->load->view('jsAssets');
		} 
	}
}

// Bill Tariff - end


}
?>