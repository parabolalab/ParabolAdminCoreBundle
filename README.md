################################################################################
#                                                                              #
#                            INSTALLING BUNDLE                                 #
#                                                                              #
################################################################################

Add to composer:
    
    {
        "minimum-stability": "stable",
        "prefer-stable": true,
        "repositories": [ 
            { "type": "composer", "url": "http://hub.parabolalab.com/" }
        ],
        "require": {
            "parabol/parabol-admin-core-bundle": "dev-master"
        }

    }

and add to kernel:

    new Parabol\AdminCoreBundle\ParabolAdminCoreBundle()



################################################################################
#                                                                              #
#                         INSTALLING ADMIN ASSETS                              #
#                                                                              #
################################################################################


Bower is required to install all admin assets, if you don't have bower do the following steps to install on OSX:

	1. Download and instal node.js from https://nodejs.org or https://nodejs.org/dist/v4.1.1/node-v4.1.1.pkg
	2. Call the following console commands:
		
		sudo npm install npm -g
		sudo npm install -g bower

On first assets installation best way to do this is call the console command:

	php app/console parabol:admin-install 

		

################################################################################
#                                                                              #
#                          DEFAULT CONFIGURATION                               #
#                                                                              #
################################################################################

    parabol_admin_core:
        dashboard:
            redirected: null            #e.g. Parabol_AdminCoreBundle_Page_list
            disabled: false             #e.g. true
        post:    
            disabled: false             #e.g. true
        text_block:    
            disabled: false             #e.g. true
        gallery:    
            disabled: false             #e.g. true   
        app_setting:    
            disabled: false             #e.g. true           


################################################################################
#                                                                              #
#                               OTHER ASSETS                                   #
#                                                                              #
################################################################################

#google dashboard
bower --config.directory=web/admin/components install bernii/gauge.js

#new version fix
bower --config.directory=web/admin/components install admin-lte
bower --config.directory=web/admin/components install ionicons
bower --config.directory=web/admin/components install select2

#bower --config.directory=web/admin/components install svg-loaders
#bower --config.directory=web/admin/components install bootstrap-hover-dropdown

#video player
#bower  --config.directory=web/admin/components install mediaelement

#required
bower --config.directory=web/admin/components install eonasdan-bootstrap-datetimepicker
bower --config.directory=web/admin/components install blueimp-file-upload
bower --config.directory=web/admin/components install bootstrap-colorpickersliders
bower --config.directory=web/admin/components install jquery-sortable


