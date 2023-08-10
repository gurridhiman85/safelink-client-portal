<table id="basic_table2" class="table table-striped" data-message="No campaign available" data-order="[[ 0, &quot;asc&quot; ]]" > <!-- data-order="[[1,'desc']]" -->
    <thead>
        <tr>

                <th class="text-center"  data-visible="true">Invoice Number</th>
                <th class="text-center"  data-visible="true">Billing Profile</th>
                <th class="text-center"  data-visible="true">Date</th>
                <th class="text-center"  data-visible="true">Amount</th>
                <th class="text-center" data-visible="true">Payment Method</th>
                <th class="text-center" data-visible="true">Status</th>

        </tr>
    </thead>
    <tbody>
        @foreach($records as $key => $record)
            @include('billings.tabs.all.table-single-row')
        @endforeach
    </tbody>
</table>

