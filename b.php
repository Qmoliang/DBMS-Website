<?php

//连接数据库

$conn = oci_connect('zhuobiao', 'wulianxu7916', 'oracle.cise.ufl.edu:1521/orcl');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// Prepare the statement
$stid = oci_parse($conn, "with s1 as (select extract(month from date_sales) as month, extract(year from date_sales) as year, product_ID as productID, c_id as userID, price
            FROM sale
            where date_sales between to_date('2018-09-01', 'yyyy-mm-dd') and to_date('2019-05-01', 'yyyy-mm-dd')),
     s2 as (select s1.* from s1 join users on userID = users.ID where area = 'shanghai'),
     s3 as (select year, month, productID, sum(price) as SaleValue from s2 group by year, month, productID),
     s4 as (select year, month, productID, SaleValue from (select s3.*, row_number() over(partition by s3.year, s3.month order by s3.SaleValue DESC) RN from s3) WHERE RN <= 10)
select year, month, sum(SaleValue) as Top10SaleValueTotal from s4 group by year, month order by year, month");
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



?>

<?php
/*
为表格中的内容赋值
*/
$p_labels = array();
$p_datasets_0_data = array();

for ($i = 0; $i < count($results['YEAR']);$i++)
      {
           //echo 'YEAR:'.$results['YEAR'][$i].",MONTH:".$results['MONTH'][$i].",SALEPRICE/(SELECTSUM(SALEPRICE)FROMT2):".$results['SALEPRICE/(SELECTSUM(SALEPRICE)FROMT2)'][$i]."\n";
		   $p_labels[]=$results['YEAR'][$i]."-".$results['MONTH'][$i];
		   $p_datasets_0_data[]=$results['TOP10SALEVALUETOTAL'][$i];
       }

var_dump($p_labels);
echo"<br>";
echo"<br>";
var_dump($p_datasets_0_data);

//$p_labels = array('Jan', 'hahahahaha', 'gai', 'Apr', 'May', 'Jun', 'Jul');
//$p_datasets_0_data = array(1765, 59, 0, 81, 56, 2355, 1140);
$p_datasets_0_label =  'Top 10 Sale value sum';

$p_line_num=1;

?>

<script>
/*
将前面php段中的值传递到javascript中
*/
var pj_labels = <?php echo json_encode($p_labels) ?>;

var pj_datasets_0_data = <?php echo json_encode($p_datasets_0_data) ?>; 
var pj_datasets_0_label = <?php echo json_encode($p_datasets_0_label) ?>; 

var pj_datasets_1_data = <?php echo json_encode($p_datasets_1_data) ?>; 

var line_num = <?php echo $p_line_num ?>;
</script>