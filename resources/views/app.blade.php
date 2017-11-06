@include('layout.header')
@include('layout.navbar')
<div class="container-fluid">
    <div class="row">
        @include('layout.sidemenu')
        <main class="col-sm-9 ml-sm-auto col-md-10 pt-3" role="main">
            @include('layout.maincontent')
        </main>
    </div>
</div>
@include('layout.footer')
