<?php 
    require 'connect_db.php';

    $less_id = $_POST['edit_id'];
    $less_subject = $_POST['edit_subject'];
    $less_week = $_POST['edit_week'];
    $less_room = $_POST['edit_room'];
    $less_start = $_POST['edit_start'];
    $less_day = $_POST['edit_day'];
    
    if ($_POST['edit_delete']) {
        mysqli_query($link, "DELETE FROM `timetable` WHERE `id` = '$less_id'");
    } 
    else {
        mysqli_query($link, "UPDATE `timetable` SET `subject` = '$less_subject', `type` = '$less_week', `room_numb` = '$less_room', `start_time` = '$less_start' WHERE `id` = '$less_id'");
    }
    
    echo $less_day;
?>