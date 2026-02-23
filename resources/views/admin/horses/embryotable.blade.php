@extends('layouts.admin')
<title>Embryos Page</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-5">
                        <h4 class="text-white text-capitalize ps-3">Embryos Table</h4>
                    </div>
                </div>
                <div class="card-body m-2 table-responsive m-2 px-0 pb-2p-2">

                </div>
                @if ($embryosByMonth->isNotEmpty())
                    <div class="container-fluid p-2 mt-2">
                        <div class="row">
                            <div class="col-12">
                                <div class="card m-auto">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="">
                                                <h6 class="text-primary">All Embryos
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Embryos By Month --}}
                                    @if(isset($embryosByMonth) && $embryosByMonth->count())
                                        @php
                                            $embryosByYear = $embryosByMonth->groupBy('year');
                                        @endphp

                                        <div class="p-1 table-responsive">
                                            <h5 class="text-primary">Embryos By Month</h5>

                                            <div class="accordion" id="embryoYearAccordion">
                                                @foreach($embryosByYear as $year => $months)
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="heading-embryo-{{ $year }}">
                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-embryo-{{ $year }}" aria-expanded="false" aria-controls="collapse-embryo-{{ $year }}">
                                                                📅 Year: {{ $year }} (Total: {{ $months->sum('count') }} Embryos)
                                                            </button>
                                                        </h2>
                                                        <div id="collapse-embryo-{{ $year }}" class="accordion-collapse collapse" aria-labelledby="heading-embryo-{{ $year }}" data-bs-parent="#embryoYearAccordion">
                                                            <div class="accordion-body table-responsive">
                                                                @foreach($months as $month)
                                                                    <table class="table table-bordered table-striped text-center mb-3">

                                                                        <tbody>
                                                                            <tr>
                                                                                <td>{{ DateTime::createFromFormat('!m', $month->month)->format('F') }}</td>
                                                                                <td>({{ $month->count }} Embryos)</td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                @endforeach
                                                                <div class="pages text-center">
                                                                    {{ $embryosByMonth->links() }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif


                                    {{-- Embryos By Month --}}

                                    <div class="card-body table-responsive m-2 px-0 pb-2px-0 pb-2">
                                        <table class="table  text-center  table-striped mb-0">
                                            <tbody class="table  text-center mb-0">
                                                @foreach ($embryosByMonth as $month)
                                                        @php
                                                            $embryos = $allembryos->filter(function($item) use ($month) {
                                                                return $item->created_at->year == $month->year && $item->created_at->month == $month->month;
                                                            });

                                                        @endphp

                                                        @if($embryos->count())
                                                            <tr>
                                                                <th style="background-color: #4a91ee;">
                                                                    {{ DateTime::createFromFormat('!m', $month->month)->format('F') }} {{ $month->year }}
                                                                </th>
                                                                <th style="background-color: #4a91ee;">
                                                                    ({{ $embryos->count() }} Embryos)
                                                                </th>
                                                            </tr>
                                                            @foreach ($embryos as $embryoitem)
                                                            <tbody class="text-center">
                                                                <tr>
                                                                    <td>
                                                                        <a href="{{ url('Details/Embryo/'.$embryoitem->id) }}" class="text-sm text-success">{{ $embryoitem->localhorsename ?? 'Unknown' }}</a>
                                                                    </td>
                                                                     <td>
                                                                        @if ($embryoitem->status == 1)
                                                                            <span class="text-success">Embryo Successed</span>
                                                                        @elseif ($embryoitem->status == 2)
                                                                            <span class="text-primary">Embryo UnSuccessed</span>
                                                                        @else
                                                                            <span class="text-primary">Embryo Still in Progress</span>

                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                    <tr>
                                                                        <td>
                                                                            Day: {{ date('d h:iA', strtotime($embryoitem->created_at)) }}
                                                                        </td>
                                                                        <td>
                                                                            {{ $embryoitem->breeding->femaleHorse->name ?? '' }}
                                                                            {{ $embryoitem->breeding->maleHorse->name ?? '' }}
                                                                        </td>
                                                                    </tr>


                                                                </tbody>
                                                            @endforeach
                                                        @endif
                                                @endforeach
                                                <div class="pages text-center">
                                                    {{ $embryosByMonth->links() }}
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
