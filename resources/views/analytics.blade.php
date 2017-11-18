@extends('app')

@section('content')
    <h1 class="page-header">{{ trans('misc.analytics') }}</h1>
    <table class="table table-striped table-condensed">
        <thead>
        <tr>
            <th>{{ trans('misc.classification') }}</th>
            <th>{{ trans('misc.tickets') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach( $classCounts as $classCount )
            <tr>
                <td>{{ $classCount->name }}</td>
                <td>{{ $classCount->tickets }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <h1 class="page-header">{{ trans('misc.tickets') }}</h1>
    <table class="table table-striped table-condensed">
        <thead>
        <tr>
            <th>{{ trans('misc.period') }}</th>
            <th>{{ trans('misc.new') }}</th>
            <th>{{ trans('misc.updated') }}</th>
        </tr>
        </thead>

        <tbody>

        <tr>
            <th>{{ trans('misc.last_day') }}</th>
            <td>{{ $new['day'] }}</td>
            <td>{{ $updated['day'] }}</td>
        </tr>
        <tr>
            <th>{{ trans('misc.last_seven_days') }}</th>
            <td>{{ $new['week'] }}</td>
            <td>{{ $updated['week'] }}</td>
        </tr>
        <tr>
            <th>{{ trans('misc.last_30_days') }}</th>
            <td>{{ $new['month'] }}</td>
            <td>{{ $updated['month'] }}</td>
        </tr>
        <tr>
            <th>{{ trans('misc.last_365_days') }}</th>
            <td>{{ $new['year'] }}</td>
            <td>{{ $updated['year'] }}</td>
        </tr>
        </tbody>
    </table>

    <div style="height:180px;width=100%;">
    <form class="form col-sm-offset-2 col-sm-8">
        <div class="row">

            <div class="col-sm-4">
                <label for="{{ trans('misc.event') }}" class="control-label">{!! trans('misc.lifecycle') !!}</label>
                {!!  Form::select('s_lifecycle', $lifecycle_options, 'created_at', ['class'=>'form-control', 'id' =>'lifecycle']) !!}
            </div>

            <div class="col-sm-4">
                <label for="{{ trans('misc.from') }}" class="control-label">{!! trans('misc.from') !!}</label>
                {!!  Form::input('date', 's_from', null, ['class' => 'form-control', 'id' => 's_from']) !!}
            </div>
            <div class="col-sm-4">
                <label for="{{ trans('misc.till') }}" class="control-label">{!! trans('misc.till') !!}</label>
                {!!  Form::input('date','s_till', null, ['class' => 'form-control', 'id' => 's_till']) !!}
            </div>


        </div>

        <div class="row">
            <label for="{{ trans('misc.classification') }}"
                   class="control-label col-sm-4">{{ trans('misc.classification') }}</label>
            <label for="{{ trans('misc.type') }}" class="control-label col-sm-4">{{ trans('misc.type') }}</label>
            <label for="{{ trans('misc.status') }}" class="control-label col-sm-4">{{ trans('misc.status') }}</label>
        </div>
        <div class="row">

            <div class="col-sm-4">
                {!!  Form::select('s_class_options', [null=> trans('misc.please_select')] + $class_options, null, ['class'=>'form-control', 'id' =>'classification']) !!}
            </div>
            <div class="col-sm-4">
                {!!  Form::select('s_type', [null=> trans('misc.please_select')] + $type_options, null, ['class'=>'form-control', 'id' => 'type']) !!}
            </div>
            <div class="col-sm-4">
                {!! Form::select('s_status', [null=> trans('misc.please_select')] + $status_options, null, ['class'=>'form-control', 'id'=>'status'])  !!}
            </div>
            <div class="col-sm-4">
                <button type="submit" class="btn btn-primary form-control"
                        id="search_btn"><i class="glyphicon glyphicon-signal"></i> {!! trans('misc.show_graph') !!}</button>
            </div>
        </div>
    </form>
    </div>
    <div id="graph" style="width:100%;height:450px;"></div>
@endsection

@section('extrajs')
    <script src="/js/echarts.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#search_btn').click(function (e) {
                e.preventDefault();
                fetchGraphData();
            });
            $('#search_btn').trigger('click');
        });

        function fetchGraphData() {
            var params = {
                s_from: $("#s_from").val(),
                s_till: $("#s_till").val(),
                s_lifecycle : $("#lifecycle").val(),
                s_classification: $("#classification").val(),
                s_type: $("#type").val(),
                s_status: $("#status").val()
            };

            $.get('analytics/graph', params, function (data) {
                drawGraph(data);
            });
        }

        function transformToDates(data) {
            return data.map(function (item) {
                return [new Date(item[0], item[1], item[2], 12, 0, 0, 0), item[3]];
            });
        }

        function drawGraph(data) {
            var echartBar = echarts.init(document.getElementById('graph'));

            echartBar.setOption({
                title: {
                    text: data.legend,
                    subtext: ''
                },
                tooltip: {
                    trigger: 'axis',
                    formatter: function (params, ticket, callback) {
                        var sText = "";
                        if (params.length) {
                            params.forEach(function (serie) {
                                sText += serie.seriesName + ": " + serie.data[1];
                            });
                        }
                        return sText;
                    }
                },
                legend: {
                    data: ['geopend']
                },
                toolbox: {
                    show: false
                },
                calculable: false,
                xAxis: [{
                    type: 'time',
                }],
                yAxis: [{
                    type: 'value',

                }],
                series: [{
                    name: data.legend,
                    type: 'line',
                    data: transformToDates(data.data),
                }]
            });
        }

    </script>
@endsection
