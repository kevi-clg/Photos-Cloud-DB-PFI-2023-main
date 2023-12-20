<?php
include 'php/sessionManager.php';
require 'DAL/PhotosCloudDB.php';

if (!isset($_GET["id"]))
    redirect("illegalAction.php");

$id = (int) $_GET["id"];
$userId = (int)$_SESSION["currentUserId"];
$photo = PhotosTable()->get($id);

if ($photo == null)
    redirect("illegalAction.php");


    if(LikesTable()->selectWhere("userId = $userId and photoId = $id")){
        $photo->setLikes(intval($photo->Likes)-1);
        LikesTable()->deleteWhere("userId = $userId and photoId = $id");
    }else{
        $photo->setLikes(intval($photo->Likes)+1);
        $monTableau = array(
            'userId' => $userId,
            'photoId' => $id
        );
        LikesTable()->insert(new Like($monTableau));
        
    }


PhotosTable()->update($photo);

redirect("photoDetails.php?id=$id");











?>