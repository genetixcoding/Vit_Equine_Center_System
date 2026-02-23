@extends('layouts.admin')
<title>{{ $horse->name }} Treatments Page</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-5">
                        <h4 class="text-white text-capitalize ps-3">{{ $horse->name }} <br> Treatments Table</h4>
                    </div>
                </div>
                @if ($treatmentsByMonth->isNotEmpty())
                <div class="card-body m-2 table-responsive m-2 px-0 pb-2p-2">
                    <div class="container-fluid p-2 mt-2">
                        <div class="row">
                                <div class="col-12">
                                    <div class="card m-auto">
                                        <div class="card-header">
                                            <div class="row">
                                                <div class="">
                                                    <h6 class="text-primary">All Treatments
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                            {{-- Treatments By Month --}}
                                        @if(isset($treatmentsByMonth) && $treatmentsByMonth->count())
                                            @php
                                                $treatmentsByYear = $treatmentsByMonth->groupBy('year');
                                            @endphp

                                            <div class="m-4">
                                                <h5 class="text-primary">Treatments By Month</h5>

                                                <div class="accordion" id="treatmentYearAccordion">
                                                    @foreach($treatmentsByYear as $year => $months)
                                                        <div class="accordion-item">
                                                            <h2 class="accordion-header" id="heading-treatment-{{ $year }}">
                                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-treatment-{{ $year }}" aria-expanded="false" aria-controls="collapse-treatment-{{ $year }}">
                                                                    📅 Year: {{ $year }} (Total: {{ $months->sum('count') }} Treatments)
                                                                </button>
                                                            </h2>
                                                            <div id="collapse-treatment-{{ $year }}" class="accordion-collapse collapse" aria-labelledby="heading-treatment-{{ $year }}" data-bs-parent="#treatmentYearAccordion">
                                                                <div class="accordion-body table-responsive">
                                                                    @foreach($months as $month)
                                                                        <table class="table table-bordered table-striped text-center mb-3">

                                                                            <tbody>
                                                                                <tr>
                                                                                    <td>{{ DateTime::createFromFormat('!m', $month->month)->format('F') }}</td>
                                                                                    <td>({{ $month->count }} Treatments)</td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    @endforeach
                                                                    <div class="pages text-center">
                                                                        {{ $treatmentsByMonth->links() }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif


                                    <div class="card-body table-responsive m-2 px-0 pb-2px-0 pb-2">
                                        <table class="table  text-center  table-striped mb-0">
                                            <tbody class="table  text-center mb-0">
                                                @foreach ($treatmentsByMonth as $month)
                                                        @php
                                                            $treatments = $alltreatments->filter(function($item) use ($month) {
                                                                return $item->created_at->year == $month->year && $item->created_at->month == $month->month;
                                                            });

                                                        @endphp

                                                        @if($treatments->count())
                                                            <tr>
                                                                <th colspan="2" style="background-color: #4a91ee;">
                                                                    {{ DateTime::createFromFormat('!m', $month->month)->format('F') }} {{ $month->year }}
                                                                </th>
                                                                <th colspan="2" style="background-color: #4a91ee;">
                                                                    ({{ $treatments->count() }} treatments)
                                                                </th>
                                                            </tr>
                                                            @foreach ($treatments as $treatment)
                                                                <tr>
                                                                    <td colspan="2" style="background-color: #4a91ee; color: #fff;">
                                                                        Day: {{ date('d h:iA', strtotime($treatment->created_at)) }}
                                                                    </td>
                                                                    <td colspan="2" style="background-color: #4a91ee; color: #fff;">
                                                                        {{ $treatment->user ? $treatment->user->name : 'No User' }}
                                                                    </td>
                                                                </tr>
                                                                @foreach ($treatment->treatmentdesc as $treatmentitem)
                                                                <tbody class="text-center">
                                                                    <tr>
                                                                        <td>
                                                                            {{ $treatmentitem->pharmacy ? $treatmentitem->pharmacy->item : 'No Medicine' }}
                                                                        </td>
                                                                        <td>
                                                                            Dose: {{ $treatmentitem->qty }}
                                                                        </td>
                                                                        <td>
                                                                            {{ $treatmentitem->description }}
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                                @endforeach
                                                            @endforeach
                                                        @endif
                                                @endforeach
                                                <div class="pages text-center">
                                                    {{ $treatmentsByMonth->links() }}
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
