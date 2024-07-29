
@php

$url_actual = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

$exp_id_porduct = explode('/',$url_actual);
$id_product = $exp_id_porduct[5];

// header('Content-type: "text/xml"');
// header('Content-disposition: attachment; filename="invoice.xml"');
// header('Content-type: "text/xml"; charset="utf8"');
// readfile('file.xml');
// ob_clean();


$xml  = "<?xml version='1.0'>".PHP_EOL;
$xml .= '<element name="client_corporate" type="invoiceRetentionsType" minOccurs="0">'.PHP_EOL;
$xml .= '<annotation>';
$xml .= '<documentation>'. $data['client_corporate'] .'</documentation>'.PHP_EOL;
$xml .= '</annotation>'.PHP_EOL;
$xml .= '</element>'.PHP_EOL;

$xml .= '<element name="client_name" type="invoiceRetentionsType" minOccurs="0">'.PHP_EOL;
$xml .= '<annotation>';
$xml .= '<documentation>'. $data['client_name'] .'</documentation>'.PHP_EOL;
$xml .= '</annotation>'.PHP_EOL;
$xml .= '</element>'.PHP_EOL;

$xml .= '<element name="client_id" type="invoiceRetentionsType" minOccurs="0">'.PHP_EOL;
$xml .= '<annotation>';
$xml .= '<documentation>'. $data['client_id'] .'</documentation>'.PHP_EOL;
$xml .= '</annotation>'.PHP_EOL;
$xml .= '</element>'.PHP_EOL;

$xml .= '<element name="client_email" type="invoiceRetentionsType" minOccurs="0">'.PHP_EOL;
$xml .= '<annotation>';
$xml .= '<documentation>'. $data['client_email'] .'</documentation>'.PHP_EOL;
$xml .= '</annotation>'.PHP_EOL;
$xml .= '</element>'.PHP_EOL;

$xml .= '<element name="client_cell" type="invoiceRetentionsType" minOccurs="0">'.PHP_EOL;
$xml .= '<annotation>';
$xml .= '<documentation>'. $data['client_cell'] .'</documentation>'.PHP_EOL;
$xml .= '</annotation>'.PHP_EOL;
$xml .= '</element>'.PHP_EOL;

$xml .= '<element name="client_address" type="invoiceRetentionsType" minOccurs="0">'.PHP_EOL;
$xml .= '<annotation>';
$xml .= '<documentation>'. $data['client_address'] .'</documentation>'.PHP_EOL;
$xml .= '</annotation>'.PHP_EOL;
$xml .= '</element>'.PHP_EOL;

$xml .= '<element name="client_postcode" type="invoiceRetentionsType" minOccurs="0">'.PHP_EOL;
$xml .= '<annotation>';
$xml .= '<documentation>'. $data['client_postcode'] .'</documentation>'.PHP_EOL;
$xml .= '</annotation>'.PHP_EOL;
$xml .= '</element>'.PHP_EOL;

$xml .= '<element name="client_country" type="invoiceRetentionsType" minOccurs="0">'.PHP_EOL;
$xml .= '<annotation>';
$xml .= '<documentation>'. $data['client_country'] .'</documentation>'.PHP_EOL;
$xml .= '</annotation>'.PHP_EOL;
$xml .= '</element>'.PHP_EOL;

$xml .= '<element name="metodo_pay" type="invoiceRetentionsType" minOccurs="0">'.PHP_EOL;
$xml .= '<annotation>';
$xml .= '<documentation>'. $data['metodo_pay'] .'</documentation>'.PHP_EOL;
$xml .= '</annotation>'.PHP_EOL;
$xml .= '</element>'.PHP_EOL;

$xml .= '<element name="order" type="invoiceRetentionsType" minOccurs="0">'.PHP_EOL;
$xml .= '<annotation>';
$xml .= '<documentation>'. $data['order'] .'</documentation>'.PHP_EOL;
$xml .= '</annotation>'.PHP_EOL;
$xml .= '</element>'.PHP_EOL;

$xml .= '<element name="total_order" type="invoiceRetentionsType" minOccurs="0">'.PHP_EOL;
$xml .= '<annotation>';
$xml .= '<documentation>'. $data['total_order'] .'</documentation>'.PHP_EOL;
$xml .= '</annotation>'.PHP_EOL;
$xml .= '</element>'.PHP_EOL;

$xml .= '<element name="total_vat" type="invoiceRetentionsType" minOccurs="0">'.PHP_EOL;
$xml .= '<annotation>';
$xml .= '<documentation>'. $data['total_vat'] .'</documentation>'.PHP_EOL;
$xml .= '</annotation>'.PHP_EOL;
$xml .= '</element>'.PHP_EOL;

$xml .= '<element name="total_shipping" type="invoiceRetentionsType" minOccurs="0">'.PHP_EOL;
$xml .= '<annotation>';
$xml .= '<documentation>'. $data['total_shipping'] .'</documentation>'.PHP_EOL;
$xml .= '</annotation>'.PHP_EOL;
$xml .= '</element>'.PHP_EOL;

$xml .= '<element name="address" type="invoiceRetentionsType" minOccurs="0">'.PHP_EOL;
$xml .= '<annotation>';
$xml .= '<documentation>'. $data['address'] .'</documentation>'.PHP_EOL;
$xml .= '</annotation>'.PHP_EOL;
$xml .= '</element>'.PHP_EOL;

$xml .= '<element name="zip" type="invoiceRetentionsType" minOccurs="0">'.PHP_EOL;
$xml .= '<annotation>';
$xml .= '<documentation>'. $data['zip'] .'</documentation>'.PHP_EOL;
$xml .= '</annotation>'.PHP_EOL;
$xml .= '</element>'.PHP_EOL;

$xml .= '<element name="neighborhood" type="invoiceRetentionsType" minOccurs="0">'.PHP_EOL;
$xml .= '<annotation>';
$xml .= '<documentation>'. $data['neighborhood'] .'</documentation>'.PHP_EOL;
$xml .= '</annotation>'.PHP_EOL;
$xml .= '</element>'.PHP_EOL;

$xml .= '<element name="number" type="invoiceRetentionsType" minOccurs="0">'.PHP_EOL;
$xml .= '<annotation>';
$xml .= '<documentation>'. $data['number'] .'</documentation>'.PHP_EOL;
$xml .= '</annotation>'.PHP_EOL;
$xml .= '</element>'.PHP_EOL;

$xml .= '<element name="complement" type="invoiceRetentionsType" minOccurs="0">'.PHP_EOL;
$xml .= '<annotation>';
$xml .= '<documentation>'. $data['complement'] .'</documentation>'.PHP_EOL;
$xml .= '</annotation>'.PHP_EOL;
$xml .= '</element>'.PHP_EOL;

$xml .= '<element name="country" type="invoiceRetentionsType" minOccurs="0">'.PHP_EOL;
$xml .= '<annotation>';
$xml .= '<documentation>'. $data['country'] .'</documentation>'.PHP_EOL;
$xml .= '</annotation>'.PHP_EOL;
$xml .= '</element>'.PHP_EOL;

$xml .= '<element name="product_name" type="invoiceRetentionsType" minOccurs="0">'.PHP_EOL;
$xml .= '<annotation>';
$xml .= '<documentation>'. $data['products'][$id_product]['name'] .'</documentation>'.PHP_EOL;
$xml .= '</annotation>'.PHP_EOL;
$xml .= '</element>'.PHP_EOL;

$xml .= '<element name="product_amount" type="invoiceRetentionsType" minOccurs="0">'.PHP_EOL;
$xml .= '<annotation>';
$xml .= '<documentation>'. $data['products'][$id_product]['amount'] .'</documentation>'.PHP_EOL;
$xml .= '</annotation>'.PHP_EOL;
$xml .= '</element>'.PHP_EOL;

$xml .= '<element name="product_unit" type="invoiceRetentionsType" minOccurs="0">'.PHP_EOL;
$xml .= '<annotation>';
$xml .= '<documentation>'. $data['products'][$id_product]['unit'] .'</documentation>'.PHP_EOL;
$xml .= '</annotation>'.PHP_EOL;
$xml .= '</element>'.PHP_EOL;

$xml .= '<element name="product_total" type="invoiceRetentionsType" minOccurs="0">'.PHP_EOL;
$xml .= '<annotation>';
$xml .= '<documentation>'. $data['products'][$id_product]['total'] .'</documentation>'.PHP_EOL;
$xml .= '</annotation>'.PHP_EOL;
$xml .= '</element>'.PHP_EOL;

$xml .= '<element name="product_porcentVat" type="invoiceRetentionsType" minOccurs="0">'.PHP_EOL;
$xml .= '<annotation>';
$xml .= '<documentation>'. $data['products'][$id_product]['porcentVat'] .'</documentation>'.PHP_EOL;
$xml .= '</annotation>'.PHP_EOL;
$xml .= '</element>'.PHP_EOL;

$xml .= '<element name="product_vat" type="invoiceRetentionsType" minOccurs="0">'.PHP_EOL;
$xml .= '<annotation>';
$xml .= '<documentation>'. $data['products'][$id_product]['vat'] .'</documentation>'.PHP_EOL;
$xml .= '</annotation>'.PHP_EOL;
$xml .= '</element>'.PHP_EOL;



$file  = fopen('pdf/invoice_order.xml', 'w+');
$write = fwrite($file, $xml);
fclose($file);

$fileName = "pdf/invoice_order.xml";

header('Content-Description: File Transfer');
header('Content-Disposition: attachment; filename="invoice_order.xml"');
header('Content-Type: application/octet-stream');
header('Content-Transfer-Encoding: binary');
// header('Content-Length: ' . filesize($fileName));
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Expires: 0');

readfile($fileName);

exit();

@endphp
