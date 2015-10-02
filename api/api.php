<?php
session_start();
include("../include/config.inc.php");
if(!isset($_SESSION["user"])) exit();

if(isset($_POST["command"])) {
	$command = $_POST["command"];
	$return = array();
	
	$gebruikPostcode = $_POST["gebruikPostcode"] == "1";
	if($gebruikPostcode) {
		$provinciaal = $_POST["provinciaal"] == "1";
		$vergPostcode = $_POST["vergelijkPostcode"];
		
		if($provinciaal) {
			$query = "SELECT `province` FROM `postcode` WHERE `postcode`='$vergPostcode' LIMIT 1"; 
			$result = mysqli_query($conn, $query) or die(mysqli_error($conn));
			if($pcode = mysqli_fetch_array($result)) {
				$province = $pcode["province"];
			}
		}
	}
		
		
	$teller = array();
	$opgeteld = array();
	
	for($i = 9; $i <= 18; $i++) {
		$teller[$i-9] = 0;
		$opgeteld[$i-9] = 0;
		
		if($command == "vergelijkSoort") {
			$apparaatId = $_POST["soort"];
			$query = "SELECT * FROM `meting` "
			."LEFT JOIN `apparaat_huishouden` ON `meting`.`app_hh`=`apparaat_huishouden`.`id` "
			."LEFT JOIN `apparaat` ON `apparaat`.`id`=`apparaat_huishouden`.`apparaat_fk` "
			."LEFT JOIN `huishouden` ON `huishouden`.`id`=`apparaat_huishouden`.`huishouden_fk` "
			.($gebruikPostcode && $provinciaal ? "LEFT JOIN `postcode` ON `postcode`.`postcode` = `huishouden`.`postcode` " : "")
			."WHERE `apparaat`.`id`='$apparaatId' "
			.($gebruikPostcode ? ($provinciaal ? "AND `postcode`.`province` = '$province' "
				: "AND `huishouden`.`postcode` = '$vergPostcode' ")
			    : "")
			."AND `meting`.`tijd` = '$i'";
		} else if($command == "vergelijkType") {
			$apparaatId = $_POST["soort"];
			$appHh = $_POST["app_hh"];
		
			$query = "SELECT * FROM `apparaat_huishouden` WHERE `id`='$appHh'";
			$result = mysqli_query($conn, $query) or die(mysqli_error($conn));
			$appHh = mysqli_fetch_array($result);
			$merk = $appHh["merk"];
			$typenr = $appHh["typenr"];
			
			$query = "SELECT * FROM `meting` "
			."LEFT JOIN `apparaat_huishouden` ON `meting`.`app_hh`=`apparaat_huishouden`.`id` "
			."LEFT JOIN `apparaat` ON `apparaat`.`id`=`apparaat_huishouden`.`apparaat_fk` "
			."LEFT JOIN `huishouden` ON `huishouden`.`id`=`apparaat_huishouden`.`huishouden_fk` "
			.($gebruikPostcode && $provinciaal ? "LEFT JOIN `postcode` ON `postcode`.`postcode` = `huishouden`.`postcode` " : "")
			."WHERE `apparaat`.`id`='$apparaatId' "
			.($gebruikPostcode ? ($provinciaal ? "AND `postcode`.`province` = '$province' "
				: "AND `huishouden`.`postcode` = '$vergPostcode' ")
			: "")
			."AND `meting`.`tijd` = '$i' "
			."AND `apparaat_huishouden`.`merk`='$merk' AND `apparaat_huishouden`.`typenr`='$typenr'";
		}
	//	echo $query;
		$result = mysqli_query($conn, $query) or die(mysqli_error($conn));
		while($meting = mysqli_fetch_array($result)) {
			$opgeteld[$i-9] += $meting["waarde"];
			$teller[$i-9]++;
		}
		//echo $query;
		if($teller[$i-9] > 0) {
	//		echo $teller[$i-9];
			$gemiddeld = $opgeteld[$i-9] / $teller[$i-9];
			$return["gem_uur".$i] = $gemiddeld;
		}
	}
	
	for($i = 9; $i <= 18; $i++) {
		$teller[$i-9] = 0;
		$opgeteld[$i-9] = 0;
		$query = "SELECT * FROM `meting` "
		."LEFT JOIN `apparaat_huishouden` ON `meting`.`app_hh`=`apparaat_huishouden`.`id` "
		."LEFT JOIN `apparaat` ON `apparaat`.`id`=`apparaat_huishouden`.`apparaat_fk` "
		."LEFT JOIN `huishouden` ON `huishouden`.`id`=`apparaat_huishouden`.`huishouden_fk` "
		."WHERE `apparaat`.`id`='$apparaatId' "
		."AND `huishouden`.`id` = '".$_SESSION["user"]["huishouden_id"]."' "
		."AND `meting`.`tijd` = '$i'";
		$result = mysqli_query($conn, $query) or die(mysqli_error($conn));
		while($meting = mysqli_fetch_array($result)) {
			$opgeteld[$i-9] += $meting["waarde"];
			$teller[$i-9]++;
		}
		//echo $query;
		if($teller[$i-9] > 0) {
			$gemiddeld = $opgeteld[$i-9] / $teller[$i-9];
			$return["gem_eigen_uur".$i] = $gemiddeld;
		}
	}
	
	echo json_encode($return);
}
?>