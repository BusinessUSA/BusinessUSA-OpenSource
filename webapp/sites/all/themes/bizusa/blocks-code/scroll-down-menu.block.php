<a href="/" title="link to homepage" class="logoscroll-containerlink">
    <img id="logoscroll" alt="BusinessUSA Menu Logo" src="/sites/all/themes/bizusa/images/scroll-menu-logo.png" />
</a>
<div class="mostVisited">
	<ul>
		<li class="item1">
			<span>hover to see wizard</span>
			<ul>
				<li><a href="/start-a-business">Start a Business</a></li>
			</ul>
		</li>
		<li class="item2">
			<span>hover to see wizard</span>
			<ul>
				<li><a href="/access-financing">Access Financing</a></li>
			</ul>
		</li>
		<li class="item3">
			<span>hover to see wizard</span>
			<ul>
				<li><a href="/export">Explore Exporting</a></li>
			</ul>
		</li>
		<li class="item4">
			<span>hover to see wizard</span>
			<ul>
				<li><a href="/find-opportunities">Find Opportunities</a></li>
			</ul>
		</li>
		<li class="item5">
			<span>hover to see wizard</span>
			<ul>
				<li><a href="/resource/grow-your-business">Grow Your Business</a></li>
			</ul>
		</li>
		<li class="item6">
			<span>hover to see wizard</span>
			<ul>
				<li><a href="/taxes-and-credits">Learn About Taxes and Credits</a></li>
			</ul>
		</li>
	</ul>
</div>
<div class="fullMenu">
	<a href="#" class="toggleMenu" title="Show Main Menu">Show Main Menu</a>
	<ul class="menu">
		<li class="last leaf home"><a href="/" title="">Home</a></li>
		<li class="last leaf about-us"><a href="/about-us" title="">About Us</a></li>
		<li class="first expanded resources">
			<a href="/find-resources" title="">Resources</a>
			<ul class="menu">
				<li class="first leaf start-a-business"><a href="/start-a-business" title="" class="active">Start a Business</a></li>
				<li class="leaf access-financing"><a href="/access-financing" title="" class="active">Access Financing</a></li>
				<li class="leaf explore-exporting"><a href="/export" title="" class="active">Explore Exporting</a></li>
				<li class="leaf grow-your-business"><a href="/resource/grow-your-business" title="" class="active">Grow Your Business</a></li>
				<li class="leaf find-opportunities"><a href="/find-opportunities" title="" class="active">Find Opportunities</a></li>
				<li class="leaf learn-about-new-health-care-changes"><a href="/healthcare" title="" class="active">Learn About New Health Care Changes</a></li>
				<li class="leaf browse-resources-for-veterans"><a href="/veterans" title="" class="active">Browse Resources for Veterans</a></li>
				<li class="leaf learn-about-taxes-and-credits"><a href="/taxes-and-credits" title="" class="active">Learn About Taxes and Credits</a></li>
				<li class="leaf help-with-hiring-employees"><a href="/jobcenter-wizard" title="" class="active">Help with Hiring Employees</a></li>
				<li class="leaf invest-in-the-usa"><a href="/select-usa" title="" class="active">Invest in the USA</a></li>
				<li class="leaf seek-disaster-assistance"><a href="/disaster-assistance" title="" class="active">Seek Disaster Assistance</a></li>
				<li class="leaf browseregulations"><a href="/browseregulations" title="" class="active">Find Regulations</a></li>
				<li class="leaf find-green-opportunities"><a href="/find-green-opportunities" title="" class="active">Find Green Opportunities</a></li>
                <li class="leaf patent-trademark-office"><a href="/patent-trademark-office" title="" class="active">Understanding Intellectual Property</a></li>
                <li class="leaf take-a-tour"><a href="/tour" title="" class="active">Take a Tour</a></li>
				<li class="last leaf browse-all-resources"><a href="/find-resources" title="" class="active">Browse All Resources</a></li>
			</ul>
		</li>
		<li class="leaf events"><a href="/events" title="" class="active">Events</a></li>
		<li class="leaf training"><a href="/training-materials-portal" title="" class="active">Training</a></li>
		<li class="leaf support-center"><a href="http://help.business.usa.gov/" title="" class="active">Support Center</a></li>
		<li class="leaf state"><a href="/micro-site/state_resource" title="" class="active">State</a></li>
		<li class="leaf ownership"><a href="#" title="" class="active">Ownership</a>
			<ul class="menu">
				<li class="first leaf woman"><a href="/micro-site/ownership-landing?ownership=women" title="">Woman</a></li>
				<li class="leaf socially"><a href="/micro-site/ownership-landing?ownership=socially_economically_disadvantaged" title="">Socially &amp; Economically Disadvantaged</a></li>
				<li class="leaf veterans"><a href="/micro-site/ownership-landing?ownership=veterans" title="">Veterans</a></li>
				<li class="leaf indians"><a href="/micro-site/american-indian-microsite/american-indian-and-alaska-natives" title="">American Indian and Native Alaskan</a></li>
			</ul>
		</li>
        <li class="leaf country"><a href="/micro-site/country-resource" title="" class="active">Country</a></li>
        <li class="leaf country"><a href="/micro-site/industry-resource" title="" class="active">Industry</a></li>
	</ul>
</div>
<div class="searchArea">
    <a title="Show Search Bar" href="#">show search bar</a>
    <form class="sitewide-logo-and-search" action="javascript: document.location = '/search/site/' + jQuery('#sitewide-search-input').val();">
      <input type="text" id="scroll-sitewide-search-input" placeholder="Start Searching..."><input type="submit" id="scroll-sitewide-search-button" value="Search">
    </form>
</div>


<script>
    if (('#scroll-sitewide-search-input').length > 0)
    {
        $('#scroll-sitewide-search-input').focus(function ()
        {
            $(this).attr('placeholder','');
        });

        $('#scroll-sitewide-search-input').focusout(function()
        {
            $(this).attr('placeholder','Start Searching...');
        });
    }
</script>
