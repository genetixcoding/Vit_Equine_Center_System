@extends('layouts.admin')
<title>Add Treatment</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-info border-radius-lg pt-4 pb-3">
                      <h4 class="text-white text-capitalize ps-3">Treatments Table</h4>
                    </div>
                  </div>
                  <div class="card mx-2 mt-6">

                      <div class="card-body px-0 pb-2 m-2">
                          <form action="{{ url('insert-treatment') }}" method="POST" enctype="multipart/form-data">
                              @csrf


                              <div class="row">
                                @if (Auth::user()->major == 2)
                                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                @else
                                <div class="mb-3 col-12 w-100">
                                    <label for="">(Optional)</label>
                                    <select class="form-select"  name="user_id">
                                        <option value="{{ Auth::user()->id }}">Select a Doctor</option>
                                        @foreach ($doctors as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                                  <div class="mb-2 col-6 col-md-5">
                                    <label for="">(Optional)</label>
                                      <select class="form-select" name="horse_id">
                                          <option value="">Select a Horse</option>
                                          @foreach ($horses as $item)
                                          <option value="{{ $item->id }}">{{ $item->name }}</option>
                                          @endforeach
                                      </select>
                                  </div>
                                  <div class="mb-2 col-6 col-md-5">
                                    <label for="">(Optional)</label>
                                      <select class="form-select" name="embryo_id">
                                          <option value="">Select Embryo</option>
                                          @foreach ($embryos as $item)
                                          <option value="{{ $item->id }}">{{ $item->localhorsename }}</option>
                                          @endforeach
                                      </select>
                                  </div>
                              </div>
                              <div class="itemtreatments">
                                  <div class="row">
                                      @php
                                          $key = 0;
                                      @endphp


                                      <div class=" mb-2 col-6 ">
                                          <select class="form-select" required name="treatmentdesc[0][pharmacy_id]">
                                                <option value="">Select Medicine</option>
                                              @foreach ($pharmacy as $item)
                                              <option value="{{ $item->id }}">{{ $item->item }} / {{ $item->unitqty }} {{ $item->type }}</option>
                                              @endforeach
                                          </select>
                                      </div>
                                      <div class=" mb-2 col-6">
                                          <input type="text" required class="form-control" name="treatmentdesc[0][description]" placeholder="Insert Description">
                                      </div>
                                      <div class=" mb-2 col-5">
                                            <label for=""></label>
                                          <input type="number" required class="form-control" placeholder="Insert Doses" name="treatmentdesc[0][qty]">
                                      </div>
                                      <div class=" mb-2 col-5">
                                            <label for=""></label>
                                          <input type="text" required class="form-control" placeholder="Insert Type" name="treatmentdesc[0][type]">
                                      </div>
                                      <div class=" p-2 col-2">
                                          <button type="button" id ="addMoreTreatments" class="btn btn-primary" title="add more row">+</button>
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
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        let i = parseInt("{{$key}}");

        $("#addMoreTreatments").click(function(e){
            e.preventDefault();
            i++;
            $(".itemtreatments").append('<div class="row addlRow"><div class="mt-2 col-6"><select class="form-select" required name="treatmentdesc['+i+'][pharmacy_id]"><option value="">Select Medicine</option>@foreach ($pharmacy as $item)<option value="{{ $item->id }}">{{ $item->item }}</option>@endforeach</select></div><div class="mt-1 col-6"><input type="text" required class="form-control" name="treatmentdesc['+i+'][description]" placeholder="Insert Description"></div><div class="mb-2 col-5"><input type="number" required class="form-control" name="treatmentdesc['+i+'][qty]" placeholder="Insert Doses"></div><div class="mb-2 col-5"><input type="text" required class="form-control" name="treatmentdesc['+i+'][type]" placeholder="Insert Type"></div><div class="p-1 col-2"><button type="button" id="remove" class="btn btn-danger text-center" title="remove"><i class="fa fa-trash"></i></button></div><hr></div>');
        });
    });
</script>

@endsection
