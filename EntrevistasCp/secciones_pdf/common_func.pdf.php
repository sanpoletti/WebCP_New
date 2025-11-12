<?php
	function printMoney($value) {
		if (!empty($value)) {
			echo getMoneyString($value);
		} 
	}

	function getMoneyString($value) {
		$result ='';
		if (!empty($value) && $value > 0) {
			$result = '$ ' . number_format($value,2);
		}
		return $result; 
	}
	
	function getLimitedLengthString($str, $max) {
		$result = $str;
		global $idx;
		global $feetNotes;
		
		if (strlen($str) > $max) {
			$idx++;
			$result = substr($str, 0, $max) . "... (*$idx)";
			$feetNotes[] = $str;
		}
		
		return $result;
	}
	
	function hasLimitedString() {
		global $feetNotes;
		return (isset($feetNotes) && ! empty($feetNotes) && count($feetNotes) > 0);
	}
	
	function echoFeetNotes() {
		global $feetNotes;
		
		if (hasLimitedString()) {
			echo "<ul style='font-size:60%; list-style-type:none;'>";
			$i=0;
			foreach ($feetNotes as $note) {
				$i++;
				echo "<li><i>(*$i)</i> $note</li>";
			}
			echo '</ul>';
			$feetNotes = '';
		}
	}
	
	function getDateString($value, $showTime=false, $sep='/') {
		$result ='';
		if (!empty($value)) {
			$chrSep = getDateCharSeparator($value);
			if (!empty($chrSep)) {
				$arr = explode($chrSep, $value);
				if (strlen($arr[0]) > 2) {	// yyyy-mm-dd [xx:xx:xx]
					$day = ( $showTime ? $arr[2] : trim(substr($arr[2], 0, 2)) );
					$result = $day . $sep . $arr[1] . $sep . $arr[0];
				} else {					// dd-mm-yyyy [xx:xx:xx]
					$year = ( $showTime ? $arr[2] : trim(substr($arr[2], 0, 2)) );
					$result = $arr[0] . $sep . $arr[1] . $sep . $year;
				}
			}
		}
		return $result; 
	}
	
	function getDateCharSeparator($date) {
		$arrChars = array('/', '-');
		for ($i=0; $i < count($arrChars); $i++) {
			if (strrpos($date, $arrChars[$i])) {
				return $arrChars[$i];
			}
		}
	}
	
	function getApeNom($hdo) {
		return $hdo->getProperty('APELLIDO') . ', ' . $hdo->getProperty('NOMBRE');
	}

	function SI_NO ($campo) {
		return  ($campo == 0)? 'NO': 'SI';
	}
	function printIndiceRowData($title, $rdo) {
	?>
	<tr>
			<td style="text-align: left; border: solid 1px black"><?php echo $title ?></td>
			<td style="text-align: center; border: solid 1px black"><?php printMoney($rdo->getProperty('ITF')) ?></td>
			<td style="text-align: center; border: solid 1px black"><?php printMoney($rdo->getProperty('nlp')) ?></td>
			<td style="text-align: center; border: solid 1px black"><?php printMoney($rdo->getProperty('nlp100')) ?></td>		
			<td style="text-align: center; border: solid 1px black"><?php printMoney($rdo->getProperty('nlp75')) ?></td>
			<td style="text-align: center; border: solid 1px black"><?php printMoney($rdo->getProperty('nlp50')) ?></td>
			<td style="text-align: center; border: solid 1px black"><?php printMoney($rdo->getProperty('nlp25')) ?></td>
			<td style="text-align: center; border: solid 1px black"><?php printMoney($rdo->getProperty('nli')) ?></td>
			<td style="text-align: center; border: solid 1px black"><?php printMoney($rdo->getProperty('nli100')) ?></td>
			<td style="text-align: center; border: solid 1px black"><?php printMoney($rdo->getProperty('nli75')) ?></td>
			<td style="text-align: center; border: solid 1px black"><?php printMoney($rdo->getProperty('nli50')) ?></td>
			<td style="text-align: center; border: solid 1px black"><?php printMoney($rdo->getProperty('nli25')) ?></td>
		</tr>
	<?php
	}
?>