<?php
include 'php/sessionManager.php';
include 'php/date.php';
require 'DAL/PhotosCloudDB.php';
$viewTitle = "Détails de photo";

userAccess();

if (!isset($_GET["id"]))
    redirect("illegalAction.php");

$id = (int) $_GET["id"];

$photo = PhotosTable()->get($id);
if ($photo == null)
    redirect("illegalAction.php");
$userId = (int)$_SESSION["currentUserId"];

$title = $photo->Title;
$description = $photo->Description;
$image = $photo->Image;
$likes = $photo->Likes;

$userLike = count(LikesTable()->selectWhere("UserId = $userId AND PhotoId = $id")) > 0;
$photoLikedByConnectedUser = $userLike ? "fa" : "fa-regular"; 

$tabUsers = LikesTable()->selectWhatWhere("userId","PhotoId = $id");

$tabUsersId = array();



foreach ($tabUsers as $personne1) {
    $tabUsersId[] = $personne1->UserId;
}

$tabUsersAll = array();

foreach ($tabUsersId as $personne2) {
    $tabUsersAll[] = UsersTable()->selectById($personne2);

}

$tabUsersName = array();


foreach ($tabUsersAll as $personne3) {
    foreach($personne3 as $personne4){
        $tabUsersName[] = $personne4->Name;
    }

}
$likesUsersList = "";

foreach($tabUsersName as $noms){
    $likesUsersList .= $noms . "\n";
}
 

$owner = UsersTable()->Get($photo->OwnerId);
$ownerName = $owner->Name;
$ownerAvatar = $owner->Avatar;
$shared = $photo->Shared == "true";
$creationDate = timeStampToFullDate(strtotime($photo->CreationDate));

$viewContent = <<<HTML
    <div class="content">
        <div class="photoDetailsOwner">
        <div class="UserAvatarSmall" style="background-image:url('$ownerAvatar')" title="$ownerName"></div>
        $ownerName
        </div>
        <hr>
        <div class="photoDetailsTitle">$title</div>
        <img src="$image" class="photoDetailsLargeImage">
        <div class="photoDetailsCreationDate">
        $creationDate
        <div class="likesSummary">
            $likes
            <a href="addLikes.php?id=$id" class="cmdIconSmall $photoLikedByConnectedUser fa-thumbs-up" id="addRemoveLikeCmd" title="$likesUsersList" ></a> 
        </div>
        <div class="photoDetailsDescription">$description</div>
    HTML;
$viewScript = <<<HTML
        <script defer>
            $("#addPhotoCmd").hide();
        </script>
    HTML;
include "views/master.php";