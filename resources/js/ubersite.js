var currentPage = window.location.pathname;

var index = currentPage.lastIndexOf("/") + 1;
var filename = currentPage.substr(index);

if (filename == "awards.php") {
  var oldValue = false;
  $(awards_init);
}

function awards_selectAward(newID) {
  if (oldValue !== false) {
    document.getElementById('award'+oldValue).style.display = 'none';
  }
  if (newID == "none") {
    oldValue = false;
  } else {
    document.getElementById('award'+newID).style.display = '';
    oldValue = newID;
  }
}

function awards_selectPerson(category, newID) {
  if (newID == "none") {
    document.getElementById('photo'+category).src = "/resources/img/no-pic-thumb.jpg";
    document.getElementById('submit'+category).style.display = 'none';
  } else {
    document.getElementById('photo'+category).src = "/campData/profiles/"+newID+"-thumb.jpg";
    document.getElementById('submit'+category).style.display = '';
  }
}

function awards_init() {
  var newValue = document.getElementById('categorySelector').value;
  if (newValue != "none") {
    oldValue = document.getElementById('categorySelector').value;
  }
}


function pegosaurus_new() {
  $("#link").hide();
  $("#new").show();
}

function photo_tag(obj) {
  $("#tagText").toggle();
  $("#tagInput").toggle();
}

function photo_untag() {
  $("#tagText").toggle();
  $("#untagInput").toggle();
}

function photo_submit(field, variable) {
  var keyCode;

  if (window.event) {
    keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
  } else if (variable) {
    keyCode = variable.which;
  }

  if (keyCode == 13) {
    $("#tagForm").submit();
  }
}

function profile_clear() {
  var textarea = $("#facts");
  if (textarea.css('fontFamily') != "monospace") {
    textarea.val("");
    textarea.css('color', "#000000");
    textarea.css('fontFamily', "monospace");
  }
}


function questionnaire_toggle(obj, type) {
  par = obj.parentNode;
  expand = (par.style.height != "auto");

  if(expand) {
    obj.innerHTML = "Minimise this box:";
    par.style.height = "auto";
  } else {
    obj.innerHTML = "Did this elective, click to expand:";
    par.style.height = "15px";
  }
}

function quotes_multiple() {
  $("#selectionRow").hide();
  $("#none").attr("selected", "selected");
}

function quotes_single() {
  $("#selectionRow").show();
}

function helperText(obj, focus) {
  if (focus && obj.className.indexOf('helper_text') !== -1) {
    obj.value = "";
    obj.className = obj.className.replace(/ ?helper_text/, "");
    return;
  }
  if (!focus && obj.value === "") {
    obj.value = obj.helperText;
    if (obj.className.indexOf('helper_text') === -1) {
      obj.className += ' helper_text';
    }
  }
}

function ajaxFunction() {
  var xmlHttp;
  try {
    // Firefox, Opera 8.0+, Safari
    xmlHttp=new XMLHttpRequest();
  } catch (e) {  // Internet Explorer
    try
    {
      xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
      try {
        xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
      }
      catch (e) {
        alert("Your browser does not support AJAX!");
        return false;
      }
    }
  }
  return xmlHttp;
}

function Ajax(f) {
  this.xhttp = ajaxFunction();
  this.xhttp.onreadystatechange = pair(this.xhttp, f);
  this.get = function(path) {
    this.xhttp.open("GET", path);
    this.xhttp.send(null);
  };

  this.post = function(path, params) {
    if (params === undefined) {
      params = {};
    }
    var parts = [];
    for (var name in params) {
      parts.push(
          encodeURIComponent(name) + '=' + encodeURIComponent(params[name]));
    }
    this.xhttp.open('POST', path);
    this.xhttp.setRequestHeader('Content-type',
                                'application/x-www-form-urlencoded');
    this.xhttp.setRequestHeader('Connection', 'close');
    var postString = parts.join('&');
    this.xhttp.setRequestHeader('Content-length', postString.length);
    this.xhttp.send(postString);
  };
}

function pair(x, f) {
  return function() {
    f(x);
  };
}

function UberButton(obj, url) {
  this.obj = obj;
  this.countEl = obj.getElementsByClassName('count')[0];
  this.url = url;
  this.ubered = false;
  this.count = 0;
  this.people = [];
  this.mouseOver = obj.appendChild(document.createElement('DIV'));
  this.mouseOver.style.display = 'none';
  this.mouseOver.className = 'uberMouseOver';
  this.mouseOver.onclick = function(e) {
    if (e) e = window.event;
    e.cancelBubble = true;
    e.stopPropagation();
  };
  this.timeout = null;

  this.display = function() {
    this.obj.className = 'uber' + (this.ubered ? 'ed' : '');
    this.countEl.innerHTML = '' + this.count;
    var mouseOver = '';
    if (this.count) {
      if (this.count === 1 && !this.ubered) {
        mouseOver = this.people + ' thinks';
      } else {
        mouseOver = this.people + ' think';
      }
      mouseOver += ' this is &uuml;ber.';
    } else {
      mouseOver = 'My mum still thinks I&apos;m cool.';
    }
    this.mouseOver.innerHTML = mouseOver;
  }

  this.uber = function() {
    new Ajax(this.loadChange).post('/uber' + this.url,
                                   {'uber': this.ubered ? '0' : '1'});
  };

  this.loadChange = (function (obj) {
    return function(xmlHttp) {
      if (xmlHttp.readyState === 4) {
        var state = eval('(' + xmlHttp.responseText + ')');
        obj.ubered = state.ubered;
        obj.count = state.count;
        obj.people = state.people;
        obj.display();
      }
    };
  })(this);

  this.obj.onclick = (function (obj) {
    var onclick = function() {
      obj.uber();
    };
    return onclick;
  })(this);

  this.obj.onmouseover = (function (obj) {
    return function() {
      if (obj.timeout) {
        clearTimeout(obj.timeout);
        obj.timeout = null;
      }
      var p = $(obj.obj);
      var offset = p.offset();
      obj.mouseOver.style.left = offset.left + 'px';
      obj.mouseOver.style.top = (offset.top + p.outerHeight()) + 'px';
      obj.mouseOver.style.display = 'block';
    };
  })(this);

  this.obj.onmouseout = (function (obj) {
    return function() {
      if (obj.timeout) clearTimeout(obj.timeout);
      obj.timeout = setTimeout(function() {
        obj.mouseOver.style.display = 'none';
      }, 200);
    };
  })(this);

  new Ajax(this.loadChange).get('/uber' + this.url);
}
