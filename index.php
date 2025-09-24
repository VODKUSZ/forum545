<?php
$fileName="data.json";
if(file_exists($fileName)){
    $JsonString= file_get_contents($fileName);
    $topics= json_decode($JsonString);
}
else{
    $topics=[];
}


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
            "name" => $_POST['topic']
        ]);
    } elseif ($_POST['action'] == 'delete') {
        $topicIdToDelete = $_POST['topic'];
        
        foreach ($topics as $key => $value) {
            if ($value->id == $topicIdToDelete) {
                unset($topics[$key]);
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
    <h1>Témák:</h1>
    <ol>
    <?php
    foreach ($topics as $value) {
     echo '<li>'. $value->name . '
     <form method="post">
     <input type="hidden" name="topic" value="'. $value->id . '">
     <input type="hidden" name="action" value="delete">
     <input type="submit" value="Törlés">
     </form>   
     ';
        
     }
    ?>
    </ol>
    <form method="post">
         <input type="hidden" name="action" value="add">
        <input type="text" name="topic" placeholder="Új téma" autofocus>
        <input type="submit" value="Add" >
    </form>
</body>
</html>