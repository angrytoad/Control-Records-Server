@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Gig #{{$gig->id}}</div>
                    <div class="panel-body">
                        <div class="col-sm-12 col-lg-6">
                            <h4>Edit Gig Details</h4>
                            <form id="edit-gig-form" method="post" action="/gigs/{{$gig->id}}/edit">
                                <div>
                                    <label>Date & Time</label>
                                    <input class="col-sm-12" type="text" name="time" value="{{\Carbon\Carbon::parse($gig->date)->format('Y/m/d H:i')}}" id="datetimepicker"/>
                                </div>
                                <div>
                                    <label>Additional Comments</label>
                                    <textarea class="col-sm-12" name="additional-comments">{{$gig->additional_comments}}</textarea>
                                </div>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button class="btn">Update Gig</button>
                            </form>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <h4>Gig Information</h4>
                            <div>
                                <p>@ <a href="/venues/{{$gig->venue->id}}">{{$gig->venue->venue_name}}</a></p>
                                <p><strong>Band: </strong><a href="/bands/{{$gig->band->id}}">{{$gig->band->name}}</a></p>
                                <p><strong>Time: </strong> {{\Carbon\Carbon::parse($gig->date)->toDayDateTimeString()}}</p>
                                <p><strong>Additional Comments: </strong> {{$gig->additional_comments}}</p>
                            </div>
                            <div>
                                <button class="btn btn-danger" onClick="gigOptions.deleteGig({{$gig->id}})">Delete This Gig</button>
                                <a href="http://ctrl-records.com/gig/{{$gig->id}}" target="_blank"><button class="btn btn-info">View Gig Page</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var gigOptions = {
            deleteGig: function($id){
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

        $(document).ready(function(){
            $.datetimepicker.setLocale('en');

            var today = new Date();
            var date = today.getDate+'/'+today.getMonth()+'/'+today.getFullYear();

            $('#datetimepicker').datetimepicker({
                dayOfWeekStart : 1,
                lang:'en',
                defaultDate: '{{\Carbon\Carbon::parse($gig->date)->format('Y/m/d')}}',
                defaultTime: '{{\Carbon\Carbon::parse($gig->date)->format('H:i')}}',
                step:10
            });
        });
    </script>
@endsection

