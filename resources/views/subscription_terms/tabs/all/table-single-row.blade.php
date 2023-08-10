<tr>
    <td>{{ $record->id }}</td>
    <td>{{ $record->Name }}</td>
    <td>{{ $record->Term }}</td>
    <td class="text-center">

        <button class="btn btn-outline-warning ajax-Link" data-href="{{ url('/subscription_terms/add/'.$record->id) }}">Edit</button>

        <button data-confirm="true" data-title="Are you sure want to delete this Subscription Term ?"  class="btn btn-outline-danger ajax-Link" data-href="{{ url('/subscription_terms/delete/'.$record->id) }}">Delete</button>
    </td>

</tr>
