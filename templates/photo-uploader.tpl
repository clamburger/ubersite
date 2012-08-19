<h2>Photo Uploader: <sup style='color: green;'>Beta</sup></h2>   
<if:leader>

	This is where you can upload photos to the <strong>&Uuml;berTweak Photograph Repository</strong>. Photos that are uploaded here will be used for the camp website, camp DVD and possibly a video presentation on show night. Even if you have photos that you don't think are that good, upload them anyway! I strongly recommend that you upload every single photo you have in the highest resolution possible. Over 2,500 photos were collected on Winter and Spring last year and it would be great to expand the collection: <strong>no photo is too trivial!</strong><br /><br />
	
	The photo uploader is currently in beta. You should not trust it to be 100% working or accurate. <strong>Do not delete your copy of the photos</strong> until you have human confirmation that the photos were uploaded successfully. <br /><br />
	
<div style='float: left; width: 55%;'>
<!-- <h3>It's an easy two step process:</h3>

	<ol>
		<li>Click the first button to upload files (make sure you check the upload restrictions first)<br />
		Once you see <span style='color: green'>Success!</span> the file has been uploaded - no further confirmation is needed.</li>
		<li><strong>Optional:</strong> Click the second button to go to processing.<br />This will allow you to tag photos, choose which photos will go on the website, etc.<br />Don't feel obliged to do this step: I'm more than happy to process them myself.<br /></li>
	</ol> -->
	
	<h3>It's an easy two step process:</h3>
	
	<ol>
		<li>Click the button and select the files you want to upload.<br />
			Wait until all of the files say "Success".<br />
			You're done!</li>
		<li>Sam will process the photos and add them to the camp website as appropriate.</li>
	</ol>
	
</div>

<div style='float: right; width: 40%;'>
<h3>Upload Restrictions:</h3>

	<ul>
		<li>Size limit: <strong>20MB</strong> per file</li>
		<li>Filetypes accepted: <strong>all</strong></li>
		<li>Maximum number of files you can upload at once: <strong>unlimited</strong><br />
			(Recommended: 50 or less at a time)</li>
		<li>If a file has the same name as an existing file it will be renamed.</li>
	</ul>
</div>

<h3 style='clear: both;'>
<if:previous>Preivous Uploads:<else:previous>Upload Controls:</if:previous>
</h3>

	<div id="upload-container">
		<div class="qq-uploader">
			<if:previous>
			<a href="?"><div class="qq-finish-button">Go back to uploader</div></a>
			<else:previous>
			<div class="qq-upload-button">Click to<br />upload files</div>
			<a href="?previous"><div class="qq-finish-button">Show previous uploads</div></a>
			</if:previous>
			<if:admin>
				<a href="photo_processing.php"><div class="qq-finish-button">Take me to<br />processing</div></a>
			</if:admin>
			
			<if:previous>
			
			<if:oldFiles>
			<ol class="qq-upload-list">
			<loop:oldFiles>
			<li class="qq-upload-success">
				<a class="qq-upload-link" href="includes/uploads/<tag:oldFiles[].filename />"><tag:oldFiles[].filename /></a>&nbsp;&nbsp;
				<span class="qq-upload-size" style="display: inline;">(<tag:oldFiles[].date />)</span>
			</li>
			</loop:oldFiles>
			</ol>
			<else:oldFiles>
			<ul class="qq-upload-list">
			<li class="qq-upload-success">You haven't uploaded any images yet.</li>
			</ui>
			</if:oldFiles>
			
			<else:previous>
			<ol class="qq-upload-list"></ol>
			</if:previous>
		</div>
	</div>

	<script>		
		function createUploader(){			
			var uploader = new qq.FileUploader({
				element: document.getElementById('upload-container'),
				action: 'includes/uploader.php',
				debug: true
			});		   
		}
		
		// in your app create uploader as soon as the DOM is ready
		// don't wait for the window to load  
		window.onload = createUploader;	 
	</script>
	
<else:leader>
You must be a leader to view this page.
</if:leader>