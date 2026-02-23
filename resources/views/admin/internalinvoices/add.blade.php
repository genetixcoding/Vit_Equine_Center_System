@extends('layouts.admin')
<title>Add Invoices Page </title>

@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-32">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-5">
                        <h4 class="text-white text-capitalize pe-3 ps-3">Add Invoices</h4>
                        <a class="text-white float-end p-1" data-bs-toggle="modal" data-bs-target="#exampleModal">Insert Supplier</a>
                    </div>
                </div>

                {{-- Insert Supplies Invoice  --}}
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="card-body py-5">
                                <div class="m-3">
                                    <form action="{{ url('insert-supplier') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="mb-3 col-6 col-md-6">
                                                <h4>Insert Supplier</h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 mb-2">
                                                <input type="text" required class="form-control" placeholder="Insert Name" name="name">
                                            </div>
                                            <div class="col-12 mb-2">
                                                <textarea name="description" class="form-control" rows="3" placeholder="Insert Description"></textarea>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary float-end mt-3">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pb-2 m-3" id="medical">
                    <a href="#supplies">
                        <h5>
                            Insert Supplies Invoice ??!
                        </h5>
                    </a>
                    <div class="card-body">
                        <div>
                            <form action="{{ url('insert-medicalinternalinvoice') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="mb-3 col-6 col-md-6">
                                        <h4>Insert Medical Invoice</h4>
                                    </div>
                                    <div class="mb-3 col-6 col-md-6">
                                        <select class="form-select" required name="supplier_id">
                                            <option value="">Select Supplier</option>
                                            @foreach ($suppliers as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="items">
                                    <div class="row">
                                        @php
                                            $key = 0;
                                        @endphp
                                        <div class="col-12 col-md-7 mb-2">
                                            <input type="text" required placeholder="Item" class="form-control" name="medinternalinvoices[0][item]">
                                        </div>
                                        <div class="col-5 col-md-2">
                                            <input type="number" required placeholder="Qty" class="form-control" name="medinternalinvoices[0][qty]">
                                        </div>
                                        <div class="col-5 col-md-2">
                                            <input type="number" required placeholder="Unit Price" class="form-control" name="medinternalinvoices[0][price]">
                                        </div>
                                        <div class="col-1">
                                            <i id="addMore" class="fa fa-plus mt-4 text-primary float-end"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class=" mt-4 col-8">
                                   <select class="form-select" required name="finance_id">
                                   <option value="">Select Finance</option>
                                   @foreach ($finances as $item)
                                   <option value="{{ $item->id }}">{{ $item->amount }} / {{ $item->decamount }}  /  {{ date('h:iA d-M-y', strtotime($item->created_at)) }}</option>
                                   @endforeach
                                   </select>
                               </div>
                                <div class="col-6">
                                    <input type="number" placeholder="Paid" required class="form-control" name="paid">
                                </div>
                                <button type="submit" class="btn btn-primary mt-4 float-end">Submit</button>

                            </form>
                        </div>
                    </div>
                </div>
                <hr>
                <br>
                <br>
                <br>
                <div class="card-body px-0 pb-2 m-3" id="supplies">
                    <a href="#medical">
                        <h5>
                            Insert Medical Invoice ??!
                        </h5>
                    </a>
                    <div class="card-body">
                        <div>
                            <form action="{{ url('insert-suppliesinternalinvoice') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="mb-3 col-6 col-md-6">
                                        <h4>insert supplies_invoice</h4>
                                    </div>
                                    <div class="mb-3 col-6 col-md-6">
                                        <select class="form-select" required name="supplier_id">
                                            <option value="">Select Supplier</option>
                                            @foreach ($suppliers as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="supitems">
                                    <div class="row">
                                        @php
                                            $key = 0;
                                        @endphp
                                        <div class="col-12 col-md-7 mb-2">
                                            <input type="text" required placeholder=" Item" class="form-control" name="supinternalinvoices[0][item]">
                                        </div>
                                        <div class="col-5 col-md-2">
                                            <input type="number" required placeholder="Qty" class="form-control" name="supinternalinvoices[0][qty]">
                                        </div>
                                        <div class="col-5 col-md-2">
                                            <input type="number" required placeholder="Unit Price" class="form-control" name="supinternalinvoices[0][price]">
                                        </div>
                                        <div class="col-1">
                                            <i id="addMoreSup" class="fa fa-plus mt-4 text-primary float-end"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class=" mt-4 col-8">
                                   <select class="form-select" required name="finance_id">
                                   <option value="">Select Finance</option>
                                   @foreach ($finances as $item)
                                   <option value="{{ $item->id }}">{{ $item->amount }} / {{ $item->decamount }}  /  {{ date('h:iA d-M-y', strtotime($item->created_at)) }}</option>
                                   @endforeach
                                   </select>
                               </div>
                                <div class="col-6">
                                    <input type="number" placeholder="Paid" required class="form-control" name="paid">
                                </div>
                                <button type="submit" class="btn btn-primary float-end mt-4">Submit</button>
                            </form>
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
        $("#addMore").click(function(e){
            e.preventDefault();
            i++;
            // Use correct field names: item, qty, price
            $(".items").append('<div class="row"><div class="mt-2 col-10 col-md-7"><input type="text" min="1" class="form-control" name="medinternalinvoices['+i+'][item]" required/></div><div class="mt-2 col-5 col-md-2"><input type="number" min="1" class="form-control" name="medinternalinvoices['+i+'][qty]" /></div><div class="mt-2 col-5 col-md-2"><input type="number" min="1" class="form-control" name="medinternalinvoices['+i+'][price]" /></div><div class="col-1"><i id="addMoreSup" class="fa fa-plus mt-4 text-primary float-end"></i><i id="remove" class="fa fa-trash text-danger mt-3 float-end"></i></div></div>');
        });

        $("#addMoreSup").click(function(e){
            e.preventDefault();
            i++;
            // Use correct field names: item, qty, price
            $(".supitems").append('<div class="row"><div class="mt-2 col-10 col-md-7"><input type="text" min="1" class="form-control" name="supinternalinvoices['+i+'][item]" required></div><div class="mt-2 col-5 col-md-2"><input type="number" min="1" class="form-control" name="supinternalinvoices['+i+'][qty]" /></div><div class="mt-2 col-5 col-md-2"><input type="number" min="1" class="form-control" name="supinternalinvoices['+i+'][price]" /></div><div class="col-1"><i id="addMoreSup" class="fa fa-plus mt-4 text-primary float-end"></i><i id="remove" class="fa fa-trash text-danger mt-3 float-end"></i></div></div>');
        });

        $(document).on("click","#remove",function(e){
            e.preventDefault();
            $(this).parent().parent().remove();
        });
    });
</script>
