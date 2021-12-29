<?php 
    require 'connect_db.php';

    $less_id = $_POST['less_id'];
    $day = $_POST['day'];
    $table_sql = mysqli_query($link, "SELECT * FROM `timetable` WHERE `id` = '$less_id'");
    $table_result = mysqli_fetch_array($table_sql);

    $less_start = $table_result['start_time'];
    $less_subject = $table_result['subject'];
    $less_room = $table_result['room_numb'];
    $week_type = $table_result['type'];
?>

<img src="img/close_icon.png" class="close__btn">
<h3>Редактирование</h3>
<form class="edit__form" method="post">
    <p>Начало: <input type="text" name="edit_start" value="<?= $less_start ?>"></p>
    <p>Предмет: <input type="text" name="edit_subject" value="<?= $less_subject ?>"></p>
    <p>Неделя: <input type="text" name="edit_week" value="<?= $week_type ?>"></p>
    <p>Кабинет: <input type="text" name="edit_room" value="<?= $less_room ?>"></p>
    <input type="hidden" name="edit_day" value="<?= $day ?>">
    <input type="hidden" name="edit_id" value="<?= $less_id ?>">
    <p class="input__checkbox"><input type="checkbox" name="edit_delete" value="1"> Удалить</p>
    <button id="edit__send__btn" type="button">Редактировать</button>
</form>