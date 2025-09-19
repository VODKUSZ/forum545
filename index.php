<?php
$szoveg="";
if(isset($_POST['topic'])){
    $szoveg = 'Kaptam új topic post adatot';
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
    echo $szoveg;
    ?>
    <h1>Témák:</h1>
    <form method="post">
        <input type="text" name="topic">
        <input type="submit" value="Add">
    </form>
</body>
</html>