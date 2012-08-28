<h2>Camp Photos:</h2>
Here we display photos that have been taken during camp. You are encouraged to submit a funny or serious caption for as many photos as you desire. If it's particularly insightful it will be displayed for all to see.

<if:nop>
	Unfortunately, no photos have been taken yet, so check back in a little while.
<else:nop>

<br /><br /><span style='font-size: large;'>Click on any photo to see a larger version of it!</span>
<ul>
<if:unapproved>
	<li><a href='?empty=admin' style='color: maroon;'>Show only unapproved photos</a></li>
</if:unapproved>
<li><a href='?empty=true'>Show only photos without captions</a></li>
<li><a href='?empty=false'>Show only photos with captions</a></li>
<li><a href='?'>Show all photos</a></li>
<li><a href='voting.php' style='color: green;'>Experimental caption view</a></li>
<if:leader>
	<li><a href='photo-uploader.php' style='color: green;'>Photo Uploader</a> <if:admin><!-- and <a href='photo-processing.php' style='color: green;'>Photo Processing Lab</a>--></if:admin></li>
</if:leader>
</ul>

<if:catFull>
	<if:filter>
		<h3><tag:filter /></h3>
	<else:filter>
		<if:unapproved>
			<h3>You have <tag:number /> unapproved photo caption<tag:suffix /> to approve or destroy.</h3>
		</if:unapproved>
	</if:filter>
	<loop:pictures>
		<div class="<tag:pictures[].class />">
		  <a href="/photo/<tag:pictures[].filename />">
			  <img src="<tag:pictures[].imageURL />" />
		  </a>
		  <div class="desc"><tag:pictures[].caption /></div>
		</div>
	</loop:pictures>
</if:catFull>

</if:nop>
