<?php

class Payment extends Controller {
	private $errorMessages = array(
		'CREDIT_CARD_NUMBER_NOT_GIVEN' => "No credit card number given.",
		'CREDIT_CARD_NUMBER_CONTAINS_INVALID_CHARACTER' => "Credit card number should contains only numbers, no other characters allowed.",
		'CREDIT_CARD_NUMBER_LENGTH' => "Credit card number must be 16 long.",
		'CREDIT_CARD_NUMBER_INVALID' => "Credit card number is invalid.",
		'CREDIT_CARD_EXPIRED' => "Credit card expired.",

		'EXPIRATION_MONTH_NOT_GIVEN' => "No credit card expiration month given.",
		'EXPIRATION_MONTH_SHOULD_BE_NUMBER' => "Credit card expiration month should be given as number between 1 and 12.",
		'EXPIRATION_MONTH_NUMBER_INVALID' => "Credit card expiration month is out of range, please enter a number between 1 and 12.",
	
		'EXPIRATION_YEAR_NOT_GIVEN' => "No credit card expiration year given.",
		'EXPIRATION_YEAR_SHOULD_BE_NUMBER' => "Credit card expiration year should be a number, for example: 2050.",
		'EXPIRATION_YEAR_NUMBER_INVALID' => "Credit card expiration year must be a higher than 1958, because first credit card was introduced back then.",

		'AMONT_TO_PAY_NOT_GIVEN' => "No amount to pay given.",
		'AMONT_TO_PAY_SHOULD_BE_NUMBER' => "The amount to pay must contain only number.",
		'AMONT_TO_PAY_NUMBER_INVALID' => "Amount to pay should be between 1 HUF and 1000000 HUF.",
	);

	public function index() {
		$this->view('payment/index');
	}

	public function submit() {
		$this->paymentError = $this->model('PaymentError');
		$this->collectErrorsRelatedToPaymentVerification();
		$areTherePaymentErrors = count(array_filter($this->paymentError->errors)) > 0;
		if ($areTherePaymentErrors) {
			$this->view('payment/index', $this->paymentError->errors);
		}
	}

	private function collectErrorsRelatedToPaymentVerification() {
		$this->getErrorMessageRelatedToInvalidCreditCardNumber();
		$this->getErrorMessageRelatedToCreditCardExpirationMonth();
		$this->getErrorMessageRelatedToCreditCardExpirationYear();
		$this->getErrorMessageRelatedToAmountToPay();
		$this->getErrorMessageRelatedToCreditCardExpired(
			!isset($this->paymentError->errors['creditCardExpirationMonthError']),
			!isset($this->paymentError->errors['creditCardExpirationYearError'])
		);
	}

	private function getErrorMessageRelatedToInvalidCreditCardNumber() {
		if (empty(filter_input(INPUT_POST,'creditCardNumber'))) {
			$this->paymentError->errors['creditCardNumberError'] = $this->errorMessages['CREDIT_CARD_NUMBER_NOT_GIVEN'];
		} elseif (!is_numeric(filter_input(INPUT_POST,'creditCardNumber'))) {
			$this->paymentError->errors['creditCardNumberError'] = $this->errorMessages['CREDIT_CARD_NUMBER_CONTAINS_INVALID_CHARACTER'];
		} elseif (strlen((string)filter_input(INPUT_POST,'creditCardNumber')) != 16) {
			$this->paymentError->errors['creditCardNumberError'] = $this->errorMessages['CREDIT_CARD_NUMBER_LENGTH'];
		} elseif (!$this->isCreditCardNumberCorrectAccordingToLuhnAlgorithm(filter_input(INPUT_POST,'creditCardNumber'))) {
			$this->paymentError->errors['creditCardNumberError'] = $this->errorMessages['CREDIT_CARD_NUMBER_INVALID'];
		}
	}

	private function getErrorMessageRelatedToCreditCardExpirationMonth() {
		if (empty(filter_input(INPUT_POST,'creditCardExpirationMonth'))) {
			$this->paymentError->errors['creditCardExpirationMonthError'] = $this->errorMessages['EXPIRATION_MONTH_NOT_GIVEN'];
		} elseif (!is_numeric(filter_input(INPUT_POST,'creditCardExpirationMonth'))) {
			$this->paymentError->errors['creditCardExpirationMonthError'] = $this->errorMessages['EXPIRATION_MONTH_SHOULD_BE_NUMBER'];
		} elseif (filter_input(INPUT_POST,'creditCardExpirationMonth') < 1 or filter_input(INPUT_POST,'creditCardExpirationMonth') > 12) {
			$this->paymentError->errors['creditCardExpirationMonthError'] = $this->errorMessages['EXPIRATION_MONTH_NUMBER_INVALID'];
		}
	}

	private function getErrorMessageRelatedToCreditCardExpirationYear() {
		if (empty(filter_input(INPUT_POST,'creditCardExpirationYear'))) {
			$this->paymentError->errors['creditCardExpirationYearError'] = $this->errorMessages['EXPIRATION_YEAR_NOT_GIVEN'];
		} elseif (!is_numeric(filter_input(INPUT_POST,'creditCardExpirationYear'))) {
			$this->paymentError->errors['creditCardExpirationYearError'] = $this->errorMessages['EXPIRATION_YEAR_SHOULD_BE_NUMBER'];
		} elseif (filter_input(INPUT_POST,'creditCardExpirationYear') < 1958 ) {
			$this->paymentError->errors['creditCardExpirationYearError'] = $this->errorMessages['EXPIRATION_YEAR_NUMBER_INVALID'];
		}
	}

	private function getErrorMessageRelatedToAmountToPay() {
		if (empty(filter_input(INPUT_POST,'amountToPay'))) {
			$this->paymentError->errors['amountToPayError'] = $this->errorMessages['AMONT_TO_PAY_NOT_GIVEN'];
		} elseif (!is_numeric(filter_input(INPUT_POST,'amountToPay'))) {
			$this->paymentError->errors['amountToPayError'] = $this->errorMessages['AMONT_TO_PAY_SHOULD_BE_NUMBER'];
		} elseif (1 > filter_input(INPUT_POST,'amountToPay') or filter_input(INPUT_POST,'amountToPay') > 1000000) {
			$this->paymentError->errors['amountToPayError'] = $this->errorMessages['AMONT_TO_PAY_NUMBER_INVALID'];
		}
	}

	private function getErrorMessageRelatedToCreditCardExpired($isCreditCardExpirateionMonthValid, $isCreditCardExpirateionYearValid) {
		if ($isCreditCardExpirateionMonthValid and $isCreditCardExpirateionYearValid) {
			if (
				$this->isCreditCardExpired(
					filter_input(INPUT_POST,'creditCardExpirationYear'),
					filter_input(INPUT_POST,'creditCardExpirationMonth'))
				) {
				$this->paymentError->errors['creditCardExpiredError'] = $this->errorMessages['CREDIT_CARD_EXPIRED'];
			}
		}
	}

	private function isCreditCardNumberCorrectAccordingToLuhnAlgorithm($creditCardNumber) {
		$modifiedCreditCardNumbers = '';
		foreach (str_split(strrev((string) $creditCardNumber)) as $indexOfCurrentNumber => $currentNumber) {
			$modifiedCreditCardNumbers .= $indexOfCurrentNumber %2 !== 0 ? $currentNumber * 2 : $currentNumber;
		}
		return array_sum(str_split($modifiedCreditCardNumbers)) % 10 === 0;
	}

	private function isCreditCardExpired($year, $month) {
		$currentYear = date("Y");
		$currentMonth = date("n");
		return $year == $currentYear 
						? $month < $currentMonth
						: $year < $currentYear;
	}
}
