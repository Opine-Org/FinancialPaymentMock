<?php
/**
 * Opine\FinancialPaymentMock
 *
 * Copyright (c)2013, 2014 Ryan Mahoney, https://github.com/Opine-Org <ryan@virtuecenter.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
namespace Opine;

class FinancialPaymentMock {
    public function payment ($orderId, $description, $amount, array $billingInfo, array $paymentInfo, &$response) {
        if (empty($description)) {
            throw new \Exception('Invalid Description');
        }
        if (!is_numeric($amount) || $amount === 0) {
            throw new \Exception('Invalid Amount');
        }
        if (!isset($paymentInfo['creditcard_number'])) {
            throw new \Exception('Card Number not set');
        }
        if (!isset($paymentInfo['creditcard_expiration_year']) || !isset($paymentInfo['creditcard_expiration_month'])) {
            throw new \Exception('Invalid card expiration');
        }
        if ($paymentInfo['creditcard_number'] == '1111111111111111') {
            $response = [
                'success' => true,
                'transaction_id' => uniqid(),
                'response' => 'ok'
            ];
            return true;
        }
        $response = [
            'success' => false,
            'errorMessage' => 'Invalid card number',
            'errorCode' => 400,
            'response' => 'error'
        ];
        return false;
    }

    public function authorize ($orderId, $description, $amount, array $billingInfo, array $paymentInfo, &$response) {
        return $this->payment($orderId, $description, $amount, $billingInfo, $paymentInfo, $response);
    }

    public function rollback ($response) {
        return true;
    }
}