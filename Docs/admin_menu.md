Adding menu item to admin menu by creating service with parabol.admin_menu tag: 

	parabol.admin_menu.setting:
        class: Parabol\AdminCoreBundle\Menu\MenuItem
        arguments: 
         - "Setting" #label
         - "parabol_admin_core_setting" #route name
         - null #route parameters
         - "fa fa-cogs" #icon class
         - "last" #position in menu
         - null #parent service name
         - "ROLE_ADMIN" #role name for which item will be displaying
        tags:
            -  { name: parabol.admin_menu }


If you want disable diplaying some defined items put to config in parameters sections parameters admin_menu_service_name.disabled: true, eg:
	
	parameters: 
		parabol.admin_menu.setting.disabled: true
