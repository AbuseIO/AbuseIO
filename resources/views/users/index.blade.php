{{--
  -- Show Users Index
  --}}
@extends('app')

@section('content')
    {{-- Draw the title and menu on the right side --}}
    @include('layout.components.pageheader', [
        'title' => uctrans('users.user', 2),
        'menu' => [
            [
                'route' => route('admin.users.create'),
                'class' => 'btn-primary',
                'icon' => 'add',
            ]
        ]
    ])
    @if ( !$users->count() )
        <div class="alert alert-primary top-buffer"><span class="fa fa-exclamation-circle"></span> {{ trans('users.no_users')}}</div>
    @else
        <div class="row top-buffer">
            @foreach($users as $user)
                @include('layout.components.usercard')
            @endforeach
        </div>
    @endif
    {{-- We need this modal for confirming delete requests --}}
    @include('layout.components.modals.confirmdelete', ['route' => 'admin.users.destroy' ])
@endsection

@section('extrajs')
    <script type="text/javascript">
        $('#btnResetSubmit').click(function() {
            clearSearchForm();
        });

        $('#dropdown-menu').find('select').dropdown();
    </script>
@stop
