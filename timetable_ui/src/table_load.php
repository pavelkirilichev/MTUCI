<table class="output__table">
    <tr>
        <th>Начало</th>
        <th>Предмет</th>
        <th>Преподаватель</th>
        <th>Кабинет</th>
        <th>Ред.</th>
    </tr>
    <?php
        require 'connect_db.php';

        function weekCheck() {
            $d1_ts = strtotime("2021-08-30 00:00:00");
            $d2_ts = strtotime(date("Y-m-d H:i:s"));
            $seconds = abs($d2_ts - $d1_ts);
            
            if ((floor(($seconds / 86400 + 1) / 7 ) + 1) % 2) {
                return "Верхняя";
            }
            else {
                return "Нижняя";
            }
        }

        $day = $_POST['day'];
        $week_type = weekCheck();
        
        if ($day == "Тек. неделя" || $day == "След. неделя") {
            if ($day == "След. неделя") {
                if ($week_type == "Верхняя") {
                    $week_type = "Нижняя";
                }
                else {
                    $week_type = "Верхняя";
                }
            }
            $day_arr = array("Понедельник", "Вторник", "Среда", "Четверг", "Пятница");

            foreach ($day_arr as $day_res) {
            ?>
                <tr>
                    <td style="color: #b3aad0;"><?= $day_res ?></td>
                </tr>
            <?   
                $table_sql = mysqli_query($link, "SELECT * FROM `timetable` WHERE `day` = '$day_res' AND (`type` = 'Общая' OR `type` = '$week_type') ORDER BY `start_time`");       
                if (mysqli_num_rows($table_sql) > 0) {
                    foreach ($table_sql as $key => $table_result) {
                        $subject = $table_result['subject'];
                        $teacher_sql = mysqli_query($link, "SELECT `full_name` FROM `teacher` WHERE `subject` = '$subject'");
                        $teacher_full_name = mysqli_fetch_array($teacher_sql)['full_name'];  
                        ?>
                            <tr>
                                <td><?= $table_result['start_time'] ?></td>
                                <td><?= $subject ?></td>
                                <td><?= $teacher_full_name ?></td>
                                <td><?= $table_result['room_numb'] ?></td>
                                <td>
                                <img src="http://cdn.onlinewebfonts.com/svg/img_120423.png" class="edit__btn" id="<?= $table_result['id'] ?>" name="<?= $day ?>">
                                </td>
                            </tr>
                        <?
                    }
                }
                else {
                ?>
                    <tr>
                        <td>Без пар</td>
                    </tr>
                <?          
                }
            }
        }

        else {
            $table_sql = mysqli_query($link, "SELECT * FROM `timetable` WHERE `day` = '$day' AND (`type` = 'Общая' OR `type` = '$week_type') ORDER BY `start_time`");       
        
            foreach ($table_sql as $key => $table_result) {
                $subject = $table_result['subject'];
                $teacher_sql = mysqli_query($link, "SELECT `full_name` FROM `teacher` WHERE `subject` = '$subject'");
                $teacher_full_name = mysqli_fetch_array($teacher_sql)['full_name'];
            ?>
                <tr>
                    <td><?= $table_result['start_time'] ?></td>
                    <td><?= $subject ?></td>
                    <td><?= $teacher_full_name ?></td>
                    <td><?= $table_result['room_numb'] ?></td>
                    <td>
                        <img src="img/edit_icon.png" class="edit__btn" id="<?= $table_result['id'] ?>" name="<?= $day ?>">
                    </td>
                </tr>
            <? 
            }
        }
        ?>
</table>

     
    
