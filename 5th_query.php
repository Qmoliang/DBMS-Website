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

$bsql = "with s1 as (select id from products where name = '".$_GET['p3']."'),
     s2 as (select extract(month from date_sales) as month, extract(year from date_sales) as year, qty, price from sale where product_id in (select * from s1) and date_sales between to_date('".$_GET['p1']."', 'yyyy-mm') and to_date('".$_GET['p2']."', 'yyyy-mm'))
select year, month, sum(qty) as SaleVolume, sum(price)/sum(qty) as AvgSalePrice, '".$_GET['p3']."' as product_name from s2 group by year, month order by year, month";

$bsql2 = "with s1 as (select id from products where name = '".$_GET['p3']."'),
     s2 as (select extract(day from date_sales) as day, extract(month from date_sales) as month, extract(year from date_sales) as year, qty, price from sale where product_id in (select * from s1) and date_sales between to_date('".$_GET['p1']."', 'yyyy-mm') and to_date('".$_GET['p2']."', 'yyyy-mm'))
select year, month, day, sum(qty) as SaleVolume, sum(price) as SaleValue, sum(price)/sum(qty) as AvgSalePrice, '".$_GET['p3']."' as product_name from s2 group by year, month, day order by year, month, day";

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
//echo $bsql2;



$stid = oci_parse($conn, $bsql);

$stid2 = oci_parse($conn, $bsql2);
//sql语句结尾不要有分号

if (!$stid) {
    $e = oci_error($conn);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// Perform the logic of the query
$r = oci_execute($stid);
$r2 = oci_execute($stid2);

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
$row_num2= oci_fetch_all($stid2, $results2);

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

//echo var_dump($results2);

}
?>

<?php
/*
为表格中的内容赋值
*/
//$p_labels = array();
//$p_datasets_0_data = array();

if( !empty($_GET['p1'])){              //未传参时不执行以防报错

$p_line_num=1;
$p_chart_num=2;
$canshuqueren=array(0,0,0);
$duiyingxian=array(0,0,0);

for ($i = 0; $i < count($results['YEAR']);$i++)
      {
           //echo 'YEAR:'.$results['YEAR'][$i].",MONTH:".$results['MONTH'][$i].",SALEPRICE/(SELECTSUM(SALEPRICE)FROMT2):".$results['SALEPRICE/(SELECTSUM(SALEPRICE)FROMT2)'][$i]."\n";
		   
		   $p_datasets_0_label[0][0] =  "Average price of ".$_GET['p3'];
		   $p_datasets_0_label[1][0] =  "Average price of ".$_GET['p3'];
		   
		   $p_labels[0][]=$results['YEAR'][$i]."-".$results['MONTH'][$i];
		   $p_datasets_0_data[0][0][]=$results['AVGSALEPRICE'][$i];
		   

		   
		   /*
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
		   */
       }
	   
	   
for ($i22= 0; $i22 < count($results2['YEAR']);$i22++)
{
	$p_labels[1][]=$results2['YEAR'][$i22]."-".$results2['MONTH'][$i22]."-".$results2['DAY'][$i22];
	$p_datasets_0_data[1][0][]=$results2['AVGSALEPRICE'][$i22];
	
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
var q5c1=1;
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
                Fifth Query
            </h1>
        </div>
        <div id="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="jumbotron">
                        <h2>Average price fluctuation query</h2>
                        <p>  The query returns the change of the monthly average selling price of the product since its release.
<p>User-specific data: time range, product type
<p>Application scenario: Analyze the development trend of the target product and the market performance of the new product, help the enterprise to judge the price trend of the single product, and take measures to control in advance.</p>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="jumbotron">
                        <h4>Selection</h4>
                        <form action="5th_query.php"  class="form-inline">
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
                                <label>Product</label>
                                <select name="p3" class="selectbox">
                                    <option selected="selected" value="[10.21] Lancome pre-open grab hold makeup air cushion new cushion Sheer light liquid foundation isolation BB Cream">[10.21] Lancome pre-open grab hold makeup air cushion new cushion Sheer light liquid foundation isolation BB Cream</option>
											<option value="[10.21] Lancome pre-opening and grab a large pink water + water edge Soothing Cream moisturizing cream suit star">[10.21] Lancome pre-opening and grab a large pink water + water edge Soothing Cream moisturizing cream suit star</option>
											<option value="[10.21] lankou pre-Open grab second generation black bottle portion Genifique 115ml large capacity mounted">[10.21] lankou pre-Open grab second generation black bottle portion Genifique 115ml large capacity mounted</option>
											<option value="Lancome pre-open grab Yanhuan Liang Zhen Jing pure cream 20ml dilute Bose due to eye pattern">Lancome pre-open grab Yanhuan Liang Zhen Jing pure cream 20ml dilute Bose due to eye pattern</option>
											<option value="Lancome/Lancome test connection (not shipped photographed)">Lancome/Lancome test connection (not shipped photographed)</option>
											<option value="Lancome "black bottle" big eye cream eye muscle base solution 20ml light faded black eye wrinkles">Lancome "black bottle" big eye cream eye muscle base solution 20ml light faded black eye wrinkles</option>
											<option value="Lancome Firming Night Cream 50ml plastic Yan pulling pale fine lines hydrating moisturizing cream genuine">Lancome Firming Night Cream 50ml plastic Yan pulling pale fine lines hydrating moisturizing cream genuine</option>
											<option value="Lancome Hypnose Waterproof Mascara 6.5g spiritual eyes big eyes doll waterproof blooming slim curling lengthened encryption">Lancome Hypnose Waterproof Mascara 6.5g spiritual eyes big eyes doll waterproof blooming slim curling lengthened encryption</option>
											<option value="Lancome Miracle tulle liquid foundation 30ml natural long-lasting moisturizing BB Cream Concealer isolation">Lancome Miracle tulle liquid foundation 30ml natural long-lasting moisturizing BB Cream Concealer isolation</option>
											<option value="Lancome Pure Zhen Yan Jing refreshing moisturizing lotion 75ml moisturizing genuine fine lines firming pulling light">Lancome Pure Zhen Yan Jing refreshing moisturizing lotion 75ml moisturizing genuine fine lines firming pulling light</option>
											<option value="Lancome Zhen White Brightening Essence 30ml facial moisturizing whitening to brighten the complexion">Lancome Zhen White Brightening Essence 30ml facial moisturizing whitening to brighten the complexion</option>
											<option value="Lancome black gold Zhen pet cream milk 30ml light sheen soft and smooth fine lines firming moisturizing moisturizing">Lancome black gold Zhen pet cream milk 30ml light sheen soft and smooth fine lines firming moisturizing moisturizing</option>
											<option value="Lancome net through Cleansing Foam Cleanser 125ml refreshing moisturizing Soothing Cleanser">Lancome net through Cleansing Foam Cleanser 125ml refreshing moisturizing Soothing Cleanser</option>
											<option value="Lancome new three-dimensional plastic Yan pulling compact skin firming lotion 100ml Moisturizing fade fine lines">Lancome new three-dimensional plastic Yan pulling compact skin firming lotion 100ml Moisturizing fade fine lines</option>
											<option value="[10.21] sale rush to open new Lancome Miracle secret language perfume 50ml / 100ml fresh floral fragrance France">[10.21] sale rush to open new Lancome Miracle secret language perfume 50ml / 100ml fresh floral fragrance France</option>
											<option value="[New] Lancome makeup velvet hold loose powder 15g hold & lasting waterproof and sweat without makeup oil control powder">[New] Lancome makeup velvet hold loose powder 15g hold & lasting waterproof and sweat without makeup oil control powder</option>
											<option value="pre-open grab Lancome black bottle of ampoule repair muscle at the end Shu Yun soothe the skin cream 20ml">pre-open grab Lancome black bottle of ampoule repair muscle at the end Shu Yun soothe the skin cream 20ml</option>
											<option value="[10.21] Lancome black gold rush to open pre-Zhen pet eye care composition cream petals Mask +">[10.21] Lancome black gold rush to open pre-Zhen pet eye care composition cream petals Mask +</option>
											<option value="[10.21] Lancome pre-open grab repair kit black bottle muscle bottom Essence 30ml + Serum 20ml ampoule">[10.21] Lancome pre-open grab repair kit black bottle muscle bottom Essence 30ml + Serum 20ml ampoule</option>
											<option value="[10.21] Lancome pre-opening and grab a small soft film the essence of muscle at the end infiltration Moisturizing Mask 28g * 7">[10.21] Lancome pre-opening and grab a small soft film the essence of muscle at the end infiltration Moisturizing Mask 28g * 7</option>
											<option value="Lancome pre-open grab 02 lipstick Ruby Queen Jing pure soft cut ruby blipstick">Lancome pre-open grab 02 lipstick Ruby Queen Jing pure soft cut ruby blipstick</option>
											<option value="Lancome pre-open grab Resculpting firming moisturizing cream 15ml Eau fine lines">Lancome pre-open grab Resculpting firming moisturizing cream 15ml Eau fine lines</option>
											<option value="Miracle Lancome fragrance 50ml / 100ml French elegant sweet fresh floral and fruity">Miracle Lancome fragrance 50ml / 100ml French elegant sweet fresh floral and fruity</option>
											<option value="The stars shining bright Yan Gaoguang Lancome limited edition pink high fence red light repair capacity of three-dimensional models with makeup">The stars shining bright Yan Gaoguang Lancome limited edition pink high fence red light repair capacity of three-dimensional models with makeup</option>
											<option value="Jing pure essence of Lancome makeup hold & Powder Concealer to brighten the color lasting moisturizing makeup">Jing pure essence of Lancome makeup hold & Powder Concealer to brighten the color lasting moisturizing makeup</option>
											<option value="Lancome "black bottle" muscle at the end Eye Cream 15ml light-emitting light faded black eye cream">Lancome "black bottle" muscle at the end Eye Cream 15ml light-emitting light faded black eye cream</option>
											<option value="Lancome Precision thick black liquid eyeliner lasting perspiration is not blooming decolorizing carbon black liquid eyeliner smooth soft head">Lancome Precision thick black liquid eyeliner lasting perspiration is not blooming decolorizing carbon black liquid eyeliner smooth soft head</option>
											<option value="Lancome Qing Ying pink water 400ml Toner Moisturizing Lotion soothes skin care woman">Lancome Qing Ying pink water 400ml Toner Moisturizing Lotion soothes skin care woman</option>
											<option value="Lancome Qing Ying rose pink jelly moisturizing mask 100ml water moisturizing Redness Smoothing">Lancome Qing Ying rose pink jelly moisturizing mask 100ml water moisturizing Redness Smoothing</option>
											<option value="Lancome Resculpting moisturizing cream 15ml pulling compact appearance of fine lines around the eyes light faded purple bottle">Lancome Resculpting moisturizing cream 15ml pulling compact appearance of fine lines around the eyes light faded purple bottle</option>
											<option value="[10.21] sale rush to open new Lancome Genifique Eye Mask 10gX7 large slices of whole eye repair">[10.21] sale rush to open new Lancome Genifique Eye Mask 10gX7 large slices of whole eye repair</option>
											<option value="pre-open grab Lancome Eye Care Set black bottle large luminous eyes Serum + Eye Cream">pre-open grab Lancome Eye Care Set black bottle large luminous eyes Serum + Eye Cream</option>
											<option value="[10.21] Lancome Miracle sale rush to open the French fragrance 30ml fresh sweet fragrant fruit lasting light">[10.21] Lancome Miracle sale rush to open the French fragrance 30ml fresh sweet fragrant fruit lasting light</option>
											<option value="[10.21] Lancome pre rush to open the halls of the French romantic and elegant perfume 100ml fresh and elegant fragrance">[10.21] Lancome pre rush to open the halls of the French romantic and elegant perfume 100ml fresh and elegant fragrance</option>
											<option value="[10.21] Lancome pre rush to open wide-angle lupine smudge-proof mascara brush anti-halo makeup swan neck">[10.21] Lancome pre rush to open wide-angle lupine smudge-proof mascara brush anti-halo makeup swan neck</option>
											<option value="[10.21] Lancome pre-open grab accurate Zhen White Brightening Essence 30ml moisturizing whitening to brighten the complexion">[10.21] Lancome pre-open grab accurate Zhen White Brightening Essence 30ml moisturizing whitening to brighten the complexion</option>
											<option value="[10.21] lankou pre grab opening lip 274 phthalocyanine pure enamel paint color light lasting 8ml clarinet">[10.21] lankou pre grab opening lip 274 phthalocyanine pure enamel paint color light lasting 8ml clarinet</option>
											<option value="[10.21] pre-open grab Lancome Men Oil Control Cleansing Gel 100ml clean water moisturizing cleanser">[10.21] pre-open grab Lancome Men Oil Control Cleansing Gel 100ml clean water moisturizing cleanser</option>
											<option value="[10.21] pre-open grab Lancome Skincare flour + water + black bottle water edge Moisturizing Cream">[10.21] pre-open grab Lancome Skincare flour + water + black bottle water edge Moisturizing Cream</option>
											<option value="[10.21] pre-open grab Lancome powder clear water Ying Toner 400ml Moisturizing Lotion soothing female">[10.21] pre-open grab Lancome powder clear water Ying Toner 400ml Moisturizing Lotion soothing female</option>
											<option value="Lancome perfume 14ml Travel Set hall 4 installed dawn jasmine tuberose * 2 + Lavender +">Lancome perfume 14ml Travel Set hall 4 installed dawn jasmine tuberose * 2 + Lavender +</option>
											<option value="Lancome plastic Yan compact Cyber \u200b\u200bWhite Essence 30ml Whitening moisturizing firming">Lancome plastic Yan compact Cyber \u200b\u200bWhite Essence 30ml Whitening moisturizing firming</option>
											<option value="Lancome plastic Yan snowflake condensation 50ml + Toner 200ml whitening package">Lancome plastic Yan snowflake condensation 50ml + Toner 200ml whitening package</option>
											<option value="Lancome pre-open grab pure Jing Sun Cream SPF50 + UV protection sunscreen milk light">Lancome pre-open grab pure Jing Sun Cream SPF50 + UV protection sunscreen milk light</option>
											<option value="Lancome water edge Soothing Moisturizing Cream 50ml Moisturizing cream fresh oil control cream soothes skin">Lancome water edge Soothing Moisturizing Cream 50ml Moisturizing cream fresh oil control cream soothes skin</option>
											<option value="New Lancome makeup before the curd 25ml Facial smooth fine lines and pores nude makeup makeup to play">New Lancome makeup before the curd 25ml Facial smooth fine lines and pores nude makeup makeup to play</option>
											<option value="New Lancome water edge soothing moisturizing cream 50ml moisture replenishment tender roses fresh cream genuine">New Lancome water edge soothing moisturizing cream 50ml moisture replenishment tender roses fresh cream genuine</option>
											<option value="The second generation of black bottle Lancome Genifique 30ml * 2 Moisturizing light lines">The second generation of black bottle Lancome Genifique 30ml * 2 Moisturizing light lines</option>
											<option value="Lancome Pure Zhen Yan Jing essence cream 60ml nourishing moisturizing cream compact edition fine lines">Lancome Pure Zhen Yan Jing essence cream 60ml nourishing moisturizing cream compact edition fine lines</option>
											<option value="Lancome cushion CC cream milk repair Yan isolation liquid foundation with sunscreen ZhouDongYu money">Lancome cushion CC cream milk repair Yan isolation liquid foundation with sunscreen ZhouDongYu money</option>
											<option value="Lancome lip 274 phthalocyanine enamel paint pure light moisturizing liquid lip gloss lipstick balm red tea, red 515">Lancome lip 274 phthalocyanine enamel paint pure light moisturizing liquid lip gloss lipstick balm red tea, red 515</option>
											<option value="Lancome muscle at the end Eye Serum Eye Cream 20ml big eyes light lines nourish lashes">Lancome muscle at the end Eye Serum Eye Cream 20ml big eyes light lines nourish lashes</option>
											<option value="[10.21] sale rush to open plastic Yan Lancome Skincare Eye Gel + water moisturizing firming pulling">[10.21] sale rush to open plastic Yan Lancome Skincare Eye Gel + water moisturizing firming pulling</option>
											<option value="[Preemptive purchase plus 196 at 0:00 on the 21st rush to open] Lancome 196 Jing pure velvet matte lipstick matte lipstick">[Preemptive purchase plus 196 at 0:00 on the 21st rush to open] Lancome 196 Jing pure velvet matte lipstick matte lipstick</option>
											<option value="grab pre-Open Lancome black bottle essence muscle bottom emission light faded black eye cream 15ml">grab pre-Open Lancome black bottle essence muscle bottom emission light faded black eye cream 15ml</option>
											<option value="pre-open grab Lancome Pure Zhen Jing facial cream 60ml moisturizing whitening nourishing compact version">pre-open grab Lancome Pure Zhen Jing facial cream 60ml moisturizing whitening nourishing compact version</option>
											<option value="pre-open grab Lancome black bottle big eyes Eye Cream 20ml light faded eye pattern improve the eye">pre-open grab Lancome black bottle big eyes Eye Cream 20ml light faded eye pattern improve the eye</option>
											<option value="pre-open grab Lancome eye cream + pure Jing Jing Jing pure essence of pure water + cream Skin Care Set">pre-open grab Lancome eye cream + pure Jing Jing Jing pure essence of pure water + cream Skin Care Set</option>
											<option value="sale rush to open new black bottle Lancome Genifique face delicate skin 50ml">sale rush to open new black bottle Lancome Genifique face delicate skin 50ml</option>
											<option value="Lancome pre rush to open new face black bottle 30ml Genifique skin repair">Lancome pre rush to open new face black bottle 30ml Genifique skin repair</option>
											<option value="Lancome pre rush to open new large black bottle 30ml + Eye Essence Skincare improve the appearance of fine lines">Lancome pre rush to open new large black bottle 30ml + Eye Essence Skincare improve the appearance of fine lines</option>
											<option value="Lancome shook transfected air cushion light lip soft matte lipstick liquid enamel Matte liquid lip balm lasting moisture">Lancome shook transfected air cushion light lip soft matte lipstick liquid enamel Matte liquid lip balm lasting moisture</option>
											<option value="Lancome skin care early adopters Star Gift Star Gift Vouchers">Lancome skin care early adopters Star Gift Star Gift Vouchers</option>
											<option value="Light air cushion pad Lancome lip and cheek blush dual 7g effort rosy nude makeup moisturizing">Light air cushion pad Lancome lip and cheek blush dual 7g effort rosy nude makeup moisturizing</option>
											<option value="New Lancome Qing Ying cleansing mousse gently clean pores 200ml fresh oil control not tight Cleansing Foam">New Lancome Qing Ying cleansing mousse gently clean pores 200ml fresh oil control not tight Cleansing Foam</option>
											<option value="New Lancome water edge Soothing Cream 50ml fresh nourish moisturizing moisturizing repair cream genuine">New Lancome water edge Soothing Cream 50ml fresh nourish moisturizing moisturizing repair cream genuine</option>
											<option value="Cream Lancome UV sunscreen UV white tube light sensing air permeable protective moisturizing SPF50">Cream Lancome UV sunscreen UV white tube light sensing air permeable protective moisturizing SPF50</option>
											<option value="Gentle Lancome lipstick mouth red lips Jing pure color monitor color lasting moisturizing not pull dry">Gentle Lancome lipstick mouth red lips Jing pure color monitor color lasting moisturizing not pull dry</option>
											<option value="Lancome Hypnose Mascara black star shining thick curly slim waterproof anti-blooming longer encryption">Lancome Hypnose Mascara black star shining thick curly slim waterproof anti-blooming longer encryption</option>
											<option value="Lancome Jing pure cream cushion BB Cream Concealer Moisturizing nourishing liquid foundation lasting moisture docile Liu Tao same paragraph">Lancome Jing pure cream cushion BB Cream Concealer Moisturizing nourishing liquid foundation lasting moisture docile Liu Tao same paragraph</option>
											<option value="Lancome Pure Zhen Yan Jing Night Repair 15ml rose essential oils soothe and nourish tough replenishment">Lancome Pure Zhen Yan Jing Night Repair 15ml rose essential oils soothe and nourish tough replenishment</option>
											<option value="Lancome Pure Zhen Yan Jing essence of rose water 150ml + new compact Jing pure cream">Lancome Pure Zhen Yan Jing essence of rose water 150ml + new compact Jing pure cream</option>
											<option value="Lancome Pure Zhen Yan Jing essence of rose water 150ml moisturizing antioxidant repair Toner Lotion">Lancome Pure Zhen Yan Jing essence of rose water 150ml moisturizing antioxidant repair Toner Lotion</option>
											<option value="Lancome Ultimate Whitening Beauty Lotion 100ml fade scars brightens the complexion Moisture Surge Refreshing">Lancome Ultimate Whitening Beauty Lotion 100ml fade scars brightens the complexion Moisture Surge Refreshing</option>
											<option value="Lancome lipstick soft satin Jing pure color lipstick lasting moisturizing 120 Wangjun Kai strongly recommend moisturizing orange red tea">Lancome lipstick soft satin Jing pure color lipstick lasting moisturizing 120 Wangjun Kai strongly recommend moisturizing orange red tea</option>
											<option value="Lancome makeup air cushion CC cream moisturizing cream color moist air cushion does not naturally nourishing meal card slim">Lancome makeup air cushion CC cream moisturizing cream color moist air cushion does not naturally nourishing meal card slim</option>
											<option value="pre-open grab Lancome 50ml plastic Yan pulling compact whitening cream Huan frost snowflake">pre-open grab Lancome 50ml plastic Yan pulling compact whitening cream Huan frost snowflake</option>
											<option value="[10.21] Lancome pre rush to open new black gold Zhen pet fine lines firming cream 50ml Eau Tila Ti Liang">[10.21] Lancome pre rush to open new black gold Zhen pet fine lines firming cream 50ml Eau Tila Ti Liang</option>
											<option value="[10.21] Lancome pre-open grab bean paste color lipstick Lip Gloss Lipstick Jing pure color lasting moisture">[10.21] Lancome pre-open grab bean paste color lipstick Lip Gloss Lipstick Jing pure color lasting moisture</option>
											<option value="[10.21] Lancome pre-open grab hold makeup foundation concealer stick lasting nude makeup natural light through the non-portable">[10.21] Lancome pre-open grab hold makeup foundation concealer stick lasting nude makeup natural light through the non-portable</option>
											<option value="[10.21] Lancome pre-opening and grab beautiful lady life light fragrance perfume floral fragrance French romantic">[10.21] Lancome pre-opening and grab beautiful lady life light fragrance perfume floral fragrance French romantic</option>
											<option value="[10.21] Lancome pre-opening and grab the star makeup set Jing pure balm lipstick 196+ hold liquid foundation makeup">[10.21] Lancome pre-opening and grab the star makeup set Jing pure balm lipstick 196+ hold liquid foundation makeup</option>
											<option value="[10.21] pre-open grab Lancome lipstick 196 + Jing pure balm perfume makeup set Beautiful Life">[10.21] pre-open grab Lancome lipstick 196 + Jing pure balm perfume makeup set Beautiful Life</option>
											<option value="Lancome perspective Resculpting condensation water 200ml water Toner pulling compact memory">Lancome perspective Resculpting condensation water 200ml water Toner pulling compact memory</option>
											<option value="Lancome pre-open grab Jing pure cream essence cream 30ml light color due to the dampening wave of anti-early aging">Lancome pre-open grab Jing pure cream essence cream 30ml light color due to the dampening wave of anti-early aging</option>
											<option value="Liang Yan Lancome lipstick bright lipstick color disk disk 290 limited edition lip gloss lipstick color all in hot bean paste">Liang Yan Lancome lipstick bright lipstick color disk disk 290 limited edition lip gloss lipstick color all in hot bean paste</option>
											<option value="New Lancome Qing Ying rejuvenation water 200ml / 400ml blue water moisturizing lotion moisturizing lotion">New Lancome Qing Ying rejuvenation water 200ml / 400ml blue water moisturizing lotion moisturizing lotion</option>
											<option value="Toner powder Lancome Water 400ml + 50ml water edge Soothing Cream replenishment Qingying">Toner powder Lancome Water 400ml + 50ml water edge Soothing Cream replenishment Qingying</option>
											<option value="Jing pure Lancome Sunscreen Lotion SPF50 sunscreen UV old black moistening">Jing pure Lancome Sunscreen Lotion SPF50 sunscreen UV old black moistening</option>
											<option value="Lancome Pure Zhen Yan Jing Rose Rose Mask 75ml Moisturizing soothing tough repair">Lancome Pure Zhen Yan Jing Rose Rose Mask 75ml Moisturizing soothing tough repair</option>
											<option value="Lancome black bottle Serum 30ml + Facial Treatment Skincare cream emitting Female">Lancome black bottle Serum 30ml + Facial Treatment Skincare cream emitting Female</option>
											<option value="Lancome black bottle portion Genifique repair muscle at the end to improve the drying of fine lines">Lancome black bottle portion Genifique repair muscle at the end to improve the drying of fine lines</option>
											<option value="Lancome liquid foundation 30ml Zhen Jing Yan pure essence oil control lasting moistening liquid foundation concealer Natural Moisturizing Cream BB">Lancome liquid foundation 30ml Zhen Jing Yan pure essence oil control lasting moistening liquid foundation concealer Natural Moisturizing Cream BB</option>
											<option value="Lancome makeup foundation holding stick lasting natural light through the bare makeup Concealer Foundation anointing leather love">Lancome makeup foundation holding stick lasting natural light through the bare makeup Concealer Foundation anointing leather love</option>
											<option value="[10.21] Lancome pre-open grab Jing pure Skincare Rose Essence Water + Jing pure cream dilute the eye pattern">[10.21] Lancome pre-open grab Jing pure Skincare Rose Essence Water + Jing pure cream dilute the eye pattern</option>
											<option value="[10.21] Lancome pre-open grab Jing pure matte lipstick matte lipstick double support package Lancome lipstick 196">[10.21] Lancome pre-open grab Jing pure matte lipstick matte lipstick double support package Lancome lipstick 196</option>
											<option value="[10.21] Lancome pre-open grab Miss Jing pure gold tube of lipstick red lipstick net 525 Cherry Red">[10.21] Lancome pre-open grab Miss Jing pure gold tube of lipstick red lipstick net 525 Cherry Red</option>
											<option value="[10.21] Lancome pre-open grab cushion CC Cream Concealer isolated milk liquid foundation makeup lasting nude female brighten">[10.21] Lancome pre-open grab cushion CC Cream Concealer isolated milk liquid foundation makeup lasting nude female brighten</option>
											<option value="Lancome pre-open grab Zhen Yan Jing pure rose essence water 150ml moisturizing antioxidant repair">Lancome pre-open grab Zhen Yan Jing pure rose essence water 150ml moisturizing antioxidant repair</option>
											<option value="Lancome pre-open grab fresh water edge Soothing Moisturizing Cream Moisturizing Day Cream Rose">Lancome pre-open grab fresh water edge Soothing Moisturizing Cream Moisturizing Day Cream Rose</option>
											<option value="Pre-open [10.21] Lancome grab Jing pure liquid foundation moisturizing sunscreen cream cushion BB cream Tao same paragraph">Pre-open [10.21] Lancome grab Jing pure liquid foundation moisturizing sunscreen cream cushion BB cream Tao same paragraph</option>
											<option value="Lancome Pure Zhen Jing Yan Huanliang firming eye cream 20ml pulling fade fine lines around the eye contour">Lancome Pure Zhen Jing Yan Huanliang firming eye cream 20ml pulling fade fine lines around the eye contour</option>
											<option value="Lancome Pure Zhen Yan Jing essence moisturizing cream 60ml light skin type">Lancome Pure Zhen Yan Jing essence moisturizing cream 60ml light skin type</option>
											<option value="Lancome Qing Ying Rose Honey Scrub Mask 100ml Moisturizing bright clean complexion">Lancome Qing Ying Rose Honey Scrub Mask 100ml Moisturizing bright clean complexion</option>
											<option value="Lancome black bottle of ampoule repair muscle at the end Shu Yun Serum 20ml Soothing Sensitive">Lancome black bottle of ampoule repair muscle at the end Shu Yun Serum 20ml Soothing Sensitive</option>
											<option value="Lancome cherished perfume 30ml / 50ml / 100ml Ms. France lasting fragrance Wangjun Kai rose strongly recommend">Lancome cherished perfume 30ml / 50ml / 100ml Ms. France lasting fragrance Wangjun Kai rose strongly recommend</option>
											<option value="Lancome new holding makeup liquid foundation 30ml lasting light concealer makeup natural bare makeup sunscreen does not">Lancome new holding makeup liquid foundation 30ml lasting light concealer makeup natural bare makeup sunscreen does not</option>
											<option value="[10.21] sale rush to open new Lancome 30ml liquid foundation makeup holding thin-lasting makeup is not stuffy pox lasting concealer">[10.21] sale rush to open new Lancome 30ml liquid foundation makeup holding thin-lasting makeup is not stuffy pox lasting concealer</option>
											<option value="[10.21] sale rush to open the temple perfume Lancome French romantic elegant lady fresh and elegant fragrance">[10.21] sale rush to open the temple perfume Lancome French romantic elegant lady fresh and elegant fragrance</option>
											<option value="grab pre-Open Lancome Aqua gel 30ml UV light through the isolation pipe air white sense sunscreen">grab pre-Open Lancome Aqua gel 30ml UV light through the isolation pipe air white sense sunscreen</option>
											<option value="pre-open grab Lancome powder clear water Ying Toner 400ml Moisturizing Lotion soothing female">pre-open grab Lancome powder clear water Ying Toner 400ml Moisturizing Lotion soothing female</option>
											<option value="[10.21] Lancome black gold rush to open the sale Lotion + Essence + Cream Facial Firming Set">[10.21] Lancome black gold rush to open the sale Lotion + Essence + Cream Facial Firming Set</option>
											<option value="[10.21] grab pre-Open Lancome black bottle Serum 30ml + Moisturizing cream light emitting Vestments">[10.21] grab pre-Open Lancome black bottle Serum 30ml + Moisturizing cream light emitting Vestments</option>
											<option value="[10.21] pre-open grab limited edition Summer Lancome lipstick moisturizing lipstick color red bean paste color 290">[10.21] pre-open grab limited edition Summer Lancome lipstick moisturizing lipstick color red bean paste color 290</option>
											<option value="Lancome pre-opening and grab the star Skincare new black bottle 30ml + large pink water 400ml">Lancome pre-opening and grab the star Skincare new black bottle 30ml + large pink water 400ml</option>
											<option value="Lancome swan neck type mascara brush wide lupine S Curl slim rod affixed wink">Lancome swan neck type mascara brush wide lupine S Curl slim rod affixed wink</option>
											<option value="Serum Lancome black bottle bottom muscle repair kit 30ml + ampoule improving fine wrinkles">Serum Lancome black bottle bottom muscle repair kit 30ml + ampoule improving fine wrinkles</option>
											<option value="Clarisonic / Clarisonic Clarisonic brushhead + 5 sets Lancome'">Clarisonic / Clarisonic Clarisonic brushhead + 5 sets Lancome'</option>
											<option value="FSC Lancome lipstick 525 168 carrot cherry red color sparkling, moisturizing replenishment">FSC Lancome lipstick 525 168 carrot cherry red color sparkling, moisturizing replenishment</option>
											<option value="Lancome Men Oil Control Cleansing Gel 100ml refreshing moisturizing cleanser clean">Lancome Men Oil Control Cleansing Gel 100ml refreshing moisturizing cleanser clean</option>
											<option value="Lancome Pure Zhen Yan Jing cream milk 30ml light texture and delicate abundance of moisturizing facial firming">Lancome Pure Zhen Yan Jing cream milk 30ml light texture and delicate abundance of moisturizing facial firming</option>
											<option value="Lancome compact plastic Yan Huan light moisturizing mask Mask 20gX5 snowflake pattern pulling elastic lip contour delicate pale">Lancome compact plastic Yan Huan light moisturizing mask Mask 20gX5 snowflake pattern pulling elastic lip contour delicate pale</option>
											<option value="[10.21] sale rush to open new Lancome Genifique black bottle 75ml repair skin deep">[10.21] sale rush to open new Lancome Genifique black bottle 75ml repair skin deep</option>
											<option value="[10.21] sale rush to open new Lancome black bottle 30ml + Lipstick Lancome lipstick 196">[10.21] sale rush to open new Lancome black bottle 30ml + Lipstick Lancome lipstick 196</option>
											<option value="pre-open grab Lancome Pure Zhen Jing facial moisturizing cream 60ml light fountain nourish aging">pre-open grab Lancome Pure Zhen Jing facial moisturizing cream 60ml light fountain nourish aging</option>
											<option value="[10.21] Lancome black gold rush to open pre-Zhen pet Lotion 150ml Moisturizing Rose moisturizing spray">[10.21] Lancome black gold rush to open pre-Zhen pet Lotion 150ml Moisturizing Rose moisturizing spray</option>
											<option value="[10.21] Lancome pre-open grab Zhen Yan Jing pure cream liquid foundation 30ml lasting moisturizing sunscreen Concealer">[10.21] Lancome pre-open grab Zhen Yan Jing pure cream liquid foundation 30ml lasting moisturizing sunscreen Concealer</option>
											<option value="[10.21] Lancome pre-open grab hold makeup liquid foundation + CC cushion shell lasting high Refreshing nude makeup concealer">[10.21] Lancome pre-open grab hold makeup liquid foundation + CC cushion shell lasting high Refreshing nude makeup concealer</option>
											<option value="[10.21] Lancome pre-opening and grab the net through Cleansing Foam Cleanser 125ml refreshing moisturizing deep cleansing">[10.21] Lancome pre-opening and grab the net through Cleansing Foam Cleanser 125ml refreshing moisturizing deep cleansing</option>
											<option value="[10.21] pre-open grab Lancome Miracle tulle liquid foundation 30ml dry skin moisturizing concealer lasting love">[10.21] pre-open grab Lancome Miracle tulle liquid foundation 30ml dry skin moisturizing concealer lasting love</option>
											<option value="[10.21] pre-open grab Lancome Qing Ying Toner powder spray water mist spray 100ml Moisturizing Soothing">[10.21] pre-open grab Lancome Qing Ying Toner powder spray water mist spray 100ml Moisturizing Soothing</option>
											<option value="Lancome plastic Yan Firming Cream 50ml Hwan snow flower pulling compact whitening cream moisturizing">Lancome plastic Yan Firming Cream 50ml Hwan snow flower pulling compact whitening cream moisturizing</option>
											<option value="Lancome pre-open grab pure essence of water 150ml + Jing Jing pure cream anti-wrinkle skin care set women">Lancome pre-open grab pure essence of water 150ml + Jing Jing pure cream anti-wrinkle skin care set women</option>
											<option value="Lancome small tender film 7 * 28g black bottle muscle at the end Eye Treatment Mask moisturizing tough">Lancome small tender film 7 * 28g black bottle muscle at the end Eye Treatment Mask moisturizing tough</option>
											<option value="Lancome water edge Soothing Serum 30ml deep water lasting moisturizing soothe sensitive muscle brightens the complexion">Lancome water edge Soothing Serum 30ml deep water lasting moisturizing soothe sensitive muscle brightens the complexion</option>
											<option value="Lancome water edge soothing Skin Refreshing Gel 200ml Moisturizing nourishing soothe sensitive skin">Lancome water edge soothing Skin Refreshing Gel 200ml Moisturizing nourishing soothe sensitive skin</option>
											<option value="The new black bottle 30ml + 400ml water powder Lancome Skin Care Set Moisturizing replenishment">The new black bottle 30ml + 400ml water powder Lancome Skin Care Set Moisturizing replenishment</option>
											<option value="Lancome Pure Zhen Yan Jing Rose Lotion Soothing Toner 150ml Moisturizing Lotion">Lancome Pure Zhen Yan Jing Rose Lotion Soothing Toner 150ml Moisturizing Lotion</option>
											<option value="Lancome Qing Ying Toner 200ml pink water temperature and moisture replenishment Toner Lotion">Lancome Qing Ying Toner 200ml pink water temperature and moisture replenishment Toner Lotion</option>
											<option value="Lancome life is beautiful perfume 30ml / 75ml light incense fragrance upscale romantic lady">Lancome life is beautiful perfume 30ml / 75ml light incense fragrance upscale romantic lady</option>
											<option value="[10.21] sale rush to open plastic Yan Lancome Skincare Eye Firming Cream + snow pulling lasting moisture">[10.21] sale rush to open plastic Yan Lancome Skincare Eye Firming Cream + snow pulling lasting moisture</option>
											<option value="[New] Lancome holding makeup concealer to cover acne spots dark circles Lancome facial acne concealer">[New] Lancome holding makeup concealer to cover acne spots dark circles Lancome facial acne concealer</option>
											<option value="[New] Lancome makeup before the velvet holding makeup before the milk Cream 25ml Lancome makeup primer modified skin pores">[New] Lancome makeup before the velvet holding makeup before the milk Cream 25ml Lancome makeup primer modified skin pores</option>
											<option value="lankou grab pre-Open plastic Yan snowflake 50ml + Toner 200ml condensation compact package">lankou grab pre-Open plastic Yan snowflake 50ml + Toner 200ml condensation compact package</option>

                                </select>
                            </div>
							<input type="submit" value="Submit">
                        </form>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="jumbotron">
                        <div class="panel panel-default chartJs">
                            <div class="panel-heading">
                                <div class="card-title">
                                    <div class="title">Line Chart</div>
                                </div>
                            </div>
                            <div class="panel-body" >
                                <canvas id="line-chart0" class="chart" style="position: relative; height:40vh; width:80vw"></canvas>
								<div class="panel-body" >
                                <canvas id="line-chart1" class="chart" style="position: relative; height:40vh; width:80vw"></canvas>
                            </div>
                        </div>
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
