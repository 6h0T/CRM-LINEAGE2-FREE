<?php

$link = !empty($_POST['link']) ? vCode($_POST['link']) : '';
$vis = !empty($_POST['vis']) ? intval($_POST['vis']) : '';

if($vis != '1') { $vis = '0'; }

if(strlen($link) > 255){
	fim('Usted ha insertado un link demasiado largo! (máximo 255 caracteres)');
}

if(strpos($link, 'v=') !== false) {
	
	$xpld1 = explode('v=', $link);
	$xpld2 = explode('&', $xpld1[1]);
	$VID = $xpld2[0];
	
} else if(strpos($link, 'youtu.be/') !== false) {
	
	$xpld1 = explode('youtu.be/', $link);
	$xpld2 = explode('/', $xpld1[1]);
	$VID = $xpld2[0];
	
} else if(strpos($link, 'embed/') !== false) {
	
	$xpld1 = explode('embed/', $link);
	$xpld2 = explode('"', $xpld1[1]);
	$VID = $xpld2[0];
	
} else {
	
	$VID = $link;
	
}

if(strlen($VID) < 8 || strlen($VID) > 14){
	fim('Link irregular! Por favor, informe el link correcto!');
}

$thumbUrl = "http://img.youtube.com/vi/".$VID."/mqdefault.jpg";

require('private/wideImage/WideImage.php');
@WideImage::load($thumbUrl)->resize(150, 150, 'outside')->crop('center', 'center', 150, 150)->saveToFile('../'.$dir_gallery.'thumbnail/'.$VID.'.jpg', 90);

require('private/classes/classGallery.php');

@Gallery::reorderAllGallery();

$inserir = Gallery::insertGallery($VID, 1, 1, $vis);
if($inserir){
	adminLog("Agregó un vídeo a la galería (ID ".DB::$lastInsertID.")"); // Admin Log
	fim('¡Vídeo agregado con éxito!', 'OK', './?page=list&module=gallery');
} else {
	fim('Lo siento, ocurrió algún error. Por favor, inténtelo más tarde.');
}
