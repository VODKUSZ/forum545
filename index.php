<?php
$fileName = "data.json";
if (file_exists($fileName)) {
    $JsonString = file_get_contents($fileName);
    $topics = json_decode($JsonString);
} else {
    $topics = [];
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
            "name" => htmlspecialchars(trim($_POST['topic'])),
            "time" => $time,
            "comments" => []
        ]);
    } elseif ($_POST['action'] == 'delete') {
        $topicIdToDelete = $_POST['id'];
        foreach ($topics as $key => $value) {
            if ($value->id == $topicIdToDelete) {
                unset($topics[$key]);
                $topics = array_values($topics);
                break;
            }
        }
    } elseif ($_POST['action'] == 'add_comment') {
        $topicId = $_POST['topic_id'];
        $commentText = htmlspecialchars(trim($_POST['comment']));
        $commentUser = htmlspecialchars(trim($_POST['userid']));  

        foreach ($topics as $topic) {
            if ($topic->id == $topicId) {
                if (!isset($topic->comments)) {
                    $topic->comments = [];
                }
                if (!empty($commentText)) {
                    $topic->comments[] = (object)[
                        "text" => $commentText,
                        "time" => $time,
                        "userid"=> $commentUser,  
                    ];
                }
                break;
            }
        }
    }
    elseif ($_POST['action'] == 'delete_comment') {
        $topicId = $_POST['topic_id'];
        $commentIndex = $_POST['comment_index'];
        foreach ($topics as $topic) {
            if ($topic->id == $topicId && isset($topic->comments[$commentIndex])) {
                unset($topic->comments[$commentIndex]);
                $topic->comments = array_values($topic->comments);
                break;
            }
        }
    }
    elseif ($_POST['action'] == 'edit_comment') {
        $topicId = $_POST['topic_id'];
        $commentIndex = $_POST['comment_index'];
        $editedText = htmlspecialchars(trim($_POST['edited_comment']));
        if (!empty($editedText)) {
            foreach ($topics as $topic) {
                if ($topic->id == $topicId && isset($topic->comments[$commentIndex])) {
                    $topic->comments[$commentIndex]->text = $editedText;
                    $topic->comments[$commentIndex]->time = $time;
                    break;
                }
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
    <title>Fórum</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
if (!isset($_GET['topic'])) {
    echo '<h1>Témák:</h1> <ol>';
    foreach ($topics as $value) {
        echo '<li><a href="index.php?topic=' . $value->id . '">' . htmlspecialchars($value->name) . '</a><br>' . $value->time . '
        <form method="post" style="display:inline;">
            <input type="hidden" name="id" value="' . $value->id . '">
            <input type="hidden" name="action" value="delete">
            <input type="submit" value="Törlés">
        </form></li>';
    }
    echo '</ol>';
    echo '
    <form method="post">
        <input type="hidden" name="action" value="add">
        <input type="text" name="topic" placeholder="Új téma" autofocus required>
        <input type="submit" value="Hozzáadás">
    </form>';
} else {
    $topicId = intval($_GET['topic']);
    $found = false;

    foreach ($topics as $topic) {
        if ($topic->id == $topicId) {
            $found = true;
            echo '<h1>' . htmlspecialchars($topic->name) . '</h1>';
            echo '<p>Létrehozva: ' . $topic->time . '</p>';
            echo '<h3>Kommentek:</h3>';
            if (isset($topic->comments) && count($topic->comments) > 0) {
                echo '<ul>';
                foreach ($topic->comments as $index => $comment) {
                    echo '<li>';
                    if (
                        isset($_POST['action']) &&
                        $_POST['action'] === 'start_edit' &&
                        $_POST['topic_id'] == $topic->id &&
                        $_POST['comment_index'] == $index
                    ) {
                        echo '<form method="post">
                                <input type="hidden" name="action" value="edit_comment">
                                <input type="hidden" name="topic_id" value="' . $topic->id . '">
                                <input type="hidden" name="comment_index" value="' . $index . '">
                                <textarea name="edited_comment" cols="40" rows="5" required>' . htmlspecialchars($comment->text) . '</textarea><br>
                                <input type="submit" value="Mentés">
                            </form>';
                    } else {
                        echo nl2br(htmlspecialchars($comment->text)) . '<br>';
                        echo '<strong>Írta: ' . htmlspecialchars($comment->userid) . ' - ' . $comment->time . '</strong><br>';
                        echo '<form method="post" style="display:inline;">
                                <input type="hidden" name="action" value="start_edit">
                                <input type="hidden" name="topic_id" value="' . $topic->id . '">
                                <input type="hidden" name="comment_index" value="' . $index . '">
                                <input type="submit" value="Szerkesztés">
                            </form>';
                        echo '<form method="post" style="display:inline; margin-left:10px;">
                                <input type="hidden" name="action" value="delete_comment">
                                <input type="hidden" name="topic_id" value="' . $topic->id . '">
                                <input type="hidden" name="comment_index" value="' . $index . '">
                                <input type="submit" value="Törlés">
                            </form>';
                    }

                    echo '</li>';
                }
                echo '</ul>';
            } else {
                echo '<p>Nincs még komment.</p>';
            }
            echo '
            <form method="post">
                <input type="hidden" name="action" value="add_comment">
                <input type="hidden" name="topic_id" value="' . $topic->id . '">
                <input type="text" name="userid" placeholder="Írj egy nevet a kommenthez"><br>
                <textarea name="comment" cols="40" rows="5" placeholder="Írj egy kommentet..." required></textarea><br>
                <input type="submit" value="Küldés">
            </form>';

            echo '<p><a href="index.php">Vissza a témákhoz</a></p>';
            break;
        }
    }
}
?>
</body>
</html>
