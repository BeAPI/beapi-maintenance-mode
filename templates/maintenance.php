<!DOCTYPE html>
<html>
<head>
	<title>Service unavailable.</title>
	<style>
		html {
			width: 100%;
			height: 100%;
		}
		body {
			width: 100%;
			height: 100%;
			background: #232526;
			background: -webkit-linear-gradient(to right, #414345, #232526);
			background: linear-gradient(to right, #414345, #232526);
			font-family: "Open Sans", sans-serif;
			font-size: 13px;
			line-height: 1.4em;
		}
		.denied {
			background: #f7f7f7;
			position: absolute;
			top: 50%;
			left: 50%;
			transform: translate3d(-50%, -50%, 0);
			padding: 50px;
    		text-align: center;
		}
		.denied h1 {
			font-size: 40px;
    		line-height: 50px;
		}
		.denied p {
			font-size: 16px;
		}
		.denied p.icon {
			font-size: 30px;
    		margin-bottom: 0px;
		}
	</style>
</head>
<body>
	<div class="denied">
		<h1>Access Denied/Forbidden</h1>
		<p>Please contact your webmaster ...</p>
		<p class="icon">🔐</p>
	</div>
</body>
</html>