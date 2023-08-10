<tr>
    <td>{{ $record->id }}</td>
    <td>{{ $record->name }}</td>
    <td>{{ $record->email }}</td>
    <td>{{ ucfirst($record->user_type) }}</td>
    <td class="text-center">

        <button class="btn btn-outline-warning ajax-Link" data-href="{{ url('/users/add/'.$record->id) }}">Edit</button>

        <button data-confirm="true" data-title="Are you sure want to delete this User ?"  class="btn btn-outline-danger ajax-Link" data-href="{{ url('/users/delete/'.$record->id) }}">Delete</button>
    </td>

</tr>
