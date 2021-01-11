<?php

require('src/jsonRPCClient.php');
require('src/walletRPC.php');

$pay_to		= "XCA1um7C98XNv379wmqQ2gjNVr8LfvvgC7u4SaJmP7w8g3Xn17EJtMYRVsxSpxXypFTVn9XQfJDf1ckRsaHwtu4y7tTrJn8cLs";
$return		= [];

$max_retries	= 10;
$attempts 	= 0;

$walletRPC      = new walletRPC('127.0.0.1', 18285);
$get_address    = $walletRPC->get_address();
$get_balance    = $walletRPC->get_balance();

if(isset($get_address['address'])){
	$address    = $get_address['address'];
	$balance    = ($get_balance['unlocked_balance'] / 1000000);

	$return['address']				= $address;
	$return['balance']['unlocked']	= $balance;
	$return['balance']['all']		= ($get_balance['balance'] / 1000000);

	if($balance > 0){
		do {
			try {
				$sweep_all = $walletRPC->sweep_all($pay_to);
			} catch (Exception $e) {
				$attempts++;
				sleep(5);
				continue;
			}

			break;
		} while ($attempts < $max_retries);

		$return['action']	= 'sweep_all';

		if(isset($sweep_all['tx_hash_list'])){
			$return['status']	= 'success';


			$return['amount']['sent']	= (array_sum($sweep_all['amount_list']) / 1000000);
			$return['amount']['fee']	= (array_sum($sweep_all['fee_list']) / 1000000);

		} else {
			$return['status']	= 'failed';
		}
	} else {
		$return['action']	= 'none';
		$return['status']	= 'success';
	}


} else {
	$return['wallet']	= 'wallet-'.$i;
	$return['message']	= 'Cannot connect to RPC Wallet';
}

echo json_encode($return, JSON_PRETTY_PRINT);
