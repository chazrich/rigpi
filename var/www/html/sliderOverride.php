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

	<title><?php echo $tCall; ?> RigPi Slider Overrides</title>
	<meta name="RigPi Slider Overrides" content="">
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

			outputAF = document.getElementById("myAFVal");
			sliderAF = document.getElementById("sliderAF");
			outputRF = document.getElementById("myRFVal");
			sliderRF = document.getElementById("sliderRF");
			outputPwrOut = document.getElementById("myOutputPwrVal");
			sliderPwrOut = document.getElementById("sliderPwrOut");
			sliderHandle="";
			outputMic = document.getElementById("myMicVal");
			sliderMic = document.getElementById("sliderMic");
			
			$.post('/programs/GetSelectedRadio.php', {un:tUserName}, function(response) {
				tMyRadio=response;
		        $.post('/programs/GetSetting.php',{radio: tMyRadio, field: 'DX', table: 'MySettings'}, function(response)
	        	{
					$('#searchText').val(response);
			    });

				$.post('/programs/GetSetting.php',{radio: tMyRadio, field: 'AFGainOride', table: 'RadioInterface'}, function(response){
					$( function() {
						$("#sliderAF").slider({
							min: 0,
							max:100,
							range: 'min'
						});
						outputAF.innerHTML = response;
						$("#sliderAF").slider('value',response);
					});
					$("#sliderAF").on("slidechange",function(event,ui){
						tVal=$("#sliderAF").slider('value');
						if (tVal<0) tVal=0;
						if (tVal!=sliderAFRef){
							waitRefresh=8;
							sliderAFRef=tVal;
							sliderHandle=ui.handle;
							var tV=tVal;
							if (tV>0 && tV<100) tV=tV-1;
							outputAF.innerHTML =tV;
							$.post("/programs/SetSettings.php", {field: "AFGainOride", radio: tMyRadio, data: tVal, table: "RadioInterface"}, function(response){
							});
						};
						$("#sliderAF").on("slide",function(event,ui){
							waitRefresh=8;
							tVal=$("#sliderAF").slider('value');
							var tV=tVal;
							if (tV>0 && tV<100) tV=tV-1;
							outputAF.innerHTML =tV;
							sliderHandle=ui.handle;
						});
					});
				});
				
				$.post('/programs/GetSetting.php',{radio: tMyRadio, field: 'RFGainOride', table: 'RadioInterface'}, function(response){
					$( function() {
						$("#sliderRF").slider({
							min: 0,
							max: 100,
							range: 'min'
						});
						outputRF.innerHTML = response;
						$("#sliderRF").slider('value',response);
					});
											
					$("#sliderRF").on("slidechange",function(event,ui){
						tVal=$("#sliderRF").slider('value');
						console.log("change: "+tVal);
					//						if (tVal<100) tVal=tVal-1;
						if (tVal<0) tVal=0;
						if (tVal!=sliderRFRef){
							waitRefresh=5;
							sliderRFRef=tVal;
							sliderHandle=ui.handle;
							var tV=tVal;
							if (tV>0 && tV<100) tV=tV-1;
							outputRF.innerHTML =tV;
							$.post("/programs/SetSettings.php", {field: "RFGainOride", radio: tMyRadio, data: tVal, table: "RadioInterface"}, function(response){
							});
					//radial.value = tV;
						};
					});
					
					$("#sliderRF").on("slide",function(event,ui){
						waitRefresh=3;
						tVal=$("#sliderRF").slider('value');
						console.log("slide: "+tVal);
						if (tVal<0) tVal=0;
						sliderHandle=ui.handle;
						var tV=tVal;
						if (tV>0 && tV<100) tV=tV-1;
						outputRF.innerHTML =tV;
					});
				});

				$.post('/programs/GetSetting.php',{radio: tMyRadio, field: 'PwrOutOride', table: 'RadioInterface'}, function(response){
					$( function() {
						$("#sliderPwrOut").slider({
							min: 0,
							max: 100,
							range: 'min'
						});
						outputPwrOut.innerHTML = response;
						$("#sliderPwrOut").slider('value',response);
					});
							
					$("#sliderPwrOut").on("slidechange",function(event,ui){
						tVal=$("#sliderPwrOut").slider('value');
						if (tVal<0) tVal=0;
						if (tVal!=sliderPwrOutRef){
							waitRefresh=5;
							sliderPwrOutRef=tVal;
							sliderHandle=ui.handle;
							var tV=tVal;
							if (tV>0 && tV<100) tV=tV-1;
							outputPwrOut.innerHTML =tV;
							$.post("/programs/SetSettings.php", {field: "PwrOutOride", radio: tMyRadio, data: tVal, table: "RadioInterface"}, function(response){
							});
						};
					});
					
					$("#sliderPwrOut").on("slide",function(event,ui){
						waitRefresh=3;
						tVal=$("#sliderPwrOut").slider('value');
						var tV=tVal;
						if (tV>0 && tV<100) tV=tV-1;
						outputPwrOut.innerHTML =tV;
						sliderHandle=ui.handle;
					});
				});
				
				$.post('/programs/GetSetting.php',{radio: tMyRadio, field: 'MicLvlOride', table: 'RadioInterface'}, function(response){
					$( function() {
						$("#sliderMic").slider({
							min: 0,
							max: 100,
							range: 'min'
						});
						outputMic.innerHTML = response;
						$("#sliderMic").slider('value',response);
					});
											
					$("#sliderMic").on("slidechange",function(event,ui){
						tVal=$("#sliderMic").slider('value');
						if (tVal<0) tVal=0;
						if (tVal!=sliderMicRef){
							sliderMicRef=tVal;
							waitRefresh=8;
							sliderHandle=ui.handle;
							var tV=tVal;
							if (tV>0 && tV<100) tV=tV-1;
							if (tV<0) tV=0;
							outputMic.innerHTML =tV;
							$.post("/programs/SetSettings.php", {field: "MicLvlOride", radio: tMyRadio, data: tVal, table: "RadioInterface"}, function(response){
							});
						};
					});
					
					$("#sliderMic").on("slide",function(event,ui){
						waitRefresh=8;
						tVal=$("#sliderMic").slider('value');
						if (tVal<0) tVal=0;
						sliderHandle=ui.handle;
						var tV=tVal;
						if (tV>0 && tV<100) tV=tV-1;
						outputMic.innerHTML =tV;
					});

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
			});				
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
	  	}

			

	  	$.getScript("/js/addPeriods.js");

	</script>
</head>

<body class="body-black" >
	<?php require $dRoot . "/includes/header.php"; ?>
	<div class="container-fluid">
		<div class="row"  style="margin-bottom:10px;">
		</div>
		<div class="row" style="margin-left:20px;margin-right:20px">
			<?php require $dRoot . "/includes/sliders.php"; ?>
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
