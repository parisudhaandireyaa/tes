<?php
	//* >>> FILTER NAME
	$filter_name = "dashboard_free_taxi";
	echo "<script> var filter_name = '{$filter_name}'; </script>";
	//* >>> FILTER NAME

	//* >>> REMEMBER FILTER
	if ( ! isset( $_SESSION['calltaxi']['filter'][ $filter_name ] ) ) {
		$_SESSION['calltaxi']['filter'][ $filter_name ] = array();
	}
	echo "<script> var pre_filter = '" . json_encode( $_SESSION['calltaxi']['filter'][ $filter_name ] ) . "'; pre_filter = JSON.parse(pre_filter); </script>";
	//* <<< REMEMBER FILTER
?>

<!-- begin row -->
<div class="row" style="background-color: white !important;">
    <!-- begin col-12 -->
    <h4 class="p-l-10 p-t-5 m-0 f-w-600">Free Vechile</h4>
    <div class="col-md-12">
        <div class="free_taxi_db">
            <table width100 class="table table-bordered"
                   id='<?php echo $filter_name; ?>'>
                <thead id="col_head">
                <tr>
                    <th class="bg-color-yellow">S.N</th>
                    <th class="bg-color-yellow">Vehicle No</th>
                    <th class="bg-color-yellow">Idle Time</th>
                    <th class="bg-color-yellow">Login Days</th>
                    <th class="bg-color-yellow">Month Collection</th>
                    <th class="bg-color-yellow">Empty KM</th>
                    <th class="bg-color-yellow">Total KM</th>
                    <th class="bg-color-yellow">Trips</th>
                    <th class="bg-color-yellow">Collection</th>
                    <th class="bg-color-yellow">CC Balance</th>
                    <th class="bg-color-yellow">Login Time</th>
                    <th class="bg-color-yellow">Current Location</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
    <!-- end col-12 -->
</div>
<!-- end row -->

<script>
    $(function () {
        var table = $("#dashboard_free_taxi").DataTable({
            serverSide: true,
            dom: 'Brtip',
            ordering: false,
            pageLength: 100,
            bFilter: true,
            ajax: GLOBAL.BASE_URL + '/dashboard/free_taxi',
            fnDrawCallback: function () {
            },
            rowCallback: function (row, data, index) {
            },
            responsive: false,
            processing: true,
            oLanguage: {
                sProcessing: "<img src='" + GLOBAL.TEMPLATE_PATH + "image/ajax-loader.gif' class='width-60'>"
            },
            columnDefs: [
                {
                    "render": function (data, type, row) {
                        var tloc = data ? data.substring(0, 20) : data;
						console.log(row['current_place_full']);
						url = '<?php echo BASE_URL."/trips/vehicles-tracking/"?>'+row['id'];
                        return "<a href="+url+" style='color:black;'><span title='" + row['current_place_full'] + "'>" + tloc + "</span></a>";
                    },
                    "targets": [11]
                },{
                    "render": function (data, type, row) {
                        var tloc = data ? data.substring(0, 20) : data;
                        return "<span title='" + data + "'>" + tloc + "</span>";
                    },
                    "targets": [9]
                }
            ],
            columns: [
                {"data": "sno", "width": "1%"},
                {"data": "vehicle_no", "width": "3%"},
                {"data": "idle_time", "width": "3%"},
                {"data": "login_days", "width": "3%"},
                {"data": "month_collection", "width": "5%"},
                {"data": "today_empty_km", "width": "5%"},
                {"data": "total_km", "width": "5%"},
                {"data": "today_trips", "class": "bg-color-yellow", "width": "5%"},
                {"data": "today_collection", "class": "bg-color-yellow", "width": "5%"},
                {"data": "previous_balance", "width": "5%"},
                {"data": "login_time", "width": "5%"},
                {"data": "current_place", "width": "10%"}
            ],
            fnCreatedRow: function (nRow, aData, iDataIndex) {
                /*if(aData['last_trip_time']){
                    var diff = new Date() - new Date(aData['last_trip_time']);
                    var minutes = Math.floor((diff / 1000) / 60);
                    if(minutes > 60){
                        $(nRow).find("td").addClass("bg-idle-mt-1hr");
                    }
                }*/
                if (aData['running_status'] == "break") {
                    $(nRow).find("td").addClass("bg-break-blue");
                }
            }
        });

        var intref = setInterval(function () {
            table.ajax.reload();
        }, 10 * 1000);
    });
</script>


<style>
    tbody>tr {
        height: 30px !important;
    }
    tbody>tr>td{
        border: 1px solid black !important;
        font-weight: bold;
    }
</style>
