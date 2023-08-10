
<table id="basic_table2" class="table">
    <thead>
        <th>Column</th>
        <th>Value</th>
        <th></th>
    </thead>
    <tbody>
        @foreach($record as $column => $value)
            @if(strtolower($column) == 'id')
                @continue
            @else
                <tr>
                    <td>{{ ucfirst($column) }}</td>
                    <td colspan="2">
                        @if($column == 'attachment')
                            <img src="{{ !empty($record['attachment']['attachment_url']) ? url('/uploads/master_products/'.$record['attachment']['attachment_url']) : '' }}" alt="sample" class="preview">

                        @elseif(strtolower($column) == 'price')
                            ${{ !empty($value) ? $value : 0 }}/Month
                        @else
                            {{ $value }}
                        @endif
                    </td>
                </tr>
            @endif

        @endforeach
    </tbody>
</table>
