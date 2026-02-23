@extends('layouts.admin')
<title>Visit Details Page</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-5">
                        <h4 class="text-white text-capitalize ps-3">Visits Details</h4>
                        <h4 class="text-white text-capitalize pe-3 text-end">
                            {{ $visit->visitdescs->count() }} Hores Visits
                        </h4>
                    </div>
                </div>

                <div class="card-body table-responsive m-2 px-0 pb-2p-2">
                    {{-- Edit Main Visit --}}
                    <table class="table table-bordered text-center table-responsive m-2 px-0 pb-2table-striped p-3">
                        @foreach ($visit->visitdescs as $item)
                        <div class="modal fade" id="exampleModal{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ url('update-visitdesc/'.$item->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="row m-5">
                                            <div class="col-md-6">
                                                <label for="">Case</label>
                                                <input type="text" class="form-control" value="{{ $item->case }}"  name="case">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">Case Price</label>
                                                <input type="text" class="form-control" value="{{ $item->caseprice }}" name="caseprice">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">Description</label>
                                                <textarea name="description" rows="5" class="form-control">{{ $item->description }}</textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">Treatment</label>
                                                <textarea name="treatment" rows="5" class="form-control">{{ $item->treatment }}</textarea>
                                            </div>
                                            <div class="col-md-12 m-2">
                                                @if ($item->image)
                                                    <img src="{{asset('assets/Uploads/Visits/'.$item->image)}}" alt="image" style="width: 50px">
                                                @endif
                                                <input type="file" name="image" class="form-control">
                                            </div>
                                            <div class="col-md-12 m-3">
                                                <button type="submit" class="btn btn-primary">update</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                            <tbody>
                                <tr>
                                    <td>
                                        {{ $item->horse->name }}
                                        <br>
                                        {{ $item->case }}
                                        <br>
                                        @if ($item->caseprice)
                                            {{ $item->caseprice }}.EGP Case
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->image == null)
                                        <img src="{{ asset ('assets/img/image.png') }}" style="width: 100px">
                                        @else
                                        <img src="{{ asset('assets/Uploads/Visits/'.$item->image)}}" data-bs-toggle="modal" data-bs-target="#exampleModalImage{{ $item->id }}" style="width: 50px">
                                        @endif
                                        <div class="modal fade" style="background-color: unset;" id="exampleModalImage{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModallabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content">
                                                    <div class="card-body">
                                                        <img src="{{ asset('assets/Uploads/Visits/'.$item->image)}}" class="img-fluid" alt="Horse Image">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <a class="text-info m-1 text-sm" data-bs-toggle="modal" data-bs-target="#exampleModal{{ $item->id }}">Edit</a>
                                        <a class="text-danger m-1 text-sm" href="{{url('delete-visitdesc/' .$item->id)}}" onclick="return confirm('Are you sure you want to delete it ?')">Delete</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ $item->description ?? 'Unknown' }}</td>
                                    <td>{{ $item->treatment ?? 'Unknown' }}</td>
                                </tr>
                            </tbody>
                            <br>
                            @endforeach
                        <thead>
                            <tr>
                                <th style="background-color: #4a91ee">

                                    {{ $visit->users->name }} /
                                    {{ $visit->stud->name }}

                                    <br>
                                    @if ($visit->totalprice == 0 && $visit->visitdescs->sum('caseprice') == 0)
                                        Stud's Doctor
                                    @elseif ($visit->totalprice + $visit->visitdescs->sum('caseprice') == $visit->visitdescs->sum('caseprice'))
                                        {{ $visit->visitdescs->sum('caseprice') }}.EGP Case Price
                                    @else
                                        {{ $visit->totalprice + $visit->visitdescs->sum('caseprice') }}.EGP Total Price
                                    @endif
                                </th>
                                <th style="background-color: #4a91ee">
                                    {{ date(' h:iA  d/M/y', strtotime($visit->created_at)) }}
                                    <br>
                                    <a class="text-info m-1 text-sm" data-bs-toggle="modal" data-bs-target="#exampleModalvisit{{ $visit->id }}">  Edit</a>
                                    <a class="text-danger m-1 text-sm" href="{{url('delete-visit/'.$visit->id)}}" onclick="return confirm('Are you sure you want to delete this expenses?')"onclick="return confirm('Are you sure you want to delete it ?')">Delete</a>
                                    <div class="modal fade" id="exampleModalvisit{{ $visit->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ url('update-visit/'.$visit->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="row  m-2 text-center">
                                                        <div class="mt-3 col-12">
                                                            <label for="">Select Doctor</label>
                                                            <select class="form-select" name="user_id">
                                                                <option value="{{ $visit->users->id }}">{{ $visit->users->name }}</option>
                                                                @foreach ($users as $item)
                                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="my-2 col-6">
                                                            <label for="">Visit Price</label>
                                                            <input type="number" class="form-control" value="{{ $visit->visitprice }}" name="visitprice">
                                                        </div>
                                                        <div class="my-2 col-6">
                                                            <label for="">Discount</label>
                                                            <input type="number" class="form-control" value="{{ $visit->discount }}" name="discount">
                                                        </div>
                                                        <div class="my-2 col-6">
                                                            <label for="">Select Financial</label>
                                                            <select class="form-select" required name="finance_id">
                                                                @if ($visit->finance)
                                                                    <option value="{{ $visit->finance->id }}">{{ $visit->finance->amount }}  /  {{ date('h:iA d-M-y', strtotime($visit->finance->created_at)) }}</option>
                                                                @else
                                                                    <option value="">No Finance Selected</option>
                                                                @endif
                                                                @foreach ($finances as $item)
                                                                        <option value="{{ $item->id }}">{{ $item->amount }} / {{ $item->decamount }} /  {{ date('h:iA d-M-y', strtotime($item->created_at)) }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="my-2 col-6">
                                                            <label for="">Paid</label>
                                                            <input type="number" class="form-control" value="{{ $visit->paid }}" name="paid">
                                                        </div>
                                                        <div class="m-2">
                                                            <button type="submit" class="btn btn-primary">Submit</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </th>
                            </tr>
                            <tr>
                                <th colspan="2" style="background-color: #4a91ee;color: white;">
                                    @if ($visit->finance)
                                        ({{ $visit->finance->description }} / {{ $visit->finance->amount }}) / {{ date('h:iA d-M-y', strtotime($visit->finance->created_at)) }}
                                    @else
                                        No Financial Record
                                    @endif
                                </th>
                            </tr>
                            <tr>
                                <th style="background-color: #4a91ee;color: white;">
                                    @if ($visit->visitprice == null)
                                        No Visit price
                                    @else
                                        {{ $visit->visitprice }}.EGP Visit
                                    @endif
                                    <br>
                                    @if ($visit->discount == null)
                                        No Discount
                                    @else
                                        {{ $visit->discount }}.EGP Discount
                                    @endif

                                </th>

                                <th style="background-color: #4a91ee;color: white;">
                                    @if ($visit->paid == null)
                                    <span class="text-danger">No Payment</span>
                                    @elseif ($visit->paid == $visit->totalprice + $visit->visitdescs->sum('caseprice'))
                                    <span class="text-success">All Paid</span>
                                    @else
                                     Paid {{ $visit->paid }}.EGP
                                    @endif
                                    <br>
                                    @if ($visit->totalprice + $visit->visitdescs->sum('caseprice') == $visit->paid)
                                    <span class="px-1 badag bg-success">All Paid</span>
                                    @elseif ($visit->totalprice + $visit->visitdescs->sum('caseprice') > $visit->paid)
                                    <span class="px-1 badag bg-danger">
                                        {{ abs($visit->totalprice + $visit->visitdescs->sum('caseprice') - $visit->paid)}}.EGP
                                        Debit</span>
                                    @else
                                        <span class="px-1 badag bg-success">
                                        Credit :{{ abs($visit->totalprice + $visit->visitdescs->sum('caseprice') - $visit->paid)}}.EGP
                                         </span>
                                    @endif
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
