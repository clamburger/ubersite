var processor = processor || {};

processor.filename = null;
processor.count = 0;

processor.loadPhoto = function(filename) {
  if (processor.filename) {
    document.getElementById(processor.filename).className =
        document.getElementById(processor.filename).className.replace(
            ' selected', '');
  }
  processor.filename = filename;
  document.getElementById('currentPhoto').src = '/uploads/' + filename;
  document.getElementById(filename).className += ' selected';
  // Load metadata.
  new Ajax(processor.getEvent).get('/kindreds-lab.php/event/' + filename);
  document.getElementById('people').focus();
};

processor.getEvent = function(xmlhttp) {
  if (xmlhttp.readyState === 4) {
    if (xmlhttp.status === 200) {
      document.getElementById('events').selectedIndex = parseInt(
          xmlhttp.responseText);
    }
  }
};


processor.handle = function(xmlhttp) {
  if (xmlhttp.readyState === 4) {
    if (xmlhttp.status !== 200) {
      // Display error.
      document.getElementById('error').innerHTML = xmlhttp.responseText;
    } else {
      var state = eval('(' + xmlhttp.responseText + ')');
      document.getElementById('counter').innerHTML =
          'You have ' + state.count + ' photo' + (state.count == 1 ? '' : 's') +
          ' to review.';
      if (state.count) {
        processor.loadPhoto(state.photos[0].filename);
      }
      // Potentially reload available photos.
      if (state.count != processor.count) {
        processor.count = state.count;
        // Reload all.
        var holder = document.getElementById('toprocess');
        holder.innerHTML = '';
        for (var i in state.photos) {
          var p = holder.appendChild(document.createElement('DIV'));
          p.className = state.photos[i].class;
          p.id = state.photos[i].filename;
          p.onclick = function() { processor.loadPhoto(p.id) };
          var img = p.appendChild(document.createElement('IMG'));
          img.src = '/uploads/' + p.id;
          img.height = 200;
          img.width = 320;
        }
      }
    }
  }
};

processor.publish = function() {
  if (document.getElementById(processor.filename).className === 'photoFrame selected') {
    --processor.count;
  }
  document.getElementById(processor.filename).className = 'photoFrame publish';
  // Call the processing function with the current filename.
  var req = new Ajax(processor.handle);
  // Parse tags and event, post that.
  var events = document.getElementById('events');
  var event = events[events.selectedIndex].text;

  var names = document.getElementById('people');
  var tags = [];
  for (var i = 0; i < names.length; ++i) {
    if (processor.ids[names[i]]) {
      tags.pushback(processor.ids[names[i]]);
    }
  }
  // Send, the result will be the next filename.
  req.post('/kindreds-lab.php/publish/' + processor.filename, {
    'event': event,
    'people': tags.join(',')
  });
};

processor.publishRest = function() {
  // First publish the current one.
  publish();
  for (var i in elements) {
    if (elements[i].className === 'img') {
      elements[i].className = 'img publish';
    }
  }
  // Call the function to publish the rest.
  new Ajax(processor.handle).post('/kindreds-lab.php/publishrest');
};

processor.trash = function() {
  if (document.getElementById(processor.filename).className === 'photoFrame selected') {
    --processor.count;
  }
  document.getElementById(processor.filename).className = 'photoFrame trash';
  // Call the trash function for the current filename.
  // Loads the next file.
  new Ajax(processor.handle).post(
      '/kindreds-lab.php/trash/' + processor.filename);
};

processor.finalise = function() {
  // Call the finalise function.
  new Ajax(processor.handle).post('/kindreds-lab.php/finalise');
};

processor.search = function(obj) {
  // Get the working line.
  // Find the first value >= entered.
  // Display next + all ones that are substrs.
};
