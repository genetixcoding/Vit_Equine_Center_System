@extends('layouts.admin')
<title>Treatment Details Page</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-5">
                <h4 class="text-white text-capitalize ps-3">
                    Treatment Details Table</h4>
              </div>
            </div>
            <div>

            </div>
            <div class="container-fluid p-2 mt-5">
                <div class="row">
                    <div class="col-12">
                        <div class="card m-auto">
                            <div class="card-header">
                                <div class="row">
                                    <div class="">
                                        <h6 class="text-primary">Treatment Details: </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body table-responsive m-2 px-0 pb-2px-0 pb-2">
                                <table class="table  text-center  table-striped mb-0">
                                    <tbody class="table  text-center mb-0">
                                        <tr>
                                            <th colspan="3" style="background-color: #4a91ee; color: #fff;">
                                                Treatment: {{ date('h:iA d/M/y', strtotime($treatment->created_at)) }}
                                            </th>
                                        </tr>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    {{ optional(value: $treatment->user)->name }}
                                                </td>
                                                <td>
                                                    @if ($treatment->horse)
                                                    <a href="{{url('Horse/Details/'.$treatment->horse->name)}}">
                                                        {{ $treatment->horse->name }}
                                                    </a>
                                                    @elseif ($treatment->embryo->localhorsename)
                                                    <a href="{{url('Details/embryo-/'.$treatment->embryo->id)}}">
                                                        {{ $treatment->embryo->localhorsename }}
                                                    </a>
                                                    @else
                                                    Unknown Horse
                                                    @endif
                                                </td>
                                                <td>
                                                    <a data-bs-toggle="modal" data-bs-target="#exampleModalTreatment{{ $treatment->id }}" class="text-sm text-info">Edit</a>
                                                    <a href="{{ url('delete-treatment/'.$treatment->id) }}" class="text-sm text-danger" onclick="return confirm('Are you sure you want to delete this treatment?')"onclick="return confirm('Are you sure you want to delete it ?')">Delete</a>
                                                </td>
                                                {{-- Modal for editing treatment --}}
                                                <div class="modal fade" id="exampleModalTreatment{{ $treatment->id }}" tabindex="-1" aria-labelledby="exampleModallabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="card-body py-5">
                                                                <form action="{{ url('update-treatment/'.$treatment->id) }}" method="POST" enctype="multipart/form-data">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="row">
                                                                        <h3 class="text-center text-primary">Edit Treatment Details</h3>
                                                                        <div class="mb-2 col-6">
                                                                            <label for="">(Optional)</label>
                                                                            <select class="form-select" name="horse_id">
                                                                                @if ($treatment->horse_id)
                                                                                <option value="{{ $treatment->horse_id }}">{{ $treatment->horse->name }}</option>
                                                                                <option value="">Remove Horse ??</option>
                                                                                @else
                                                                                <option value="">Select Horse</option>
                                                                                @endif
                                                                                @foreach ($horses as $itemhorse)
                                                                                <option value="{{ $itemhorse->id }}">{{ $itemhorse->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="mb-2 col-6">
                                                                            <label for="">(Optional)</label>
                                                                            <select class="form-select" name="embryo_id">
                                                                                @if ($treatment->embryo_id)
                                                                                <option value="{{ $treatment->embryo_id }}">{{ $treatment->embryo->localhorsename }}</option>
                                                                                <option value="">Remove Embryo ??</option>
                                                                                @else
                                                                                <option value="">Select Embryo</option>
                                                                                @endif
                                                                                @foreach ($embryos as $itemembryo)
                                                                                <option value="{{ $itemembryo->id }}">{{ $itemembryo->localhorsename }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="mb-2 col-8">
                                                                            <label for=""></label>
                                                                            <select class="form-select" name="user_id">
                                                                                <option value="{{ $treatment->user->id }}">{{ $treatment->user->name ?? 'Name' }}</option>
                                                                                @foreach ($users as $itemuser)
                                                                                <option value="{{ $itemuser->id }}">{{ $itemuser->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="my-2 col-4">
                                                                            <button
                                                                                type="submit"
                                                                                class="btn btn-primary float-center">
                                                                                Update
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </tr>
                                            @foreach ($treatment->treatmentdesc as $treatmentdesc)

                                                    <tr>
                                                        <td>{{ $treatmentdesc->pharmacy->item ?? 'Unknown'}}
                                                        <td>
                                                            {{ $treatmentdesc->description ?? 'Unknown'}}
                                                            {{ $treatmentdesc->qty ?? 'Unknown'}} {{ $treatmentdesc->type ?? ''}}
                                                        </td>
                                                        <td>
                                                            <a data-bs-toggle="modal" data-bs-target="#exampleModalDesc{{ $treatmentdesc->id }}" class="text-sm text-info">Edit</a>
                                                            <a href="{{ url('delete-treatmentdesc/'.$treatmentdesc->id) }}" class="text-sm text-danger" onclick="return confirm('Are you sure you want to delete this treatment?')"onclick="return confirm('Are you sure you want to delete it ?')">Delete</a>
                                                        </td>
                                                    </tr>
                                                    {{-- Modal for editing treatmentdesc --}}
                                                            <div class="modal fade" id="exampleModalDesc{{ $treatmentdesc->id }}" tabindex="-1" aria-labelledby="exampleModallabel" aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="card-body py-5">
                                                                            <form action="{{ url('update-treatmentdesc/'.$treatmentdesc->id) }}" method="POST" enctype="multipart/form-data">
                                                                                @csrf
                                                                                @method('PUT')
                                                                                <div class="row">
                                                                                    <h3 class="text-center text-primary">Edit Treatment Details</h3>
                                                                                    <div class="mt-4 col-6">
                                                                                        <select class="form-select" required name="pharmacy_id">
                                                                                            <option value="{{ $treatmentdesc->pharmacy_id }}">{{ $treatmentdesc->pharmacy->item ?? 'Select Medicine' }}</option>
                                                                                            @foreach ($pharmacy as $itempharmacy)
                                                                                            <option value="{{ $itempharmacy->id }}">{{ $itempharmacy->item }} / {{ $itempharmacy->unitqty }} {{ $itempharmacy->type }}</option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="mb-2 col-6">
                                                                                        <label for="">Insert Description</label>
                                                                                        <input type="text" required class="form-control" name="description" value="{{ $treatmentdesc->description }}" placeholder="Insert Description">
                                                                                    </div>
                                                                                    <div class="mb-2 col-6">
                                                                                        <label for="">Insert Doses</label>
                                                                                        <input type="number" required class="form-control" placeholder="Insert Doses" name="qty" value="{{ $treatmentdesc->qty }}" min="1">
                                                                                    </div>
                                                                                    <div class="mb-2 col-6">
                                                                                        <label for="">Insert Type</label>
                                                                                        <input type="text" required class="form-control" placeholder="Insert Type" name="type" value="{{ $treatmentdesc->type }}">
                                                                                    </div>
                                                                                    <button type="submit" class="btn btn-primary float-end">
                                                                                        Update
                                                                                    </button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>


                                            @endforeach

                                        </tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
