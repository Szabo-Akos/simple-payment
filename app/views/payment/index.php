<?php
	include ('../app/views/header.php');
	?>
	</head>
	<body>
		<div class="container">
			<div class="card">
				<form method="post" action="<?php echo htmlspecialchars('submit');?>">
					<div>
						<label for="creditCardNumber">Credit card number:</label>
						<input type="number" id="creditCardNumber" name="creditCardNumber" placeholder="6548790134512794"
										value="<?php if(isset($_POST['creditCardNumber'])) { echo $_POST['creditCardNumber']; } ?>">
						<?php if(isset($data['creditCardNumberError'])) { ?>
							<p class="error"><?php echo $data['creditCardNumberError'] ?></p>
						<?php } ?>
						<label for="creditCardExpirationMonth">Credit card expiration month:</label>
						<input type="number" id="creditCardExpirationMonth" name="creditCardExpirationMonth" min="1" max="12" placeholder="2"
										value="<?php if(isset($_POST['creditCardExpirationMonth'])) { echo $_POST['creditCardExpirationMonth']; } ?>">
						<?php if(isset($data['creditCardExpirationMonthError'])) { ?>
							<p class="error"><?php echo $data['creditCardExpirationMonthError'] ?></p>
						<?php } ?>
						<label for="creditCardExpirationYear">Credit card expiration year:</label>
						<input type="number" id="creditCardExpirationYear" name="creditCardExpirationYear" min="1958" placeholder="1958"
										value="<?php if(isset($_POST['creditCardExpirationYear'])) { echo $_POST['creditCardExpirationYear']; } ?>">
						<?php if(isset($data['creditCardExpirationYearError'])) { ?>
							<p class="error"><?php echo $data['creditCardExpirationYearError'] ?></p>
						<?php } ?>
						<?php if(isset($data['creditCardExpiredError'])) { ?>
							<p class="error"><?php echo $data['creditCardExpiredError'] ?></p>
						<?php } ?>
						<label for="amountToPay">Amount to pay in HUF:</label>
						<input type="number" id="amountToPay" name="amountToPay" min="1" max="1000000" placeholder="1"
										value="<?php if(isset($_POST['amountToPay'])) { echo $_POST['amountToPay']; } ?>">
						<?php if(isset($data['amountToPayError'])) { ?>
							<p class="error"><?php echo $data['amountToPayError'] ?></p>
						<?php } ?>
					</div>
					<button class="submit" type="submit">SUBMIT</button>
				</form>
			</div>
		</div>
	</body>
</html>
