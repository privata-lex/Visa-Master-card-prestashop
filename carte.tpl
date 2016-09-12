<p class="payment_module">
	<a href="javascript:$('#carte_form').submit();" title="{l s='Payer avec Visa Master Card (Tunsie)' mod='carte'}">
		<img src="{$module_template_dir}carte.gif" alt="{l s='Payer avec Visa Master Card (Tunsie)' mod='carte'}" width="60" height="50" />
		{l s='Payer avec Visa Master Card (Tunsie)' mod='carte'}
	</a>
</p>


<form action="modules/carte/payement.php" method="post" id="carte_form" class="hidden">
	<input type="hidden" name="upload" value="1" />
	<input type="hidden" name="first_name" value="{$address->firstname}" />
	<input type="hidden" name="last_name" value="{$address->lastname}" />
	<input type="hidden" name="address1" value="{$address->address1}" />
	{if !empty($address->address2)}<input type="hidden" name="address2" value="{$address->address2}" />{/if}
	<input type="hidden" name="city" value="{$address->city}" />
	<input type="hidden" name="zip" value="{$address->postcode}" />
	<input type="hidden" name="country" value="{$country->iso_code}" />
	<input type="hidden" name="total_price" value="{$total_price}" />
	<input type="hidden" name="email" value="{$customer->email}" />

	<input type="hidden" name="charset" value="utf-8" />
	<input type="hidden" name="currency_code" value="{$currency->iso_code}" />
	<input type="hidden" name="payer_id" value="{$customer->id}" />
	<input type="hidden" name="payer_email" value="{$customer->email}" />
	<input type="hidden" name="custom" value="{$cart->id}" />
	<input type="hidden" name="return" value="{$goBackUrl}" />
	<input type="hidden" name="notify_url" value="{$returnUrl}" />
    <input type="hidden" name="rm" value="1" />
	<input type="hidden" name="bn" value="PRESTASHOP_WPS" />
</form>