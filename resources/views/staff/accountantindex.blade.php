@extends('layouts.admin')
<title>Vit Equine Center System</title>
<!-- Section: Design Block -->
@section('content')
<div class="container-fluid py-4">
    <div class="card-body">
        <div class="row  mb-4">
            <div class="mb-4 col-6 col-md">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">system_update_alt</i>
                        </div>
                        <div class="text-end pt-1">
                            <h4 class="mb-0  text-primary font-weight-bolder"></h4>
                            <p class="text-sm mt-5 text-capitalize">all Studs count</p>
                            <a href="{{ url('Studs/Counts')}}">
                            <p class="text-sm mb-0 text-capitalize"><span class="text-info text-sm font-weight-bolder">more_information</span></p>
                            </a>
                        </div>
                    </div>
                        <hr class="dark horizontal my-0">
                </div>
            </div>
            <div class="mb-4 col-6 col-md">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">system_update_alt</i>
                        </div>
                        <div class="text-end pt-1">
                            <h4 class="mb-0  text-primary font-weight-bolder"></h4>
                            <p class="text-sm mt-5 text-capitalize">all visits count</p>
                            <a href="{{ url('VisitsCount')}}">
                            <p class="text-sm mb-0 text-capitalize"><span class="text-info text-sm font-weight-bolder">more_information</span></p>
                            </a>
                        </div>
                    </div>
                        <hr class="dark horizontal my-0">
                </div>
            </div>
            <div class="col-6 col-md mb-3">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">event_note</i>
                        </div>
                        <div class="text-end pt-1">
                            <h4 class="mb-0  text-primary font-weight-bolder"></h4>
                            <p class="text-sm mt-5 text-capitalize">all breedings count</p>
                            <a href="{{ url('BreedingsCount')}}">
                            <p class="text-sm mb-0 text-capitalize"><span class="text-info text-sm font-weight-bolder">more_information</span></p>
                            </a>
                        </div>
                    </div>
                        <hr class="dark horizontal my-0">
                </div>
            </div>
            <div class="col-6 col-md mb-3">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">event_note</i>
                        </div>
                        <div class="text-end pt-1">
                            <h4 class="mb-0  text-primary font-weight-bolder"></h4>
                            <p class="text-sm mt-5 text-capitalize">all embryo count</p>
                            <a href="{{ url('EmbryosCount')}}">
                            <p class="text-sm mb-0 text-capitalize"><span class="text-info text-sm font-weight-bolder">more_information</span></p>
                            </a>
                        </div>
                    </div>
                        <hr class="dark horizontal my-0">
                </div>
            </div>
            <div class="col-12 col-md mb-3">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">contact_mail</i>
                        </div>
                        <div class="text-end pt-1">
                            <h4 class="mb-0  text-primary font-weight-bolder"></h4>
                            <p class="text-sm mt-5 text-capitalize">doctors accounts</p>
                            <a href="{{ url('Doctors/Details')}}">
                            <p class="text-sm mb-0 text-capitalize"><span class="text-info text-sm font-weight-bolder">more_information</span></p>
                            </a>
                        </div>
                    </div>
                        <hr class="dark horizontal my-0">
                </div>
            </div>
        </div>

        <div class="row  mb-4">
            <div class="col-6 col-md-3 mb-2">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <a href="{{ url('FinancialsCount') }}">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        financials Count
                                    </div>
                                </div>
                            </a>
                            <div class="col-auto">
                                <div class="h6 mb-0 font-weight-bold text-gray-300"><i class="fa fa-money fa-1x text-gray-300"></i> Attatched</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-2">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <a href="{{ url('SalaryCount')}}">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        salary count
                                    </div>
                                </div>
                            </a>
                            <div class="col-auto">
                                <div class="h6 mb-0 font-weight-bold text-gray-300"><i class="fa fa-money fa-1x text-gray-300"></i> Attatched</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-2">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <a href="{{ url('ExpensesCount')}}">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        expenses count
                                    </div>
                                </div>
                            </a>
                            <div class="col-auto">
                                <div class="h6 mb-0 font-weight-bold text-gray-300"><i class="fa fa-shopping-basket fa-1x text-gray-300"></i> Attatched</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-2">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <a href="{{ url('FeedingBeddingCount') }}">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Feeding & Bedding count
                                    </div>
                                </div>
                            </a>
                            <div class="col-auto">
                                <div class="h6 mb-0 font-weight-bold text-gray-300"><i class="fa fa-calendar fa-1x text-gray-300"></i> Attatched</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3 mb-2">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <a href="{{ url('PharmacyCount') }}">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        pharmacy count
                                    </div>
                                </div>
                            </a>
                            <div class="col-auto">
                                <div class="h6 mb-0 font-weight-bold text-gray-300"><i class="fa fa-calendar fa-1x text-gray-300"></i> Attatched</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-2">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <a href="{{ url('Suppliers') }}">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Suppliers
                                    </div>
                                </div>
                            </a>
                            <div class="col-auto">
                                <div class="h6 mb-0 font-weight-bold text-gray-300"><i class="fa fa-calendar fa-1x text-gray-300"></i> Attatched</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-2">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <a href="{{ url('InternalInvoicesCount') }}">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Internal Invoices Count
                                    </div>
                                </div>
                            </a>
                            <div class="col-auto">
                                <div class="h6 mb-0 font-weight-bold text-gray-300"><i class="fa fa-calendar fa-1x text-gray-300"></i> Attatched</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3 mb-2">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <a href="{{ url('ExternalInvoicesCount') }}">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        External Invoices Count
                                    </div>
                                </div>
                            </a>
                            <div class="col-auto">
                                <div class="h6 mb-0 font-weight-bold text-gray-300"><i class="fa fa-calendar fa-1x text-gray-300"></i> Attatched</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
