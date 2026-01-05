<?php

$vis = (!empty($_POST['vis']) ? intval($_POST['vis']) : (!empty($_GET['vis']) ? intval($_GET['vis']) : ''));

if($vis != '1') { $vis = '0'; }

$gid = !empty($_GET['gid']) ? intval($_GET['gid']) : 0;

require('private/classes/classGallery.php');

$findGallery = Gallery::findGallery($gid);
if(count($findGallery) == 0){
	fim('¡Ítem inexistente en la galería!');
}

$inserir = Gallery::editGallery($gid, $vis);
if($inserir){
	adminLog("Cambiou ".($findGallery[0]['isvideo'] == '1' ? "un vídeo" : "una imagen")." en galeria (ID ".$gid.")"); // Admin Log
	fim('¡Edición efectuada con éxito!', 'OK', './?page=list&module=gallery');
} else {
	fim('Lo siento, ocurrió algún error. Por favor, inténtelo más tarde.');
}
