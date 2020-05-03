<?
session_start();
include_once $_SERVER['DOCUMENT_ROOT'].'/app/Registry.php';

//unset($_SESSION['F_USER']);
//unset($_SESSION['F_SESSION']);
use Finance\Model;
use Finance\Controller;

$controller = new Controller();
//$model = new Model();
//$model->createDefaultTables(); //создаёт дефолтного пользователя и его аккаунт со средствами
//$controller->createDefaultValues(); //создаёт дефолтного пользователя и его аккаунт со средствами
?>
<html>
<head>
    <title>Финансовые транзакции</title>
</head>
<body>
    <?if($controller->isAuthorized()):
        $uid = explode(':',$_SESSION['F_USER'])[1];
        $uArr = $controller->getUserAccount($uid);
    ?>
        <h1>Здравствуйте, <?=$uArr['name']?></h1>
        <h2>На вашем счету <?=$uArr['funds']?> р.</h2>
        <form action="/fundsTakeOff" method="post">
            <input type="number" name="AMOUNT" max="<?=$uArr['funds']?>" placeholder="Средств списать" />
            <input type="submit" value="Списать">
        </form>
        <br clear="all" />
        <?=$controller->getJournalRecords($uid)?>
    <?else:?>
        <h1>Авторизуйтесь</h1>
        <form action="/auth" method="post">
            <input type="text" name="LOGIN" placeholder="Логин" required/>
            <input type="password" name="PASS" placeholder="Пароль" required/>
            <input type="submit" value="Войти">
        </form>
    <?endif;?>
</body>
</html>
<?
session_write_close();
?>