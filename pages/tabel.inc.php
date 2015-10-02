<a id="back-button" href="?p=menu"><div class="back-button">Terug naar menu</div></a><br />

<h3>Energieproductie overzicht</h3>
<div class="middle">
	<table>
		<tr style="background-color: #ddf">
			<th>Apparaat</th><th>09:00</th><th>10:00</th><th>11:00</th><th>12:00</th><th>13:00</th><th>14:00</th><th>15:00</th><th>16:00</th><th>17:00</th><th>18:00</th>
		</tr>
		<?php
			$teller = array();
			$opgeteld = array();
			$query = "SELECT `omschr`,`id` FROM `apparaat_huishouden` WHERE `huishouden_fk`='".$_SESSION["user"]["huishouden_id"]."'";
			$result = mysqli_query($conn, $query) or die(mysqli_error($conn));
			while($appHh = mysqli_fetch_array($result)) {
				$appHhId = $appHh["id"];
				echo "<tr>";
				echo "<td style='background-color: #ddf'>".$appHh["omschr"]."</td>";
				for($i = 9; $i <= 18; $i++) {
					echo "<td>";
					$teller[$i-9] = 0;
					$opgeteld[$i-9] = 0;
					$query = "SELECT * FROM `meting` "
					."WHERE `app_hh`='$appHhId' "
					."AND `meting`.`tijd` = '$i'";
					$result2 = mysqli_query($conn, $query) or die(mysqli_error($conn));
					while($meting = mysqli_fetch_array($result2)) {
						$opgeteld[$i-9] += $meting["waarde"];
						$teller[$i-9]++;
					}
					//echo $query;
					if($teller[$i-9] > 0) {
						$gemiddeld = $opgeteld[$i-9] / $teller[$i-9];
						echo round($gemiddeld,2);
					}
					echo "</td>";
				}
				
				echo "</tr>";
				
				echo "<tr>";
				echo "<td style='background-color: #ddf'>".$appHh["omschr"]." (Som)</td>";
				$som = 0;
				for($i = 9; $i <= 18; $i++) {
					echo "<td>";
					if($teller[$i-9] > 0) {
						$gemiddeld = $opgeteld[$i-9] / $teller[$i-9];
						$som += $gemiddeld;
						echo round($som, 2);
					}
					echo "</td>";
				}
				echo "</tr>";
			}
		?>
	</table>
</div>