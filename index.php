<?php
$fileName="data.json";
if(file_exists($fileName)){
    $JsonString= file_get_contents($fileName);
    $topics= json_decode($JsonString);
}
else{
    $topics=[];
}

$szoveg="";
if(isset($_POST['action'])){
    if($_POST['action'] == 'add') {
    array_push($topics,$_POST['topic']);
    $JsonString = json_encode($topics);
    file_put_contents($fileName,$JsonString);
    }
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
     echo '<li>'. $value . '
     <form method="post">
     <input type="hidden" name="topic" value="'. $value . '">
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