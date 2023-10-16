<?php

class BBAjaxController extends CI_Controller {
	function __construct()
	{
	    parent::__construct();	
            $this->load->library(array('session', 'form_validation'));
            $this->load->model(array('CI_auth', 'CI_menu'));
            $this->load->helper(array('html','form', 'url'));
			$this->load->driver('cache');
			//$this->load->model('ci_auth', 'bm', TRUE);
            $this->oracledb=$this->load->database('second', TRUE);
			
			header("cache-Control: no-store, no-cache, must-revalidate");
			header("cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");
			header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
			
	}	


    function searchBL(){
        $keyword= $_GET["bl_no"];
        $CI = &get_instance();
        $this->db2 = $CI->load->database('second', TRUE);
       
        $query=$this->db2->query("SELECT CRG_BILLS_OF_LADING.NBR AS BL,CRG_BL_ITEM.WEIGHT_TOTAL_KG as TOTAL_WAIGHT,CRG_BL_ITEM.QUANTITY as TOTAL_QTY,
        argo_carrier_visit.id as IBACTUALVISIT,REF_BIZUNIT_SCOPED.ID AS AGENT_CODE
        FROM CRG_BILLS_OF_LADING 
        INNER JOIN CRG_BL_ITEM ON CRG_BL_ITEM.BL_GKEY = CRG_BILLS_OF_LADING.GKEY
        INNER JOIN INV_GOODS  ON CRG_BILLS_OF_LADING.NBR = INV_GOODS.bl_nbr
        INNER JOIN INV_UNIT ON INV_GOODS.GKEY = INV_UNIT.GOODS
        INNER JOIN INV_UNIT_FCY_VISIT  ON INV_UNIT_FCY_VISIT.UNIT_GKEY = INV_UNIT.gkey
        INNER JOIN REF_BIZUNIT_SCOPED ON REF_BIZUNIT_SCOPED.GKEY = INV_UNIT.LINE_OP
        INNER JOIN argo_carrier_visit ON (argo_carrier_visit.gkey=inv_unit.declrd_ib_cv)
        WHERE CRG_BILLS_OF_LADING.NBR='$keyword' fetch FIRST 1 rows only");
         echo "Called Ajax Controller".$query;
       // echo  json_encode($query);
        //return $query->result_array();
        echo json_encode($query);
    }
}