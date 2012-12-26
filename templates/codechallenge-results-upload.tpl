<if:leader>
Upload results file here.

<div style='float: right; width: 40%;'>
<h3>Upload Restrictions:</h3>

  <ul>
    <li>Size limit: <strong>100KB</strong> per file</li>
    <li>Filetypes accepted: <strong>.csv, .txt</strong></li>
    <li>Maximum number of files you can upload at once: <strong>unlimited</strong><br />
      (Recommended: 1)</li>
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
        action: '/codechallenge-results-uploader',
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