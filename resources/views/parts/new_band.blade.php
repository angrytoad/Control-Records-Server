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
    <form id="new-band-form" method="post" action="/bands/create">
        <div>
            <label>Band name</label>
            <input type="text" name="name" placeholder="Band Name" class="col-sm-12" />
        </div>
        <div>
            <label>Primary Contact Name</label>
            <input type="text" name="primary_name" placeholder="Primary Contact Name" class="col-sm-12" />
        </div>
        <div>
            <label>Primary Contact Email</label>
            <input type="text" name="primary_email" placeholder="Primary Contact Email" class="col-sm-12" />
        </div>
        <div>
            <label>Primary Contact Telephone</label>
            <input type="text" name="primary_telephone" placeholder="Primary Contact Telephone" class="col-sm-12" />
        </div>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <button class="btn">Create band</button>
    </form>
</div>
