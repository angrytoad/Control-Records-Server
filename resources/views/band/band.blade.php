@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">{{$band->name}}</div>
                    <div class="panel-body">
                        <div class="col-sm-12 col-lg-6">
                            <h4>Edit Band Details</h4>
                            <form id="edit-band-form" method="post" action="/bands/{{$band->id}}/edit">
                                <div>
                                    <label>Band name</label>
                                    <input type="text" name="name" placeholder="Band Name" value="{{$band->name}}" class="col-sm-12" />
                                </div>
                                <div>
                                    <label>Primary Contact Name</label>
                                    <input type="text" name="primary_name" placeholder="Primary Contact Name" value="{{$band->primary_name}}" class="col-sm-12" />
                                </div>
                                <div>
                                    <label>Primary Contact Email</label>
                                    <input type="text" name="primary_email" placeholder="Primary Contact Email" value="{{$band->primary_email}}" class="col-sm-12" />
                                </div>
                                <div>
                                    <label>Primary Contact Telephone</label>
                                    <input type="text" name="primary_telephone" placeholder="Primary Contact Telephone" value="{{$band->primary_telephone}}" class="col-sm-12" />
                                </div>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button class="btn">Edit band</button>
                            </form>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <h4>Band Information</h4>
                            <div>
                                <p><strong>Band: </strong>{{$band->name}}</p>
                                <p><strong>Primary Contact Name: </strong>{{$band->primary_name}}</p>
                                <p><strong>Primary Email: </strong>{{$band->primary_email}}</p>
                                <p><strong>Primary Telephone: </strong>{{$band->primary_telephone}}</p>
                            </div>
                            <div>
                                <button class="btn btn-danger" onClick="bandOptions.deleteBand({{$band->id}})">Delete This Band</button>
                                <a href="https://ctrl-records.com/band/{{$band->url_safe_name}}" target="_blank"><button class="btn btn-info">View Band Page</button></a>
                                <a href="/bands/{{$band->id}}/additional"><button class="btn btn-info">Edit Additional Content</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var bandOptions = {
            deleteBand: function($id){
                swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then(function() {
                    window.location.href = location.pathname+'/delete';
                })
            }
        };
    </script>
@endsection

