<?php
session_start();
$_SESSION["message"] = '';

    $picname = $_GET['pic'];

    try{
    $conn = new PDO("mysql:host=localhost;dbname=db_camagru", "root", "root");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $req = $conn->prepare("SELECT id FROM users where username = :username");
    $req->execute(array(
        ':username' => $_SESSION['LOGGED_ON']
    ));
    $id = $req->fetch(PDO::FETCH_COLUMN, 0);      
    }
    catch(PDOException $e)
    {
        echo "Couldn't write in Database: " . $e->getMessage();
    }

    try{
        $conn = new PDO("mysql:host=localhost;dbname=db_camagru", "root", "root");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $req = $conn->prepare("SELECT PhotoID FROM photos where url = :url");
        $req->execute(array(
            ':url' => $picname
        ));
        $idphoto = $req->fetch(PDO::FETCH_COLUMN, 0);    
    }
    catch(PDOException $e)
    {
        echo "Couldn't write in Database: " . $e->getMessage();
    }
    try
    {
        $conn = new PDO("mysql:host=localhost;dbname=db_camagru", "root", "root");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $req = $conn->prepare("SELECT LikeID FROM likes WHERE Userid = :UserID AND photoID = :photoID");
        $req->execute(array(
            ':UserID' => $id,
            ':photoID' => $idphoto
        ));
    }
    catch(PDOException $e)
    {
        echo "Couldn't write in Database: " . $e->getMessage();
    }
    if ($req->rowCount() > 0)
    {
        try
        {
    
            $conn = new PDO("mysql:host=localhost;dbname=db_camagru", "root", "root");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $req = $conn->prepare("DELETE FROM likes WHERE UserID = :UserID AND photoID = :photoID");
            $req->execute(array(
                ':UserID' => $id,
                ':photoID' => $idphoto
            ));
        }
        catch(PDOException $e)
        {
            echo "Couldn't write in Database: " . $e->getMessage();
        }
        echo "DISLIKED";
        
    }
    else
    {
        try
        {
    
            $conn = new PDO("mysql:host=localhost;dbname=db_camagru", "root", "root");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $req = $conn->prepare("INSERT INTO likes (UserID, photoID) VALUES (:UserID, :photoID)");
            $req->execute(array(
                ':UserID' => $id,
                ':photoID' => $idphoto
            ));
        }
        catch(PDOException $e)
        {
            echo "Couldn't write in Database: " . $e->getMessage();
        }
        echo "LIKED";
    }
    
 ?>