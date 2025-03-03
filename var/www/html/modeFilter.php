<?php
if (!isset($GLOBALS["htmlPath"])) {
  $GLOBALS["htmlPath"] = $_SERVER["DOCUMENT_ROOT"];
}
$dRoot = $GLOBALS["htmlPath"];
$tCall = $_GET["c"];
$tUserName = $_GET["x"];
require_once $dRoot . "/classes/Membership.php";
$membership = new Membership();
$membership->confirm_Member($tUserName);
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">

	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?php echo $tCall; ?> RigPi Default Settings</title>
	<meta name="RigPi Default Settings" content="">
	<meta name="author" content="Howard Nurse">

	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="/Bootstrap/bootstrap.min.css">
	 <script src="/Bootstrap/jquery.min.js" ></script>
<!--
<script src="/js/jogDial.min.js"></script>
<script src="/js/gauge.min.js"></script>
-->
	<link rel="shortcut icon" href="./favicon.ico">
	<link rel="apple-touch-icon" href="./favicon.ico">
	<?php require $dRoot . "/includes/styles.php"; ?>
	<link href="./awe/css/all.css" rel="stylesheet">
	<link href="./awe/css/fontawesome.css" rel="stylesheet">
	<link href="./awe/css/solid.css" rel="stylesheet">	
	<link rel="stylesheet" href="./Bootstrap/jquery-ui.css">

	<!-- Replace favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
	<link rel="shortcut icon" href="/favicon.ico">
	<link rel="apple-touch-icon" href="/apple-touch-icon.png">
	<?php require $dRoot . "/includes/styles.php"; ?>
	<script>
		var tManu='';
		var tRotorID='1';
		var tMyRadio='1';
		var tMyRotorPort=4531;
		var tMyCall="<?php echo $tCall; ?>";
		var tCall=tMyCall;
        var tUserName=<?php echo "'" . $tUserName . "'"; ?>;
        var tUser='';
		var bEnable=[],bName;
		var outputAF, sliderAF, outputPwrOut, sliderPwrOut, sliderMic, outputMic, outputRF, sliderRF, tVal;
		var sliderAFRef, sliderRFRef, sliderPwrOutRef, sliderMicRef, sliderHandle
		var Aon,Bon,Con,Don,Eon,Ron
  		$(document).ready(function(){
			$.post('/programs/GetSelectedRadio.php', {un:tUserName}, function(response) {
				tMyRadio=response;
				tMyRotorPort=tMyRadio*2+4531;
				$("#curID").val(response);
		        $.post('/programs/GetSetting.php',{radio: tMyRadio, field: 'DX', table: 'MySettings'}, function(response)
	        	{
					$('#searchText').val(response);
			    });
				
				$.post("/programs/GetUserField.php", {un:tUserName, field:'ModeEnable'}, function(response)
				{
					if (response==""){
						response="1,1,1,1,1,1,1,1,1";
					}
					bEnable=response.split(",");
					updateButtons();
				});
			});

			function updateButtons(){
				var bName="";
				for (i=0;i<9;i++){
					switch (i){
						case 0:
							bName="#lsbButton";
							break;
						case 1:
							bName="#usbButton";
							break;
						case 2:
							bName="#usbdButton";
							break;
						case 3:
							bName="#cwButton";
							break;
						case 4:
							bName="#cwrButton";
							break;
						case 5:
							bName="#rttyButton";
							break;
						case 6:
							bName="#fmButton";
							break;
						case 7:
							bName="#amButton";
							break;
						case 8:
							bName="#rttyrButton";
							break;
					};
					if (bEnable[i]=="0") {
						$(bName).removeClass("btn-success");
						$(bName).addClass("btn-secondary");
					}else{
						bEnable[i]=1;
						$(bName).removeClass("btn-secondary");
						$(bName).addClass("btn-success");
					}
				}
			}

			$(document).keydown(function(e){
			var t=e.key;
			e.multiple
			var w=e.which;
			if (w==191)
			{
				if (e.shiftKey){
					<?php require $dRoot . "/includes/shortcutsOther.php"; ?>
					$("#modalCO-body").html(tSh);			  				
					$("#modalCO-title").html("Shortcut Keys");
					  $("#myModalCancelOnly").modal({show:true});
					  return false;
				}else{
					var tS1=document.activeElement.tagName;
					if (tS1=='INPUT'){
						return true;
					}else{
						$("#searchText").focus();
						return false;
					}
				}
			};
			if (w == 27) { 
				document.getElementById('closeModal').click();
			}
				if (e.altKey){
					switch(w){
					case 65: // a
						var win="/calendar_main.php?x="+tUserName+"&c="+tCall;
						window.open(win, "_self");
						break;
					case 69: // e
						showSettings();
						e.preventDefault();
						break;
					case 72: // h
						showHelp();
						e.preventDefault();
						break;
					case 75: //k
						showKeyer();
						e.preventDefault();
						break;
					case 76: //l
						showLog();
						e.preventDefault();
						break;
			 			
					case 83: // s
						showSpots();
						e.preventDefault();
						break;
					case 84: // t
						showTuner();
						e.preventDefault();
						break;
					case 87: // w
						showWeb();
						e.preventDefault();
						break;
					};
				};
			});
								
			$("input").bind("keydown", function(event) 
			{
				// track enter key
				var keycode = (event.keyCode ? event.keyCode : (event.which ? event.which : event.charCode));
				if (keycode == 13) { // keycode for enter key
					if ($('#searchText').val()==''){
						return false;
					}
					var tDX=$('#searchText').val().toUpperCase();
					$('#searchText').val(tDX);
					document.getElementById('searchButton').click();
					$.post("/programs/SetSettings.php", {field: "DX", radio: tMyRadio, data: tDX, table: "MySettings"});
					return false;
				} else  {
					return true;
				}
			});
			
			$(document).on('click', '#RButton', function() 
			 {
				 var on=1;
				doEnableMF(0, '#AButton', on);
				doEnableMF(1, '#AButton', on);
				doEnableMF(2, '#AButton', on);
				doEnableMF(3, '#AButton', on);
				doEnableMF(4, '#AButton', on);
				doEnableMF(5, '#AButton', on);
				doEnableMF(6, '#AButton', on);
				doEnableMF(7, '#AButton', on);
				doEnableMF(8, '#AButton', on);
				updateButtons();
				var bS=bEnable.join();
				$.post("/programs/SetUserFieldByName.php", {field: "ModeEnable", username: tUserName, data: bS, table: "Users"}, function(response){
					});
			});	
			 
			$(document).on('click', '#AButton', function() 
			 {
				if (bEnable[0]==1 || bEnable[1]==1 || bEnable[6]==1 || bEnable[7]==1 ){
					Aon=0;
				}else{
					Aon=1;
				}
				 var on=Aon;
				doEnableMF(0, '#AButton', on);
				doEnableMF(1, '#AButton', on);
				doEnableMF(6, '#AButton', on);
				doEnableMF(7, '#AButton', on);
				updateButtons();
				var bS=bEnable.join();
				$.post("/programs/SetUserFieldByName.php", {field: "ModeEnable", username: tUserName, data: bS, table: "Users"}, function(response){
					});
			 });	
			 
			$(document).on('click', '#BButton', function() 
			 {
				if (bEnable[3]==1 || bEnable[4]==1){
					Bon=0;
				}else{
					Bon=1;
				}
				var on=Bon;
				doEnableMF(3, '#BButton', on);
				doEnableMF(4, '#BButton', on);
				updateButtons();
				var bS=bEnable.join();
				$.post("/programs/SetUserFieldByName.php", {field: "ModeEnable", username: tUserName, data: bS, table: "Users"}, function(response){
				 });
			 });	
			 
			$(document).on('click', '#CButton', function() 
			 {
				if (bEnable[2]==1 || bEnable[5]==1 || bEnable[8]==1){
					Con=0;
				}else{
					Con=1;
				}
				var on=Con;
				doEnableMF(2, '#CButton', on);
				doEnableMF(5, '#CButton', on);
				doEnableMF(8, '#CButton', on);
				updateButtons();
				var bS=bEnable.join();
				$.post("/programs/SetUserFieldByName.php", {field: "ModeEnable", username: tUserName, data: bS, table: "Users"}, function(response){
					});
			 });	
			 
			 
			$(document).on('click', '#logoutButton', function() 
			{
				openWindowWithPost("/login.php", {
					status: "loggedout",
					username: tUserName});
			});	
		
			function openWindowWithPost(url, data) {
			    var form = document.createElement("form");
			    form.target = "_self";
			    form.method = "POST";
			    form.action = url;
			    form.style.display = "none";
			
			    for (var key in data) {
			        var input = document.createElement("input");
			        input.type = "hidden";
			        input.name = key;
			        input.value = data[key];
			        form.appendChild(input);
			    }
			
			    document.body.appendChild(form);
			    window.open("/login.php","_self");
			    form.submit();
			};                
		
				$.getScript("/js/modalLoad.js");
		
			 });
		
			var tUpdate = setInterval(updateTimer,1000);
		
			function updateTimer(){
		       $.post('/programs/GetInterfaceIn.php',{radio: tMyRadio, un: tUserName, myCall:<?php echo "'" .
           $tCall .
           "'"; ?>}, function(response) 
		        {
					updateFooter();
				});
		        var now = new Date();
		        var now_hours=now.getUTCHours();
		        now_hours=("00" + now_hours).slice(-2);
		        var now_minutes=now.getUTCMinutes();
		        now_minutes=("00" + now_minutes).slice(-2);
		        $("#fPanel5").text(now_hours+":"+now_minutes+'z');
		  	};
		
			$(document).on('click', '.bBtn0', function() 
			{
				doEnable(0,"#"+this.id);
			});
			
			$(document).on('click', '.bBtn1', function() 
			{
				doEnable(1,"#"+this.id);
			});
			
			$(document).on('click', '.bBtn2', function() 
			{
				doEnable(2,"#"+this.id);
			});
			
			$(document).on('click', '.bBtn3', function() 
			{
				doEnable(3,"#"+this.id);
			});
			
			$(document).on('click', '.bBtn4', function() 
			{
				doEnable(4,"#"+this.id);
			});
			
			$(document).on('click', '.bBtn5', function() 
			{
				doEnable(5,"#"+this.id);
			});
			
			$(document).on('click', '.bBtn6', function() 
			{
				doEnable(6,"#"+this.id);
			});
			
			$(document).on('click', '.bBtn7', function() 
			{
				doEnable(7,"#"+this.id);
			});
			
			$(document).on('click', '.bBtn8', function() 
			{
				doEnable(8,"#"+this.id);
			});
			
		
			function doEnable(which, name){
				
				if (bEnable[which]=="1"){
					$(name).removeClass("btn-success");
					$(name).addClass("btn-secondary");
					bEnable[which]=0;
					var bS=bEnable.join();
					$.post("/programs/SetUserFieldByName.php", {field: "ModeEnable", username: tUserName, data: bS, table: "Users"}, function(response){
						});
				}else{
					$(name).removeClass("btn-secondary");
					$(name).addClass("btn-success");
					bEnable[which]=1;
					var bS=bEnable.join();
					$.post("/programs/SetUserFieldByName.php", {field: "ModeEnable", username: tUserName, data: bS, table: "Users"}, function(response){
						});
				};
			};
			
			function doEnableMF(which, name, onOff){
					bEnable[which]=onOff;
			};
		
			 $.getScript("/js/addPeriods.js");

	</script>
</head>

<body class="body-black" >
	<?php require $dRoot . "/includes/header.php"; ?>
	<div class="container-fluid">
		<div class="row"  style="margin-bottom:10px;">
		</div>
		<div class="row" style="margin-left:20px;margin-right:20px">
			<?php require $dRoot . "/includes/modes.php"; ?>
		</div>
	</div>
    <?php require $dRoot . "/includes/footer.php"; ?>
    <?php require $dRoot . "/includes/modalAlert.txt"; ?>
    <?php require $dRoot . "/includes/modal.txt"; ?>
<?php require $dRoot . "/includes/modalCancelOnly.txt"; ?>
	<script src="/js/mscorlib.js" type="text/javascript"></script> 
	<script src="/js/PerfectWidgets.js" type="text/javascript"></script>
	<script src="/Bootstrap/jquery-ui.js"></script>
	<script src="/js/jquery.ui.touch-punch.min.js"></script>   
	<script src="/Bootstrap/bootstrap.min.js"></script>
	<script src="/js/nav-active.js"></script>
	<script src="/Bootstrap/popper.min.js"></script>
</body>
</html>
