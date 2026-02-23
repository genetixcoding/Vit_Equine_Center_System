@extends('layouts.admin')
<title>{{ $stud->name }} Details</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-3">
                        <h4 class="text-white text-capitalize ps-3">Stud Details Table</h4>
                        <h6 class="text-white text-capitalize ps-3">Stud Name:- {{ $stud->name }}</h6>
                    </div>
                </div>
                <div class="card-body px-0 pb-2 m-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0 text-center">
                            <thead>
                                <tr>
                                    <th> @if ($stud->image == null)
                                        <img src="{{ asset ('assets/img/image.png') }}" style="width: 100px">
                                        @else
                                        <img src="{{ asset('assets/Uploads/Studs/'.$stud->image)}}" style="width: 50px">
                                        @endif
                                    </th>
                                    <th>
                                        @if ($stud->status == 0)
                                            Under Observation
                                        @else
                                            Rejected Stud
                                        @endif
                                    </th>
                                </tr>
                                <tr>
                                    <th>{{$stud->description}}</th>
                                    <th>{{ $horses->count()}} Horses Attached</th>
                                </tr>
                                @if ($stud->users->count() == 0)
                                <th>
                                        <a data-bs-toggle="modal" data-bs-target="#exampleModaladdclint" class="text-sm">Add Clint</a>
                                        <div class="modal fade" id="exampleModaladdclint" tabindex="-1" aria-labelledby="exampleModallabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="card-body py-5">
                                                        <form action="{{ url('insert-user') }}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="row m-5">
                                                                <input type="hidden" name="stud_id" value="{{ $stud->id }}">

                                                                <div class="col-md-12">
                                                                    <select name="major" class="form-select">
                                                                        <option value="">Select Memmber Major</option>
                                                                        <option value="0">Owner</option>
                                                                        <option value="1">Accountant</option>
                                                                        <option value="3">Ostler</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-6 mb-2">
                                                                    <label for="">Name</label>
                                                                    <input type="text" class="form-control"  name="name" required placeholder="Enter Name">
                                                                </div>
                                                                <div class="col-md-6 mb-2">
                                                                    <label for="">Email</label>
                                                                    <input type="text" class="form-control"  name="email" required placeholder="Enter Email">
                                                                </div>
                                                                <div class="col-md-6 mb-2">
                                                                    <label for="">Password</label>
                                                                    <input type="number" class="form-control"  name="password" required placeholder="Enter Password">
                                                                </div>
                                                                <div class="col-md-6 mb-2">
                                                                    <label for="">Phone Number</label>
                                                                    <input type="number" class="form-control" name="phone" required placeholder="Enter Phone Number" maxlength="15">
                                                                </div>
                                                                <div class="col-12 mb-2">
                                                                    <label for="">description</label>
                                                                    <textarea type="text" class="form-control"  name="description" required placeholder="Enter Description"></textarea>
                                                                </div>
                                                                <div class="m-2">
                                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </th>
                                @else
                                @foreach ($stud->users as $item)
                                <tr>
                                    <td>
                                        {{ $item->name }}
                                    </td>
                                    <td>
                                        @if ($item->major == 0)
                                            Owner
                                        @elseif($item->major == 1)
                                            Accountant
                                        @elseif($item->major == 3)
                                            Ostler
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        {{ $item->phone }}
                                    </td>
                                    <td>
                                        {{ $item->description }}
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </thead>
                        </table>
                        <br>
                    </div>
                </div>
                <div class="text-center">
                    @if ($stud->visits->count() > 0)
                    <button type="button" class="btn btn-link float-end">
                        <a href="{{ url('Studs/Visit/Table/'.$stud->id) }}" class="text-primary m-2">
                            Visits Table
                        </a>
                    </button>
                    @endif
                    @if ($stud->externalinvoices->count() > 0)
                    <button type="button" class="btn btn-link float-end">
                        <a href="{{ url('Studs/Externalinvoices/Table/'.$stud->name) }}" class="text-primary m-2">
                            External Invoices Table
                        </a>
                    </button>
                    @endif
                </div>
                <div class="container-fluid py-4">
                    <div class="row mb-4">
                       @php
                        $mares = $horses->filter(fn($item) => $item->gender == 0);
                        @endphp
                        @if ($mares->count() > 0)
                        <div class="col-12 col-md-6 col-lg-6 mb-4">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <div class="row">
                                        <div class="col-lg-6 col-7">
                                            <h6>Attached Mares</h6>
                                            <i class="fa fa-check text-info" aria-hidden="true"></i>
                                        </div>
                                        <div class="col-lg-6 col-5 my-auto text-end">
                                            <div class="dropdown float-lg-end pe-4">
                                                <a class="cursor-pointer" id="dropdownTable" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fa fa-ellipsis-v text-secondary"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body table-responsive px-0 pb-2">
                                    <table class="table align-items-center table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>Image</th>
                                                <th>Mares</th>
                                                <th>Visits</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            @foreach ($mares as $item)
                                                <tr>
                                                    <td>
                                                        @if ($item->image)
                                                            <img src="{{ asset('assets/Uploads/Studs/'.$item->image) }}" data-bs-toggle="modal" data-bs-target="#exampleModalImage{{ $item->id }}" style="width: 70px; border-radius: 50%;">
                                                        @else
                                                            <img src="{{ asset('assets/img/image.png') }}" style="width: 70px; border-radius: 50%;">
                                                        @endif
                                                        <div class="modal fade" id="exampleModalImage{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="card-body">
                                                                        <img src="{{ asset('assets/Uploads/Studs/'.$item->image) }}" class="img-fluid" alt="item Image">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a href="{{ url('Stud/'.$stud->name.'/'.$item->name) }}"><h6 class="mb-0 text-sm">{{ $item->name }}</h6></a>
                                                    </td>
                                                    <td>{{ $item->visitdesc->count() }} V</td>
                                                    <td>
                                                        <a href="{{url('edit-horse/'.$item->name)}}" class="text-sm text-info">Edit</a>
                                                        <a href="{{url('delete-horse/'.$item->id)}}" class="text-sm text-danger" onclick="return confirm('Are you sure you want to delete it ?')">Delete</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="pages text-center">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @php
                            $stallions = $horses->filter(fn($item) => $item->gender == 1);
                        @endphp
                        @if ($stallions->count() > 0)
                        <div class="col-12 col-md-6 col-lg-6 mb-4">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <div class="row">
                                        <div class="col-lg-6 col-7">
                                            <h6>Attached Stallions</h6>
                                            <i class="fa fa-check text-info"></i>
                                        </div>
                                        <div class="col-lg-6 col-5 my-auto text-end">
                                            <div class="dropdown float-lg-end pe-4">
                                                <a class="cursor-pointer" id="dropdownTable" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fa fa-ellipsis-v text-secondary"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body table-responsive px-0 pb-2">
                                    <table class="table align-items-center table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>Image</th>
                                                <th>Horses Name</th>
                                                <th>Visits Count</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            @foreach ($stallions as $item)
                                                <tr>
                                                    <td>
                                                        @if ($item->image)
                                                            <img src="{{ asset('assets/Uploads/Studs/'.$item->image) }}" data-bs-toggle="modal" data-bs-target="#exampleModalImage{{ $item->id }}" style="width: 70px; border-radius: 50%;">
                                                        @else
                                                            <img src="{{ asset('assets/img/image.png') }}" style="width: 70px; border-radius: 50%;">
                                                        @endif
                                                        <div class="modal fade" id="exampleModalImage{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="card-body">
                                                                        <img src="{{ asset('assets/Uploads/Studs/'.$item->image) }}" class="img-fluid" alt="item Image">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a href="{{ url('Stud/'.$stud->name.'/'.$item->name) }}"><h6 class="mb-0 text-sm">{{ $item->name }}</h6></a>
                                                    </td>
                                                    <td>{{ $item->visitdesc->count() }} Visits</td>
                                                    <td>
                                                        <a href="{{url('edit-horse/'.$item->name)}}" class="text-sm text-info">Edit</a>
                                                        <a href="{{ url('Stud/'.$stud->name.'/'.$item->name) }}" class="text-sm text-primary">Details</a>
                                                        <a href="{{url('delete-horse/'.$item->id)}}" class="text-sm text-danger" onclick="return confirm('Are you sure you want to delete it ?')">Delete</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="pages text-center">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
