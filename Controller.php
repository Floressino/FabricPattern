<?

    require('Settings.php');
    require_once "vendor/autoload.php";
    require('vk.php');

    interface SenderOfMessages {
        function sendMessage($message): void;
    }

    class TelegrammApiController implements SenderOfMessages {

        private $bot;

        function __construct(){
            $this->bot = new \TelegramBot\Api\BotApi(TELEGRAMM_TOKEN);
        }

        function sendMessage($message): void {

            $this->bot->sendMessage(TELEGRAMM_ID_CHAT, $message);

        }
    }

    class VkApiController implements SenderOfMessages {

        private $bot;

        function __construct(){
            $this->bot = new VKBot(VK_GROUP_ID, VK_TOKEN);
        }

        function sendMessage($message): void {

            $this->bot->call('messages.send', ['user_id' => 211682187, 'message' => $message, 'random_id' => random_int(1, 999999)]);

        }

    }


    class ControllerFactory {
        static function createController($type) : SenderOfMessages {
            switch($type) {
                case "TELEGRAMM":
                    return new TelegrammApiController();
                case "VK":
                    return new VkApiController();
            }
        }
    }

    $message = $_POST["message"];

    $vk = ControllerFactory::createController("TELEGRAMM");
    $whatshapp = ControllerFactory::createController("VK");      

    $vk->sendMessage($message);
    $whatshapp->sendMessage($message);

    echo '<script type="text/javascript">',
                "alert('Сообщения отправлены!');",
                "location='http://fabricpattern/index.php'",
                '</script>';
?>