@extends('layouts.admin')
<title>Bedding</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-info shadow-info border-radius-lg pt-4 pb-5">
                <h4 class="text-white text-capitalize ps-3">Bedding Table</h4>
                <a class="text-white float-end p-1" data-bs-toggle="modal" data-bs-target="#exampleModalbedding">Add New Bedding</a>
              </div>
            </div>
            <div>
                <div class="modal fade" id="exampleModalbedding" tabindex="-1" aria-labelledby="exampleModallabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="card-body py-5">
                                <form action="{{ url('insert-bedding') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <h3 class="text-center text-primary">Add New Bedding</h3>
                                        <div class="col-8 m-auto mt-4">
                                            <select class="form-select" required name="feedbed_id">
                                            <option value="">Feeding&Bedding</option>
                                            @foreach ($feedingbedings as $itemfb)
                                            <option value="{{ $itemfb->id }}">
                                                {{ $itemfb->item }} / {{ $itemfb->qty }} / {{ $itemfb->decqty }}
                                                 / {{ date('h:iA d-M-y', strtotime($itemfb->created_at)) }}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                        <div class="col-8 mt-2">
                                            <select class="form-select" required name="horse_id">
                                            <option value="">Select a Horse</option>
                                            @foreach ($horses as $itemh)
                                            <option value="{{ $itemh->id }}">{{ $itemh->name }}</option>
                                            @endforeach
                                            </select>
                                        </div>

                                        <div class="col-4 mb-2">
                                            <input type="number" step="0.01" class="form-control" placeholder="Qty" required name="qty">
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

            @if ($beddingByMonth->isNotEmpty())
            <div class="container-fluid p-2 mt-5">
                <div class="row">
                    <div class="col-12">
                        <div class="card m-auto">
                            <div class="card-header">
                                <div class="row">
                                    <div class="">
                                        <h6>All Bedding Descriptions</h6>
                                        <h6 class="text-primary">Total Bedding: {{ $allbedding->count() }}</h6>
                                    </div>
                                </div>
                            </div>
                            @if(isset($beddingByMonth) && $beddingByMonth->count())
                                        <div class="p-1 table-responsive">
                                            <h5 class="text-primary">Bedding By Year & Month</h5>
                                            <table class="table table-bordered table-striped">
                                                <tbody>
                                                    @php
                                                        $groupedByYear = $beddingByMonth->groupBy('year');
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
                                                {{ $beddingByMonth->links() }}
                                            </div>
                                        </div>
                                    @endif
                            <div class="card-body table-responsive m-2 px-0 pb-2px-0 pb-2">
                                <table class="table  text-center  table-striped mb-0">
                                    <tbody class="table  text-center mb-0">
                                        @foreach ($beddingByMonth as $month)
                                            @php
                                                $monthBeddings = $allbedding->filter(function($item) use ($month) {
                                                    return $item->created_at->year == $month->year && $item->created_at->month == $month->month;
                                                });
                                                $groupedMonthBeddings = $monthBeddings->groupBy('feedbed_id');
                                            @endphp

                                            @if($monthBeddings->count())
                                                <tr>
                                                    <th colspan="5" style="background-color: #4a91ee;">
                                                        {{ DateTime::createFromFormat('!m', $month->month)->format('F') }} {{ $month->year }}
                                                        ({{ $monthBeddings->count() }} beddings)
                                                    </th>
                                                </tr>
                                                @foreach ($groupedMonthBeddings as $feedbedId => $beddingsGroup)
                                                    @php
                                                        $feedbed = $feedingbedings->firstWhere('id', $feedbedId);
                                                    @endphp
                                                    @if($feedbed)
                                                        <tr>
                                                            <th style="background-color: #2f78cc; color: #FFF;">
                                                                {{ $feedbed->item }}
                                                                <br>
                                                                (Qty:{{ $feedbed->qty }} /  :{{ $feedbed->decqty }})
                                                            </th>
                                                            <th style="background-color: #2f78cc; color: #FFF;">
                                                                {{ date('h:iA d-M-y', strtotime($feedbed->created_at)) }}
                                                            </th>
                                                        </tr>
                                                        @foreach ($beddingsGroup as $itembed)
                                                            <tr>
                                                                <td>{{ $itembed->item }}</td>
                                                                <td>{{ $itembed->horse->name ?? '' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{ date('h:iA d/M/y', strtotime($itembed->created_at)) }}</td>
                                                                <td>
                                                                    {{ $itembed->qty }} Unit
                                                                    <a data-bs-toggle="modal" data-bs-target="#exampleModalBedding{{ $itembed->id }}" class="text-sm text-info">Edit</a>
                                                                    <a href="{{url('delete-bedding/'.$itembed->id)}}" class="text-sm text-danger" onclick="return confirm('Are you sure you want to delete it ?')">Delete</a>
                                                                </td>
                                                            </tr>
                                                            <!-- Modal for editing bedding -->
                                                            <div class="modal fade" id="exampleModalBedding{{ $itembed->id }}" tabindex="-1" aria-labelledby="exampleModalBeddingLabel{{ $itembed->id }}" aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="card-body py-5">
                                                                            <form action="{{ url('update-bedding/'.$itembed->id) }}" method="POST" enctype="multipart/form-data">
                                                                                @csrf
                                                                                @method('PUT')
                                                                                <div class="row">
                                                                                    <h3 class="text-center text-primary">Edit</h3>
                                                                                    <div class="col-6 mt-4 mb-2">
                                                                                        <select class="form-select" required name="feedbed_id">
                                                                                            <option value="{{ $itembed->feedingbeding->id }}">{{ $itembed->feedingbeding->item }} / {{ $itembed->feedingbeding->qty }} / {{ $itembed->feedingbeding->decqty }} / {{ date('h:iA d-M-y', strtotime($itembed->feedingbeding->created_at)) }}</option>
                                                                                            @foreach ($feedingbedings as $feedbed)
                                                                                                <option value="{{ $feedbed->id }}">{{ $feedbed->item }} / {{ $feedbed->qty }} / {{ $feedbed->decqty }} / {{ date('h:iA d-M-y', strtotime($feedbed->created_at)) }}</option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-6 mt-4">
                                                                                        <select class="form-select" required name="horse_id">
                                                                                            <option value="{{ $itembed->horse->id }}">{{ $itembed->horse->name }}</option>
                                                                                            @foreach ($horses as $item)
                                                                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-8 mb-2">
                                                                                        <label for="">Item</label>
                                                                                        <input type="text" class="form-control" value="{{$itembed->item}}" required name="item">
                                                                                    </div>
                                                                                    <div class="col-4 mb-2">
                                                                                        <label for="">Qty</label>
                                                                                        <input type="number" class="form-control" value="{{$itembed->qty}}" required name="qty">
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
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach
                                        <div class="pages text-center">
                                            {{ $beddingByMonth->links() }}
                                        </div>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
                <div class="text-primary text-center">
                   <h3>No Beddings Found</h3>
                </div>
            @endif
          </div>

        </div>
    </div>
</div>
@endsection
