<?php
    if (!isset($_REQUEST)) {
        return;
    }

    $confirmation_token = '79da3f7b';
    $token = '809ad03db4c9867ceddeef0da4d7651c205918e3ba07a8fcad744080174634e4100c411cb0c19d072cc5f';
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
                [getBtn("/help", 'primary'), getBtn("Хочу", 'primary')],
                [getBtn("Мотивация", 'positive')]
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
        $chat_id = $data->object->message->peer_id;
        $message_text = $data->object->message->text;
        $user_info = json_decode(file_get_contents("https://api.vk.com/method/users.get?user_ids={$user_id}&access_token={$token}&v=5.103"));
        
        if ($message_text == "Хочу") {
            send_vk("Тогда тебе сюда – https://mtuci.ru/");
        }

        else if ($message_text == "Начать") {
            send_vk("Привет! Хочешь узнать свежую информацию о МТУСИ?");
        }

        else if ($message_text == "Приветствие") {
            send_vk("Здравствуйте! Мира Вашему дому");
        }

        else if ($message_text == "Прощание") {
            send_vk("До новых встреч! Скоро увидимся");
        }

        else if ($message_text == "Мотивация") {
            $data_sessiya = strtotime("2022-01-10 00:00:00");
            $data_now = strtotime(date("Y-m-d H:i:s"));
            $days = floor(abs($data_sessiya - $data_now) / 86400);

            send_vk("До начала сессии осталось {$days} дн. :)");
        }

        else if (mb_strpos(" ".$message_text, "/реши ")) {
            $exp_str = mb_substr($message_text, 6); 

            if (preg_match('/[a-zA-Z]/', $exp_str)) {
                send_vk("хак момент не произошел");
            }
            else {
                send_vk("Ответ: ".eval('return '.$exp_str.';'));
            }
        }

        else if ($message_text == "/help") {
            send_vk("Чтобы узнать свежую информацию о МТУСИ, введите - Хочу\nЧтобы решить пример, введите - /реши 'выражение'.\nПример команды: /реши 777 * 777 - 333\nВведите - Приветствие, чтобы бот Вас поприветствовал\nВведите - Прощание, чтобы бот с Вами попрощался");
        }

        else {
            send_vk("Извините, я Вас не понял");
        }

        echo('ok');

        break;

    }
?>