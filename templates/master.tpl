<!DOCTYPE html>
<html lang="en">
  <head>
    <title><tag:campname /> <tag:campyear />: <tag:title /></title>

    <if:standalone>
		<link rel="icon" type="image/png" href="/resources/img/icon.png" />
		<link rel="stylesheet" type="text/css" href="/resources/css/bootstrap-refined.css" />
		<link rel="stylesheet" type="text/css" href="/resources/css/<tag:stylesheet />.css" />
		<link rel="stylesheet" type="text/css" href="/resources/css/layout.css" />
	<else:standalone>
		<link rel="icon" type="image/png" href="<tag:standalone-icon />" />
		<style type="text/css">
		<tag:standalone-style />
		</style>
    </if:standalone>

    <if:head>
		<tag:head />
	</if:head>
    
	<if:standalone>

		<script src='/external/jquery-1.7.js'></script>
		<script src='/external/bootstrap-tabs.js'></script>
		<script src='/resources/js/ubersite.js'></script>
		<script src='/external/highcharts.js'></script>

		<if:js>
			<script src='/resources/js/<tag:js />'></script>
		</if:js>
	
		<!if:wget>
			<script type="text/javascript" src="/resources/js/updateBox.js"></script>
			<if:processWidth>
				<script type="text/javascript">
				if (location.search) {
					window.location = location.href + '&screenWidth=' + screen.width;   
				} else {
					window.location = location.href + '?screenWidth=' + screen.width;   
				}
				</script>
			</if:processWidth>
		</!if:wget>
		
    </if:standalone>
    <meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8" />
  </head>
  <body>
    <!-- Header -->
    <if:standalone>
    <div id="headerContainer">
		<div id="header">
			<a href='/index'><img src="/resources/img/logo.png" class="logo" border="0" alt="Übertweak Logo" width="96px" height="96px" /></a>
			
			<div class="title">
			<if:developer>
			<span style='color: maroon;'>
			<else:developer>
			<span>
			</if:developer>
			<tag:campname /> <!if:small><tag:campyear /></!if:small> - 
			<if:shortTitle>
				<tag:shortTitle />
			<else:shortTitle>
				<tag:title />
			</if:shortTitle></span><br />
			<div class="version">Powered by 
			<a href='<tag:loginURL />/version'>
			<tag:software /> <if:codename><tag:codename /><else:codename>
			<tag:version /></if:codename></a>
			</div>
			
			</div>
		</div>
		<div id="whatson">
			<!if:wget>
				<tag:whatson />
			</!if:wget>
		</div>
    </div>
    <div id="menu">
        <ul id="nav">
			<li style='width: 10px;'>&nbsp;</li>
            <tag:menu />
			<if:developer><!if:wget>
				<li class="right special"><a href='http://github.com/clamburger/ubersite'>Github</a>
                    <ul style="left: -92px;">
                        <li><a href="https://github.com/clamburger/ubersite/issues">Issue Tracker</a></li>
                    </ul>
                </li>
			</!if:wget></if:developer>
			<if:loggedin>
				<li class="right"><a href='/logout'>Logout</a></li>
				<li class="right"><span class='text'>Current User: </span><a href='person/<tag:currentUser />'><tag:currentName /></a></li>
			</if:loggedin>
        </ul>
    </div>
    <br clear="both" />
    <!-- Content -->
    <tag:messages />

    <div id="content">
        <if:contenttitle>
          <h2><tag:contenttitle />:</h2>
          <tag:titleuber />
        </if:contenttitle>
        <tag:content />
    </div>
    <else:standalone>    
    <div id="headerContainer">
    <div id="header" style='width: 100%;'>
		<img src="<tag:standalone-logo />" class="logo" border="0" alt="Übertweak Logo" />
		<div class="title">
		<tag:campname /> <tag:campyear /> - <tag:title /><br />
		<div class="version">Powered by <strong><tag:software /> <if:codename><tag:codename /><else:codename><tag:version /></if:codename></strong></div></div>
	</div>
	</div>
	<!-- Content -->
	
	<div id="content">
                <if:contenttile>
                  <h2><tag:contenttitle />:</h2>
                  <tag:titleuber />
                </if:contenttitle>
		<tag:content />
	</div>
    </if:standalone>
    <if:showQueries>
		<div style='height: 10px; clear: both;'>&nbsp;</div>
		<hr style='clear: both;' />
		<div style='margin: 10px;'>Query Count: <tag:queryCount /><br /><ol style='margin: 0px;'><tag:queries /></ol></div>
    </if:showQueries>
  </body>
</html>
