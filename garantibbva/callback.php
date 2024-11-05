<?php

require_once '../../../init.php';
require_once '../../../includes/gatewayfunctions.php';
require_once '../../../includes/invoicefunctions.php';

$gatewayModule = 'garantibbva';
$gatewayParams = getGatewayVariables($gatewayModule);

if (!$gatewayParams['type']) die("Modül aktif değil.");

$response = $_POST;

// HASH VALODATION
$hashparamsval = $_POST['hashparamsval'];
$store_key = $gatewayParams['store_key'];
$calculated_hash = strtoupper(sha1($hashparamsval . $store_key));

// CHECK IF PROCESS IS SUCCESSFULLY EXECUTED
if ($response['procreturncode'] == '00' && $calculated_hash == $response['hash']) {
    $invoiceId = $response['oid'];
    $transId = $response['hostrefnum'];
    $paymentAmount = $response['txnamount'] / 100; // cents to euro, pennies to dollar, kuruş to turkish lira conversion

    $invoiceId = checkCbInvoiceID($invoiceId, $gatewayParams['name']);
    checkCbTransID($transId);

    addInvoicePayment($invoiceId, $transId, $paymentAmount, 0, $gatewayModule);
    logTransaction($gatewayParams['name'], $response, "Succeed");
    header("Location: {$gatewayParams['systemurl']}/viewinvoice.php?id={$invoiceId}&paymentstatus=success");
} else {
    logTransaction($gatewayParams['name'], $response, "Failed");
    header("Location: {$gatewayParams['systemurl']}/viewinvoice.php?id={$response['oid']}&paymentstatus=fail");
}
?>
