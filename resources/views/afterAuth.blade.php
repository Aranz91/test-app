<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TEST</title>
    <link rel="stylesheet" href="https:stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <style type="text/css">
      body{
        background-color: #121212;
      }
      
      .card {
        background-color: #1d1d1d;
      }
      
      .login-form {
        padding: 40px;
      }

      a.btn {
        line-height: 1;
      }
    </style>
</head>
    <body>
        <main class="login-form">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <form id="form" action="{{ route('redirect') }}" method="GET">
                                    @csrf
                                    <a href="#" onclick="getLocation()" class="btn btn-secondary">
                                        <svg xmlns="http:www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-google" viewBox="0 0 16 16">
                                            <path d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.689 7.689 0 0 1 5.352 2.082l-2.284 2.284A4.347 4.347 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.792 4.792 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.702 3.702 0 0 0 1.599-2.431H8v-3.08h7.545z"/>
                                        </svg>
                                    </a>
                                    <p id="demo"></p>
                                    <input type="hidden" name="lat" id="lat" value="">
                                    <input type="hidden" name="lon" id="lon" value="">
                                </form>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <script>
            var x = document.getElementById("lat");
            var y = document.getElementById("lon");
            getLocation();
            function getLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(showPosition);
                } else {
                   
                }
            }

            function showPosition(position) {
                x.value = position.coords.latitude;
                y.value = position.coords.longitude;
            }
        </script>
    </body>
</html>