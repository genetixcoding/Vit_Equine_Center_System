@extends('layouts.admin')
<title>Suppliers Details</title>
@section('content')

<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-md-6">
            <div class="card my-4 h-100">
                <div class="card-header bg-gradient-info shadow-primary border-radius-lg pt-4 pb-2">
                    <h5 class="text-white">Medical Invoice Suppliers</h5>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped mb-4">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($medicalSuppliers as $supplier)
                                <tr>
                                    <td><a  href="{{ url('Supplier/Details/'.$supplier->id) }}">{{$supplier->name }}</a></td>
                                    <td>{{ $supplier->description }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="card-header bg-gradient-success shadow-primary border-radius-lg pt-2 pb-2 mt-4">
                        <h5 class="text-white"> Supplies Invoice Suppliers</h5>
                    </div>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($suppliesSuppliers as $supplier)
                                <tr>
                                    <td><a  href="{{ url('Supplier/Details/'.$supplier->id) }}">{{$supplier->name }}</a></td>
                                    <td>{{ $supplier->description }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card my-4 h-100">
                <div class="card-header bg-gradient-info shadow-primary border-radius-lg pt-4 pb-2">
                    <h5 class="text-white">Feeding Suppliers</h5>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped mb-4">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($feedingSuppliers as $supplier)
                                <tr>
                                    <td><a  href="{{ url('Supplier/Details/'.$supplier->id) }}">{{$supplier->name }}</a></td>
                                    <td>{{ $supplier->description }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="card-header bg-gradient-success shadow-primary border-radius-lg pt-2 pb-2 mt-4">
                        <h5 class="text-white">Bedding Suppliers</h5>
                    </div>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($beddingSuppliers as $supplier)
                                <tr>
                                    <td><a  href="{{ url('Supplier/Details/'.$supplier->id) }}">{{$supplier->name }}</a></td>
                                    <td>{{ $supplier->description }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
