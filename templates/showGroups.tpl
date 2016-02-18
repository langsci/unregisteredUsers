{**
 * plugins/generic/unregisteredUsers/templates/showGroups.tpl
 *
 * Copyright (c) 2015 Language Science Press
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 *}

<link rel="stylesheet" href="{$baseUrl}/plugins/generic/unregisteredUsers/css/unregisteredUsers.css" type="text/css" />

<div class="unregisteredUsers">

{if $groups}

	 <table>
	  <tr>
		<th>{translate key="plugins.generic.unregisteredUsers.groupName"}</th>
	  </tr>
		{foreach from=$groups item=group}
		  <tr>
			<td>{$group}</td>
		  </tr>
		{/foreach}
	</table> 

{else}

	<p>{$userName} {translate key="plugins.generic.unregisteredUsers.inNoGroup"}</p>

{/if}	

</div>




