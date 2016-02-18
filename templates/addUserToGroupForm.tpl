{**
 * plugins/generic/unregisteredUsers/templates/addUserToGroupForm.tpl
 *
 * Copyright (c) 2015 Language Science Press
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 *}

<script type="text/javascript">
	$(function() {ldelim}
		// Attach the form handler.
		$('#addUserToGroup').pkpHandler('$.pkp.controllers.form.AjaxFormHandler');
	{rdelim});
</script>


{url|assign:actionUrl router=$smarty.const.ROUTE_COMPONENT component="plugins.generic.unregisteredUsers.controllers.grid.UnregisteredGroupsGridHandler" op="addUserToGroup"  escape=false}

<form class="pkp_form" id="addUserToGroup" method="post" action="{$actionUrl}">

	<input type="hidden" name="unregisteredGroupId" value="{$unregisteredGroupId|escape}" />

	{fbvFormArea id="addDate"}
		{fbvFormSection title="plugins.generic.unregisteredUsers.groupName" for="role" required="true" size=$fbvStyles.size.MEDIUM}
			{fbvElement type="select" from=$nonGivenUsers selected=$userId id="userId" translate=false}
		{/fbvFormSection}
		{fbvFormButtons}
	{/fbvFormArea}
</form>
<p><span class="formRequired">{translate key="common.requiredField"}</span></p>
