<?php
if(isset($_POST["apphh"])) {
	$apphh = $_POST["apphh"];
	$kwh = $_POST["kwh"];
	$datum = date('Y-m-d');
	$tijd = date("H"); //date('H:i:s');
	
	$huishoudenId = $_SESSION["user"]["huishouden_id"];
	
	$query = "INSERT INTO `meting`(`app_hh`, `datum`, `tijd`, `waarde`) VALUES('$apphh', '$datum', '$tijd', '$kwh')";
	$result = mysqli_query($conn, $query) or die(mysqli_error($conn));
	
	$msg = "Meting is toegevoegd";
	
}
?>
<a id="back-button" href="?p=menu"><div class="back-button">Terug naar menu</div></a><br />
<?php
if(isset($msg)) echo "<p>$msg</p>";
?>
<h3>Registreer moment van energieproductie</h3>
<form method="POST">
	<table>
		<tr>
			<td>Apparaat</td>
			<td>
				<select name="apphh">
			<?php
				$huishoudenId = $_SESSION["user"]["huishouden_id"];
				$result = mysqli_query($conn, "SELECT * FROM `apparaat_huishouden` WHERE `huishouden_fk`='$huishoudenId';");
				while($app = mysqli_fetch_array($result)) {
					echo "<option value='".$app["id"]."'>".$app["omschr"]."</option>";
				}
			?>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				Productie Kw/h
			</td>
			<td>
				<input type="text" name="kwh" />
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<input type="submit" value="Opslaan" />
			</td>
		</tr>
	</table>
</form>