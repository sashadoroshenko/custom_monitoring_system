<table class="table table-bordered table-striped table-hover">
    <thead>
    <tr>
        <th>S.No</th>
        <th>Status</th>
        <th>Stock</th>
        <th>Created date</th>
        <th>Updated date</th>
    </tr>
    </thead>
    <tbody>
    @if(!$stocks->isEmpty())
        {{-- */$x=0;/* --}}
        @foreach($stocks as $stock)
            {{-- */$x++;/* --}}
            <tr>
                <td>{{ $x }}</td>
                <td>{{ $stock->status ? "Active" : "Inactive" }}</td>
                <td>{{ $stock->stock }}</td>
                <td>{{ $stock->created_at }}</td>
                <td>{{ $stock->created_at == $stock->updated_at && $stock->status ? '-' : $stock->updated_at }}</td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="5">No Result.</td>
        </tr>
    @endif
    </tbody>
</table>
