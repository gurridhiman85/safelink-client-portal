<div class="row">
    <div class="col-sm-12">
        <div class="statistics-details d-flex align-items-center justify-content-between">
            <div>
                <p class="statistics-title">Online Safelink</p>
                <h3 class="rate-percentage">{{ $online_bond }}</h3>
            </div>
            <div>
                <p class="statistics-title">Offline Safelink</p>
                <h3 class="rate-percentage text-danger">{{ $offline_bond }}</h3>
            </div>
            <div>
                <p class="statistics-title">Total Safelink</p>
                <h3 class="rate-percentage">{{ $total_bonds }}</h3>
            </div>
            <div class="d-none d-md-block">
                <p class="statistics-title">Up Circuit</p>
                <h3 class="rate-percentage">{{ $up_circuit }}</h3>

                </p>
            </div>
            <div class="d-none d-md-block">
                <p class="statistics-title">Down Circuit</p>
                <h3 class="rate-percentage text-danger">{{ $down_circuit }}</h3>
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
                                    <a id="refresh" title="Refresh" onclick="$('#refreshBtn').trigger('click')" class="btn btn-primary  text-white btn-outline-secondary btn-sm">
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
                                {!! $bond_html !!}
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
