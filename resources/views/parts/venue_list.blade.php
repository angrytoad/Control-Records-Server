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
            <div class="venue-options">
                <a href="/venues/{{$venue->id}}">
                    <button class="btn btn-info">
                        Edit
                    </button>
                </a>
            </div>
        </li>
    @endforeach
@endif
</ul>