<?php
/**
 * Created by PhpStorm.
 * User: luren
 * Date: 2017/9/19
 * Time: 15:22
 */
function expExcel($arr,$name){

    require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getProperties()->setCreator("xcjock")
        ->setLastModifiedBy("xcjock")
        ->setTitle("导出标题")
        ->setSubject("导出主题")
        ->setDescription("导出数据")
        ->setKeywords("标记")
        ->setCategory("类别");
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->setCellValue('A1','rank')
        ->setCellValue('B1','freq')
        ->setCellValue('C1','word')
        ->setCellValue('D1','fy')
        ->setCellValue('E1','yb')
        ->getStyle('A1:E1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
    $key=1;
    foreach ($arr as $v){
        $key++;
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$key,$v['rank'])
            ->setCellValue('B'.$key,$v['freq'])
            ->setCellValue('C'.$key,$v['word'])
            ->setCellValue('D'.$key,$v['fy'])
            ->setCellValue('E'.$key,$v['yb']);
    }
    $objPHPExcel->setActiveSheetIndex(0);
    ob_end_clean();
    header('Content-Type: application/vnd.ms-excel'); //文件类型
    header('Content-Disposition: attachment;filename="'.$name.'.csv"'); //文件名
    header('Cache-Control: max-age=0');
    header('Content-Type: text/html; charset=utf-8'); //编码
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');  //csv
    $objWriter->save('php://output');
    exit;
}
header("Content-type:text/html;charset=utf-8");

//链接数据库
$dbh = new PDO('mysql:host=localhost;dbname=xc', "root","123456");
$arr = array();
foreach ($dbh->query("select * from test_old")as $row){
    $arr[] = $row; //添加BOM
}
$name = "翻译";

//调用
expExcel($arr,$name);