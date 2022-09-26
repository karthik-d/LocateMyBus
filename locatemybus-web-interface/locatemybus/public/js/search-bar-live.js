
function makeRequestAddress(location){
  var current_location = window.location.href.split('/');
  var request_url = current_location[0]
  console.log(request_url);
  request_url += '//';
  request_url += current_location[1];
  request_url += current_location[2];
  request_url += '/';
  request_url += location;
  return request_url;
}

function getContentTypeFromHeaderString(header){
  var startPos = header.indexOf('content-type')+('content-type').length+1;
  var endPos = header.indexOf('\n', startPos);
  var type = header.slice(startPos, endPos).trim();
  return type;
}

function makeSuggestions(query, search_type, result_id){
  query = query.trim();
  if(query.length==0){
    document.getElementById(result_id).hidden = true;
    document.getElementById(result_id).innerHTML = "";
  }
  else{
    var ajax = new XMLHttpRequest();
    ajax.onreadystatechange = showSuggestions;

    function showSuggestions(){
      if(ajax.readyState==4 && ajax.status==200){
        var responseType = getContentTypeFromHeaderString(ajax.getAllResponseHeaders());
        if(responseType=="application/json"){
          var response = JSON.parse(ajax.responseText);
          var suggests = response.suggestions;
          var values = response.values;
          document.getElementById(result_id).hidden = false;
          document.getElementById(result_id).innerHTML = "";
          if(suggests.length==0){
            document.getElementById(result_id).innerHTML = "<p class='search_suggestion_head'>No Matching Stops</p>";
          }
          else{
            document.getElementById(result_id).innerHTML = "<p class='search_suggestion_head'>Search Suggestions</p>";
          }
          for(i=0;i<suggests.length;i++){
            handlerFunc = 'handle'+search_type;
            document.getElementById(result_id).appendChild(makeButton(suggests[i], values[i], handlerFunc));
            document.getElementById(result_id).innerHTML += "<br />";
            document.getElementById(result_id).style.border = "1px solid #A5ACB2";
          }
        }
      }
    }

    var request_url = makeRequestAddress('search-suggestions');
    var body = {"search_id": "stop_name",
                "query": query
              };
    var token = document.querySelector('meta[name=csrf-token]').content;
    ajax.open("PATCH", request_url, true);
    ajax.setRequestHeader('X-CSRF-TOKEN', token);
    ajax.setRequestHeader('content-type', 'application/json');
    ajax.send(JSON.stringify(body));
  }
}

function clearSearch(event, result_id, input_id){
  if(event.relatedTarget && event.relatedTarget.className=='search_suggestion'){
    ;
  }
  else{
    var target_div = document.getElementById(result_id);
    target_div.hidden = true;
    document.getElementById(input_id).value = "";
    if(input_id=="bysource_input"){
      source_value = "";
    }
    else if(input_id=="bydestn_input"){
      destn_value = "";
    }
  }
}

function makeSearchResults(origin, destination){
  var ajax = new XMLHttpRequest();
  ajax.onreadystatechange = getSearchResults;

  function getSearchResults(){
    if(ajax.readyState==4 && ajax.status==200){
      var responseType = getContentTypeFromHeaderString(ajax.getAllResponseHeaders());
      if(responseType=="application/json"){
        var response = JSON.parse(ajax.responseText);
        var routes = response.routes;
        var trip_ids = response.trip_ids;
        var origins = response.origins;
        var destns = response.destinations;
        var section = document.getElementById('results_section');
        if(routes.length==0){
          makeNoResultSection(section);
        }
        else{
          for(i=0;i<routes.length;i++){
            makeResultsSection(section, routes[i], origins[i], destns[i], trip_ids[i], !i);
          }
        }
        section.hidden = false;
      }
    }
  }
  var request_url = makeRequestAddress('search-results-live');
  var body = {"search_type": "trips",
              "origin": origin,
              "destination": destination
            };
  var token = document.querySelector('meta[name=csrf-token]').content;
  ajax.open("POST", request_url, true);
  ajax.setRequestHeader('X-CSRF-TOKEN', token);
  ajax.setRequestHeader('content-type', 'application/json');
  ajax.send(JSON.stringify(body));
}
