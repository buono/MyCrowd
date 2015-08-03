<?php
/**
 * Created by PhpStorm.
 * User: yakko_tofu
 * Date: 15/07/24
 * Time: 1:21
 */

class WorksController extends AppController
{
    public $name = 'Work';
    public $uses = 'Work'; //モデルの指定
    public $autoRender = true;

    public function index(){
        $results = $this->Work->find('all');
        $this->set('results', $results); //ビューに渡す

        if($this->request->is('get')){

        }
        //return $this->redirect(array('contoller'=>'main', 'action'=>'index'));
    }

    public function getData(){
    }

    public function setData(){
        $autoRender = false;
        $this->Work->create();

        /*****データを取得******/
        for($page=1; $page<4; $page++){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://www.lancers.jp/work/search/all?page=".$page);
            //curl_setopt($ch, CURLOPT_URL, "http://www.lancers.jp/work/search/all?page=7");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);//標準出力でなく、文字列として取得
            curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0(Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001Firefox/0.10.1");
            //Firefox のふりをしてアクセス
            $res = curl_exec($ch);
            $res = mb_convert_encoding($res, "HTML-ENTITIES", "auto");
            curl_close($ch);
            $dom = @DOMDocument::loadHTML($res);
            $xml = simplexml_import_dom($dom);

            //xpath
            $title = $xml->xpath("//p[@class='work_title']/a");
            $detail = $xml->xpath("//p[@class='description hide']");
            $type_img = $xml->xpath("//p[@class='price_icn']/img");
            $category1 = $xml->xpath("//p[@class='category']/a[1]");
            $category2 = $xml->xpath("//p[@class='category']/a[2]");
            $proposal_num = $xml->xpath("//td[@class='stats propose']/span/a");
            $left_time = $xml->xpath("//td[@class='stats time']/p");
            $url = $xml->xpath("//p[@class='work_title']/a/@href");
            //タイプごとに報酬を割り当て
            $_reward_upper_compe = $xml->xpath("//span[@class='work-price-span price-number']");
            $_reward_lower_compe = 0;
            $_reward_upper_proj = $xml->xpath("//span[@class='price-block bottom']/span[@class='work-price-span price-number']");
            $_reward_lower_proj = $xml->xpath("//span[@class='price-block top']/span[@class='work-price-span price-number']");
            $_reward_upper_task = $xml->xpath("//span[@class='work-price-span price-number']");
            $_reward_lower_task = 0;

            /********データベースへ送信******/
            for($i=0; $i<count($title); $i++) {
                //for($i=0; $i<count($title); $i++){

                //残り時間の修正
                if ($left_time[$i]->span) {
                    $left_time[$i] = $left_time[$i]->span;
                } else {
                    $left_time[$i] = $left_time[$i];
                }

                //タイプを文字に変換
                if ($type_img[$i]["src"] == '/img/icon/competition.gif') {
                    $type[$i] = "コンペ";
                } else if ($type_img[$i]["src"] == '/img/icon/project.gif') {
                    $type[$i] = "プロジェクト";
                } else if ($type_img[$i]["src"] == '/img/icon/task.gif') {
                    $type[$i] = "タスク";
                } else {
                    $type[$i] = "no hit";
                }

                //報酬をタイプごとに割り当て
                $x = 0;
                $y = 0;
                $z = 0;
                if ($type[$i] == "コンペ") {
                    $_reward_upper[$i] = $_reward_upper_compe[$x];
                    $_reward_lower[$i] = 0;
                    $x++;
                } else if ($type[$i] == "プロジェクト") {
                    $_reward_upper[$i] = $_reward_upper_proj[$y];
                    $_reward_lower[$i] = $_reward_lower_proj[$y];
                    $y++;
                } else if ($type[$i] == "タスク") {
                    $_reward_upper[$i] = $_reward_upper_task[$z];
                    $_reward_lower[$i] = 0;
                    $z++;
                } else {
                    $_reward_upper[$i] = 0;
                    $_reward_lower[$i] = 0;
                }

                //報酬を数字に変換
                $reward_upper[$i] = str_replace(',', '', $_reward_upper[$i]);
                $reward_lower[$i] = str_replace(',', '', $_reward_lower[$i]);

                //非公開案件の提案数-を0へ
                if ($proposal_num[$i] = "-") {
                    $proposal_num[$i] = 0;
                }


                //データのまとめ
                $data = Array
                (
                    'Work' => Array
                    (
                        'id' => null,
                        'title' => $title[$i],
                        'detail' => $detail[$i],
                        'type' => $type[$i],
                        'category1' => $category1[$i],
                        'category2' => $category2[$i],
                        'reward_upper' => $reward_upper[$i],
                        'reward_lower' => $reward_lower[$i],
                        'proposal_num' => $proposal_num[$i],
                        'left_time' => $left_time[$i],
                        'url' => $url[$i]
                    )
                );
                $this->Work->save($data, false);
            }
            sleep(1);
        }

    }
}