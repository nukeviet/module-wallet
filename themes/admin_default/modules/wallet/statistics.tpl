<!-- BEGIN: main -->
<div class="row">
    <div class="col-xs-8">
        <div class="text-center">
            <em class="fa fa-5x fa-area-chart"></em><br>
            <h2><a href="{LINK_MONTH}">{LANG.statisticsM}</a></h2>
        </div>
    </div>
    <div class="col-xs-8">
        <div class="text-center">
            <em class="fa fa-5x fa-area-chart"></em><br>
            <h2><a href="{LINK_YEAR}">{LANG.statisticsY}</a></h2>
        </div>
    </div>
    <div class="col-xs-8">
        <div class="text-center">
            <em class="fa fa-5x fa-area-chart"></em><br>
            <h2><a href="{LINK_COMPARE}">{LANG.statisticsC}</a></h2>
        </div>
    </div>
</div>
<!-- END: main -->

<!-- BEGIN: month -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<!-- BEGIN: result -->
</script>
<script type="text/javascript" src="{DATA_LINK}Chart.min.js"></script>
<div class="text-left row">
    <div id="canvas-holder" class="col-md-12">
        <canvas id="chart-area" width="300" height="300"></canvas>
    </div>
    <div class="col-md-11 col-md-offset-1">
        <div class="col-md-24">
            <div class="col-md-24" style="width: 30px;height: 30px;background:rgba(151,187,205,1)">
            &nbsp;&nbsp;&nbsp;&nbsp;
            </div>
            &nbsp;{LANG.num_money_cost}
        </div>
        <div class="col-md-24">
            <div class="col-md-24" style="width: 30px;height: 30px;background:rgba(255, 255, 0,1)">
            &nbsp;&nbsp;&nbsp;&nbsp;
            </div>
            &nbsp;{LANG.num_money_collection}
        </div>
    </div>
</div>
<h2 class="text-center">{CHART_TITLE}</h2>
<div class="alert alert-info">{LANG.statistics_note}</div>
<script type="text/javascript">
var pieData = {
    labels: {LABEL},
    datasets: [{
            label: "{LANG.num_money_cost}",
            fillColor: "rgba(151,187,205,0.2)",
            strokeColor: "rgba(151,187,205,1)",
            pointColor: "rgba(151,187,205,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(151,187,205,1)",
            data: {MONEY_DISCOUNT}
        },
        {
            label: "{LANG.num_money_collection}",
            fillColor: "rgba(255, 255, 0,0.2)",
            strokeColor: "rgba(255, 255, 0,1)",
            pointColor: "rgba(255, 255, 0,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(255, 255, 0,1)",
            data: {MONEY_REVENUE}
        }
    ]
};

function addCommas(n) {
    n = parseFloat(n);
    return n.toFixed(0).replace(/./g, function(c, i, a) {
        return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
    }) + ' vnd';
}
window.onload = function() {
    var ctx = document.getElementById("chart-area").getContext("2d");
    window.myLine = new Chart(ctx).Line(pieData, {
        responsive: true
    });
};
</script>
<!-- END: result -->
<div class="well">
    <h2>{LANG.statisticsM_select}</h2>
    <hr>
    <form action="{FORM_ACTION}" method="post">
        <div class="row">
            <!-- BEGIN: loop_month -->
            <div class="col-xs-4">
                <label>
                    <input type="checkbox" name="month[]" value="{MONTH.key}"{MONTH.checked}{MONTH.disabled}> {MONTH.title}
                </label>
            </div>
            <!-- END: loop_month -->
        </div>
        <div class="text-center">
            <input type="submit" class="btn btn-primary" value="{LANG.statistics_view}" name="submit">
        </div>
    </form>
</div>
<!-- END: month -->

<!-- BEGIN: year -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<!-- BEGIN: result -->
<script type="text/javascript" src="{DATA_LINK}Chart.min.js"></script>
<div class="text-center">
    <div id="canvas-holder">
        <canvas id="chart-area" width="300" height="300"/>
    </div>
    <h2>{LANG.statisticsY_title1}</h2>
</div>
<div class="alert alert-info">{LANG.statistics_note}</div>
<script type="text/javascript">
var pieData = {DATAS};

function addCommas(n) {
    n = parseFloat(n);
    return n.toFixed(0).replace(/./g, function(c, i, a) {
        return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
    }) + ' vnd';
}

window.onload = function() {
    var ctx = document.getElementById("chart-area").getContext("2d");
    var myPie = new Chart(ctx).Pie(pieData);
};
</script>
<!-- END: result -->
<!-- BEGIN: result1 -->
<script type="text/javascript" src="{DATA_LINK}Chart.min.js"></script>
<div class="text-left row">
    <div id="canvas-holder" class="col-md-12">
        <canvas id="chart-area" width="300" height="300"></canvas>
    </div>
    <div class="col-md-11 col-md-offset-1">
        <div class="col-md-24">
            <div class="col-md-24" style="width: 30px;height: 30px;background:rgba(151,187,205,1)">
            &nbsp;&nbsp;&nbsp;&nbsp;
            </div>
            &nbsp;{LANG.num_money_cost}
        </div>
        <div class="col-md-24">
            <div class="col-md-24" style="width: 30px;height: 30px;background:rgba(255, 255, 0,1)">
            &nbsp;&nbsp;&nbsp;&nbsp;
            </div>
            &nbsp;{LANG.num_money_collection}
        </div>
    </div>
</div>
<h2 class="text-center">{LANG.statisticsY_title1}</h2>
<div class="alert alert-info">{LANG.statistics_note}</div>
<script type="text/javascript">
var pieData = {
    labels: {LABEL},
    datasets: [{
            label: "{LANG.num_money_cost}",
            fillColor: "rgba(151,187,205,0.2)",
            strokeColor: "rgba(151,187,205,1)",
            pointColor: "rgba(151,187,205,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(151,187,205,1)",
            data: {MONEY_DISCOUNT}
        },
        {
            label: "{LANG.num_money_collection}",
            fillColor: "rgba(255, 255, 0,0.2)",
            strokeColor: "rgba(255, 255, 0,1)",
            pointColor: "rgba(255, 255, 0,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(255, 255, 0,1)",
            data: {MONEY_REVENUE}
        }
    ]
};

function addCommas(n) {
    n = parseFloat(n);
    return n.toFixed(0).replace(/./g, function(c, i, a) {
        return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
    }) + ' vnd';
}

window.onload = function() {
    var ctx = document.getElementById("chart-area").getContext("2d");
    window.myLine = new Chart(ctx).Line(pieData, {
        responsive: true
    });
};
</script>
<!-- END: result1 -->
<div class="well">
    <h2>{LANG.statisticsY_select}</h2>
    <hr>
    <form action="{FORM_ACTION}" method="post">
        <div class="row">
            <!-- BEGIN: loop_year -->
            <div class="col-xs-4">
                <label>
                    <input type="checkbox" name="year[]" value="{YEAR.key}"{YEAR.checked}> {YEAR.title}
                </label>
            </div>
            <!-- END: loop_year -->
        </div>
        <div class="text-center">
            <input type="submit" class="btn btn-primary" value="{LANG.statistics_view}" name="submit">
        </div>
    </form>
</div>
<!-- END: year -->

<!-- BEGIN: compare -->
<script type="text/javascript" src="{DATA_LINK}Chart.min.js"></script>
<div class="text-center">
    <div id="canvas-holder">
        <canvas id="chart-area" width="300" height="300"></canvas>

        <canvas id="chart-area1" width="300" height="300"></canvas>
    </div>
    <!-- BEGIN: notdata -->
    <p>
        {NOT_DATA}
    </p>
    <!-- END: notdata -->
    <!-- BEGIN: notdata_b -->
    <p>
        {NOT_DATA}
    </p>
    <!-- END: notdata_b -->
    <h2>{CHART_TITLE}</h2>
</div>
<div class="alert alert-info">{LANG.statistics_note}</div>
<script type="text/javascript">
var pieData = {CURRENT};
var pieData1 = {BEFORE};

function addCommas(n) {
    n = parseFloat(n);
    return n.toFixed(0).replace(/./g, function(c, i, a) {
        return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
    }) + ' vnd';
}

window.onload = function() {
    var ctx = document.getElementById("chart-area").getContext("2d");
    var myPie = new Chart(ctx).Pie(pieData);

    //var ctx1 = document.getElementById("chart-area1").getContext("2d");
    //var myPie1 = new Chart(ctx1).Pie(pieData1);
};
</script>

<div class="well">
    <h2>{LANG.statisticsM_select}</h2>
    <hr>
    <form action="{FORM_ACTION}" method="post">
        <div class="row">
            <div class="col-xs-4">
                <select name="month" class="form-control">
                    <!-- BEGIN: loop_month -->
                    <option value="{MONTH.key}" {MONTH.checked} >{MONTH.title}</option>
                    <!-- END: loop_month -->
                </select>
            </div>
        </div>
        <div class="text-center">
            <input type="submit" class="btn btn-primary" value="{LANG.statistics_view}" name="submit">
        </div>
    </form>
</div>
<!-- END: compare -->