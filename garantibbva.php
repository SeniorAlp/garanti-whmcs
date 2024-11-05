<?php

function garantibbva_MetaData() {
    return array(
        'DisplayName' => 'Garanti BBVA Virtual POS',
        'APIVersion' => '1.1',
        //AUTHOR: @a.alperensahin on IG
    );
}

function garantibbva_config() {
    $configarray = array(
        "FriendlyName" => array("Type" => "System", "Value" => "Garanti BBVA Virtual POS"),
        "merchant_id" => array("FriendlyName" => "Merchant ID", "Type" => "text", "Size" => "20"),
        "terminal_id" => array("FriendlyName" => "Terminal ID", "Type" => "text", "Size" => "20"),
        "terminal_prov_user_id" => array("FriendlyName" => "Provision User ID", "Type" => "text", "Size" => "20"),
        "store_key" => array("FriendlyName" => "Store Key", "Type" => "text", "Size" => "20"),
        "password" => array("FriendlyName" => "Provision Password", "Type" => "password", "Size" => "20"),
        "currencyCode" => array("FriendlyName" => "Currency(840=$, 949=₺, 978=€)", "Type" => "text", "Size" => "3"),
        "firm_name" => array("FriendlyName" => "Firm Name", "Type" => "text", "Size" => "20"),
        "test_mode" => array("FriendlyName" => "Test Modu", "Type" => "yesno", "Description" => "Run in test mode"),
    );
    return $configarray;
}
function garantibbva_link($params) {
    $gatewayurl = "https://sanalposprovtest.garantibbva.com.tr/servlet/gt3dengine";
    $mode = "TEST";
    if($params["test_mode"] == "off"){
        $mode = "PROD";
        $gatewayurl = "https://sanalposprov.garanti.com.tr/servlet/gt3dengine";
    }
    
    $merchant_id = $params['merchant_id'];
    $terminal_id = intval($params['terminal_id']);  
    $datetime = date('Y-m-d\TH:i:s\Z');
    
    $prov_user_id = $params['terminal_prov_user_id'];
    $store_key = $params['store_key'];
    $password = $params['password'];
    $currencyCode = $params['currencyCode'];
    $firmName = $params['firm_name'];
    $installmentCount = "";
    $amount = $params['amount'];
    $order_id = $params['invoiceid'];
    $order = $order_id;
    $success_url = $params['systemurl'] . 'modules/gateways/garantibbva/callback.php';
    $fail_url = $params['systemurl'] . 'modules/gateways/garantibbva/callback.php';
    $customer_email = $params['clientdetails']['email'];
    $type = "sales";
    
    //IMPORTANT DON'T TOUCH THIS CODE
    $security_data =  strtoupper(sha1($password.'0'.$terminal_id));
    $hash_data = strtoupper(hash('sha512',$terminal_id. $order . (intval($amount)*100) .$currencyCode. $success_url . $fail_url .$type .$installmentCount . $store_key . $security_data));
    // DONT TOUCH THIS CODE 

   
    // PAYMENT FORM START
    $html = '<form id="garantiForm" role="form" method="post" action="' . $gatewayurl . '">';
    $html .= '<input type="hidden" name="secure3dsecuritylevel" id="secure3dsecuritylevel" value="3D_PAY">';
    $html .= '<input type="hidden" name="mode" id="mode" value="'.$mode.'" />';
    $html .= '<input type="hidden" name="apiversion" id="apiversion" value="512" />';
    $html .= '<input type="hidden" name="txntype" id="txntype" value="sales" />';
    //<!--CURRENCY VALUES: TR: "949" | USD: "840" | EURO: "978" | GBP: "826" | JPY: "392"-->
    $html .= '<input type="hidden" name="txncurrencycode" id="txncurrencycode" value="'.$currencyCode.'" />';

    $html .= '<input type="hidden" name="txninstallmentcount" id="txninstallmentcount" value="" />';
    //<!--PROCESS TIME (UTC)-->
    $html .= '<input type="hidden" name="txntimestamp" id="txntimestamp" value="'.$datetime.'" />';
    $html .= '<input type="hidden" name="terminaluserid" id="terminaluserid" value="GARANTI" />';
    $html .= '<input type="hidden" name="terminalid" id="terminalid" value="' . $terminal_id . '">';
    $html .= '<input type="hidden" name="terminalprovuserid" id="terminalprovuserid" value="' . $prov_user_id . '">';
    $html .= '<input type="hidden" name="terminalmerchantid" id="terminalmerchantid" value="' . $merchant_id . '">';
    
    $html .= '<input type="hidden" name="txnamount" id="txnamount" value="' . (intval($amount)*100) . '">';
    $html .= '<input type="hidden" name="orderid" id="orderid" value="' . $order . '">';
    $html .= '<input type="hidden" name="customeremailaddress" value="' . $customer_email . '">';
    $html .= '<input type="hidden" name="successurl" id="successurl" value="' . $success_url . '">';
    $html .= '<input type="hidden" name="errorurl" id="errorurl" value="' . $fail_url . '">';
    $html .= '<input type="hidden" name="secure3dhash" id="secure3dhash" value="' . $hash_data . '">';

    //<!--FIRM NAME-->
    $html .= '<input type="hidden" name="companyname"  id="companyname" Value="'.$firmName.'" />';
    //<!--LANGUAGE-->
    $html .= '<input type="hidden" name="lang" id="lang" Value="tr" />';
    //<!--REFRESH TIME-->
    $html .= '<input type="hidden" name="refreshtime" id="refreshtime" value="1" />';



    //<!--Müşteri IP Adresi--> WOULD BE BETTER IF YOU GET CUSTOMER'S IP ADDRESS INTO VALUE PARAMETER
    $html .= '<input type="hidden" name="customeripaddress" id="customeripaddress" value="8.8.8.8" />';
    

    // YOU CAN EDIT THIS AREA START                 (PLEASE SAVE ELEMENT NAMES WHILE CHANGING)
    //<!--Müşteri Kart Üzerindeki Adı--> card holder 
    $html .= '<input name="cardholdername" value="Test User" />';
    //<!--Müşteri Kart Numarası-->  card number max 16
    $html .= '<input name="cardnumber" value="5406697543211173" />';
    //<!--Müşteri Kartı Son Kullanma Ay--> exp month
    $html .= '<input name="cardexpiredatemonth" value="03"/>';
    //<!--Müşteri Kartı Son Kullanma Yıl--> exp year
    $html .= '<input name="cardexpiredateyear" value="23" />';
    //<!--Müşteri Kartı CVC Güvenlik Numarası--> cvv2
    $html .= '<input name="cardcvv2" value="465" />';
    // submit button
    $html .= '<button type="submit">Pay now</button>';
    // YOU CAN EDIT THIS AREA END


    $html .= '</form>';
    //PAYMENT FORM END

    return $html;
}
?>