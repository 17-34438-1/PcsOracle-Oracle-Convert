<?php


class PerformanceReportModel extends CI_Model
{
   
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->oracledb=$this->load->database('fifth', TRUE);
        
        
    }
   

    function GellVesselInformationByDate($date){

        $query=$this->oracledb->query("SELECT argo_quay.id AS berth,VSL_VESSELS.name,REF_BIZUNIT_SCOPED.ID AS AGENT_CODE,to_char(argo_carrier_visit.ata, 'dd-mm-yyyy') as ataDate,to_char(argo_carrier_visit.ata, 'HH:MM') as ataTime,
        to_char(argo_carrier_visit.atd, 'dd-mm-yyyy') as atdDate,to_char(argo_carrier_visit.atd, 'HH:MM') as atdTime,
        NVL(vsl_vessel_visit_details.flex_string03,vsl_vessel_visit_details.flex_string02) AS BERTHOP,INV_UNIT.TIME_DENORM_CALC
         FROM INV_UNIT 
        INNER JOIN argo_carrier_visit on argo_carrier_visit.gkey = inv_unit.declrd_ib_cv
        INNER JOIN vsl_vessel_visit_details on vsl_vessel_visit_details.vvd_gkey = argo_carrier_visit.cvcvd_gkey
        INNER JOIN REF_BIZUNIT_SCOPED ON REF_BIZUNIT_SCOPED.GKEY = INV_UNIT.LINE_OP
        INNER JOIN  VSL_VESSELS  ON VSL_VESSELS.gkey = vsl_vessel_visit_details.vessel_gkey
        INNER JOIN  VSL_VESSEL_CLASSES ON VSL_VESSEL_CLASSES.gkey=VSL_VESSELS.vesclass_gkey
        INNER JOIN vsl_vessel_berthings on vsl_vessel_berthings.vvd_gkey=vsl_vessel_visit_details.vvd_gkey
        INNER JOIN argo_quay ON argo_quay.gkey=vsl_vessel_berthings.quay
        WHERE INV_UNIT.TIME_DENORM_CALC  > to_date(concat('2023-03-24',' 08:00:00'),'YYYY-MM-DD HH24-MI-SS')-1 and INV_UNIT.TIME_DENORM_CALC < to_date(concat('2023-03-24','  08:00:00'),'YYYY-MM-DD HH24-MI-SS')
        AND vsl_vessel_classes.basic_class='BBULK'");
       
    
        return $query->result_array();
    }

}