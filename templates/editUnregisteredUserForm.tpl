{**
 * plugins/generic/unregisteredUsers/templates/editUnregisteredUserForm.tpl
 *
 * Copyright (c) 2015 Language Science Press
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 *}

<script type="text/javascript">
	$(function() {ldelim}
		// Attach the form handler.
		$('#unregisteredUserForm').pkpHandler('$.pkp.controllers.form.AjaxFormHandler');
	{rdelim});
</script>

{url|assign:actionUrl router=$smarty.const.ROUTE_COMPONENT component="plugins.generic.unregisteredUsers.controllers.grid.UnregisteredUsersGridHandler" op="updateUnregisteredUser"  escape=false}

<form class="pkp_form" id="unregisteredUserForm" method="post" action="{$actionUrl}">

	{if $unregisteredUserId}
		<input type="hidden" name="unregisteredUserId" value="{$unregisteredUserId|escape}" />
	{/if}

	{fbvFormArea id="unregisteredUserFormArea" class="border"}

		{fbvFormSection}
			{fbvElement type="text" label="plugins.generic.unregisteredUsers.firstName" id="firstName" required="true" value=$firstName maxlength="50" inline=true multilingual=false size=$fbvStyles.size.MEDIUM}
		{/fbvFormSection}

		{fbvFormSection}
			{fbvElement type="text" label="plugins.generic.unregisteredUsers.lastName" id="lastName" required="true" value=$lastName maxlength="50" inline=true multilingual=false size=$fbvStyles.size.MEDIUM}
		{/fbvFormSection}

		{fbvFormSection}
			{fbvElement type="text" label="plugins.generic.unregisteredUsers.email" id="email" value=$email maxlength="50" inline=true multilingual=false size=$fbvStyles.size.MEDIUM}
		{/fbvFormSection}

		{fbvFormSection}
			{fbvElement type="text" label="plugins.generic.unregisteredUsers.ompUsername" id="ompUsername" value=$ompUsername maxlength="50" inline=true multilingual=false size=$fbvStyles.size.MEDIUM}
		{/fbvFormSection}

		{fbvFormSection}
			{fbvElement type="textarea" label="plugins.generic.unregisteredUsers.notes" id="notes" value=$notes inline=true multilingual=false size=$fbvStyles.size.MEDIUM}
		{/fbvFormSection}

	{/fbvFormArea}

	{fbvFormSection class="formButtons"}
		{fbvElement type="submit" class="submitFormButton" id=$buttonId label="common.save"}
	{/fbvFormSection}

</form>
<p><span class="formRequired">{translate key="common.requiredField"}</span></p>
