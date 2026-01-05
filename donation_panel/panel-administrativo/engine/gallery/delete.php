<?php

$gid = !empty($_GET['gid']) ? intval($_GET['gid']) : 0;

require('private/classes/classGallery.php');

$findGallery = Gallery::findGallery($gid);
if(count($findGallery) == 0){
	fim('¡Ítem inexistente en la galería!');
}

$delete = Gallery::deleteGallery($gid);
if($delete) {
	
	if(file_exists('../'.$dir_gallery.$findGallery[0]['url']) && !empty($findGallery[0]['url'])) {
		@unlink('../'.$dir_gallery.$findGallery[0]['url']);
	}
	
	if(file_exists('../'.$dir_gallery.'thumbnail/'.$findGallery[0]['url']) && !empty($findGallery[0]['url'])) {
		@unlink('../'.$dir_gallery.'thumbnail/'.$findGallery[0]['url']);
	}
	
	$withJPG = $findGallery[0]['url'].'.jpg';
	if(file_exists('../'.$dir_gallery.'thumbnail/'.$withJPG) && !empty($findGallery[0]['url'])) {
		@unlink('../'.$dir_gallery.'thumbnail/'.$withJPG);
	}
	
	adminLog("Excluyó de la galeria ".($findGallery[0]['isvideo'] == '1' ? "el vídeo" : "la imagen")." de ID ".$gid); // Admin Log
	fim('¡Exclusión efectuada con éxito!', 'OK', './?page=list&module=gallery');
	
} else {
	fim('Lo siento, ocurrió algún error. Por favor, inténtelo más tarde.');
}
