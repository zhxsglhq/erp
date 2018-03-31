<?php
class core{
	public function sign($ak,$sk){
		if($ak || $sk){
			if(md5(sign($key='accessKey'))==$ak){
				$secretKey = hash("sha512", sign($key='secretKey'));
				if($secretKey==$sk)$result=true;
			}
		}
		if(!$result)$result='';
		return $result;
	}

	public function login(){
		$decrypt=$_POST['ras'];
		$sqlsafe = new sqlsafe();
		$sqlsafe->arrscan(array($decrypt));
		$decrypted=$this->decrypt($decrypt);
		if($decrypted){
			echo json_encode($decrypted['password']);
		}
	}

	public function encrypt($encrypt){
		$rsa = new mycrypt;
		$encrypted=$rsa -> encrypt($encrypt);
		if(!empty($encrypted)){
			$arr = json_encode($encrypted, true);
			echo $arr;
		}
		if(!$encrypt_exist)$encrypt_exist='';
		return $encrypt_exist;
	}

	public function decrypt($decrypt){
		$rsa = new mycrypt;
		$decrypted=$rsa -> decrypt($decrypt);
		if(!empty($decrypted)){
			$arr = json_decode($decrypted, true);
			parse_str($arr,$arr);
			$sqlsafe = new sqlsafe();
			$sqlsafe->arrscan($arr);
			if(array_key_exists("ak",$arr) || array_key_exists("sk",$arr)) {
				//if($this->sign($ak=$arr['ak'],$sk=$arr['sk']);) $encrypt_exist=true;
				$decrypt_exist=$arr;
			}
		}
		if(!$decrypt_exist)$encrypt_exist='';
		return $decrypt_exist;
	}

}
?>
