@extends('layouts.common')

@section('file_includes')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script type="text/javascript" src="{{ asset('js/search-bar-live.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/livetrack-handles.js') }}"></script>
<link rel="stylesheet" href="{{ asset('css/running-search.css') }}" />
@stop

@section('sidebar_content')
@parent
<div class="sidebar_content">
  <ul>
    <li><a href="/"><span class="sidebar_list">Home</a></li>
    <li><a href="expected-schedule"><span class="sidebar_list">Expected Schedule</a></li>
  </ul>
</div>
@stop

@section('main_content')
  <div class="about">
    <span class="detail">Track Running Trips</span>
  </div>

  <form id="search_by_stops">
  <div class="livesearch">
    <input class="searchbar" id="bysource_input"
      type="text"
      placeholder="Source Stop..."
      onkeyup="makeSuggestions(this.value, 'SrcStop', 'bysource_result');"
      onfocusout="clearSearch(event, 'bysource_result', 'bysource_input');"
      autocomplete="off" />
  </div>
  <div class="search_results" id="bysource_result" hidden='true'></div>
  <div class="livesearch">
    <input class="searchbar" id="bydestn_input"
      type="text"
      placeholder="Destination Stop..."
      onkeyup="makeSuggestions(this.value, 'DestnStop', 'bydestn_result');"
      onfocusout="clearSearch(event, 'bydestn_result', 'bydestn_input');"
      autocomplete="off" />
  </div>
  <div class="search_results" id="bydestn_result" hidden='true'></div>
  </form>

  <section class="result_listing" id="results_section" hidden>
  </section>

@stop
