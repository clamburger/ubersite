<h3>Sections in Use:</h3>
<ul id='useqs'>
</ul>

<h3>Secitons available:</h3>
<ul id='notuseqs'>
</ul>
<script type="text/javascript">
  update_sections("<tag:items />");
</script>

<h3>Create/Edit Section:</h3>
<form name="SectionMaker" action="" method="POST" onsubmit="return false;">
  <input name="id" type="hidden" />
  <table id="questiontable">
    <thead>
      <tr>
        <th>Name:</th>
        <td colspan="2">
          <input type="text" style="width:100%" name="name" value="" />
        </td>
        <th>Hide:</th>
        <td>
          <input type="checkbox" name="hideName" />
        </td>
      </tr>
      <tr>
        <th>Page:</th>
        <td colspan="2">
          <select style="width:100%" name="page">
            <option value="-1">&nbsp;</option>
            <loop:pages>
              <option value="<tag:pages[].id />"><tag:pages[].name /></option>
            </loop:pages>
          </select>
        </td>
        <th>Expandable:</th>
        <td>
          <input type="checkbox" name="expandable" />
        </td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td colspan="5">
          <a href="javascript:v();" onclick="addQuestion(this)" id="addq">
            Add new question
          </a>
        </td>
      </tr>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="5">
          <input type="button" value="Create" name="submit"
              onclick="creat(this.value)"/>
          <input type="button" value="Clear" onclick="clr()"/>
        </td>
      </tr>
    </tfoot>
  </table>
</form>
<if:admin>
<h3>Questionnaires:</h3>
<ul id='questionnaires'>
</ul>
<script type="text/javascript">
  var pages = {<loop:pages>
    <tag:pages[].id />: "<tag:pages[].name />",
  </loop:pages>};
  updateQuestionnaires("<tag:questionnaires />");
</script>
<h3>Create/Edit Questionnaire:</h3>
<form name="QuizMaker" action="" onsubmit="return false;">
  <input name="id" type="hidden" />
  <table id="quiztable">
    <thead>
      <tr>
        <th>Name:</th>
        <td colspan="2">
          <input type="text" style="width:100%" name="name" value="" />
        </td>
      </tr>
      <tr>
        <th>Intro:</th>
        <td colspan="2">
          <textarea type="text" style="width:100%" name="intro" rows="3">
          </textarea>
        </td>
      </tr>
      <tr>
        <th>Outro:</th>
        <td colspan="2">
          <textarea type="text" style="width:100%" name="outro" rows="3">
          </textarea>
        </td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td colspan="3">
          <a href="javascript:v();" onclick="addPage(this)" id="addp">
            Add new page
          </a>
        </td>
      </tr>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="3">
          <input type="button" value="Create" name="submit"
              onclick="creatQuiz(this.value)"/>
          <input type="button" value="Clear" onclick="clrQuiz()"/>
        </td>
      </tr>
    </tfoot>
    </table>
</form>
</if:admin>
