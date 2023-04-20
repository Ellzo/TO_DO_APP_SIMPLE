<?php

switch ($vars['action']) {
    case "list": {
            $user_id = $db->query("SELECT user_id from users where email=?", $appuser["email"])->fetchAll()[0]["user_id"];
            $items = $db->query('SELECT * FROM items where user_id=(?)', $user_id)->fetchAll();
            include("view/header.php");
            include("view/list.php");
            include("view/footer.php");
            exit;
        }
        break;

    case "do_add": {
            if ($vars['title']) {
                $user_id = $db->query("SELECT user_id from users where email=?", $appuser["email"])->fetchAll()[0]["user_id"];
                $db->query("INSERT INTO items (title,create_time,user_id) VALUES (?,?,?)", $vars['title'], getdate()[0], $user_id);
            }
            header("location: index.php?action=list");
            exit;
        }
        break;
    case "delete": {
            $db->query("DELETE FROM items WHere item_id=(?)", $vars['item_id']);
            header("location: index.php?action=list");
            exit;
        }
        break;

    case "do_edit": {
            $db->query("UPDATE items set title=?", $vars["title"]);
            header("location: index.php?action=list");
            exit;
        }
        break;

    case "help": {

            exit;
        }
        break;
}
