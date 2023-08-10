@extends('layouts.app')
@section('content')
    <div class="loader"></div>
    <div class="row">
        <div class="col-sm-12">
            <div class="home-tab">
                <div class="tab-content tab-content-basic">
                    <a href="javascript:void(0);" class="d-none" id="refreshBtn"></a>
                    <div class="tab-pane fade show active space-details" id="overview" role="tabpanel"
                         aria-labelledby="overview">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="statistics-details d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="statistics-title">Online Safelink</p>
                                        <h3 class="rate-percentage">0</h3>
                                    </div>
                                    <div>
                                        <p class="statistics-title">Offline Safelink</p>
                                        <h3 class="rate-percentage text-danger">0</h3>
                                    </div>
                                    <div>
                                        <p class="statistics-title">Total Safelink</p>
                                        <h3 class="rate-percentage">0</h3>
                                    </div>
                                    <div class="d-none d-md-block">
                                        <p class="statistics-title">Up Circuit</p>
                                        <h3 class="rate-percentage">0</h3>

                                        </p>
                                    </div>
                                    <div class="d-none d-md-block">
                                        <p class="statistics-title">Down Circuit</p>
                                        <h3 class="rate-percentage text-danger">0</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 d-flex flex-column">
                                <div class="row flex-grow">
                                    <div class="col-12 grid-margin stretch-card">
                                        <div class="card card-rounded">
                                            <div class="card-body">
                                                <div class="d-sm-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h4 class="card-title card-title-dash">Statistics</h4>
                                                    </div>

                                                    <div>
                                                        <div class="input-group">
                                                            <a id="refresh" onclick="$('#refreshBtn').trigger('click')" title="Refresh" class="btn btn-primary text-white btn-outline-secondary btn-sm">
                                                                <i class="ti-reload"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="table-responsive  mt-1">
                                                    <table id="basic_table_without_dynamic_pagination" class="table select-table">
                                                        <thead>
                                                        <tr>
                                                            <th>Safelink ID</th>
                                                            <th>Safelink Name</th>
                                                            <th>Circuit</th>
                                                            <th>Download</th>
                                                            <th>Upload</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td colspan="5">
                                                                <center>
                                                                    No active safelink device
                                                                </center>
                                                            </td>
                                                        </tr>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 d-flex flex-column">
                                <div class="row flex-grow">
                                    <div class="col-12 grid-margin stretch-card">
                                        <div class="card card-rounded">
                                            <div class="card-body">
                                                <div id="map" style=" height: 550px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://maps.google.com/maps/api/js?key={{ config('constant.Google_APIKEY') }}&callback=initializeMap"
            type="text/javascript"></script>
    <script type="text/javascript">


        function addMarkers(locations) {

            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 5,
                center: new google.maps.LatLng(-2.748602,109.9130542),
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });

            var infowindow = new google.maps.InfoWindow();


            var marker, i;

            for (i = 0; i < locations.length; i++) {
                marker = new google.maps.Marker({
                    position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                    map: map
                });

                google.maps.event.addListener(marker, 'click', (function(marker, i) {
                    return function() {
                        infowindow.setContent(locations[i][0]);
                        infowindow.open(map, marker);
                    }
                })(marker, i));
            }
        }

    </script>
    <script type="application/javascript">
        $(document).ready(function () {
            //setInterval(refresh_dashboard, 60000);

            var interval;
            var timer = function(){
                interval = setInterval(function(){
                    refresh_dashboard();
                },60000);
            };

            setTimeout(function () {
                $('[data-space-index="1"]').trigger('click');
                $('#selectSpace').attr('data-select-index', 1);
                timer();
            },2000);

            $('#refreshBtn').on('click', function(){
                refresh_dashboard();
                //go back to previous slide and reset time
                clearInterval(interval);
                timer()
            });




            var locations = [];
            addMarkers(locations);
            $('.space-changer').on('click', function () {
                var $space = $(this).data('space');
                var $space_name = $(this).data('space-name');
                var $space_index = $(this).attr('data-space-index');
                $('#selectSpace').attr('data-select-index', $space_index);
                if($space !== ""){
                    $('#selectSpace').text($space_name);
                    ACFn.sendAjax('{{ url('/dashboard/get') }}', 'POST', {
                        space : $space
                    }, $('.space-details'));
                }else{
                    $('#selectSpace').text('Select Space');
                }

            });

            ACFn.show_dashboard_ajax = function (F, R) {
                if(R.success){
                    F.html(R.html);
                    var locations = [];
                    var singleLoc = [];
                    if(R.bonds_info.length > 0){
                        $.each(R.bonds_info, function(i, item) {
                            var singleLoc = [];
                            if(item.address)
                                singleLoc.push(item.id + '<br/>' +item.name + '<br/>' + item.address);
                            else
                                singleLoc.push('');

                            if(item.lat)
                                singleLoc.push(item.lat);
                            else
                                singleLoc.push(0);

                            if(item.long)
                                singleLoc.push(item.long);
                            else
                                singleLoc.push(0);

                            locations.push(singleLoc);
                        });

                    }

                    /*var locations = [
                        ['Bondi Beach', -33.890542, 151.274856, 4],
                        ['Coogee Beach', -33.923036, 151.259052, 5],
                        ['Cronulla Beach', -34.028249, 151.157507, 3],
                        ['Manly Beach', -33.80010128657071, 151.28747820854187, 2],
                        ['Maroubra Beach', -33.950198, 151.259302, 1]
                    ];*/
                    addMarkers(locations);
                    console.log('locations--',locations)
                    //initJS(F);


                }
            }

        });

        function refresh_dashboard() {
            var idx = $('#selectSpace').attr('data-select-index');
            if(parseInt(idx) > 0){
                $('[data-space-index="'+idx+'"]').trigger('click');
            }
        }

    </script>

@endsection
