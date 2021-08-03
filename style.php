<?php

	header("Content-type: text/css; charset: UTF-8");

	$cssGame  = $_GET['game'];
	
	$bgColor = "#DDF3FF";
	$color = "#233243";
	$activeLink = "#2C70A7";
	$visitedLink = "#374F68";
	$sideBgColor = "#069";
	$hoverSideBgColor = "#0078B3";
	$sideColor = "#EEE";
	
	switch($cssGame) {
		case "alphabounce":
			$bgColor = "#475682";
			$color = "#C1CEEC";
			$activeLink = "#FFF";
			$visitedLink = "#CCC";
			$sideBgColor = "#373C59";
			$hoverSideBgColor = "#464D73";
			
			$bodyBackground = 
			'background-image: url("http://www.alphabounce.com/img/design/core_planet.jpg");
			background-repeat: no-repeat;
			background-position: bottom right;';
			break;
		case "arkadeo":
			$bgColor = "#FAFAFA";
			$color = "#444444";
			$sideBgColor = "#00C3E7";
			$hoverSideBgColor = "#22DEFF";
			
			$bodyBackground = 
			'background-image: url("http://data.arkadeo.com/img/skin/habillage_top.png"), url("http://data.arkadeo.com/img/skin/habillage_bot.png");
			background-repeat: no-repeat, no-repeat;
			background-position: top, bottom;';
			break;
		case "carapass":
			$bgColor = "#FEF3C4";
			$color = "#3F2914";
			$sideBgColor = "#9EBD24";
			$hoverSideBgColor = "#B0D228";
			
			$bodyBackground = 
			'background-image: url("http://www.carapass.com/img/gradientbg.jpg");
			background-repeat-x: repeat;
			background-repeat-y: no-repeat;
			background-position: top left';
			break;
		case "dead_cells":
			$bgColor = "#1B1421";
			$color = "#DEAD5E";
			$subtitle = "#AF4727";
			$activeLink = "#AF4727";
			$visitedLink = "#D66645";
			$sideBgColor = "#482424";
			$hoverSideBgColor = "#5C2E2E";
			
			$bodyBackground = 
			'background-image: url("http://data.dead-cells.com/img/banner.png"), url("http://data.dead-cells.com/img/footer.png");
			background-repeat: no-repeat, no-repeat;
			background-position: top, bottom;';		
			
			break;
		case "dinorpg":
			$bgColor = "#FBDAA7";
			$color = "#A25933";
			$activeLink = "#BF423C";
			$visitedLink = "#A33732";
			$sideBgColor = "#FF9122";
			$hoverSideBgColor = "#FFA042";
			
			$bodyBackground = 
			'background-image: url("http://data.es.dinorpg.com/img/design/bg_ciel.jpg"), url("http://data.es.dinorpg.com/img/design/sky_footer.jpg");
			background-repeat-x: repeat;
			background-repeat-y: no-repeat;
			background-position: top, bottom;';		
			
			break;
		case "drakarnage":
			$bgColor = "#834334";
			$color = "#FFEDEC";
			$activeLink = "#EBB44F";
			$visitedLink = "#F3D296";
			$sideBgColor = "#59221F";
			$hoverSideBgColor = "#6F2926";
			
			$bodyBackground = 
			'background-image: url("http://data.drakarnage.com/img/bg1.jpg");
			background-repeat-x: no-repeat;
			background-repeat-y: no-repeat;
			background-position: top;';		
			
			break;
		case "elbruto":
			$bgColor = "#F9F0B5";
			$color = "#433232";
			$sideBgColor = "#C58356";
			$hoverSideBgColor = "#CB9169";
			
			$bodyBackground = 
			'background-image: url("http://data.elbruto.muxxu.com/img/head_bg_repeat.gif");
			background-repeat-x: repeat;
			background-repeat-y: no-repeat;
			background-position: top left;';		
			break;
		case "fever":
			$bgColor = "#FFE4AE";
			$color = "#412720";
			$activeLink = "#BF423C";
			$visitedLink = "#A33732";
			$sideBgColor = "#5C3830";
			$hoverSideBgColor = "#6C4239";
			
			$bodyBackground = 
			'background-image: url("http://fever.muxxu.com/img/maincontent.gif");
			background-repeat-x: repeat;
			background-repeat-y: repeat;
			background-position: -13px;';		
			break;
		case "hammerfest":
			$bgColor = "#D5E8F8";
			$color = "#233243";
			$sideBgColor = "#9C68B9";
			$hoverSideBgColor = "#AA7CC2";
			
			$bodyBackground = 
			'background-image: url("http://www.hammerfest.es/img/design/stars.jpg"), url("http://www.hammerfest.es/img/design/landscape.jpg");
			background-repeat-x: repeat;
			background-repeat-y: no-repeat;
			background-position: top, bottom';
			break;
		case "kadokado":
			$bgColor = "#EFF3F4";
			$color = "#3D5E65";
			$sideBgColor = "#8D9C9F";
			$hoverSideBgColor = "#A6B3B5";
			
			$bodyBackground = 
			'background-image: url("http://dat.kadokado.com/gfx/gui/karbon/bg_header.jpg"), url("http://dat.kadokado.com/gfx/gui/karbon/bottomHeader.jpg"), url("http://dat.kadokado.com/gfx/gui/karbon/patternBG.jpg");
			background-repeat: repeat-x, repeat-x, repeat;
			background-position: top, 0px 100px, 0px 114px';
			break;
		case "kingdom":
			$bgColor = "#F9F4E1";
			$color = "#333333";
			$sideBgColor = "#5F5F5D";
			$hoverSideBgColor = "#6D6D6B";
			
			$bodyBackground = 
			'background-image: url("/images/themes/kingdom_top_repeat.png");
			background-repeat: repeat-x;
			background-position: top';
			break;
		case "kube":
			$bgColor = "#B5A9CD";
			$color = "#473D50";
			$sideBgColor = "#4CA5CD";
			$hoverSideBgColor = "#69B5D6";
			break;
		case "minitroopers":
			$bgColor = "#E1E8F0";
			$color = "#2C323A";
			$sideBgColor = "#86A0C0";
			$hoverSideBgColor = "#94ABC7";
			
			$bodyBackground = 
			'background-image: url("http://data.minitroopers.es/img/bg_repeat.jpg");
			background-repeat: repeat;
			background-position: top';
			break;
		case "monstruhotel":
			$bgColor = "#F5ECE0";
			$color = "#424242";
			$activeLink = "#7B716F";
			$visitedLink = "#615A58";
			$sideBgColor = "#B46385";
			$hoverSideBgColor = "#BE7896";
			
			$bodyBackground = 
			'background-image: url("http://data.hotel.es.muxxu.com/img/design/site_bg.png");
			background-repeat: no-repeat;
			background-position: top';
			break;
		case "monstruhotel2":
			$bgColor = "#F1C2FF";
			$color = "#470E51";
			$activeLink = "#840B98";
			$visitedLink = "#9B37AB";
			$sideBgColor = "#401353";
			$hoverSideBgColor = "#592171";
			
			$bodyBackground = 
			'background-image: url("http://data-monsterhotel.twinoid.com/img/design/banner.jpg");
			background-repeat: repeat-x;
			background-position: top';
			break;
		case "mush":
			$bgColor = "#323A74";
			$color = "#CBCDDC";
			$activeLink = "#AAA";
			$visitedLink = "#888";
			$sideBgColor = "#1A66BB";
			$hoverSideBgColor = "#1F75D3";
			
			$bodyBackground = 
			'background-image: url("/images/themes/mush_bg.png");
			background-repeat: repeat-x;
			background-position: top';
			break;
		case "rockfaller_journey":
			$bgColor = "#B1CFF5";
			$color = "#21557C";
			$activeLink = "#126C8C";
			$visitedLink = "#131E2D";
			$sideBgColor = "#1A66BB";
			$hoverSideBgColor = "#17273C";
			
			$bodyBackground = 
			'background: linear-gradient(#0D2445,#3B689A);
			background-image: url("http://data-rockfaller.twinoid.com/img/stars2.png");
			background-repeat: repeat-y;
			background-position: top';
			break;
		case "snake":
			$bgColor = "#D4F5B6";
			$color = "#030";
			$sideBgColor = "#4EA800";
			$hoverSideBgColor = "#57B700";
			
			$bodyBackground = 
			'background-image: url("/images/themes/snake_bg_repeat.png");
			background-repeat: repeat-x;
			background-position: top';
			break;
		case "street_writer":
			$bgColor = "#81A3C5";
			$color = "#EAF0F6";
			$activeLink = "#336984";
			$visitedLink = "#497EA9";
			$sideBgColor = "#466685";
			$hoverSideBgColor = "#4D6F91";
			
			$bodyBackground = 
			'background-image: url("http://data-streetwriter.twinoid.com/img/bgPattern2.png");
			background-repeat: repeat-x;
			background-position: top';
			break;
		case "teacher_story":
			$bgColor = "#FAF4EC";
			$color = "#0D7984";
			$sideBgColor = "#FDE38A";
			$hoverSideBgColor = "#FEEBA9";
			$sideColor = "#999";
	
			$bodyBackground = 
			'background-image: url(), url("http://data.teacher-story.com/img/unified_bg.jpg");
			background-repeat: no-repeat;
			background-position: center';
			break;
		case "zombinoia":
			$bgColor = "#7E4D2A";
			$color = "#D3B98D";
			$activeLink = "#BE8676";
			$visitedLink = "#B67663";
			$sideBgColor = "#C59D62";
			$hoverSideBgColor = "#CEAC79";
			break;
		default:
			$bgColor = "#DDF3FF";
			$color = "#233243";
			$activeLink = "#2C70A7";
			$visitedLink = "#374F68";
			$sideBgColor = "#069";
			$hoverSideBgColor = "#0078B3";
			$sideColor = "#EEE";
			
			$bodyBackground = 
			'background-image: url("/images/themes/main_bg_repeat.png"), url("/images/themes/main_bg_repeat_bot.png");
			background-repeat: repeat-x;
			background-position: top, bottom';
	}
	
	if(!isset($subtitle)) {
		$subtitle = $color;
	}
	
	switch($cssGame) {
		case "alphabounce":
			$bgc = "#1B1E39";
			break;
		case "arkadeo":
			$bgc = "#0093C0";
			break;
		case "carapass":
			$bgc = "#5F9501";
			break;
		case "dead_cells":
			$bgc = "#341B1B";
			break;
		case "dinorpg":
			$bgc = "#D9632B";
			break;
		case 'drakarnage':
			$bgc = "#3D220B";
			break;
		case "elbruto":
			$bgc = "#9D7147";
			break;
		case "fever":
			$bgc = "#44271F";
			break;
		case "hammerfest":
			$bgc = "#8334A9";
			break;
		case "kadokado":
			$bgc = "#6F6F6F";
			break;
		case "kingdom":
			$bgc = "#474747";
			break;
		case "kube":
			$bgc = "#796788";
			break;
		case "minitroopers":
			$bgc = "#6784AD";
			break;
		case "monstruhotel":
			$bgc = "#8E4A66";
			break;
		case "monstruhotel2":
			$bgc = "#21032b";
			break;
		case "mush":
			$bgc = "#0B4A91";
			break;
		case "rockfaller_journey":
			$bgc = "#0D2445";
			break;
		case "snake":
			$bgc = "#009700";
			break;
		case "street_writer":
			$bgc = "#3C5166";
			break;
		case "teacher_story":
			$bgc = "#FBBC61";
			break;
		case "zombinoia":
			$bgc = "#9E773D";
			break;
		default:
			$bgc = "#274161";
			break;
	}		
?>

body {
	padding: 0;
    margin: 0;
	<?php 
	echo $bodyBackground;
	?>
}

#wrapper {
	width: 1100px;
	
	margin: 0 auto;
	padding: 0;
	
	font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
}

#header {
	height: 200px;
	
	margin: 0;
	padding: 0;
}

#gamesbar {
	height: 70px;
	text-align: center;
	
	margin: 0 auto;
	padding: 0;
	
	background-image:url(/images/themes/gamebar.png);
}

#inner-gamesbar {
	display: inline-block;
	height: 30px;

	padding: 20px;
}

inner-gamesbar li {
	height: 30px;
}

#main-block {
	width: 1100px;
	
	margin: 0 auto;
	padding: 0;
}

#content {
	height: 100%;
	width: 950px;
	
	min-height: 650px;
	
	float: right;
	margin: 0;
	padding: 0;
	
	background-color: <?php echo $bgColor; ?>;
	color: <?php echo $color; ?>;
}

#head-content {
	height: 50px;
	
	margin: 0;
	padding: 0;
}

#main-title {
	display: inline-block;
	float: left;
	line-height: 0;
	
	font-size: 25px;
	font-family: "Arial Black", Gadget, sans-serif; 
}

#log {
	display: inline-block;
	float: right;
	
	text-align: right;
	font-size: 14px;
}

hr {
	border-color: #000;
	color: #000;
	background-color: #000;
}

#main-text {
	margin: 0 auto;
	padding: 20px;
}

#sidebar {
	height: 100%;
	width: 146px;
	
	float: left;
	margin: 0;
	padding: 0;
	
	text-align: center;
	
	background-color: <?php echo $sideBgColor; ?>;
	border: solid 2px <?php echo $bgc; ?>;
	border-bottom: none;
}

#sidebar ul {
	display: inline-block;
	
	margin: 0;
	padding: 0;
	
	list-style-type: none;
}

#sidebar li {
	margin: 0;
	padding: 0;
	
	list-style-type: none;
	list-style: none;
}

.side-category {
	width: 146px;
	height: 50px;
	line-height: 50px;
	
	display: inline-block;
	margin-bottom: 10px;
	
	font-size: 15px;
	font-family: Trebuchet MS;
	text-align: center;
	color: #666;
	font-weight: bold;
	
	background-image:url(/images/themes/cloud.png);
}

.mini-text {
	font-size: 10px;
	color: <?php echo $sideColor; ?>;
}

#bottom {
	height: 70px;
	width: 1100px;
	
	display: inline-block;
	
	background-color: <?php echo $sideBgColor; ?>;
	border: solid 2px <?php echo $bgc; ?>;
	border-top: none;
	
	font-weight: bold;
}

#support-links {
	display: inline-block;
	float: left;
	
	padding: 5px;
	margin-left: 30px;
	
	text-align: left;
	font-size: 14px;
	color: <?php echo $sideColor; ?>;
}

#copyright {
	width: 400px;
	
	display: inline-block;
	float: right;
	
	padding: 5px;
}

#support-links a {
	color: <?php echo $sideColor; ?>;
}

subtitle, h1
{
	font-family: "Arial Black", Gadget, sans-serif;
	font-size: 22px;
	font-weight: bold;
	border-bottom-width: 1px;
	border-bottom-style: dotted;
	color: <?php echo $subtitle; ?>;
}

minititle, h2
{
	font-size: 16px;
	font-weight: bold;
	text-decoration:underline;
	font-family: Verdana;	
}	

a:link {
	color: <?php echo $activeLink; ?>;
}

a:active, a:visited {
	color: <?php echo $visitedLink; ?>;
}

/** TABLES **/

table {
	margin: auto;	
}

#userTable {
	width: 740px;
	background-color: #A9C5DA;
	border: 1px solid black;
}
	
#userTable th {
	background-color: #488799;
	border: 1px solid black;
	padding: 5px;
	min-width: 60px;
}

#userTable td {
	padding: 5px;
	text-align: center;
	border: 1px solid black;
}
	
#kube-table {
	width: 900px;
	border: 2px solid  <?php echo $bgc; ?>;
	color: white;
	float: center;
	border-spacing: 0px;
}

#kube-table th {
	text-align: center;
	padding: 5px;
	min-width: 50px;
}

#kube-table th.odd {
	background-color: #7773C1;
}

#kube-table th.even {
	background-color: #7D79C4;
}

#kube-table td {
	padding: 5px;
	text-align: center;
}

#kube-table tr.odd {
	background-color: #7684C7;
}

#kube-table tr.odd > td.odd {
	background-color: #7594CC;
}

#kube-table tr.odd > td.even {
	background-color: #7B98CE;
}

#kube-table tr.even > td.odd {
	background-color: #7684C7;
}

#kube-table tr.even > td.even {
	background-color: #7E8CCB;
}

#kube-table input[type="radio"] {
	width: 15px;
	margin-left: 2px;
}

#kube-table #radios {
	text-align: left;
	margin-bottom: 10px;	
}

#kube-table form {
	font-size: 11px;	
}

#kube-table input[type="image"] {
	margin: auto;
}

#kube-table #tierra-firme {
	margin-top: 10px;	
}

#kube-table #warponia {
    margin-left: 22px;	
}

#kube-table #paraiso {
	margin-left: 10px;	
}

#kube-table #arkubepielago {
	margin-left: 12px;	
}

#distance {
	margin-right: 19px;	
}

#kube-table.entry {
	width: auto;
}

#kube-table.entry th,
#kube-table.entry td {
	padding: 15px;		
}

#hammerfest-table {
	width: 900px;
	color: <?php echo $color; ?>;
	font-size: 12px;
	float: center;
	background-color: #3CB7E6;
	border-spacing: 5px;
    border-collapse: separate;
}

#hammerfest-table th {
	background-color: #91D6F0;
	border: 3px solid #5EC4EA;
	text-align: center;
	font-size: 14px;
	padding: 5px;
}

#hammerfest-table td {
	padding: 5px;
	text-align: center;
	border: 3px solid #A3DDF3;
	background-color: <?php echo $bgColor; ?>;
	min-width: 50px;
}

#mush-table {
	width: 900px;
	border: 2px solid  <?php echo $bgc; ?>;
	color: white;
	float: center;
	border-spacing: 0px;
}

#mush-table th {
	background-color: #1E6FC8;
	text-align: center;
	padding: 5px;
}

#mush-table tr.odd {
	background-color: #448FE3;
}

#mush-table td {
	padding: 5px;
	text-align: center;
}

#mush-table tr.odd > td.odd {
	background-color: #6BA7E9;
}

#mush-table tr.odd > td.even {
	background-color: #76ADEB;
}

#mush-table tr.even > td.odd {
	background-color: #3888E0;
}

#mush-table tr.even > td.even {
	background-color: #448FE3;
}

#snake-table {
	width: 900px;
	border: 2px solid  <?php echo $bgc; ?>;
	color: white;
	float: center;
	border-spacing: 0px;
}

#snake-table th {
	background-color: #55BA12;
	text-align: center;
	padding: 5px;
}

#snake-table tr.odd {
	background-color: #71D72D;
}

#snake-table tr.even {
	background-color: #60CD16;
}

#snake-table td {
	padding: 5px;
	text-align: center;
}

#snake-table tr.odd > td.odd {
	background-color: #71D72D;
}

#snake-table tr.odd > td.even {
	background-color: #7DDB40;
}

#snake-table tr.even > td.odd {
	background-color: #5CC815;
}

#snake-table tr.even > td.even {
	background-color: #60CD16;
}

#main-table {
	width: 900px;
	border: 2px solid  <?php echo $bgc; ?>;
	color: white;
	float: center;
	border-spacing: 0px;
}

#main-table th {
	text-align: center;
	padding: 5px;
	min-width: 50px;
}

#main-table th.odd {
	background-color: #5C84DE;
}

#main-table th.even {
	background-color: #6187E0;
}

#main-table td {
	padding: 5px;
	text-align: center;
}

#main-table tr.odd > td.odd {
	background-color: #80BFE6;
}

#main-table tr.odd > td.even {
	background-color: #95CAEA;
}

#main-table tr.even > td.odd {
	background-color: #62A5EE;
}

#main-table tr.even > td.even {
	background-color: #75AFF0;
}

#writer-table {
	width: 900px;
	border: 2px solid  <?php echo $bgc; ?>;
	color: white;
	float: center;
	border-spacing: 0px;
}

#writer-table th {
	text-align: center;
	padding: 5px;
	min-width: 50px;
}

#writer-table th.odd {
	background-color: #407899;
}

#writer-table th.even {
	background-color: #3F7EA3;
}

#writer-table td {
	padding: 5px;
	text-align: center;
}

#writer-table tr.even > td.odd {
	background-color: #4A85A3;
}

#writer-table tr.even > td.even {
	background-color: #5289A5;
}

#writer-table tr.odd > td.odd {
	background-color: #568EB0;
}

#writer-table tr.odd > td.even {
	background-color: #5E93B3;
}

.alphabounce-table {
	width: 900px;
	border: 2px solid  <?php echo $bgc; ?>;
	color: white;
	float: center;
	border-spacing: 0px;
}

.alphabounce-table th {
	background-color: #1E6FC8;
	text-align: center;
	padding: 5px;
}

.alphabounce-table tr.odd {
	background-color: #448FE3;
}

.alphabounce-table td {
	padding: 5px;
	text-align: center;
	height: 50px;
}

.alphabounce-table tr.odd > td.odd {
	background-color: #6BA7E9;
}

.alphabounce-table tr.odd > td.even {
	background-color: #76ADEB;
}

.alphabounce-table tr.even > td.odd {
	background-color: #3888E0;
}

.alphabounce-table tr.even > td.even {
	background-color: #448FE3;
}

#teacher-story-table {
	width: 900px;
	border: 2px solid #1D4943;
	color: white;
	float: center;
	border-spacing: 0px;
}

#teacher-story-table th {
	text-align: center;
	padding: 5px;
	min-width: 50px;
}

#teacher-story-table th.odd {
	background-color: #2E949E;
}

#teacher-story-table th.even {
	background-color: #339AA5;
}

#teacher-story-table td {
	padding: 5px;
	text-align: center;
    color: #C28834;
}

#teacher-story-table tr.even > td.odd {
	background-color: #FCE7A6;
}

#teacher-story-table tr.even > td.even {
	background-color: #FDECB7;
}

#teacher-story-table tr.odd > td.odd {
	background-color: #F9E8B5;
}

#teacher-story-table tr.odd > td.even {
	background-color: #FCEEC2;
}

/*** OTHER ***/

.center {
	text-align: center;	
}

.ok {
	color: #090;
	font-weight: bold;
	text-align: center;	
}

.error {
	color: #F00;
	font-weight: bold;
	text-align: center;	
}

/*** NAV BARS ***/

/*** ESSENTIAL STYLES ***/

.sf-menu, .sf-menu * {
	margin: 0;
	padding: 0;
	list-style: none;
}

.sf-menu li {
	position: relative;
	min-height: 20px;
}

.sf-menu ul {
	position: absolute;
	display: none;
	top: 100%;
	left: 0;
	z-index: 99;
}

.sf-menu > li {
	height: 30px;
	float: left;
}

.sf-menu li:hover > ul,
.sf-menu li.sfHover > ul {
	display: block;
}

.sf-menu a {
	display: block;
	position: relative;
}

.sf-menu ul ul {
	top: 0;
	left: 100%;
}

/*** DEMO SKIN ***/

.sf-menu {
	float: left;
	margin-bottom: 1em;
}

.sf-menu ul {
	text-align:left;
	font-weight:bold;
	line-height:22px;
	text-indent:10px;
	font-family:Verdana, Geneva, sans-serif;
	font-size:12px;
	box-shadow: 2px 2px 6px rgba(0,0,0,.2);
	min-width: 11em; /* allow long menu items to determine submenu width */
	*width: 11em; /* no auto sub width for IE7, see white-space comment below */
	min-height: 25px;
	border-top: 2px solid #2C3445;
	border-bottom: 2px solid #2C3445;
	border-left: 2px solid #2C3445;
	border-right: 2px solid #2C3445;
}

.sf-menu a {
	text-decoration: none;
	zoom: 1; /* IE7 */
}

.sf-menu a {
	color:#CCCCCC;
}

.sf-menu li {
	background: #3D475F;
	white-space: nowrap; /* no need for Supersubs plugin */
	*white-space: normal; /* ...unless you support IE7 (let it wrap) */
	-webkit-transition: background .2s;
	transition: background .2s;
}

.sf-menu ul li {
	background: #3D475F;
}

.sf-menu ul ul li {
	background: #3D475F;
}

.sf-menu li:hover,
.sf-menu li.sfHover {
	background:#485673;
	/* only transition out, not in */
	-webkit-transition: none;
	transition: none;
}

/*** arrows (for all except IE7) **/

.sf-arrows .sf-with-ul {
	padding-right: 2.5em;
	*padding-right: 1em; /* no CSS arrows for IE7 (lack pseudo-elements) */
}

/* styling for both css and generated arrows */

.sf-arrows .sf-with-ul:after {
	content: '';
	position: absolute;
	top: 50%;
	right: 1em;
	margin-top: -3px;
	height: 0;
	width: 0;
	/* order of following 3 rules important for fallbacks to work */
	border: 5px solid transparent;
	border-top-color: #dFeEFF; /* edit this to suit design (no rgba in IE8) */
	border-top-color: rgba(255,255,255,.5);
}

.sf-arrows > li > .sf-with-ul:focus:after,
.sf-arrows > li:hover > .sf-with-ul:after,
.sf-arrows > .sfHover > .sf-with-ul:after {
	border-top-color: white; /* IE8 fallback colour */
}

/* styling for right-facing arrows */

.sf-arrows ul .sf-with-ul:after {
	margin-top: -5px;
	margin-right: -3px;
	border-color: transparent;
	border-left-color: #dFeEFF; /* edit this to suit design (no rgba in IE8) */
	border-left-color: rgba(255,255,255,.5);
}

.sf-arrows ul li > .sf-with-ul:focus:after,
.sf-arrows ul li:hover > .sf-with-ul:after,
.sf-arrows ul .sfHover > .sf-with-ul:after {
	border-left-color: white;
}

/*** SIDE BUTTON ***/

.button {
  /* background-image: -webkit-linear-gradient(top, rgba(29,145,222,1) 30%,rgba(15,101,158,1) 100%);
   background-image:    -moz-linear-gradient(top, rgba(29,145,222,1) 30%,rgba(15,101,158,1) 100%);
   background-image:     -ms-linear-gradient(top, rgba(29,145,222,1) 30%,rgba(15,101,158,1) 100%);
   background-image:      -o-linear-gradient(top, rgba(29,145,222,1) 30%,rgba(15,101,158,1) 100%);
   background-image:         linear-gradient(top, rgba(29,145,222,1) 30%,rgba(15,101,158,1) 100%);
   border: solid 2px #184878;
   -webkit-border-radius: 10px;
      -moz-border-radius: 10px;
           border-radius: 10px; */
	width: 146px;
	height: 30px;
	line-height: 30px;
	
	display: inline-block;
	
	font-size: 15px;
	font-family: Trebuchet MS;
	text-align: center;
	color: <?php echo $sideColor; ?>;
	font-weight: bold;
}
.button:hover {	
	background-color: <?php echo $hoverSideBgColor; ?>;	
   /*background-image:    -moz-linear-gradient(top, rgba(29,137,204,1) 30%,rgba(15,92,140,1) 100%);
   background-image:     -ms-linear-gradient(top, rgba(29,137,204,1) 30%,rgba(15,92,140,1) 100%);
   background-image:      -o-linear-gradient(top, rgba(29,137,204,1) 30%,rgba(15,92,140,1) 100%);
   background-image:         linear-gradient(top, rgba(29,137,204,1) 30%,rgba(15,92,140,1) 100%);*/
}

/*.button:active {
   background-image: -webkit-linear-gradient(top, rgba(29,133,194,1) 0%,rgba(29,133,194,1) 100%);
   background-image:    -moz-linear-gradient(top, rgba(29,133,194,1) 0%,rgba(29,133,194,1) 100%);
   background-image:     -ms-linear-gradient(top, rgba(29,133,194,1) 0%,rgba(29,133,194,1) 100%);
   background-image:      -o-linear-gradient(top, rgba(29,133,194,1) 0%,rgba(29,133,194,1) 100%);
   background-image:         linear-gradient(top, rgba(29,133,194,1) 0%,rgba(29,133,194,1) 100%);
   -webkit-box-shadow: 0px -2px 20px 2px rgba(15,92,140,1)inset;
      -moz-box-shadow: 0px -2px 20px 2px rgba(15,92,140,1)inset;
           box-shadow: 0px -2px 20px 2px rgba(15,92,140,1)inset;
}*/

.button a {
	text-decoration: none;
	color: inherit;
}

/*** SEARCHBAR ***/

#sidebar #___gcse_0 {
	padding-top: 5px;
}

#sidebar .gsc-control-cse.gsc-control-cse-es {
	font-family: Verdana, Geneva, sans-serif;
	font-size: 11px; 
	background-color: <?php echo $sideBgColor; ?>;
	border: none;
}

#sidebar .cse .gsc-control-cse, #sidebar .gsc-control-cse {
	padding: 7px;	
}

#sidebar .gsc-search-box-tools .gsc-search-box .gsc-input {
	padding-right: 5px;
}

#sidebar table.gsc-search-box td {
	vertical-align: middle;
}

#sidebar .gsib_a, #sidebar .gsib_b {
	vertical-align: top;
}

#gsc-i-id1 {
	font-family: Verdana, Geneva, sans-serif;
	font-size: 11px;
}

#sidebar #gs_st50 {
	font-size: 1px;
	padding: 0px;
}

#sidebar .gsst_a {
	padding: 1px 2px;
}

#gs_cb50 {
	font: 12px/13px arial,sans-serif;
}

#sidebar input.gsc-search-button, input.gsc-search-button:hover, #sidebar input.gsc-search-button:focus {
	border: 2px solid <?php echo $bgc; ?>;
	background-color: <?php echo $hoverSideBgColor; ?>;
}

#sidebar .cse .gsc-search-button input.gsc-search-button-v2, #sidebar input.gsc-search-button-v2 {
	padding: 5px 6px;
	margin-top: 2px;
	border-radius: 5px;
}
	
/*** TABLE-TAGS ***/

.mush-table-form input,
.snake-table-form input,
.kube-table-form input
 {
	font-size: 11px;
	font-weight: bold;
	margin-top: 5px;
	margin-bottom: 5px;	
}

.mush-table-btn,
.snake-table-btn,
.kube-table-btn {
  -webkit-border-radius: 4;
  -moz-border-radius: 4;
  border-radius: 4px;
  font-family: Arial;
  font-size: 10px;
  padding: 3px 3px 3px 3px;
  text-decoration: none;
  font-weight: bold;
}

.mush-table-btn:hover,
.snake-table-btn:hover,
.kube-table-btn:hover {
  text-decoration: none;
}

.mush-table-form input {
	width: 120px;
	background-color: #3347A6;
	border: 2px solid #0058B0;
	color: white;
}

.mush-table-btn {
  background: #1E63D5;
  background-image: -webkit-linear-gradient(top, #1E63D5, #1c4a94);
  background-image: -moz-linear-gradient(top, #1E63D5, #1c4a94);
  background-image: -ms-linear-gradient(top, #1E63D5, #1c4a94);
  background-image: -o-linear-gradient(top, #1E63D5, #1c4a94);
  background-image: linear-gradient(to bottom, #1E63D5, #1c4a94);
  color: #ffffff;
  border: solid #103D6D 2px;
}

.snake-table-form input {
	width: 150px;
	background-color: #63D516;
	border: 2px solid #488C1A;
	color: white;
}

.snake-table-btn {
  background: #4FB50B;
  background-image: -webkit-linear-gradient(top, #4FB50B, #0C7628);
  background-image: -moz-linear-gradient(top, #4FB50B, #0C7628);
  background-image: -ms-linear-gradient(top, #4FB50B, #0C7628);
  background-image: -o-linear-gradient(top, #4FB50B, #0C7628);
  background-image: linear-gradient(to bottom, #4FB50B, #0C7628);
  color: #ffffff;
  border: solid #106B38 2px;
}

.kube-table-form input[type="text"],
.kube-table-form input[type="number"],
.kube-table-form textarea {
	background-color: #605CAE;
	border: 2px solid #4E4B90;
	color: white;
}

.kube-table-form input[type="text"],
.kube-table-form input[type="number"] {
	width: 70px;
}

.kube-table-form textarea {
	width: 200px;
	height: 60px;
	vertical-align: top;
}

.distance::-webkit-input-placeholder {
	color: #FFF; 
	text-align: center;
} 

.distance:-moz-placeholder {
	color: #FFF; 
	text-align: center;
} 

.distance:-ms-input-placeholder {
	color: #FFF; 
	text-align: center;
} 

.distance::-moz-placeholder {
	color: #FFF; 
	text-align: center;
} 


.kube-table-btn {
  background: #667AD5;
  background-image: -webkit-linear-gradient(top, #667AD5, #5552A3);
  background-image: -moz-linear-gradient(top, #667AD5, #5552A3);
  background-image: -ms-linear-gradient(top, #667AD5, #5552A3);
  background-image: -o-linear-gradient(top, #667AD5, #5552A3);
  background-image: linear-gradient(to bottom, #667AD5, #5552A3);
	color: #ffffff;
	border: solid #4E4B90 2px;
}
