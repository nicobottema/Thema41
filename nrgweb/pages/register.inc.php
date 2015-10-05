<?php
if(isset($_POST["gebruikersnaam"])) {
	$gebruikersnaam = $_POST["gebruikersnaam"];
	$wachtwoord = $_POST["wachtwoord"];
	$wachtwoord2 = $_POST["wachtwoord2"];
	$email = $_POST["email"];
	$aantalpersonen = $_POST["aantalpersonen"];
	$voorletters = $_POST["voorletters"];
	$achternaam = $_POST["achternaam"];
	$huisnr = $_POST["huisnr"];
	$postcode = $_POST["postcode"];
	$telnr = $_POST["telnr"];
	
	if($wachtwoord == $wachtwoord2) {
		$wachtwoord = md5($wachtwoord);
		$query = "INSERT INTO `gebruiker`(`username`, `password`,`email`,`aantalpersonen`,`voorletters`,`achternaam`,`huisnr`,`postcode`,`telnr`) VALUES('$gebruikersnaam','$wachtwoord','$email','$aantalpersonen','$voorletters','$achternaam','$huisnr','$postcode','$telnr');";
		$result = mysqli_query($conn, $query);
		$gebruikerId = mysqli_insert_id($conn);
		$query = "INSERT INTO `huishouden`(`gebruiker_id`,`postcode`,`huisnummer`,`grootte`) VALUES('$gebruikerId','$postcode','$huisnr','$aantalpersonen');";
		$result = mysqli_query($conn, $query);
		$huishoudenId = mysqli_insert_id($conn);
		$query = "UPDATE `gebruiker` SET `huishouden_id`='$huishoudenId' WHERE `id`='$gebruikerId';";
		$result = mysqli_query($conn, $query);
		
		$query = "SELECT * FROM `gebruiker` WHERE `username`='$gebruikersnaam' AND `password`='$wachtwoord' LIMIT 1";
		$result = mysqli_query($conn, $query);
	
		$_SESSION["user"] = mysqli_fetch_array($result);
		?>
		<script type="text/javascript">
			location.href="?p=menu";
		</script>
		<?php
	} else {
		$error = "Het herhaalde wachtwoord is niet gelijk";
	}
}
?>
<form method="POST">
<?php
if(isset($error)) {
	echo "<div>$error</div>";
}
?>
<table class="login-table">
	<tr>
		<td colspan=2>
			Registreer een huishouden
		</td>
	</tr>
	<tr>
		<td>
			Gebruikersnaam
		</td>
		<td>
			<input type="text" name="gebruikersnaam" />
		</td>
	</tr>
	<tr>
		<td>
			Wachtwoord
		</td>
		<td>
			<input type="password" name="wachtwoord" />
		</td>
	</tr>
	<tr>
		<td>
			Wachtwoord herhalen
		</td>
		<td>
			<input type="password" name="wachtwoord2" />
		</td>
	</tr>
	<tr>
		<td>
			Email
		</td>
		<td>
			<input type="text" name="email" />
		</td>
	</tr>
	<tr>
		<td>
			Aantal personen
		</td>
		<td>
			<input type="text" name="aantalpersonen" />
		</td>
	</tr>
	<tr>
		<td>
			Voorletter(s)
		</td>
		<td>
			<input type="text" name="voorletters" />
		</td>
	</tr>
	<tr>
		<td>
			Achternaam
		</td>
		<td>
			<input type="text" name="achternaam" />
		</td>
	</tr>
	<tr>
		<td>
			Huisnummer
		</td>
		<td>
			<input type="text" name="huisnr" />
		</td>
	</tr>
	<tr>
		<td>
			Postcode
		</td>
		<td>
			<input type="text" name="postcode" />
		</td>
	</tr>
	<tr>
		<td>
			Tel. nr
		</td>
		<td>
			<input type="text" name="telnr" />
		</td>
	</tr>

	<tr>
		<td colspan=2>
			<input type="submit" value="Registreer" />
		</td>
	</tr>
</table>
<a href="?p=login">Login</a>
</form>