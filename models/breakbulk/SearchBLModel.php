<?php


class SearchBLModel extends CI_Model
{
   
   public function __construct() {
       parent::__construct();
       $this->load->library('session'); 
       $this->oracledb=$this->load->database('fifth', TRUE);
       $this->load->model('breakbulk/UtilModel');
	   //$this->load->helper('url');
	  //$this->load->helper('file');
       
   }

   function searchBL($keyword){
       
      // $keyword=$this->UtilModel->FormatBLNo($value);
       $query=$this->oracledb->query("SELECT INV_UNIT_FCY_VISIT.LAST_POS_NAME as YARD_POS, CRG_BILLS_OF_LADING.NBR AS BL,CRG_BL_ITEM.WEIGHT_TOTAL_KG as TOTAL_WAIGHT,CRG_BL_ITEM.QUANTITY as TOTAL_QTY,
       argo_carrier_visit.id as IBACTUALVISIT,REF_BIZUNIT_SCOPED.ID AS AGENT_CODE,CRG_BILLS_OF_LADING.CATEGORY
       FROM CRG_BILLS_OF_LADING 
       INNER JOIN CRG_BL_ITEM ON CRG_BL_ITEM.BL_GKEY = CRG_BILLS_OF_LADING.GKEY
       INNER JOIN INV_GOODS  ON CRG_BILLS_OF_LADING.NBR = INV_GOODS.bl_nbr
       INNER JOIN INV_UNIT ON INV_GOODS.GKEY = INV_UNIT.GOODS
       INNER JOIN INV_UNIT_FCY_VISIT  ON INV_UNIT_FCY_VISIT.UNIT_GKEY = INV_UNIT.gkey
       INNER JOIN REF_BIZUNIT_SCOPED ON REF_BIZUNIT_SCOPED.GKEY = INV_UNIT.LINE_OP
       INNER JOIN argo_carrier_visit ON (argo_carrier_visit.gkey=inv_unit.declrd_ib_cv)
       WHERE CRG_BILLS_OF_LADING.NBR like '%$keyword' fetch FIRST 1 rows only");
    
       return $query->result_array();
   }


function searchBLBYState($keyword){
    //$keyword=$this->UtilModel->FormatBLNo($value);
    $query=$this->oracledb->query("SELECT TSTATE,COUNT(ID),SUM(LOT_WEIGHT) as lot_weight,SUM(LOT_QTY) as  LOT_QTY
    FROM (
        SELECT INV_UNIT.ID ,SUBSTR(inv_unit_fcy_visit.transit_state, INSTR(inv_unit_fcy_visit.transit_state, '_')+1) as TSTATE,
        (SELECT CRG_LOTS.pos_name FROM CRG_LOTS where CRG_LOTS.ID = ID fetch FIRST 1 rows only) as POSITION,
        (SELECT NVL(CRG_LOTS.quantity,0) FROM CRG_LOTS where CRG_LOTS.ID = INV_UNIT.ID  fetch FIRST 1 rows only) as LOT_QTY,
        (SELECT CAST(CRG_LOTS.weight_total_kg as INT) as weight_total_kg  FROM CRG_LOTS where CRG_LOTS.ID = INV_UNIT.ID  fetch FIRST 1 rows only) as LOT_WEIGHT,
        INV_UNIT.category, CRG_BILLS_OF_LADING.NBR AS BL,
        validate_conversion(CRG_BL_ITEM.WEIGHT_TOTAL_KG as number)   as TOTAL_WAIGHT,CRG_BL_ITEM.QUANTITY as TOTAL_QTY,
        argo_carrier_visit.id as IBACTUALVISIT,REF_BIZUNIT_SCOPED.ID AS AGENT_CODE
        FROM CRG_BILLS_OF_LADING 
        INNER JOIN CRG_BL_ITEM ON CRG_BL_ITEM.BL_GKEY = CRG_BILLS_OF_LADING.GKEY
        INNER JOIN INV_GOODS  ON CRG_BILLS_OF_LADING.NBR = INV_GOODS.bl_nbr
        INNER JOIN INV_UNIT ON INV_GOODS.GKEY = INV_UNIT.GOODS
        INNER JOIN INV_UNIT_FCY_VISIT  ON INV_UNIT_FCY_VISIT.UNIT_GKEY = INV_UNIT.gkey
        INNER JOIN REF_BIZUNIT_SCOPED ON REF_BIZUNIT_SCOPED.GKEY = INV_UNIT.LINE_OP
        INNER JOIN argo_carrier_visit ON (argo_carrier_visit.gkey=inv_unit.declrd_ib_cv)
        WHERE CRG_BILLS_OF_LADING.NBR like '%$keyword') GROUP BY TSTATE");

    return $query->result_array();
    }

    function searchBLBYShedYard($keyword){
        //$keyword=$this->UtilModel->FormatBLNo($value);
        $query=$this->oracledb->query("SELECT listagg(INV_UNIT_FCY_VISIT.LAST_POS_NAME,',')   WITHIN GROUP (ORDER BY INV_UNIT.ID) as YARD
        FROM CRG_BILLS_OF_LADING 
        INNER JOIN CRG_BL_ITEM ON CRG_BL_ITEM.BL_GKEY = CRG_BILLS_OF_LADING.GKEY
        INNER JOIN INV_GOODS  ON CRG_BILLS_OF_LADING.NBR = INV_GOODS.bl_nbr
        INNER JOIN INV_UNIT ON INV_GOODS.GKEY = INV_UNIT.GOODS
        INNER JOIN INV_UNIT_FCY_VISIT  ON INV_UNIT_FCY_VISIT.UNIT_GKEY = INV_UNIT.gkey
        WHERE CRG_BILLS_OF_LADING.NBR like '%$keyword'  AND SUBSTR(inv_unit_fcy_visit.transit_state, INSTR(inv_unit_fcy_visit.transit_state, '_')+1) = 'YARD'");
    
        return $query->result_array();
        }
}