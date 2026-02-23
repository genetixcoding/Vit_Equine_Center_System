@extends('layouts.admin')
<title>Pharmacy Page</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-info shadow-info border-radius-lg pt-4 pb-3">
                <h4 class="text-white text-capitalize ps-3">Pharmacy Table</h4>
                <h4 class="text-white text-capitalize pe-3 text-end">{{ $countpharmacy }} Pharmacy</h4>
              </div>
            </div>
            <div class="text-center">
                <button type="button" class="btn btn-link float-end">
                    <a data-bs-toggle="modal" data-bs-target="#exampleModalpharmacy" class="text-sm">Add New Medicine</a>
                    <br>
                    <a href="#emptymedicine" class="mt-4 text-sm">Pharmacy shortages</a>
                </button>
            </div>
            <div>
                <div class="modal fade" id="exampleModalpharmacy" tabindex="-1" aria-labelledby="exampleModallabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="card-body py-5">
                                <form action="{{ url('insert-pharmacy') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <h3 class="text-center text-primary">Add New Medicine Details</h3>
                                        <div class="mb-2 col-12">
                                            <label for="">Medicine Description</label>
                                            <input type="text" required placeholder="Medicine Description" class="form-control" name="item">
                                        </div>
                                        <div class="mb-2 col-6">
                                            <label for="">Qty</label>
                                            <input type="number" step="0.01" required placeholder="Qty" class="form-control" name="qty">
                                        </div>
                                        <div class="mb-2 col-6">
                                            <label for="">Unit In One Package</label>
                                            <input type="text" required placeholder="Unit" class="form-control" name="unit">
                                        </div>
                                        <div class="mb-2 col-6">
                                            <label for="">Type</label>
                                            <input type="text" required placeholder="Type" class="form-control" name="type">
                                        </div>
                                        <div class="mb-2 col-6">
                                            <label for="">Price</label>
                                            <input type="number" required placeholder="Unit Price" class="form-control" name="price">
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
            @if ($pharmacy->isNotEmpty())
            <div class="row my-2">
                <div class="col-12 p-4">
                    <div class="card">
                        <div class="card-header pb-0">
                            <div class="row">
                                <div class="col-lg-6 col-7">
                                    <h6>All Pharmacy Medicines</h6>
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
                        <div class="card-body p-1">
                            <div class="table-responsive">
                                <table class="table text-center">
                                    @foreach ($pharmacy as $item)
                                    <tbody>
                                        <tr>
                                            <th style="background-color : #2f78cc; color: #FFF;">{{$item->item}}</th>
                                            <th style="background-color : #2f78cc; color: #FFF;">{{$item->price}} .EGP</th>
                                        </tr>
                                        <tr>
                                            <td>
                                                @if ($item->qty == 0)
                                                    <span class="text-danger">Out of Stock</span>
                                                @elseif (($item->unitqty / $item->unit) <= 2)
                                                    <span class="text-danger">
                                                        {{ number_format($item->qty, 2) }} package - Low Stock</span>
                                                @elseif (($item->unitqty / $item->unit) <= 5)
                                                    <span class="text-success">
                                                        {{ number_format($item->qty, 2) }} package - Low Stock</span>
                                                @else
                                                <span class="text-primary">
                                                    {{ number_format($item->qty, 2) }} package</span>
                                                @endif
                                            </td>
                                            <td>
                                               Basic: {{ $item->unitqty }}  {{ $item->type }}
                                            </td>

                                        </tr>

                                        <tr>
                                            <td>
                                                Storage: {{$item->unit}} {{ $item->type }}
                                            </td>
                                            <td>
                                                <a data-bs-toggle="modal" data-bs-target="#exampleModal{{ $item->id }}" class="text-sm text-info">Edit</a>
                                                <a href="{{url('delete-pharmacy/'.$item->id)}}"class="text-sm text-danger" onclick="return confirm('Are you sure you want to delete it ?')">Delete</a>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <div class="modal fade" id="exampleModal{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModallabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="card-body py-5">
                                                    <form action="{{ url('update-pharmacy/'.$item->id) }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="row">
                                                            <h3 class="text-center text-primary">Edit Medicine: {{ $item->item }}</h3>


                                                            <div class="mb-2 col-12">
                                                                <label for="">Medicine Description</label>
                                                                <input type="text" required placeholder="Medicine Description" value="{{ $item->item }}" class="form-control" name="item">
                                                            </div>
                                                            <div class="mb-2 col-6">
                                                                <label for="">Qty</label>
                                                                <input type="number" step="0.01" required placeholder="Qty" class="form-control" value="{{ $item->qty }}" name="qty">
                                                            </div>
                                                            <div class="mb-2 col-6">
                                                                <label for="">Unit In One Package</label>
                                                                <input type="text" required placeholder="Unit" class="form-control" value="{{ $item->unit }}" name="unit">
                                                            </div>
                                                            <div class="mb-2 col-6">
                                                                <label for="">Type</label>
                                                                <input type="text" required placeholder="Type" class="form-control" value="{{ $item->type }}" name="type">
                                                            </div>
                                                            <div class="mb-2 col-6">
                                                                <label for="">Price</label>
                                                                <input type="number" required placeholder="Unit Price" class="form-control" value="{{ $item->price }}" name="price">
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
                                    @endforeach
                                </table>
                            </div>
                            <div class="pages text-center">
                                {{ $pharmacy->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @if ($emptypharmacy->isNotEmpty())
            <div class="row my-2" id="emptymedicine">
                <div class="col-12 p-4">
                    <div class="card">
                        <div class="card-header pb-0">
                            <div class="row">
                                <div class="col-lg-6 col-7">
                                    <h6>Pharmacy shortages</h6>
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

                        <div class="card-body p-1">
                            <div class="table-responsive">
                                <table class="table text-center">
                                    @foreach ($emptypharmacy as $item)
                                    <tbody>
                                        <tr>
                                            <th colspan="2" style="background-color : #2f78cc; color: #FFF;">{{$item->item}}</th>
                                            <th style="background-color : #2f78cc; color: #FFF;">{{$item->price}} .EGP</th>

                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="text-danger">Out of Stock</span>
                                            </td>
                                            <td>
                                                {{$item->unit}} Unit
                                            </td>
                                            <td>
                                                <a data-bs-toggle="modal" data-bs-target="#exampleModal{{ $item->id }}" class="text-sm text-info">Edit</a>
                                                <a href="{{url('delete-pharmacy/'.$item->id)}}"class="text-sm text-danger" onclick="return confirm('Are you sure you want to delete it ?')">Delete</a>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <div class="modal fade" id="exampleModal{{ $item->name }}" tabindex="-1" aria-labelledby="exampleModallabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="card-body py-5">
                                                    <form action="{{ url('update-pharmacy') }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row">
                                                            <h3 class="text-center text-primary">Edit Medicine: {{ $item->item }}</h3>
                                                            <div class="mb-2 col-12">
                                                                <label for="">Medicine Description</label>
                                                                <input type="text" required placeholder="Medicine Description" value="{{ $item->item }}" class="form-control" name="item">
                                                            </div>

                                                            <div class="mb-2 col-4">
                                                                <label for="">Qty</label>
                                                                <input type="text" required placeholder="Qty" class="form-control" value="{{ $item->qty }}" name="qty">
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
                                    @endforeach
                                </table>
                            </div>
                            <div class="pages text-center">
                                {{ $emptypharmacy->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
