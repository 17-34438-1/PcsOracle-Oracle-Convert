<?php
error_reporting(E_ALL ^ E_DEPRECATED);
defined('BASEPATH') OR exit('No direct script access allowed');

class FrontEndController extends CI_Controller {

	function __construct()
	{
		parent::__construct();	
        $this->load->library(array('session', 'form_validation'));
        //$this->load->model(array('CI_auth'));
        $this->load->helper(array('html','form', 'url'));
		//$this->load->driver('cache');
		$this->load->model('CI_auth', 'bm', TRUE);
		$this->load->library("pagination");
			
		header("cache-Control: no-store, no-cache, must-revalidate");
		header("cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");			
	}

    function logout(){
        $this->session->sess_destroy();
        $this->cache->clean();
		$this->load->view('FrontEnd/header');
		$this->load->view('FrontEnd/index');
		$this->load->view('FrontEnd/footer');
		$this->db->cache_delete_all();
    }
	
	public function index()
	{
        $this->load->view('FrontEnd/header');
        $this->load->view('FrontEnd/AboutUs');
        $this->load->view('FrontEnd/footer');
	}
	public function AllCourse()
	{
        $this->load->view('FrontEnd/header');
        $this->load->view('FrontEnd/allCourse');
        $this->load->view('FrontEnd/footer');
	}
	public function Blog()
	{
        $this->load->view('FrontEnd/header');
        $this->load->view('FrontEnd/Blog');
        $this->load->view('FrontEnd/footer');
	}
	public function BlogDetails()
	{
        $this->load->view('FrontEnd/header');
        $this->load->view('FrontEnd/BlogDetails');
        $this->load->view('FrontEnd/footer');
	}
	public function ContactUs()
	{
        $this->load->view('FrontEnd/header');
        $this->load->view('FrontEnd/ContactUs');
        $this->load->view('FrontEnd/footer');
	}
	public function CourseDetl()
	{
        $this->load->view('FrontEnd/header');
        $this->load->view('FrontEnd/CourseDetl');
        $this->load->view('FrontEnd/footer');
	}
	public function Gallery()
	{
		$cs = session_id();
        $this->load->view('FrontEnd/header');
        $this->load->view('FrontEnd/gallery',$cs);
        $this->load->view('FrontEnd/footer');
	}
	public function NewsEvents()
	{
        $this->load->view('FrontEnd/header');
        $this->load->view('FrontEnd/NewsEvents');
        $this->load->view('FrontEnd/footer');
	}
	public function Staff()
	{
        $this->load->view('FrontEnd/header');
        $this->load->view('FrontEnd/Staff');
        $this->load->view('FrontEnd/footer');
	}

	public function Dashboard()
	{		
		$org_Type_id = $this->session->userdata('org_Type_id');
		//Menu Expanding....
		$this->session->set_userdata(array('menu' => "Dashboard"));
		$this->session->set_userdata(array('sub_menu' => "Dashboard"));
		//Menu Expanding....
		
		if($org_Type_id=="")
		{
			echo "Invalid Organization";
		}
		else
		{					
			$data['org_Type_id']=$org_Type_id;
			//Code for C&F Assignment List in dashboard starts-----------------
			if($org_Type_id=="2")
			{
				// $login_id = $this->session->userdata('login_id');
				// $org_license = $this->session->userdata('org_license');
				// $session_id = $this->session->userdata('value');
				// $LoginStat = $this->session->userdata('LoginStat');
				
				// //$this->bm->updateAssignmet();
				// $data['msg'] = "";
				// $cond = "";
				// if($this->input->post('search'))
				// {
				// 	$searchBy = $this->input->post('searchBy');
				// 	$searchVal = $this->input->post('searchVal');
					
				// 	if($searchBy == "rotation")
				// 	{
				// 		$cond = " AND rot_no='$searchVal'";
				// 	}
				// 	else if($searchBy == "cont")
				// 	{
				// 		$cond = " AND cont_no='$searchVal'";
				// 	}
				// 	else if($searchBy == "bl")
				// 	{
				// 		$cond = "";
				// 	}				
				// 	else if($searchBy == "beNo")
				// 	{
				// 		$cond = "";
				// 	}
				// 	else if($searchBy == "verificationNo")
				// 	{
				// 		$cond = "";
				// 	}					
				// }
				
				// $sql_assignmentList = "";				
				// $sql_assignmentList = "SELECT cont_no,rot_no,mlo,cf_name AS cf,cont_status,size,ROUND(height,1) AS height,slot AS carrentPosition,Yard_No,Block_No,mfdch_value,assignmentDate,custom_remarks 
				// FROM ctmsmis.tmp_oracle_assignment 
				// WHERE Block_No = 'NCY' AND cf_lic='$org_license' AND assignmentDate>=DATE(NOW()) ".$cond." ORDER BY assignmentDate DESC";
				
				// $rslt_assignmentList=$this->bm->dataSelect($sql_assignmentList);			
				// $data['rslt_assignmentList']=$rslt_assignmentList;
				// $data['org_Type_id']=$org_Type_id;
				// $data['title']="Assignment List";
				// $this->load->view('cssAssetsList');
				// $this->load->view('headerTop');
				// $this->load->view('sidebar');
				// $this->load->view('dashboard',$data);
				// $this->load->view('jsAssetsList');

				$login_id = $this->session->userdata('login_id');
				$org_license = $this->session->userdata('org_license');
				$session_id = $this->session->userdata('value');
				$LoginStat = $this->session->userdata('LoginStat');
				$org_Type_id = $this->session->userdata('org_Type_id');
				
				if($LoginStat!="yes")
				{
					$this->logout();
				}
				else
				{			
					// $this->bm->updateAssignmet();
					$data['msg'] = "";
					$cond = "";
					if($this->input->post('search'))
					{
						$searchBy = $this->input->post('searchBy');
						$searchVal = $this->input->post('searchVal');
						
						if($searchBy == "rotation")
						{
							$cond = " AND rot_no='$searchVal'";
						}
						else if($searchBy == "cont")
						{
							$cond = " AND cont_no='$searchVal'";
						}
						else if($searchBy == "bl")
						{
							$cond = "";
						}				
						else if($searchBy == "beNo")
						{
							$cond = "";
						}
						else if($searchBy == "verificationNo")
						{
							$cond = "";
						}					
					}
					
					$sql_assignmentList = "";
					if($org_Type_id==2)		// c&F
					{
						// $sql_assignmentList = "SELECT cont_no,rot_no,mlo,cf_name AS cf,cont_status,size,ROUND(height,1) AS height,Yard_no AS carrentPosition,Yard_No,mfdch_value
						// FROM ctmsmis.tmp_oracle_assignment
						// WHERE cf_lic='$org_license'".$cond;
						$sql_assignmentList = "SELECT cont_no,rot_no,mlo,cf_name AS cf,cont_status,size,ROUND(height,1) AS height,slot AS carrentPosition,Yard_No,Block_No,mfdch_value,assignmentDate,custom_remarks,unit_gkey 
						FROM ctmsmis.tmp_oracle_assignment 
						WHERE cf_lic='$org_license' AND cf_lic!='' AND assignmentDate>=DATE(NOW()) ".$cond." ORDER BY assignmentDate DESC";

						// $sql_assignmentList = "SELECT cont_no,rot_no,mlo,cf_name AS cf,cont_status,size,ROUND(height,1) AS height,slot AS carrentPosition,Yard_No,Block_No,mfdch_value,assignmentDate,custom_remarks,unit_gkey 
						// FROM ctmsmis.tmp_oracle_assignment 
						// WHERE Block_No = 'NCY' AND cf_lic='$org_license' AND cf_lic!='' AND assignmentDate>=DATE(NOW()) ".$cond." ORDER BY assignmentDate DESC";

					}
					else
					{
						// $sql_assignmentList = "SELECT cont_no,rot_no,mlo,cf_name AS cf,cont_status,size,ROUND(height,1) AS height,Yard_no AS carrentPosition,Yard_No,mfdch_value
						// FROM ctmsmis.tmp_oracle_assignment
						// WHERE Block_No = 'NCY'".$cond;
						
						$sql_assignmentList = "SELECT cont_no,rot_no,mlo,cf_name AS cf,cont_status,size,ROUND(height,1) AS height,slot AS carrentPosition,Yard_No,Block_No,mfdch_value,assignmentDate, custom_remarks
						FROM ctmsmis.tmp_oracle_assignment
						WHERE Block_No = 'NCY'".$cond." ORDER BY assignmentDate DESC";
					}
					
					$rslt_assignmentList=$this->bm->dataSelect($sql_assignmentList);	
				// 2021-04-21 - start	- work on custom_remarks
					if(count($rslt_assignmentList)==0)
					{
						$sql_cnfGkey = "SELECT gkey AS rtnValue FROM ref_bizunit_scoped WHERE id='$org_license'";
						$cnfGkey = $this->bm->dataReturn($sql_cnfGkey);										
						
						$sql_assignmentList="SELECT DISTINCT a.gkey AS unit_gkey,a.id AS cont_no,k.name  AS cnf,a.freight_kind AS cont_status,
						(SELECT ib_vyg FROM sparcsn4.argo_carrier_visit
						INNER JOIN sparcsn4.vsl_vessel_visit_details ON sparcsn4.vsl_vessel_visit_details.vvd_gkey=sparcsn4.argo_carrier_visit.cvcvd_gkey
						WHERE sparcsn4.argo_carrier_visit.gkey=b.actual_ib_cv
						) AS rot_no,
						CONCAT(k.address_line1,k.address_line2) AS cnf_addr,
						b.flex_date01,
						(SELECT RIGHT(sparcsn4.ref_equip_type.nominal_length,2) FROM sparcsn4.inv_unit_equip
						INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey 
						INNER JOIN sparcsn4.ref_equip_type ON sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey
						WHERE sparcsn4.inv_unit_equip.unit_gkey=a.gkey
						)  AS size,
						(SELECT RIGHT(sparcsn4.ref_equip_type.nominal_height,2) FROM sparcsn4.inv_unit_equip
						INNER JOIN sparcsn4.ref_equipment ON sparcsn4.ref_equipment.gkey=sparcsn4.inv_unit_equip.eq_gkey 
						INNER JOIN sparcsn4.ref_equip_type ON sparcsn4.ref_equip_type.gkey=sparcsn4.ref_equipment.eqtyp_gkey
						WHERE sparcsn4.inv_unit_equip.unit_gkey=a.gkey
						)  AS height,
						DATE(b.flex_date01) AS assignmentDate, j.bl_nbr, k.gkey AS bizu_gkey, config_metafield_lov.mfdch_value,
						mfdch_desc,'' AS custom_remarks 
						FROM sparcsn4.inv_unit a
						INNER JOIN sparcsn4.inv_unit_fcy_visit b ON b.unit_gkey = a.gkey
						INNER JOIN sparcsn4.inv_goods j ON j.gkey = a.goods
						INNER JOIN sparcsn4.inv_unit_fcy_visit ON sparcsn4.inv_unit_fcy_visit.unit_gkey=a.gkey	
						LEFT JOIN sparcsn4.ref_bizunit_scoped k ON k.gkey = j.consignee_bzu
						INNER JOIN sparcsn4.config_metafield_lov ON a.flex_string01 = config_metafield_lov.mfdch_value	
						WHERE (b.flex_date01 BETWEEN CONCAT(DATE(NOW()),' 00:00:00') AND CONCAT(DATE(NOW()),' 23:59:59')) AND j.consignee_bzu = '$cnfGkey' AND config_metafield_lov.mfdch_value!='CANCEL'";
						$rslt_assignmentList=$this->bm->dataSelect($sql_assignmentList);
					}				
					$data['rslt_assignmentList']=$rslt_assignmentList;
					$data['org_Type_id']=$org_Type_id;
					
					$data['title']="Assignment List";
					$this->load->view('cssAssetsList');
					$this->load->view('headerTop');
					$this->load->view('sidebar');
					$this->load->view('dashboard',$data);
					$this->load->view('jsAssetsList');

				}

			//Code for C&F Assignment List in dashboard ends-------------------   
			}
			else
			{
				$this->load->view('cssAssets');
				$this->load->view('headerTop');
				$this->load->view('sidebar');
				$this->load->view('dashboard',$data);
				$this->load->view('jsAssets');
			}
		}
	}
}
