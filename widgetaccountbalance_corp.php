
<script type="text/javascript">
$(document).ready(function(){

	var selvalue=$('#lblAccNo').val();
	//alert(selvalue);
	var result=selvalue.split(",");
	
	var accno=result[0];
	var branchcode=result[1];
	var strurl="<?php echo Base_URL ."methods/SendISO_BalanceInquiry.php"; ?>";	

	$.ajax({ url: strurl,
		data: {"accountnumber": accno,"Branchcode":branchcode},
		type: 'post',
		dataType:'html',
		success: function(response) { 
			var result=response.split(",");
			if (result[1].length>0 && result[2].length>0 && result[0].length>0)
			{
                var res_1= result[2].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
				$('#lblledbal').html(result[1] +" "+ res_1);
                var res_2= result[0].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
				$('#lblAvlbal').html(result[1] +" "+ res_2);
			   
				//$('#lblledbal').html(result[1] +" "+ result[2]);
				//$('#lblAvlbal').html(result[1] +" "+ result[0]);
			}
		},
		error: function(strerror){

			//alert(strerror);
		}
	});

	$('#lblAccNo').on('change',function(e){ 

		var selvalue=$('#lblAccNo').val();
		//alert(selvalue);
		var result=selvalue.split(",");
		
		var accno=result[0];
		var branchcode=result[1];
		var strurl="<?php echo Base_URL ."methods/SendISO_BalanceInquiry.php"; ?>";	

		$.ajax({ url: strurl,
			data: {"accountnumber": accno,"Branchcode":branchcode},
			type: 'post', 
			success: function(output) { 
				var result=output.split(",");
				if (result[1].length>0 && result[2].length>0 && result[0].length>0)
				{

					var res_3= result[2].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
					$('#lblledbal').html(result[1] +" "+ res_3);
					var res_4= result[0].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
					$('#lblAvlbal').html(result[1] +" "+ res_4);
					//$('#lblledbal').html(result[1] +" "+ result[2]);
					//$('#lblAvlbal').html(result[1] +" "+ result[0]);
				}
			},
			error: function(strerror){

				//alert(strerror);
			}
		});

		return false;	
	});
});
</script>

<?php 
$sql="SELECT vaccountnumber,vbranchcode FROM corp_accountdetails WHERE cstatus='E' AND ncorpid='".$_SESSION['corpid']."'  ORDER BY vaccountnumber";
$retval=mysql_query($sql,$connection);
$topaccount='';
if($retval)
{
	if(mysql_num_rows($retval)>0)
	{
		$content='';
		$rowid=0;
		while($row = mysql_fetch_array($retval, MYSQL_ASSOC))
		{
			if($rowid==0)
			{
				$topaccount=$row['vaccountnumber'];
			}
			$content.="<option value='".$row['vaccountnumber'].",".$row['vbranchcode']."'"; 
			$content.=isset($_POST['lblAccNo']) && $_POST['lblAccNo']==$row['vaccountnumber']?"selected":"";
			$content.=" >".$row['vaccountnumber']." - ".GetAccountName($_SESSION['corpid'],$row['vaccountnumber'],$connection)."</option>";
			$rowid++;
		}		
	}
}
?>
<form class="form-inline" action="#" method="">
	<?php if(mysql_num_rows($retval)>0){ ?>
	<label id="lbl" name="lbl" >Account: </label> 
	<select class="span7" id="lblAccNo" name="lblAccNo" >
		<?php echo $content; ?>
	</select>
	<!--<a href="#" class="btn btn-primary" id="ballink" name="ballink">Go</a>-->
	<p></p>
	<div class="row-fluid">
		<div class="span6">			
			Ledger balance:&nbsp;<?php echo '<span id="lblledbal" value="lblledbal"></span>'; ?>
		</div>
		<div class="span6">
			Available balance:&nbsp;<?php echo '<span id="lblAvlbal" value="lblledbal"></span>'; ?>
		</div>
	</div>
	<?php }else{echo '<h5>No Account Number is Available. Please Contact your administrator<h5>';} ?>
</form>
