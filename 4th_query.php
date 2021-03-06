<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta content="" name="description" />
    <meta content="webthemez" name="author" />
    <title>DBMS Term Project</title>
    <!-- Bootstrap Styles-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FontAwesome Styles-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- Morris Chart Styles-->
    <link href="assets/js/morris/morris-0.4.3.min.css" rel="stylesheet" />
    <!-- Custom Styles-->
    <link href="assets/css/custom-styles.css" rel="stylesheet" />
    <!-- Google Fonts-->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
</head>

<body>

<?php

if( !empty($_GET['p1'])){              //未传参时不执行以防报错

//连接数据库

$conn = oci_connect('zhuobiao', 'wulianxu7916', 'oracle.cise.ufl.edu:1521/orcl');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// Prepare the statement

$bsql = "with s1 as (select extract(month from date_sales) as month, extract(year from date_sales) as year, price, product_ID as productID, c_id as userID, qty, o_status FROM sale
            where date_sales between to_date('".$_GET['p1']."', 'yyyy-mm') and to_date('".$_GET['p2']."', 'yyyy-mm')),
     t1 as (select s1.* from s1 join users on userID = users.id where area = '".$_GET['p3']."'), --filter by city
     t2 as (select t1.* from t1 join Products on productID = products.id where categorie_id = (select ID from categories where name = '".$_GET['p4']."')), -- filter by type
     t3 as (select productID, count(*) as count_cancel from t2 where o_status = 'cancel' group by productID),
     t4 as (select productID from t3 where count_cancel >= all (select count_cancel from t3)), --get the product_ids which have the most return records
     t5 as (select productID, year, month, count(*) as count_cancel from t2 where o_status = 'cancel' and productID in (select * from t4) group by productID, year, month order by productID, year, month),
     t6 as (select productID, year, month, count(*) as count_total from t2 where productID in (select * from t4) group by productID, year, month order by productID, year, month)
select (select name from products where id = productID), year, month,count_cancel, count_cancel/count_total as cancel_rate from t5 natural join t6";
/*		
if( !empty($_GET['p3'])&&($_GET['p3']!='optional')){ 
$bsql=$bsql."'".$_GET['p3']."'";
}

if( !empty($_GET['p4'])&&($_GET['p4']!='optional')){ 
$bsql=$bsql.','."'".$_GET['p4']."'";
}

if( !empty($_GET['p5'])&&($_GET['p5']!='optional')){ 
$bsql=$bsql.','."'".$_GET['p5']."'";
}

$bsql=$bsql.")
        group by categories.name, month, year
        order by categories.name, year, month";
*/		
//echo $bsql;



$stid = oci_parse($conn, $bsql);
//sql语句结尾不要有分号

if (!$stid) {
    $e = oci_error($conn);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// Perform the logic of the query
$r = oci_execute($stid);
if (!$r) {
    $e = oci_error($stid);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

/*// Fetch the results of the query
print "<table border='1'>\n";
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
    print "<tr>\n";
    foreach ($row as $item) {
        print "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
    }
    print "</tr>\n";
}
print "</table>\n";
*/

$row_num= oci_fetch_all($stid, $results);

//echo count($results['YEAR']);
/*
for ($i = 0; $i < count($results['YEAR']);$i++)
      {
           echo 'YEAR:'.$results['YEAR'][$i].",MONTH:".$results['MONTH'][$i].",SALEPRICE/(SELECTSUM(SALEPRICE)FROMT2):".$results['SALEPRICE/(SELECTSUM(SALEPRICE)FROMT2)'][$i]."\n";
       }
//var_dump($results);
*/
oci_free_statement($stid);
oci_close($conn);

/*if( !empty($_GET['p1'])){
    echo $_GET['p2'];
}
else{
    echo '尚未指定sql参数';
}
//方法可行
*/


}
?>

<?php
/*
为表格中的内容赋值
*/
//$p_labels = array();
//$p_datasets_0_data = array();

if(0){              //不画表，因此不执行

$p_line_num=0;
$p_chart_num=2;
$canshuqueren=array(0,0,0);
$duiyingxian=array(0,0,0);

for ($i = 0; $i < count($results['YEAR']);$i++)
      {
           //echo 'YEAR:'.$results['YEAR'][$i].",MONTH:".$results['MONTH'][$i].",SALEPRICE/(SELECTSUM(SALEPRICE)FROMT2):".$results['SALEPRICE/(SELECTSUM(SALEPRICE)FROMT2)'][$i]."\n";
		   
		   
		   if( !empty($_GET['p3'])&&($_GET['p3']!='optional')){    //对p3参数查询返回的数据的处理
		   if($results['NAME'][$i]==$_GET['p3'])
		   {
			   if($canshuqueren[0]==0)
			   {
					$p_line_num+=1; //确定一个输入参数，即每张图需要1条线来表示
					$canshuqueren[0]=1;//确认该参数存在
					$duiyingxian[0]=$p_line_num-1;

					
					$p_datasets_0_label[0][$duiyingxian[0]] =  "Sale value of ".$_GET['p3'];    //线条名
					$p_datasets_0_label[1][$duiyingxian[0]] =  "Sale volume of ".$_GET['p3'];
			   }
			   
			   for ($nchart = 0; $nchart < $p_chart_num;$nchart++)
			   {
				   if($nchart==0)
				   {
			   $p_labels[$nchart][]=$results['YEAR'][$i]."-".$results['MONTH'][$i];
			   $p_datasets_0_data[$nchart][$duiyingxian[0]][]=$results['SALEPRICE'][$i];
				   }
				   if($nchart==1)
				   {
			   $p_labels[$nchart][]=$results['YEAR'][$i]."-".$results['MONTH'][$i];
			   $p_datasets_0_data[$nchart][$duiyingxian[0]][]=$results['SALEVOLUME'][$i];
				   }
			   }

		   }}
		   
		   if( !empty($_GET['p4'])&&($_GET['p4']!='optional')){    //对p4参数查询返回的数据的处理
		   if($results['NAME'][$i]==$_GET['p4'])
		   {
			   if($canshuqueren[1]==0)
			   {
					$p_line_num+=1; //确定一个输入参数，即每张图需要1条线来表示
					$canshuqueren[1]=1;//确认该参数存在
					$duiyingxian[1]=$p_line_num-1;

					
					$p_datasets_0_label[0][$duiyingxian[1]] =  "Sale value of ".$_GET['p4'];    //线条名
					$p_datasets_0_label[1][$duiyingxian[1]] =  "Sale volume of ".$_GET['p4'];
			   }
			   
			   for ($nchart = 0; $nchart < $p_chart_num;$nchart++)
			   {
				   if($nchart==0)
				   {
			   //$p_labels[$nchart][]=$results['YEAR'][$i]."-".$results['MONTH'][$i];
			   $p_datasets_0_data[$nchart][$duiyingxian[1]][]=$results['SALEPRICE'][$i];
				   }
				   if($nchart==1)
				   {
			   //$p_labels[$nchart][]=$results['YEAR'][$i]."-".$results['MONTH'][$i];
			   $p_datasets_0_data[$nchart][$duiyingxian[1]][]=$results['SALEVOLUME'][$i];
				   }
			   }

		   }}		   
		   
		   if( !empty($_GET['p5'])&&($_GET['p5']!='optional')){    //对p5参数查询返回的数据的处理
		   if($results['NAME'][$i]==$_GET['p5'])
		   {
			   if($canshuqueren[2]==0)
			   {
					$p_line_num+=1; //确定一个输入参数，即每张图需要1条线来表示
					$canshuqueren[2]=1;//确认该参数存在
					$duiyingxian[2]=$p_line_num-1;
		
					
					$p_datasets_0_label[0][$duiyingxian[2]] =  "Sale value of ".$_GET['p5'];    //线条名
					$p_datasets_0_label[1][$duiyingxian[2]] =  "Sale volume of ".$_GET['p5'];
			   }
			   
			   for ($nchart = 0; $nchart < $p_chart_num;$nchart++)
			   {
				   if($nchart==0)
				   {
			   //$p_labels[$nchart][]=$results['YEAR'][$i]."-".$results['MONTH'][$i];
			   $p_datasets_0_data[$nchart][$duiyingxian[2]][]=$results['SALEPRICE'][$i];
				   }
				   if($nchart==1)
				   {
			   //$p_labels[$nchart][]=$results['YEAR'][$i]."-".$results['MONTH'][$i];
			   $p_datasets_0_data[$nchart][$duiyingxian[2]][]=$results['SALEVOLUME'][$i];
				   }
			   }

		   }}		   
		   
       }

echo '---';
echo $canshuqueren[0].$canshuqueren[1].$canshuqueren[2];
echo '---';
echo !empty($_GET['p4'])&&($_GET['p4']!='optional');
//$p_labels = array('Jan', 'hahahahaha', 'gai', 'Apr', 'May', 'Jun', 'Jul');
//$p_datasets_0_data = array(1765, 59, 0, 81, 56, 2355, 1140);
//$p_datasets_0_label[$p_chart_num-1][$p_line_num-1] =  'Eye shadow Sales ratio';


}
?>

<script>
/*
将前面php段中的值传递到javascript中
*/
function Array2D(x)
{
    var array2D = new Array(x);

    for(var i = 0; i < array2D.length; i++)
    {
        array2D[i] = new Array();
    }

    return array2D;
}

function Array3D(x, y)
{
    var array2D = new Array(x);

    for(var i = 0; i < array2D.length; i++)
    {
        array2D[i] = new Array(y);
		for(var j = 0; j < array2D[i].length; j++)
		{
			array2D[i][j] = new Array();
		}
    }

    return array2D;
}

//var myNewArray = Array2D(4);

//myNewArray[3][5] = "booger";

var pj_labels = Array2D(24);
var pj_datasets_0_label = Array2D(24);
var pj_datasets_0_data = Array3D(24,6);

var line_num = <?php echo $p_line_num ?>;
var chart_num = <?php echo $p_chart_num ?>;

pj_labels = <?php echo json_encode($p_labels) ?>;
pj_datasets_0_label = <?php echo json_encode($p_datasets_0_label ) ?>; 
pj_datasets_0_data= <?php echo json_encode($p_datasets_0_data) ?>; 

var ylabelString=" ";
var q5c1=0;
var q6c1=0;

console.log(pj_labels);
console.log(pj_datasets_0_label);
console.log(pj_datasets_0_data);

console.log(line_num);
console.log(chart_num);

var istacked=0;

</script>

<script type="text/javascript">

var t2 = <?php echo json_encode($p_datasets_0_label) ?>;
//console.log(t2);
</script> 

<div id="wrapper">
    <nav class="navbar navbar-default top-navbar" role="navigation">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="test.html"><strong><i class="icon fa fa-plane"></i> Group 5 project </strong></a>
        </div>

        <ul class="nav navbar-top-links navbar-right">
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                    <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li><a href="login.html"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
    <!--/. NAV TOP  -->
    <nav class="navbar-default navbar-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav" id="main-menu">
                <li>
                    <a href="test.php"><i class="fa fa-dashboard"></i> Home </a>
                </li>

                <li>
                    <a href="1st_query.php"><i class="fa fa-desktop"></i> Query 1 </a>
                </li>

                <li>
                    <a href="2nd_query.php"><i class="fa fa-desktop"></i> Query 2 </a>
                </li>

                <li>
                    <a href="3rd_query.php"><i class="fa fa-desktop"></i> Query 3 </a>
                </li>

                <li>
                    <a href="4th_query.php"><i class="fa fa-desktop"></i> Query 4 </a>
                </li>

                <li>
                    <a href="5th_query.php"><i class="fa fa-desktop"></i> Query 5 </a>
                </li>

                <li>
                    <a href="6th_query.php"><i class="fa fa-desktop"></i> Query 6 </a>
                </li>

                <li>
                    <a href="7th_query.php"><i class="fa fa-desktop"></i> Query 7 </a>
                </li>
            </ul>
        </div>
    </nav>
    <!-- /. NAV SIDE  -->
    <div id="page-wrapper">
        <div class="header">
            <h1 class="page-header">
                Forth Query
            </h1>
        </div>
        <div id="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="jumbotron">
                        <h2>Return rate query</h2>
                        <p> Show the change of monthly return rate of products
<p>User options: time range, product type, area
<p>Application scenario: Sales completion rate is an important indicator of sales efficiency. The return rate inquiry helps the company understand the actual purchase intention of the product, so as to further track and investigate the reason for the product return. </p>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="jumbotron">
                        <h4>Selection</h4>
                        <form action="4th_query.php"  class="form-inline">
                            <div class="form-group">
                                <label>From</label>
                                <select name="p1" class="selectbox">
                                    <option> 2018-01 </option>
                                    <option selected="selected" value="2018-01">2018-01</option>
                                    <option value="2018-02">2018-02</option>
											<option value="2018-03">2018-03</option>
                                    <option value="2018-04">2018-04</option>
											<option value="2018-05">2018-05</option>
                                    <option value="2018-06">2018-06</option>
											<option value="2018-07">2018-07</option>
                                    <option value="2018-08">2018-08</option>
											<option value="2018-09">2018-09</option>
                                    <option value="2018-10">2018-10</option>
											<option value="2018-11">2018-11</option>
                                    <option value="2018-12">2018-12</option>
											<option value="2019-01">2019-01</option>
                                    <option value="2019-02">2019-02</option>
											<option value="2019-03">2019-03</option>
                                    <option value="2019-04">2019-04</option>
											<option value="2019-05">2019-05</option>
											<option value="2019-06">2019-06</option>
											<option value="2019-07">2019-07</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>To</label>
                                <select name="p2" class="selectbox">
                                    <option> 2018-02 </option>
                                    <option selected="selected" value="2018-02">2018-02</option>
											<option value="2018-03">2018-03</option>
                                    <option value="2018-04">2018-04</option>
											<option value="2018-05">2018-05</option>
                                    <option value="2018-06">2018-06</option>
											<option value="2018-07">2018-07</option>
                                    <option value="2018-08">2018-08</option>
											<option value="2018-09">2018-09</option>
                                    <option value="2018-10">2018-10</option>
											<option value="2018-11">2018-11</option>
                                    <option value="2018-12">2018-12</option>
											<option value="2019-01">2019-01</option>
                                    <option value="2019-02">2019-02</option>
											<option value="2019-03">2019-03</option>
                                    <option value="2019-04">2019-04</option>
											<option value="2019-05">2019-05</option>
											<option value="2019-06">2019-06</option>
											<option value="2019-07">2019-07</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Area</label>
                                <select name="p3" class="selectbox">
												<option selected="selected" value="kunming">	kunming	</option>
												<option value="maanshan">	maanshan	</option>
												<option value="jingmen">	jingmen	</option>
												<option value="guanling">	guanling	</option>
												<option value="shijiazhuang">	shijiazhuang	</option>
												<option value="fuxin">	fuxin	</option>
												<option value="nanchang">	nanchang	</option>
												<option value="hangzhou">	hangzhou	</option>
												<option value="chengdu">	chengdu	</option>
												<option value="shenyang">	shenyang	</option>
												<option value="dongguan">	dongguan	</option>
												<option value="foshan">	foshan	</option>
												<option value="huaian">	huaian	</option>
												<option value="qianjiang">	qianjiang	</option>
												<option value="xinji">	xinji	</option>
												<option value="zhangjiagang">	zhangjiagang	</option>
												<option value="handan">	handan	</option>
												<option value="aomen">	aomen	</option>
												<option value="chaozhou">	chaozhou	</option>
												<option value="shenzhen">	shenzhen	</option>
												<option value="luan">	luan	</option>
												<option value="guangzhou">	guangzhou	</option>
												<option value="yidou">	yidou	</option>
												<option value="nanjing">	nanjing	</option>
												<option value="changsha">	changsha	</option>
												<option value="huizhou">	huizhou	</option>
												<option value="taiyuan">	taiyuan	</option>
												<option value="wuzhou">	wuzhou	</option>
												<option value="shanwei">	shanwei	</option>
												<option value="daye">	daye	</option>
												<option value="lanzhou">	lanzhou	</option>
												<option value="changchun">	changchun	</option>
												<option value="taibei">	taibei	</option>
												<option value="liuzhou">	liuzhou	</option>
												<option value="guiyang">	guiyang	</option>
												<option value="ningde">	ningde	</option>
												<option value="beizhen">	beizhen	</option>
												<option value="yinchuan">	yinchuan	</option>
												<option value="chongqing">	chongqing	</option>
												<option value="haimen">	haimen	</option>
												<option value="tongliao">	tongliao	</option>
												<option value="qiqihaer">	qiqihaer	</option>
												<option value="wulumuqi">	wulumuqi	</option>
												<option value="chaohu">	chaohu	</option>
												<option value="heshan">	heshan	</option>
												<option value="xingcheng">	xingcheng	</option>
												<option value="fuzhou">	fuzhou	</option>
												<option value="xian">	xian	</option>
												<option value="huhehaote">	huhehaote	</option>
												<option value="haerbin">	haerbin	</option>
												<option value="beijing">	beijing	</option>
												<option value="xining">	xining	</option>
												<option value="lasa">	lasa	</option>
												<option value="xinganmeng">	xinganmeng	</option>
												<option value="nanning">	nanning	</option>
												<option value="shanghai">	shanghai	</option>
												<option value="yongan">	yongan	</option>
												<option value="tianjin">	tianjin	</option>
												<option value="xianggang">	xianggang	</option>
												<option value="zhengzhou">	zhengzhou	</option>
												<option value="jinan">	jinan	</option>
												<option value="hefei">	hefei	</option>
												<option value="haikou">	haikou	</option>
												<option value="liupanshui">	liupanshui	</option>
												<option value="jiahe">	jiahe	</option>
												<option value="liaoyang">	liaoyang	</option>
												<option value="wuhan">	wuhan	</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Product types</label>
                                <select name="p4" class="selectbox">
                                    <option> make up before the milk </option>
                                    <option selected="selected" value="make up before the milk">make up before the milk</option>
                                    <option value="Foundation">Foundation</option>
											<option value="Concealer">Concealer</option>
											<option value="Powder">Powder</option>
											<option value="Blush">Blush</option>
											<option value="mascara">mascara</option>
											<option value="Eye Shadow">Eye Shadow</option>
											<option value="Eyeliner">Eyeliner</option>
											<option value="eyebrow pencil">eyebrow pencil</option>
											<option value="Lip Glaze">Lip Glaze</option>
											<option value="Lipstick">Lipstick</option>
											<option value="perfume">perfume</option>
											<option value="Clean up remover">Clean up remover</option>
											<option value="Toner">Toner</option>
											<option value="Essence">Essence</option>
											<option value="Emulsion">Emulsion</option>
											<option value="cream">cream</option>
											<option value="Mask">Mask</option>
											<option value="Isolation sunscreen">Isolation sunscreen</option>
											<option value="Eye products">Eye products</option>
											<option value="Makeup">Makeup</option>
											<option value="Men">Men</option>
                                </select>
                            </div>
							<input type="submit" value="Submit">
                        </form>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="jumbotron">
							<br>
							<?php 
							if( !empty($_GET['p1'])){ 
							//直接输出查询结果，不画表
							for ($i = 0; $i < count($results['YEAR']);$i++)
							{
								echo "<h6>Cancel date:".$results['YEAR'][$i]."-".$results['MONTH'][$i]."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp"."Product:".$results['(SELECTNAMEFROMPRODUCTSWHEREID=PRODUCTID)'][$i]."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp"."Cancel number:".$results['COUNT_CANCEL'][$i]."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp"."Cancel rate:".($results['CANCEL_RATE'][$i]*100)."% <br><br></h6>";
							}}
							//echo print_r($results); 
							?>
                    </div>
                </div>
            </div>
        </div>
        <!-- /. PAGE INNER  -->
    </div>
    <!-- /. PAGE WRAPPER  -->
</div>
    <!-- /. WRAPPER  -->
    <!-- JS Scripts-->
    <!-- jQuery Js -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- Bootstrap Js -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- Metis Menu Js -->
    <script src="assets/js/jquery.metisMenu.js"></script>
    <!-- Chart Js -->
    <script type="text/javascript" src="assets/js/Chart.min.js"></script>
    <script type="text/javascript" src="assets/js/chartjs.js"></script>
    <!-- Morris Chart Js -->
    <script src="assets/js/morris/raphael-2.1.0.min.js"></script>
    <script src="assets/js/morris/morris.js"></script>
    <!-- Custom Js -->
    <script src="assets/js/custom-scripts.js"></script>

</div>
</body>
</html>
