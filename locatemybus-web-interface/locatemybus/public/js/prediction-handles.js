source_value = "";
destn_value = "";
date_value = "";
var months = {1:"Jan", 2:"Feb", 3:"Mar", 4:"Apr", 5:"May", 6:"June", 7:"July", 8:"Aug", 9:"Sept", 10:"Oct", 11:"Nov", 12:"Dec"};

function dateWordsFromDate(date){
  date = date.replace(/-/g, " ");
  date = date.slice(8,10) + " " + months[parseInt(date.slice(5,7))] + " " + date.slice(0,4);
  document.getElementById('date-detail').innerHTML = date;
}

function dateToUrlString(date){
  var parts = date.split("-");
  var date = "";
  for(prt in parts){
    date += parts[prt];
  }
  return date;
}

function makeResultsSection(section, route_id, source, destn, trip_id, hr=false, is_running=false, start_time=null, date=null){

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

  if(is_running){
    var request_url = makeRequestAddress('running-status/'+trip_id.trim());
  }
  else{
    var request_url = makeRequestAddress('expected-schedule/'+trip_id.trim()+'/'+dateToUrlString(date))
  }
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
  if(is_running){
    para.setAttribute('class', 'result_status_content_run');
    para.innerHTML = 'Running';
  }
  else{
    para.setAttribute('class', 'result_status_content_notrun');
    para.innerHTML = 'Scheduled at '+start_time;
  }
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
  para.innerHTML = 'No Direct Trips Between These Stops';
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
  console.log(date_value);
  if(document.getElementById('bydestn_input').value && date_value){
    makeSearchResults(value, destn_value, date_value);
  }
}

function handleDestnStop(value, text){
  document.getElementById('bydestn_result').hidden = true;
  document.getElementById('bydestn_input').value = text;
  document.getElementById('results_section').innerHTML = ""; // CLear Old Results
  destn_value = value;
  console.log(source_value);
  console.log(value);
  console.log(date_value);
  if(document.getElementById('bysource_input').value && date_value){
    makeSearchResults(source_value, value, date_value);
  }
}

function handleDate(){
  var dateObj = new Date();
  var today = dateObj.getFullYear()+'-'+('0'+(dateObj.getMonth()+1)).slice(-2)+'-'+('0'+dateObj.getDate()).slice(-2);
  var date = document.getElementById('traveldate_input');
  document.getElementById('results_section').innerHTML = ""; // CLear Old Results
  if(!date.value || date.value<today){  // Set to null if entered date is invalid
    date_value = null;
    console.log(source_value);
    console.log(destn_value);
    console.log(date_value);
    return
  }
  date_value = date.value;
  console.log(source_value);
  console.log(destn_value);
  console.log(date_value);
  if(document.getElementById('bysource_input').value && document.getElementById('bydestn_input').value){
    makeSearchResults(source_value, destn_value, date_value);
  }
}
