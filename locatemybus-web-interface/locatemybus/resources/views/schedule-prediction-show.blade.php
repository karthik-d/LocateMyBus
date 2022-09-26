@extends('layouts.common')

@section('file_includes')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script type="text/javascript" src="{{ asset('js/prediction-handles.js') }}"></script>
<link rel="stylesheet" href="{{ asset('css/prediction.css') }}" />
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
  <span class="detail">Expected Schedule</span>
  <span class="punctuator">-</span>
  <span class="detail" id="date-detail">{!!$date!!}</span>
  <script>dateWordsFromDate(document.getElementById('date-detail').innerHTML)</script>
</div>

<div class="panel">
  <div class="title"></div>
  <ul class="notification-bar">

    @for($i=0;$i<count($stops);$i++)
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
