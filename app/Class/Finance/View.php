<?

namespace Finance;

class View {

    public static function showJournal($arResult) {
        $actionArr = [
            'takeOff' => 'Снятие',
            'putOn' => 'Пополнение'
        ];

        $view = '<table align="left">';
        $view .= '
            <tr style="border-botom:2px solid #000;">
                <td>ID</td>
                <td>Действие</td>
                <td>Сумма</td>
                <td>Дата</td>
            </tr>';    
        foreach($arResult as $k => $v):
            $view .= '
                <tr style="border-bottom:1px solid #eee;">
                    <td>'.$v['ID'].'</td>
                    <td>'.$actionArr[$v['action']].'</td>
                    <td>'.$v['sum'].'</td>
                    <td>'.$v['date'].'</td>
                </tr>';
        endforeach;
        $view .= '</table>';
        
        return $view;
    }

}