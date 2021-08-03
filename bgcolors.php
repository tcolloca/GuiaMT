<?php
	//DUPLICATED IN STYLE

	preg_match('/^\/([^\/]*)/', $_SERVER["REQUEST_URI"], $matches);
$game = substr($matches[0], 1);

	switch($game) {
		case "alphabounce":
			$bgc = "#1B1E39";
			break;
		case "arkadeo":
			$bgc = "#C7C7C7";
			break;
		case "carapass":
			$bgc = "#FECE3C";
			break;
		case "dead_cells":
			$bgc = "#13111B";
			break;
		case "dinorpg":
			$bgc = "#FFFFFF";
			break;
		case 'drakarnage':
			$bgc = "#8C960F";
		case "elbruto":
			$bgc = "#ECAD71";
			break;
		case "fever":
			$bgc = "44271F";
			break;
		case "hammerfest":
			$bgc = "#64AAE5";
			break;
		case "kadokado":
			$bgc = "#48666E";
			break;
		case "kingdom":
			$bgc = "#F2DFB9";
			break;
		case "kube":
			$bgc = "#796788";
			break;
		case "minitroopers":
			$bgc = "#D19A47";
			break;
		case "monstruhotel":
			$bgc = "#616F8F";
			break;
		case "monstruhotel2":
			$bgc = "#21032b";
			break;
		case "mush":
			$bgc = "#0F0F43";
			break;
		case "rockfaller_journey":
			$bgc = "#0D2445";
			break;
		case "snake":
			$bgc = "#A6EA68";
			break;
		case "street_writer":
			$bgc = "#263341";
			break;
		case "teacher_story":
			$bgc = "#f5ece3 ";
			break;
		case "zombinoia":
			$bgc = "#221816";
			break;
		default:
			$bgc = "#2E4967";
			break;
	}
?>