{**
 * plugins/generic/unregisteredUsers/templates/showUsers.tpl
 *
 * Copyright (c) 2015 Language Science Press
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 *}

<link rel="stylesheet" href="{$baseUrl}/plugins/generic/unregisteredUsers/css/unregisteredUsers.css" type="text/css" />

<div class="unregisteredUsers">

{if $users}

 <table>
  <tr>
    <th>First name</th>
    <th>Last name</th>
    <th>Email</th>
    <th>OMP username</th>
  </tr>
	{foreach from=$users item=user}
	  <tr>
		<td>{$user.firstName}</td>
		<td>{$user.lastName}</td>
		<td>{$user.email}</td>
		<td>{$user.ompUsername}</td>
	  </tr>
	{/foreach}
</table> 

{else}
	<p>{translate key="plugins.generic.unregisteredUsers.noUsersInGroup"}</p>
{/if}

</div>



