<tr>
    <td>{{ $record->id }}</td>
    <td>{{ $record->Name }}</td>
    <td>{{ $record->Descriptions }}</td>
    <td>{{ number_format($record->Price) }}</td>
    <td class="text-center">
        @if(isset($record->attachment))
            <img src="{{ $record->attachment->is_thumbnail == 1 ? url('/uploads/master_products/thumb/'.$record->attachment->attachment_url) : '' }}"/>
        @endif
    </td>
    <td class="text-center">
        <button class="btn btn-outline-info ajax-Link" data-href="{{ url('/master_products/view/'.$record->id) }}">View</button>

        <button class="btn btn-outline-warning ajax-Link" data-href="{{ url('/master_products/add/'.$record->id) }}">Edit</button>

        <button data-confirm="true" data-title="Are you sure want to delete this master product ?"  class="btn btn-outline-danger ajax-Link" data-href="{{ url('/master_products/delete/'.$record->id) }}">Delete</button>
    </td>

</tr>
