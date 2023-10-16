<?php


class BBImportRotationWiseCargoDischargeModel extends CI_Model
{
   public function __construct() {
       parent::__construct();
       //$this->load->library('session'); 
       $this->load->database();
       $this->oracledb=$this->load->database('fifth', TRUE);
  }
   
   
 

function importRotationWiseCargoDischargeSearch($keyword){


    $query=$this->oracledb->query("SELECT UNIT_NUMBER,BL,AGENT_CODE, 
(SELECT CRG_LOTS.quantity FROM CRG_LOTS where CRG_LOTS.ID = UNIT_NUMBER  fetch FIRST 1 rows only) as LOT_QTY,
(SELECT  CAST(CRG_LOTS.weight_total_kg as INT) as weight_total_kg  FROM CRG_LOTS where CRG_LOTS.ID = UNIT_NUMBER  fetch FIRST 1 rows only) as LOT_WEIGHT
 ,IB_VYG as ROTATION,BOPTR,ATA,ATD,BERTH
 FROM (      
SELECT INV_UNIT.ID as UNIT_NUMBER,
CRG_BILLS_OF_LADING.NBR AS BL,
REF_BIZUNIT_SCOPED.ID AS AGENT_CODE,VSL_VESSEL_VISIT_DETAILS.IB_VYG,
VSL_VESSEL_VISIT_DETAILS.FLEX_STRING02 AS BOPTR,TO_CHAR(ARGO_CARRIER_VISIT.ATA ,'YYYY-MM-DD') as ATA, 
TO_CHAR(ARGO_CARRIER_VISIT.ATD ,'YYYY-MM-DD') as ATD, POS_SLOT AS BERTH 

FROM CRG_BILLS_OF_LADING 
INNER JOIN CRG_BL_ITEM ON CRG_BL_ITEM.BL_GKEY = CRG_BILLS_OF_LADING.GKEY
INNER JOIN INV_GOODS  ON CRG_BILLS_OF_LADING.NBR = INV_GOODS.bl_nbr
INNER JOIN INV_UNIT ON INV_GOODS.GKEY = INV_UNIT.GOODS
INNER JOIN INV_UNIT_FCY_VISIT  ON INV_UNIT_FCY_VISIT.UNIT_GKEY = INV_UNIT.gkey
INNER JOIN REF_BIZUNIT_SCOPED ON REF_BIZUNIT_SCOPED.GKEY = INV_UNIT.LINE_OP
INNER JOIN argo_carrier_visit ON (argo_carrier_visit.gkey=inv_unit.declrd_ib_cv)
INNER JOIN VSL_VESSEL_VISIT_DETAILS ON argo_carrier_visit.CVCVD_GKEY = VSL_VESSEL_VISIT_DETAILS.vvd_GKEY 
INNER JOIN VSL_VESSEL_BERTHINGS ON VSL_VESSEL_VISIT_DETAILS.VVD_GKEY = VSL_VESSEL_BERTHINGS.VVD_GKEY
WHERE VSL_VESSEL_VISIT_DETAILS.IB_VYG= '$keyword' AND inv_unit_fcy_visit.transit_state !='S20_INBOUND'  ORDER BY CRG_BILLS_OF_LADING.NBR ASC )");
     return $query->result_array();


    }
   function importRotationWiseCargoDischargeSearchIGMData($keyword){


   $query=$this->db->query("SELECT im.Vessel_Name AS VESSEL_NAME,
     idts.Import_Rotation_No AS ROTATION,idts.Line_No AS LINE_NO,idts.BL_No AS MASTER_BL_NO,IFNULL(subdtls.BL_NO,idts.BL_No) AS BL_NO ,idts.Pack_Marks_Number AS PACK_MARKS,idts.Description_of_Goods AS DESC_GOODS, IFNULL(CAST(subdtls.weight AS SIGNED), CAST(idts.weight AS SIGNED)) AS TOTAL_WEIGHT,
      IFNULL(subdtls.ConsigneeDesc,idts.ConsigneeDesc ) AS CONSIGNEE, IFNULL(idts.NotifyDesc,idts.NotifyDesc) AS NOTIFY
     ,idts.Pack_Description AS PACKAGE_TYPE,org.Organization_Name AS SHIPING_AGENT_NAME,org.Agent_Code AS AGENT_CODE,idts.mlocode AS MLO,idts.Pack_Number AS QUENTITY
     FROM  igm_masters im 
     LEFT JOIN igm_details idts ON idts.IGM_id = im.id
     LEFT JOIN igm_supplimentary_detail subdtls ON subdtls.igm_detail_id = idts.id
     LEFT JOIN organization_profiles org ON org.id = im.Submitee_Org_Id
     WHERE idts.Import_Rotation_No='$keyword' ORDER BY ABS(idts.Line_No) ASC");
  
     return $query->result_array();


    }
    
}