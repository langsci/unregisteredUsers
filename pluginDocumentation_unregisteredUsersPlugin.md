Key data
============

- name of the plugin: Unregistered Users Plugin
- author: Carola Fanselow
- current version: 1.0.0.0
- tested on OMP version: 1.1.1-1, 1.2
- github link: https://github.com/langsci/unregisteredUsers.git
- community plugin: yes, 1.0.0.0
- date: 18.2.2016

Description
============

Plugin for managing users who must not be registered with the press. Groups may be created and users may be assigned to one or more groups. Fields for groups are 1. Group name, 2. Notes on the group. Fields for users are 1. First name, 2. Second name, 3. Email, 4. OMP username, 5. Notes on the user. 

 
Implementation
================

Hooks
-----
- used hooks: 2

		LoadHandler
		LoadComponentHandler

New pages
------
- new pages: 1

		[press]/unregisteredUsers/index

Templates
---------
- templates that substitute other templates: 0
- templates that are modified with template hooks: 0
- new/additional templates: 7

		addUserToGroupForm.tpl
		deleteUserFromGroupForm.tpl
		editUnregisteredGroupForm.tpl
		editUnregisteredUserForm.tpl
		showGroups.tpl
		showUsers.tpl
		unregisteredUsers.tpl

Database access, server access
-----------------------------
- reading access to OMP tables: 0
- writing access to OMP tables: 0
- new tables: 3

		langsci_unregistered_users
		langsci_unregistered_groups
		langsci_unregistered_users_groups

- nonrecurring server access: yes

	creating database table during installation (file: schema.xml)

- recurring server access: no
 
Classes, plugins, external software
-----------------------
- OMP classes used (php): 8
	
	GenericPlugin
	Handler
	DAO
	DataObject
	GridHandler
	GridRow
	GridCellProvider
	Form

- OMP classes used (js, jqeury, ajax): 1

	AjaxFormHandler

- necessary plugins: 0
- optional plugins: 1

	Unregistered Users Import Export Plugin: Import and export of groups and users via csv-file (see file "example.csv")

- use of external software: no
- file upload: no
 
Metrics
--------
- number of files 20
- lines of code: 2268

Settings
--------
- settings: no

Plugin category
----------
- plugin category: generic

Other
=============
- does using the plugin require special (background)-knowledge?: no
- access restrictions: access restricted to admins and managers



