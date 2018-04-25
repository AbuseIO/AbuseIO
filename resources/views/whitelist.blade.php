@extends('app')

@section('content')


<h1 class="page-header">{{ trans('misc.whitelist') }}</h1>
<div class="row">
  <div class="col-md-6">
    <label>
      {{ trans('whitelist.header.add_field') }}
      <input type="search" class="form-control input-sm" id="add_another_input">
      </input>
    </label>
    <a href="#" class="btn btn-info" id="add_another">
      {{ trans('whitelist.button.add') }}
    </a>
  </div>
  <div class="col-md-6">
    <a id="submit_whitelist" class="btn btn-success pull-right">
      <i class="glyphicon glyphicon-upload"></i>
      {{ trans('whitelist.button.update') }}
    </a>
  </div>
</div>
{!! Form::open(['id' => 'whitelist_form'])!!}
<table class="table table-striped" id="whitelist-table" align="center">
  <thead>
  <tr>
    <th>IP Address/Subnet</th>
  </tr>
  </thead>
  <tbody>
    @foreach($whitelist as $key)
      <tr>
        <td> {{$key}} </td>
        {!! Form::hidden($key) !!}
        <td class="text-right">
          <button type="submit" class="btn btn-danger btn-xs delete-btn">
            <i class="glyphicon glyphicon-remove"></i>
            {{ trans('misc.button.delete') }}
          </button>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>

{!! Form::close() !!}
@endsection

@section('extrajs')
<script>
  $(function(){
    $("#submit_whitelist").click(function(){
      $("#whitelist_form").submit();
    });

    $(document).on("click", ".delete-btn", function(e){
      e.preventDefault();
      $(this).closest('tr').remove();
    });

    function add_table_row(){
      var $new_ip = $("#add_another_input").val();

      // Loose sanity check to prevent invalid IPv4 addresses or CIDRs
      // Backend does its own validation anyway.
      if (isIPv4($new_ip) || isIPv4CIDR($new_ip)){
        var $new_row=`<tr>
                    <td>`+ $new_ip +`</td>
                    <input type="hidden" name=`+ $new_ip +`>
                    <td class="text-right">
                      <button type="submit" class="btn btn-danger btn-xs delete-btn">
                        <i class="glyphicon glyphicon-remove"></i>
                        {{ trans('misc.button.delete') }}
                      </button>
                    </td>
                  </tr>`
        $("#whitelist-table tbody").append($new_row);
        // Clear input field's value
        $("#add_another_input").val('');
      }
    };

    $("#add_another").click(add_table_row);

    $("#add_another_input").keypress(function(e){
      // If enter is pressed add another row
      if (e.which == 13){
        add_table_row();
      }
    })
  });

  function isIPv4CIDR($cidr){
    var $tokens = $cidr.split('/');
    if ($tokens.length == 2 && isIPv4($tokens[0]) && ($tokens[1] >= 8 && $tokens[1] <= 32)){
      return true;
    }
    return false;
  };

  function isIPv4($ip){
    if (/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/.test($ip)) {
      return true;
    }
    return false;
  };
</script>
@endsection
