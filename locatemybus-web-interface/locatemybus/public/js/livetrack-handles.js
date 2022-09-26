// The stop ids when valid value is set
var source_value = "";
var destn_value = "";

function makeResultsSection(section, route_id, source, destn, trip_id, hr=false){

  var main_div = document.createElement("DIV");
  main_div.setAttribute('class', 'result_listing');

  if(hr){
    var hr = document.createElement("HR");
    hr.setAttribute('class', 'result_separator');
    main_div.appendChild(hr);
    var para = document.createElement("P");
    para.setAttribute('class', 'results_head');
    para.innerHTML = 'Search Results';
    main_div.appendChild(para);
    main_div.appendChild(hr);
  }

  var request_url = makeRequestAddress('running-status/'+trip_id.trim())
  var anchor = document.createElement('A');
  anchor.setAttribute('class', 'livetrack_link');
  anchor.setAttribute('href', request_url);
  var sub_div = document.createElement("DIV");
  sub_div.setAttribute('class', 'result');
  var div = document.createElement("DIV");
  div.setAttribute('class', 'result_route');
  para = document.createElement("P");
  para.setAttribute('class', 'result_route');
  para.innerHTML = route_id;
  div.appendChild(para);
  sub_div.appendChild(div);

  div = document.createElement("DIV");
  div.setAttribute('class', 'result_stops');
  para = document.createElement("P");
  para.setAttribute('class', 'result_source');
  para.innerHTML = source;
  div.appendChild(para);
  para = document.createElement("P");
  para.setAttribute('class', 'result_source_to');
  para.innerHTML = 'TO';
  div.appendChild(para);
  para = document.createElement("P");
  para.setAttribute('class', 'result_destn');
  para.innerHTML = destn;
  div.appendChild(para);
  sub_div.appendChild(div);

  div = document.createElement("DIV");
  div.setAttribute('class', 'result_status');
  para = document.createElement("P");
  para.setAttribute('class', 'result_status_label');
  para.innerHTML = 'Status:';
  div.appendChild(para);
  para = document.createElement("P");
  para.setAttribute('class', 'result_status_content');
  para.innerHTML = 'Running';
  div.appendChild(para);
  sub_div.appendChild(div);

  anchor.appendChild(sub_div);
  main_div.appendChild(anchor);
  section.appendChild(main_div);

  return true;
}

function makeNoResultSection(section){
  var div = document.createElement("DIV");
  div.setAttribute('class', 'no_result');
  para = document.createElement("P");
  para.setAttribute('class', 'no_result');
  para.innerHTML = 'No Running Trips In This Route';
  div.appendChild(para);
  section.appendChild(div);
}

function makeButton(text, value, handlerFunc){
  var args = "(this.value,'"+text+"')";
  var button = document.createElement("BUTTON");
  button.innerHTML = text;
  button.setAttribute('class', 'search_suggestion');
  button.setAttribute('type', 'button');
  button.setAttribute('value', value);
  button.setAttribute('tabindex', 0);
  button.setAttribute('onclick', handlerFunc+args);
  return button;
}

/*function handleRouteId(value, text){
  //Close the search result div
  document.getElementById('byroute_result').hidden = true;
  document.getElementById('byroute_input').value = text;
}*/

function handleSrcStop(value, text){
  document.getElementById('bysource_result').hidden = true;
  document.getElementById('bysource_input').value = text;
  document.getElementById('results_section').innerHTML = ""; // CLear Old Results
  source_value = value;
  console.log(value);
  console.log(destn_value);
  if(document.getElementById('bydestn_input').value){
    makeSearchResults(value, destn_value);
  }
}

function handleDestnStop(value, text){
  document.getElementById('bydestn_result').hidden = true;
  document.getElementById('bydestn_input').value = text;
  document.getElementById('results_section').innerHTML = ""; // CLear Old Results
  destn_value = value;
  console.log(source_value);
  console.log(value);
  if(document.getElementById('bysource_input').value){
    makeSearchResults(source_value, value);
  }
}
