<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 Forbidden</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/custom.css') }}">
</head>
<body>
    <div class="container" style="margin-top: 40px;">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <h3 class="panel-title">403 Forbidden</h3>
                    </div>
                    <div class="panel-body">
                        <p>{{ isset($message) ? $message : 'You are not authorized to access this resource.' }}</p>
                        <hr>
                        <dl class="dl-horizontal">
                            <dt>Permission</dt>
                            <dd>{{ isset($permission) ? $permission : 'n/a' }}</dd>
                            <dt>Route</dt>
                            <dd>{{ isset($route) ? $route : 'n/a' }}</dd>
                            <dt>URI</dt>
                            <dd>{{ isset($uri) ? $uri : request()->path() }}</dd>
                            {{-- Object intentionally omitted to avoid leaking model identifiers --}}
                        </dl>
                        <a href="{{ url()->previous() }}" class="btn btn-default">Go Back</a>
                        <a href="{{ url('/admin/home') }}" class="btn btn-primary">Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('/js/jquery.min.js') }}"></script>
    <script src="{{ asset('/js/bootstrap.min.js') }}"></script>
</body>
</html>