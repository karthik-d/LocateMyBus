@extends('layouts.common')

@section('sidebar_content')
@parent
<div class="sidebar_content">
  <ul>
    <li><a href="running-status"><span class="sidebar_list">Live Tracking</a></li>
    <li><a href="expected-schedule"><span class="sidebar_list">Expected Schedule</a></li>
  </ul>
</div>
@stop

@section('main_content')
  {{$var}}
  <p>This is my body content.</p>
@stop
