<?php
header("Access-Control-Allow-Origin: *");

$notes = json_decode(file_get_contents("notes.json"));

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    printCards();
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    array_push($notes, array("text" => ""));    
    printCards(); 
} else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    unset($notes[$_REQUEST['id']]);
    $new_notes = array();
    foreach($notes as $note) {
        array_push($new_notes, $note);    
    }
    $notes = $new_notes;
    printCards();
} else if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $post_data = file_get_contents('php://input');
    $values = explode('=', $post_data);
    $notes[$_REQUEST['id']]->text = urldecode($values[1]);
}
file_put_contents("notes.json", json_encode($notes));

function printCards() {
    global $notes;

    foreach($notes as $key => $value) {
    echo "
<div class='card'>
    <textarea 
        rows='5' 
        cols='30'
        name='text'
        hx-put='notes.php?id=$key'
        hx-trigger='keyup changed trottle:1s'
        >" . $value->text . "</textarea>
    <button 
        hx-delete='notes.php?id=$key'
        hx-target='#card_list'
        hx-swap='innerHTML'
        >
        Delete
    </button>
</div>  
    ";
    }
}