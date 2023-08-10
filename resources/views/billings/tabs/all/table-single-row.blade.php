<tr>
    <td class="text-center">{{ $record->invoice_number }}</td>
    <td class="text-center">{{ isset($record->billing_profile->company_name) ? $record->billing_profile->company_name : '' }}</td>
    <td class="text-center">{{ $record->order_date }}</td>
    <td class="text-center">${{ isset($record->grand_total_amount) ? number_format($record->grand_total_amount) : 0 }}</td>
    <td class="text-center">{{ str_replace('_', ' ', $record->payment_method) }}</td>
    <td class="text-center">{{ $record->payment_status }}</td>

</tr>
