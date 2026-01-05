<?php if((!$indexing) || ($logged != 1)) { exit; }
require('private/classes/classDonate.php');
?>

<ul class="breadcrumb">
	<li><a href='./?module=donate&page=add'><i class='fa fa-money'></i> <?php echo $LANG[12039]; ?></a></li>
	<li><a href='./?module=donate&page=orders'><?php echo $LANG[10015]; ?></a></li>
	<li><?php echo $LANG[10052]; ?></li>
</ul>

<?php

$protocolo = !empty($_GET['f']) ? intval($_GET['f']) : 0;

if(empty($protocolo)) { echo "<script>document.location.replace('./?module=donate&page=orders');</script>"; exit; }

$donation = Donate::findDonation($_SESSION['acc'], $protocolo);
if(count($donation) > 0) {
	
	$mpxpl = explode('_', $donation[0]['metodo_pgto']);
	$metodo_pgto = $mpxpl[0];
	
	echo "
	<h1>".$LANG[10052]."</h1>
	<div class='pddInner'>
		<b>".$LANG[10029].":</b> ".$donation[0]['protocolo']."<br />
		<b>".$LANG[10030].":</b> ".$donation[0]['quant_coins']."<br />
		<b>".$LANG[10031].":</b> ".$donation[0]['coins_bonus']."<br />
		<b>".$LANG[10032].":</b> ".($donation[0]['quant_coins']+$donation[0]['coins_bonus'])."<br />
		<b>".$LANG[10034].":</b> ".obtainCurrencySymbol($donation[0]['currency'])." ".number_format(trim($donation[0]['valor']), 2, ',', '.')." (".$donation[0]['currency'].")<br />
		<b>".$LANG[10035].":</b> ".date('d F, Y H:i', $donation[0]['data'])."<br />
		<b>".$LANG[10036].":</b> ".(!empty($donation[0]['ultima_alteracao']) ? date('d/m/Y H:i', $donation[0]['ultima_alteracao']) : $LANG[10039])."<br />
		<b>".$LANG[10037].":</b> ".$metodo_pgto."<br />
		<b>".$LANG[10038].":</b> ".obtainOrderStatusName($donation[0]['status'])."<br /><br />
		".$LANG[10045]."
	</div>
	";
	
	if($donation[0]['status'] == 1) {
		
		$donateDesc = $LANG[10052]." ".$donation[0]['protocolo']." - ".$donation[0]['quant_coins']." ".$coinName;
		
		switch(strtolower($metodo_pgto)) {
			
			case 'pagseguro':
			
				echo "
			    <form target='_blank' method='POST' action='".($PagSeguro['testando'] == 1 ? 'https://sandbox.pagseguro.uol.com.br/v2/checkout/payment.html' : 'https://pagseguro.uol.com.br/v2/checkout/payment.html')."'>  
		            <input name='receiverEmail' value='".$PagSeguro['email']."' type='hidden' />  
		            <input name='currency' value='".$donation[0]['currency']."' type='hidden' />  
		            <input name='itemId1' value='1' type='hidden' />  
		            <input name='itemDescription1' value='".$donateDesc."' type='hidden' />  
		            <input name='itemAmount1' value='".number_format(trim($donation[0]['valor']), 2, '.', '')."' type='hidden' />  
		            <input name='itemQuantity1' value='1' type='hidden' />  
		            <input name='reference' value='".$donation[0]['protocolo']."' type='hidden' />
		            <input type='submit' value='".$LANG[10042]."' class='default big' style='margin-left:20px;' />
		        </form>
		        ";
				
			break;
		
			case 'banking':
			
				echo "
				<h1>".$LANG[10040]."</h1>
				<div class='pddInner'>
					".$LANG[15005].":<br /><br />
					".$Banking['bank_dados']."
					<br /><br />
					<div class='rmsg warn'>".$LANG[15004]."</div>
				</div>
				";
				
			break;
		
			case 'picpay':
			
				echo "
				<h1>".$LANG[10040]."</h1>
				<div class='pddInner'>
					PicPay: <b>".$PicPay['name']."</b><br />
					Download app: <a target='_blank' href='https://www.picpay.com/site/download'>https://www.picpay.com/site/download</a>
					<br /><br />
					<div class='rmsg warn'>".$LANG[15004].": <b>".$donateEmail."</b></div>
				</div>
				";
				
			break;
		
			case 'paypal':
			
				echo "
				<form target='_blank' method='POST' action='https://www.paypal.com/cgi-bin/webscr'>
					<input type='hidden' name='cmd' value='_xclick' />
					<input type='hidden' name='business' value='".$PayPal['business_email']."' />
					<input type='hidden' name='currency_code' value='".$donation[0]['currency']."' />
					<input type='hidden' name='item_name' value='".$donateDesc."' />
					<input type='hidden' name='amount' value='".number_format(trim($donation[0]['valor']), 2, '.', '')."' />
					<input type='hidden' name='quantity' value='1' />
					<input type='hidden' name='custom' value='".$donation[0]['protocolo']."' />
					<input type='submit' value='".$LANG[10042]."' class='default big' style='margin-left:20px;' />
				</form>
				";
				
			break;
		
			case 'mercadopago':
			
				require_once('private/mp/mercadopago.php');
				
				$mp = new MP(trim($MercadoPago['access_token']));
				
				$preference_data = array(
					"external_reference" => $donation[0]['protocolo'],
					"items" => array(
						array(
							"title" => "".$donateDesc."",
							"quantity" => 1,
							"currency_id" => "".$donation[0]['currency']."",
							"unit_price" => ceil(trim($donation[0]['valor']))
						)
					)
				);
				
				$preference = $mp->create_preference($preference_data);
		
				echo "
				<a href='".$preference['response']['init_point']."' name='MP-Checkout' class='default big' style='margin-left:20px;'>".$LANG[10042]."</a>
				<script type='text/javascript' src='//resources.mlstatic.com/mptools/render.js'></script>
				";
				
			break;
		
			case 'paygol':
			
				echo "
				<form target='_blank' method='POST' action='https://www.paygol.com/pay' >
					<input type='hidden' name='pg_serviceid' value='".trim($PayGol['service_id'])."'>
					<input type='hidden' name='pg_currency' value='".$donation[0]['currency']."'>
					<input type='hidden' name='pg_name' value='".$donateDesc."'>
					<input type='hidden' name='pg_custom' value='".$donation[0]['protocolo']."'>
					<input type='hidden' name='pg_price' value='".number_format(ceil($donation[0]['valor']), 2, '.', '')."'>
					<input type='submit' value='".$LANG[10042]."' class='default big' style='margin-left:20px;' />
				</form>
				";
				
			break;
		
			case 'webmoney':
				
				echo "
				<form target='_blank' method='POST' action='https://merchant.wmtransfer.com/lmi/payment.asp'>
					<input type='hidden' name='LMI_PAYMENT_AMOUNT' value='".number_format($donation[0]['valor'], 2, '.', '')."'>
					<input type='hidden' name='LMI_PAYMENT_DESC' value='".$donateDesc."'>
					<input type='hidden' name='LMI_PAYMENT_NO' value='".$donation[0]['protocolo']."'>
					<input type='hidden' name='LMI_PAYEE_PURSE' value='".$WebMoney['merch_purse']."'>
					<input type='submit' value='".$LANG[10042]."' class='default big' style='margin-left:20px;' />
				</form>
				<br /><br />
				<div class='rmsg warn'>".$LANG[15004].": <b>".$donateEmail."</b></div>
				";
				
			break;
		
			case 'payza':
				
				echo "
				<form target='_blank' method='POST' action='https://secure.payza.com/checkout' >
					<input type='hidden' name='ap_merchant' value='".$Payza['email']."'/>
					<input type='hidden' name='ap_purchasetype' value='item'/>
					<input type='hidden' name='ap_itemname' value='".$donation[0]['quant_coins']." ".trim($coinName)."'/>
					<input type='hidden' name='ap_description' value='".$donateDesc."'/>
					<input type='hidden' name='ap_amount' value='".number_format($donation[0]['valor'], 2, '.', '')."'/>
					<input type='hidden' name='ap_currency' value='".$donation[0]['currency']."'/>
					<input type='hidden' name='ap_itemcode' value='".$donation[0]['protocolo']."'/>
					<input type='submit' value='".$LANG[10042]."' class='default big' style='margin-left:20px;' />
				</form>
				";
				
			break;
		
			case 'skrill':
				
				echo "
				<form target='_blank' method='POST' action='https://pay.skrill.com'>
					<input type='hidden' name='pay_to_email' value='".$Skrill['email']."'>
					<input type='hidden' name='language' value='EN'>
					<input type='hidden' name='amount' value='".number_format($donation[0]['valor'], 2, '.', '')."'>
					<input type='hidden' name='currency' value='".$Skrill['currency']."'>
					<input type='hidden' name='detail1_description' value='".$donateDesc."'>
					<input type='hidden' name='merchant_fields' value='protocol'>
					<input type='hidden' name='protocol' value='".$donation[0]['protocolo']."'>
					<input type='submit' value='".$LANG[10042]."' class='default big' style='margin-left:20px;' />
				</form>
				<br /><br />
				<div class='rmsg warn'>".$LANG[15004].": <b>".$donateEmail."</b></div>
				";
				
			break;

            case 'ualabis':

                $user_name = $Ualabis['user_name'];
                $client_id = $Ualabis['client_id'];
                $secret = $Ualabis['secret'];

                //Get Access token
                $data = [
                    'user_name' => $user_name,
                    'client_id' => $client_id,
                    'client_secret_id' => $secret,
                    'grant_type' => 'client_credentials',
                ];

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://auth.prod.ua.la/1/auth/token');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                $server_output = curl_exec($ch);
                curl_close ($ch);

                $answer = json_decode($server_output, true);

                if (isset($answer['access_token'])) {
                    $access_token = $answer['access_token'];

                    //Create Checkout
                    $data = [
                        'amount'           => $donation[0]['valor'],
                        'description'      => 'Servicio L2',
                        'userName'         => $user_name,
                        'callback_fail'    => 'https://'.$panel_url.'/?module=donate&page=order_pay&f='.trim($donation[0]['protocolo']),
                        'callback_success' => 'https://'.$panel_url.'/?module=donate&page=order_pay&f='.trim($donation[0]['protocolo']),
                        'notification_url' => 'https://'.$panel_url.'/ipn/ualabis_warcry_34895671.php'
                    ];

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, 'https://checkout.prod.ua.la/1/checkout');
                    curl_setopt($ch, CURLOPT_POST, TRUE);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token));
                    $server_output = curl_exec($ch);
                    curl_close($ch);

                    $answer = json_decode($server_output, TRUE);
                    if (isset($answer['uuid']) && isset($answer['links']['checkoutLink'])) {

                        $uuid = $answer['uuid'];

                        new DB($conMethod, $host, $user, $pass, $dbnm, $port);
                        $updateOrder = DB::Executa("UPDATE site_donations SET ultima_alteracao = '".time()."', transaction_code = '".$uuid."', status = '0', status_real = '0' WHERE protocolo = '".$donation[0]['protocolo']."'", "SITE");

                        $payButton = "<a href='" . $answer['links']['checkoutLink'] . "' class='default big'>" . $LANG[10042] . "</a>";
                    } else {
                        $payButton = "Fail! Please, contact admin. #PAYBUTTON";
                    }

                } else {
                    $payButton = "Fail! Please, contact admin. #PAYBUTTON";
                }

                    echo "
                        <h1>".$LANG[10040]."</h1>
                        <div class='pddInner'>
                            ".$LANG[39013]."<br /><br />                            
                            ".$payButton."                            
                        </div>
				    ";

                break;

            case 'weear':

                require('private/classes/classIndex.php');
                $accData = Index::accData($_SESSION['acc']);

                $api_key = $Weear['api_key'];



                //Create Checkout
                $data = [
                    'payer_name'         => ($accData[0]['login'] !== NULL) ? $accData[0]['login'] : 'admin',
                    'description'        => 'Servicio L2',
                    'first_total'        => intval($donation[0]['valor']) * 100,
                    'first_due_date'     => date('d-m-Y', strtotime('+1days')),
                    'external_reference' => strval($donation[0]['protocolo']),
                    'payer_email'        => ($accData[0]['email'] !== NULL) ? $accData[0]['email'] : 'admin@warcry.com.ar',
                    'payment_type'       => 'COBRO SERVICIOS INFORMATICOS',
                    'items'              => [[
                        'quantity'    => 1,
                        'description' => 'Servicio L2',
                        'amount'      => intval($donation[0]['valor']) * 100,
                    ]],
                ];

                //var_dump(json_encode($data));
                //die();


                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://api-checkout.wee.ar/api/v1/checkout');
                curl_setopt($ch, CURLOPT_POST, TRUE);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'apiKey: ' . $api_key));
                $server_output = curl_exec($ch);
                curl_close($ch);

                //var_dump($server_output);
                //die();

                    $answer = json_decode($server_output, TRUE);


                    if (isset($answer['data']) && isset($answer['data']['checkout_url'])) {

                        $uuid = $answer['data']['uuid'];

                        new DB($conMethod, $host, $user, $pass, $dbnm, $port);
                        $updateOrder = DB::Executa("UPDATE site_donations SET ultima_alteracao = '".time()."', transaction_code = '".$uuid."', status = '0', status_real = '0' WHERE protocolo = '".$donation[0]['protocolo']."'", "SITE");

                        $payButton = "<a href='" . $answer['data']['checkout_url'] . "' class='default big'>" . $LANG[10042] . "</a>";
                    } else {
                        $payButton = "Fail! Please, contact admin. #PAYBUTTON";
                    }

                echo "
                        <h1>".$LANG[10040]."</h1>
                        <div class='pddInner'>
                            ".$LANG[39013]."<br /><br />                            
                            ".$payButton."                            
                        </div>
				    ";

                break;

			default:
			
				// g2apay
				
				$hash = hash('sha256', trim($donation[0]['protocolo']).trim($donation[0]['valor']).trim($G2APay['currency']).trim($G2APay['api_secret']));
				
				$post = 'api_hash='.trim($G2APay['api_hash']);
				$post .= '&hash='.$hash;
				$post .= '&order_id='.trim($donation[0]['protocolo']);
				$post .= '&amount='.trim($donation[0]['valor']);
				$post .= '&currency='.trim($G2APay['currency']);
				$post .= '&url_failure='.urlencode('https://'.$panel_url.'/?module=donate&page=order_pay&f='.trim($donation[0]['protocolo']));
				$post .= '&url_ok='.urlencode('https://'.$panel_url.'/?module=donate&page=order_pay&f='.trim($donation[0]['protocolo']));
				$post .= '&items=[{"sku":"99999","name":"'.trim($coinName).'","amount":"'.trim($donation[0]['valor']).'","qty":"'.trim($donation[0]['quant_coins']).'","price":'.round(trim(trim($donation[0]['valor'])/$donation[0]['quant_coins']), 2).',"id":"'.trim($donation[0]['protocolo']).'","url":"'.urlencode('https://'.$panel_url.'/?module=donate&page=add').'"}]';
				
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'https://checkout.pay.g2a.com/index/createQuote');
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_TIMEOUT, 30);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
				
				$response = json_decode(curl_exec($ch));
				curl_close($ch);
				
				if(trim($response->status) == 'ok') {
					
					$payButton = "<a target='_blank' href='https://checkout.pay.g2a.com/index/gateway?token=".$response->token."' class='default big'>".$LANG[10042]."</a>";
					
				} else {
					
					$payButton = "Fail! Please, contact admin. #PAYBUTTON";
					
				}
			
				echo "
				<h1>".$LANG[10040]."</h1>
				<div class='pddInner'>
				    ".$LANG[39013]."<br /><br />
				    
					".$payButton."
				    
				</div>
				";
				
		}
		
	}

} else {
	
	echo $LANG[10046]."
	<div style='display:table;width:100%;'>
		<a href='./?module=donate&page=add' class='default' style='float:right;margin-right:30px'>".$LANG[10047]."</a>
	</div>
	";
}
