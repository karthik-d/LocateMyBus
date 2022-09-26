<html>

  <head>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
	<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    @yield('file_includes')
	</head>

  <body>
    <div id="header">
      @section('header_content')
    </div>
    <div class="wrapper">
      <div class="main">
        @yield('main_content')
      </div>
      <div class="sidebar">
        <div class="sidebar_title">
          <span class="sidebar_title">Options</span>
        </div>
        @yield('sidebar_content')
      </div>
    </div>
    </div>
  </body>

</html>
