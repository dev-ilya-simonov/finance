<?
namespace Finance;

use Finance\Model;
use Finance\View;

class Controller {

    public function isAuthorized() {

        if(!isset($_SESSION['F_USER']) && empty($_SESSION['F_USER']))
            return false;

        return true;

    }

    //default values
    public function createDefaultValues() {
        $model = new Model();

        $userTableArr = [
            'NAME'          => 'Илья',
            'SECOND_NAME'   => 'Симонов',
            'LOGIN'         => 'GodZilA',
            'PASS'          => md5('qwerty'),
            'ACCOUNT_ID'    => 1
        ];
        $uid = $model->add('users',$userTableArr);
        
        $accountTableArr = [
            'USER_ID'       => $uid,
            'FUNDS'         => 275684.53
        ];
        $model->add('account',$accountTableArr);
        
    }

    public function auth() {
        $model = new Model();

        if($_POST['LOGIN'] && $_POST['PASS']) {
            $table = 'users';
            $arSelect = ['*'];
            $arFilter = [
                'LOGIN' => $_POST['LOGIN'],
                'PASS'  => md5($_POST['PASS'])
            ];
            
            $uArr = $model->get($table,$arSelect,$arFilter);
            
            if(!empty($uArr)) {
                $_SESSION['F_USER'] = $uArr[0]['login'].':'.$uArr[0]['ID'];
                $_SESSION['F_SESSION'] = session_id();

                header("Location: /");
            }
        }

        return false;
    }
    
    public function getUserAccount($uid) {
        $model = new Model();
        
        $table = 'users';
        $arSelect = ['*'];
        $arFilter = [
            'users.id' => $uid
        ];
        $arParams = [
            'JOIN' => [
                'TABLE'     => 'account',
                'ON'  => [
                    'users.id' => 'account.user_id'
                ]
            ]
        ];
        
        $uArr = $model->get($table,$arSelect,$arFilter,$arParams);
        
        if(!empty($uArr))
            return $uArr[0];
    }
    
    public function fundsTakeOff() {
        $model = new Model();
        
        $uid = explode(':',$_SESSION['F_USER'])[1];
        $amount = $_POST['AMOUNT'];
        $table = 'journal';
        $journalTableArr = [
            'USER_ID'       => $uid,
            'ACTION'        => 'takeOff',
            'SUM'           => $amount
        ];
        $model->add($table,$journalTableArr);

        $arValues = [
            'FUNDS' => "(funds - $amount)"
        ];
        $arFilter = [
            'USER_ID' => $uid
        ];
        $model->update('account',$arValues,$arFilter);
        header("Location: /");

    }

    public function getJournalRecords($uid){
        $model = new Model();
        
        $table = 'journal';
        $arSelect = ['*'];
        $arFilter = [
            'user_id' => $uid
        ];
        
        $arResult = $model->get($table,$arSelect,$arFilter);

        return View::showJournal($arResult);

    }

}