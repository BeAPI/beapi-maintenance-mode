<!DOCTYPE html>
<html>
<head>
	<title><?php esc_html_e( 'Service unavailable.', 'beapi-maintenance-mode-mode' ); ?></title>
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
			margin-bottom: 0;
		}
	</style>
</head>
<body>
<div class="denied">
	<h1><?php esc_html_e( 'Access Denied/Forbidden', 'beapi-maintenance-mode-mode' ); ?></h1>
	<p><?php esc_html_e( 'Please contact your webmaster ...', 'beapi-maintenance-mode-mode' ); ?></p>
	<p class="icon">🔐</p>
</div>
</body>
</html>