<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <?php include_http_metas() ?>
        <?php include_metas() ?>
        <?php include_title() ?>
        <link rel="shortcut icon" href="/favicon.ico" />
        <?php include_stylesheets() ?>
        <script type="text/javascript">
            var login_url = '<?php echo url_for('@login') ?>';
        </script>
        <?php include_javascripts() ?>
        <?php if(!$sf_user->hasAttribute('browser_version_checked')){?>
            <?php $sf_user->setAttribute('browser_version_checked', true);?>
            <script type="text/javascript">
                $(document).ready(function(){
                    checkBrowserVersion();
                });

                function checkBrowserVersion()
                {
                    var nAgt = navigator.userAgent;
                    var fullVersion  = ''+parseFloat(navigator.appVersion);
                    var deprecated = false;

                    // In Chrome, the true version is after "Chrome"
                    if ((verOffset=nAgt.indexOf("Chrome"))!=-1) {
                        fullVersion = nAgt.substring(verOffset+7);
                        majorVersion = getMajorBrowserVersion(fullVersion);
                        if(majorVersion < 18)
                        {
                            deprecated = true;
                        }
                    }
                    // In Firefox, the true version is after "Firefox"
                    else if ((verOffset=nAgt.indexOf("Firefox"))!=-1) {
                        browserName = "Firefox";
                        fullVersion = nAgt.substring(verOffset+8);
                        majorVersion = getMajorBrowserVersion(fullVersion);
                        if(majorVersion < 11)
                        {
                            deprecated = true;
                        }
                    }

                    // Explorer
                    else if (navigator.appName == 'Microsoft Internet Explorer')
                    {
                        var ua = navigator.userAgent;
                        var re  = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
                        if (re.exec(ua) != null)
                        {
                            browserName = navigator.appName;
                            fullVersion = parseFloat( RegExp.$1 );
                            if(fullVersion < 8)
                            {
                                deprecated = true;
                            }

                        }
                    }

                    if(deprecated)
                    {
                        alert( "<?php echo __('Version of your browser is deprecated and not supported. Please upgrade your browser or switch to other browser.') ?>");
                    }
                }

                function getMajorBrowserVersion(fullVersion)
                {
                    // trim the fullVersion string at semicolon/space if present
                    if ((ix=fullVersion.indexOf(";"))!=-1)
                        fullVersion=fullVersion.substring(0,ix);
                    if ((ix=fullVersion.indexOf(" "))!=-1)
                        fullVersion=fullVersion.substring(0,ix);

                    majorVersion = parseInt(''+fullVersion,10);
                    if (isNaN(majorVersion)) {
                        fullVersion  = ''+parseFloat(navigator.appVersion);
                        majorVersion = parseInt(navigator.appVersion,10);
                    }
                    return majorVersion;
                }

            </script>
        <?php }?>
    </head>
    <body class="<?php if (!has_slot('submenu'))
            echo 'without-submenu' ?>">
              <?php include_component('default', 'menu'); ?>
        <div id="main-container" class="container">
            <?php include_component('default', 'flashes') ?>

            <?php echo $sf_content ?>

        </div>
        <div class="modal modal-center hide fade <?php if (!has_slot('submenu'))
            echo 'without-submenu' ?>" id="modal_dialog"></div>
    </body>
</html>
