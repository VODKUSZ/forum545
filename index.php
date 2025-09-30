<?php
$fileName="data.json";
if(file_exists($fileName)){
    $JsonString= file_get_contents($fileName);
    $topics= json_decode($JsonString);
}
else{
    $topics=[];
}
$time = date("Y.n.j. H:i:s");

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'add') {
        $lastID = 0;
        if (!empty($topics)) {
            $lastItem = end($topics);
            $lastID = $lastItem->id;
        }
        $newId = $lastID + 1;
        
        array_push($topics, (object)[
            "id" => $newId,
            "name" => $_POST['topic'],
            "time" => $time,
        ]);
        //itt kezdodik a torles
    } elseif ($_POST['action'] == 'delete') {
        $topicIdToDelete = $_POST['id'];
        
        foreach ($topics as $key => $value) {
            if ($value->id == $topicIdToDelete) {
                unset($topics[$key]);
                $topics = array_values($topics);
                break;
                
            }
        }
    }
    

    
    $JsonString = json_encode(array_values($topics), JSON_PRETTY_PRINT);
    file_put_contents($fileName, $JsonString);
}


?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fórum</title>
</head>
<body>

    <?php
    if (!isset($_GET['topic'])){
    echo '<h1>Témák:</h1> <ol>';
    foreach ($topics as $value) {
     echo '<li><a href="index.php?topic=' . $value->id . '"> '. $value->name . '</a><br>'. $value->time .'
     <form method="post">
     <input type="hidden" name="id" value="'. $value->id . '">
     <input type="hidden" name="action" value="delete">
     <input type="submit" value="Törlés">
     </form>   
     ';
     }
     echo '</ol>';
     echo'
    <form method="post">
        <input type="hidden" name="action" value="add">
        <input type="text" name="topic" placeholder="Új téma" autofocus>
        <input type="submit" value="Add" >
    </form>';
    } else{
        echo '<center><h1><a href=index.php>Vissza a témákhoz</a><h1><center>';
        
        
    }?>
</body>
</html>