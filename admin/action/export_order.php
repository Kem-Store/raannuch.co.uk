<?php
//header('Content-Type: text/html; charset=utf-8');
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$_POST[invoice_no].xls");
include("../../libs/SyncDatabase.php"); 
$base = new SyncDatabase();

$bill = $base->Query("SELECT * FROM billing WHERE billing_id = ".$_POST['billing_id']);
$bill = $bill[0];
?>
<style type="text/css">
	table td {
		padding:3px;	
	}
td.header {
	text-align:center;
	font-weight:bold;
}
</style>
<table border="1" style="width:800px;">
	<tr>
		<td colspan="2" class="header">RAAN NUCH LTD.</td>
	</tr>
	<tr>
		<td colspan="2" class="header">6 Deighton Close, Orrell Wigan WN5 8RZ</td>
	</tr>
	<tr>
		<td colspan="2" class="header">Tel. +44(0)78 347 88143</td>
	</tr>
	<tr>
		<td colspan="2" class="header">ORDER DETAILS/INVOICE</td>
	</tr>
	<tr>
		<td colspan="2" class="header" style="height:10px;"></td>
	</tr>
	<tr>
		<td><b>Customer No :</b> <?php echo $bill['billing_id']; ?></td>
		<td style="width:250px;"><b>Invoice No:</b> <?php echo $bill['invoice_no']; ?></td>
	</tr>
	<tr>
		<td><b>Customer Name :</b> <?php echo $bill['firstname'].' '.$bill['lastname']; ?></td>
		<td><b>Date :</b> <?php echo $bill['invoice_date']; ?></td>
	</tr>
	<tr>
		<td rowspan="2" style="vertical-align: top;">
			<b>Address :</b> 
			<?php echo $bill['address1'].' '.$bill['address1'].'<br>'.$bill['city'].$bill['country'].$bill['zipcode']; ?>
		</td>
		<td><b>Email:</b> <?php echo $bill['email']; ?></td>
	</tr>
	<tr>
		<td><b>Tel:</b> <?php echo $bill['tel']; ?></td>
	</tr>
	<tr>
		<td colspan="2" class="header" style="height:20px;"></td>
	</tr>
</table>
<table border="1" style="width:800px;">
  <tr>
    <th width="80">NO.</th>
    <th>DESCRIPTION</th>
    <th width="120">PRICE/UNIT</th>
    <th width="120">QUANTITY</th>
    <th width="120">TOTAL</th>
  </tr>
  <?php 
  $no = 1;
  $total = 0;
  foreach($base->Query("SELECT * FROM billing_detail WHERE billing_id = ".$_POST['billing_id']) as $row) : 
  ?>
  <tr>
    <td style="text-align:center;"><?php echo $no; ?></td>
    <td><?php echo $row['description']; ?></td>
    <td>£<?php echo $row['price']; ?></td>
    <td><?php echo $row['qty']; ?></td>
    <td><?php echo $row['price'] * $row['qty']; ?></td>
  </tr>
  <?php 
  $total += $row['price'] * $row['qty']; 
  $no++; 
  endforeach; 
  ?>
  <tr>
    <td colspan="3"></td>
    <td><b>Total (£)</b></td>
    <td>£<?php echo $total; ?></td>
  </tr>
  <tr>
    <td colspan="3" rowspan="2" style="vertical-align: top;"><b>Note :</b> <?php echo $bill['notes']; ?></td>
    <td><b>Delivery</b></td>
    <td>£<?php echo $bill['delivery']; ?></td>
  </tr>
  <tr>
    <td><b>Total (£)</b></td>
    <td>£<?php echo $total + $bill['delivery']; ?></td>
  </tr>
</table>