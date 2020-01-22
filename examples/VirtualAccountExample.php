<?php

/**
 * VirtualAccountExample.php
 * php version 7.2.0
 *
 * @category Example
 * @package  Xendit/Examples
 * @author   Ellen <ellen@xendit.co>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://api.xendit.co
 */

use Xendit\Xendit;

require 'vendor/autoload.php';

Xendit::setApiKey(
    <<<'TAG'
xnd_development_prHUBDfVuOQTxyWTQSNkpjn9OwX9ZSUjdqgF9GenZ6hwhUQkc3NZ9WVexdH
TAG
);

$params = ["external_id" => "VA_fixed-12341234",
   "bank_code" => "MANDIRI",
   "name" => "Steve Wozniak"
];

$createVA = \Xendit\VirtualAccounts::create($params);
var_dump($createVA);

$id = $createVA['id'];

$getVABanks = \Xendit\VirtualAccounts::getVABanks();
var_dump($getVABanks);

$getVA = \Xendit\VirtualAccounts::retrieve($id);
var_dump($getVA);

$updateParams = ["suggested_amount" => 1000];

$updateVA = \Xendit\VirtualAccounts::update($id, $updateParams);
var_dump($updateVA);

$paymentID = "VA_fixed-1579666681_1579666702976"; //got from test account

$getFVAPayment = \Xendit\VirtualAccounts::getFVAPayment($paymentID);
var_dump($getFVAPayment);
