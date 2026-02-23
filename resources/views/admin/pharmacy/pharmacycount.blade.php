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
                                            <td colspan="2">
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

                                        </tr>

                                        <tr>
                                            <td>
                                               Basic: {{ $item->unitqty }}  {{ $item->type }}
                                            </td>
                                            <td>
                                                Storage: {{$item->unit}} {{ $item->type }}
                                            </td>
                                         </tr>
                                    </tbody>

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
                                            <th colspan="3" style="background-color : #2f78cc; color: #FFF;">{{$item->item}}</th>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="text-danger">Out of Stock</span>
                                            </td>
                                            <td>
                                                {{$item->unit}} Unit
                                            </td>
                                            <td>
                                                {{$item->price}} .EGP
                                            </td>
                                        </tr>
                                    </tbody>

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
        </div>
    </div>
</div>
@endsection
