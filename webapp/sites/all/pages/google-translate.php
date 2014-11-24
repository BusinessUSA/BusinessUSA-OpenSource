<div class="sitewide-googletranslate-mastercontainer" style="display: none;" title="Google Translator">
    <div class="sitewide-googletranslate-leftside">
        <div class="sitewide-googletranslate-googleicon-container">
            <img src="/sites/all/themes/bizusa/images/google-icon-100x100.png" alt="Google Logo" />
        </div>
        <div class="sitewide-googletranslate-label" style="color: rgb(121, 121, 121) !important;">
            Translate
        </div>
    </div>
    <div class="sitewide-googletranslate-rightside">
        <!-- The following div shall state what language the page is translated in. This div's content(s) is updated by a function in share_links.php, search for EFECVHR in that file -->
        <!-- The notranslate class applied to this div tells the GoogleTrasnlate API that no contents within the div should be translated to another language -->
        <input type="button" class="sitewide-googletranslate-busa-translate-language-indicator notranslate" onclick="jQuery('.sitewide-googletranslate-optionsmastercontainer').toggle(); if ( jQuery('.sitewide-googletranslate-optioncontainer a:visible').eq(0).length > 0 ) { jQuery('.sitewide-googletranslate-optioncontainer a:visible').eq(0).focus(); } " />
        <div>
            <!-- The purpose of this div is to contain a selectable (tabable) input in which would foward focus from here, over the
            GoogleTranslate-injected IFrame, and onto the next selectable element (when a user is tabbing through the DOM) -->
            <input type="button" id="googleTranslateApiPreContainer" class="hidden-offscreen"/>
            <script>
                jQuery('#googleTranslateApiPreContainer').bind('focus', function () { // When #googleTranslateApiPreContainer gets focus (tabbed to)...
                    consoleLog('The div#googleTranslateApiPreContainer has obtained focus, skipping focus over the GoogleTranslate IFrame...');
                    if ( jQuery('.sitewide-googletranslate-optionsmastercontainer:visible').length > 0 ) {
                        consoleLog('into the language-selection area (Coder Bookmark: CB-TA1TGNZ-BC)');
                        jQuery('.sitewide-googletranslate-optionsmastercontainer a').eq(0).focus();
                    } else {
                        consoleLog('onto the Twitter Icon on the top-roght of the page (Coder Bookmark: LIWEFT8)');
                        jQuery('#twitter a').focus();
                    }
                });
            </script>
        </div>
        <div class="googletranslateapi-container">
            <!-- The following div and scripts is stored in share_links.php
                START: Google Translate API - this HTML is given to us by Google
                NOTE: We cannot really controle what elements on the page are created by Google, and using jQuery to alter them is ugly, so what
                I have implemented so far (2013/08/22) is CSS that sets the google-translate elements to be 100% transparent, and force their size to
                be a certain width/height. Our elements that look like a drop-down is really layered under the GoogleTranslate-elements, but since they
                are 100% transparent, the user does not see them, but can click on them.
            -->
            <div id="google_translate_element"></div>
            <script type="text/javascript">
                function googleTranslateElementInit() {
                    new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE, gaTrack: true, gaId: 'UA-19362636-19'}, 'google_translate_element');
                }
            </script>
            <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
            <!-- END: Google Translate API - this HTML is given to us by Google -->
        </div>
        <div>
            <!-- The purpose of this div is to contain a selectable (tabable) input in which would foward focus from here, over the
            GoogleTranslate-injected IFrame, and onto the next selectable element (when a user is shift+tabbing through the DOM) -->
            <input type="button" id="googleTranslateApiPostContainer" class="hidden-offscreen"/>
            <script>
                jQuery('#googleTranslateApiPostContainer').bind('focus', function () { // When #googleTranslateApiPreContainer gets focus (tabbed to)...
                    consoleLog('The div#googleTranslateApiPreContainer has obtained focus, skipping focus over the GoogleTranslate IFrame and onto the previous element... (Coder Bookmark: 8MV3O4V)');
                    var x = 0;
                    var allShareLinksSelectables = jQuery('#shareLinks').find('a, input');
                    for ( x = allShareLinksSelectables.length ; x > 0 ; x-- ) { // loop through all the focus(able) elements in #shareLinks
                        if ( jQuery('#googleTranslateApiPreContainer').get(0) == jQuery(allShareLinksSelectables[x]).get(0) ) {
                            break; // untill we have found #googletranslateapi-postcontainer
                        }
                    }
                    x--; // then look at the PREVIOUS focusable element
                    consoleLog('The target element to focus is:');
                    consoleLog(allShareLinksSelectables[x]);
                    allShareLinksSelectables[x].focus(); // and set focus on that element.
                });
            </script>
        </div>
    </div>
</div>

<!-- The following div contains a list of all the languages that this page may be translated into (this is the appearing drop-down for the translation options) -->
<!-- The notranslate class applied to this div tells the GoogleTrasnlate API that no contents within the div should be translated to another language -->
<div class="sitewide-googletranslate-optionsmastercontainer notranslate" style="display: none;">
<!-- The following divs are stored in share_links-languages.php -->
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'English\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); jQuery('.goog-te-banner-frame').contents().find('.goog-te-menu-value span').eq(0).text('English'); ">English</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Afrikaans\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Afrikaans</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Albanian\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Albanian</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Arabic\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Arabic</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Armenian\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Armenian</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Azerbaijani\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Azerbaijani</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Basque\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Basque</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Belarusian\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Belarusian</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Bengali\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Bengali</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Bosnian\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Bosnian</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Bulgarian\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Bulgarian</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Catalan\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Catalan</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Cebuano\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Cebuano</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Chinese (Simplified)\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Chinese (Simplified)</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Chinese (Traditional)\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Chinese (Traditional)</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Croatian\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Croatian</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Czech\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Czech</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Danish\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Danish</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Dutch\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Dutch</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'English\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); jQuery('.goog-te-banner-frame').contents().find('.goog-te-menu-value span').eq(0).text('English') ">English</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Esperanto\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Esperanto</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Estonian\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Estonian</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Filipino\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Filipino</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Finnish\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Finnish</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'French\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">French</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Galician\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Galician</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Georgian\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Georgian</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'German\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">German</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Greek\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Greek</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Gujarati\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Gujarati</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Haitian Creole\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Haitian Creole</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Hebrew\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Hebrew</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Hindi\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Hindi</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Hmong\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Hmong</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Hungarian\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Hungarian</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Icelandic\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Icelandic</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Indonesian\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Indonesian</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Irish\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Irish</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Italian\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Italian</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Japanese\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Japanese</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Javanese\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Javanese</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Kannada\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Kannada</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Khmer\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Khmer</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Korean\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Korean</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Lao\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Lao</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Latin\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Latin</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Latvian\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Latvian</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Lithuanian\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Lithuanian</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Macedonian\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Macedonian</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Malay\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Malay</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Maltese\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Maltese</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Marathi\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Marathi</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Norwegian\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Norwegian</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Persian\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Persian</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Polish\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Polish</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Portuguese\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Portuguese</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Romanian\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Romanian</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Russian\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Russian</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Serbian\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Serbian</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Slovak\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Slovak</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Slovenian\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Slovenian</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Spanish\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Spanish</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Swahili\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Swahili</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Swedish\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Swedish</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Tamil\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Tamil</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Telugu\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Telugu</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Thai\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Thai</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Turkish\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Turkish</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Ukrainian\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Ukrainian</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Urdu\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Urdu</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Vietnamese\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Vietnamese</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Welsh\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Welsh</a>
</div>
<div class="sitewide-googletranslate-optioncontainer">
    <a href="javascript: void(0);" onclick="jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'Yiddish\') span').click(); jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer').hide(); ">Yiddish</a>
</div>

</div>

<script>
/*
 This script tag handles JavaScript functionality for the GoogleTranslate implementation.
 In the BusinessUSA site, this code is stored in the file; share_links.php
 DO NOT relocate this JavsScript tag elsewhere (i.e. global.js), we want to keep this JavaScript code in the general header of the site for Parature's sake
 */

lastGoogleTranslatedLanguage = 'English';
jQuery(document).ready( function () {

    // This global variable shall control weather or not the GoogleTranslate context-div should be hidden on the body's click event
    ignoreClearGoogleTranlsate = false;

    // Dependancy function - createCookie
    if ( typeof createCookie != 'function' ) {
        createCookie = function(name,value,days) {
            if (days) {
                var date = new Date();
                date.setTime(date.getTime()+(days*24*60*60*1000));
                var expires = "; expires="+date.toGMTString();
            } else {
                var expires = "";
            }
            document.cookie = escape(name)+"="+escape(value)+expires+"; path=/";
        }
    }

    // Dependancy function - readCookie
    if ( typeof readCookie != 'function' ) {
        readCookie = function(name) {
            var nameEQ = escape(name) + "=";
            var ca = document.cookie.split(';');
            for ( var i = 0 ; i < ca.length ; i++ ) {
                var c = ca[i];
                while (c.charAt(0)==' ') c = c.substring(1,c.length);
                if (c.indexOf(nameEQ) == 0) return unescape(c.substring(nameEQ.length,c.length));
            }
            return null;
        }
    }

    // Dependancy function - readCookie
    if ( typeof consoleLog != 'function' ) {
        consoleLog = function (inptMsg) {
            if ( typeof console != 'undefined' ) {
                console.log(inptMsg);
            }
        }
    }

    // Dependancy globals - arrUrlQueries and objUrlQueries
    if ( typeof objUrlQueries == 'undefined' ) {
        arrUrlQueries = String(document.location.search).replace('?', '').split('&');
        objUrlQueries = {};
        for ( var x = 0 ; x < arrUrlQueries.length ; x++ ) {
            thisQueryParams = String(arrUrlQueries[x]).split('=');
            if ( thisQueryParams.length == 0 ) {
                // nothing to add to objUrlQueries
            } else if ( thisQueryParams.length == 1 ) {
                objUrlQueries[thisQueryParams[0]] = thisQueryParams[0];
            } else if ( thisQueryParams.length >1 ) {
                objUrlQueries[thisQueryParams[0]] = thisQueryParams[1];
            }
        }
    }

    // A function used to set the global ignoreClearGoogleTranlsate variable to true for the next X milliseconds. If this function is called a second time, less than X milliconds from the last call, the previously-created timer to rest ignoreClearGoogleTranlsate should be destroyed.
    ignoreClearGoogleTranlsate_Timers = [];
    ignoreClearGoogleTranlsateTemporarily = function (durationInMiliseconds) {

        // Clear any timers that are about to set ignoreClearGoogleTranlsate = false
        for ( var x = 0 ; x < ignoreClearGoogleTranlsate_Timers.length ; x++ ) {
            clearTimeout(ignoreClearGoogleTranlsate_Timers[x]);
        }
        ignoreClearGoogleTranlsate_Timers = []; // All timers have been cleared, reset this array

        // Set ignoreClearGoogleTranlsate = true, create a timer to set ignoreClearGoogleTranlsate back to false in durationInMiliseconds ms
        ignoreClearGoogleTranlsate = true;
        var timerId = setTimeout( function () {
            ignoreClearGoogleTranlsate = false;
            consoleLog('ignoreClearGoogleTranlsate set to false. Coder Bookmark: CB-9VUSZMB-BC');
        }, durationInMiliseconds);
        ignoreClearGoogleTranlsate_Timers.push(timerId);
    }

    // A function to try translating the page to a target language, and keep retrying to do so on failure
    forceLanguageAndRetryOnFailure = function (targetLanguage) {
        var langChangeFailure = false;
        if ( jQuery('.goog-te-menu-frame').length == 0 ) {
            langChangeFailure = true;
        }
        if ( jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'' + targetLanguage + '\') span').length == 0 ) {
            langChangeFailure = true;
        }
        if ( langChangeFailure ) {
            consoleLog('Could not trigger the GooleTranslate API to translate to ' + objUrlQueries['setLanguage'] + '. Will retry in 1000ms.');
            setTimeout("forceLanguageAndRetryOnFailure('" + targetLanguage + "')", 1000);
        } else {
            jQuery('.goog-te-menu-frame').contents().find('.goog-te-menu2-item:contains(\'' + targetLanguage + '\') span').click();
            consoleLog('Triggered click event for page translation (GoogleTranslate API)');
        }
    }

    // OnLoad Event - Check if there is a setLanguage flag in the HTTP-GET query (of the URL). If there is, translate the page to this language
    if ( typeof objUrlQueries['setLanguage'] != 'undefined' ) {
        consoleLog('There is a setLanguage flag set in the URL, will translate this page to ' + objUrlQueries['setLanguage']);
        forceLanguageAndRetryOnFailure(objUrlQueries['setLanguage']);
    }

    // We shall create a function that will detect what language the page has been translated to (if/when it has)
    getGoogleTranslatedLanguage = function() {
        // First check if the GoogleTranslate bar is even on the page (it is always present AND visible when the page is translated
        if ( jQuery('.goog-te-banner-frame').length == 0 ) {
            return ''; // It is not, the page has not been translated yet
        }
        // Check if the GoogleTranslate bar is visible - this bar is ALWAYS visible when the page is translated
        if ( jQuery('.goog-te-banner-frame').parent().is(':visible') == false ) {
            return ''; // It is not, the page may have been translated, but right now it has been switched back to English
        }
        var translatedLanguageLabelText = jQuery('.goog-te-banner-frame').contents().find('.goog-te-menu-value span').eq(0).text();
        if ( jQuery.trim(translatedLanguageLabelText) == '' ) {
            return 'Select Language';
        } else {
            return String(translatedLanguageLabelText).replace('Select Language', '');
        }
    }

    // When the user clicks on the Translate globe, toggle the display of the Translate-UI
    jQuery('.sitewide-googletranslate-ui-globe').bind('click', function () {
        jQuery('.sitewide-googletranslate-mastercontainer').toggle();
    });

    // When the user hovers their mouse over the globe, or the user clicks it - show the Google-Translate UI
    jQuery('.sitewide-googletranslate-ui-globe, .sitewide-googletranslate-mastercontainer, .sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-busa-translate-language-indicator').bind('mouseenter', function () {
        jQuery('.sitewide-googletranslate-mastercontainer').show();
        // The mouse may have left the div.sitewide-googletranslate-mastercontainer and entered the div.sitewide-googletranslate-optionsmastercontainer...
        //  In this situation, we do not want to clear away the GoogleTranslate UI
        ignoreClearGoogleTranlsateTemporarily(250);
        consoleLog('mouseenter event - hiding GoogleTranslate context-div shall be ignored for the next 500ms (share_links.php, Coder Bookmark: CB-6ZRRU2T-BC)');
    });

    // We want to hide all elements associated with the GoogleTranslate UI when the mouse is outside of the Globale AND main-GoogleTranslate div AND the LanguageSelectorList
    jQuery('.sitewide-googletranslate-ui-globe, .sitewide-googletranslate-mastercontainer, .sitewide-googletranslate-optionsmastercontainer').bind('mouseleave', function () {
        setTimeout( function () {
            if ( ignoreClearGoogleTranlsate != true ) {
                consoleLog('Force-hiding the GoogleTranslate UI - mouseleave event (js in share_links.php)');
                jQuery('.sitewide-googletranslate-mastercontainer, .sitewide-googletranslate-optionsmastercontainer').hide();
            }
        }, 250);
    });

    // When the body's click even is fired, force-hide the GoogleTranslate context-div
    jQuery('.sitewide-googletranslate-optionsmastercontainer, .sitewide-googletranslate-mastercontainer, .sitewide-googletranslate-ui-globe').bind('click', function () {
        consoleLog('body.click event for hiding GoogleTranslate context-div shall be ignored for the next 100ms (share_links.php, Coder Bookmark: 8D1DVKY)');
        ignoreClearGoogleTranlsateTemporarily(500);
    });

    // But do not force-hide the GoogleTranslate context-div when the user clicks on the globe or the GoogleTranslate context-div itself
    jQuery('body').bind('click', function () {
         if ( ignoreClearGoogleTranlsate != true ) {
         consoleLog('Force-hiding the GoogleTranslate UI - body.click event (js in share_links.php, Coder Bookmark: ON9DGWA)');
         jQuery('.sitewide-googletranslate-mastercontainer, .sitewide-googletranslate-optionsmastercontainer').hide();
         }
     });

    // When the user tabs (508) to the globe, show the Translate-UI
    jQuery('.sitewide-googletranslate-ui-globe').bind('focus', function () {
        /* When the user tabs (508) to the globe, show the Translate-UI. Coder Bookmark: GBUX5TF */
        consoleLog('sitewide-googletranslate-ui-globe, focus event: Showing GoogleTranslate UI, hiding languager-list (js in share_links.php, Coder Bookmark: CB-AY2JT04-BC)');
        jQuery('.sitewide-googletranslate-mastercontainer').show();
        jQuery('.sitewide-googletranslate-optionsmastercontainer').hide();
    });

    // We shall notice when the page has been translated to another language - check getGoogleTranslatedLanguage() every second
    lastGoogleTranslatedLanguage = 'English';
    setInterval( function () { /* Code-Bookmark: EFECVHR */
        var currentLanguage = getGoogleTranslatedLanguage();
        if ( currentLanguage != lastGoogleTranslatedLanguage ) {
            /* The following code (rest of the code in this code block) is the event handler for when the translated-language CHANGES */
            consoleLog('GoogleTranslate has just translated the page to ' + currentLanguage);
            // Bug killer - sometimes it seems something is killing this div, force-inject this div into the DOM when it is not there
            if ( jQuery('.sitewide-googletranslate-busa-translate-language-indicator').length == 0 ) {
                jQuery('.sitewide-googletranslate-rightside').prepend('<div class="sitewide-googletranslate-busa-translate-language-indicator"></div>')
            }
            // Update the text displayed over the Language-Selection drop down - the drop-down should show the language selected
            jQuery('.sitewide-googletranslate-busa-translate-language-indicator').val( currentLanguage == '' ? 'English' : currentLanguage );
            // Note internally what the translated-language of the page has been set to
            lastGoogleTranslatedLanguage = String(currentLanguage).replace('Select Language', '');
            createCookie('LanguageTranslated', currentLanguage, 14);
        }
    }, 1000);

    // Special key events for anchors within the Language-List
    jQuery('.sitewide-googletranslate-optioncontainer a').bind('keydown', function (e) { /* Coder Bookmark: CB-4F8SFNB-BC */
        var jqThis = jQuery(this);
        var code = ( e.keyCode ? e.keyCode : e.which );
        if (code == 40) {
            setFocusTo = jqThis.parent().next().find('a');
            setTimeout( function () {
                consoleLog('The down-arrow key has been pressed while focus is within the Language-List, setting focus to the next element. /* Coder Bookmark: CB-C7NV6KC-BC */');
                setFocusTo.focus();
            }, 50);
        } else if (code == 38) {
            setFocusTo = jqThis.parent().prev().find('a');
            setTimeout( function () {
                consoleLog('The up-arrow key has been pressed while focus is within the Language-List, setting focus to the next element. /* Coder Bookmark: CB-HYI2D08-BC */ ');
                setFocusTo.focus();
            }, 50);
        } else if (code == 9) {
            setTimeout( function () {
                consoleLog('The tab key has been pressed while focus is within the Language-List, setting focus to the twitter icon. /* Coder Bookmark: CB-QCNKINY-BC */ ');
                jQuery('#twitter a').focus();
            }, 50);
        }
    });
    //added to allow tabbing
    jQuery('#googleTranslateApiPostContainer').blur(function(){
      jQuery('.sitewide-googletranslate-mastercontainer').css('display', 'none');
    });

});

</script>