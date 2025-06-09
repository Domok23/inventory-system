<!-- filepath: d:\laragon\www\inventory-system-v2\resources\views\inventory\export.blade.php -->
<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Category</th>
            <th>Quantity</th>
            <th>Unit</th>
            @if ($showCurrencyAndPrice)
                <th>Unit Price</th>
                <th>Currency</th>
            @endif
            <th>Supplier</th>
            <th>Location</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($inventories as $inventory)
            <tr>
                <td>{{ $inventory->name }}</td>
                <td>{{ $inventory->category ? $inventory->category->name : '-' }}</td>
                <td>{{ $inventory->quantity }}</td>
                <td>{{ $inventory->unit }}</td>
                @if ($showCurrencyAndPrice)
                    <td>{{ number_format($inventory->price, 2, ',', '.') }}</td>
                    <td>{{ $inventory->currency ? $inventory->currency->name : '' }}</td>
                @endif
                <td>{{ $inventory->supplier }}</td>
                <td>{{ $inventory->location }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
