<?php

error_reporting(0);
ini_set('display_errors', 0);

require('private/includes/params.php');
session_name(md5($_SERVER['HTTP_USER_AGENT'].$uniqueKey));
session_start();

require('private/configs.php');
if(file_exists('../private/configs.php') && !isset($host)) {
	require('../private/configs.php');
}

if(!isset($port)) { $port = ''; }

require('private/classes/DB.php');
new DB($conMethod, $host, $user, $pass, $dbnm, $port);

require('private/classes/classVote.php');

header('Content-Type: application/json');

$response = array('success' => false, 'message' => '');

// Verificar sesión
if(empty($_SESSION['acc'])) {
	$response['message'] = 'Sesión expirada. Por favor, inicie sesión nuevamente.';
	echo json_encode($response);
	exit;
}

// Verificar que la función de votación esté activa
if(empty($funct['vote'])) {
	$response['message'] = 'El sistema de votación no está disponible.';
	echo json_encode($response);
	exit;
}

// Obtener datos
$topsite_id = isset($_POST['topsite_id']) ? intval($_POST['topsite_id']) : 0;

if(empty($topsite_id) || !isset($topsites[$topsite_id]) || $topsites[$topsite_id]['actived'] != 1) {
	$response['message'] = 'Topsite inválido o no disponible.';
	echo json_encode($response);
	exit;
}

$topsite = $topsites[$topsite_id];

// Verificar cooldown
$voteStatus = Vote::canVote($_SESSION['acc'], $topsite_id, $voteCooldown);
if(!$voteStatus['can_vote']) {
	$timeRemaining = Vote::formatTimeRemaining($voteStatus['time_remaining']);
	$response['message'] = 'Debes esperar '.$timeRemaining.' para votar nuevamente en '.$topsite['name'].'.';
	echo json_encode($response);
	exit;
}

// Obtener IP del usuario
$userIP = !empty($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : 
		  (!empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0] : $_SERVER['REMOTE_ADDR']);
$userIP = trim($userIP);

// Verificar voto en la API del topsite
$voteVerified = Vote::verifyVoteAPI($topsite['api_url'], $userIP);

if(!$voteVerified) {
	// Si la API no confirma el voto, intentamos un método alternativo
	// Algunos topsites no tienen API, así que confiamos en el usuario (opcional)
	// Por ahora, rechazamos si no se puede verificar
	$response['message'] = 'No pudimos verificar tu voto en '.$topsite['name'].'. Asegúrate de haber votado primero y luego reclama la recompensa.';
	echo json_encode($response);
	exit;
}

// Registrar el voto
$registerVote = Vote::registerVote($_SESSION['acc'], $topsite_id, $topsite['name'], $userIP, $voteReward);

if($registerVote) {
	// Entregar recompensa según el método configurado
	if(isset($voteDeliveryMethod) && $voteDeliveryMethod == 'ingame') {
		// Entrega directa al personaje via ItemDelivery
		$character = Vote::getFirstCharacter($_SESSION['acc']);
		if(count($character) > 0) {
			Vote::deliverToCharacter($_SESSION['acc'], $character[0]['char_id'], $coinID, $voteReward);
			$response['success'] = true;
			$response['message'] = '¡Voto verificado! Has recibido '.$voteReward.' '.$coinGame.'(s) en tu personaje '.$character[0]['char_name'].'.';
		} else {
			// Si no tiene personaje, agregar al saldo online
			Vote::addBalance($_SESSION['acc'], $voteReward);
			$response['success'] = true;
			$response['message'] = '¡Voto verificado! Has recibido '.$voteReward.' '.$coinName_mini.'(s) en tu saldo online.';
		}
	} else {
		// Agregar al saldo online (método por defecto)
		Vote::addBalance($_SESSION['acc'], $voteReward);
		$response['success'] = true;
		$response['message'] = '¡Voto verificado! Has recibido '.$voteReward.' '.$coinName_mini.'(s) por votar en '.$topsite['name'].'.';
	}
} else {
	$response['message'] = 'Error al registrar el voto. Por favor, intenta nuevamente.';
}

echo json_encode($response);

@DB::close();
