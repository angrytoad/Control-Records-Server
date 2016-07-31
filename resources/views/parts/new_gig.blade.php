<div>
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form id="new-gig-form" method="post" action="/gigs/create">
        <div>
            <label>Venue</label>
            <select name="venue" class="col-sm-12">
                @foreach ($venues as $venue)
                    <option value="{{$venue->id}}">{{$venue->venue_name}}</option>
                @endforeach
            </select>
        </div>
        <div>
           <label>Band</label>
            <select name="band" class="col-sm-12">
                @foreach ($bands as $band)
                    <option value="{{$band->id}}">{{$band->name}}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Date & Time</label>
            <input class="col-sm-12" type="text" name="time" value="" id="datetimepicker"/>
        </div>
        <div>
            <label>Additional Comments</label>
            <textarea class="col-sm-12" name="additional-comments"></textarea>
        </div>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <button class="btn">Create gig</button>
    </form>

    <script>
        $(document).ready(function(){
            $.datetimepicker.setLocale('en');

            var today = new Date();
            var date = today.getDate+'/'+today.getMonth()+'/'+today.getFullYear();

            $('#datetimepicker').datetimepicker({
                dayOfWeekStart : 1,
                lang:'en',
                startDate:	date,
                step:10
            });
        });

    </script>
</div>
