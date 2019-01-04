<?php
	// $host = "";
	// $user = "hansen";
	// $pass = "H@nsen!xyz";
	// $db = "adonai_ajk0109";

	$host = "localhost:3361";
	$user = "developer";
	$pass = "devAdonai@17!";
	$db   = "adonai_ajk0109";

	$con = mysqli_connect($host, $user, $pass, $db);
	

	function inputtext($id,$nama,$placeholder="",$required=""){
		return '<div class="form-group">
			        <label class="control-label col-md-2">'.$nama.'</label>
			        <div class="col-md-10">
			          <input id="'.$id.'" name="'.$id.'" class="form-control" type="text" placeholder="'.$placeholder.'" '.$required.'>
			        </div>
			    	</div>';
	}

	function inputradio($id,$nama){
		return '<div class="form-group">
							<label class="control-label col-md-2">'.$nama.'</label>
							<div class="col-md-10 text-center">
								<div class="radio-inline">
									<label>
										<input type="radio" name="'.$id.'" id="'.$id.'" class="radio-onchange" value="Y"> Ya
									</label>
								</div>
								<div class="radio-inline">
									<label>
										<input type="radio" name="'.$id.'" id="'.$id.'" class="radio-onchange" value="T" checked> Tidak															
									</label>
								</div>
							</div>
		        </div>';
	}

	function tohtml($text){
		return str_replace('"', '\"',preg_replace("/\s+|\n+|\r/", ' ', $text));
	}
?>