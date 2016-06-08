<table class="table table-bordered table-striped table-hover">
    <thead>
    <tr>
        <th>S.No</th>
        <th>Status</th>
        <th>Price</th>
        <th>Created date</th>
        <th>Updated date</th>
    </tr>
    </thead>
    <tbody>
    @if(!$prices->isEmpty())
        {{-- */$x=0;/* --}}
        @foreach($prices as $price)
            {{-- */$x++;/* --}}
            <tr>
                <td>{{ $x }}</td>
                <td>{{ $price->status ? "Active" : "Inactive" }}</td>
                <td>${{ $price->price }}</td>
                <td>{{ $price->created_at }}</td>
                <td>{{ $price->created_at == $price->updated_at && $price->status ? '-' : $price->updated_at }}</td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="5">No Result.</td>
        </tr>
    @endif
    </tbody>
</table>
