<?php
	function randomCode($num){
		$randcode = '';
		$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
		for ($i = 0; $i < $num; $i++ )
			$randcode .= $characters[mt_rand(0, 61)];	
		return $randcode;
	}
?>