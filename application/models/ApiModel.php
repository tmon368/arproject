<?php

class ApiModel extends CI_Model {

    public function get_pro_all(){
        //$this->db->where('PRO_START',$gameID);
        //$this->db->where('code_id',$userID);
        $query = $this->db->get('v_promotion_active');
        return $query->result_array();
    }

    public function get_info_by_tagID($id){
        $this->db->where('job_id',$gameID);
        $query = $this->db->get('bingo_value');
        return $query->result_array();
        */
    }
    
    
    
    public function getNewSheetID($gameID){
        
    }
    
    
    public function getSheetDetailArray($sheetID){
        // $this->db->where('sheet_id', $sheetID);
        // $query = $this->db->get('bingo_sheet');
        // $sheet = array();
        // $i = 0;
        // foreach ($query->result() as $row)
        // {
        //     $sheetRow = array("position"=>$row->position, "row"=>$row->rows, "col"=>$row->cols,"data"=>$row->data_bi);
        //     $sheet[$i] = $sheetRow;
        //     $i++;
        // }
        // return  $sheet;
        
    }
    
    public function getBingoValue(){
        // $this->db->select('value');
        // $query = $this->db->get('bingo_value');
        // //$values = $query->result_array();
        // $values = array();
        // $i = 0;
        // foreach($query->result() as $row){
        //     $values[$i] = $row->value;
        //     $i++;
        // }
        // return $values;
    }
}