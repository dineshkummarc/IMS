<title>Expense Report</title>

<p style="font-size:20px" align="center">Expense Report Between {{$from}} <small>To</small> {{$to}}</p>


<table width="100%" cellspacing="0" cellpadding="0" border="1" style="text-align:center">

<tr>
<td><b>SNO</b></td>
<td><b>Narration</b></td>
<td><b>Amount</b></td>
<td><b>Date</b></td>
</tr>

<?php 
$i=0;
foreach($res as $row)
{
		
	//date added formate change					
	$d 	= strtotime($row->date_added);
	$od = date("d-M-y", $d);
	
	//count for total
	$totalArray[] = $row->amount;
	$i++;
?>
<tr>
<td>{{$i}}</td>
<td>{{$row->narration}}</td>
<td>Rs.{{$row->amount}}</td>
<td>{{$od}}</td>
</tr>
<?php } ?>

<tr>
<td>&nbsp;</td>

<td><b>Total</b></td>
<td><b>Rs.{{array_sum($totalArray)}}</b></td>
<td>&nbsp;</td>

</tr>
</table>