function change_use(id) {
  var x = ajaxFunction();
  x.onreadystatechange = pair(x, update_users);
  x.open("GET", "questionnaire-writer/toggle/" + id);
  x.send(null);
}

function clr() {
  document.forms["SectionMaker"]["submit"].value = "Create";
  document.forms["SectionMaker"]["name"].value = "";
  document.forms["SectionMaker"]["page"].selectedIndex = 0;
  document.forms["SectionMaker"]["hideName"].checked = false;
  document.forms["SectionMaker"]["expandable"].checked = false;
  var sRows = document.getElementById("questiontable").rows;
  var i = 0;
  while (i < sRows.length) {
    if (sRows[i].className == "questions" ||
        sRows[i].className == "customOptions") {
      sRows[i].parentNode.removeChild(sRows[i]);
    } else {
      i++;
    }
  }
}

function creat(which) {
  var x = ajaxFunction();
  x.onreadystatechange = pair(x, update_users);
  var path = "questionnaire-writer/write";
  if (which != "Create") {
    var id = document.forms["SectionMaker"]["id"].value;
    path += "/" + id;
  }
  x.open("POST", path);
  x.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  x.setRequestHeader("Connection", "close");
  var postString = "name=" + document.forms["SectionMaker"]["name"].value;
  postString += "&page=" + document.forms["SectionMaker"]["page"].value;
  postString += "&hidden=" + (
      document.forms["SectionMaker"]["hideName"].checked ? "1" : "0");
  postString += "&expandable=" + (
      document.forms["SectionMaker"]["expandable"].checked ? "1" : "0");
  var sRows = document.getElementById("questiontable").tBodies[0].rows;
  var i = 0;
  // Add questions to the POST data.
  while (i < sRows.length) {
    if (sRows[i].className == "questions") {
      postString += "&question" + i + "=" + encodeURIComponent(
          sRows[i].childNodes[1].childNodes[0].value);
      postString += "&questionType" + i + "=" + encodeURIComponent(
          sRows[i].childNodes[3].childNodes[0].value);
    } else if (sRows[i].className == "customOptions") {
      // If there are custom options that should be included, add them to the
      // post data.
      var options = sRows[i].firstChild.firstChild;
      if (options.value !== options.helperText &&
          !options.disabled) {
        postString += "&questionOptions" + (i-1) + "=" + encodeURIComponent(
            options.value);
      }
      sRows[i].parentNode.removeChild(sRows[i]);
      continue;
    }
    i++;
  }

  x.setRequestHeader("Content-length", postString.length);
  x.send(postString);
  clr();
}

function selectSection(id) {
  var x = ajaxFunction();
  x.onreadystatechange = pair([x, id], loadQuestion);
  x.open("GET", "questionnaire-writer/details/" + id);
  x.send(null);
}

function copy_q(id) {
  var x = ajaxFunction();
  x.onreadystatechange = pair(x, update_users);
  var path = "questionnaire-writer/copy/" + id;
  x.open("GET", path);
  x.send(null);
}

function v() {}

function addQuestion(obj) {
  var sRows = obj.parentNode.parentNode.parentNode;
  var row = document.createElement("TR");
  var child;
  row.className = "questions";
  row.appendChild(document.createElement("TH")).innerHTML = "Text:";
  child = row.appendChild(document.createElement("TD"));
  var qText = child.appendChild(document.createElement("INPUT"));
  row.appendChild(document.createElement("TH")).innerHTML = "Type:";
  child = row.appendChild(document.createElement("TD"));
  var questionType = child.appendChild(document.createElement("SELECT"));
  questionType.innerHTML = "<option value=\"0\">Yes/No</option>" +
                           "<option value=\"1\">Long - Short</option>" +
                           "<option value=\"2\">1-10</option>" +
                           "<option value=\"3\">1-5</option>" +
                           "<option value=\"4\">Short Text</option>" +
                           "<option value=\"5\">Large Text</option>" +
                           "<option value=\"6\">Custom Radio</option>" +
                           "<option value=\"7\">Custom Multi</option>";
  questionType.onchange = function() {
    questionTypeChange(row, questionType.value);
  };
  child = row.appendChild(document.createElement("TD"));
  child.innerHTML = "<img src='resources/img/cancel.png' " +
                    "title='Remove Question' onclick='removeQuestion(this)' />";
  sRows.insertBefore(child.parentNode, obj.parentNode.parentNode);
  qText.focus();
  return child.parentNode;
}

function questionTypeChange(row, type) {
  if (type === "6" || type === "7") {
    // Display custom row.
    if (row.nextSibling.className === "customOptions") {
      row.nextSibling.firstChild.disabled = false;
      row.nextSibling.style.display = "";
      return;
    }
    var optionsRow = row.parentNode.insertBefore(document.createElement("TR"),
                                                 row.nextSibling);
    optionsRow.className = "customOptions";
    var cell = optionsRow.appendChild(document.createElement("TD"));
    cell.colSpan = 5;
    var element = cell.appendChild(
        document.createElement("TEXTAREA"));
    element.helperText = 'Answer options, new line delimited.';
    element.onfocus = function() { helperText(this, true); };
    element.onblur = function() { helperText(this, false); };
    helperText(element, false);
    element.style.width = "100%";
    element.rows = 1;
    element.name = "customOptions";
    element.onkeyup = function (e) {
      if (e.keyCode == 13) {
        element.rows = (' ' + element.value).split('\n').length;
      }
    };
  } else {
    // Clear custom row.
    if (row.nextSibling.className !== "customOptions") {
      return;
    }
    row.nextSibling.firstChild.disabled = true;
    row.nextSibling.style.display = "none";
  }
}



function removeQuestion(obj) {
  var row = obj.parentNode.parentNode;
  if (row.nextSibling.className === "customOptions") {
    row.parentNode.removeChild(row.nextSibling);
  }
  row.parentNode.removeChild(row);
}

function update_users(x) {
  if (x.readyState == 4) {
    update_sections(x.responseText);
  }
}

function update_sections(str) {
  var lines = str.split("\n");
  var i = 0;
  document.getElementById("useqs").innerHTML = "";
  while (lines[i] != "" ) {
    document.getElementById("useqs").innerHTML += lines[i++];
  }
  i++;
  document.getElementById("notuseqs").innerHTML = "";
  while (i < lines.length) {
    document.getElementById("notuseqs").innerHTML += lines[i++];
  }
}

function loadQuestion(args) {
  function getSelectionWithId(obj, value) {
    for (var i = 0; i < obj.options.length; ++i) {
      if (obj.options[i].value === value) {
        return i;
      }
    }
    return 0;
  }

  var x = args[0];
  if (x.readyState == 4) {
    var obj = eval("("+x.responseText+")");
    clr();

    document.forms["SectionMaker"]["id"].value = args[1];
    document.forms["SectionMaker"]["name"].value = obj["name"];
    document.forms["SectionMaker"]["page"].selectedIndex = getSelectionWithId(
        document.forms["SectionMaker"]["page"], obj["page"]);
    document.forms["SectionMaker"]["hideName"].checked = obj["hideName"];
    document.forms["SectionMaker"]["expandable"].checked = obj["expandable"];

    var i = 0;
    while (i < obj["questions"].length) {
      var row = addQuestion(document.getElementById("addq"));
      row.childNodes[1].childNodes[0].value = obj["questions"][i][0];
      row.childNodes[3].childNodes[0].selectedIndex = obj["questions"][i][1];
      if (obj["questions"][i][1] >= 6) {
        questionTypeChange(row, "" + obj["questions"][i][1]);
        if (obj["questions"][i].length > 2) {
          var options = row.nextSibling.firstChild.firstChild;
          options.rows = obj["questions"][i].length - 2;
          // Simulate selecting, typing and deselecting.
          helperText(options, true);
          options.value = obj["questions"][i].slice(2).join('\n');
          helperText(options, false);
        }
      }
      i++;
    }

    document.forms["SectionMaker"]["submit"].value="Update";
  }
}

function updateQuestionnairesXmlHTTP(x) {
  if (x.readyState == 4) {
    updateQuestionnaires(x.responseText);
  }
}

function updateQuestionnaires(str) {
  var lines = str.split("\n");
  var i = 0;
  document.getElementById("questionnaires").innerHTML = "";
  while (i in lines && lines[i] != "" ) {
    document.getElementById("questionnaires").innerHTML += lines[i++];
  }
}

function addPage(obj) {
  var sRows = obj.parentNode.parentNode.parentNode;
  var row = document.createElement("TR");
  var child;
  row.className = "page";
  row.appendChild(document.createElement("TH")).innerHTML = "Page:";
  child = row.appendChild(document.createElement("TD"));
  child = child.appendChild(document.createElement("SELECT"));
  for (var i in pages) {
    var option = child.appendChild(document.createElement("OPTION"));
    option.value = i;
    option.innerHTML = pages[i];
  }
  child = row.appendChild(document.createElement("TD"));
  child.innerHTML = "<img src='/resources/img/cancel.png' " +
                    "title='Remove Question' onclick='removeQuestion(this)' />";
  sRows.insertBefore(child.parentNode, obj.parentNode.parentNode);
  return child.parentNode;
}

function clrQuiz() {
  document.forms["QuizMaker"]["submit"].value = "Create";
  document.forms["QuizMaker"]["name"].value = "";
  document.forms["QuizMaker"]["intro"].value = "";
  document.forms["QuizMaker"]["outro"].value = "";
  var sRows = document.getElementById("quiztable").rows;
  var i = 0;
  while (i < sRows.length) {
    if (sRows[i].className == "page") {
      sRows[i].parentNode.removeChild(sRows[i]);
    } else {
      i++;
    }
  }
}

function creatQuiz(which) {
  var x = ajaxFunction();
  x.onreadystatechange = pair(x, updateQuestionnairesXmlHTTP);
  var path = "questionnaire-writer/write_quiz";
  if (which != "Create") {
    var id = document.forms["QuizMaker"]["id"].value;
    path += "/" + id;
  }
  x.open("POST", path);
  x.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  x.setRequestHeader("Connection", "close");
  var postString = "name=" + encodeURIComponent(
      document.forms["QuizMaker"]["name"].value);
  postString += "&intro=" + encodeURIComponent(
      document.forms["QuizMaker"]["intro"].value);
  postString += "&outro=" + encodeURIComponent(
      document.forms["QuizMaker"]["outro"].value);
  var sRows = document.getElementById("quiztable").tBodies[0].rows;
  var i = 0;
  // Add questions to the POST data.
  while (i < sRows.length) {
    if (sRows[i].className == "page") {
      postString += "&page" + i + "=" +
          sRows[i].childNodes[1].childNodes[0].value;
    }
    i++;
  }

  x.setRequestHeader("Content-length", postString.length);
  x.send(postString);
  clrQuiz();
}

function selectQuiz(id) {
  var x = ajaxFunction();
  x.onreadystatechange = pair([x, id], loadQuiz);
  x.open("GET", "questionnaire-writer/details_quiz/" + id);
  x.send(null);
}

function loadQuiz(args) {
  function getSelectionWithId(obj, value) {
    for (var i = 0; i < obj.options.length; ++i) {
      if (obj.options[i].value === value) {
        return i;
      }
    }
    return 0;
  }
  var x = args[0];
  if (x.readyState == 4) {
    var obj = eval("("+x.responseText+")");
    clrQuiz();

    document.forms["QuizMaker"]["id"].value = args[1];
    document.forms["QuizMaker"]["name"].value = obj["name"];
    document.forms["QuizMaker"]["intro"].value = obj["intro"];
    document.forms["QuizMaker"]["outro"].value = obj["outro"];

    var i = 0;
    while (i < obj["pages"].length) {
      var row = addPage(document.getElementById("addp"));
      row.childNodes[1].childNodes[0].selectedIndex = getSelectionWithId(
          row.childNodes[1].childNodes[0], "" + obj["pages"][i]);
      i++;
    }

    document.forms["QuizMaker"]["submit"].value="Update";
  }
}
