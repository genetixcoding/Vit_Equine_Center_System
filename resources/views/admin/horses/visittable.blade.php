@extends('layouts.admin')
<title>{{ $horse->name }} Visits Page</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-5">
                        <h4 class="text-white text-capitalize ps-3">{{ $horse->name }} <br> Visits Table</h4>
                    </div>
                </div>
                <div class="card-body m-2 table-responsive m-2 px-0 pb-2p-2">

                </div>
                @if ($visitsByMonth->isNotEmpty())
                    <div class="container-fluid p-2 mt-2">
                        <div class="row">
                            <div class="col-12">
                                <div class="card m-auto">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="">
                                                <h6 class="text-primary">All Visits
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                    @if(isset($visitsByMonth) && $visitsByMonth->count())
                                        <div class="accordion" id="yearAccordion">
                                            @php
                                                // نجمعهم حسب السنة
                                                $visitsByYear = $visitsByMonth->groupBy('year');
                                            @endphp

                                            @foreach($visitsByYear as $year => $months)
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="heading-{{ $year }}">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#collapse-{{ $year }}"
                                                            aria-expanded="false"
                                                            aria-controls="collapse-{{ $year }}">
                                                            📅 Year: {{ $year }} (Total: {{ $months->sum('count') }} Visits)
                                                        </button>
                                                    </h2>
                                                    <div id="collapse-{{ $year }}" class="accordion-collapse collapse"
                                                        aria-labelledby="heading-{{ $year }}"
                                                        data-bs-parent="#yearAccordion">
                                                        <div class="accordion-body">
                                                            <table class="table table-bordered table-striped">

                                                                <tbody>
                                                                    @foreach($months as $month)
                                                                        <tr>
                                                                            <td>{{ DateTime::createFromFormat('!m', $month->month)->format('F') }}
                                                                               : {{ $month->count }} Visits
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <div class="card-body table-responsive m-2 px-0 pb-2px-0 pb-2">
                                        <table class="table  text-center  table-striped mb-0">
                                            <tbody class="table  text-center mb-0">
                                                @foreach ($visitsByMonth as $month)
                                                        @php
                                                            $visits = $allvisits->filter(function($item) use ($month) {
                                                                return $item->created_at->year == $month->year && $item->created_at->month == $month->month;
                                                            });

                                                        @endphp

                                                        @if($visits->count())
                                                            <tr>
                                                                <th style="background-color: #4a91ee;">
                                                                    {{ DateTime::createFromFormat('!m', $month->month)->format('F') }} {{ $month->year }}
                                                                </th>
                                                                <th style="background-color: #4a91ee;">
                                                                    ({{ $visits->count() }} visits)
                                                                </th>
                                                            </tr>
                                                            @foreach ($visits as $visititem)
                                                            <tr>
                                                                <th style="background-color: #4a91ee;color: #fff;">
                                                                    {{ $visititem->visit->visitdescs->count() }} Case in Visit at Day:{{ date('d h:iA', strtotime($visititem->created_at)) }}
                                                                </th>
                                                                <th style="background-color: #4a91ee;color: #fff;">
                                                                      {{ $visititem->visit->user->name ?? 'Unknown User' }}
                                                                </th>
                                                            </tr>
                                                            <tbody class="text-center">

                                                                <tr>
                                                                    <td>
                                                                        @if ($visititem->image == null)
                                                                        <img src="{{ asset ('assets/img/image.png') }}" style="width: 100px">
                                                                        @else
                                                                        <img src="{{ asset('assets/img/'.$visititem->image)}}" data-bs-toggle="modal" data-bs-target="#exampleModalImage{{ $visititem->id }}" style="width: 50px">
                                                                        @endif
                                                                        <div class="modal fade" style="background-color: unset;" id="exampleModalImage{{ $visititem->id }}" tabindex="-1" aria-labelledby="exampleModallabel" aria-hidden="true">
                                                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                                                <div class="modal-content">
                                                                                    <div class="card-body">
                                                                                        <img src="{{ asset('assets/img/'.$visititem->image)}}" class="img-fluid" alt="Horse Image">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                     {{ $visititem->case }}
                                                                        <br>
                                                                        Case Pricr
                                                                        {{ $visititem->caseprice }}.EGP
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                     {{ $visititem->description }}
                                                                    </td>
                                                                    <td>
                                                                     {{ $visititem->treatment }}
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                            @endforeach
                                                        @endif
                                                @endforeach
                                                <div class="pages text-center">
                                                    {{ $visitsByMonth->links() }}
                                                </div>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
