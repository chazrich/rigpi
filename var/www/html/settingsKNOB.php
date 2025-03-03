<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>HTML5 &lt;x-knob&gt; rotating knob web component</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!--
Required for browsers that do not yet support the latest technologies.
http://webcomponents.org/
https://github.com/webcomponents/webcomponentsjs/releases
-->
<script type="text/javascript">
if (!document.registerElement) {
	document.writeln(decodeURI('%3Cscript%20src=%22https://cdnjs.cloudflare.com/ajax/libs/webcomponentsjs/0.7.12/webcomponents.min.js%22%20type=%22text/javascript%22%3E%3C/script%3E'));
}
</script>

<script src="./js/xknob.js" type="text/javascript" async></script>
<link href="./js/xknob.css" type="text/css" rel="stylesheet">

<style type="text/css">
html, body {
	background: white;
	color: black;
}
h2 {
	border-top: 1px solid black;
	margin: 2em 0 1ex;
}
:target {
	background: #eeeeec;
}

table#bigdemo {
	border-collapse: collapse;
}
table#bigdemo td > label {
	display: block;
}
table#bigdemo th {
	text-align: right;
	font: inherit;
	padding-left: 0.5em;
	padding-right: 0.5em;
}
#big_container {
	position: relative;
	padding: 0;
}
#big_bg {
	display: block;
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	width: 100%;
	height: 100%;
}
#big {
	display: block;
	position: relative;
	width: 13em;
	height: 13em;
	background-repeat: no-repeat;
	background-size: contain;
	background-position: center center;
}

#sizes x-knob {
	outline: 1px blue dotted;
}
#sizes x-knob:nth-child(1) { width: 1em; height: 1em; }
#sizes x-knob:nth-child(2) { width: 2em; height: 2em; }
#sizes x-knob:nth-child(3) { width: 4em; height: 2em; }
#sizes x-knob:nth-child(4) { width: 2em; height: 4em; }
#sizes x-knob:nth-child(5) { width: 4em; height: 4em; }

#clock-example {
	display: flex;
	flex-direction: row;
	flex-wrap: wrap;
	justify-content: flex-start;
	align-items: center;
}
#clock-block {
	width: 16em;
	height: 16em;
	position: relative;
	flex: 0 0 auto;
	margin-right: 2em;
}
#clock-output {
	flex: 1 1 auto;
	font-size: 3em;
	width: 5em;
}
#clock-block > svg,
#clock-block > x-knob {
	display: block;
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	width: 100%;
	height: 100%;
}
</style>

<style type="text/css" shim-shadowdom>
/* Requires shim-shadowdom and webcomponentsjs in Firefox (due to lack of ::shadow support). https://github.com/Polymer/docs/issues/269 */
#transition x-knob::shadow .knob_gfx { transition: 125ms transform; }
</style>

<svg style="display: none;">
	<!-- Most of the colors are from Tango palette -->
	<defs id="sample_gfx">
		<symbol id="circle-with-dot" viewBox="-6 -6 12 12">
			<circle cx="0" cy="0" r="5.875" stroke="#2e3436" fill="#babdb6" stroke-width="0.25"/>
			<circle cx="0" cy="-3.75" r="0.75" stroke="none" fill="#2e3436"/>
		</symbol>
		<symbol id="long-stick" viewBox="-6 -6 12 12">
			<circle cx="0" cy="0" r="2.5" stroke="#2e3436" fill="#888a85" stroke-width="0.25"/>
			<rect x="-1" y="-5" rx="1" ry="1" width="2" height="10" stroke="#2e3436" fill="#babdb6" stroke-width="0.25"/>
			<line x1="0" y1="-3.75" x2="0" y2="-4.5" stroke="#2e3436" stroke-width="0.5px" stroke-linecap="round"/>
		</symbol>
		<symbol id="arrow" viewBox="-3 -3 6 6">
			<path fill="#2e3436" d="M0,-3 l2,3 h-1 v2.5 h-2 v-2.5 h-1 z"/>
		</symbol>
		<symbol id="arrow-inside-circle" viewBox="-3 -3 6 6">
			<circle cx="0" cy="0" r="3" fill="#fce94f"/>
			<path fill="#2e3436" d="M0,-3 l2,3 h-1 v2.5 h-2 v-2.5 h-1 z"/>
		</symbol>
		<symbol id="arrow-too-big" viewBox="-3 -3 6 6">
			<circle cx="0" cy="0" r="3" fill="#fce94f"/>
			<path fill="#a40000" d="M0,-3 l3,3 h-1 v3 h-4 v-3 h-1 z"/>
		</symbol>
		<symbol id="transparent-circle" viewBox="-4 -4 8 8">
			<!-- A transparent SVG object still receives pointer events. -->
			<circle cx="0" cy="0" r="4" opacity="0"/>
			<path fill="#2e3436" d="M0,-4 l0.5,5 h-1 z"/>
		</symbol>
		<symbol id="chromatic-wheel" viewBox="-4 -4 8 8">
			<!-- Inspired by https://openclipart.org/detail/181641/chromatic-wheel-1 -->
			<!-- Generated using:
				var r = 4;
				var steps = 36;
				var angle = Math.PI * 2 / steps / 2;

				var a1 = [0, -(r - 1)];
				var a2 = [0, -r];

				var rotate = function(p, angle) { return [p[0] * Math.cos(angle) - p[1] * Math.sin(angle), p[0] * Math.sin(angle) + p[1] * Math.cos(angle)]; };
				var points = [
				 	rotate(a1, angle),
				 	rotate(a2, angle),
				 	rotate(a2, -angle),
				 	rotate(a1, -angle),
				];

				var d = 'M' + points.map(function(p){return p.join(',');}).join(' ') + ' z';
			-->
			<g visibility="hidden">
				<path id="circle-section" d="M-0.2614672282429745,-2.988584094275237 -0.34862297099063266,-3.984778792366982 0.34862297099063266,-3.984778792366982 0.2614672282429745,-2.988584094275237 z"/>
			</g>
			<!-- Any referenced element inside this symbol must be defined also inside it, otherwise <x-knob> won't copy it. -->
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(0, 100%, 50%)" transform="rotate(0)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(10, 100%, 50%)" transform="rotate(10)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(20, 100%, 50%)" transform="rotate(20)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(30, 100%, 50%)" transform="rotate(30)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(40, 100%, 50%)" transform="rotate(40)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(50, 100%, 50%)" transform="rotate(50)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(60, 100%, 50%)" transform="rotate(60)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(70, 100%, 50%)" transform="rotate(70)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(80, 100%, 50%)" transform="rotate(80)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(90, 100%, 50%)" transform="rotate(90)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(100, 100%, 50%)" transform="rotate(100)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(110, 100%, 50%)" transform="rotate(110)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(120, 100%, 50%)" transform="rotate(120)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(130, 100%, 50%)" transform="rotate(130)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(140, 100%, 50%)" transform="rotate(140)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(150, 100%, 50%)" transform="rotate(150)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(160, 100%, 50%)" transform="rotate(160)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(170, 100%, 50%)" transform="rotate(170)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(180, 100%, 50%)" transform="rotate(180)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(190, 100%, 50%)" transform="rotate(190)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(200, 100%, 50%)" transform="rotate(200)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(210, 100%, 50%)" transform="rotate(210)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(220, 100%, 50%)" transform="rotate(220)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(230, 100%, 50%)" transform="rotate(230)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(240, 100%, 50%)" transform="rotate(240)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(250, 100%, 50%)" transform="rotate(250)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(260, 100%, 50%)" transform="rotate(260)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(270, 100%, 50%)" transform="rotate(270)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(280, 100%, 50%)" transform="rotate(280)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(290, 100%, 50%)" transform="rotate(290)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(300, 100%, 50%)" transform="rotate(300)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(310, 100%, 50%)" transform="rotate(310)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(320, 100%, 50%)" transform="rotate(320)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(330, 100%, 50%)" transform="rotate(330)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(340, 100%, 50%)" transform="rotate(340)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(350, 100%, 50%)" transform="rotate(350)"/>
		</symbol>
		<symbol id="chromatic-wheel-labeled" viewBox="-5 -5 10 10">
			<g visibility="hidden">
				<path id="circle-section" d="M-0.2614672282429745,-2.988584094275237 -0.34862297099063266,-3.984778792366982 0.34862297099063266,-3.984778792366982 0.2614672282429745,-2.988584094275237 z"/>
			</g>
			<!-- Any referenced element inside this symbol must be defined also inside it, otherwise <x-knob> won't copy it. -->
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(0, 100%, 50%)" transform="rotate(0)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(10, 100%, 50%)" transform="rotate(10)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(20, 100%, 50%)" transform="rotate(20)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(30, 100%, 50%)" transform="rotate(30)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(40, 100%, 50%)" transform="rotate(40)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(50, 100%, 50%)" transform="rotate(50)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(60, 100%, 50%)" transform="rotate(60)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(70, 100%, 50%)" transform="rotate(70)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(80, 100%, 50%)" transform="rotate(80)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(90, 100%, 50%)" transform="rotate(90)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(100, 100%, 50%)" transform="rotate(100)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(110, 100%, 50%)" transform="rotate(110)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(120, 100%, 50%)" transform="rotate(120)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(130, 100%, 50%)" transform="rotate(130)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(140, 100%, 50%)" transform="rotate(140)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(150, 100%, 50%)" transform="rotate(150)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(160, 100%, 50%)" transform="rotate(160)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(170, 100%, 50%)" transform="rotate(170)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(180, 100%, 50%)" transform="rotate(180)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(190, 100%, 50%)" transform="rotate(190)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(200, 100%, 50%)" transform="rotate(200)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(210, 100%, 50%)" transform="rotate(210)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(220, 100%, 50%)" transform="rotate(220)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(230, 100%, 50%)" transform="rotate(230)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(240, 100%, 50%)" transform="rotate(240)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(250, 100%, 50%)" transform="rotate(250)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(260, 100%, 50%)" transform="rotate(260)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(270, 100%, 50%)" transform="rotate(270)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(280, 100%, 50%)" transform="rotate(280)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(290, 100%, 50%)" transform="rotate(290)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(300, 100%, 50%)" transform="rotate(300)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(310, 100%, 50%)" transform="rotate(310)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(320, 100%, 50%)" transform="rotate(320)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(330, 100%, 50%)" transform="rotate(330)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(340, 100%, 50%)" transform="rotate(340)"/>
			<use xlink:href="#circle-section" x="0" y="0" fill="hsl(350, 100%, 50%)" transform="rotate(350)"/>

			<g text-anchor="middle" dominant-baseline="central" font-family="Arial, sans-serif" font-size="0.4px">
				<text x="0" y="-4.5" transform="rotate(0)">0</text>
				<text x="0" y="-4.5" transform="rotate(10)">10</text>
				<text x="0" y="-4.5" transform="rotate(20)">20</text>
				<text x="0" y="-4.5" transform="rotate(30)">30</text>
				<text x="0" y="-4.5" transform="rotate(40)">40</text>
				<text x="0" y="-4.5" transform="rotate(50)">50</text>
				<text x="0" y="-4.5" transform="rotate(60)">60</text>
				<text x="0" y="-4.5" transform="rotate(70)">70</text>
				<text x="0" y="-4.5" transform="rotate(80)">80</text>
				<text x="0" y="-4.5" transform="rotate(90)">90</text>
				<text x="0" y="-4.5" transform="rotate(100)">100</text>
				<text x="0" y="-4.5" transform="rotate(110)">110</text>
				<text x="0" y="-4.5" transform="rotate(120)">120</text>
				<text x="0" y="-4.5" transform="rotate(130)">130</text>
				<text x="0" y="-4.5" transform="rotate(140)">140</text>
				<text x="0" y="-4.5" transform="rotate(150)">150</text>
				<text x="0" y="-4.5" transform="rotate(160)">160</text>
				<text x="0" y="-4.5" transform="rotate(170)">170</text>
				<text x="0" y="-4.5" transform="rotate(180)">180</text>
				<text x="0" y="-4.5" transform="rotate(190)">190</text>
				<text x="0" y="-4.5" transform="rotate(200)">200</text>
				<text x="0" y="-4.5" transform="rotate(210)">210</text>
				<text x="0" y="-4.5" transform="rotate(220)">220</text>
				<text x="0" y="-4.5" transform="rotate(230)">230</text>
				<text x="0" y="-4.5" transform="rotate(240)">240</text>
				<text x="0" y="-4.5" transform="rotate(250)">250</text>
				<text x="0" y="-4.5" transform="rotate(260)">260</text>
				<text x="0" y="-4.5" transform="rotate(270)">270</text>
				<text x="0" y="-4.5" transform="rotate(280)">280</text>
				<text x="0" y="-4.5" transform="rotate(290)">290</text>
				<text x="0" y="-4.5" transform="rotate(300)">300</text>
				<text x="0" y="-4.5" transform="rotate(310)">310</text>
				<text x="0" y="-4.5" transform="rotate(320)">320</text>
				<text x="0" y="-4.5" transform="rotate(330)">330</text>
				<text x="0" y="-4.5" transform="rotate(340)">340</text>
				<text x="0" y="-4.5" transform="rotate(350)">350</text>
			</g>
		</symbol>

		<!-- Driving wheel clipart from https://openclipart.org/detail/172010/driving-wheel -->
		<!-- Compatibility results:
		* As <image>:
			* Works fine in Chrome 44. Square-shaped clickable area.
			* Almost works in Firefox 40. Square-shaped tinted background instead of fully transparent.
		* As <use>:
			* Does not work in Chrome 44. Nothing is displayed. Unsure about the reason.
			* Works perfectly in Firefox 40. Precise clickable area.
		-->
		<symbol id="driving-wheel-as-image" viewBox="0 0 1 1">
			<image xlink:href="drivingwheel.svg" x="0" y="0" width="1" height="1"/>
		</symbol>
		<symbol id="driving-wheel-as-use" viewBox="0 0 1 1">
			<!-- https://css-tricks.com/svg-use-external-source/ -->
			<use xlink:href="drivingwheel.svg#drivingwheel" x="0" y="0" width="1" height="1"/>
		</symbol>

		<!-- Clock hands from http://www.3quarks.com/en/SVGClock/ http://www.3quarks.com/images/svg/station-clock.svg -->
		<symbol id="hourHandSiemens" viewBox="0 0 200 200">
			<g style="fill:#222">
				<rect x="97.3" y="65" width="5.4" height="35" style="stroke:none"/>
				<circle cx="97.3" cy="58.5" r="9" style="stroke:none"/>
				<circle cx="102.7" cy="58.5" r="9" style="stroke:none"/>
				<path d="M 88.3,58.5 Q 88.3,52 100,37.5 Q 111.7,52 111.7,58.5 Z" style="stroke:none"/>
				<path d="M 93.5,123 Q 100,125.5 106.5,123 Q 103,116 102.7,100 L 97.3,100 Q 97.3,116 93.5,123 Z" style="stroke:none"/>
				<circle cx="100" cy="100" r="7.4" style="stroke:none"/>
			</g>
		</symbol>
		<symbol id="minuteHandSiemens" viewBox="0 0 200 200">
			<g style="fill:#222">
				<polygon points="95.3,49 99.5,2 100.5,2 104.7,49 102.7,100 97.3,100" style="stroke:none"/>
				<path d="M 93.5,123 Q 100,125.5 106.5,123 Q 103,116 102.7,100 L 97.3,100 Q 97.3,116 93.5,123 Z" style="stroke:none"/>
				<circle cx="100" cy="100" r="7" style="stroke:none"/>
			</g>
		</symbol>
		<symbol id="secondHandDIN41071.2" viewBox="0 0 200 200">
			<g style="fill:#ad1a14; stroke:#ad1a14">
				<polygon points="98.8,11 100,9.8 101.2,11 101.6,42 98.4,42" style="stroke:none"/>
				<polygon points="98.1,58 101.9,58 102.5,122 97.5,122" style="stroke:none"/>
				<circle cx="100" cy="50" r="8.5" style="fill:none; stroke-width:6.5"/>
			</g>
		</symbol>
	</defs>

	<defs id="sample_bg">
		<symbol id="bg-green" viewBox="0 0 1 1">
			<rect x="0" y="0" width="1" height="1" fill="#8ae234"/>
		</symbol>
		<symbol id="bg-green-circle" viewBox="-1 -1 2 2">
			<circle cx="0" cy="0" r="1" fill="#8ae234"/>
		</symbol>
		<symbol id="bg-radial-lines-2" viewBox="-1 -1 2 2">
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(  0)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(180)"/>
		</symbol>
		<symbol id="bg-radial-lines-3" viewBox="-1 -1 2 2">
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(  0)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(120)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(240)"/>
		</symbol>
		<symbol id="bg-radial-lines-4" viewBox="-1 -1 2 2">
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(  0)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate( 90)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(180)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(270)"/>
		</symbol>
		<symbol id="bg-radial-lines-5" viewBox="-1 -1 2 2">
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(  0)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate( 72)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(144)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(216)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(288)"/>
		</symbol>
		<symbol id="bg-radial-lines-6" viewBox="-1 -1 2 2">
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(  0)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate( 60)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(120)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(180)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(240)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(300)"/>
		</symbol>
		<symbol id="bg-radial-lines-8" viewBox="-1 -1 2 2">
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(  0)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate( 45)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate( 90)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(135)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(180)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(225)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(270)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(315)"/>
		</symbol>
		<symbol id="bg-radial-lines-12" viewBox="-1 -1 2 2">
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(  0)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate( 30)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate( 60)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate( 90)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(120)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(150)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(180)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(210)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(240)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(270)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(300)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(330)"/>
		</symbol>
		<symbol id="bg-radial-lines-24" viewBox="-1 -1 2 2">
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(  0)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate( 15)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate( 30)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate( 45)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate( 60)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate( 75)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate( 90)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(105)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(120)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(135)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(150)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(165)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(180)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(195)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(210)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(225)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(240)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(255)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(270)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(285)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(300)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(315)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(330)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(345)"/>
		</symbol>
		<symbol id="bg-radial-lines-36" viewBox="-1 -1 2 2">
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(  0)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate( 10)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate( 20)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate( 30)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate( 40)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate( 50)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate( 60)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate( 70)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate( 80)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate( 90)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(100)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(110)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(120)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(130)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(140)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(150)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(160)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(170)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(180)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(190)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(200)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(210)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(220)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(230)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(240)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(250)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(260)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(270)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(280)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(290)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(300)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(310)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(320)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(330)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(340)"/>
			<line x1="0" y1="0" x2="0" y2="-1" stroke="#2e3436" stroke-width="0.0078125px" transform="rotate(350)"/>
		</symbol>
	</defs>
</svg>

</head>
<body>

<h1><code>&lt;x-knob&gt;</code> Web Component</h1>

<p><a href="https://github.com/denilsonsa/html5-knob">View this project on GitHub!</a></p>

<script type="text/javascript">
if (window.WebComponents) {
	document.writeln('<p>Note: <code>document.registerElement()</code> not detected, enabling the <a href="https://github.com/webcomponents/webcomponentsjs/releases">webcomponentsjs</a> polyfill.</p>');
}
</script>

<h2>Playaround demo</h2>

<p>A demo with a single <code>&lt;x-knob&gt;</code> element with all attributes exposed. It's easy to get a feel on how it works by just interacting with this demo.</p>

<!-- I'm sorry I'm using a table for layout. But my intent is to demonstrate the knob, not the table. -->
<table id="bigdemo">
	<tr>
		<td rowspan="9" id="big_container">
			<!-- Just a background. -->
			<svg id="big_bg" viewBox="0 0 1 1"></svg>
			<!-- This is the main element! -->
			<x-knob id="big"></x-knob>
		</td>
		<th><label for="bigdivisions"><code>divisions</code></label></th>
		<td><input type="number" id="bigdivisions" min="0" step="1" value="0" title="Accepts integers starting from 2. Any other value is ignored."></td>
	</tr>
	<tr>
		<th><label for="bigmin"><code>min</code></label></th>
		<td><input type="number" id="bigmin" step="any" value="" title="Any float, or null/empty for not having a limit."></td>
	</tr>
	<tr>
		<th><label for="bigmax"><code>max</code></label></th>
		<td><input type="number" id="bigmax" step="any" value="" title="Any float, or null/empty for not having a limit."></td>
	</tr>
	<tr>
		<th></th>
		<td><label><input type="checkbox" id="bigdisabled"> <code>disabled</code></td>
	</tr>
	<tr>
		<th></th>
		<td><label><input type="checkbox" id="bigreadonly"> <code>readonly</code></td>
	</tr>
	<tr>
		<th><label for="bigsvgsymbolid"><code>svgsymbolid</code></label></th>
		<td><select id="bigsvgsymbolid">
			<option value="" selected>&lt;empty&gt;</option>
		</select>
		<script type="text/javascript">
		(function(){
			// Filling up the <select> with all available sample graphics.
			var select = document.getElementById('bigsvgsymbolid');
			Array.prototype.forEach.call(
				document.querySelectorAll('#sample_gfx symbol[id]'),
				function(curr, index, arr) {
					var option = document.createElement('option');
					var id = curr.getAttribute('id');
					option.textContent = id;
					option.setAttribute('value', id);
					select.appendChild(option);
				}
			);
		})();
		</script></td>
	</tr>
	<tr>
		<th><label for="bigbackground">background</label></th>
		<td><select id="bigbackground">
			<option value="" selected>none</option>
		</select>
		<script type="text/javascript">
		(function(){
			// Filling up the <select> with all available sample backgrounds.
			var select = document.getElementById('bigbackground');
			Array.prototype.forEach.call(
				document.querySelectorAll('#sample_bg symbol[id]'),
				function(curr, index, arr) {
					var option = document.createElement('option');
					var id = curr.getAttribute('id');
					option.textContent = id;
					option.setAttribute('value', id);
					select.appendChild(option);
				}
			);
		})();
		</script></td>
	</tr>
	<tr>
		<th></th>
		<td><label><input type="checkbox" id="bigpadding"> Add padding</td>
	</tr>
	<tr>
		<th><label for="bigvalue"><code>value</code></label></th>
		<td><input type="number" id="bigvalue" step="any" value=""></td>
	</tr>
</table>

<script type="text/javascript">

document.getElementById('bigdemo').addEventListener('input', function(ev) {
	if (['bigdivisions', 'bigmin', 'bigmax', 'bigsvgsymbolid', 'bigvalue'].indexOf(ev.target.id) > -1) {
		var attr = ev.target.id.replace('big', '');
		document.getElementById('big')[attr] = ev.target.value;
	} else if (ev.target.id === 'bigbackground') {
		document.getElementById('big_bg').innerHTML = '<use xlink:href="#' + ev.target.value + '" x="0" y="0" width="1" height="1"/>';
	}
	document.getElementById('bigvalue').value = document.getElementById('big').value;
});
document.getElementById('bigpadding').addEventListener('click', function(ev) {
	document.getElementById('big').style.padding = ev.target.checked ? '1em' : '0';
});
document.getElementById('bigdisabled').addEventListener('click', function(ev) {
	document.getElementById('big').disabled = ev.target.checked;
});
document.getElementById('bigreadonly').addEventListener('click', function(ev) {
	document.getElementById('big').readonly = ev.target.checked;
});

// 'input' event does not fire for <select> in Firefox.
// This (ugly) workaround will cause the 'input' event to fire twice for <select> in other browsers.
Array.prototype.forEach.call(
	document.querySelectorAll('select'),
	function(curr, index, arr) {
		curr.addEventListener('change', function(ev) {
			curr.dispatchEvent(new Event('input', {
				'bubbles': ev.bubbles,
				'cancelable': ev.cancelable
			}));
		});
	}
);

</script>


<h2>Misc. demos</h2>

<p>Several small demonstrations trying to showcase specific features or behaviors.</p>

<p id="simple"><x-knob></x-knob> Simplest markup "just works" with some reasonable defaults. It only requires importing <code>xknob.js</code> and <code>xknob.css</code>.</p>

<p id="sizes"><x-knob></x-knob> <x-knob></x-knob> <x-knob></x-knob> <x-knob></x-knob> <x-knob></x-knob> SVG graphics adapting to any size. Also means the click region is precisely the graphic itself, and nothing else.</p>

<p id="disabledreadonly"><x-knob disabled></x-knob> <x-knob readonly></x-knob> <x-knob disabled readonly></x-knob> Attributes <code>disabled</code> and <code>readonly</code> are available, but don't change the look.</p>

<p id="gfx"><x-knob svgsymbolid="circle-with-dot"></x-knob> <x-knob svgsymbolid="long-stick"></x-knob> <x-knob svgsymbolid="arrow"></x-knob> A custom SVG graphic can be selected by passing an <code>id</code> of an SVG <code>&lt;symbol&gt;</code> available from within the same document. The symbol will be (deep-)copied into the shadow DOM of the <code>x-knob</code> element. Symbols from external files are not supported.</p>

<p id="events"><x-knob id="eventsdemo"></x-knob> Supports <code>input</code> event (value = <output id="oninputvalue"></output>) and <code>change</code> event (value = <output id="onchangevalue"></output>). The <code>change</code> event is triggered if the value has changed after the mouse button is released, after the touch has ended, or after a keyboard press. The <code>input</code> event is triggered immediately.</p>

<p id="bubbling"><x-knob data-name="first knob"></x-knob> <x-knob data-name="second knob"></x-knob> The events bubble correctly: <output id="bubblingoutput"></output></p>

<p id="snap"><x-knob divisions="2"></x-knob> <x-knob divisions="3"></x-knob> <x-knob divisions="4"></x-knob> <x-knob divisions="8"></x-knob> <x-knob divisions="16"></x-knob> Optionally snaps the knob to discrete rotations. <output id="snapoutput"></output></p>

<p id="transition"><x-knob divisions="2"></x-knob> <x-knob divisions="3"></x-knob> <x-knob divisions="4"></x-knob> <x-knob divisions="8"></x-knob> <x-knob divisions="16"></x-knob> CSS transitions work fine together with discrete rotations, but don't look good on a continuous (analog) knob. <x-knob></x-knob></p>

<p id="minmax"><x-knob min="-1" max="1"></x-knob> <x-knob min="0"></x-knob> <x-knob max="2"></x-knob> <x-knob min="-0.3333" max="0.3333"></x-knob> Has support for <code>min</code> and <code>max</code> attributes. <output id="minmaxoutput"></output></p>

<p id="attrs"><input id="attrsdivisions" type="number" min="2" step="1" value="8" style="width: 4em" title="divisions"> <x-knob id="attrsmin" value="-1" title="min"></x-knob> <x-knob id="attrsmax" value="1" title="max"></x-knob> <x-knob id="attrsvalue"></x-knob> All attributes (<code>divisions</code>, <code>min</code>, <code>max</code>, <code>svgsymbolid</code>, <code>value</code>) can be set using standard DOM methods (such as <code>setAttribute()</code>) or directly to the object. <x-knob id="attrsdom"></x-knob> <x-knob id="attrsdirect"></x-knob></p>

<p id="updatingvalue"><x-knob divisions="0"></x-knob> <x-knob divisions="2"></x-knob> <x-knob divisions="3"></x-knob> <x-knob divisions="4"></x-knob> <x-knob divisions="8"></x-knob> <x-knob divisions="16"></x-knob> Updating the <code>value</code> of a knob that is being rotated may cause drifting. <output id="updatingvalueoutput"></output></p>

<script type="text/javascript">

var eventsdemo = document.getElementById('eventsdemo');
eventsdemo.addEventListener('input', function(ev) {
	document.getElementById('oninputvalue').value = ev.target.value;
});
eventsdemo.addEventListener('change', function(ev) {
	document.getElementById('onchangevalue').value = ev.target.value;
});


var bubbling = document.getElementById('bubbling');  // This is the <p> element.
var bubbling_handler = function(ev) {
	document.getElementById('bubblingoutput').value = ev.type + ' on ' + ev.target.dataset.name;
};
bubbling.addEventListener('input', bubbling_handler);
bubbling.addEventListener('change', bubbling_handler);


document.getElementById('snap').addEventListener('input', function(ev) {
	document.getElementById('snapoutput').value = Array.prototype.map.call(
		ev.currentTarget.querySelectorAll('x-knob'),
		function(x) { return x.value.toFixed(2); }
	).join(' | ');
});


document.getElementById('minmax').addEventListener('input', function(ev) {
	document.getElementById('minmaxoutput').value = Array.prototype.map.call(
		ev.currentTarget.querySelectorAll('x-knob'),
		function(x) { return x.value.toFixed(2); }
	).join(' | ');
});


document.getElementById('attrs').addEventListener('input', function(ev) {
	if (['attrsdivisions', 'attrsmin', 'attrsmax', 'attrsvalue'].indexOf(ev.target.id) > -1) {
		var attr = ev.target.id.replace('attrs', '');
		document.getElementById('attrsdom').setAttribute(attr, ev.target.value);
		document.getElementById('attrsdirect')[attr] = ev.target.value;
	}
});


document.getElementById('updatingvalue').addEventListener('input', function(ev) {
	// Since .value has a setter function, the following line may cause side-effects.
	ev.target.value = ev.target.value;
	document.getElementById('updatingvalueoutput').value = Array.prototype.map.call(
		ev.currentTarget.querySelectorAll('x-knob'),
		function(x) { return x.value.toFixed(2); }
	).join(' | ');
});

</script>

</body>
</html>

