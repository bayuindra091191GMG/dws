<table>
    <thead>
    <tr>
        <th>No Transaksi</th>
        <th>Tanggal</th>
        <th>Pengolahan Sampah</th>
        <th>Sumber Sampah</th>
        <th>Jenis Transaksi</th>
        <th>Kategori Sampah</th>
        <th>Waste Collector</th>
        <th>Jenis Sampah</th>
        <th>Berat (Kg)</th>
        <th>Harga (Rp)</th>
        <th>Total Berat</th>
        <th>Total Harga</th>
    </tr>
    </thead>
    <tbody>

    @foreach($trxHeaders as $header)
        @php($i=1)
        @foreach($header->transaction_details as $detail)
            @if($i !== 1)
                <tr>
                    <td>{{ $header->transaction_no }}</td>
                    <td>{{ $header->date_string }}</td>
                    <td>
                        @if(!empty($header->waste_bank_id))
                            {{ $header->waste_bank->name }}
                        @endif
                    </td>
                    <td>{{ !empty($header->user_id) ? $header->user->first_name. ' '. $header->user->last_name : '-' }}</td>
                    <td>{{ strtoupper($header->transaction_type->description) }}</td>
                    <td>{{ strtoupper($header->waste_category->name) }}</td>
                    <td>{{ !empty($header->waste_collector_id) ? $header->waste_collector->first_name. ' '. $header->waste_collector->last_name : '-' }}</td>
                    <td>
                        @if($detail->dws_category_id != null && $detail->masaro_category_id == null)
                            {{ $detail->dws_waste_category_data->code. ' - '. $detail->dws_waste_category_data->name }}
                        @elseif($detail->dws_category_id == null && $detail->masaro_category_id != null)
                            {{ $detail->masaro_waste_category_data->code. ' - '. $detail->masaro_waste_category_data->name }}
                        @endif
                    </td>
                    <td>{{ $detail->weight_kg }}</td>
                    <td>{{ $detail->price }}</td>
                    <td></td>
                    <td></td>
                </tr>
            @endif
            @php($i++)
        @endforeach

        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{ $header->total_weight_kg }}</td>
            <td>{{ $header->total_price }}</td>
        </tr>

    @endforeach
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>Total</td>
        <td>{{ $totalWeight }}</td>
        <td>{{ $totalPrice }}</td>
    </tr>
    </tbody>
</table>
