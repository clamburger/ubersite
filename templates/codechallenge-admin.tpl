<if:leader> <!-- should be admin instead of leader but I don't know if I'll get admin privileges on camp -->
<div style="float: left; width: 40%">
Here is where you can manage the code challenge.<br />
<h3>Types of CSV files to upload:</h3>
<ul>
	<li><strong>Content:</strong> what should be displayed on the main 'code challenge' area of the site. In format Title, Content.</li>
	<li><strong>Test Cases:</strong> test cases used to check the challenge. In format ID, Params, Result, Visible.</li>
	<li><strong>Results:</strong> participant results after taking the challenge. In format UserID, Score, Average Time in ms, Time1 in ms, Time2 in ms, Time3 in ms.</li>
</ul>

<h3>Please ensure that filenames contain one of the words "content", "test", or "results".</h3>
</div>

</ul>
<div style='float: right; width: 40%;'>
<h3>Upload Restrictions:</h3>

  <ul>
    <li>Size limit: <strong>10MB</strong> per file</li>
    <li>Filetypes accepted: <strong>.csv, .txt</strong></li>
    <li>Maximum number of files you can upload at once: <strong>unlimited</strong></li>
    <li>If a file has the same name as an existing file it will be <strong>renamed</strong>.</li>
  </ul>
</div>

<h3 style='clear: both;'>
<if:previous>Previous Uploads:<else:previous>Upload Controls:</if:previous>
</h3>

  <div id="upload-container">
    <div class="qq-uploader">
      <if:previous>
      <a href="/photo-upload"><div class="qq-finish-button">Go back to uploader</div></a>
      <else:previous>
      <div class="qq-upload-button">Click to<br />upload files</div>
      <a href="/photo-upload/previous"><div class="qq-finish-button">Show previous uploads</div></a>
      </if:previous>
      <if:previous>

      <if:oldFiles>
      <ol class="qq-upload-list">
      <loop:oldFiles>
      <li class="qq-upload-success">
        <a class="qq-upload-link" href="/camp-data/uploads/<tag:oldFiles[].filename />"><tag:oldFiles[].filename /></a>&nbsp;&nbsp;
        <span class="qq-upload-size" style="display: inline;">(<tag:oldFiles[].date />)</span>
      </li>
      </loop:oldFiles>
      </ol>
      <else:oldFiles>
      <ul class="qq-upload-list">
      <li class="qq-upload-success">You haven't uploaded anything yet.</li>
      </ui>
      </if:oldFiles>

      <else:previous>
      <ol class="qq-upload-list"></ol>
      </if:previous>
    </div>
  </div>
  
  <div id="make-visible-button">
  <form action="/codechallenge-admin" method="post">
  <input type="submit" name="makeVisible" value="Make All Test Cases Visible">
  </form>
  </div>
  
  <div id="download-zip-button">
  <form action="/codechallenge-admin" method="post">
  <input type="submit" name="downloadZip" method="post" value="Download All Submissions">
  </form>
  </div>
  
  <script>
    function createUploader(){
      var uploader = new qq.FileUploader({
        element: document.getElementById('upload-container'),
        action: '/codechallenge-admin-uploader',
        debug: true
      });
    }

    // in your app create uploader as soon as the DOM is ready
    // don't wait for the window to load
    window.onload = createUploader;
  </script>
<else:leader>
Only leaders are allowed to access this page.
</if:leader>