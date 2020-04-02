<?php

class BingoModel extends CI_Model {
    
    public function getOnlineGame(){
        $this->db->where('online',1);
        $query = $this->db->get('Bingo_Job');
        $game_id = 0;
        foreach ($query->result() as $row)
        {
            $game_id = $row->round;
        }
        return $game_id;
    }
    
    public function getValueOngame(){
        $query = $this->db->get('bingo_value');
        return $query->result_array();
    }
    
    public function getSheetbyPlayer($userID,$gameID){
        $this->db->where('job_id',$gameID);
        $this->db->where('code_id',$userID);
        $query = $this->db->get('players');
        $sheetID = 0;
        foreach ($query->result() as $row)
        {
            $sheetID = $row->bimgo_sheet;
        }
        return $sheetID;
    }
    
    
    
    public function getNewSheetID($gameID){
        $sql = "SELECT (IfNull(max(bimgo_sheet),0)) + 1 as sheetID FROM players WHERE job_id ='$gameID' ";
        //echo $sql;
        $query = $this->db->query($sql);
        $sheetID = 0;
        foreach ($query->result() as $row)
        {
            $sheetID = $row->sheetID;
        }
        return $sheetID;
    }
    
    public function getBigoSheet($code_id,$username){
        $gameID = $this->getOnlineGame();
        //$sql = "SELECT * FROM players p JOIN Bingo_Job j on p.job_id = j.job_ID WHERE p.code_id = '$code_id' and j.Online = 1 ";
        $sql = "SELECT * FROM players WHERE code_id = '$code_id' and job_id='$gameID'";
        //echo "<br>".$sql;
        $query = $this->db->query($sql);
        $sheetID = '';
        //มีการกำหนด sheet ให้แล้ว
        if ($query->num_rows() > 0){
            foreach ($query->result() as $row)
            {
                $sheetID = $row->bimgo_sheet;
            }
        }else {
            //ยังไม่กำหนด sheet ให้
            //$sheetID = $this->getNewSheetID($gameID);
            //$sql = "INSERT INTO  players (`id`, `job_id`, `fname`, `code_id`, `bimgo_sheet`) VALUES (NULL,'$gameID','$username','$code_id','$sheetID')";
            $sql = " INSERT INTO players SELECT NULL,$gameID,'$username','$code_id',(IfNull(max(bimgo_sheet),0)) + 1 FROM players; ";
            $this->db->query($sql);
           /*
            $this->db->trans_start(); // Query will be rolled back
            $this->db->select("max(bimgo_sheet) as max");
            $max = $this->db->get("players")->row("max");
            $lastSheet = (empty($max))?1:$max+1;
            $sql = "INSERT INTO  players (id, job_id, fname, code_id, bimgo_sheet) VALUES (NULL,'$gameID','$username','$code_id','$lastSheet')";
            $this->db->query($sql);
            $this->db->trans_complete();
*/
            
            $sql = "SELECT * FROM players WHERE code_id = '$code_id' and job_id='$gameID'";
            $query = $this->db->query($sql);
            foreach ($query->result() as $row)
            {
                $sheetID = $row->bimgo_sheet;
            }
        }
        
        $sheetDetail = $this->getSheetDetailArray($sheetID);
        $result = array();
        $result["status"] = true;
        $result["round"] = $gameID;
        $result["data"] = $sheetDetail;
        
        return $result; 
    }
    
    public function getSheetDetailArray($sheetID){
        $this->db->where('sheet_id', $sheetID);
        $query = $this->db->get('bingo_sheet');
        $sheet = array();
        $i = 0;
        foreach ($query->result() as $row)
        {
            $sheetRow = array("position"=>$row->position, "row"=>$row->rows, "col"=>$row->cols,"data"=>$row->data_bi);
            $sheet[$i] = $sheetRow;
            $i++;
        }
        
        //print_r($sheet);
        return  $sheet;
        
    }
    
    public function getBingoValue(){
        //$gameID = $this->getOnlineGame();
        $this->db->select('value');
        $query = $this->db->get('bingo_value');
        //$values = $query->result_array();
        $values = array();
        $i = 0;
        foreach($query->result() as $row){
            $values[$i] = $row->value;
            $i++;
        }
        return $values;
    }
    
    public function chkAll($code_id,$round,$userdata){
        //ตัวเลขที่ถูกหยิบขึ้นมา
        $bingo_value = $this->getBingoValue();
        //ตรวจสอบตัวเลขที่เลือกมามีการ bingo ที่ case ไหนบ้าง
        $iscase = $this->checkIsCase($userdata);
        
        //ตอนนี้เกมส์ที่เท่าไหร่
        $onlinegame = $this->getOnlineGame();
        //ใช้ bingo sheet ใหน
        $sheetID = $this->getSheetbyPlayer($code_id,$onlinegame);
        //รายละเอียดของ bingo sheet
        $sheetDetail = $this->getSheetDetailArray($sheetID);
        //var_dump($sheetDetail);
        //var_dump($sheetID);
        //var_dump($value_ongame);
        //print_r($iscase);
        $isbingo = false;
        foreach($iscase as $key=>$value){
            if($key == 0){
                $arr = array(0,1,2,3,4);
                if($this->chkInCase1($sheetDetail,$bingo_value,$arr)){
                    $isbingo = true;
                }
            }
            if($key == 1){
                $arr = array(5,6,7,8,9);
                if($this->chkInCase1($sheetDetail,$bingo_value,$arr)){
                    $isbingo = true;
                }
            }
            if($key == 2){
                $arr = array(10,11,13,14);
                if($this->chkInCase1($sheetDetail,$bingo_value,$arr)){
                    $isbingo = true;
                }
            }
            if($key == 3){
                $arr = array(15,16,17,18,19);
                if($this->chkInCase1($sheetDetail,$bingo_value,$arr)){
                    $isbingo = true;
                }
            }
            if($key == 4){
                
                $arr = array(20,21,22,23,24);
                if($this->chkInCase1($sheetDetail,$bingo_value,$arr)){
                    $isbingo = true;
                }
            }
            if($key == 5){
                $arr = array(0,5,10,15,20);
                if($this->chkInCase1($sheetDetail,$bingo_value,$arr)){
                    $isbingo = true;
                }
            }
            if($key == 6){
                $arr = array(1,6,11,16,21);
                if($this->chkInCase1($sheetDetail,$bingo_value,$arr)){
                    $isbingo = true;
                }
            }
            if($key == 7){
                $arr = array(2,7,17,22);
                if($this->chkInCase1($sheetDetail,$bingo_value,$arr)){
                    $isbingo = true;
                }
            }
            if($key == 8){
                
                $arr = array(3,8,13,18,23);
                if($this->chkInCase1($sheetDetail,$bingo_value,$arr)){
                    $isbingo = true;
                }
            }
            if($key == 9){
                
                $arr = array(4,9,14,19,24);
                if($this->chkInCase1($sheetDetail,$bingo_value,$arr)){
                    $isbingo = true;
                }
            }
            if($key == 10){
                
                $arr = array(0,6,18,24);
                if($this->chkInCase1($sheetDetail,$bingo_value,$arr)){
                    $isbingo = true;
                }
            }
            if($key == 11){
                
                $arr = array(4,8,16,20);
                if($this->chkInCase1($sheetDetail,$bingo_value,$arr)){
                    $isbingo = true;
                }
            }
        }
        //$this->chkCase1($userdata, $bingo_value);
        /*
            [0] 0,1,2,3,4
            [1] 5,6,7,8,9
            [2] 10,11,12,13,14
            [3] 15,16,17,18,19
            [4] 20,21,22,23,24
            [5] 0,5,10,15,20
            [6] 1,6,11,16,21
            [7] 2,7,12,17,22
            [8] 3,8,13,18,23
            [9] 4,9,14,19,24
            [10] 0,6,18,12,24
            [11] 4,8,12,16,20
        */
        
        return $isbingo;
    }
    
    public function chkInCase1($sheetDetail,$bingo_value,$arrayCase){
        //[0] 0,1,2,3,4
        $chkIsBingo = true;
        //print_r($bingo_value);
        foreach($arrayCase as $key=>$value){
            //print_r("[$key,$value]");
            //echo "[".$sheetDetail[$value]['data']."]";
            if(!in_array($sheetDetail[$value]['data'],$bingo_value)){
                $chkIsBingo = false;
            }
        }
        return $chkIsBingo;
    }
    
    public function checkIsCase ($userdata){
        
        $isBingoArr = array();
        $count = 0;
        $cases = array();
        //case0 : 0,1,2,3,4
        $cases[0] = array(0,1,2,3,4);
        //case1 : 5,6,7,8,9
        $cases[1] = array(5,6,7,8,9);
        //case2 : 10,11,12,13,14
        $cases[2] = array(10,11,13,14);
        //case3 : 15,16,17,18,19
        $cases[3] = array(15,16,17,18,19);
        //case4 : 20,21,22,23,24
        $cases[4] = array(20,21,22,23,24);
        //case5 : 0,5,10,15,20
        $cases[5] = array(0,5,10,15,20);
        //case6 : 1,6,11,16,21
        $cases[6] = array(1,6,11,16,21);
        //case7 : 2,7,12,17,22
        $cases[7] = array(2,7,17,22);
        //case8 : 3,8,13,18,23
        $cases[8] = array(3,8,13,18,23);
        //case9 : 4,9,14,19,24
        $cases[9] = array(4,9,14,19,24);
        //case10 : 0,6,18,12,24
        $cases[10] = array(0,6,18,24);
        //case11 : 4,8,12,16,20
        $cases[11] = array(4,8,16,20);
        
        //var_dump($cases);
        
        foreach ($cases as $key =>$arrCase){
            $iscase = true;
            foreach ($arrCase as $keys=>$value){
                if(!in_array($value,$userdata)){
                    $iscase = false;
                }
            }
            if($iscase){
                $isBingoArr[$key] = true;
            }
        }
        
        return $isBingoArr;
    }
    public function getresultbingo($round) {
        $this->db->from('bingo_player');
        $this->db->join('players', 'players.code_id = bingo_player.code_id');
        $this->db->where('players.job_id', $round); 
        
        $this->db->order_by('bingo_time', 'ASC');
        
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function getscorebord() {
        $round = $this->getOnlineGame();
        $result = array();
        $result['round'] = $round;
        $resultnumber = $this->getBingoValue();
        $result['resultnumber'] = $resultnumber;
        $resultbingo = $this->getresultbingo($round);
        $result['resultbingo'] = $resultbingo;
        return $result;
        //print_r($result);
    }
    
    public function putaddnumber($number){
        $data = array(
            'value' => $number
        );
        $isinsert = $this->db->insert('bingo_value', $data);
        return array('status'=>true);
    }
    public function putdelnumber($number){
        $this->db->where('value', $number);
        $isinsert = $this->db->delete('bingo_value');
        return array('status'=>true);
    }
    
    
    public function putbingoplayer($code_id){
        $data = array(
            'code_id' => $code_id
        );
        $isinsert = $this->db->insert('bingo_player', $data);
        return array('status'=>true);
    }
}