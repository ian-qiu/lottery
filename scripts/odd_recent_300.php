<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include dirname(__FILE__) . '/../config/global.php';

include_once ROOT_DIR . 'db/lottery_db_helper.php';

include_once ROOT_DIR . 'model/lottery_util.php';

$util = new LotteryUtil();
$db = new LotteryDBHelper();

$file = ROOT_DIR . 'scripts/重庆时时彩A.txt';

if(!is_file($file)){
    die("$file not exists!");
}

$handle = fopen($file,'r');

$odd_list = array(
    '822','436','724','785','053','888','338','004','427','934','921','584','147','389','424','880','273','978','996','803','694','816','916','636','288','317','070','712','368','923','957','827','026','125','457','007','547','038','314','449','974','704','008','276','738','617','433','882','723','676','953','420','161','707','042','327','071','700','193','054','272','791','749','702','322','216','891','271','938','011','560','395','233','564','586','348','386','094','091','115','759','294','308','226','034','595','557','183','325','056','913','569','047','758','126','993','963','977','316','207','655','719','563','747','475','612','711','377','344','997','490','286','652','666','796','275','270','506','902','001','009','398','618','369','472','242','848','535','607','991','590','478','462','289','650','926','808','988','235','626','534','973','683','446','486','364','258','601','658','177','764','215','265','536','086','138','548','615','672','877','510','587','487','746','441','593','931','530','407','144','251','028','972','234','632','241','052','048','082','437','958','830','994','616','123','628','439','528','363','773','257','579','768','687','874','886','824','104','553','853','300','292','290','543','840','488','688','014','274','955','221','236','812','572','246','901','610','899','780','507','770','716','240','421','995','211','076','952','090','744','051','331','440','776','565','381','197','624','152','175','189','895','599','983','936','385','061','365','735','894','961','393','935','690','644','479','545','836','662','480','942','184','782','753','752','409','307','918','732','249','039','372','410','892','229','443','598','208','689','577','502','145','905','268','261','217','781','924','583','397','760','464','709','127','059','163','366','802','664','453','513','238','541','648','476','110','832','518','162','171','813','164','976','124','728','255','717','471','260','864','452','279','182','057','388','106','745','304','554','981','359','940','412','821','575','087','020','920','699','352','391','225','151','619','945','713','230','245','986','324','763','903','269','757','170','736','310','863','205','469','984','948','281','496','865','929','653','159','357','362','343','422','678','444','774','730','621','743','113','679','872','843','305','089','414','073','573','306','975','435','811','861','477','915','665','962','850','148','734','194','295','674','999','742','247','571','224','520','663','461','199','100','285','778','537','631','336','501','228','027','871','898','933','293','673','029','425','005','122','798','804','335','527','399','762','944','429','741','654','134','772','003','755','896','467','077','118','570','319','505','954','567','465','370','188','129','540','030','103','213','392','638','549','016','491','645','346','708','402','489','282','466','971','312','866','432','879','351','068','754','291','927','806','394','831','580','470','611','667','172','243','142','681','715','680','857','046','250','187','552','065','826','833','720','946','143','858','839','332','884','341','845','731','710','254','093','040','044','201','023','701','367','639','825','508','206','174','684','360','063','111','574','222','829','499','237','210','606','181','630','943','815','697','847','950','878','198','050','130','740','098','339','218','523','964','401','095','705','985','075','280','856','519','121','015','801','765','669','036','970','299','562','320','620','021','374','192','079','509','153','844','202','810','990','982','890','914','659','010','117','529','140','870','517','512','698','641','154','445','525','769','204','640','873','605','081','131','404','064','852','881','538','426','084','334','442','522','779','185','078','337','062','932','455','795','136','842','893','721','835','232','897','066','330','220','013','165','558','133','311','794','846','178','114','787','347','788','196','448','418','323','581','423','303','278','203','105','511','214','417','613','907','876','819','551','889','906','987','657','067','169','609','383','266','494','256','809','396','556','807','682','108','634','262','043','454','431','668','789','775','403','248','035','849','793','350','296','018','797','354','818','751','714','604','447','783','854','766','968','533','223','756','691','859','947','434','576','941','141','589','930','623','219','642','168','006','463','318','646','784','149','909','473','002','922','656','356','072','382','855','937','514','025','167','264','139','500','588','137','661','696','692','939','371','355','173','582','578','107','358','726','375','566','041','949','259','074','353','158','777','910','790','321','191','085','989','860','733','597','297','116','660','132','227','750','092','493','649','883','315','474','284','851','887','069','200','820'            
);

while (!feof($handle)){
    $line = fgets($handle,1024);
    $line = trim($line);
    list($item_date,$code) = explode("\t", $line);
    $item_code = substr($code,2);
    $tmp = $util->getRecent300V2List($item_date, $item_code, 300);
    $recent_300 = array();
    foreach ($tmp as $v) {
        $recent_300[] = substr($v['item_code'],2);
    }
    $recent_300 = array_unique($recent_300);
    $end = array_diff($recent_300, $odd_list);
    print_r($end);
    die();
}