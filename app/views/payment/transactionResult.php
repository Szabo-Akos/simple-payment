<?php
	include ('../app/views/header.php');
	?>
	</head>
	<body>
		<div class="container">
			<div class="card">
				<p> The transaction was successful:
				<?php if(isset($data['amountPaidInEuro'])) { echo $data['amountPaidInEuro']; } ?> EUR paid. </p>
				<form method="post" action="<?php echo htmlspecialchars('index');?>">
					<button class="submit" type="submit">NEW TRANSACTION</button>
				</form>
			</div>
		</div>
	</body>
</html>
