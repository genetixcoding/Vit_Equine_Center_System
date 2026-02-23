@extends('layouts.admin')
<title>Horses Page</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-5">
                <h4 class="text-white text-capitalize ps-3">Horses Table</h4>
                <h4 class="text-white text-capitalize pe-3 text-end">{{ $counthorse }} horse</h4>
              </div>
            </div>
            <div>
                <div class="card-header p-0 position-relative mt-n4 mt-3 z-index-2">
                    <div class="text-center">
                        <button type="button" class="btn btn-link float-end" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            rejected Horses
                        </button>
                    </div>
                    <div class="text-center">
                        <button type="button" class="btn btn-link float-end">
                            <a class="text-primary m-1" data-bs-toggle="modal" data-bs-target="#exampleModaladd-horse">Add New Horse</a>
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
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($horses as $item)
                                            <tr>
                                                <td>
                                                    @if ($item->image == null)
                                                    <img src="{{ asset ('assets/img/image.png') }}" style="width: 100px">
                                                    @else
                                                    <img src="{{ asset('assets/Uploads/Horses/'.$item->image)}}" style="width: 50px">
                                                    @endif
                                                </td>
                                                <td>{{$item->name}}</td>
                                                <td>
                                                    <a href="{{url('edit-horse/'.$item->name)}}" class="text-primary m-1 text-sm">Edit</a>
                                                    <a href="{{url('Horse/Details/'.$item->name)}}" class="text-info m-1 text-sm">Details</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="exampleModaladd-horse" tabindex="-1" aria-labelledby="exampleModallabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="card-body py-5">
                                <form action="{{ url('insert-horse') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <h3 class="text-center text-primary">Add New Horse</h3>
                                        <div class="col-6 m-auto">
                                            <label for="">Name</label>
                                            <input type="text" class="form-control" required name="name">
                                        </div>
                                        {{-- <div class="col-3 m-auto">
                                            <label for="">age</label>
                                            <input type="number" class="form-control" required name="age">
                                        </div> --}}
                                        <div class="col-3 m-auto">
                                            <label for="">Male ??!</label>
                                            <input type="checkbox" name="gender">
                                        </div>
                                        <div class="m-2">
                                            <label for="">Shelter ??!</label>
                                            <input type="text" placeholder="Inter Horse Owner" class="form-control" name="shelter">
                                        </div>
                                        <div class="m-2">
                                            <label for="">Description</label>
                                            <textarea required name="description" class="form-control"></textarea>
                                        </div>
                                        <div class="m-2">
                                            <label for="">Choose Image</label>
                                            <input type="file" required name="image" class="form-control">
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
            </div>
            <div class="container-fluid py-4">
                <div class="row mb-4">
                    @if ($horsesfemale->isNotEmpty())
                        <div class="col mb-4">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <div class="row">
                                        <div class="col-lg-6 col-7">
                                            <h6>Attached Mares Female Horses</h6>
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
                                <div class="card-body table-responsive m-2 px-0 pb-2px-0 pb-2">
                                    <table class="table  text-center  table-striped mb-0">
                                        <thead>
                                            <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Image</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Mares name</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table  text-center mb-0">
                                            @foreach ($horsesfemale as $item)
                                                <tr>
                                                    <td>
                                                        @if ($item->image == null)
                                                        <img src="{{ asset ('assets/img/image.png') }}" style="width: 100px">
                                                        @else
                                                        <img src="{{ asset('assets/Uploads/Horses/'.$item->image)}}" data-bs-toggle="modal" data-bs-target="#exampleModalImage{{ $item->id }}" style="width: 50px">
                                                        @endif
                                                        <div class="modal fade" style="background-color: unset;" id="exampleModalImage{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModallabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="card-body">
                                                                        <img src="{{ asset('assets/Uploads/Horses/'.$item->image)}}" class="img-fluid" alt="Horse Image">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="m-2">
                                                            <h6>{{ $item->name }}</h6>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="m-2">
                                                            <a href="{{url('edit-horse/'.$item->name)}}" class="text-sm text-primary">Edit</a>
                                                            <a href="{{ url('Horse/Details/'.$item->name) }}" class="text-sm text-info">Details</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="pages text-center">
                                        {{ $horsesfemale->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($horsesmale->isNotEmpty())
                    <div class="col mb-4">
                        <div class="card">
                            <div class="card-header pb-0">
                                <div class="row">
                                    <div class="col-lg-6 col-7">
                                        <h6>Attached Male Horses</h6>
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
                            <div class="card-body table-responsive m-2 px-0 pb-2px-0 pb-2">
                                <table class="table text-center  table-striped mb-0">
                                    <thead>
                                        <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Image</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Horses name</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table text-center mb-0">
                                        @foreach ($horsesmale as $item)
                                            <tr>
                                                <td>
                                                    @if ($item->image == null)
                                                    <img src="{{ asset ('assets/Empire.png') }}" style="width: 50px">
                                                    @else
                                                    <img src="{{ asset('assets/Uploads/Horses/'.$item->image)}}"  data-bs-toggle="modal" data-bs-target="#exampleModalImage{{ $item->id }}" style="width: 50px">
                                                    @endif
                                                    <div class="modal fade" style="background-color: unset;" id="exampleModalImage{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModallabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                                            <div class="modal-content">
                                                                <div class="card-body">
                                                                    <img src="{{ asset('assets/Uploads/Horses/'.$item->image)}}" class="img-fluid" alt="Horse Image">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="my-2">
                                                        <h6>{{ $item->name }}</h6>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="my-2">
                                                        <a href="{{url('edit-horse/'.$item->name)}}" class="text-sm text-primary">Edit</a>
                                                        <a href="{{ url('Horse/Details/'.$item->name) }}" class="text-sm text-info">Details</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="pages text-center">
                                    {{ $horsesmale->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="container-fluid py-4">
                <div class="row mb-4">
                    @if ($horsesfemaleshelter->isNotEmpty())
                    <div class="col mb-4">
                        <div class="card">
                            <div class="card-header pb-0">
                                <div class="row">
                                    <div class="col-lg-6 col-7">
                                        <h6>Attached Mares Female Shelter Horses</h6>
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
                            <div class="card-body table-responsive m-2 px-0 pb-2px-0 pb-2">
                                <table class="table  text-center  table-striped mb-0">
                                    <thead>
                                        <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">image</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Mares name</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table  text-center mb-0">
                                        @foreach ($horsesfemaleshelter as $item)
                                            <tr>
                                                <td>
                                                    @if ($item->image == null)
                                                    <img src="{{ asset ('assets/img/image.png') }}" style="width: 100px">
                                                    @else
                                                    <img src="{{ asset('assets/Uploads/Horses/'.$item->image)}}" data-bs-toggle="modal" data-bs-target="#exampleModalImage{{ $item->id }}" style="width: 50px">
                                                    @endif
                                                    <div class="modal fade" style="background-color: unset;" id="exampleModalImage{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModallabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                                            <div class="modal-content">
                                                                <div class="card-body">
                                                                    <img src="{{ asset('assets/Uploads/Horses/'.$item->image)}}" class="img-fluid" alt="Horse Image">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="my-2">
                                                        <h6>{{ $item->name }}</h6>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="my-2">
                                                        <a href="{{url('edit-horse/'.$item->name)}}" class="text-sm text-primary">Edit</a>
                                                        <a href="{{ url('Horse/Details/'.$item->name) }}" class="text-sm text-info">Details</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="pages text-center">
                                    {{ $horsesfemaleshelter->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if ($horsesmaleshelter->isNotEmpty())
                        <div class="col mb-4">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <div class="row">
                                        <div class="col-lg-6 col-7">
                                            <h6>Attached Male Shelter Horses</h6>
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
                                <div class="card-body table-responsive m-2 px-0 pb-2px-0 pb-2">
                                    <table class="table text-center  table-striped mb-0">
                                        <thead>
                                            <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Image</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Horses name</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table text-center mb-0">
                                            @foreach ($horsesmaleshelter as $item)
                                                <tr>
                                                    <td>
                                                        @if ($item->image == null)
                                                        <img src="{{ asset ('assets/Empire.png') }}" style="width: 50px">
                                                        @else
                                                        <img src="{{ asset('assets/Uploads/Horses/'.$item->image)}}"  data-bs-toggle="modal" data-bs-target="#exampleModalImage{{ $item->id }}" style="width: 50px">
                                                        @endif
                                                        <div class="modal fade" style="background-color: unset;" id="exampleModalImage{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModallabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="card-body">
                                                                        <img src="{{ asset('assets/Uploads/Horses/'.$item->image)}}" class="img-fluid" alt="Horse Image">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="my-2">
                                                            <h6>{{ $item->name }}</h6>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="my-2">
                                                            <a href="{{url('edit-horse/'.$item->name)}}" class="text-sm text-primary">Edit</a>
                                                            <a href="{{ url('Horse/Details/'.$item->name) }}" class="text-sm text-info">Details</a>
                                                            <a href="{{url('delete-horse/'.$item->id)}}"class="text-sm text-danger" onclick="return confirm('Are you sure you want to delete it ?')">Delete</a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="pages text-center">
                                        {{ $horsesmaleshelter->links() }}
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
@endsection
