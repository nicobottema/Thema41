<?php
session_start();
include("include/config.inc.php");
$page = "login";
if(isset($_SESSION["user"])) {
	$page = "menu";
	if(isset($_GET["p"])) {
		switch($_GET["p"]) {
			case "menu":
			case "regapp":
			case "regprod":
			case "tabel":
			case "compprov":
				$page = $_GET["p"];
				break;
			case "logout":
				session_unset();
				$page = "login";
		}
	}
} else if(isset($_GET["p"]) && $_GET["p"] == "register") {
	$page = $_GET["p"];
}
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/style.css" />
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<script src="//code.jquery.com/jquery-1.10.2.js"></script>
		<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		<script src="js/chartjs.js"></script>
  
	</head>
	<body>
		<div class="content">
			<div class="container">
				<div class="<?php echo $page; ?>">
					<h3>NRGWeb Management Panel</h3>
					<?php
						include("pages/$page.inc.php");
					?>
				</div>
			</div>
		</div>
	</body>
</html>