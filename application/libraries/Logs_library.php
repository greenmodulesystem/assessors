<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logs_library{    
	public $User_ID;
	public $Last_name;
	private $Date;
	public $Module;
	public $Table;
	public $Action;
	public $Application_ID;

	private $Folder;
	private $Filename;
	private $File;
	function __construct(){
		date_default_timezone_set('Asia/Manila');
		$this->Folder = '../assessors/application/back up logs/';
		$this->Filename = 'logs.txt';
		$this->File = $this->Folder.$this->Filename;
		if(!file_exists($this->Folder)){
			mkdir($this->Folder,0777,true);
			$myfile = fopen($this->File, "w") or die("Unable to open file!");
		}
	}

    public function record(){
		try{
			if(empty($this->User_ID) || empty($this->Last_name) || empty($this->Module) || empty($this->Table) || empty($this->Action) || empty($this->Application_ID)){
				throw new Exception("Error. missing data.", 1);
			}
			
			$this->Action = strtoupper($this->Action);
			$this->Date = date("Y-m-d H:i:s",time());
			$Handle = fopen($this->File, 'a')  or die("Unable to open file!");
			$Data = "".$this->User_ID.", ".$this->Last_name.", ".$this->Date.", ".$this->Module.", ".$this->Table.", ".$this->Action.", ".$this->Application_ID."\n";
			fwrite($Handle, $Data);
			
			return array('has_error' => false,'message' => 'Logs added..');
		}catch(Exception $e){
			// echo json_encode(array('has_error'=> true , 'error_message' => $e->getMessage()));
			return array('has_error'=> true , 'error_message' => $e->getMessage());
		}
		
	}
	
	public function read(){
		$Handle = fopen($this->File, 'r')  or die("Unable to open file!");
		$content = '';
		if(filesize($this->File) > 0)
			$content = fread($Handle,filesize($this->File));
		echo $content;
		fclose($Handle);
	}
}
?>

