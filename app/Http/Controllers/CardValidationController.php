<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class CardValidationController extends Controller
{
    public function validateCard(Request $request)
    {
        $cardNumber = $request['data']['card_number'];
        $expiryMonth = $request['data']['expiry_month'];
        $expiryYear = $request['data']['expiry_year'];
        $cvv = $request['data']['cvv'];

        if (!$this->isValidExpiryDate($expiryMonth, $expiryYear)) {
            $response = array(
                'status' => 'error',
                'message' => 'Card Expired',
            );
        } elseif (!$this->isValidCvv($cardNumber, $cvv)) {
            $response = array(
                'status' => 'error',
                'message' => 'Invalid CVV',
            );
        } elseif (!$this->isValidCardNumber($cardNumber)){
            $response = array(
                'status' => 'error',
                'message' => 'Card Number must be between 16 and 19 digits long ',
            );
        }else {
            $response = array(
                'status' => 'success',
                'message' => 'Card is Valid',
            );
        }


        return response()->json($response);
    }

    public function isValidExpiryDate($month, $year)
    {
        $expiryDate = Carbon::createFromDate($year, $month);
        $currentDate = Carbon::now();
        return !($expiryDate < $currentDate);
    }

    public function isValidCvv($cardNumber, $cvv)
    {
        if ($this->isAmericanExpress($cardNumber)) {
            $isValid = strlen($cvv) == 4;
        } else {
            $isValid = strlen($cvv) == 3;
        }
        return $isValid;
    }

    public function isAmericanExpress($cardNumber): bool
    {
        $cardFirstTwoDigits = substr($cardNumber, 0, 2);
        return $cardFirstTwoDigits == '34' || $cardFirstTwoDigits == '37';
    }

    public function isValidCardNumber($cardNumber){
        return strlen($cardNumber) >= 16 && strlen($cardNumber) <= 19;
    }
}
