<div id="toolBar">
    <div class="logo-container">
        <a target="window" href="http://business.usa.gov/">
            <img id="toolBarLogo" src="/sites/all/themes/bizusa/images/external-top-logo.png">
        </a>
    </div>
    <ul class="clearfix" id="toolbarButtons">
        <li>
            <a target="_blank" href="http://help.businessusa.gov/ics/support/KBSplash.asp" class="browseknowledgebase background-color-change">
                <img alt="" src="/sites/all/themes/bizusa/images/graduate-hat.png" />
                <div class="bar-text">
                    Browse<br/>Knowledgebase
                </div>
            </a>
        </li>
        <li>
            <a target="_blank" href="http://help.business.usa.gov/ics/support/ticketnewwizard.asp?style=classic&feedback=true" class="submitfeedback background-color-change">
                <img alt="" src="/sites/all/themes/bizusa/images/speech-bubble-icon.png" />
                <div class="bar-text">
                    Submit<br/>Feedback
                </div>
            </a>
        </li>
        <li>
            <a target="_blank" href="http://help.businessusa.gov/ics/support/ticketnewwizard.asp?style=classic" class="askaquestion background-color-change">
                <img alt="" src="/sites/all/themes/bizusa/images/question-mark-icon.png" />
                <div class="bar-text">
                    Ask a<br/>Question
                </div>
            </a>
        </li>
        <li>
            <!-- a id="closeFrame" href="javascript: // Call 1-844-BIZ-USA2" target="_parent" class="fedinfo" -->
            <a id="closeFrame" href="javascript: // Call 1-800-FED-INFO" target="_parent" class="fedinfo">
                <div class="bar-text">
                    <!-- 1-844-BIZ-USA2 -->
                    1-800-FED-INFO 
                </div>
            </a>
        </li>
        <li>
            <!--a href="javascript: history.back();" class="backbtn">
                <img id="toolBarLogo" src="/sites/all/themes/bizusa/images/external-back.png">
            </a-->
        </li>
        <li>
            <a href="javascript: closeTopFrame(); void(0);" target="_parent" class="closebtn">
                <img id="toolBarLogo" src="/sites/all/themes/bizusa/images/external-close.png">
            </a>
        </li>
    </ul>
</div>

<script>

    jQuery(document).ready( function () {
        jQuery('.closebtn').bind('click', function () {
            var confMsg = 'Closing this frame will cause you to leave the current page that you are viewing. Continue?';
            if ( confirm(confMsg) ) { 
                window.parent.window.parent.window.location = '<?php print $_GET['closeTo']; ?>'; 
            }
        });
    });
    
</script>

<!-- Google Analytic Implementation -->
<script type="text/javascript" src="http://business.usa.gov/sites/all/themes/bususa/js/federated-analytics.js"></script>
<script>
      var _gaq = _gaq || [];_gaq.push(["_setAccount", "UA-19362636-19"]);_gaq.push(["_trackPageview"]);(function() {var ga = document.createElement("script");ga.type = "text/javascript";ga.async = true;ga.src = ("https:" == document.location.protocol ? "https://ssl" : "http://www") + ".google-analytics.com/ga.js";var s = document.getElementsByTagName("script")[0];s.parentNode.insertBefore(ga, s);})();
</script>
<script>
    var _gaq = _gaq || [];_gaq.push(["_setAccount", "UA-17367410-37"]);_gaq.push(["_trackPageview"]);(function() {var ga = document.createElement("script");ga.type = "text/javascript";ga.async = true;ga.src = ("https:" == document.location.protocol ? "https://ssl" : "http://www") + ".google-analytics.com/ga.js";var s = document.getElementsByTagName("script")[0];s.parentNode.insertBefore(ga, s);})();
</script>
