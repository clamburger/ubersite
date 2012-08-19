<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" id="www-ubertweak-org-au">
  
  <head>
    <title><tag:software /> Setup</title>
    
    <link rel="icon" type="image/png" href="../resources/img/icon.png" />
	
	<link rel="stylesheet" type="text/css" href="../external/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="../resources/css/layout.css" />
    <link rel="stylesheet" type="text/css" href="../resources/css/setup.css" />
    
    <script src="../external/jquery-1.7.js"></script>
    <script src="../external/bootstrap-tabs.js"></script>
    <script src="setup.js"></script>
    
    <style type="text/css">
    .tabs .error {
		color: #B94A48 !important;
	}
	.tabs .success {
		color: #468847 !important;
	}
	.input .help-block {
		clear: both;
	}
    </style>
    	
    <meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8" />
  </head>
  
  <body id="module">
    <!-- Header -->
        
    <div id="headerContainer">
    <div id="header" style='width: 100%;'>
		<img src="../resources/img/logo.png" class="logo" border="0" alt="UberTweak Logo" />
		<div class="title">
		&Uuml;berSite Setup<br />
		<div class="version">Version <tag:version /> <if:codename>(<tag:codename />)</if:codename></strong></div></div>
	</div>
	</div>
	<!-- Content -->
	
	<div id="content">
	
		<h2>Prerequisite Check:</h2>

		Welcome to <tag:software />. This short setup process will help configure your website - simply enter in the information for each tab.
		Once all tabs are complete, open the final tab in order to save your configuration and proceed to the next configuration stage.
		<br />	
		<ul>
			<li>PHP version at least 5.3... <tag:checks.php /></li>
			<li>Config directory writable... <tag:checks.configWritable /></li>
			<li>MySQL functions available... <tag:checks.mysql /></li>
			<li>MySQLi functions available... <tag:checks.mysqli /></li>
			<li>SSH2 functions available... <tag:checks.ssh /></li>
			<li>LDAP functions available... <tag:checks.ldap /></li>
		</ul>
		
		<if:error>
		<h3>An essential requirement is not available: the setup process cannot continue.</h3>
		<else:error>
		
		<ul class="tabs">
			<li class="active"><a href="#camp-information" class="error">Camp Information</a></li>
			<li><a href="#mysql" class="error">MySQL Database</a></li>
			<li><a href="#authentication" class="error">Authentication</a></li>
			<li><a href="#admin-user" class="error">Admin User</a></li>
			<li><a href="#finish" id="finish-tab">Finish Setup</a></li>
		</ul>
 
		<div class="tab-content">
			<div class="active" id="camp-information">
			
			<h2>Camp Information:</h2>
		
				<div class="row">
					<div class="span4">
					Basic information about the camp: this information will be used for display purposes.
					</div>
					
					<div class="span12">
				
						<form name="camp-information">
							
							<div class="clearfix">
								<label for="campName">Camp name:</label>
								<div class="input">
									<input id="campName" class="span4" type="text" name="campName" placeholder="&Uuml;bertweak Summer" />
									<span class="help-block">Special characters will be automatically escaped</span>
								</div>
							</div>
							<div class="clearfix">
								<label for="campYear">Camp year:</label>
								<div class="input">
									<input id="campYear" class="span1" type="text" name="campYear" maxlength="4" placeholder="2011" />
								</div>
							</div>
							<div class="clearfix">
								<label for="directors">Directors:</label>
								<div class="input">
									<input id="directors" class="span4" type="text" name="directors" placeholder="Alan, Jake and Josh" />
									<span class="help-block">Used only in the last page of the questionnaire</span>
								</div>
							</div>
							<div class="clearfix">
								<label for="timezone">Timezone:</label>
								<div class="input">
									<input id="timezone" class="span4" type="text" name="timezone" placeholder="Australia/Brisbane" />
								</div>
							</div>
							<div class="clearfix">
								<label for="stylesheet">Stylesheet:</label>
								<div class="input">
									<select id="stylesheet" class="span4" name="stylesheet">
										<option value="winter">Winter (recommended)</option>
										<option value="spring">Spring</option>
										<option value="setup">Greyscale</option>
									</select>
								</div>
							</div>
							<div class="actions">
								<input class="btn" type="submit" value="Verify information" />
							</div>
							
						</form>
						
					</div>
							
				</div>
			
			</div>
			
			<div id="mysql">
			
				<h2>MySQL Database:</h2>
	
				<div class="row">
					<div class="span4">
					<tag:software /> uses a MySQL database to store data. Once the information has been verified as correct, the database structure will be created.<br /><br />
					If the selected database does not already exist, make sure the user has CREATE DATABASE permissions.
					</div>
					
					<div class="span12">
				
						<form name="mysql-database">
							
							<div class="clearfix">
								<label for="mysqlHost">Host:</label>
								<div class="input">
									<input id="mysqlHost" class="span4" type="text" name="mysqlHost" placeholder="localhost" />
								</div>
							</div>
							<div class="clearfix">
								<label for="mysqlUser">Username:</label>
								<div class="input">
									<input id="mysqlUser" class="span4" type="text" name="mysqlUser" placeholder="root" />
								</div>
							</div>
							<div class="clearfix">
								<label for="mysqlPassword">Password:</label>
								<div class="input">
									<input id="mysqlPassword" class="span4" type="password" name="mysqlPassword" placeholder="password" />
								</div>
							</div>
							<div class="clearfix">
								<label for="mysqlDatabase">Database:</label>
								<div class="input">
									<input id="mysqlDatabase" class="span4" type="text" name="mysqlDatabase" placeholder="ubertweak_sp11" />
								</div>
							</div>
							<div class="actions">
								<input class="btn" type="submit" value="Verify information" />
							</div>
							
						</form>
						
					</div>
							
				</div>
			
			</div>
			
			<div id="authentication">
				<h2>Authentication:</h2>
		
				<div class="row">
					<div class="span4">
						<tag:software /> supports a number of different authentication methods, including LDAP and SSH. MySQL authentication is easiest to set-up, but does not integrate with things such as computer logins.
						<br /><br />
						LDAP and SSH support is not fully functional: do not use it unless you know what you're doing.
					</div>
					
					<div class="span12">
					
						<form name="authentication">
							
							<div class="clearfix">
								<label for="authType">Authentication:</label>
								<div class="input">
									<select id="authType" class="span4" name="authType">
										<option value="mysql">MySQL</option>
										<if:ldap>
											<option value="ldap">LDAP</option>
										</if:ldap>
										<if:ssh>
											<option value="ssh">SSH</option>
										</if:ssh>
									</select>
								</div>
							</div>
							
							<if:ldap>
							<div class="clearfix">
								<label for="serverLDAP">LDAP server:</label>
								<div class="input">
									<input id="serverLDAP" class="span4" type="text" name="serverLDAP" placeholder="10.65.15.2" />
								</div>
							</div>
							</if:ldap>
							
							<if:ssh>
							<div class="clearfix">
								<label for="serverSSH">SSH server:</label>
								<div class="input">
									<input id="serverSSH" class="span4" type="text" name="serverSSH" placeholder="10.65.15.3" />
								</div>
							</div>
							</if:ssh>
							
							<div class="actions">
								<input class="btn" type="submit" value="Verify information" />
							</div>
						
						</form>
					</div>
				</div>
				
			</div>
			
			<div id="admin-user">
			
				<h2>Admin User:</h2>
		
				<div class="row">
				
					<div class="span4">
						Admin users are able to import other users and perform various other administrative tasks.
						Enter the details of a user here, and they will become the first admin.
						<br /><br />
						Remember that this user will appear normally, just like any other user: I recommend making this your own personal account.
					</div>
					
					<div class="span12">
					
						<form name="admin-user">
							
							<div class="clearfix">
								<label for="adminName">Name:</label>
								<div class="input">
									<input id="adminName" class="span4" type="text" name="adminName" placeholder="Sam Horn" />
								</div>
							</div>
							<div class="clearfix">
								<label for="adminUser">Username:</label>
								<div class="input">
									<input id="adminUser" class="span4" type="text" name="adminUser" placeholder="sam-h" />
								</div>
							</div>
							<div class="clearfix">
								<label for="adminPass">Password:</label>
								<div class="input">
									<input id="adminPass" class="span4" type="password" name="adminPass" placeholder="password" />
								</div>
							</div>
							
							<div class="actions">
								<input class="btn" type="submit" value="Verify information" />
							</div>
						
						</form>
					</div>
				</div>
			
			</div>
			
			<div id="finish">
			
				<h2>Finish Setup:</h2>
				
				<div class="row">
					<div class="span16">
						<div class="alert-message info" id="processing" style="display: none;">Processing...</div>
						
						<div id="before-msg">
							Once you've filled in and verified every section, hit the button below to save your changes.
							The database will be populated and you will be able to access the main part of the website.
							From there you will be able to make changes to things such as the timetable and duty groups.
						</div>
						
						<div id="after-msg" style="display: none;">
							The setup process is complete! If everything worked, you should be able to click through
							to the website and log in using the admin details you provided.<br /><br />
							<strong>For security reasons, you should now delete the <tt>setup</tt> directory.</strong>
						</div>
						
						<br />
						<input type="submit" class="btn" value="Save configuration" id="save-config" />
						<input type="submit" class="btn primary" value="Go to the website!" id="refresh-page" style="display: none;" />
						<span id="not-complete" class="help-block">You have not yet completed every section</span>
						
					</div>
				</div>
			
			</div>
		</div>
    
    </if:error>
    
    </div>
    
  </body>
</html>
