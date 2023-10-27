<!-- BEGIN: main -->
<script type="text/javascript" src="{NV_STATIC_URL}{NV_ASSETS_DIR}/js/chart/chart.min.js"></script>



<div class="container">
    <ul class="nav nav-tabs">
      <li class="active"><a data-toggle="tab" href="#month">Tháng/Năm</a></li>
      <li><a data-toggle="tab" href="#year">Theo năm</a></li>
    </ul>
  
    <div class="tab-content">
      <div id="month" class="tab-pane fade in active">
        <!-- BEGIN: month -->
        <div class="well">
            <form action="{NV_BASE_ADMINURL}index.php" method="get">
                <input type="hidden" name="{NV_LANG_VARIABLE}" value="{LANG_DATA}" />
                <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
                <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
        
                <div class="row">
                    <div class="col-xs-12 col-md-3">
                        <div class="form-group">
                            <label><strong>{LANG.search_statistical_year}</strong></label>
                            <input class="form-control" type="number" maxlength="4" min="{year_min}" max="{year_max}" value="{Y}" maxlength="64" name="y"/>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-24">
                        <div class="form-group">
                            <input class="btn btn-primary" type="submit" value="{LANG.search}" />
                        </div>
                    </div>
                </div>
                <label><em>Tìm từ năm {year_min} đến năm hiện tại</em></label>
            </form>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading"><i class="fa fa-fw fa-line-chart"></i>Thống kê tháng theo năm {CTS.caption}</div>
            <div class="panel-body">
                <canvas id="canvas_month"></canvas>
            </div>
            <div class="panel-footer">
                <div class="text-right">
                    {CTS.total.0}: <strong>{CTS.total.1}</strong>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            var config_month = {
                type: 'line',
                data: {
                    labels: [{DATA_LABEL}],
                    datasets: [{
                        label: "",
                        backgroundColor: 'rgb(54, 162, 235)',
                        borderColor: 'rgb(54, 162, 235)',
                        data: [{DATA_VALUE}],
                        fill: false,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                title: function(tooltipItems) {
                                    return config_month['data']['labels'][tooltipItems[0].parsed.x]
                                },
                                label: function(context) {
                                    var label = ' {LANG.add_statistical_times}: ';
                                    if (context.parsed.y !== null) {
                                        label += context.parsed.y;
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            display: true,
                            title: {
                                display: false
                            }
                        },
                        y: {
                            display: true,
                            title: {
                                display: true,
                                text: '{LANG.add_statistical_times}'
                            }
                        }
                    }
                }
            };
            $(function() {
                new Chart(document.getElementById("canvas_month").getContext("2d"), config_month);
            });
        </script>
        <!-- END: month -->        

      </div>
      <div id="year" class="tab-pane fade">
        <!-- BEGIN: year -->
        <div class="panel panel-default">
            <div class="panel-heading"><i class="fa fa-fw fa-line-chart"></i>Thống kê từ năm {CTS.caption}</div>
            <div class="panel-body">
                <canvas id="canvas_year"></canvas>
            </div>
            <div class="panel-footer">
                <div class="text-right">
                    {CTS.total.0}: <strong>{CTS.total.1}</strong>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            var config_year = {
                type: 'line',
                data: {
                    labels: [{DATA_LABEL}],
                    datasets: [{
                        label: "",
                        backgroundColor: 'rgb(54, 162, 235)',
                        borderColor: 'rgb(54, 162, 235)',
                        data: [{DATA_VALUE}],
                        fill: false,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                title: function(tooltipItems) {
                                    return '{LANG.statistical_year}: ' + config_year['data']['labels'][tooltipItems[0].parsed.x]
                                },
                                label: function(context) {
                                    var label = ' {LANG.add_statistical_times}: ';
                                    if (context.parsed.y !== null) {
                                        label += context.parsed.y;
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            display: true,
                            title: {
                                display: true,
                                text: '{LANG.statistical_year}'
                            }
                        },
                        y: {
                            display: true,
                            title: {
                                display: true,
                                text: '{LANG.add_statistical_times}'
                            }
                        }
                    }
                }
            };
            $(function() {
                new Chart(document.getElementById("canvas_year").getContext("2d"), config_year);
            });
        </script>
        <!-- END: year -->


      </div>
    </div>
  </div>
  
  </body>
<!-- END: main -->