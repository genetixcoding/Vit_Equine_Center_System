@extends('layouts.admin')
<title>Staff Page</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n5 mx-3 z-index-2">
              <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-5">
                <h4 class="text-white text-capitalize ps-3">Staff Table

                </h4>
                <a class="text-white float-end  ps-3 p-1" data-bs-toggle="modal" data-bs-target="#exampleModalstaff">Add New Member</a>
              </div>
            </div>

                <div class="modal fade" id="exampleModalstaff" tabindex="-1" aria-labelledby="exampleModallabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="card-body py-5">
                                <form action="{{ url('insert-user') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row mt-3">
                                        @if (Auth::user()->role_as == 1)
                                        <div class="col-12 mb-1">
                                            <select name="role_as" class="form-select">
                                                <option value="" disabled selected>Select Role</option>
                                                 @if (Auth::user()->id == 1)
                                                <option value="1">Admin</option>
                                                @endif
                                                <option value="2">Manager</option>
                                            </select>
                                        </div>
                                        @endif
                                        <div class="col-6">
                                            <label for="">Select Memmber Major</label>
                                            <select name="major" class="form-select">
                                                @if (Auth::user()->role_as == 1)
                                                <option value="" disabled selected>Select Major</option>
                                                    <option value="1">Accountant</option>
                                                    <option value="2">Doctor</option>
                                                    <option value="3">Ostler</option>
                                                    <option value="4">Betar</option>
                                                @else
                                                    <option value="2">Doctor</option>
                                                    <option value="3">Ostler</option>
                                                    <option value="4">Betar</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <label for="">Name</label>
                                            <input type="text" class="form-control"  name="name" required placeholder="Insert Name">
                                        </div>
                                        <div class="col-12 mb-2">
                                            <label for="">Email</label>
                                            <input type="text" class="form-control"  name="email" required placeholder="Insert Email">
                                        </div>
                                        <div class="col-6 mb-2">
                                            <label for="">Password</label>
                                            <input type="number" class="form-control"  name="password" required placeholder="Insert Password">
                                        </div>
                                        <div class="col-6 mb-2">
                                            <label for="">Phone Number</label>
                                            <input type="number" class="form-control" name="phone" required placeholder="Insert Phone Number" maxlength="15">
                                        </div>
                                        <div class="col-12 mb-2">
                                            <label for="">Description</label>
                                            <textarea type="text" class="form-control"  name="description" required placeholder="Insert Description"></textarea>
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
            <div class="card-body m-2 table-responsive m-2 px-0 pb-2p-2">
                <table class="table table-bordered text-center table-responsive m-2 px-0 pb-2table-striped">
                   <thead>
                        <tr>
                            <th style="background-color: #338ded; color: #FFF;">Name</th>
                            <th style="background-color: #338ded; color: #FFF;">Description</th>
                        </tr>
                    </thead>
                    @foreach ($users as $item)
                    <tbody>

                    {{-- Edit User --}}
                    <div class="modal fade" id="exampleModaledit{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ url('update-user/'.$item->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="row m-2">

                                        @if (Auth::user()->role_as == 1)
                                        <div class="col-12 mb-2">
                                            <select name="role_as" class="form-select" required>
                                                @if (Auth::user()->id == 1)
                                                <option value="1" {{ $item->role_as == 1 ? 'selected' : '' }}>Admin</option>
                                                @endif
                                                <option value="2" {{ $item->role_as == 2 ? 'selected' : '' }}>Supervisor</option>
                                                <option value="0" {{ $item->role_as == 0 ? 'selected' : '' }}>Staff</option>
                                            </select>
                                        </div>
                                        @endif
                                        <div class="col-6">
                                            <label for="">Select Major</label>
                                            <select name="major" class="form-select">
                                                @if (Auth::user()->role_as == 1)
                                                    <option>Select</option>
                                                    <option value="1" {{ $item->major == 1 ? 'selected' : '' }}>Accountant</option>
                                                    <option value="2" {{ $item->major == 2 ? 'selected' : '' }}>Doctor</option>
                                                    <option value="3" {{ $item->major == 3 ? 'selected' : '' }}>Ostler</option>
                                                    <option value="4" {{ $item->major == 4 ? 'selected' : '' }}>Betar</option>
                                                @else
                                                    <option>Select</option>
                                                    <option value="2" {{ $item->major == 2 ? 'selected' : '' }}>Doctor</option>
                                                    <option value="3" {{ $item->major == 3 ? 'selected' : '' }}>Ostler</option>
                                                    <option value="4" {{ $item->major == 4 ? 'selected' : '' }}>Betar</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <label for="">Name</label>
                                            <input type="text" class="form-control" value="{{ $item->name }}" name="name" >
                                        </div>
                                        <div class="col-12 mb-2">
                                            <label for="">Email</label>
                                            <input type="text" class="form-control"  value="{{ $item->email }}" name="email" >
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label for="">Password</label>
                                            <input type="password" class="form-control"  value="{{ $item->password }}" name="password" >
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label for="">Phone Number</label>
                                            <input type="tel" class="form-control" value="{{ $item->phone }}" name="phone">
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <label for="">Description</label>
                                            <textarea type="text" class="form-control" name="description" required>{{ $item->description }}</textarea>
                                        </div>
                                        <div class="m-2">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    {{-- Details User --}}
                    <div class="modal fade" id="exampleModaldetails{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="row m-2">
                                    <div class="col-12 mb-2">
                                        <label for="">Email</label>
                                        <input type="text" class="form-control"  value="{{ $item->email }}" name="email" >
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="">Password</label>
                                        <input type="password" class="form-control" value="********" name="password" readonly>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="">Phone Number</label>
                                        <input type="tel" class="form-control" value="{{ $item->phone }}" name="phone">
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label for="">Description</label>
                                        <textarea type="text" class="form-control" name="description" required>{{ $item->description }}</textarea>
                                    </div>
                                    <div class="m-2">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if (Auth::user()->id == 1)
                        <tr>
                            <td>{{$item->name}}</td>
                            <td>
                                @if($item->role_as == 1)
                                    Admin
                                @elseif($item->role_as == 2)
                                    Supervisor
                                @else
                                    Staff
                                @endif
                                    /
                                @if ($item->major == 0)
                                    @if ($item->role_as == 1)
                                        Owner
                                    @elseif ($item->role_as == 2)
                                        Manager
                                    @endif
                                @elseif($item->major == 1)
                                    Accountant
                                @elseif($item->major == 2)
                                    Doctor
                                @elseif($item->major == 3)
                                    Ostler
                                @elseif($item->major == 4)
                                    Farrier
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <td>
                                @if ($item->phone == null)
                                    No PHone Information
                                @else
                                    {{$item->phone}}
                                @endif
                            </td>
                            <td>
                                <a data-bs-toggle="modal" data-bs-target="#exampleModaledit{{ $item->id }}" class="p-2 text-info">{{ __('language.edit') }}</a>
                                <a data-bs-toggle="modal" data-bs-target="#exampleModaldetails{{ $item->id }}" class="p-2 text-primary">{{ __('language.details') }}</a>
                                <a href="{{url('delete-user/'.$item->id)}}" class="p-2 text-danger">{{ __('language.delete') }}</a>
                            </td>
                        </tr>
                    @else
                        @if ($item->id !== 1)
                        <tr>
                            <td>{{$item->name}}</td>
                            <td>
                                @if($item->role_as == 1)
                                    Admin
                                @elseif($item->role_as == 2)
                                    Supervisor
                                @else
                                    Staff
                                @endif
                                    /
                                @if ($item->major == 0)
                                @if ($item->role_as == 1)
                                    Owner
                                @elseif ($item->role_as == 2)
                                    Manager
                                @endif
                                @elseif($item->major == 1)
                                    Accountant
                                @elseif($item->major == 2)
                                    Doctor
                                @elseif($item->major == 3)
                                    Ostler
                                @elseif($item->major == 4)
                                    Farrier
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <td>
                                @if ($item->phone == null)
                                    No PHone Information
                                @else
                                    {{$item->phone}}
                                @endif
                            </td>
                            <td>
                                <a data-bs-toggle="modal" data-bs-target="#exampleModaledit{{ $item->id }}" class="p-2 text-info">Edit</a>
                                <a data-bs-toggle="modal" data-bs-target="#exampleModaldetails{{ $item->id }}" class="p-2 text-primary">Details</a>
                                <a href="{{url('delete-user/'.$item->id)}}" class="p-2 text-danger"onclick="return confirm('Are you sure you want to delete it ?')">Delete</a>
                            </td>
                        </tr>
                        @endif
                    @endif
                    </tbody>
                    @endforeach
                </table>
                <div class="pages text-center">
                    {{ $users->links() }}
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
</div>
@endsection



