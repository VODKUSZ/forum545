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
if(isset($_POST['topic'])){
    array_push($topics,$_POST['topic']);
    $JsonString = json_encode($topics);
    file_put_contents($fileName,$JsonString);
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
    <ul>
    <?php
    foreach ($topics as $key => $value) {
     echo '<li>'. ($key+1) . '.' . $value;   
     }
    ?>
    </ul>
    <form method="post">
        <input type="text" name="topic">
        <input type="submit" value="Add">
    </form>
</body>
</html>