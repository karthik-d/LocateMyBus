@extends('layouts.common')

@section('file_includes')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script type="text/javascript" src="{{ asset('js/search-bar.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/search_bar.js') }}"></script>
<link rel="stylesheet" href="{{ asset('css/livetrack.css') }}" />
@stop

@section('sidebar_content')
@parent
<div class="sidebar_content">
  <ul>
    <li><a href="/"><span class="sidebar_list">Home</a></li>
    <li><a href="/running-status"><span class="sidebar_list">Live Tracking</a></li>
    <li><a href="/expected-schedule"><span class="sidebar_list">Expected Schedule</a></li>
  </ul>
</div>
@stop

@section('main_content')

<div class="about">
  <span class="route_num">{{$route}}</span>
  <span class="punctuator">-</span>
  <span class="detail">Running Status</span>
</div>

<div class="panel">
  <div class="title"></div>
  <ul class="notification-bar">

    @for($i=0;$i<$stop_crossed;$i++)
      <li class="unread">
          <i class="ion-checkmark"></i>
          <div>
            <span class="stop">{{$stops[$i]}}</span>
            <span class="time_at"></span>
            <span class="time">{{$times[$i]}}</span>
          </div>
      </li>
    @endfor

    @for($i=$stop_crossed;$i<count($stops);$i++)
      <li class="read">
          <i class="ion-plus"></i>
          <div>
            <span class="stop">{{$stops[$i]}}</span>
            <span class="time_at"></span>
            <span class="time">Expected - {{$times[$i]}} </span>
          </div>
      </li>
    @endfor
  </ul>
</div>

@stop
