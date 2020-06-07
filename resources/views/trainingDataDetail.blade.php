@extends('layouts.app')

<script src="/echarts/build/source/echarts.js" type="text/javascript"></script>

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
            @elseif (session('failed'))
            <div class="alert alert-danger" role="alert">
                {{ session('failed') }}
            </div>
            @endif
        </div>
    </div>

    <div class="row justify-content-center">

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Training report
                </div>

                <div class="row justify-content-around mt-3">
                    <a href="/{{ $training_data->csv_path }}" class="btn btn-primary"> Download CSV file </a>
                    <a href="/home" class="btn btn-primary"> Go back </a>
                </div>

                <div class="card-body text-center">

                    <div id="div1" style="width:100%;height:400px;border:1px solid #dddddd;;"></div>

                </div>
            </a>
        </div>
    </div>

    <script type="application/javascript">
        require.config({
            paths: {
                echarts: '/echarts/build/dist' //引用资源文件夹路径，注意路径
            }
        });
        require(
            [
                'echarts'
                , 'echarts/chart/line' // 按需加载所需图表，用到什么类型就加载什么类型，这里不需要考虑路径        
            ]
            , function(ec) {
                var myChart = ec.init(document.getElementById('div1'));
                var ecConfig = require('echarts/config');
                var option = {
                    title: {
                        text: 'Stretch angle'
                        , x: 'center'
                    }
                    , tooltip: {
                        trigger: 'axis'
                    }
                    , legend: {
                        data: ['Thumb', 'Forefinger', 'Middle finger', 'Ring finger', 'Little finger']
                        , y: "bottom"
                    }
                    , toolbox: {
                        show: true, //是否显示工具箱
                        feature: {
                            mark: {
                                show: true
                            }
                            , dataView: {
                                show: true
                                , readOnly: false
                            }
                            , magicType: {
                                show: true
                                , type: ['line', 'bar', 'stack', 'tiled']
                            }
                            , restore: {
                                show: true
                            }
                            , saveAsImage: {
                                show: true
                            }
                        }
                    },
                    //calculable: true,    容易搞错的属性，折线图、柱状图是否叠加
                    xAxis: [{
                        type: 'category'
                        , boundaryGap: false
                        , data: [
                            @for ($i = 0; $i < count($csv)-1; $i++)
                            {{ $csv[$i+1][0] }}+'s',
                            @endfor
                        ]
                    }]
                    , yAxis: [{
                        type: 'value'
                    }]
                    , series: [{
                            name: 'Thumb'
                            , type: 'line'
                            //, stack: '平铺'
                            , data: [
                                @for ($i = 0; $i < count($csv)-1; $i++)
                                {{ $csv[$i+1][1] }},
                                @endfor
                            ]
                        }
                        , {
                            name: 'Forefinger'
                            , type: 'line'
                            //, stack: '平铺'
                            , data: [
                                @for ($i = 0; $i < count($csv)-1; $i++)
                                {{ $csv[$i+1][2] }},
                                @endfor
                            ]
                        }
                        , {
                            name: 'Middle finger'
                            , type: 'line'
                            //, stack: '平铺'
                            , data: [
                                @for ($i = 0; $i < count($csv)-1; $i++)
                                {{ $csv[$i+1][3] }},
                                @endfor
                            ]
                        }
                        , {
                            name: 'Ring finger'
                            , type: 'line'
                            //, stack: '平铺'
                            , data: [
                                @for ($i = 0; $i < count($csv)-1; $i++)
                                {{ $csv[$i+1][4] }},
                                @endfor
                            ]
                        }
                        , {
                            name: 'Little finger'
                            , type: 'line'
                            //, stack: '平铺'
                            , data: [
                                @for ($i = 0; $i < count($csv)-1; $i++)
                                {{ $csv[$i+1][5] }},
                                @endfor
                            ]
                        }
                    ]
                };
                myChart.setOption(option);
            }
        );

    </script>
    @endsection
