<ul id="venue-list">
@if(count($venues) == 0)
    > No venues currently added
@else
    @foreach ($venues as $venue)
        <li>
            <h4>{{ $venue->venue_name }}</h4>
            <ul>
                <li><span>Contact:</span> {{ $venue->contact_name }}</li>
                <li><span>Email:</span> {{ $venue->contact_email }}</li>
                <li><span>Tel:</span> {{ $venue->contact_telephone }}</li>
            </ul>
        </li>
    @endforeach
@endif
</ul>