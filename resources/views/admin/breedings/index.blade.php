@extends('layouts.admin')
<title>Breedings Page</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-5">
                <h4 class="text-white text-capitalize ps-3">Breedings Table</h4>
                <h4 class="text-white text-capitalize pe-3 text-end">{{ $allbreedings->count() }} Breeding</h4>
              </div>
            </div>
            <div>
                <div class="card-header p-0 position-relative mt-n4 mt-3 z-index-2">
                    <div class="text-center">
                        <button type="button" class="btn btn-link float-end">
                            <a class="text-primary m-1" data-bs-toggle="modal" data-bs-target="#exampleModaladd-breeding">Add New Breeding</a>
                        </button>
                    </div>
                </div>
                <div class="modal fade" id="exampleModaladd-breeding" tabindex="-1" aria-labelledby="exampleModallabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="card-body py-5">
                                <form action="{{ url('insert-breeding') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <h3 class="text-center text-primary">Add New Breeding</h3>
                                        <div class="col-6 mt-4 mb-2">
                                            <label for="">(optional)</label>
                                            <select class="form-select"  name="femalehorse">
                                            <option value="">Select a Female Horse</option>
                                            @foreach ($horse as $item)
                                                @if ($item->gender == '0')
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endif
                                            @endforeach
                                            </select>
                                        </div>
                                        <div class="col-6 mt-4 mb-2">
                                            <Label>(optional)</Label>
                                            <select class="form-select" name="malehorse">
                                            <option value="">Select a Male Horse</option>
                                            @foreach ($horse as $item)
                                            @if ($item->gender == '1')
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endif
                                            @endforeach
                                            </select>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <label for="">Horse Name(optional)</label>
                                            <input type="text" class="form-control" name="horsename">
                                        </div>
                                        <div class="col-6 mb-2">
                                            <label for="">Stud Name(optional)</label>
                                            <input type="text" class="form-control" name="stud">
                                        </div>
                                        <div class="col-6 mt-2">
                                            <label for="">(optional)</label>
                                            <select class="form-select" name="user_id">
                                            <option value="">Select a Doctor</option>
                                            @foreach ($users as $item)
                                                @if ($item->major == 2 && !empty($item->name))
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endif
                                            @endforeach
                                            </select>
                                        </div>
                                         <div class="col-6 mb-2">
                                            <label for="">(optional)</label>
                                            <input type="number" class="form-control" placeholder="Cost ..." name="cost">
                                        </div>
                                       <div class="col-6 mt-2">
                                            <label for="">(optional)</label>
                                            <select class="form-select" name="finance_id">
                                                <option value="">Select a Finance</option>
                                                @foreach ($finances as $item)
                                                    <option value="{{ $item->id }}">({{ $item->description }} / {{ $item->decamount }})  /  {{ date('h:iA d-M-y', strtotime($item->created_at)) }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-6 mb-2">
                                            <label for="">Paid(optional)</label>
                                            <input type="number" class="form-control" placeholder="Paid ..." name="paid">
                                        </div>
                                        <div class="col-12 mb-2">
                                            <label for="">Description(optional)</label>
                                            <textarea name="description" class="form-control"></textarea>
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
            @if ($breedingsByMonth->isNotEmpty())
                <div class="container-fluid p-2 mt-5">
                    <div class="row">
                        <div class="col-12">
                            <div class="card m-auto">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="">
                                            <h6 class="text-primary">All Breedings
                                            </h6>
                                        </div>
                                    </div>
                                </div>

                                {{-- Breedings By Month --}}
                                    @if(isset($breedingsByMonth) && $breedingsByMonth->count())
                                        <div class="p-1 m-3 table-responsive">
                                            <h5 class="text-primary">Breedings By Year & Month</h5>
                                            <table class="table table-bordered table-striped">
                                                <tbody>
                                                    @php
                                                        $groupedByYear = $breedingsByMonth->groupBy('year');
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
                                                {{ $breedingsByMonth->links() }}
                                            </div>
                                        </div>
                                    @endif

                                <div class="card-body table-responsive m-2 px-0 pb-2px-0 pb-2">
                                    <table class="table  text-center  table-striped mb-0">
                                        <tbody class="table  text-center mb-0">
                                            @foreach ($breedingsByMonth as $month)
                                                @if ($month && isset($month->year) && isset($month->month))
                                                    @php
                                                        $breedings = $allbreedings->filter(function($item) use ($month) {
                                                            return $item->created_at &&
                                                                $item->created_at->year == $month->year &&
                                                                $item->created_at->month == $month->month;
                                                        });
                                                    @endphp

                                                    @if($breedings->count())
                                                        <tr>
                                                            <th style="background-color: #4a91ee;">
                                                                {{ DateTime::createFromFormat('!m', $month->month)->format('F') }} {{ $month->year }}
                                                            </th>
                                                            <th style="background-color: #4a91ee;">
                                                                ({{ $breedings->count() }} Breedings)
                                                            </th>
                                                        </tr>
                                                        @foreach ($breedings as $item)
                                                            <tr>
                                                                <th colspan="2" style="background-color: #4a91ee; color: #fff;">
                                                                    Day: {{ date('d h:iA', strtotime($item->created_at)) }}
                                                                    <a class="text-info text-sm" style="background-color: #4a91ee; " data-bs-toggle="modal" data-bs-target="#exampleModal{{ $item->id }}">  Edit</a>
                                                                    <a class="text-white text-sm" style="background-color: #4a91ee; " href="{{url('Details/Breeding/'.$item->id)}}">Details</a>
                                                                    <a class="text-danger text-sm" style="background-color: #4a91ee; " href="{{url('delete-breeding/'.$item->id )}}" onclick="return confirm('Are you sure you want to delete it ?')">Delete</a>
                                                                </th>
                                                            </tr>

                                                            <tbody>

                                                                <tr>
                                                                    <td>
                                                                        @if ($item->user)
                                                                        {{ optional($item->user)->name }}
                                                                        @else
                                                                        <span class="text-primary">Natural Breeding</span>
                                                                        @endif
                                                                    </td>

                                                                    <td>
                                                                        @if ($item->cost > $item->paid)
                                                                            <span class="text-danger">Debit: {{ $item->cost - $item->paid }}.EGP</span>
                                                                        @elseif ($item->cost < $item->paid)
                                                                            <span class="text-success">Credit: {{ $item->paid - $item->cost }}.EGP</span>
                                                                        @elseif ($item->paid == null)
                                                                                <span class="text-primary">Natural Breeding</span>
                                                                        @elseif (($item->cost == $item->paid) > 0 )
                                                                            <span class="text-info">Breeding Paid</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="2">
                                                                        @if ($item->status == 0)
                                                                            <i style="background-color: #4a91ee;" class="fa fa-stopwatch text-white"></i>
                                                                        @elseif ($item->status == 1)
                                                                            <i style="background-color: #4a91ee;" class="fa fa-check"></i>
                                                                        @elseif ($item->status == 2)
                                                                            <i style="background-color: #4a91ee;" class="fa fa-times text-danger"></i>
                                                                        @endif

                                                                        @if ($item->femaleHorse && is_object($item->femaleHorse))
                                                                            ({{ $item->femaleHorse->name }})
                                                                        @endif
                                                                        @if ($item->maleHorse && is_object($item->maleHorse))
                                                                            ({{ $item->maleHorse->name }})
                                                                        @endif

                                                                        @if ($item->horsename)
                                                                            {{ $item->horsename }} /
                                                                            {{ $item->stud }}
                                                                        @endif
                                                                        @if ($item->embryo->isNotEmpty())
                                                                            / <span class="text-success">Embryo</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                            <div class="">
                                                                <div class="modal fade" id="exampleModal{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModallabell" aria-hidden="true">
                                                                    <div class="modal-dialog">
                                                                        <div class="modal-content">
                                                                            <div class="card-body py-5">
                                                                                <form action="{{ url('update-breeding/'.$item->id ) }}" method="POST" enctype="multipart/form-data">
                                                                                    @csrf
                                                                                    @method('PUT')
                                                                                    <div class="row">
                                                                                        <h3 class="text-center text-primary">Edit breeding</h3>
                                                                                        <input type="hidden" name="breeding_id" value="{{ $item->id }}">
                                                                                        <div class="col-md-12 m-2">
                                                                                            <select name="status" class="form-select" required>
                                                                                                <option value="0">Still In Progress</option>
                                                                                                <option value="1" {{ $item->status == 1 ? 'selected' : '' }}>Pregnant</option>
                                                                                                <option value="2" {{ $item->status == 2 ? 'selected' : '' }}>Not Pregnant</option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-6 mb-2">
                                                                                            <select class="form-select" name="femalehorse">
                                                                                                @if ($item->femalehorse)
                                                                                                <option value="{{ $item->femalehorse }}">{{ optional($item->femaleHorse)->name ?? 'NotRecorded' }}</option>
                                                                                                <option value="">Remove Horse ??</option>
                                                                                                @else
                                                                                                <option value="">Select Horse</option>
                                                                                                @endif
                                                                                                @foreach ($horse as $itemh)
                                                                                                    @if ($itemh->gender == '0')
                                                                                                        <option value="{{ $itemh->id }}">{{ $itemh->name }}</option>
                                                                                                    @endif
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-6 mb-2">
                                                                                            <select class="form-select" name="malehorse">
                                                                                            @if ($item->malehorse)
                                                                                            <option value="{{ $item->malehorse }}">{{ optional($item->maleHorse)->name ?? 'NotRecorded' }}</option>
                                                                                            <option value="">Remove Horse ??</option>
                                                                                            @else
                                                                                            <option value="">Select Horse</option>
                                                                                            @endif
                                                                                            @foreach ($horse as $itemh)
                                                                                                    @if ($itemh->gender == '1')
                                                                                                        <option value="{{ $itemh->id }}">{{ $itemh->name }}</option>
                                                                                                    @endif
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </div>

                                                                                        <div class="col-6 mb-2">
                                                                                            <label for="">Horse Name (optional)</label>
                                                                                            <input type="text" class="form-control" value="{{ $item->horsename }}" name="horsename">
                                                                                        </div>
                                                                                        <div class="col-6 mb-2">
                                                                                            <label for="">Stud Name (optional)</label>
                                                                                            <input type="text" class="form-control" value="{{ $item->stud }}" name="stud">
                                                                                        </div>
                                                                                        <div class="col-6 mt-2">
                                                                                            <label for="">Doctor (optional)</label>
                                                                                            <select class="form-select" name="user_id">
                                                                                                <option value="{{ $item->user_id }}">{{ optional($item->user)->name ?? 'NotRecorded' }}</option>
                                                                                                @foreach ($users as $itemuser)
                                                                                                    @if ($itemuser->major == 2)
                                                                                                        <option value="{{ $itemuser->id }}">{{ $itemuser->name }}</option>
                                                                                                    @endif
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-6 mt-2">
                                                                                            <label for="">Coat(optional)</label>
                                                                                            <input type="number" class="form-control" placeholder="Cost ..." value="{{ $item->cost }}" name="cost">
                                                                                        </div>
                                                                                        <div class="col-6 mt-2">
                                                                                            <label for="">Finance (optional)</label>
                                                                                            <select class="form-select" name="finance_id">
                                                                                                ({{ optional($item->finance)->decamount }}.EGP
                                                                                                    {{ optional($item->finance)->description }})
                                                                                                    <br> {{ optional($item->finance)->created_at?->format('h:i A d/M') }}
                                                                                                @foreach ($finances as $finance)
                                                                                                    <option value="{{ $finance->id }}">({{ $finance->description }} / {{ $finance->decamount }})  /  {{ date('h:iA d-M-y', strtotime($finance->created_at)) }}</option>
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-6 mt-2">
                                                                                           <label for="">Paid (optional)</label>
                                                                                           <input type="number" class="form-control" placeholder="Paid ..." value="{{ $item->paid ?? 0 }}" name="paid">
                                                                                       </div>

                                                                                        <div class="col-12 mb-2">
                                                                                            <label for="">Description</label>
                                                                                            <textarea name="description" class="form-control">{{ $item->description }}</textarea>
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
                                                        @endforeach
                                                    @endif
                                                @endif
                                            @endforeach
                                            <div class="pages text-center">
                                                {{ $breedingsByMonth->links() }}
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
                    <h5 class="text-primary text-center">No Breedings Found</h5>
                </div>
            @endif

            <div class="container-fluid py-4">
                <div class="row mb-4">
                    @if ($breedingspregByMonth->isNotEmpty())
                    <div class="col-12 col-md-6 col-lg-6 mb-4">
                        <div class="card m-auto">
                            <div class="card-header">
                                <div class="row">
                                    <div class="">
                                        <h6 class="text-success">Success Breeding
                                            <i class="fa fa-check text-info" aria-hidden="true"></i>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body table-responsive m-2 px-0 pb-2px-0 pb-2">
                                <table class="table  text-center  table-striped mb-0">
                                    <tbody class="table  text-center mb-0">
                                         @foreach ($breedingspregByMonth as $month)
                                                @if ($month && isset($month->year) && isset($month->month))
                                                    @php
                                                        $breedings = $allbreedings->filter(function($item) use ($month) {
                                                            return $item->created_at &&
                                                                $item->created_at->year == $month->year &&
                                                                $item->created_at->month == $month->month &&
                                                                $item->status == 1;
                                                        });
                                                    @endphp

                                                    @if($breedings->count())
                                                        <tr>
                                                            <th style="background-color: #4a91ee;">
                                                                {{ DateTime::createFromFormat('!m', $month->month)->format('F') }} {{ $month->year }}
                                                            </th>
                                                            <th style="background-color: #4a91ee;">
                                                                ({{ $breedings->count() }} Breedings)
                                                            </th>
                                                        </tr>
                                                        @foreach ($breedings as $item)
                                                            <tr>
                                                                <th colspan="2" style="background-color: #4a91ee; color: #fff;">
                                                                    Day: {{ date('d h:iA', strtotime($item->created_at)) }}
                                                                    <a class="text-info text-sm" style="background-color: #4a91ee; " data-bs-toggle="modal" data-bs-target="#exampleModal{{ $item->id }}">  Edit</a>
                                                                    <a class="text-danger text-sm" style="background-color: #4a91ee; " href="{{url('delete-breeding/'.$item->id )}}" onclick="return confirm('Are you sure you want to delete it ?')">Delete</a>
                                                                    <a class="text-white text-sm" style="background-color: #4a91ee; " href="{{url('Details/Breeding/'.$item->id)}}">Details</a>
                                                                </th>
                                                            </tr>

                                                            <tbody>

                                                                <tr>
                                                                    <td>
                                                                        @if ($item->user)
                                                                        {{ optional($item->user)->name }}
                                                                        @else
                                                                        <span class="text-primary">Natural Breeding</span>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                       @if ($item->cost > $item->paid)
                                                                            <span class="text-danger">Debit: {{ $item->cost - $item->paid }}.EGP</span>
                                                                        @elseif ($item->cost < $item->paid)
                                                                            <span class="text-success">Credit: {{ $item->paid - $item->cost }}.EGP</span>
                                                                        @elseif ($item->paid == null)
                                                                                <span class="text-primary">Natural Breeding</span>
                                                                        @elseif (($item->cost == $item->paid) > 0 )
                                                                            <span class="text-info">Breeding Paid</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="2">
                                                                        @if ($item->status == 0)
                                                                            <i style="background-color: #4a91ee;" class="fa fa-stopwatch text-white"></i>
                                                                        @elseif ($item->status == 1)
                                                                            <i style="background-color: #4a91ee;" class="fa fa-check"></i>
                                                                        @elseif ($item->status == 2)
                                                                            <i style="background-color: #4a91ee;" class="fa fa-times text-danger"></i>
                                                                        @endif

                                                                        @if ($item->femaleHorse && is_object($item->femaleHorse))
                                                                            ({{ $item->femaleHorse->name }})
                                                                        @endif
                                                                        @if ($item->maleHorse && is_object($item->maleHorse))
                                                                            ({{ $item->maleHorse->name }})
                                                                        @endif

                                                                        @if ($item->horsename)
                                                                            {{ $item->horsename }} /
                                                                            {{ $item->stud }}
                                                                        @endif
                                                                        @if ($item->embryo)
                                                                            / <span class="text-success">Embryo</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>

                                                                <div class="">
                                                                    <div class="modal fade" id="exampleModal{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModallabell" aria-hidden="true">
                                                                        <div class="modal-dialog">
                                                                            <div class="modal-content">
                                                                                <div class="card-body py-5">
                                                                                    <form action="{{ url('update-breeding/'.$item->id ) }}" method="POST" enctype="multipart/form-data">
                                                                                        @csrf
                                                                                        @method('PUT')
                                                                                        <div class="row">
                                                                                            <h3 class="text-center text-primary">Edit breeding</h3>
                                                                                            <div class="col-md-12 m-2">
                                                                                                <select name="status" class="form-select" required>
                                                                                                    <option for="">Still In Progress</option>
                                                                                                    <option value="1" {{ $item->status == 1 ? 'selected' : '' }}>Pregnant</option>
                                                                                                    <option value="2" {{ $item->status == 2 ? 'selected' : '' }}>Not Pregnant</option>
                                                                                                </select>
                                                                                            </div>
                                                                                            <div class="col-6 mb-2">
                                                                                                <select class="form-select" name="femalehorse">
                                                                                                    <option value="{{ $item->femalehorse }}">{{ optional($item->femaleHorse)->name ?? 'NotRecorded' }}</option>
                                                                                                    @foreach ($horse as $itemh)
                                                                                                        @if ($itemh->gender == '0')
                                                                                                            <option value="{{ $itemh->id }}">{{ $itemh->name }}</option>
                                                                                                        @endif
                                                                                                    @endforeach
                                                                                                </select>
                                                                                            </div>
                                                                                            <div class="col-6 mb-2">
                                                                                                <select class="form-select" name="malehorse">
                                                                                                <option value="{{ $item->malehorse }}">{{ optional($item->maleHorse)->name ?? 'NotRecorded' }}</option>
                                                                                                    @foreach ($horse as $itemh)
                                                                                                        @if ($itemh->gender == '1')
                                                                                                            <option value="{{ $itemh->id }}">{{ $itemh->name }}</option>
                                                                                                        @endif
                                                                                                    @endforeach
                                                                                                </select>
                                                                                            </div>
                                                                                            <div class="col-6 mt-4 mb-2">
                                                                                                <select class="form-select" name="user_id">
                                                                                                    <option value="{{ $item->user_id }}">{{ optional($item->user)->name ?? 'NotRecorded' }}</option>
                                                                                                    @foreach ($users as $itemuser)
                                                                                                        @if ($itemuser->major == 2)
                                                                                                            <option value="{{ $itemuser->id }}">{{ $itemuser->name }}</option>
                                                                                                        @endif
                                                                                                    @endforeach
                                                                                                </select>
                                                                                            </div>
                                                                                            <div class="col-6 mb-2">
                                                                                                <label for="">Horse Name</label>
                                                                                                <input type="text" class="form-control" value="{{ $item->horsename }}" name="horsename">
                                                                                            </div>
                                                                                            <div class="col-6 mb-2">
                                                                                                <label for="">Stud Name</label>
                                                                                                <input type="text" class="form-control" value="{{ $item->stud }}" name="stud">
                                                                                            </div>
                                                                                            <div class="col-6 mb-2">
                                                                                                <label for="">(optional)</label>
                                                                                                <input type="number" class="form-control" placeholder="Cost ..." value="{{ $item->cost }}" name="cost">
                                                                                            </div>

                                                                                            <div class="col-12 mb-2">
                                                                                                <label for="">Description</label>
                                                                                                <textarea name="description" class="form-control">{{ $item->description }}</textarea>
                                                                                            </div>
                                                                                            <div class="col-6 mb-2">
                                                                                                <label for="">Paid</label>
                                                                                                <input type="number" class="form-control" placeholder="Paid ..." value="{{ $item->paid ?? 0 }}" name="paid">
                                                                                            </div>
                                                                                            <div class="col-6 mt-4">
                                                                                                <select class="form-select" name="finance_id">
                                                                                                    <option value="{{ $item->finance->id }}">({{ $item->finance->description }} / {{ $item->finance->decamount }})  /  {{ date('h:iA d-M-y', strtotime($item->finance->created_at)) }}</option>
                                                                                                    @foreach ($finances as $finance)
                                                                                                        <option value="{{ $finance->id }}">({{ $finance->description }} / {{ $finance->decamount }})  /  {{ date('h:iA d-M-y', strtotime($finance->created_at)) }}</option>
                                                                                                    @endforeach
                                                                                                </select>
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
                                                            </tbody>
                                                        @endforeach
                                                    @endif
                                                @endif
                                            @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if ($breedingsnotpregByMonth->isNotEmpty())
                    <div class="col-12 col-md-6 col-lg-6 mb-4">
                        <div class="card my-auto">
                            <div class="card-header">
                                <div class="row">
                                    <div class="">
                                        <h6 class="text-danger">Faild Breeding
                                            <i class="fa fa-times text-danger"></i>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body table-responsive m-2 px-0 pb-2px-0 pb-2">
                                <table class="table  text-center  table-striped mb-0">
                                    <tbody class="table  text-center mb-0">
                                         @foreach ($breedingsnotpregByMonth as $month)
                                                @if ($month && isset($month->year) && isset($month->month))
                                                    @php
                                                        $breedings = $allbreedings->filter(function($item) use ($month) {
                                                            return $item->created_at &&
                                                                $item->created_at->year == $month->year &&
                                                                $item->created_at->month == $month->month &&
                                                                $item->status == 2;
                                                        });
                                                    @endphp

                                                    @if($breedings->count())
                                                        <tr>
                                                            <th style="background-color: #4a91ee;">
                                                                {{ DateTime::createFromFormat('!m', $month->month)->format('F') }} {{ $month->year }}
                                                            </th>
                                                            <th style="background-color: #4a91ee;">
                                                                ({{ $breedings->count() }} Breedings)
                                                            </th>
                                                        </tr>
                                                        @foreach ($breedings as $item)
                                                            <tr>
                                                                <th colspan="2" style="background-color: #4a91ee; color: #fff;">
                                                                    Day: {{ date('d h:iA', strtotime($item->created_at)) }}
                                                                    <a class="text-info text-sm" style="background-color: #4a91ee; " data-bs-toggle="modal" data-bs-target="#exampleModal{{ $item->id }}">  Edit</a>
                                                                    <a class="text-danger text-sm" style="background-color: #4a91ee; " href="{{url('delete-breeding/'.$item->id )}}" onclick="return confirm('Are you sure you want to delete it ?')">Delete</a>
                                                                    <a class="text-white text-sm" style="background-color: #4a91ee; " href="{{url('Details/Breeding/'.$item->id)}}">Details</a>
                                                                </th>
                                                            </tr>

                                                            <tbody>

                                                                <tr>
                                                                    <td>
                                                                        @if ($item->user)
                                                                        {{ optional($item->user)->name }}
                                                                        @else
                                                                        <span class="text-primary">Natural Breeding</span>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                       @if ($item->cost > $item->paid)
                                                                            <span class="text-danger">Debit: {{ $item->cost - $item->paid }}.EGP</span>
                                                                        @elseif ($item->cost < $item->paid)
                                                                            <span class="text-success">Credit: {{ $item->paid - $item->cost }}.EGP</span>
                                                                        @elseif ($item->paid == null)
                                                                                <span class="text-primary">Natural Breeding</span>
                                                                        @elseif (($item->cost == $item->paid) > 0 )
                                                                            <span class="text-info">Breeding Paid</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="2">
                                                                        @if ($item->status == 0)
                                                                            <i style="background-color: #4a91ee;" class="fa fa-stopwatch text-white"></i>
                                                                        @elseif ($item->status == 1)
                                                                            <i style="background-color: #4a91ee;" class="fa fa-check"></i>
                                                                        @elseif ($item->status == 2)
                                                                            <i style="background-color: #4a91ee;" class="fa fa-times text-danger"></i>
                                                                        @endif

                                                                        @if ($item->femaleHorse && is_object($item->femaleHorse))
                                                                            ({{ $item->femaleHorse->name }})
                                                                        @endif
                                                                        @if ($item->maleHorse && is_object($item->maleHorse))
                                                                            ({{ $item->maleHorse->name }})
                                                                        @endif

                                                                        @if ($item->horsename)
                                                                            {{ $item->horsename }} /
                                                                            {{ $item->stud }}
                                                                        @endif
                                                                        @if ($item->embryo)
                                                                            / <span class="text-success">Embryo</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>

                                                                <div class="">
                                                                    <div class="modal fade" id="exampleModal{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModallabell" aria-hidden="true">
                                                                        <div class="modal-dialog">
                                                                            <div class="modal-content">
                                                                                <div class="card-body py-5">
                                                                                    <form action="{{ url('update-breeding/'.$item->id ) }}" method="POST" enctype="multipart/form-data">
                                                                                        @csrf
                                                                                        @method('PUT')
                                                                                        <div class="row">
                                                                                            <h3 class="text-center text-primary">Edit Breeding</h3>
                                                                                            <div class="col-md-12 m-2">
                                                                                                <select name="status" class="form-select" required>
                                                                                                    <option for="">Still In Progress</option>
                                                                                                    <option value="1" {{ $item->status == 1 ? 'selected' : '' }}>Pregnant</option>
                                                                                                    <option value="2" {{ $item->status == 2 ? 'selected' : '' }}>Not Pregnant</option>
                                                                                                </select>
                                                                                            </div>
                                                                                            <div class="col-6 mb-2">
                                                                                                <select class="form-select" name="femalehorse">
                                                                                                    <option value="{{ $item->femalehorse }}">{{ optional($item->femaleHorse)->name ?? 'NotRecorded' }}</option>
                                                                                                    @foreach ($horse as $itemh)
                                                                                                        @if ($itemh->gender == '0')
                                                                                                            <option value="{{ $itemh->id }}">{{ $itemh->name }}</option>
                                                                                                        @endif
                                                                                                    @endforeach
                                                                                                </select>
                                                                                            </div>
                                                                                            <div class="col-6 mb-2">
                                                                                                <select class="form-select" name="malehorse">
                                                                                                <option value="{{ $item->malehorse }}">{{ optional($item->maleHorse)->name ?? 'NotRecorded' }}</option>
                                                                                                    @foreach ($horse as $itemh)
                                                                                                        @if ($itemh->gender == '1')
                                                                                                            <option value="{{ $itemh->id }}">{{ $itemh->name }}</option>
                                                                                                        @endif
                                                                                                    @endforeach
                                                                                                </select>
                                                                                            </div>
                                                                                            <div class="col-6 mt-4 mb-2">
                                                                                                <select class="form-select" name="user_id">
                                                                                                    <option value="{{ $item->user_id }}">{{ optional($item->user)->name ?? 'NotRecorded' }}</option>
                                                                                                    @foreach ($users as $itemuser)
                                                                                                        @if ($itemuser->major == 2)
                                                                                                            <option value="{{ $itemuser->id }}">{{ $itemuser->name }}</option>
                                                                                                        @endif
                                                                                                    @endforeach
                                                                                                </select>
                                                                                            </div>
                                                                                            <div class="col-6 mb-2">
                                                                                                <label for="">Horse Name</label>
                                                                                                <input type="text" class="form-control" value="{{ $item->horsename }}" name="horsename">
                                                                                            </div>
                                                                                            <div class="col-6 mb-2">
                                                                                                <label for="">Stud Name</label>
                                                                                                <input type="text" class="form-control" value="{{ $item->stud }}" name="stud">
                                                                                            </div>
                                                                                            <div class="col-6 mb-2">
                                                                                                <label for="">(optional)</label>
                                                                                                <input type="number" class="form-control" placeholder="Cost ..." value="{{ $item->cost }}" name="cost">
                                                                                            </div>

                                                                                            <div class="col-12 mb-2">
                                                                                                <label for="">Description</label>
                                                                                                <textarea name="description" class="form-control">{{ $item->description }}</textarea>
                                                                                            </div>
                                                                                            <div class="col-6 mb-2">
                                                                                                <label for="">Paid</label>
                                                                                                <input type="number" class="form-control" placeholder="Paid ..." value="{{ $item->paid ?? 0 }}" name="paid">
                                                                                            </div>
                                                                                            <div class="col-6 mt-4">
                                                                                                <select class="form-select" name="finance_id">
                                                                                                    <option value="{{ $item->finance->id }}">({{ $item->finance->description }} / {{ $item->finance->decamount }})  /  {{ date('h:iA d-M-y', strtotime($item->finance->created_at)) }}</option>
                                                                                                    @foreach ($finances as $finance)
                                                                                                        <option value="{{ $finance->id }}">({{ $finance->description }} / {{ $finance->decamount }})  /  {{ date('h:iA d-M-y', strtotime($finance->created_at)) }}</option>
                                                                                                    @endforeach
                                                                                                </select>
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
                                                            </tbody>
                                                        @endforeach
                                                    @endif
                                                @endif
                                            @endforeach

                                    </tbody>
                                </table>
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
