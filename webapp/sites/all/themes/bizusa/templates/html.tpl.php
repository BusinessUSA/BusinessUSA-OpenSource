<!DOCTYPE html>
<html lang="<?php print $language->language; ?>" dir="<?php print $language->dir; ?>" class="<?php print $classes; ?>" <?php print $rdf_namespaces; ?>>

    <head profile="<?php print $grddl_profile; ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <?php print $head; ?>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>
            <?php print $head_title; ?>
        </title>
        <?php print $styles; ?>

        <!-- SmartMenus jQuery Bootstrap Addon CSS -->
        <link href="/sites/all/themes/bizusa/theme/lib/smartmenus-0.9.6/addons/bootstrap/jquery.smartmenus.bootstrap.css" rel="stylesheet">

        <!-- Mobile menu -->
        <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.0.1/css/font-awesome.min.css">
        <link rel="stylesheet" href="/sites/all/themes/bizusa/theme/lib/MultiLevelPushMenu_v2.1.4/jquery.multilevelpushmenu.css">

        <!-- bxSlider CSS -->
        <link href="/sites/all/themes/bizusa/theme/lib/jquery.bxslider/jquery.bxslider.css" rel="stylesheet">

        <?php print $scripts; ?>

        <!-- SmartMenus jQuery plugin -->
        <script type="text/javascript" src="/sites/all/themes/bizusa/theme/lib/smartmenus-0.9.6/jquery.smartmenus.js"></script>

        <!-- SmartMenus jQuery Bootstrap Addon -->
        <script type="text/javascript" src="/sites/all/themes/bizusa/theme/lib/smartmenus-0.9.6/addons/bootstrap/jquery.smartmenus.bootstrap.js"></script>

        <!-- Mobile menu -->
        <script type="text/javascript" src="/sites/all/themes/bizusa/theme/lib/MultiLevelPushMenu_v2.1.4/jquery.multilevelpushmenu.js"></script>
        <script type="text/javascript" src="/sites/all/themes/bizusa/theme/lib/MultiLevelPushMenu_v2.1.4/bootstrap/bootstrap_integration.js"></script>

        <!-- Internet Explorer 10 in Windows 8 and Windows Phone 8 (http://getbootstrap.com/getting-started/#support-ie10-width) -->
        <script type="text/javascript" src="/sites/all/themes/bizusa/theme/js/ie10-viewport-bug-workaround.js"></script>

    </head>

    <body class="<?php print $classes; ?>" <?php print $attributes;?>>
        <noscript class="noscript-message" style="background-color: white; padding: 15px; margin: 15px; border: 1px solid gray;">
            JavaScript is currently disabled by the browser. Please enable JavaScript to access full functionality. 
            JavaScript is required on this web application. 
        </noscript>
        <a href="#contentArea" id="skipToContent" >Skip to Content</a>

        <?php if ( !empty($GLOBALS['overrideBodyMarkup']) ):  ?>
            <?php print $GLOBALS['overrideBodyMarkup']; ?>
        <?php else: ?>
        
            <div id="siteNavigations" rendersource="<?php print basename(__FILE__); ?>">

                  <div id="mainNavigation" class="navbar navbar-default navbar-fixed-top navbar-inverse" role="navigation">
                    <div class="container">
                      <div class="navbar-collapse collapse">
                      
                        <ul class="nav navbar-nav navbar-right">
                          <li><a href="/">Home</a></li>
                          <li><a href="/about-us">About</a></li>
                          <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Resources</a>
                            <ul class="dropdown-menu" role="menu">
                              <li><a href="/example-wizard-1">Wizard 1</a></li>
                              <li><a href="/example-wizard-2">Wizard 2</a></li>
                              <li><a href="/example-wizard-3">Wizard 3</a></li>
                              <li><a href="/dev/docs/swimlane-page-1">Swimlane Page 1</a></li>
                              <li><a href="/dev/docs/swimlane-page-2">Swimlane Page 2</a></li>
                            </ul>
                          </li>
                          <li><a href="/events-search">Events</a></li>
                          <li class="dropdown dropdown-submenu">
                              <a href="/request-appointment-and-closest-resource-centers" class="dropdown-toggle" data-toggle="">Regional Resources</a>
                          </li>

                          <li class="translate dropdown">                            
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"></a>
                            <ul class="dropdown-menu mega-menu" style="display:none; position:absolute;">
                              <li>   
                                <div class="container" style="width:200px;max-width:100%;">
                                  
                                </div>
                              </li>
                            </ul>
                          </li>
                          <li class="twitter text-hide"><a href="javascript: alert('edit line 84 of html.tpl.php to put in your associated Twitter account. Or delete this line to remove this menu item.'); void(0);">Twitter</a></li>
                          <li class="linkedin text-hide"><a href="javascript: alert('edit line 85 of html.tpl.php to put in your associated Linked-In account. Or delete this line to remove this menu item.'); void(0);">LinkedIn</a></li>
                        </ul>
                      </div>
                    </div>
                  </div>

                  <div id="scrollNavigation" class="navbar navbar-default navbar-fixed-top navbar-inverse" role="navigation">
                    <div class="container">
                      
                      <div class="navbar-header">
                        <a class="navbar-brand text-hide" href="/">Business USA</a>
                      </div>

                      <div class="navbar-collapse collapse">
                      
                        <ul class="nav navbar-nav wizards">
                          <li class="dropdown dropdown-submenu start-a-business-menu-icon">
                            <a href="#" class="dropdown-toggle text-hide" data-toggle="dropdown">Wizard 1</a>
                            <ul class="dropdown-menu" role="menu">
                              <li><a href="/example-wizard-1">Wizard 1</a></li>
                            </ul>
                          </li>
                          <li class="dropdown dropdown-submenu access-financing-menu-icon">
                            <a href="#" class="dropdown-toggle text-hide" data-toggle="dropdown">Wizard 2</a>
                            <ul class="dropdown-menu" role="menu">
                              <li><a href="/example-wizard-2">Wizard 2</a></li>
                            </ul>
                          </li>
                          <li class="dropdown dropdown-submenu explore-exporting-menu-icon">
                            <a href="#" class="dropdown-toggle text-hide" data-toggle="dropdown">Swimlane Page 1</a>
                            <ul class="dropdown-menu" role="menu">
                              <li><a href="/dev/docs/swimlane-page-1">Swimlane Page 1</a></li>
                            </ul>
                          </li>
                          <li class="dropdown dropdown-submenu find-opportunities-menu-icon">
                            <a href="#" class="dropdown-toggle text-hide" data-toggle="dropdown">Swimlane Page 2</a>
                            <ul class="dropdown-menu" role="menu">
                              <li><a href="/dev/docs/swimlane-page-2">Swimlane Page 2</a></li>
                            </ul>
                          </li>
                        </ul>


                        <ul class="nav navbar-nav navbar-right">
                          <li class="dropdown">
                              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-align-justify"></span> Menu</a>
                              <ul class="dropdown-menu" role="menu">

                                <li><a href="/">Home</a></li>
                                <li><a href="/about-us">About Us</a></li>
                                <li><a href="/example-wizard-1">Wizard 1</a></li>
                                <li><a href="/example-wizard-2">Wizard 2</a></li>
                                <li><a href="/dev/docs/swimlane-page-1">Swimlane Page 1</a></li>
                                <li><a href="/dev/docs/swimlane-page-2">Swimlane Page 2</a></li>
                                <li><a href="/events-search">Events</a></li>
                                <li><a href="/events-search">Regional Resources</a></li>
                              </ul>
                          </li>
                          <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-search"></span> Search</a>
                            <ul class="dropdown-menu mega-menu" style="display:none; position:absolute;">  
                              <li>
                                <div class="container" style="width:250px;max-width:100%;">
                                  <div class="row">
                                    <div class="col-sm-12" style="padding-top:6px; padding-bottom:6px;">
                                      <div class="input-group">
                                        <input type="text" class="form-control input-sm" placeholder="Start Searching...">
                                        <span class="input-group-btn">
                                          <button type="submit" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-search"></span></button>
                                        </span>
                                      </div>
                                    </div>
                                  </div>
                                </div>              
                              </li>
                            </ul>
                          </li>
                        </ul>

                      </div>
                    </div>
                  </div>

            </div>

            <div id="menu" rendersource="<?php print basename(__FILE__); ?>">
                <nav>
                  <h2><i class="fa fa-reorder"></i>Business USA</h2>
                    <ul>
                        <li><a href="/">Home</a></li>
                        <li><a href="/about-us">About</a></li>
                        <li><a href="/example-wizard-1">Wizard 1</a></li>
                        <li><a href="/example-wizard-2">Wizard 2</a></li>
                        <li><a href="/dev/docs/swimlane-page-1">Swimlane Page 1</a></li>
                        <li><a href="/dev/docs/swimlane-page-2">Swimlane Page 2</a></li>
                        <li><a href="/tour">Take a Tour</a></li>
                        <li><a href="/events-search">Events</a></li>
                        <li><a href="/events-search">Regional Resources</a></li>
                        <li><a href="javascript: alert('edit line 183 of html.tpl.php to put in your associated Twitter account. Or delete this line to remove this menu item.'); void(0);">Twitter</a></li>
                        <li><a href="javascript: alert('edit line 184 of html.tpl.php to put in your associated Linked-In account. Or delete this line to remove this menu item.'); void(0);">LinkedIn</a></li>
                    </ul>
                </nav>
            </div>

            <?php print $page_top; ?>
            <?php print $page; ?>
            <?php print $page_bottom; ?>
        
            <div class="footer navbar navbar-default">
              <div class="container-fluid">
                <p class="navbar-text" style="text-transform: initial;">[ Insert site signature here ] (from within sites/all/themes/bizusa/templateshtml.tpl.php)</p>
              </div>
            </div>
        
        <?php endif; ?>
        
    </body>

</html>
