<!DOCTYPE html>
<html lang="en">
    <?php
        require_once("application.php");
        require_once("js_css_header.php");

        $stmt = $db_con->query("EXECUTE MRPIndexDashboard");
        $stmtResult = $stmt->fetch(PDO::FETCH_ASSOC);
    ?>
    <body>
        <div id="loader" class="app-loader">
            <span class="spinner"></span>
        </div>
        <div id="app" class="app app-header-fixed app-sidebar-fixed">
            <?php
                require_once('navbar.php');
                require_once("menu.php");
            ?>
            <div class="app-sidebar-bg" data-bs-theme="dark"></div>
            <div class="app-sidebar-mobile-backdrop"><a href="#" data-dismiss="app-sidebar-mobile" class="stretched-link"></a></div>
            
            <div id="content" class="app-content">
                <ol class="breadcrumb float-xl-end">
                    <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
                    <li class="breadcrumb-item"><a href="javascript:;">Dashboard</a></li>
                    <li class="breadcrumb-item active">Dashboard v2</li>
                </ol>
                <h1 class="page-header mb-3">Production Dashboard v2</h1>
                <div class="d-sm-flex align-items-center mb-3">
                    <a href="#" class="btn btn-dark me-2 text-truncate" id="daterange-filter">
                        <i class="fa fa-calendar fa-fw text-white text-opacity-50 ms-n1"></i> 
                        <span>1 Jun 2023 - 7 Jun 2023</span>
                        <b class="caret ms-1 opacity-5"></b>
                    </a>
                    <div class="text-dark-300 fw-bold mt-2 mt-sm-0">compared to <span id="daterange-prev-date">24 Mar-30 Apr 2023</span></div>
                </div>
                <div class="row">
                    <div class="col-xl-6">
                        <div class="card border-0 mb-3 overflow-hidden text-dark">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xl-7 col-lg-8">
                                        <div class="mb-3">
                                            <b>TOTAL PRODUCTION</b>
                                            <span class="ms-2">
                                                <i class="fa fa-info-circle" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-title="Total Production" data-bs-placement="top" data-bs-content="จำนวนการผลิตภายในวันที่ <?=$buffer_date?> รวมทุก Model ที่มีการส่งงานขึ้นไปยัง WH"></i>
                                            </span>
                                        </div>
                                        <div class="d-flex mb-1">
                                            <h2 class="mb-0"><span data-animation="number" data-value="<?=number_format($stmtResult['total_ft2'], 2)?>">0.00</span> / 130,000 Ft<sup>2</sup></h2>
                                            <div class="ms-auto mt-n1 mb-n1"><div id="total-sales-sparkline"></div></div>
                                        </div>
                                        <div class="mb-3">
                                            <i class="fa fa-caret-up"></i> <span data-animation="number" data-value="<?=number_format((100 / $stmtResult['total_plan']) * $stmtResult['total_ft2'], 2)?>">0.00</span>% compare to capacity today
                                        </div>
                                        <hr />
                                        <div class="row text-truncate">
                                            <div class="col-6">
                                                <div class="">Total job plan today (Close/ Plan)</div>
                                                <div><span class="fs-18px mb-5px fw-bold" data-animation="number" data-value="<?=$stmtResult['closed_job']?>">0</span> / <?=$stmtResult['job_plan_date']?></div>
                                                <div class="progress h-5px rounded-3 bg-silver mb-5px">
                                                    <div class="progress-bar progress-bar-striped rounded-right bg-green" data-animation="width" data-value="<?=($stmtResult['closed_job'] * 100 / $stmtResult['job_plan_date'])?>%" style="width: 0%"></div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="">Avg. sales per order</div>
                                                <div><span class="fs-18px mb-5px fw-bold" data-animation="number" data-value="<?=$stmtResult['job_plan_today_and_closed_today']?>">0</span> / <?=$stmtResult['job_plan_date']?></div>
                                                <div class="progress h-5px rounded-3 bg-silver mb-5px">
                                                    <div class="progress-bar progress-bar-striped rounded-right bg-blue" data-animation="width" data-value="<?=($stmtResult['job_plan_today_and_closed_today'] * 100 / $stmtResult['job_plan_date'])?>%" style="width: 0%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-5 col-lg-4 align-items-center d-flex justify-content-center">
                                        <img src="<?=$CFG->sub_images?>/svg/img-1.svg" height="150px" class="d-none d-lg-block" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="card border-0 text-truncate mb-3 text-dark">
                                    <div class="card-body">
                                        <div class="mb-3 ">
                                            <b class="mb-3">CONVERSION RATE</b> 
                                            <span class="ms-2"><i class="fa fa-info-circle" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-title="Conversion Rate" data-bs-placement="top" data-bs-content="Percentage of sessions that resulted in orders from total number of sessions." data-original-title="" title=""></i></span>
                                        </div>
                                        <div class="d-flex align-items-center mb-1">
                                            <h2 class="mb-0"><span data-animation="number" data-value="2.19">0.00</span>%</h2>
                                            <div class="ms-auto">
                                                <div id="conversion-rate-sparkline"></div>
                                            </div>
                                        </div>
                                        <div class="mb-4">
                                            <i class="fa fa-caret-down"></i> <span data-animation="number" data-value="0.50">0.00</span>% compare to last week
                                        </div>
                                        <div class="d-flex mb-2">
                                            <div class="d-flex align-items-center">
                                                <i class="fa fa-circle text-red fs-8px me-2"></i>
                                                Added to cart
                                            </div>
                                            <div class="d-flex align-items-center ms-auto">
                                                <div class="small"><i class="fa fa-caret-up"></i> <span data-animation="number" data-value="262">0</span>%</div>
                                                <div class="w-50px text-end ps-2 fw-bold"><span data-animation="number" data-value="3.79">0.00</span>%</div>
                                            </div>
                                        </div>
                                        <div class="d-flex mb-2">
                                            <div class="d-flex align-items-center">
                                                <i class="fa fa-circle text-warning fs-8px me-2"></i>
                                                Reached checkout
                                            </div>
                                            <div class="d-flex align-items-center ms-auto">
                                                <div class="small"><i class="fa fa-caret-up"></i> <span data-animation="number" data-value="11">0</span>%</div>
                                                <div class="w-50px text-end ps-2 fw-bold"><span data-animation="number" data-value="3.85">0.00</span>%</div>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <div class="d-flex align-items-center">
                                                <i class="fa fa-circle text-lime fs-8px me-2"></i>
                                                Sessions converted
                                            </div>
                                            <div class="d-flex align-items-center ms-auto">
                                                <div class="small"><i class="fa fa-caret-up"></i> <span data-animation="number" data-value="57">0</span>%</div>
                                                <div class="w-50px text-end ps-2 fw-bold"><span data-animation="number" data-value="2.19">0.00</span>%</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="card border-0 text-truncate mb-3 text-dark">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <b class="mb-3">STORE SESSIONS</b> 
                                            <span class="ms-2"><i class="fa fa-info-circle" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-title="Store Sessions" data-bs-placement="top" data-bs-content="Number of sessions on your online store. A session is a period of continuous activity from a visitor." data-original-title="" title=""></i></span>
                                        </div>
                                        <div class="d-flex align-items-center mb-1">
                                            <h2 class="mb-0"><span data-animation="number" data-value="70719">0</span></h2>
                                            <div class="ms-auto">
                                                <div id="store-session-sparkline"></div>
                                            </div>
                                        </div>
                                        <div class="mb-4">
                                            <i class="fa fa-caret-up"></i> <span data-animation="number" data-value="9.5">0.00</span>% compare to last week
                                        </div>
                                        <div class="d-flex mb-2">
                                            <div class="d-flex align-items-center">
                                                <i class="fa fa-circle text-teal fs-8px me-2"></i>
                                                Mobile
                                            </div>
                                            <div class="d-flex align-items-center ms-auto">
                                                <div class="small"><i class="fa fa-caret-up"></i> <span data-animation="number" data-value="25.7">0.00</span>%</div>
                                                <div class="w-50px text-end ps-2 fw-bold"><span data-animation="number" data-value="53210">0</span></div>
                                            </div>
                                        </div>
                                        <div class="d-flex mb-2">
                                            <div class="d-flex align-items-center">
                                                <i class="fa fa-circle text-blue fs-8px me-2"></i>
                                                Desktop
                                            </div>
                                            <div class="d-flex align-items-center ms-auto">
                                                <div class="small"><i class="fa fa-caret-up"></i> <span data-animation="number" data-value="16.0">0.00</span>%</div>
                                                <div class="w-50px text-end ps-2 fw-bold"><span data-animation="number" data-value="11959">0</span></div>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <div class="d-flex align-items-center">
                                                <i class="fa fa-circle text-cyan fs-8px me-2"></i>
                                                Tablet
                                            </div>
                                            <div class="d-flex align-items-center ms-auto">
                                                <div class="small"><i class="fa fa-caret-up"></i> <span data-animation="number" data-value="7.9">0.00</span>%</div>
                                                <div class="w-50px text-end ps-2 fw-bold"><span data-animation="number" data-value="5545">0</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="row">
                    <div class="col-xl-12 col-lg-6">
                        <div class="card border-0 mb-3 text-dark">
                            <div class="card-body">
                                <div class="mb-3"><b>MACHINE CAPACITY ANALYTICS (Ft<sup>2</sup> and Minute capacity)</b> <span class="ms-2"><i class="fa fa-info-circle" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-title="Top products with units sold" data-bs-placement="top" data-bs-content="Products with the most individual units sold. Includes orders from all sales channels." data-original-title="" title=""></i></span></div>
                            </div>
                            <div class="card-body p-0">
                                <div id="delList" class="chart-wrapper">
                                    <canvas id="bar-chart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
                <div class="row">
                    <div class="col-xl-12 col-lg-6">
                        <div class="card border-0 mb-3 text-dark">
                            <div class="card-body p-0">
                                <input type="date" id="handle_date" value="<?=$buffer_date?>" class="btn btn-sm border me-2 ms-2 mt-2">
                                <button onclick="handleBarChart()" class="btn btn-sm btn-white me-2 px-2 mt-2"> <i class="fa-solid fa-chart-simple"></i> Show Data</button>
                                <div id="apex-mixed-chart"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-6">
                        <div class="card border-0 mb-3 text-dark">
                            <div class="card-body">
                                <div class="mb-2">
                                    <b>JOB PREPARING</b>
                                    <span class="ms-2"><i class="fa fa-info-circle" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-title="Total sales" data-bs-placement="top" data-bs-content="Net sales (gross sales minus discounts and returns) plus taxes and shipping. Includes orders from all sales channels."></i></span>
                                </div>
                                <div id="visitors-map" class="mb-2" style="height: 100px" ></div>
                                <div data-scrollbar="true" data-height="500px" class="ps ps--active-y">
                                    <?php
                                        $j_p_st = $db_con->query("SELECT job_no + ' - ' + CAST(job_plan_date AS NVARCHAR(20)) + ' - ' + job_fg_description AS list, job_status FROM tbl_job_mst WHERE job_status = 'prepare' ORDER BY job_no DESC");
                                        while($j_p_st_rest = $j_p_st->fetch(PDO::FETCH_ASSOC)):
                                    ?>
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="d-flex w-100">
                                            <div><?=$j_p_st_rest['list']?></div>
                                            <div class="ms-auto badge bg-gradient-red"><?=$j_p_st_rest['job_status']?></div>
                                        </div>
                                    </div>
                                    <?php endwhile; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <a href="javascript:;" class="btn btn-icon btn-circle btn-theme btn-scroll-to-top" data-toggle="scroll-to-top"><i class="fa fa-angle-up"></i></a>
        </div>
    </body>
    <?php require_once("js_css_footer.php"); ?>
</html>

<script type="text/javascript">
    $(document).ready(function(){
        // MachineCapacity()
        handleBarChart()
    })

    function MachineCapacity(){
        $.post('<?=$CFG->mod_dashboard?>/dash-home', { protocol: 'MachineCapacity', start_date: '<?=$buffer_date?>', end_date: '<?=$buffer_date?>' }, function(data){
            try {
                const result = JSON.parse(data)
                var data = {
                    labels: result.name,
                    datasets: [
                        {
                            label: 'Actual Minute',
                            borderWidth: 1,
                            borderColor: 'green',
                            backgroundColor: 'green',
                            data: result.act
                        },{
                            label: 'Capacity Minute',
                            borderWidth: 1,
                            borderColor: 'orange',
                            backgroundColor: 'orange',
                            data: result.cap
                        },{
                            label: 'Plan Minute',
                            borderWidth: 1,
                            borderColor: 'blue',
                            backgroundColor: 'blue',
                            data: result.value
                        },
                    ]
                }

                $("#delList").html('')
                $("#delList").html('<canvas id="bar-chart"></canvas>')

                var grapharea = document.getElementById('bar-chart').getContext("2d");
                var myChart = new Chart(grapharea, { type: 'bar', data: data });
                myChart.destroy();
                window.myChart = new Chart(grapharea, { type: 'bar', data: data });
            } catch(err) {
                console.log(err.message)
            }
        })
    }

    function handleBarChart(){
        $.post('<?=$CFG->mod_dashboard?>/dash-home', { protocol: 'ProductionTime', start_date: $("#handle_date").val() }, function(data){
            var result = JSON.parse(data)

            console.log(result)

            $('#apex-mixed-chart').empty();
            var options = {
                chart: {
                    height: 350,
                    type: 'line',
                    stacked: false
                },
                dataLabels: {
                    enabled: false
                },
                series: [{
                    name: 'Time Plan (min)',
                    type: 'column',
                    data: result.actual_min
                }, {
                    name: 'Cap (min)',
                    type: 'line',
                    data: result.cap
                }, {
                    name: 'Cap OT (min)',
                    type: 'line',
                    data: result.ot
                }],
                stroke: {
                    width: [0, 3, 3]
                },
                colors: [app.color.blue, app.color.success, app.color.orange],
                title: {
                    text: 'XYZ - Planning Production Time',
                    align: 'left',
                    offsetX: 110
                },
                xaxis: {
                    categories: result.machine_list
                },
                yaxis: [{
                    axisTicks: {
                        show: true,
                        color: 'rgba('+ app.color.componentColorRgb + ', .15)'
                    },
                    axisBorder: {
                        show: true,
                        color: 'rgba('+ app.color.componentColorRgb + ', .15)'
                    },
                    title: {
                        text: "Income (thousand crores)",
                        style: {
                            color: app.color.componentColor
                        }
                    },
                    tooltip: {
                        enabled: true
                    }
                },{
                    seriesName: 'Time Plan (min)',
                    opposite: true,
                    axisTicks: {
                        show: true,
                        color: 'rgba('+ app.color.componentColorRgb + ', .15)'
                    },
                    axisBorder: {
                        show: true,
                        color: 'rgba('+ app.color.componentColorRgb + ', .15)'
                    },
                    title: {
                        text: "Machine Capacity",
                        style: {
                            color: app.color.componentColor
                        }
                    },
                }, {
                    seriesName: 'Revenue',
                    opposite: true,
                    axisTicks: {
                        show: true,
                    },
                    axisBorder: {
                        show: true,
                        color: app.color.orange
                    },
                    title: {
                        text: "Capacity OT",
                        style: {
                            color: app.color.orange
                        }
                    }
                }],
                tooltip: {
                    fixed: {
                        enabled: true,
                        position: 'topLeft', // topRight, topLeft, bottomRight, bottomLeft
                        offsetY: 30,
                        offsetX: 60
                    },
                },
                legend: {
                    horizontalAlign: 'left',
                    offsetX: 40
                }
            };

            var chart = new ApexCharts(
                document.querySelector('#apex-mixed-chart'),
                options
            );

            chart.render();
        })
    }
</script>