<table id="basic_table2" class="table table-striped" data-message="No campaign available" data-order="[[ 0, &quot;asc&quot; ]]" > <!-- data-order="[[1,'desc']]" -->
    <thead>
        <tr>

                <th data-visible="true">ID</th>
                <th data-visible="true">Name</th>
                <th data-visible="true">Description</th>
                <th data-visible="true">Price</th>
                <th class="text-center" data-visible="true">Photo</th>
                <th class="text-center" data-visible="true">Action</th>

        </tr>
    </thead>
    <tbody>
        @foreach($records as $key => $record)
            @include('master_products.tabs.all.table-single-row')
        @endforeach
    </tbody>
</table>

