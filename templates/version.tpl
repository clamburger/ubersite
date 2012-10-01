"<tag:software />" is the name of the software that runs this website. It was originally created by Doug for &Uuml;berTweak Winter 2007, and it's been repeatedly improved upon for each camp. In 2008 it was given version numbers for the first time, allowing us to track changes and improvements across camps. If you have any questions or suggestions about the site, put a message into the <a href="suggestions.php">Suggestion Box</a> or feel free to let a tech leader know yourself!

<h3>Current Version: <strong><tag:version /></strong></h3>
<if:codename><h3>Codename: <strong><tag:codename /></strong></h3>
<tag:codename-desc /></if:codename>

<!-- <h2 style='margin-top: 30px;'>Latest Changes:</h2>
<div class="changelog">
<tag:changes />
</div>
</ul> -->

<if:admin>
<h2 style='margin-top: 30px;'>MySQL Details:</h2>
<div class="changelog">
<ul>
<li><strong>Host:</strong> <tag:db-host /></li>
<li><strong>User:</strong> <tag:db-user /></li>
<li><strong>Password:</strong> <span style='color: black; background-color: black;'><tag:db-pass /></span></li>
<li><strong>Database:</strong> <tag:db-name /></li>
</ul>
</div>

<h2 style='margin-top: 30px;'>Page View Analysis:</h2>
This is located on a <a href="analysis.php">separate page</a>.
</if:admin>
