<?php
    require_once 'app/init.php';
    if(!empty($_POST)){
        if(isset($_POST['title'],$_POST['body'],$_POST['keywords'])){
            $title = $_POST['title'];
            $body = $_POST['body'];
            $keywords = explode(',',$_POST['keywords']);
            
            $indexed = $client->index([
                'index'=>'articles',
                'type'=>'article',
                'body'=>[
                    'title'=>$title,
                    'body'=>$body,
                    'keywords'=>$keywords
                ]
            ]);

            if($indexed){
                print_r($indexed);
            }
        }
    }
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Ajout | ES</title>
        <link rel="stylesheet" href="css/main.css">
    </head>
    <body>
        <form action="add.php" method="post" autocomplete="off">
            <label>
                Titre
                <input type="text" name="title">
            </label>
            <br>
            <label>
                Body
                <textarea name="body" rows="8"></textarea>
            </label>
            <br>
            <label>
                Keywords
                <input type="text" name="keywords">
            </label>
            <br>
            <input type="submit" value="Ajouter">
        </form>
    </body>
</html>