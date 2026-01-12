@props(['headers'])

<div class="table-responsive">
    <table {{ $attributes->merge(['class' => 'table table-bordered table-striped']) }}>
        <thead>
            <tr>
                @foreach($headers as $header)
                    <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            {{ $slot }}
        </tbody>
    </table>
</div>
