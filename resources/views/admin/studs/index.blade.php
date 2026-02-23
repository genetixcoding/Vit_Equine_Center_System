@extends('layouts.admin')
<title>Studs Page</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-3">
                <h4 class="text-white text-capitalize ps-3">Studs Table</h4>
                <h4 class="text-white text-capitalize pe-3 text-end">{{ $countstud }} Stud</h4>
              </div>
            </div>
            @if ($countstud > 0)
            <div class="card-header p-0 position-relative mt-n4 mt-3 z-index-2">
                <div class="text-center">
                    <button type="button" class="btn btn-link float-end" data-bs-toggle="modal" data-bs-target="#exampleModal">
                       ({{ $rejectedstudscount }}) Rejected Studs
                    </button>
                    <button type="button" class="btn btn-link float-end">
                        <a href="{{url('add-stud/')}}">Add Stud</a>
                    </button>
                </div>
            </div>
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="card-body py-5">
                            <table class="table align-items-center mb-0 text-center">
                                <thead">
                                    <tr>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Attached Horse</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($studs as $item)
                                    @if ($item->status == 1)
                                    <tr>
                                        <td>
                                            @if ($item->image == null)
                                            <img src="{{ asset ('assets/img/image.png') }}" style="width: 100px">
                                            @else
                                            <img src="{{ asset('assets/Uploads/Studs/'.$item->image)}}" style="width: 50px">
                                            @endif
                                        </td>
                                        <td><a href="{{url('Details/Stud/'.$item->id)}}">{{$item->name}}</a></td>
                                        <td>
                                            {{$item->horses->count()}}
                                        </td>
                                        <td>{{ date('d/M/y', strtotime($item->created_at)) }}</td>
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body px-0 pb-2 m-2">
                <div class="table-responsive">
                <table class="table align-items-center mb-0 text-center">
                    <thead style="background-color: hsl(0, 0%, 96%)">
                        <tr>
                            <th style="background-color: #338ded">Image</th>
                            <th style="background-color: #338ded">Name</th>
                            <th style="background-color: #338ded">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($studs as $item)
                        @if ($item->status == 0)
                        <tr>
                            <td>
                            @if ($item->image == null)
                                <img src="{{ asset ('assets/img/image.png') }}" style="width: 50px; border-radius: 50%;">
                            @else
                                <img src="{{ asset('assets/Uploads/Studs/'.$item->image)}}" data-bs-toggle="modal" data-bs-target="#exampleModalImage{{ $item->id }}" style="width: 50px; border-radius: 50%;">
                            @endif
                            <div class="modal fade" style="background-color: unset;" id="exampleModalImage{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModallabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">
                                        <div class="card-body">
                                            <img src="{{ asset('assets/Uploads/Studs/'.$item->image)}}" class="img-fluid" alt="item Image">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </td>
                            <td><a href="{{url('Details/Stud/'.$item->id)}}">{{$item->name}}</a></td>
                            <td>
                                <a class="text-primary  text-sm" href="{{url('edit-stud/'.$item->id)}}">Edit</a>
                                <a class="text-danger  text-sm" href="{{url('delete-stud/'.$item->id)}}"onclick="return confirm('Are you sure you want to delete it ?')">Delete</a>
                                <a class="text-info  text-sm" href="{{url('Details/Stud/'.$item->id)}}">Details</a>
                            </td>
                        </tr>
                        @endif
                        @endforeach
                        <div class="pages text-center">
                            {{ $studs->links() }}
                        </div>
                    </tbody>
                </table>
            </div>
            @else
                <div class="text-center">
                    <h5>No Studs Found</h5>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
