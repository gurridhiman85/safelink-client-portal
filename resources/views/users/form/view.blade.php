
<table id="basic_table2" class="table">
    <thead>
        <th>Column</th>
        <th>Value</th>
    </thead>
    <tbody>
        @foreach($record as $column => $value)
            <tr>
                <td>{{ $column }}</td>
                <td>
                    {{ $value }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
