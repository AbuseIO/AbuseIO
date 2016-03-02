@extends('app')

@section('content')
<h1 class="page-header">{{ trans('netblocks.header.edit') }}</h1>
{!! Form::model($netblock, ['method' => 'PATCH', 'route' => ['admin.netblocks.update', $netblock->id], 'class' => 'form-horizontal']) !!}
{!! Form::hidden('id', $netblock->id) !!}
@include('netblocks/partials/_form', ['submit_text' => trans('misc.button.save')])
{!! Form::close() !!}
@endsection
