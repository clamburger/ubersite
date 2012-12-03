<if:nop>
  Unfortunately, no photos can be processed.
<else:nop>
  Click on any photo to see a larger version of it!
  <h3 id="counter">You have <tag:number /> photo<tag:suffix /> to review.</h3>
  <div id="error">&nbsp;</div>
  <table id="photoProcessor">
    <tr>
      <td class="frame" colspan="4">
        <img id="currentPhoto" src="" />
      </td>
    </tr>
    <tr>
      <th>Event:</th>
      <td colspan="2"><select id="events">
          <loop:events><option><tag:events[] /></option>
          </loop:events>
        </select>
      </td>
      <td id="tagged" rowspan="2">&nbsp;</td>
    </tr>
    <tr>
      <th>Tag People:</th>
      <td colspan="2">
        <textarea id="people" onkeyup="processor.search(this)"></textarea>
      </td>
    </th>
    <tr class="buttons">
      <td><input type="button" onclick="processor.publish();" value="Publish" /></td>
      <td><input type="button" onclick="processor.publishRest();" value="Publish Rest" /></td>
      <td><input type="button" onclick="processor.trash();" value="Trash" /></td>
      <td><input type="button" onclick="processor.finalise();" value="Finalise Session" /></td>
    </tr>
  </table>
  <br/>
  <br/>
  <div id="toprocess">
  <loop:pictures>
    <div class="<tag:pictures[].class />" id="<tag:pictures[].filename />"
        onclick="processor.loadPhoto(this.id);">
      <img src="/camp-data/photos/cache/<tag:pictures[].thumb />" height="133" width="200" />
    </div>
  </loop:pictures>
  </div>
  <script type="text/javascript">
    processor.loadPhoto('<tag:current.filename />');
    processor.count = <tag:number />;

    // People
    processor.people = <tag:people />;
    processor.rPeople = <tag:rPeople />;
  </script>
</if:nop>
