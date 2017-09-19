<?php
/**
 * Created by PhpStorm.
 * User: luren
 * Date: 2017/9/19
 * Time: 15:17
 */
set_time_limit(0);
    $from = "auto";
    $to = "auto";
    $appk = "6fac5eaf5b00d175";
    $salt = uniqid();
function youdaoapi($q){
    global $from,$to,$appk,$salt;
    $sign = md5("$appk"."$q"."$salt"."gSvcBJ5Pg9mL9UhSZ15keQsciG2yHyCp");
    $ch = curl_init("http://openapi.youdao.com/api?q=$q&from=$from&to=$to&appKey=$appk&salt=$salt&sign=$sign");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 400);
    $content = curl_exec($ch);
    curl_close($ch);
    $obj = json_decode($content);
    if ($obj->{'basic'}){
        $basic = $obj->{'basic'};
        $basic1 = get_object_vars($basic);
        $basic2 = $basic1['explains'];
        $a = array($basic2['0'],$basic1['phonetic']);
        return $a;
    }
    else {
        echo "没有查询到"."$q";
    }
}
function updateDb(){
    try {
        $dbh = new PDO('mysql:host=localhost;dbname=xc', "root","123456");
        foreach ($dbh->query("select word from demo")as $row){
            $q= $row['word'];
            $tmp = youdaoapi($q);
            $sth = $dbh->prepare('update demo set fy=:fy,yb=:yb where word=:word');
            $sth->bindParam(":word", $q);
            $sth->bindParam(":fy", $tmp[0]);
            $sth->bindParam(":yb", $tmp[1]);
//             echo $row['word']."<br/>".$tmp[0]."<br/>".$tmp[1]."<br/>";
            if($sth->execute()){
                echo "执行成功"."<br/>";
            }else{
                echo "执行失败！";
            }
        }

    }catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }

}
updateDb();
?>
