<?php /* Smarty version 2.6.14, created on 2013-11-19 16:45:42
         compiled from pay-for-coins.tpl */ ?>
<!-- <?php echo 'pay-for-coins.tpl'; ?>
 -->
<h1 class="title"><?php echo $this->_config[0]['vars']['I_WANT_PAY_COINS']; ?>
</h1>
<div id="container-content-back">
	<div class="container-box-content-payforcoins"><?php echo $this->_config[0]['vars']['Coin_Text_Line1']; ?>
<br /><br /><?php echo $this->_config[0]['vars']['Coin_Text_Line2']; ?>
</div>

	<div class="container-box-payforcoins">
	<?php if ($_SESSION['payment_admin']): ?>
	<span style="display:block; width:371px; height:176px; float:left;  text-decoration:none; margin-left:50px; margin-bottom:20px;">
		<div style="background:url(images/pay-for-coin-btn-bg-p2.png) no-repeat; width:371px; height:176px;">
	        <div class="container-payforcoin-font">
				<form action="" method="get">
				<div class="fontLeft">
				<input type="hidden" name="action" value="payment"/>
				<input type="hidden" name="package_id" value="0"/>
				<input type="text" name="price" value="10" style="width: 20px"/> <?php echo $this->_tpl_vars['rcurrency']['value']; ?>

				</div>
			   
				<div class="fontright">
				<input type="text" name="coins" value="<?php echo @COIN_EMAIL; ?>
" style="width: 20px"/> Coins
				<input type="submit" value="Pay"/>
				</div>
				</form>
			</div>  
		</div>
	</span>
	<?php endif; ?>
	<?php if ($this->_tpl_vars['trialPackage']): ?>
	<a href="?action=payment&package_id=<?php echo $this->_tpl_vars['trialPackage']['id']; ?>
" style="display:block; width:371px; height:176px; float:left;  text-decoration:none; margin-left:50px; margin-bottom:20px;">
	<div style="background:url(images/pay-for-coin-btn-bg-p<?php echo $this->_tpl_vars['trialPackage']['id']; ?>
.png) no-repeat; width:371px; height:176px;">
		
        <div class="container-payforcoin-font">
			<div class="fontLeft">
			<?php echo $this->_tpl_vars['trialPackage']['currency_price']; ?>
 <?php echo $this->_tpl_vars['rcurrency']['value']; ?>

			</div>
		   
			<div class="fontright">
			<?php echo $this->_tpl_vars['trialPackage']['coin']; ?>
 Coins
			</div>
		</div>  
	</div>
	</a>
	<?php endif; ?>
	<?php $_from = $this->_tpl_vars['coinpackage']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['coinpackages'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['coinpackages']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['package']):
        $this->_foreach['coinpackages']['iteration']++;
?>
	<?php if ($this->_tpl_vars['package']['paypal']): ?>
	<a href="#" style="display:block; width:371px; height:176px; float:left;  text-decoration:none; margin-left:50px; margin-bottom:20px;" onclick="payWithPaypal(<?php echo $this->_tpl_vars['package']['id']; ?>
); return false;">
	<div style="background:url(images/pay-for-coin-btn-bg-p<?php echo $this->_tpl_vars['package']['id']; ?>
.png) no-repeat; width:371px; height:176px;">
        <div class="container-payforcoin-font">
			<div class="fontLeft">
			&nbsp;&nbsp;<?php echo $this->_tpl_vars['package']['currency_price']; ?>
 <?php echo $this->_tpl_vars['rcurrency']['value']; ?>

			</div>
		   
			<div class="fontright">
			&nbsp;&nbsp;<?php echo $this->_tpl_vars['package']['coin']; ?>
 Coins
			</div>
		</div> 
	</div>
	</a>
	<?php else: ?>
	<a href="?action=payment&package_id=<?php echo $this->_tpl_vars['package']['id']; ?>
" style="display:block; width:371px; height:176px; float:left;  text-decoration:none; margin-left:50px; margin-bottom:20px;">
	<div style="background:url(images/pay-for-coin-btn-bg-p<?php echo $this->_tpl_vars['package']['id']; ?>
.png) no-repeat; width:371px; height:176px;">
        <div class="container-payforcoin-font">
			<div class="fontLeft">
			&nbsp;&nbsp;<?php echo $this->_tpl_vars['package']['currency_price']; ?>
 <?php echo $this->_tpl_vars['rcurrency']['value']; ?>

			</div>
		   
			<div class="fontright">
			&nbsp;&nbsp;<?php echo $this->_tpl_vars['package']['coin']; ?>
 Coins
			</div>
		</div> 
	</div>
	</a>
	<?php endif; ?>
	<?php endforeach; endif; unset($_from); ?>

	</div>
</div>

<script>
<?php echo '
jQuery.ajaxSetup({cache:false})

function payWithPaypal(id)
{
	jQuery.ajax({
			type: "POST",
			'; ?>
url: "?action=payment&id="+id,<?php echo '
			data: { paymentProvider: \'Paypal\'},
			success:(function(result) {
				if(result)
				{
					window.location=result;
				}
				else
				{
					alert("Failed");
				}
			})
		});
}
'; ?>

</script>