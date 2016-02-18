{**
 * plugins/generic/unregisteredUsers/templates/unregisteredUsers.tpl
 *
 * Copyright (c) 2015 Language Science Press
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 *}

{strip}
	{if !$contentOnly}
		{include file="common/header.tpl"}
	{/if}
{/strip}

<p>{translate key="plugins.generic.unregisteredUsers.intro"}

{translate key="plugins.generic.unregisteredUsers.hintImportExport"}
<a href="{$url}">{translate key="plugins.generic.unregisteredUsers.linkToImportExport"}</a></p>

{url|assign:unregisteredGroupsGridUrl router=$smarty.const.ROUTE_COMPONENT component="plugins.generic.unregisteredUsers.controllers.grid.UnregisteredGroupsGridHandler" op="fetchGrid" escape=false}
{load_url_in_div id="unregisteredGroupsGridContainer" url=$unregisteredGroupsGridUrl}

{url|assign:unregisteredUsersGridUrl router=$smarty.const.ROUTE_COMPONENT component="plugins.generic.unregisteredUsers.controllers.grid.UnregisteredUsersGridHandler" op="fetchGrid" escape=false}
{load_url_in_div id="unregisteredUsersGridContainer" url=$unregisteredUsersGridUrl}

{strip}
		{include file="common/footer.tpl"}
{/strip}
