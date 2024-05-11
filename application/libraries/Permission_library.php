<?php

class Permission_library{

    public $Read = false;
    public $Write = false;
    public $Approved = false;

    public function check_permission($user_permissions){
        $results = [];
      
        if($this->Read){
            array_push($results,(bool) $user_permissions->read == $this->Read ? true : false);
        }
        if($this->Write){
            array_push($results,(bool) $user_permissions->write == $this->Write ? true : false);
        }

        if($this->Approved){
            array_push($results,(bool) $user_permissions->approved == $this->Approved ? true : false);
        }
        
        foreach ($results as $key => $value) {
            if(!$value){
                return false;
            }
        }
        return true;
    }
}

?>