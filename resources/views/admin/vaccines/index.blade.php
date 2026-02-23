@extends('layouts.admin')
<title>Vaccines page </title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-info shadow-info border-radius-lg pt-4 pb-3">
                <h4 class="text-white text-capitalize pe-3 ps-3">Vaccines table</h4>
              </div>
            </div>
            <div class="text-center">
                <button type="button" class="btn btn-link float-end">
                    <a data-bs-toggle="modal" data-bs-target="#exampleModalpharmacy" class="text-sm">add Vaccines</a>
                </button>
            </div>

            <!-- Modal: multi-entry vaccine form -->
            <div>
                <div class="modal fade" id="exampleModalpharmacy" tabindex="-1" aria-labelledby="exampleModallabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="card-body py-4">
                                <form action="{{ url('insert-vaccine') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <h5 class="text-center mb-3">{{ __('language.add') }} {{ __('language.vaccine') }}</h5>

                                    <div class="itemvaccines">
                                        <div class="row">
                                            @php
                                                $key = 0;
                                            @endphp

                                            <div class="col-6 m-2">
                                                <select class="form-select" name="vaccinedesc[0][horse_id]">
                                                    <option value="">{{ __('language.select') }} {{ __('language.horse') }}</option>
                                                    @foreach ($horses as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3 col-11">
                                                <input type="text" required class="form-control" name="vaccinedesc[0][description]" placeholder="{{ __('language.write') }} {{ __('language.description') }}"/>
                                            </div>
                                            <div class="col-6">
                                                <label for="">{{ __('language.choose') }} {{ __('language.image') }}</label>
                                                <input type="file" name="vaccinedesc[0][image]" required class="w-100">
                                            </div>
                                            <div class="mb-3 col-6">
                                                <button type="button" id ="addMoreVaccines" class="btn btn-primary float-end" title="add more row">+</button>
                                            </div>
                                            <hr>
                                        </div>
                                    </div>
                                    <button
                                        type="submit"
                                        class="btn btn-primary float-end">
                                        Send
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row px-1 my-5">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <div class="row">
                                <div class="col-lg-6 col-7">
                                    <h6>All Vaccines</h6>
                                </div>

                            </div>
                        </div>

                        <div class="card-body p-0 m-0 mt-4">
                            <div class="table-responsive">
                                <h5 class="text-primary">Vaccine By Year & Day</h5>
                                <table class="table table-bordered table-striped">
                                    <tbody>
                                        @php
                                            $groupedByYear = $VaccinesByDay->groupBy(function($item) {
                                                return \Carbon\Carbon::parse($item->day)->year;
                                            });
                                        @endphp
                                        @foreach($groupedByYear as $year => $days)
                                            <tr>
                                                <th colspan="2" style="background-color: #e3f2fd;">Year: {{ $year }}</th>
                                            </tr>
                                            @foreach($days as $day)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($day->day)->format('d M') }}</td>
                                                    <td>{{ $day->count }}</td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="pages text-center">
                                    {{ $VaccinesByDay->links() }}
                                </div>
                            </div>

                            {{-- New: Show all Vaccine attached to each day --}}
                            <div class="mt-4">
                                <h5 class="text-info">Vaccine Attached to Each Day</h5>
                                @foreach ($VaccinesGroupedByDay as $day => $Vaccines)
                                    <div class="card p-0 m-0">
                                        <div class="card-header bg-light">
                                            <strong>{{ $day }}</strong>
                                        </div>
                                        <div class="card-body p-0 m-0 table-responsive">
                                            <table class="table table-bordered text-center p-0 m-0 table-responsive mb-2 pb-2 table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Horse</th>
                                                        <th>Image</th>
                                                        <th>Description</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($Vaccines as $vaccine)
                                                    <tr>
                                                        <td>{{ $vaccine->horse->name }}</td>
                                                        <td>
                                                            @if ($vaccine->image == null)
                                                            <img src="{{ asset ('assets/img/image.png') }}" style="width: 100px">
                                                            @else
                                                            <img src="{{ asset('assets/Uploads/Vaccines/'.$vaccine->image)}}" data-bs-toggle="modal" data-bs-target="#exampleModalImage{{ $vaccine->id }}" style="width: 50px">
                                                            @endif
                                                            <div class="modal fade" style="background-color: unset;" id="exampleModalImage{{ $vaccine->id }}" tabindex="-1" aria-labelledby="exampleModallabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                                                    <div class="modal-content">
                                                                        <div class="card-body">
                                                                            <img src="{{ asset('assets/Uploads/Vaccines/'.$vaccine->image)}}" class="img-fluid" alt="Vaccine Image">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>{{ $vaccine->description }}</td>

                                                        <td>
                                                            <a class="text-primary p-1 text-sm" data-bs-toggle="modal" data-bs-target="#exampleModall{{ $vaccine->id }}">edit</a>
                                                            <a class="text-danger p-1 text-sm" href="{{url('delete-vaccine/'.$vaccine->id)}}" onclick="return confirm('Are you sure you want to delete it ?')">delete</a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>

                                    @foreach ($Vaccines as $vaccine)
                                        <div class="modal fade" id="exampleModall{{ $vaccine->id }}" tabindex="-1" aria-labelledby="exampleModalLabell{{ $vaccine->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="card-body py-5">
                                                        <form action="{{ url('update-vaccine/'.$vaccine->id) }}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="mb-3">
                                                                <select name="horse_id" required class="form-control">
                                                                    <option value="">select horse</option>
                                                                    @foreach ($horses as $horse)
                                                                        <option value="{{ $horse->id }}" {{ $vaccine->horse_id == $horse->id ? 'selected' : '' }}>{{ $horse->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <textarea name="description" class="form-control" placeholder="description">{{ $vaccine->description }}</textarea>
                                                            </div>
                                                            <div class="mb-3">
                                                                @if ($vaccine->image)
                                                                    <img src="{{asset('assets/Uploads/Vaccines/'.$vaccine->image)}}" alt="vaccine image" style="width: 200px">
                                                                @endif
                                                                <label class="form-label">Photo</label>
                                                                <input type="file" name="image" class="form-control">
                                                            </div>
                                                            <button type="submit" class="btn btn-primary">Update</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endforeach
                                {{-- Pagination links for days --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        let i = "{{$key}}";

    $("#addMoreVaccines").click(function(e){
    e.preventDefault();
    i++;
    $(".itemvaccines").append('<div class="mb-3 col-6"><label for="">(Optional)</label><select class="form-select" required name="vaccinedesc['+i+'][horse_id]"><option value="">Select a Horse</option>@foreach ($horses as $item)<option value="{{ $item->id }}">{{ $item->name }}</option>@endforeach</select></div><div class="row"><div class="mb-3 col-11"><input type="text" required class="form-control"name="vaccinedesc['+i+'][description]" placeholder="Write  Description"/> <div class="col-6"><label for="">Choose Image</label><input type="file" name="vaccinedesc['+i+'][image]" required class="w-100"></div></div><div class="col-1"><button type="button" id ="remove" class="btn btn-danger float-end my-2 mx-2" title="remove"><i class="fa fa-trash"></i></button></div><hr></div>');
        });
    });
</script>
