{**
 * plugins/generic/unregisteredUsers/templates/editUnregisteredGroupForm.tpl
 *
 * Copyright (c) 2015 Language Science Press
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 *}

<script type="text/javascript">
	$(function() {ldelim}
		// Attach the form handler.
		$('#unregisteredGroupForm').pkpHandler('$.pkp.controllers.form.AjaxFormHandler');
	{rdelim});
</script>

{url|assign:actionUrl router=$smarty.const.ROUTE_COMPONENT component="plugins.generic.unregisteredUsers.controllers.grid.UnregisteredGroupsGridHandler" op="updateUnregisteredGroup"  escape=false}

<form class="pkp_form" id="unregisteredGroupForm" method="post" action="{$actionUrl}">

	{if $unregisteredGroupId}
		<input type="hidden" name="unregisteredGroupId" value="{$unregisteredGroupId|escape}" />
	{/if}

	{fbvFormArea id="unregisteredGroupFormArea" class="border"}

		{if not $uniqueName}<span>This name is already being used.</span>{/if}
		{fbvFormSection}
			{fbvElement type="text" label="plugins.generic.unregisteredUsers.groupName" id="name" required="true" value=$name maxlength="50" inline=true multilingual=false size=$fbvStyles.size.MEDIUM}
		{/fbvFormSection}

		{fbvFormSection}
			{fbvElement type="textarea" label="plugins.generic.unregisteredUsers.notes" id="notes" value=$notes  inline=true multilingual=false size=$fbvStyles.size.MEDIUM}
		{/fbvFormSection}

	{/fbvFormArea}

	{fbvFormSection class="formButtons"}
		{fbvElement type="submit" class="submitFormButton" id="submitFormButtonUnregisteredGroupForm" label="common.save"}
	{/fbvFormSection}

</form>
<p><span class="formRequired">{translate key="common.requiredField"}</span></p>

