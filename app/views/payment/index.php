<?php
	include ('../app/views/header.php');
	?>
	</head>
	<body>
		<div class="container">
			<div class="card">
				<form method="post">
					<div>
						<label for="creditCardNumber">Credit card number:</label>
						<input type="number" id="creditCardNumber" name="creditCardNumber" placeholder="6548790134512794">
						<label for="creditCardExpirationMonth">Credit expiration month:</label>
						<input type="number" id="creditCardExpirationMonth" name="creditCardExpirationMonth" min="1" max="12"
								placeholder="2">
						<label for="creditCardExpirationYear">Credit expiration year:</label>
						<input type="number" id="creditCardExpirationYear" name="creditCardExpirationYear" min="2010" max="2050"
								placeholder="2025">
						<label for="amountToPay">Amount to pay in HUF:</label>
						<input type="number" id="amountToPay" name="amountToPay" min="1" max="1000000" placeholder="1">
					</div>
					<button class="submit" type="submit">SUBMIT</button>
				</form>
			</div>
		</div>
	</body>
</html>
