@extends('layouts.admin')
<title>Feeding & Bedding Page</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-info shadow-info border-radius-lg pt-4 pb-5">
                <h4 class="text-white text-capitalize ps-3">Feeding & Bedding Table</h4>
                <a class="text-white float-end p-1" data-bs-toggle="modal" data-bs-target="#exampleModalfeed">Add New Feeding Or Bedding</a>
              </div>
            </div>
            <div>
                <div class="modal fade" id="exampleModalfeed" tabindex="-1" aria-labelledby="exampleModallabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="card-body py-5">
                                <form action="{{ url('insert-feedingbedding') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <h3 class="text-center text-primary">Add New Feeding & Bedding</h3>

                                        <div class="col-6 mt-4">
                                            <select class="form-select" required name="supplier_id">
                                            <option value="">Select a Supplier</option>
                                            @foreach ($suppliers as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                        <div class="col-6 mt-4">
                                            <select class="form-select" required name="finance_id">
                                            <option value="">Select a Finance</option>
                                            @foreach ($finances as $item)
                                            <option value="{{ $item->id }}">{{ $item->amount }} / {{ $item->decamount }}
                                                /
                                                {{ date('h:iA d-M-y', timestamp: strtotime($item->created_at))}}
                                            </option>
                                            @endforeach
                                            </select>
                                        </div>
                                        <div class="col-4 mb-2">
                                            <input type="number" class="form-control" placeholder="Price" name="price">
                                        </div>
                                        <div class="col-4 mb-2">
                                            <input type="number" class="form-control" placeholder="Qty" required name="qty">
                                        </div>
                                        <div class="col-4 mb-2">
                                            <input type="number" class="form-control" placeholder="Paid" required name="paid">
                                        </div>
                                        <div class="col-12 mb-2">
                                            <input type="text" class="form-control" placeholder="Item" required name="item">
                                        </div>
                                        <div class="col-12 mb-2">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                @if ($feedbedByMonth->isNotEmpty())
                <div class="container-fluid p-2 mt-5">
                    <div class="row">
                        <div class="col-12">
                            <div class="card m-auto">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="">
                                            <h6 class="m-1 p-1 text-primary">All Feeding & Bedding
                                            </h6>
                                        </div>
                                    </div>
                                </div>

                                {{-- feedbed By Month --}}
                                    @if(isset($feedbedByMonth) && $feedbedByMonth->count())
                                        <div class="p-1 table-responsive">
                                            <h5 class="m-1 p-1 text-primary">Feeding & Bedding By Year & Month</h5>
                                            <table class="table table-bordered table-striped">

                                                <tbody>
                                                    @php
                                                        $groupedByYear = $feedbedByMonth->groupBy('year');
                                                    @endphp
                                                    @foreach($groupedByYear as $year => $months)
                                                        <tr>
                                                            <th colspan="2" style="background-color: #e3f2fd;">Year: {{ $year }}</th>
                                                        </tr>
                                                        @foreach($months as $month)
                                                            <tr>
                                                                <td>{{ DateTime::createFromFormat('!m', $month->month)->format('F') }}</td>
                                                                <td>{{ $month->count }}</td>
                                                            </tr>
                                                        @endforeach
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <div class="pages text-center">
                                                {{ $feedbedByMonth->links() }}
                                            </div>
                                        </div>
                                    @endif

                                <div class="card-body table-responsive m-2 px-0 pb-2px-0 pb-2">
                                    <table class="table  text-center  table-striped mb-0">
                                        <tbody class="table  text-center mb-0">
                                            @foreach ($feedbedByMonth as $month)
                                                    @php
                                                        $feedbed = $allfeedbed->filter(function($item) use ($month) {
                                                            return $item->created_at->year == $month->year && $item->created_at->month == $month->month;
                                                        });
                                                    @endphp

                                                    @if($feedbed->count())
                                                        <tr>
                                                            <th style="background-color: #4a91ee;">
                                                                {{ DateTime::createFromFormat('!m', $month->month)->format('F') }} {{ $month->year }}
                                                            </th>
                                                            <th style="background-color: #4a91ee;">
                                                                ({{ $feedbed->count() }} Feeding & Bedding)
                                                            </th>
                                                        </tr>
                                                        @foreach ($feedbed as $item)

                                                            <tbody>
                                                                    <tr>
                                                                        <th style="background-color : #2f78cc; color: #FFF;">
                                                                            <a class="text-white" href="{{ url(' Supplier/Details/'.$item->id) }}">{{$item->supplier->name}}</a>
                                                                            <br>
                                                                            <span class="text-white">Day: {{ date('d h:iA', strtotime($item->created_at))}}</span>
                                                                        </th>
                                                                        <th style="background-color : #2f78cc; color: #FFF;">
                                                                            <a data-bs-toggle="modal" data-bs-target="#exampleModal{{ $item->id }}" class="text-sm text-info">Edit</a>
                                                                            <a href="{{url('delete-feedingbedding/'.$item->id)}}"class="text-sm text-danger" onclick="return confirm('Are you sure you want to delete it ?')">Delete</a>
                                                                            <br>
                                                                            @if ($item->price < $item->paid)
                                                                                <span class="p-2 badge bg-success">Credit: {{ $item->paid - $item->price }} . EGP</span>
                                                                            @elseif ($item->price > $item->paid)
                                                                                <span class="p-2 badge bg-danger">Unpaid: {{ $item->price - $item->paid }} . EGP</span>
                                                                            @else
                                                                            <span class="text-white">Paid</span>
                                                                            @endif
                                                                        </th>
                                                                        <div class="modal fade" id="exampleModal{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModallabel" aria-hidden="true">
                                                                            <div class="modal-dialog">
                                                                                <div class="modal-content">
                                                                                    <div class="card-body py-5">
                                                                                        <form action="{{ url('update-feedingbedding/'.$item->id) }}" method="POST" enctype="multipart/form-data">
                                                                                            @csrf
                                                                                            @method('PUT')
                                                                                            <div class="row">
                                                                                                <h3 class="text-center text-primary">Edite : {{$item->item}}</h3>

                                                                                                <div class="col-6 mt-4">
                                                                                                    <select class="form-select" required name="supplier_id">
                                                                                                    <option value="{{ $item->supplier->id }}">{{ $item->supplier->name }}</option>
                                                                                                    @foreach ($suppliers as $supplier)
                                                                                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                                                                                    @endforeach
                                                                                                    </select>
                                                                                                </div>
                                                                                                <div class="col-6 mt-4">
                                                                                                    <select class="form-select" required name="finance_id">
                                                                                                    <option value="{{ $item->finance->id }}">{{$item->finance->amount}} / {{$item->finance->decamount}}
                                                                                                        /
                                                                                                        {{ date('h:iA d-M-y', timestamp: strtotime($item->finance->created_at))
                                                                                                        }}
                                                                                                    </option>
                                                                                                    @foreach ($finances as $finance)
                                                                                                    <option value="{{ $finance->id }}">{{ $finance->amount }} / {{ $finance->decamount }}
                                                                                                        /
                                                                                                        {{ date('h:iA d-M-y', timestamp: strtotime($item->finance->created_at))}}
                                                                                                    </option>
                                                                                                    @endforeach
                                                                                                    </select>
                                                                                                </div>
                                                                                                <div class="col-4 mb-2">
                                                                                                    <label for="">Price</label>
                                                                                                    <input type="number" class="form-control" value="{{$item->price}}" name="price">
                                                                                                </div>
                                                                                                <div class="col-4 mb-2">
                                                                                                    <label for="">Qty</label>
                                                                                                    <input type="number" class="form-control" value="{{$item->qty}}" required name="qty">
                                                                                                </div>
                                                                                                <div class="col-4 mb-2">
                                                                                                    <label for="">Paid</label>
                                                                                                    <input type="number" class="form-control" value="{{$item->paid}}" name="paid">
                                                                                                </div>
                                                                                                <div class="col-12 mb-2">
                                                                                                    <label for="">Item</label>
                                                                                                    <input type="text" class="form-control" value="{{$item->item}}" required name="item">
                                                                                                </div>
                                                                                                <div class="col-12 mb-2">
                                                                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                                                                </div>
                                                                                            </div>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            {{$item->item}}
                                                                        </td>
                                                                        @if ($item->feeding->count() == 0 && $item->bedding->count() == 0)
                                                                            <td><span class="m-1 p-1 text-danger">Unrecorded data</span></td>
                                                                        @else

                                                                        <td>
                                                                            @if ($item->feeding->count() != 0)
                                                                            <span class="text-primary">{{$item->feeding->count()}} Feeding</span>
                                                                            @endif
                                                                            @if ($item->bedding->count() != 0)
                                                                            <span class="text-primary">{{$item->bedding->count()}} Bedding</span>
                                                                            @endif
                                                                        </td>
                                                                        @endif
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            Qty :{{$item->qty}} Units
                                                                        </td>
                                                                        <td>
                                                                            Price :{{$item->price}} .EGP
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td>
                                                                            Storage: {{$item->decqty}} Units
                                                                        </td>
                                                                        <td>
                                                                            Unit Price :{{$item->unitprice}} .EGP
                                                                        </td>
                                                                    </tr>
                                                        @endforeach
                                                    @endif
                                            @endforeach
                                            <div class="pages text-center">
                                                {{ $feedbedByMonth->links() }}
                                            </div>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                    <div class="card-body text-center">
                        <h5 class="text-primary text-center">No Feeding & Bedding Found</h5>
                    </div>
                @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
