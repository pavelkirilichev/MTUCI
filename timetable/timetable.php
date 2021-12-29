<?php
    if (!isset($_REQUEST)) {
        return;
    }

    $confirmation_token = '1ea20b0d';
    $token = '3bfa8b41d10b859edabd3404b80699c25a92d0a9b0f62b9f537dc07ad5933148dcc0cd59e43717649de43';
    $data = json_decode(file_get_contents('php://input'));

    function getBtn($label, $color) {
        return [
            'action' => [
                'type' => 'text',
                'label' => $label
            ],
            'color' => $color
        ];
    }

    
    function send_vk($text) {
        global $chat_id, $token;

        $kbd = [
            'one_time' => false,
            'inline' => false,
            'buttons' => [
                [getBtn("Понедельник", 'primary'), getBtn("Вторник", 'primary'), getBtn("Среда", 'primary')],
                [getBtn("Четверг", 'primary'), getBtn("Пятница", 'primary')],
                [getBtn("Текущая неделя", 'positive'), getBtn("Следующая неделя", 'negative')],
            ]
        ];

        $request_params = array(
            'message' => $text,
            'peer_id' => $chat_id,
            'access_token' => $token,
            'v' => '5.103',
            'keyboard' => json_encode($kbd, JSON_UNESCAPED_UNICODE),
            'random_id' => '0'
        );
    
        $get_params = http_build_query($request_params);

        file_get_contents('https://api.vk.com/method/messages.send?'. $get_params);
    
    }

    switch ($data->type) {

        case 'confirmation':
            echo $confirmation_token;
            break;

        case 'message_new':

        $user_id = $data->object->message->from_id;
        $adres = $data->object->message->from_id->fields->nickname;
        $chat_id = $data->object->message->peer_id;
        $message_text = $data->object->message->text;
        $user_info = json_decode(file_get_contents("https://api.vk.com/method/users.get?user_ids={$user_id}&access_token={$token}&v=5.103"));
        $adres = $user_info->response[0]->fields->bdate;
        $user_name = $user_info->response[0]->first_name;
        $user_id_reply = $data->object->message->reply_message->from_id;

        $host = 'localhost';
        $i = 0;
        $database = 'bomj4235_mtuci';
        $user = 'julki6ri_mtuci';
        $password = '8848205123';
        $link = mysqli_connect($host, $user, $password, $database)
        or die("Ошибка " . mysqli_error($link));
        
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
        
        if ($message_text == "/mtuci") {
            send_vk("https://mtuci.ru/");
        }

        else if ($message_text == "/week") {
            send_vk(weekCheck()." неделя");
        }

        else if ($message_text == "Начать") {
            send_vk("Добро пожаловать \nЧтобы подробнее узнать о возможностях бота \nВведите - /help");
        }

        else if ($message_text == "/help") {
            send_vk("Чтобы узнать какая сейчас неделя (верхняя или нижняя) \nвведите - /week \nЧтобы увидеть ссылку на официальный сайт МТУСИ введите - /mtuci \n\nЧерез встроенную клавиатуру вы можете вывести расписание на нужный вам период времени (при выборе определенного дня выводится расписание по текущей недели)");
        }

        else if ($message_text == "Понедельник" || $message_text == "Вторник" || $message_text == "Среда" || $message_text == "Четверг" || $message_text == "Пятница") {
            $week_type = weekCheck();
           
            $send_str = "Расписание на ".mb_strtolower($message_text)."\n\n";
            $table_sql = mysqli_query($link, "SELECT * FROM `timetable` WHERE `day` = '$message_text' AND (`type` = 'Общая' OR `type` = '$week_type') ORDER BY `start_time`");
            
            foreach($table_sql as $key => $table_result) {
                $subject = $table_result['subject'];
                $teacher_sql = mysqli_query($link, "SELECT `full_name` FROM `teacher` WHERE `subject` = '$subject'");
                $teacher_full_name = mysqli_fetch_array($teacher_sql)['full_name'];

                $send_str .= ($key + 1).". ".$subject." | ".$table_result['room_numb']." | ".$table_result['start_time']." | ".$teacher_full_name."\n";
            }

            send_vk($send_str);
        }

        else if ($message_text == "Текущая неделя") {
            $week_type = weekCheck();

            $send_str = "Расписание на текущую неделю \n\n";
            $day_arr = array("Понедельник", "Вторник", "Среда", "Четверг", "Пятница");

            foreach($day_arr as $day) {
                $send_str .= $day."\n___________\n";
                $table_sql = mysqli_query($link, "SELECT * FROM `timetable` WHERE `day` = '$day' AND (`type` = 'Общая' OR `type` = '$week_type') ORDER BY `start_time`");
            
                foreach($table_sql as $key => $table_result) {
                    $subject = $table_result['subject'];
                    $teacher_sql = mysqli_query($link, "SELECT `full_name` FROM `teacher` WHERE `subject` = '$subject'");
                    $teacher_full_name = mysqli_fetch_array($teacher_sql)['full_name'];

                    $send_str .= ($key + 1).". ".$subject." | ".$table_result['room_numb']." | ".$table_result['start_time']." | ".$teacher_full_name."\n";
                }
                $send_str .= "\n\n";
            }

            send_vk($send_str);
        }

        else if ($message_text == "Следующая неделя") {
            $week_type = weekCheck();


            if ($week_type == "Верхняя") {
                $week_type = "Нижняя";
            }
            else {
                $week_type = "Верхняя";
            }

            $send_str = "Расписание на следующую неделю \n\n";
            $day_arr = array("Понедельник", "Вторник", "Среда", "Четверг", "Пятница");

            foreach($day_arr as $day) {
                $send_str .= $day."\n___________\n";
                $table_sql = mysqli_query($link, "SELECT * FROM `timetable` WHERE `day` = '$day' AND (`type` = 'Общая' OR `type` = '$week_type') ORDER BY `start_time`");
            
                foreach($table_sql as $key => $table_result) {
                    $subject = $table_result['subject'];
                    $teacher_sql = mysqli_query($link, "SELECT `full_name` FROM `teacher` WHERE `subject` = '$subject'");
                    $teacher_full_name = mysqli_fetch_array($teacher_sql)['full_name'];

                    $send_str .= ($key + 1).". ".$subject." | ".$table_result['room_numb']." | ".$table_result['start_time']." | ".$teacher_full_name."\n";
                }
                $send_str .= "\n\n";
            }

            send_vk($send_str);
        }

        else {
            send_vk("Извините, я Вас не понял");
        }

        echo('ok');

        break;

    }
?>